<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "contact_manager";

// إنشاء الاتصال
$conn = mysqli_connect($host, $user, $password, $database);

// التحقق من الاتصال
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
