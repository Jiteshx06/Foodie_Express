<?php

session_start();


require_once 'config.php';

// Get user input from the POST request
$firstName = $_POST['first_name'] ?? '';
$lastName = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';


$fullName = trim($firstName . ' ' . $lastName);


if ($fullName && $email && $password && $confirmPassword) {

  if ($password !== $confirmPassword) {
    echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
    exit();
  }


  $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
  $check->execute([$email]);
 
  $checkResult = $check->fetch(PDO::FETCH_ASSOC);

  if ($checkResult) {
   
    echo "<script>alert('Email already registered!'); window.history.back();</script>";
  } else {
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

   
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    
     if ($stmt->execute([$fullName, $email, $hashedPassword])) {
      
      echo "<script>alert('Registration successful! Please log in.'); window.location.href = 'login.html';</script>";
    } else {
      
      echo "<script>alert('Something went wrong. Try again.'); window.history.back();</script>";
    }
  }
} else {
 
    echo "<script>alert('Please fill all required fields.'); window.history.back();</script>";
}

?>
