<?php

session_start();


require_once 'config.php';


$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';


if ($email && $password) {
  
  $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
  
  $stmt->execute([$email]);
  
 
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    
    if (password_verify($password, $user['password'])) {
    
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      
      header("Location: index.html"); 
      exit(); 
    } else {
      
      echo "<script>alert('Incorrect password.'); window.history.back();</script>";
    }
  } else {
    
    echo "<script>alert('Email not found.'); window.history.back();</script>";
  }
}

?>
