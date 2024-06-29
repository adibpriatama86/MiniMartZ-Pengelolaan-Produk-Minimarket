<?php
// periksa apakah user sudah login, cek kehadiran session username
// jika tidak ada, redirect ke login.php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login_user.php");
}

// buka koneksi dengan MySQL
include("connection.php");

// ambil pesan jika ada
if (isset($_GET["pesan"])) {
    $pesan = $_GET["pesan"];
}

// ambil nama pengguna dari sesi
$username = $_SESSION["username"];

// cek apakah form telah di submit
// berasal dari form pencairan, siapkan query
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
    <link rel="stylesheet" href="./css/daftar_produk_user.css">
    <link rel="icon" href="./assets/images/logo.png" type="image/x-icon">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <div id="header" class="text-center">
            <img class="brand" src="./assets/images/brand.png">
            <p class="mt-2"><b>Selamat datang, <?php echo htmlspecialchars($username); ?>!</b></p>
            <p id="tanggal"><?php echo date("d M Y"); ?></p>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <hr>
        <form class="pencarian mb-4" id="search" action="daftar_produk_user.php" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="nama_produk" id="nama_produk" placeholder="Cari produk...">
                <input type="submit" name="submit" value="Search">
            </div>
        </form>

        <h2 class="judul text-center">Data Produk</h2>
        <?php
        // tampilkan pesan jika ada
        if (isset($pesan)) {
            echo "<div class=\"alert alert-info\">$pesan</div>";
        }
        ?>
        <div class="row">
            <?php
            // jalankan query
            $result = mysqli_query($link, $query);

            if (!$result) {
                die("Query Error: " . mysqli_errno($link) . " - " . mysqli_error($link));
            }

            // buat perulangan untuk element tabel dari data mahasiswa
            while ($data = mysqli_fetch_assoc($result)) {
                echo "<div class=\"col-md-4 mb-4\">";
                echo "<div class=\"card\">";
                echo "<img src='" . $data['gambar_produk'] . "' class=\"card-img-top\" alt='Gambar Produk'>";
                echo "<div class=\"card-body\">";
                echo "<h5 class=\"card-title\">$data[nama_produk]</h5>";
                echo "<p class=\"card-text\">Harga: $data[harga_produk]</p>";
                // echo "<p class=\"card-text\">Stok: $data[stok_produk]</p>";
                echo "<p class=\"card-text\">Deskripsi: $data[deskripsi_produk]</p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }

            // bebaskan memory
            mysqli_free_result($result);

            // tutup koneksi dengan database mysql
            mysqli_close($link);
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>