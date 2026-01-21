<?php
require_once __DIR__ . '/../models/StudentModel.php';

class StudentController {

    public function index() {
        require_once __DIR__ . '/../views/students/index.php';
    }

    public function api() {
        header('Content-Type: application/json; charset=utf-8');

        $model  = new StudentModel();
        $action = $_GET['action'] ?? 'list';

        // LIST
        if ($action === 'list') {
            echo json_encode([
                'success' => true,
                'data' => $model->all()
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // CREATE
        if ($action === 'create') {
            $ok = $model->create($_POST);

            echo json_encode([
                'success' => $ok
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // DELETE
        if ($action === 'delete') {
            $ok = $model->delete($_POST['id'] ?? 0);

            echo json_encode([
                'success' => $ok
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // DEFAULT
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);
        exit;
    }
}
