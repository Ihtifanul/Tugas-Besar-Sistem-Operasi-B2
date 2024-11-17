<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Cek login untuk user dari database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        
        if ($user['role'] === 'admin') {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard_user.php");
        }
        exit;
    } else {
        echo "Login gagal. Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="desain.css">
    <title>Login</title>
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <form action="index.php" method="get" style="margin-top: 10px;">
        <button type="submit">Kembali ke Beranda</button>
    </form>
</body>
</html>
