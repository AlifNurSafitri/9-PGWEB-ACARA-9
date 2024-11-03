<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Kabupaten Sleman</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
        }
        #map {
            width: 60%;
            height: 300px;
        }
        table {
            width: 60%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

<header>
    <h1>Peta Sebaran Penduduk dan Data Kecamatan</h1>
   
</header>

<!-- Peta -->
<div id="map"></div>

<!-- Tabel Data -->
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kecamatan</th>
            <th>Luas (km²)</th>
            <th>Jumlah Penduduk</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Action</th>
            
        </tr>
    </thead>
    <tbody>
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

        // Query untuk mengambil data dari tabel penduduk
        $sql = "SELECT kecamatan, luas, jumlah_penduduk, longitude, latitude FROM penduduk";
        $result = $conn->query($sql);

        $markers = [];  // Simpan data marker ke array PHP
        if ($result->num_rows > 0) {
            $no = 1;  // Inisialisasi nomor baris
            while ($row = $result->fetch_assoc()) {
                // Isi tabel HTML dengan data
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['kecamatan']}</td>
                        <td>{$row['luas']}</td>
                        <td>{$row['jumlah_penduduk']}</td>
                        <td>{$row['longitude']}</td>
                        <td>{$row['latitude']}</td>
                        <td><a href='delete.php?kecamatan=" . urlencode($row["kecamatan"]) . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a></td>
                      </tr>";

                // Tambahkan data ke array markers
                $markers[] = $row;
                $no++;
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada data</td></tr>";
        }

        // Tutup koneksi
        $conn->close();
        ?>
    </tbody>
</table>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Inisialisasi peta di Sleman
    var map = L.map("map").setView([-7.7166, 110.3558], 12); // Sleman sebagai pusat peta

    // Tambahkan basemap Rupabumi Indonesia
    var rupabumiindonesia = L.tileLayer('https://geoservices.big.go.id/rbi/rest/services/BASEMAP/Rupabumi_Indonesia/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Badan Informasi Geospasial'
    }).addTo(map);

    // Data marker dari PHP
    var markers = <?php echo json_encode($markers); ?>;

    // Tambahkan marker ke peta
    markers.forEach(function (data) {
        var marker = L.marker([data.latitude, data.longitude]).addTo(map);
        marker.bindPopup(`
            <b>${data.kecamatan}</b><br>
            Luas: ${data.luas} km²<br>
            Jumlah Penduduk: ${data.jumlah_penduduk}
        `);
    });
</script>

</body>

</html>
