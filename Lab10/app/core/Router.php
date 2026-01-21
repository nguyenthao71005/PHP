<?php
class Router {
    public static function run() {
        $c = $_GET['c'] ?? 'books';
        $a = $_GET['a'] ?? 'index';

        $controllerName = ucfirst($c) . 'Controller';
        $file = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (!file_exists($file)) die("Controller not found");

        require_once $file;
        $controller = new $controllerName();

        if (!method_exists($controller, $a)) die("Action not found");

        $controller->$a();
    }
}
