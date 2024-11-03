<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "penduduk_db";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek apakah form dikirim melalui POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $kecamatan = $_POST['kecamatan'];
    $luas = $_POST['luas'];
    $jumlah_penduduk = $_POST['jumlah_penduduk'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];

    // Query untuk memperbarui data
    $sql = "UPDATE penduduk SET luas = ?, jumlah_penduduk = ?, longitude = ?, latitude = ? WHERE kecamatan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dddds", $luas, $jumlah_penduduk, $longitude, $latitude, $kecamatan);

    // Eksekusi query
    if ($stmt->execute()) {
        echo "Data berhasil diperbarui.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Tutup statement dan koneksi
    $stmt->close();
}
$conn->close();
?>

<!-- Tautan untuk kembali ke halaman utama -->
<a href="index.php">Kembali ke Halaman Utama</a>
