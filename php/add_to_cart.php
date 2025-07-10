<?php
session_start();
header('Content-Type: application/json');




if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please log in to add items to your cart.'
    ]);
    exit;
}


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.html"); // Redirect to your login page
    exit();
}


$required = ['product_id', 'product_name', 'product_price', 'product_image', 'quantity'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo json_encode([
            'success' => false,
            'message' => "Missing required field: $field"
        ]);
        exit;
    }
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);
$product_name = htmlspecialchars($_POST['product_name'], ENT_QUOTES, 'UTF-8');
$product_price = floatval($_POST['product_price']);
$product_image = htmlspecialchars($_POST['product_image'], ENT_QUOTES, 'UTF-8');


if ($product_id <= 0 || $quantity <= 0 || $product_price <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product data'
    ]);
    exit;
}

$host = 'localhost';
$dbname = 'food_delievery';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $stmt = $pdo->prepare("
        INSERT INTO cart_item (
            user_id, 
            product_id, 
            product_name, 
            product_price, 
            product_image, 
            quantity
        ) VALUES (
            :user_id, 
            :product_id, 
            :product_name, 
            :product_price, 
            :product_image, 
            :quantity
        )
        ON DUPLICATE KEY UPDATE 
            quantity = quantity + VALUES(quantity)
    ");

    $stmt->execute([
        ':user_id' => $user_id,
        ':product_id' => $product_id,
        ':product_name' => $product_name,
        ':product_price' => $product_price,
        ':product_image' => $product_image,
        ':quantity' => $quantity
    ]);

    $countStmt = $pdo->prepare("SELECT SUM(quantity) AS total_count FROM cart_item WHERE user_id = ?");
    $countStmt->execute([$user_id]);
    $cartCount = $countStmt->fetchColumn() ?? 0;

    echo json_encode([
        'success' => true,
        'cart_count' => (int)$cartCount
    ]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred'
    ]);
}