<?php
//koneksi dengan database mysql
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "minimartz";
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

//periksa koneksi, tampilkan pesan jika gagal
if (!$link) {
    die ("Koneksi dengan database gagal: ".mysqli_connect_errno(). " - ".mysqli_connect_error());
}
?>