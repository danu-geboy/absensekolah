const express = require("express");
const mysql = require("mysql2");
const bcrypt = require("bcryptjs");

const app = express();
app.use(express.json()); // Untuk parsing JSON body

// Konfigurasi koneksi database
const db = mysql.createConnection({
    host: "localhost",
    user: "myuser", // Ganti dengan username database Anda
    password: "Qwerty123~", // Ganti dengan password database Anda
    database: "absensi" // Ganti dengan nama database Anda
});

// Test koneksi ke database
db.connect((err) => {
    if (err) {
        console.error("Gagal terhubung ke database:", err.message);
    } else {
        console.log("Berhasil terhubung ke database.");
    }
});

// Endpoint untuk menyimpan data pengguna
app.post("/api/users", async (req, res) => {
    const { username, password, class: userClass } = req.body;

    if (!username || !password || !userClass) {
        return res.status(400).json({ message: "Semua data harus diisi!" });
    }

    try {
        // Enkripsi password
        const hashedPassword = await bcrypt.hash(password, 10);

        // Simpan ke database
        const query = "INSERT INTO users (username, password, `class`) VALUES (?, ?, ?)";
        db.query(query, [username, hashedPassword, userClass], (err, results) => {
            if (err) {
                return res.status(500).json({ message: "Gagal menyimpan data", error: err });
            }
            res.status(201).json({ message: "Data berhasil disimpan!" });
        });
    } catch (error) {
        res.status(500).json({ message: "Terjadi kesalahan", error });
    }
});

// Endpoint untuk mendapatkan daftar pengguna
app.get("/api/users", (req, res) => {
    const query = "SELECT id, username, `class` FROM users";
    db.query(query, (err, results) => {
        if (err) {
            return res.status(500).json({ message: "Gagal mengambil data", error: err });
        }
        res.status(200).json(results);
    });
});

// Jalankan server di port 4000
const PORT = 4000;
app.listen(PORT, () => {
    console.log(`Server berjalan pada port ${PORT}. Akses melalui http://localhost:${PORT}/api/users`);
});
