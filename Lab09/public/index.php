<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Controller & action mặc định
$c = $_GET['c'] ?? 'student';
$a = $_GET['a'] ?? 'index';

// Tên controller
$controllerName = ucfirst($c) . 'Controller';

// Đường dẫn controller (CHUẨN)
$controllerPath = dirname(__DIR__) . '/app/controllers/' . $controllerName . '.php';

// Kiểm tra file controller
if (!file_exists($controllerPath)) {
    die("Controller not found: " . $controllerPath);
}

// Load controller
require_once $controllerPath;

// Kiểm tra class
if (!class_exists($controllerName)) {
    die("Class not found: " . $controllerName);
}

// Khởi tạo controller
$controller = new $controllerName();

// Kiểm tra action
if (!method_exists($controller, $a)) {
    die("Action not found: " . $a);
}

// Gọi action
$controller->$a();
