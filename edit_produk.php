<?php
// periksa apakah user sudah login, cek kehadiran session username
// jika tidak ada, redirect ke login_admin.php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login_admin.php");
}
// buka koneksi dengan MySQL
include("connection.php");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
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
        <h2 class="judul">Edit Data Produk</h2>
        <?php
        // tampilkan pesan jika ada
        if ((isset($_GET["pesan"]))) {
            echo "<div class=\"pesan\">{$_GET["pesan"]}</div>";
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
                            <th class="table-th">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Tangkap parameter pencarian
                        $nama_produk = "";
                        if (isset($_GET['nama'])) {
                            $nama_produk = mysqli_real_escape_string($link, $_GET['nama']);
                        }

                        // Modifikasi query SQL berdasarkan parameter pencarian
                        $sql = "SELECT * FROM produk";
                        if ($nama_produk != "") {
                            $sql .= " WHERE nama_produk LIKE '%$nama_produk%'";
                        }
                        $sql .= " ORDER BY nama_produk ASC";

                        $result = mysqli_query($link, $sql);

                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $no . "</td>";
                                    echo "<td>" . $row['nama_produk'] . "</td>";
                                    echo "<td>" . $row['harga_produk'] . "</td>";
                                    echo "<td>" . $row['stok_produk'] . "</td>";
                                    echo "<td>" . $row['deskripsi_produk'] . "</td>";
                                    echo "<td><img src='" . $row['gambar_produk'] . "' style='max-width: 100px; max-height: 100px;' /></td>";
                                    echo "<td><a href='form_edit.php?id=" . $row['id_produk'] . "' class='btn btn-primary'>Edit</a></td>";
                                    echo "</tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='7'>Tidak ada produk yang ditemukan</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Error: " . mysqli_error($link) . "</td></tr>";
                        }

                        mysqli_free_result($result);

                        mysqli_close($link);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>