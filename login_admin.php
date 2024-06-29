<?php

// ambil pesan jika ada
if (isset($_GET["pesan"])) {
    $pesan = $_GET["pesan"];
}

// cek apakah form telah di submit
if (isset($_POST["submit"])) {
    // form telah disubmit, proses data

    // ambil nilai form
    $username = htmlentities(strip_tags(trim($_POST["username"])));
    $password = htmlentities(strip_tags(trim($_POST["password"])));

    // siapkan variabel untuk menampung pesan error
    $pesan_error = "";

    // cek apakah "username" sudah diisi atau tidak
    if (empty($username)) {
        $pesan_error .= "Username belum diisi <br>";
    }

    // cek apakah "password" sudah diisi atau tidak
    if (empty($password)) {
        $pesan_error .= "Password belum diisi <br>";
    }

    // buat koneksi ke mysql dari file connection.php
    include("connection.php");

    // filter dengan mysqli_real_escape_string
    $username = mysqli_real_escape_string($link, $username);
    $password = mysqli_real_escape_string($link, $password);

    // generate hashing
    $password_sha1 = sha1($password);

    // cek apakah username dan password ada di tabel admin
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password_sha1'";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) == 0) {
        // data tidak ditemukan, buat pesan error
        $pesan_error .= "Username dan/atau Password tidak sesuai";
    }

    // bebaskan memory
    mysqli_free_result($result);

    // tutup koneksi dengan database MySQL
    mysqli_close($link);

    // jika lolos validasi, set session
    if ($pesan_error === "") {
        session_start();
        $_SESSION["username"] = $username;
        header("Location: daftar_produk_admin.php");
    }
} else {

    // form belum disubmit atau halaman ini tampil untuk pertama kali
    // berikan nilai awal untuk semua isian form
    $pesan_error = "";
    $username = "";
    $password = "";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>MiniMartZ - Login Admin</title>
    <link rel="icon" href="./assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/login_user.css">
</head>

<body>
    <div class="container">
        <img src="./assets/images/brand.png" />
        <h3 class="judul">Login sebagai Admin</h3>
        <?php
        // tampilkan pesan jika ada
        if (isset($pesan)) {
            echo "<div class=\"pesan\">$pesan</div>";
        }

        // tampilkan error jika ada
        if ($pesan_error !== "") {
            echo "<div class=\"error\">$pesan_error</div>";
        }
        ?>

        <form action="login_admin.php" method="post">
            <p>
                <label for="username">Username : </label>
                <input type="text" name="username" id="username" value="<?php echo $username ?>" placeholder="Masukkan username anda" required>

            </p>
            <p>
                <label for="password">Password : </label>
                <input type="password" name="password" id="password" value="<?php echo $username ?>"  placeholder="Masukkan password anda" required>

            </p>
            <p>
                <input type="submit" name="submit" value="Log In">
            </p>
        </form>
    </div>
</body>

</html>