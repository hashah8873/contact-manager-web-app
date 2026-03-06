<?php
include 'db_connect.php';

/* عدد جهات الاتصال */
$total_contacts = $conn->query("SELECT COUNT(*) as total FROM contacts1")->fetch_assoc()['total'];

/* عدد التصنيفات */
$total_categories = $conn->query("SELECT COUNT(*) as total FROM categories")->fetch_assoc()['total'];

/* آخر 5 جهات اتصال */
$recent = $conn->query("
SELECT contacts1.*, categories.category_name
FROM contacts1
LEFT JOIN categories
ON contacts1.category_id = categories.category_id
ORDER BY contacts1.id DESC
LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Dashboard</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
margin:0;
}

h1{
text-align:center;
margin-top:30px;
}

.container{
width:90%;
margin:auto;
}

.cards{
display:flex;
justify-content:center;
gap:40px;
margin:30px;
}

.card{
background:white;
padding:30px;
width:220px;
text-align:center;
border-radius:10px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

.card h2{
margin:0;
font-size:40px;
color:#3498db;
}

.card p{
margin-top:10px;
font-weight:bold;
}

table{
border-collapse:collapse;
width:100%;
background:white;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

th,td{
border:1px solid #eee;
padding:12px;
}

th{
background:#2c3e50;
color:white;
}

img{
width:50px;
height:50px;
object-fit:cover;
border-radius:6px;
}

.toplink{
text-align:center;
margin:20px;
}

.toplink a{
background:#3498db;
color:white;
padding:10px 15px;
text-decoration:none;
border-radius:5px;
}

</style>

</head>

<body>

<h1>Contact Manager Dashboard</h1>

<div class="container">

<div class="cards">

<div class="card">
<h2><?php echo $total_contacts; ?></h2>
<p>Total Contacts</p>
</div>

<div class="card">
<h2><?php echo $total_categories; ?></h2>
<p>Total Categories</p>
</div>

</div>

<div class="toplink">
<a href="index.php">Open Contact Manager</a>
</div>

<h2>Recent Contacts</h2>

<table>

<tr>
<th>Image</th>
<th>Name</th>
<th>Email</th>
<th>Category</th>
</tr>

<?php while($r = $recent->fetch_assoc()): ?>

<tr>

<td>
<?php if($r['image_path']): ?>
<img src="<?= $r['image_path'] ?>">
<?php endif; ?>
</td>

<td><?= htmlspecialchars($r['name']) ?></td>
<td><?= htmlspecialchars($r['email']) ?></td>
<td><?= $r['category_name'] ?? '-' ?></td>

</tr>

<?php endwhile; ?>

</table>

</div>

</body>
</html>