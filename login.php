<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

// fallback إذا ما وصل JSON
if (!$data) {
    $data = $_POST;
}

// 👇 غيّري هون البيانات
$correctEmail = "hiba@gmail.com";
$correctPassword = "123123";

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if ($email === $correctEmail && $password === $correctPassword) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>