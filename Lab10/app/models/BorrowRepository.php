<?php
class BorrowRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ===============================
    // DANH SÁCH PHIẾU MƯỢN
    // ===============================
    public function all() {
        $sql = "
            SELECT b.id, br.full_name, b.borrow_date, b.note
            FROM borrows b
            JOIN borrowers br ON br.id = b.borrower_id
            ORDER BY b.id DESC
        ";
        return $this->pdo->query($sql)->fetchAll();
    }

    // ===============================
    // TẠO PHIẾU MƯỢN + CHI TIẾT (TRANSACTION)
    // ===============================
    public function createBorrow($borrowerId, $date, $note, $items) {
        try {
            $this->pdo->beginTransaction();

            // 1. tạo phiếu mượn
            $stmt = $this->pdo->prepare(
                "INSERT INTO borrows(borrower_id, borrow_date, note)
                 VALUES (?, ?, ?)"
            );
            $stmt->execute([$borrowerId, $date, $note]);
            $borrowId = $this->pdo->lastInsertId();

            // 2. xử lý từng sách
            foreach ($items as $bookId => $qty) {
                if ($qty <= 0) continue;

                // kiểm tra tồn kho
                $stmt = $this->pdo->prepare(
                    "SELECT qty FROM books WHERE id = ? FOR UPDATE"
                );
                $stmt->execute([$bookId]);
                $book = $stmt->fetch();

                if (!$book || $book['qty'] < $qty) {
                    throw new Exception("Sách ID $bookId không đủ số lượng");
                }

                // lưu chi tiết mượn
                $stmt = $this->pdo->prepare(
                    "INSERT INTO borrow_items(borrow_id, book_id, qty)
                     VALUES (?, ?, ?)"
                );
                $stmt->execute([$borrowId, $bookId, $qty]);

                // trừ tồn kho
                $stmt = $this->pdo->prepare(
                    "UPDATE books SET qty = qty - ? WHERE id = ?"
                );
                $stmt->execute([$qty, $bookId]);
            }

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return $e->getMessage();
        }
    }

    // ===============================
    // LẤY PHIẾU MƯỢN THEO ID
    // ===============================
    public function find($id) {
        $stmt = $this->pdo->prepare(
            "SELECT b.*, br.full_name
             FROM borrows b
             JOIN borrowers br ON br.id = b.borrower_id
             WHERE b.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ===============================
    // CHI TIẾT SÁCH TRONG PHIẾU
    // ===============================
    public function items($borrowId) {
        $stmt = $this->pdo->prepare(
            "SELECT bi.qty, bo.title, bo.author
             FROM borrow_items bi
             JOIN books bo ON bo.id = bi.book_id
             WHERE bi.borrow_id = ?"
        );
        $stmt->execute([$borrowId]);
        return $stmt->fetchAll();
    }

    // ===============================
    // XÓA PHIẾU MƯỢN (OPTIONAL)
    // ===============================
    public function delete($id) {
        $this->pdo->prepare("DELETE FROM borrow_items WHERE borrow_id=?")
                  ->execute([$id]);

        $this->pdo->prepare("DELETE FROM borrows WHERE id=?")
                  ->execute([$id]);
    }
}
