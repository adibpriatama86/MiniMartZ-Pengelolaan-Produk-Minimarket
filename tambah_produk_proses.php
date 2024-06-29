<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $harga_produk = $_POST['harga_produk'];
    $stok_produk = $_POST['stok_produk'];
    $deskripsi_produk = $_POST['deskripsi_produk'];

    // Ambil URL gambar dari form
    $url_gambar = $_POST['url_gambar'];

    // Simpan data ke database
    $query = "INSERT INTO produk (nama_produk, harga_produk, stok_produk, deskripsi_produk, gambar_produk)
            VALUES ('$nama_produk', '$harga_produk', '$stok_produk', '$deskripsi_produk', '$url_gambar')";
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

mysqli_close($link);
