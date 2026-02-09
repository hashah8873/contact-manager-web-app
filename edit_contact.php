<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// جلب بيانات جهة الاتصال الحالية
$sql = "SELECT * FROM contacts1 WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "Contact not found";
    exit();
}

$contact = mysqli_fetch_assoc($result);

// عند ضغط زر Save
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // رفع الصورة الجديدة إذا اختار المستخدم صورة
    $image_path = $contact['image_path']; // يبقي الصورة القديمة إذا لم يتم رفع صورة جديدة
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploads_dir = 'uploads';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }
        $tmp_name = $_FILES['image']['tmp_name'];
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $destination = $uploads_dir . '/' . $filename;
        if (move_uploaded_file($tmp_name, $destination)) {
            $image_path = $destination;
        }
    }

    $update_sql = "UPDATE contacts1 SET name='$name', email='$email', phone='$phone', image_path='$image_path' WHERE id=$id";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Contact</title>
    <style>
        form { width: 300px; margin: 50px auto; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 5px; }
        button { margin-top: 15px; padding: 5px 10px; }
        img { max-width: 100px; max-height: 100px; object-fit: cover; margin-top: 10px; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Edit Contact</h2>

<form method="post" action="edit_contact.php?id=<?php echo $contact['id']; ?>" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $contact['name']; ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $contact['email']; ?>" required>

    <label>Phone:</label>
    <input type="text" name="phone" value="<?php echo $contact['phone']; ?>" required>

    <label>Image:</label>
    <?php if (!empty($contact['image_path'])): ?>
        <img src="<?php echo $contact['image_path']; ?>" alt="Contact Image">
    <?php endif; ?>
    <input type="file" name="image">

    <button type="submit">Save Changes</button>
</form>

<div style="text-align:center; margin-top:20px;">
    <a href="index.php">Back to Contacts List</a>
</div>

</body>
</html>
