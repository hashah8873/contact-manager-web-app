<?php
include 'db_connect.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$result = mysqli_query($conn, "SELECT * FROM categories");

$data = [];

while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}

echo json_encode($data);