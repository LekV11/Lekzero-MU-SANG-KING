<?php
// Konfigurasi Database
$host = 'localhost';
$user = 'root'; // Ganti jika kamu menggunakan user selain root
$pass = '';     // Masukkan password MariaDB kamu di sini
$db   = 'web_remote';

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses jika ada form yang dikirim (Simpan Pesan)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pesan'])) {
    $isi_pesan = $conn->real_escape_string($_POST['pesan']);
    if (!empty($isi_pesan)) {
        $sql = "INSERT INTO pesan (isi_pesan) VALUES ('$isi_pesan')";
        $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remote Web & Database</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        textarea { width: 100%; height: 100px; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
        .pesan-box { background: #e9ecef; padding: 10px; margin-top: 10px; border-radius: 4px; }
        .waktu { font-size: 0.8em; color: #666; }
    </style>
</head>
<body>

<div class="container">
    <h2>Kirim Pesan ke Server</h2>
    
    <form method="POST" action="">
        <textarea name="pesan" placeholder="Tulis pesan kamu di sini..." required></textarea><br>
        <button type="submit">Simpan Pesan</button>
    </form>

    <hr>
    <h3>Daftar Pesan Tersimpan:</h3>

    <?php
    $sql_tampil = "SELECT * FROM pesan ORDER BY waktu DESC";
    $result = $conn->query($sql_tampil);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='pesan-box'>";
            echo "<p>" . htmlspecialchars($row['isi_pesan']) . "</p>";
            echo "<span class='waktu'>" . $row['waktu'] . "</span>";
            echo "</div>";
        }
    } else {
        echo "<p>Belum ada pesan.</p>";
    }
    $conn->close();
    ?>
</div>

</body>
</html>