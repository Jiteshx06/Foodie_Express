<?php
// php/process_login.php
session_start();

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Invalid request method');
}


$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('Location: ../login.html?error=empty_fields');
    exit;
}


require_once '../config.php';

try {
   
    $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();


    if (!$user || !password_verify($password, $user['password'])) {
        header('Location: ../login.html?error=invalid_credentials');
        exit;
    }

    $_SESSION['id'] = $user['id']; 
    $_SESSION['user_id'] = $user['id']; 
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['loggedin'] = true; 

  
    header('Location: ../index.html');
    exit;

} catch (PDOException $e) {
 
    error_log('Database error during login: ' . $e->getMessage());
    header('Location: ../login.html?error=db_error');
    exit;
}
?>
