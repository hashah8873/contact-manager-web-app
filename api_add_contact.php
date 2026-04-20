<?php
include 'db_connect.php';

header("Access-Control-Allow-Origin: *");

$name  = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$cat   = $_POST['category_id'];

$image_path = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

    $filename = time() . "_" . $_FILES['image']['name'];

    if (!is_dir("uploads")) {
        mkdir("uploads");
    }

    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $filename);

    $image_path = "uploads/" . $filename;
}

$stmt = $conn->prepare("
INSERT INTO contacts1 (name,email,phone,image_path,category_id)
VALUES (?,?,?,?,?)
");

$stmt->bind_param("ssssi", $name, $email, $phone, $image_path, $cat);
$stmt->execute();

echo json_encode(["status"=>"ok"]);