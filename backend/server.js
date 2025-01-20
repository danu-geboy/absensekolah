const express = require("express");
const mysql = require("mysql2");
const bcrypt = require("bcryptjs");

const app = express();
app.use(express.json());

// Konfigurasi koneksi database
const db = mysql.createConnection({
    host: "localhost",
    user: "myuser",
    password: "Qwerty123~",
    database: "absensi"
});

db.connect(err => {
    if (err) {
        console.error("Gagal terhubung ke database:", err);
        process.exit(1);
    }
    console.log("Terhubung ke database!");
});

// Endpoint untuk menyimpan data pengguna
app.post("/api/users", async (req, res) => {
    const { username, password, class: userClass } = req.body;

    if (!username || !password || !userClass) {
        return res.status(400).json({ message: "Semua data harus diisi!" });
    }

    try {
        const hashedPassword = await bcrypt.hash(password, 10);
        const query = "INSERT INTO users (username, password, class) VALUES (?, ?, ?)";
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

// Endpoint untuk mengambil daftar pengguna
app.get("/api/users", (req, res) => {
    db.query("SELECT id, username, class FROM users", (err, results) => {
        if (err) {
            return res.status(500).json({ message: "Gagal mengambil data", error: err });
        }
	console.log("Data pengguna diambil:", results); // Log hasil
        res.json(results);
    });
});
app.get("/users", (req, res) => {
    db.query("SELECT id, username, class FROM users", (err, results) => {
        if (err) {
            console.error("Error saat mengambil data pengguna:", err);
            return res.status(500).json({ message: "Gagal mengambil data", error: err });
        }
        res.json(results);
    });
});


// Menjalankan server
const PORT = 4000;
app.listen(PORT, () => {
    console.log(`Server berjalan di port ${PORT}`);
});

// Endpoint untuk root
app.get("/", (req, res) => {
    res.send("Hello World!");
});

