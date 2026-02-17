<?php
include 'db_connect.php';

/* قراءة البحث والفلتر */
$search = $_GET['search'] ?? '';
$catfilter = $_GET['cat'] ?? '';

$sql = "
SELECT contacts1.*, categories.category_name
FROM contacts1
LEFT JOIN categories
ON contacts1.category_id = categories.category_id
WHERE 1
";

if($search){
$q = mysqli_real_escape_string($conn,$search);
$sql .= " AND (
name LIKE '%$q%' OR
email LIKE '%$q%' OR
phone LIKE '%$q%'
)";
}

if($catfilter){
$sql .= " AND contacts1.category_id=".(int)$catfilter;
}

$sql .= " ORDER BY contacts1.id DESC";

$result = mysqli_query($conn,$sql);

/* قائمة التصنيفات للفلتر */
$catlist = mysqli_query($conn,"SELECT * FROM categories");
?>

<!DOCTYPE html>
<html>
<head>
<title>Contact Manager</title>

<style>
body{font-family:Arial;background:#fafafa;}
table{border-collapse:collapse;width:90%;margin:auto;background:white;}
th,td{border:1px solid #ddd;padding:10px;}
th{background:#f2f2f2;}
tr:hover{background:#f9f9f9;}
img{width:55px;height:55px;object-fit:cover;border-radius:6px;}
.topbar{text-align:center;margin:15px;}
input,select{padding:6px;}
</style>

</head>
<body>

<h2 style="text-align:center;">Contact Manager</h2>

<div class="topbar">
<a href="add_contact.php">➕ Add Contact</a>
</div>

<!-- SEARCH + FILTER -->

<div class="topbar">
<form>

<input name="search"
placeholder="Search..."
value="<?= htmlspecialchars($search) ?>">

<select name="cat">
<option value="">All Categories</option>

<?php while($c=mysqli_fetch_assoc($catlist)): ?>
<option value="<?= $c['category_id'] ?>"
<?= $catfilter==$c['category_id']?'selected':'' ?>>
<?= $c['category_name'] ?>
</option>
<?php endwhile; ?>

</select>

<button>Apply</button>

</form>
</div>

<table>

<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Category</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)): ?>
<tr>

<td><?= $row['id'] ?></td>

<td>
<?php if($row['image_path']): ?>
<img src="<?= $row['image_path'] ?>">
<?php else: ?> N/A <?php endif; ?>
</td>

<td>
<a href="contact.php?id=<?= $row['id'] ?>">
<?= $row['name'] ?>
</a>
</td>

<td><?= $row['email'] ?></td>
<td><?= $row['phone'] ?></td>
<td><?= $row['category_name'] ?? '—' ?></td>

<td>
<a href="edit_contact.php?id=<?= $row['id'] ?>">Edit</a> |
<a href="delete_contact.php?id=<?= $row['id'] ?>"
onclick="return confirm('Delete?')">Delete</a>
</td>

</tr>
<?php endwhile; ?>

</table>

</body>
</html>
