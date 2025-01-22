<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Koneksi database
$conn = new mysqli("localhost", "myuser", "Qwerty123~", "absensi");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil detail pengguna berdasarkan ID
$id = intval($_GET['id']);
$result = $conn->query("SELECT id, username, class FROM users WHERE id = $id");
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Pengguna</title>
</head>
<body>
    <h1>Detail Pengguna</h1>
    <?php if ($user): ?>
        <p>ID: <?php echo htmlspecialchars($user['id']); ?></p>
        <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
        <p>Kelas: <?php echo htmlspecialchars($user['class']); ?></p>
        <a href="dashboard.php">Kembali ke Dashboard</a>
    <?php else: ?>
        <p>Pengguna tidak ditemukan!</p>
    <?php endif; ?>
</body>
</html>
