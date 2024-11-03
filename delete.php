<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = ""; // Sesuaikan jika perlu
$dbname = "penduduk_db"; // Pastikan nama database benar

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pastikan parameter 'kecamatan' ada
if (isset($_GET['kecamatan'])) {
    // Ambil parameter kecamatan dari URL
    $kecamatan = urldecode($_GET['kecamatan']);
    
    // Query untuk menghapus data berdasarkan kecamatan
    $sql = "DELETE FROM penduduk WHERE kecamatan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $kecamatan);

    // Eksekusi query dan cek hasil
    if ($stmt->execute()) {
        echo "Data berhasil dihapus";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Redirect kembali ke halaman utama setelah penghapusan
    header("Location: index.php");
    exit();
} else {
    echo "Parameter 'kecamatan' tidak ditemukan.";
}

// Tutup koneksi
$conn->close();
?>
