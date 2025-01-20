<?php
// Koneksi ke database
$db = new mysqli("localhost", "myuser", "Qwerty123~", "absensi");

// Cek koneksi
if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}

// Proses data jika form dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Enkripsi password
    $class = $_POST["class"];

    // Masukkan data ke database
    $stmt = $db->prepare("INSERT INTO users (username, password, class) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $class);
    if ($stmt->execute()) {
        echo "Data berhasil disimpan!";
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form Registrasi</title>
</head>
<body>
    <h2>Form Registrasi</h2>
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Kelas:</label><br>
        <input type="text" name="class" required><br><br>

        <button type="submit">Daftar</button>
    </form>
</body>
</html>
