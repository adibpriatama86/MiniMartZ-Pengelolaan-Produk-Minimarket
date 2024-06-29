<?php
session_start();

// hapus session
unset($_SESSION["username"]);

// redirect ke halaman login.php
header("Location: index.php");
?>