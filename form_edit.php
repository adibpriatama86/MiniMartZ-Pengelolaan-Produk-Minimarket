<?php
// Periksa apakah user sudah login, cek kehadiran session name
// Jika tidak ada, redirect ke login
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}

// Buka koneksi dengan mysql
include("connection.php");

// Periksa apakah parameter id telah diterima
if (isset($_GET['id'])) {
    // Ambil nilai id_produk
    $id_produk = htmlentities(strip_tags(trim($_GET["id"])));
    // Filter data
    $id_produk = mysqli_real_escape_string($link, $id_produk);

    // Ambil semua data dari database untuk menjadi nilai awal form
    $query = "SELECT * FROM produk WHERE id_produk = '$id_produk'";
    $result = mysqli_query($link, $query);

    if (!$result) {
        die("Query Error: " . mysqli_errno($link) . " - " . mysqli_error($link));
    }

    // Tidak perlu perulangan while karena hanya ada 1 record
    $data = mysqli_fetch_assoc($result);

    $nama_produk = $data["nama_produk"];
    $harga_produk = $data["harga_produk"];
    $stok_produk = $data["stok_produk"];
    $deskripsi_produk = $data["deskripsi_produk"];
    $gambar_produk = $data["gambar_produk"];

    // Bebaskan memori
    mysqli_free_result($result);
} elseif (isset($_POST["submit"]) && $_POST["submit"] == "Update Produk") {
    // Nilai form berasal dari halaman formEdit
    // Ambil semua nilai form
    $id_produk = htmlentities(strip_tags(trim($_POST["id_produk"])));
    $nama_produk = htmlentities(strip_tags(trim($_POST["nama_produk"])));
    $harga_produk = htmlentities(strip_tags(trim($_POST["harga_produk"])));
    $stok_produk = htmlentities(strip_tags(trim($_POST["stok_produk"])));
    $deskripsi_produk = htmlentities(strip_tags(trim($_POST["deskripsi_produk"])));
    $gambar_produk = htmlentities(strip_tags(trim($_POST["gambar_produk"])));

    // Proses validasi form
    // Siapkan variabel untuk menampung pesan error
    $pesan_error = "";

    // Cek apakah nama produk sudah diisi
    if (empty($nama_produk)) {
        $pesan_error .= "Nama Produk belum diisi <br>";
    }

    // Cek harga
    if (!is_numeric($harga_produk) or ($harga_produk <= 0)) {
        $pesan_error .= "Harga Produk harus diisi dengan angka yang lebih dari 0<br>";
    }

    // Cek stok
    if (!is_numeric($stok_produk) or ($stok_produk <= 0)) {
        $pesan_error .= "Stok Produk harus diisi dengan angka yang lebih dari 0<br>";
    }

    // Cek deskripsi
    if (empty($deskripsi_produk)) {
        $pesan_error .= "Deskripsi Produk belum diisi <br>";
    }

    // Cek gambar
    if (empty($gambar_produk)) {
        $pesan_error .= "Gambar Produk belum diisi <br>";
    }

    // Jika tidak ada error, proses update ke database
    if ($pesan_error === "") {
        // Filter semua data sebelum disimpan
        $id_produk = mysqli_real_escape_string($link, $id_produk);
        $nama_produk = mysqli_real_escape_string($link, $nama_produk);
        $harga_produk = mysqli_real_escape_string($link, $harga_produk);
        $stok_produk = mysqli_real_escape_string($link, $stok_produk);
        $deskripsi_produk = mysqli_real_escape_string($link, $deskripsi_produk);
        $gambar_produk = mysqli_real_escape_string($link, $gambar_produk);

        // Update data
        $query = "UPDATE produk SET ";
        $query .= "nama_produk = '$nama_produk', harga_produk = '$harga_produk', stok_produk = '$stok_produk', ";
        $query .= "deskripsi_produk = '$deskripsi_produk', gambar_produk = '$gambar_produk' ";
        $query .= "WHERE id_produk = '$id_produk'";

        $result = mysqli_query($link, $query);

        if ($result) {
            // Berhasil di-update, redirect ke daftar_produk.php + pesan
            $pesan = "Produk dengan nama = \"<b>$nama_produk</b>\" sudah berhasil di update";
            $pesan = urlencode($pesan);
            header("Location: daftar_produk_admin.php?pesan={$pesan}");
            exit;
        } else {
            die("Query gagal dijalankan: " . mysqli_errno($link) . " - " . mysqli_error($link));
        }
    }
} else {
    // Jika tidak ada parameter id di URL dan tidak ada form yang dikirim
    // Redirect ke halaman edit_produk.php
    header("Location: edit_produk.php");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="icon" href="./assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/edit_produk.css">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
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
            <h1>Edit Produk</h1>
            <?php
            if (isset($pesan_error) && $pesan_error !== "") {
                echo "<div class=\"alert alert-danger\" role=\"alert\">$pesan_error</div>";
            }
            ?>
            <form id="formEdit" action="form_edit.php" method="post">
                <input type="hidden" name="id_produk" value="<?php echo $id_produk; ?>">
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo $nama_produk; ?>">
                </div>
                <div class="mb-3">
                    <label for="harga_produk" class="form-label">Harga Produk</label>
                    <input type="number" class="form-control" id="harga_produk" name="harga_produk" value="<?php echo $harga_produk; ?>">
                </div>
                <div class="mb-3">
                    <label for="stok_produk" class="form-label">Stok Produk</label>
                    <input type="number" class="form-control" id="stok_produk" name="stok_produk" value="<?php echo $stok_produk; ?>">
                </div>
                <div class="mb-3">
                    <label for="deskripsi_produk" class="form-label">Deskripsi Produk</label>
                    <textarea class="form-control" id="deskripsi_produk" name="deskripsi_produk" rows="3"><?php echo $deskripsi_produk; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="gambar_produk" class="form-label">Gambar Produk</label>
                    <input type="text" class="form-control" id="gambar_produk" name="gambar_produk" value="<?php echo $gambar_produk; ?>">
                </div>
                <button type="submit" name="submit" class="btn btn-primary" value="Update Produk">Update Produk</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>