<?php
class BorrowerRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all() {
        return $this->pdo->query(
            "SELECT * FROM borrowers ORDER BY id DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO borrowers(full_name, phone) VALUES (?,?)"
        );
        return $stmt->execute([$data['full_name'], $data['phone']]);
    }
}
