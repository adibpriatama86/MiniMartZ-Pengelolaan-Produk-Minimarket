<?php
// periksa apakah user sudah login, cek kehadiran session username
// jika tidak ada, redirect ke login_admin.php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login_admin.php");
    exit;
}

// buka koneksi dengan MySQL
include("connection.php");

// cek apakah form telah di submit
if (isset($_POST["submit"])) {
    // form telah disubmit, proses data
    // ambil semua nilai form
    $nama_produk = htmlentities(strip_tags(trim($_POST["nama_produk"])));
    $harga_produk = htmlentities(strip_tags(trim($_POST["harga_produk"])));
    $stok_produk = htmlentities(strip_tags(trim($_POST["stok_produk"])));
    $deskripsi_produk = htmlentities(strip_tags(trim($_POST["deskripsi_produk"])));
    $gambar_produk = htmlentities(strip_tags(trim($_POST["gambar_produk"])));

    // siapkan variabel untuk menampung pesan error
    $pesan_error = "";

    // cek ke database, apakah sudah ada nama produk yang sama
    // filter data $nim
    $nama_produk = mysqli_real_escape_string($link, $nama_produk);
    $query = "SELECT * FROM produk WHERE nama_produk='$nama_produk'";
    $hasil_query = mysqli_query($link, $query);

    // cek jumlah record (baris), jika ada, $nama_produk tidak bisa diproses
    $jumlah_data = mysqli_num_rows($hasil_query);
    if ($jumlah_data >= 1) {
        $pesan_error .= "Nama Produk yang sama sudah digunakan <br>";
    }

    // cek apakah "nama produk" sudah diisi atau tidak
    if (empty($nama_produk)) {
        $pesan_error .= "Nama Produk belum diisi <br>";
    }

    // cek apakah harga sudah diisi atau tidak
    if (!is_numeric($harga_produk) or ($harga_produk <= 0)) {
        $pesan_error .= "Harga Produk belum diisi <br>";
    }

    // cek apakah stok sudah diisi atau tidak
    if (!is_numeric($stok_produk) or ($stok_produk <= 0)) {
        $pesan_error .= "Stok Produk belum diisi <br>";
    }

    // cek apakah deskripsi sudah diisi atau tidak
    if (empty($deskripsi_produk)) {
        $pesan_error .= "Deskripsi Produk belum diisi <br>";
    }

    // cek apakah url gambar sudah diisi atau tidak
    if (empty($gambar_produk)) {
        $pesan_error .= "Gambar Produk belum diisi <br>";
    }

    // jika tidak ada error, input ke database
    if ($pesan_error === "") {
        // filter semua data
        $nama_produk = mysqli_real_escape_string($link, $nama_produk);
        $harga_produk = mysqli_real_escape_string($link, $harga_produk);
        $stok_produk = mysqli_real_escape_string($link, $stok_produk);
        $deskripsi_produk = mysqli_real_escape_string($link, $deskripsi_produk);
        $gambar_produk = mysqli_real_escape_string($link, $gambar_produk);


        // buat dan jalankan query INSERT
        $query = "INSERT INTO produk VALUES ";
        $query .= "('$nama_produk', '$harga_produk', '$stok_produk', ";
        $query .= "'$deskripsi_produk','$gambar_produk')";
        $result = mysqli_query($link, $query);

        // periksa hasil query
        if ($result) {
            // INSERT berhasil, redirect ke tampil_mahasiswa.php + pesan
            $pesan = "Produk dengan nama = \"<b>$nama_produk</b>\" sudah berhasil di tambah";
            $pesan = urlencode($pesan);
            header("Location: daftar_produk_admin.php?pesan={$pesan}");
            exit;
        } else {
            die("Query gagal dijalankan: " . mysqli_errno($link) . " - " . mysqli_error($link));
        }
    }
} else {
    // form belum disubmit atau halaman ini tampil untuk pertama kali
    // berikan nilai awal untuk semua isian form
    $pesan_error = "";
    $nama_produk = "";
    $harga_produk = "";
    $stok_produk = "";
    $deskripsi_produk = "";
    $gambar_produk = "";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link rel="icon" href="./assets/images/logo.png" type="image/x-icon">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/tambah_produk.css">
</head>

<body>
    <div class="container">
        <div id="header">
            <img class="brand" src="./assets/images/brand.png">
            <p id="tanggal"><?php echo date("d M Y"); ?></p>
        </div>
        <hr>
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
        <form id="search" action="daftar_produk_admin.php" method="get" hidden>
            <p>
                <label for="nama_produk">Nama Produk: </label>
                <input type="text" name="nama" id="nama" placeholder="search...">
                <input type="submit" name="submit" value="Search">
            </p>
        </form>

        <div class="content">
            <h2 class="judul">Tambah Produk</h2>
            <form action="tambah_produk_proses.php" method="POST">
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk:</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo $nama_produk ?>" required>
                </div>
                <div class="mb-3">
                    <label for="harga_produk" class="form-label">Harga Produk:</label>
                    <input type="number" class="form-control" id="harga_produk" name="harga_produk" value="<?php echo $harga_produk ?>" required>
                </div>
                <div class="mb-3">
                    <label for="stok_produk" class="form-label">Stok Produk:</label>
                    <input type="number" class="form-control" id="stok_produk" name="stok_produk" value="<?php echo $stok_produk ?>" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi_produk" class="form-label">Deskripsi Produk:</label>
                    <input type="text" class="form-control" id="deskripsi_produk" name="deskripsi_produk" value="<?php echo $deskripsi_produk ?>" required>
                </div>
                <div class="mb-3">
                    <label for="gambar_produk" class="form-label">Gambar Produk:</label>
                    <input type="text" class="form-control" name="url_gambar" value="<?php echo $gambar_produk ?>" placeholder="Masukkan URL Gambar">
                </div>
                <button type="submit" name="submit" value="Tambah Produk" class="btn btn-primary">Tambah Produk</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>
<?php
// tutup koneksi dengan database mysql
mysqli_close($link);
?>