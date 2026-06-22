<?php
session_start();
require_once 'mysql/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'shopping_cart.php';
    header('Location: ../pages/login.php');
    exit;
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;

if ($product_id <= 0) {
    header('Location: ../pages/product_catalog.php');
    exit;
}

$customer_id = $_SESSION['user_id'];
$pdo = getDBConnection();

if (!$pdo) {
    header('Location: ../pages/product_catalog.php');
    exit;
}

try {
    // Check if product exists
    $stmt = $pdo->prepare("SELECT product_id, product_name FROM my_Products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        $_SESSION['cart_message'] = 'Product not found.';
        header('Location: ../pages/product_catalog.php');
        exit;
    }
    
    // Check if item already in cart
    $stmt = $pdo->prepare("SELECT quantity FROM my_ShoppingCart WHERE customer_id = ? AND product_id = ?");
    $stmt->execute([$customer_id, $product_id]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Update quantity
        $stmt = $pdo->prepare("UPDATE my_ShoppingCart SET quantity = quantity + 1, added_date = NOW() WHERE customer_id = ? AND product_id = ?");
        $stmt->execute([$customer_id, $product_id]);
    } else {
        // Insert new item
        $stmt = $pdo->prepare("INSERT INTO my_ShoppingCart (customer_id, product_id, quantity, added_date) VALUES (?, ?, 1, NOW())");
        $stmt->execute([$customer_id, $product_id]);
    }
    
    $_SESSION['cart_message'] = 'Item added to cart successfully!';
    
} catch (PDOException $e) {
    error_log("Add to cart error: " . $e->getMessage());
    $_SESSION['cart_message'] = 'Error adding item to cart.';
}

// Redirect back
if ($category_id > 0) {
    header("Location: ../pages/category_products.php?category_id=" . $category_id);
} else {
    header("Location: ../pages/product_catalog.php");
}
exit;
?>
