<?php
include 'db_connect.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$sql = "
SELECT c.*, cat.category_name
FROM contacts1 c
LEFT JOIN categories cat ON c.category_id = cat.category_id
ORDER BY c.id DESC
";

$result = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);