<?php
// Lưu flash message vào session
function set_flash($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

// Lấy flash message rồi xóa
function get_flash($key) {
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}
