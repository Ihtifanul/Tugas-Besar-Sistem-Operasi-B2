<?php
session_start();
include 'db.php';

// Cek apakah user sudah login dan apakah user adalah admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Proses penghapusan data
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Hapus data dari database berdasarkan ID
    $stmt = $pdo->prepare("DELETE FROM contact_us WHERE id = ?");
    $stmt->execute([$delete_id]);

    // Redirect ke halaman yang sama setelah penghapusan
    header("Location: contact_admin.php");
    exit;
}

// Ambil semua data dari tabel contact_us
$stmt = $pdo->prepare("SELECT * FROM contact_us ORDER BY created_at DESC");
$stmt->execute();
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="desain.css">
    <title>Admin - Pesan Contact Us</title>
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
    </style>
</head>
<body>
    <h2>Pesan dari Pengguna</h2>
    <p>Berikut adalah pesan yang dikirim oleh pengguna:</p>

    <?php if (count($messages) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?= htmlspecialchars($message['id']) ?></td>
                        <td><?= htmlspecialchars($message['name']) ?></td>
                        <td><?= htmlspecialchars($message['email']) ?></td>
                        <td><?= htmlspecialchars($message['message']) ?></td>
                        <td><?= htmlspecialchars($message['created_at']) ?></td>
                        <td>
                            <a href="contact_admin.php?delete_id=<?= $message['id'] ?>" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                               Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada pesan yang tersedia.</p>
    <?php endif; ?>

    <a href="dashboard_admin.php" class="back-to-dashboard-btn">Kembali ke Dashboard Admin</a>
</body>
</html>
