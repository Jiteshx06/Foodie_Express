<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
 
    echo json_encode(['success' => true, 'cart_count' => 0]);
    exit;
}

require_once '../config.php'; 

$userId = $_SESSION['id'];
try {
    $sql_count = "SELECT COUNT(*) AS cart_count FROM cart_item WHERE user_id = :user_id";
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute([':user_id' => $userId]);
    $result = $stmt_count->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'cart_count' => $result['cart_count']]);

} catch (PDOException $e) {
    error_log("Error fetching cart count: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error fetching cart count.', 'cart_count' => 0]);
}
?>
