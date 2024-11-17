<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Validasi sederhana
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Simpan pesan ke database
        $stmt = $pdo->prepare("INSERT INTO contact_us (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        if ($stmt->execute([$name, $email, $message])) {
            $success = "Pesan Anda telah berhasil dikirim.";
        } else {
            $error = "Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.";
        }
    } else {
        $error = "Semua kolom harus diisi.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="desain.css">
    <title>Contact Us</title>
</head>
<body>
    <h1>Contact Us</h1>
   

    <?php if (isset($success)): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php elseif (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="contact.php">
    <h3>Silakan isi formulir di bawah ini untuk mengirimkan pesan Anda.</h3>
        <label for="name">Nama:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="message">Pesan:</label><br>
        <textarea id="message" name="message" rows="4" required></textarea><br><br>

        <button type="submit">Kirim</button>
    </form>

    <form action="index.php" method="get" style="margin-top: 10px;">
        <button type="submit">Kembali ke Beranda</button>
    </form>
</body>
</html>
