<?php
class BooksRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function all($kw = '', $sort = 'created_at', $dir = 'DESC') {
        $sql = "SELECT * FROM books
                WHERE title LIKE :kw OR author LIKE :kw
                ORDER BY $sort $dir";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['kw' => "%$kw%"]);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO books(title,author,price,qty) VALUES (?,?,?,?)"
        );
        $stmt->execute([
            $data['title'],
            $data['author'],
            $data['price'],
            $data['qty']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare(
            "UPDATE books SET title=?, author=?, price=?, qty=? WHERE id=?"
        );
        $stmt->execute([
            $data['title'],
            $data['author'],
            $data['price'],
            $data['qty'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id=?");
        $stmt->execute([$id]);
    }
}
