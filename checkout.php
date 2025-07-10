<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.html"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - FoodieExpress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }
        .checkout-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 90%;
        }
        h1 {
            color: #ff6b35;
            margin-bottom: 30px;
            font-weight: bold;
        }
        p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: #ff6b35;
            border-color: #ff6b35;
            padding: 12px 25px;
            font-size: 1.1rem;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #e05a2b;
            border-color: #e05a2b;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255,107,53,0.3);
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h1>Proceed to Checkout</h1>
        <p>This is a placeholder for your checkout process. Here you would integrate payment gateways and finalize the order.</p>
        <a href="cart.php" class="btn btn-primary">Back to Cart</a>
    </div>
</body>
</html>
