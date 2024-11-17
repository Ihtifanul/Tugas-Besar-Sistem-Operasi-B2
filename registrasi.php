<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password, $email])) {
        header("Location: login.php");
    } else {
        echo "Registrasi gagal.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="desain.css">
    <title>Registrasi</title>
</head>
<body>
    <form method="POST">
        <h2>Registrasi</h2>
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        Email: <input type="email" name="email" required><br>
        <button type="submit">Daftar</button>
    </form>
    <form action="index.php" method="get" style="margin-top: 10px;">
        <button type="submit">Kembali ke Beranda</button>
    </form>
</body>
</html>
