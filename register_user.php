<?php

// ambil pesan jika ada
if (isset($_GET["pesan"])) {
    $pesan = $_GET["pesan"];
}

// cek apakah form telah di submit
if (isset($_POST["submit"])) {
    // form telah disubmit, proses data

    // ambil nilai form
    $nama = htmlentities(strip_tags(trim($_POST["nama"])));
    $email = htmlentities(strip_tags(trim($_POST["email"])));
    $tanggal_lahir = htmlentities(strip_tags(trim($_POST["tanggal_lahir"])));
    $username = htmlentities(strip_tags(trim($_POST["username"])));
    $password = htmlentities(strip_tags(trim($_POST["password"])));

    // siapkan variabel untuk menampung pesan error
    $pesan_error = "";

    // cek apakah semua field sudah diisi atau tidak
    if (empty($nama)) {
        $pesan_error .= "Nama lengkap belum diisi <br>";
    }
    if (empty($email)) {
        $pesan_error .= "Email belum diisi <br>";
    }
    if (empty($tanggal_lahir)) {
        $pesan_error .= "Tanggal lahir belum diisi <br>";
    }
    if (empty($username)) {
        $pesan_error .= "Username belum diisi <br>";
    }
    if (empty($password)) {
        $pesan_error .= "Password belum diisi <br>";
    }

    // buat koneksi ke mysql dari file connection.php
    include("connection.php");

    // filter dengan mysqli_real_escape_string
    $nama = mysqli_real_escape_string($link, $nama);
    $email = mysqli_real_escape_string($link, $email);
    $tanggal_lahir = mysqli_real_escape_string($link, $tanggal_lahir);
    $username = mysqli_real_escape_string($link, $username);
    $password = mysqli_real_escape_string($link, $password);

    // generate hashing
    $password_sha1 = sha1($password);

    // cek apakah username sudah digunakan
    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0) {
        // username sudah digunakan, buat pesan error
        $pesan_error .= "Username sudah digunakan <br>";
    } else {
        // masukkan data ke database
        $query = "INSERT INTO user (nama, email, tanggal_lahir, username, password) VALUES ('$nama', '$email', '$tanggal_lahir', '$username', '$password_sha1')";
        $result = mysqli_query($link, $query);

        if ($result) {
            // data berhasil dimasukkan, buat pesan sukses
            $pesan = "Pendaftaran berhasil! Silakan login.";
            header("Location: login_user.php?pesan=" . urlencode($pesan));
            exit();
        } else {
            // terjadi kesalahan saat memasukkan data
            $pesan_error .= "Terjadi kesalahan saat mendaftar. Silakan coba lagi.";
        }
    }

    // bebaskan memory
    mysqli_free_result($result);

    // tutup koneksi dengan database MySQL
    mysqli_close($link);
} else {

    // form belum disubmit atau halaman ini tampil untuk pertama kali
    // berikan nilai awal untuk semua isian form
    $pesan_error = "";
    $nama = "";
    $email = "";
    $tanggal_lahir = "";
    $username = "";
    $password = "";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>MiniMartZ - Register User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/register_user.css">
    <link rel="icon" href="./assets/images/logo.png" type="image/x-icon">
</head>

<body>
    <div class="container">
        <img src="./assets/images/brand.png" />
        <h3 class="judul">Registrasi</h3>
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

        <form action="register_user.php" method="post">
            <p>
                <label for="nama">Nama Lengkap : </label>
                <input type="text" name="nama" id="nama" value="<?php echo $nama ?>" placeholder="Masukkan Nama Lengkap Anda" required>

            </p>
            <p>
                <label for="email">Email : </label>
                <input type="email" name="email" id="email" value="<?php echo $email ?>" placeholder="Masukkan Email Anda" required>

            </p>
            <p>
                <label for="tanggal_lahir">Tanggal Lahir : </label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="<?php echo $tanggal_lahir ?>" required>
            </p>
            <p>
                <label for="username">Username : </label>
                <input type="text" name="username" id="username" value="<?php echo $username ?>" placeholder="Buat Username Anda" required>

            </p>
            <p>
                <label for="password">Password : </label>
                <input type="password" name="password" id="password" value="<?php echo $password ?>" placeholder="Buat Password Anda" required>

            </p>
            <p>
                <input type="submit" name="submit" value="Daftar">
            </p>
        </form>
    </div>
</body>

</html>