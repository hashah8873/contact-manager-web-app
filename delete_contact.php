<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

/* Get image path first */
$stmt = $conn->prepare("SELECT image_path FROM contacts1 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();
    $image = $row['image_path'];

    /* Delete image from server if exists */
    if (!empty($image) && file_exists($image)) {
        unlink($image);
    }

    /* Delete contact from database */
    $delete = $conn->prepare("DELETE FROM contacts1 WHERE id = ?");
    $delete->bind_param("i", $id);
    $delete->execute();
    $delete->close();
}

$stmt->close();

/* Redirect back */
header("Location: index.php");
exit();
?>