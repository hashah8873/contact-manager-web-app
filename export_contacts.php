<?php
include 'db_connect.php';

/* Tell browser to download file */
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="contacts.csv"');

/* Open file output */
$output = fopen("php://output", "w");

/* CSV column headers */
fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Category']);

/* Query contacts with category */
$sql = "
SELECT contacts1.id, contacts1.name, contacts1.email, contacts1.phone, categories.category_name
FROM contacts1
LEFT JOIN categories
ON contacts1.category_id = categories.category_id
ORDER BY contacts1.id DESC
";

$result = mysqli_query($conn, $sql);

/* Write rows to CSV */
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

/* Close output */
fclose($output);
exit();
?>