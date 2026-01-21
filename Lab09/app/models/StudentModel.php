<?php
require_once dirname(__DIR__) . '/core/Database.php';

class StudentModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        return $this->db
            ->query("SELECT * FROM students ORDER BY id DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO students(code, full_name, email, dob)
                VALUES (:code, :full_name, :email, :dob)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':code' => $data['code'],
            ':full_name' => $data['full_name'],
            ':email' => $data['email'],
            ':dob' => $data['dob'] ?: null
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM students WHERE id=?");
        return $stmt->execute([$id]);
    }
}
