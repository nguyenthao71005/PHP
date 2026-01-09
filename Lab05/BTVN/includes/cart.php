<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cart_add(int $id, int $qty = 1): void {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
}

function cart_update(int $id, int $qty): void {
    if ($qty <= 0) {
        unset($_SESSION['cart'][$id]);
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
}

function cart_remove(int $id): void {
    unset($_SESSION['cart'][$id]);
}

function cart_clear(): void {
    $_SESSION['cart'] = [];
}
