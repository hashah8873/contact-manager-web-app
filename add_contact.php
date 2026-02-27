<?php
include 'db_connect.php';

$cats = mysqli_query($conn, "SELECT * FROM categories");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $cat   = intval($_POST['category_id']);

    /* Validation */
    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($phone)) {
        $errors[] = "Phone is required.";
    }

    if (empty($errors)) {

        $image_path = NULL;

        /* Image Upload */
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

            $allowed = ['jpg','jpeg','png','gif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (in_array($ext,$allowed)) {

                if (!is_dir("uploads")) {
                    mkdir("uploads");
                }

                $filename = time().'_'.basename($_FILES['image']['name']);
                $image_path = "uploads/".$filename;

                move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
            }
        }

        /* Prepared Statement */
        $stmt = $conn->prepare("
            INSERT INTO contacts1
            (name,email,phone,image_path,category_id)
            VALUES (?,?,?,?,?)
        ");

        $stmt->bind_param("ssssi", $name, $email, $phone, $image_path, $cat);

        $stmt->execute();
        $stmt->close();

        header("Location:index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Contact</title>
<style>
form{width:320px;margin:40px auto;}
label{display:block;margin-top:10px;}
input,select{width:100%;padding:6px;}
.error{color:red;text-align:center;}
</style>
</head>

<body>

<h2 style="text-align:center;">Add Contact</h2>

<?php if(!empty($errors)): ?>
<div class="error">
<?php foreach($errors as $e): ?>
<p><?= $e ?></p>
<?php endforeach; ?>
</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

<label>Name</label>
<input name="name" required>

<label>Email</label>
<input name="email" type="email" required>

<label>Phone</label>
<input name="phone" required>

<label>Category</label>
<select name="category_id" required>
<?php while($c=mysqli_fetch_assoc($cats)): ?>
<option value="<?= $c['category_id'] ?>">
<?= $c['category_name'] ?>
</option>
<?php endwhile; ?>
</select>

<label>Image</label>
<input type="file" name="image" accept="image/*">

<br><br>
<button>Save Contact</button>

</form>

</body>
</html>