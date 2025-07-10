<?php


session_start(); 
require_once 'config.php'; 
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.html"); 
     exit();
}

$user_id = $_SESSION['id']; 

if (isset($_GET['remove_product_id'])) {
    $product_id_to_remove = filter_input(INPUT_GET, 'remove_product_id', FILTER_VALIDATE_INT);

    if ($product_id_to_remove !== false) {
        try {
            $sql_delete = "DELETE FROM cart_item WHERE user_id = :user_id AND product_id = :product_id";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->execute([
                ':user_id' => $user_id,
                ':product_id' => $product_id_to_remove
            ]);
              } catch (PDOException $e) {
           
            error_log("Error removing item from cart: " . $e->getMessage());
            echo "<script>alert('Error removing item from cart. Please try again.');</script>";
        }
    }
    header("Location: cart.php");
    exit();
}

$cart_items = [];
$total = 0;

try {
    $sql_fetch_cart = "SELECT product_id, product_name, product_price, product_image, quantity FROM cart_item WHERE user_id = :user_id";
    $stmt_fetch = $pdo->prepare($sql_fetch_cart);
    $stmt_fetch->execute([':user_id' => $user_id]);
    $fetched_items = $stmt_fetch->fetchAll(); 

    foreach ($fetched_items as $item) {
        $item_subtotal = $item['product_price'] * $item['quantity'];
        $total += $item_subtotal;
        $cart_items[] = [
            'product_id' => htmlspecialchars($item['product_id']),
            'name' => htmlspecialchars($item['product_name']),
            'price' => htmlspecialchars($item['product_price']),
            'img' => htmlspecialchars($item['product_image']),
            'qty' => htmlspecialchars($item['quantity']),
            'subtotal' => htmlspecialchars(number_format($item_subtotal, 2)) 
        ];
    }
} catch (PDOException $e) {
    // Log database errors
    error_log("Error fetching cart items: " . $e->getMessage());
    echo "<p class='text-danger text-center'>Error loading cart items. Please try again later.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart - FoodieExpress</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .cart-container {
      margin: 60px auto;
      max-width: 1000px;
      padding: 20px;     .card {
      border: none;
      border-radius: 20px;
      transition: transform 0.3s ease;
      box-shadow: 0 15px 30px rgba(0,0,0,0.1);
      background-color: #fff;   }
    .card:hover {
      transform: translateY(-5px);
    }
    .cart-img {
      border-radius: 15px;
      object-fit: cover;
      height: 150px;
      width: 100%;
    }
    .remove-btn {
      display: inline-block;
      border: none;
      background-color: #ff4d4d;
      color: white;
      padding: 8px 15px; 
      border-radius: 10px;
      font-size: 14px;
      text-decoration: none; 
      transition: background-color 0.3s ease;
    }
    .remove-btn:hover {
      background-color: #cc0000;
      color: white;
    }
    .summary {
      background: #fff8f0;
      padding: 25px;
      border-radius: 20px;
      margin-top: 30px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .btn-primary, .btn-success {
      border-radius: 20px;
      padding: 10px 25px;
      font-weight: bold;
      text-decoration: none;
    }
    .empty-cart {
      text-align: center;
      padding: 100px;
      color: #555;
    }
  
    @media (max-width: 768px) {
      .cart-container {
        margin: 30px auto;
        padding: 15px;
      }
      .card {
        margin-bottom: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container cart-container">
    <h1 class="text-center mb-5 fw-bold">🛒 Your Food Cart</h1>

    <?php

    if (empty($cart_items)):
    ?>
      <div class="empty-cart">
        <h3>Your cart is empty!</h3>
        <p>Go back and add some delicious items 🍕</p>
        <a href="index.html" class="btn btn-primary mt-4">Back to Menu</a>
      </div>
    <?php else: ?>
      <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php
        
          foreach ($cart_items as $item):
        ?>
        <div class="col">
          <div class="card h-100">
             <img src="<?= $item['img'] ?>" alt="<?= $item['name'] ?>" class="cart-img">
            <div class="card-body">
              <h5 class="card-title"><?= $item['name'] ?></h5>
              <p class="card-text">Price: ₹<?= $item['price'] ?> × <?= $item['qty'] ?></p>
              <p class="card-text fw-bold">Subtotal: ₹<?= $item['subtotal'] ?></p>
               <a href="?remove_product_id=<?= $item['product_id'] ?>" class="remove-btn">Remove</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="summary mt-5">
        <h4>Total Amount: ₹<?= htmlspecialchars(number_format($total, 2)) ?></h4>
        <div class="mt-4 d-flex justify-content-between">
          <a href="index.html" class="btn btn-primary me-3">Back to Menu</a>
          <a href="https://docs.google.com/forms/d/1RGL-xo_6oHna2jnAoUp8qzo16O2DHq2rCiTWagd3bMs/viewform?pli=1&pli=1&edit_requested=true" class="btn btn-success">Proceed to Checkout</a>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
