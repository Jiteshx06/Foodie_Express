<?php
header('Content-Type: application/json');

// Start session and get user ID (assuming user is logged in)
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to cart']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate data
if (!isset($data['product_id'], $data['product_name'], $data['product_price'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid product data']);
    exit;
}

// Database connection
$host = 'localhost';
$dbname = 'food_delievery';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if item already exists in cart
    $stmt = $pdo->prepare("SELECT * FROM cart_item WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $data['product_id']]);
    $existingItem = $stmt->fetch();

    if ($existingItem) {
        // Update quantity if item exists
        $stmt = $pdo->prepare("UPDATE cart_item SET quantity = quantity + 1 WHERE id = ?");
        $stmt->execute([$existingItem['id']]);
    } else {
        // Insert new item
        $stmt = $pdo->prepare("INSERT INTO cart_item (user_id, product_id, product_name, product_price, product_image, quantity) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user_id,
            $data['product_id'],
            $data['product_name'],
            $data['product_price'],
            $data['product_image'] ?? null,
            $data['quantity'] ?? 1
        ]);
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>