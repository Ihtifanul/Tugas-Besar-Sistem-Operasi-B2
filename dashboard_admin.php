<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="desain.css">
    <title>Dashboard Admin</title>
</head>
<body>

    <h1>Dashboard Admin</h1>
    <nav>
    <form action="contact_admin.php" method="get" style="margin-top: 10px;">
        <button type="submit">Lihat Contact</button>
    </form>

    <form action="menu_admin.php" method="get" style="margin-top: 10px;">
    <button type="submit">Lihat Menu</button>
    </form>

    <form action="admin_users.php" method="get" style="margin-top: 10px;">
    <button type="submit">Lihat Data User</button>
    </form>

    <form action="logout.php" method="get" style="margin-top: 10px;">
    <button type="submit">Logout</button>
    </form>

</body>
</html>
