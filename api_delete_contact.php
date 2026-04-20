<?php
include 'db_connect.php';

header("Access-Control-Allow-Origin: *");

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM contacts1 WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode(["status"=>"deleted"]);