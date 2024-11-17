<?php
session_start();
include 'db.php';

// Cek apakah user sudah login dan apakah user adalah admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Proses penghapusan pengguna
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Hapus pengguna dari database berdasarkan ID
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$delete_id]);

    // Redirect ke halaman yang sama setelah penghapusan
    header("Location: admin_users.php");
    exit;
}

// Proses edit pengguna
if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update pengguna berdasarkan ID
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->execute([$username, $email, $role, $edit_id]);

    // Redirect ke halaman users setelah pengeditan
    header("Location: admin_users.php");
    exit;
}

// Ambil semua data pengguna dari database
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="desain.css">
    <title>Admin - Manajemen Pengguna</title>
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
        .form-edit {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 20px;
            width: 300px;
        }
    </style>
</head>
<body>
    <h2>Manajemen Pengguna</h2>
    <p>Berikut adalah daftar pengguna:</p>

    <?php if (count($users) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <a href="admin_users.php?delete_id=<?= $user['id'] ?>" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                               Hapus
                            </a> |
                            <a href="admin_users.php?edit_id=<?= $user['id'] ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada pengguna yang tersedia.</p>
    <?php endif; ?>

    <?php
    // Proses jika ada parameter edit_id
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$edit_id]);
        $user_to_edit = $stmt->fetch();
    }
    ?>

    <?php if (isset($user_to_edit)): ?>
        <h3>Edit Pengguna</h3>
        <form method="POST" action="admin_users.php">
            <input type="hidden" name="edit_id" value="<?= $user_to_edit['id'] ?>">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" value="<?= $user_to_edit['username'] ?>" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?= $user_to_edit['email'] ?>" required><br><br>

            <label for="role">Role:</label><br>
            <select id="role" name="role" required>
                <option value="user" <?= $user_to_edit['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user_to_edit['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select><br><br>

            <button type="submit">Simpan Perubahan</button>
        </form>
    <?php endif; ?>
    <a href="dashboard_admin.php" class="back-to-dashboard-btn">Kembali ke Dashboard Admin</a>
</body>
</html>
