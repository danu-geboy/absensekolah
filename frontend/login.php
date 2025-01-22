session_start();
if ($login_berhasil) {
    $_SESSION["logged_in"] = true;
    $_SESSION["username"] = $username; // Simpan username (opsional)
    header("Location: home.php");
    exit();
}
