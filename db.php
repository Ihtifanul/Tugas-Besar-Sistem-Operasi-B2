<?php
$host = "srv1410.hstgr.io";
$db = "u740202808_Moonlight";
$user = "u740202808_RootMoonlight";
$pass = "!@mooLIG34";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
