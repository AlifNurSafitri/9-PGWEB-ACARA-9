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

// Cek apakah parameter 'kecamatan' ada
if (isset($_GET['kecamatan'])) {
    $kecamatan = urldecode($_GET['kecamatan']);

    // Query untuk mengambil data kecamatan tertentu
    $sql = "SELECT * FROM penduduk WHERE kecamatan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $kecamatan);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    // Tampilkan form dengan data yang sudah ada untuk diedit
    if ($data) {
        echo "<style>
                form {
                    max-width: 400px;
                    margin: auto;
                    padding: 20px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background-color: #f9f9f9;
                }
                label {
                    display: block;
                    margin-bottom: 10px;
                }
                input[type='text'] {
                    width: 100%;
                    padding: 8px;
                    margin-bottom: 15px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                }
                button {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 15px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }
                button:hover {
                    background-color: #45a049;
                }
              </style>
              <form action='update.php' method='post'>
                <input type='hidden' name='kecamatan' value='{$data['kecamatan']}'>
                <label>Luas (km²):
                    <input type='text' name='luas' value='{$data['luas']}'>
                </label>
                <label>Jumlah Penduduk:
                    <input type='text' name='jumlah_penduduk' value='{$data['jumlah_penduduk']}'>
                </label>
                <label>Longitude:
                    <input type='text' name='longitude' value='{$data['longitude']}'>
                </label>
                <label>Latitude:
                    <input type='text' name='latitude' value='{$data['latitude']}'>
                </label>
                <button type='submit'>Update</button>
              </form>";
    } else {
        echo "Data tidak ditemukan.";
    }

    // Tutup statement dan koneksi
    $stmt->close();
}
$conn->close();
?>
