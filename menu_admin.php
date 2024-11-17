<?php
session_start();
include 'db.php';

// Cek apakah user sudah login dan apakah user adalah admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Proses penghapusan menu
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Hapus menu dari database berdasarkan ID
    $stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?");
    $stmt->execute([$delete_id]);

    // Redirect ke halaman yang sama setelah penghapusan
    header("Location: menu_admin.php");
    exit;
}

// Proses edit menu
if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    // Update menu berdasarkan ID
    $stmt = $pdo->prepare("UPDATE menu SET name = ?, description = ?, price = ?, image_url = ? WHERE id = ?");
    $stmt->execute([$name, $description, $price, $image_url, $edit_id]);

    // Redirect ke halaman menu setelah pengeditan
    header("Location: menu_admin.php");
    exit;
}

// Proses penambahan menu baru
if (isset($_POST['add_menu'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    // Masukkan menu baru ke dalam database
    $stmt = $pdo->prepare("INSERT INTO menu (name, description, price, image_url) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $image_url]);

    // Redirect ke halaman menu setelah penambahan
    header("Location: menu_admin.php");
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
    <title>Admin - Manajemen Menu</title>
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
        .form-edit, .form-add {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 20px;
            width: 300px;
        }
    </style>
</head>
<body>
    <h2>Manajemen Menu</h2>
    <p>Berikut adalah daftar menu yang tersedia:</p>

    <?php if (count($menus) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
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
                        <td><?= htmlspecialchars($menu['id']) ?></td>
                        <td><?= htmlspecialchars($menu['name']) ?></td>
                        <td><?= htmlspecialchars($menu['description']) ?></td>
                        <td>Rp. <?= number_format($menu['price'], 0, ',', '.') ?></td>
                        <td><img src="<?= htmlspecialchars($menu['image_url']) ?>" alt="Image" width="100"></td>
                        <td>
                            <a href="menu_admin.php?delete_id=<?= $menu['id'] ?>" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?');">
                               Hapus
                            </a> |
                            <a href="menu_admin.php?edit_id=<?= $menu['id'] ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada menu yang tersedia.</p>
    <?php endif; ?>

    <?php
    // Proses jika ada parameter edit_id
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
        $stmt->execute([$edit_id]);
        $menu_to_edit = $stmt->fetch();
    }
    ?>

    <?php if (isset($menu_to_edit)): ?>
        <h3>Edit Menu</h3>
        <form method="POST" action="menu_admin.php">
            <input type="hidden" name="edit_id" value="<?= $menu_to_edit['id'] ?>">
            <label for="name">Nama:</label><br>
            <input type="text" id="name" name="name" value="<?= $menu_to_edit['name'] ?>" required><br><br>

            <label for="description">Deskripsi:</label><br>
            <textarea id="description" name="description" rows="4" required><?= $menu_to_edit['description'] ?></textarea><br><br>

            <label for="price">Harga:</label><br>
            <input type="number" id="price" name="price" value="<?= $menu_to_edit['price'] ?>" required><br><br>

            <label for="image_url">URL Gambar:</label><br>
            <input type="text" id="image_url" name="image_url" value="<?= $menu_to_edit['image_url'] ?>" required><br><br>

            <button type="submit">Simpan Perubahan</button>
        </form>
    <?php endif; ?>

    <h3>Tambah Menu Baru</h3>
    <form method="POST" action="menu_admin.php" class="form-add">
        <label for="name">Nama:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="description">Deskripsi:</label><br>
        <textarea id="description" name="description" rows="4" required></textarea><br><br>

        <label for="price">Harga:</label><br>
        <input type="number" id="price" name="price" required><br><br>

        <label for="image_url">URL Gambar:</label><br>
        <input type="text" id="image_url" name="image_url" required><br><br>

        <button type="submit" name="add_menu">Tambah Menu</button>
    </form>

    <a href="dashboard_admin.php" class="back-to-dashboard-btn">Kembali ke Dashboard Admin</a>
</body>
</html>
