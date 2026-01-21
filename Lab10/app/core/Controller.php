<?php
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layout.php';
    }

    protected function redirect($query) {
        header("Location: index.php?$query");
        exit;
    }
}
