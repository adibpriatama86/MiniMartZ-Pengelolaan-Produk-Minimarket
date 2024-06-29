<?php
// periksa apakah user sudah login, cek kehadiran session username
// jika tidak ada, redirect ke login.php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login_admin.php");
}

// buka koneksi dengan MySQL
include("connection.php");

// ambil pesan jika ada
if (isset($_GET["pesan"])) {
    $pesan = $_GET["pesan"];
}

// cek apakah form telah di submit
// berasal dari form pencairan
$query = "";
if (isset($_GET["submit"])) {
    // ambil nilai nama_produk
    $nama_produk = htmlentities(strip_tags(trim($_GET["nama_produk"])));

    // filter untuk $nama_produk untuk mencegah sql injection
    $nama_produk = mysqli_real_escape_string($link, $nama_produk);

    // buat query pencarian
    $query = "SELECT * FROM produk WHERE nama_produk LIKE '%$nama_produk%' ORDER BY nama_produk ASC";

    // buat pesan
    $pesan = "Hasil pencarian untuk produk: <b>\"$nama_produk\"</b>";
} else {
    // bukan dari form pencairan
    // siapkan query untuk menampilkan seluruh data dari tabel produk
    $query = "SELECT * FROM produk ORDER BY nama_produk ASC";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Produk</title>
    <link rel="stylesheet" href="./css/daftar_produk_admin.css">
    <link rel="icon" href="./assets/images/logo.png" type="image/x-icon">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
    <div class="outer">
        <div class="container">
            <div id="header">
                <img class="brand" src="./assets/images/brand.png">
                <p id="tanggal"><?php echo date("d M Y"); ?></p>
            </div>
            <hr>
            <div class="container-nav">
                <nav>
                    <ul>
                        <div class="container-li">
                            <li><a href="daftar_produk_admin.php">Daftar Produk</a></li>
                            <li><a href="tambah_produk.php">Tambah Produk</a></li>
                            <li><a href="edit_produk.php">Edit Produk</a></li>
                            <li><a href="hapus_produk.php">Hapus Produk</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </div>
                    </ul>
                </nav>
            </div>
    
            <form class="pencarian" id="search" action="daftar_produk_admin.php" method="get">
                <p>
                    <label for="nama_produk">Nama Produk : </label>
                    <input type="text" name="nama_produk" id="nama_produk" placeholder="search...">
                    <input type="submit" name="submit" value="Search">
                </p>
            </form>
    
            <h2 class="judul">Data Produk</h2>
            <?php
            // tampilkan pesan jika ada
            if (isset($pesan)) {
                echo "<div class=\"pesan\">$pesan</div>";
            }
            ?>
            <div class="table-responsive">
                <div class="table-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="table-th">No</th>
                                <th class="table-th">Nama Produk</th>
                                <th class="table-th">Harga</th>
                                <th class="table-th">Stok</th>
                                <th class="table-th">Deskripsi</th>
                                <th class="table-th">Gambar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // jalankan query
                            $result = mysqli_query($link, $query);
    
                            if (!$result) {
                                die("Query Error: " . mysqli_errno($link) . " - " . mysqli_error($link));
                            }
    
                            // buat perulangan untuk element tabel dari data mahasiswa
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>$no</td>";
                                echo "<td>$data[nama_produk]</td>";
                                echo "<td>$data[harga_produk]</td>";
                                echo "<td>$data[stok_produk]</td>";
                                echo "<td>$data[deskripsi_produk]</td>";
                                echo "<td><img src='" . $data['gambar_produk'] . "' style='max-width: 100px; max-height: 100px;' /></td>";
                                echo "</tr>";
                                $no++;
                            }
    
                            // bebaskan memory
                            mysqli_free_result($result);
    
                            // tutup koneksi dengan database mysql
                            mysqli_close($link);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>