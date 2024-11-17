<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Ambil semua data menu dari database
$stmt = $pdo->prepare("SELECT * FROM menu");
$stmt->execute();
$menus = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="desain.css">
    <title>Dashboard User</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Menu Terang Bulan</h1>
    <h3>Berikut adalah daftar menu terang bulan yang tersedia:</h3>

    <?php if (count($menus) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menus as $menu): ?>
                    <tr>
                        <td><?= htmlspecialchars($menu['name']) ?></td>
                        <td><?= htmlspecialchars($menu['description']) ?></td>
                        <td>Rp. <?= number_format($menu['price'], 0, ',', '.') ?></td>
                        <td><img src="<?= htmlspecialchars($menu['image_url']) ?>" alt="Image"></td>
                        <td>
                            <a href="https://wa.me/6285369324413?text=Halo,%20saya%20ingin%20memesan%20<?= urlencode($menu['name']) ?>.%20Apakah%20masih%20tersedia?" 
                               target="_blank">
                               Pesan Sekarang
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada menu yang tersedia saat ini.</p>
    <?php endif; ?>

    <h2><a href="logout.php">Logout</a></h2>
</body>
</html>
