<?php
include 'db_connect.php';

$id = intval($_GET['id']);

$sql = "
SELECT contacts1.*, categories.category_name
FROM contacts1
LEFT JOIN categories
ON contacts1.category_id = categories.category_id
WHERE contacts1.id = $id
";

$q = mysqli_query($conn,$sql);

if(mysqli_num_rows($q)==0){
die("Contact not found");
}

$c = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html>
<head>
<title>Contact Details</title>

<style>
body{font-family:Arial;background:#fafafa;}
.card{
width:420px;
margin:40px auto;
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
text-align:center;
}
img{
width:140px;
height:140px;
object-fit:cover;
border-radius:12px;
margin-bottom:15px;
}
</style>

</head>

<body>

<div class="card">

<h2>Contact Details</h2>

<?php if($c['image_path']): ?>
<img src="<?= $c['image_path'] ?>">
<?php endif; ?>

<p><strong>Name:</strong> <?= $c['name'] ?></p>
<p><strong>Email:</strong> <?= $c['email'] ?></p>
<p><strong>Phone:</strong> <?= $c['phone'] ?></p>
<p><strong>Category:</strong> <?= $c['category_name'] ?? '—' ?></p>

<br>

<a href="edit_contact.php?id=<?= $c['id'] ?>">Edit</a>
 |
<a href="index.php">Back to list</a>

</div>

</body>
</html>
