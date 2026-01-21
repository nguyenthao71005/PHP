<?php
require_once '../app/core/Database.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT COUNT(*) FROM students");

echo "Total students: " . $stmt->fetchColumn();
