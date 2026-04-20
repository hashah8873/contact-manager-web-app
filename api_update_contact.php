<?php
include 'db_connect.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$id    = $data['id'];
$name  = $data['name'];
$email = $data['email'];
$phone = $data['phone'];
$cat   = $data['category_id'];

$stmt = $conn->prepare("
UPDATE contacts1
SET name=?, email=?, phone=?, category_id=?
WHERE id=?
");

$stmt->bind_param("sssii", $name, $email, $phone, $cat, $id);
$stmt->execute();

echo json_encode(["status"=>"updated"]);