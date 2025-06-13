<?php
session_start();

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

header("Location: cart.php");
exit();
