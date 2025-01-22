<?php
// Koneksi ke database
$servername = "localhost";
$username = "myuser";
$password = "Qwerty123~";
$dbname = "absensi";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses data saat form dikirim
$message = ""; // Pesan untuk menampilkan informasi ke pengguna
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $class = $_POST["class"];

    if (!empty($username) && !empty($password) && !empty($class)) {
        // Enkripsi password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Gunakan prepared statement untuk keamanan
        $stmt = $conn->prepare("INSERT INTO users (username, password, class) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $class);

        if ($stmt->execute()) {
            // Redirect ke halaman home.php setelah berhasil
            header("Location: home.php");
            exit();
        } else {
            $message = "Gagal menyimpan data: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Semua data harus diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi Pengguna</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Formulir Pendaftaran</h2>
        
        <!-- Tampilkan pesan jika ada -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="class" class="form-label">Kelas</label>
                <input type="text" class="form-control" id="class" name="class" required>
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>
    </div>
</body>
</html>
