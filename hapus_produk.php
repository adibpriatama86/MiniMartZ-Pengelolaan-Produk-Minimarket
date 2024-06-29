<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login_admin.php");
    exit();
}

include("connection.php");

if (isset($_POST["submit"])) {
    $id_produk = htmlentities(strip_tags(trim($_POST["id_produk"])));
    $id_produk = mysqli_real_escape_string($link, $id_produk);

    $query = "DELETE FROM produk WHERE id_produk = '$id_produk'";
    $hasil_query = mysqli_query($link, $query);

    if ($hasil_query) {
        $pesan = "Produk dengan ID = <b>$id_produk</b> sudah berhasil dihapus.";
        $pesan = urlencode($pesan);
        header("Location: daftar_produk_admin.php?pesan={$pesan}");
        exit();
    } else {
        die("Query gagal dijalankan: " . mysqli_errno($link) . " - " . mysqli_error($link));
    }
}

$query_produk = "SELECT * FROM produk ORDER BY nama_produk ASC";
$result_produk = mysqli_query($link, $query_produk);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Produk</title>
    <link rel="icon" href="./assets/images/logo.png" type="image/x-icon">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/hapus_produk.css">
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
            <h2 class="judul">Hapus Produk</h2>

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
                            if ($result_produk) {
                                if (mysqli_num_rows($result_produk) > 0) {
                                    $no = 1;
                                    while ($row_produk = mysqli_fetch_assoc($result_produk)) {
                                        echo "<tr>";
                                        echo "<td>" . $no . "</td>";
                                        echo "<td>" . $row_produk['nama_produk'] . "</td>";
                                        echo "<td>" . $row_produk['harga_produk'] . "</td>";
                                        echo "<td>" . $row_produk['stok_produk'] . "</td>";
                                        echo "<td>" . $row_produk['deskripsi_produk'] . "</td>";
                                        echo "<td><img src='" . $row_produk['gambar_produk'] . "' style='max-width: 100px; max-height: 100px;' /></td>";
                                        echo "<td>
                                              <button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#confirmDeleteModal' data-id_produk='" . $row_produk['id_produk'] . "'>Hapus</button>
                                              </td>";
                                        echo "</tr>";
                                        $no++;
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Tidak ada produk yang ditemukan</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Error: " . mysqli_error($link) . "</td></tr>";
                            }
                            mysqli_close($link);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="hapus_produk.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus produk ini?
                        <input type="hidden" name="id_produk" id="id_produk">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger" name="submit" value="delete">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script>
        var confirmDeleteModal = document.getElementById('confirmDeleteModal');
        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id_produk = button.getAttribute('data-id_produk');
            var modalBodyInput = confirmDeleteModal.querySelector('.modal-body #id_produk');
            modalBodyInput.value = id_produk;
        });
    </script>
</body>

</html>
