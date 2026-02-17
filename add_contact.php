<?php
include 'db_connect.php';

/* جلب التصنيفات */
$cats = mysqli_query($conn,"SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

$name = mysqli_real_escape_string($conn,$_POST['name']);
$email = mysqli_real_escape_string($conn,$_POST['email']);
$phone = mysqli_real_escape_string($conn,$_POST['phone']);
$cat   = intval($_POST['category_id']);

$image_path = NULL;

if ($_FILES['image']['error']==0){
    if(!is_dir("uploads")) mkdir("uploads");
    $filename = time().'_'.basename($_FILES['image']['name']);
    $image_path = "uploads/".$filename;
    move_uploaded_file($_FILES['image']['tmp_name'],$image_path);
}

$sql = "INSERT INTO contacts1
(name,email,phone,image_path,category_id)
VALUES
('$name','$email','$phone','$image_path','$cat')";

mysqli_query($conn,$sql);
header("Location:index.php");
exit();
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
</style>
</head>

<body>

<h2 style="text-align:center;">Add Contact</h2>

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
