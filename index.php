<?php
include 'db_connect.php';

/* عدد العناصر في كل صفحة */
$limit = 5;

/* الصفحة الحالية */
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

/* البحث والفلترة */
$search = $_GET['search'] ?? '';
$catfilter = $_GET['cat'] ?? '';

$where = "WHERE 1";
$params = [];
$types = "";

/* شروط البحث */
if ($search) {
    $where .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

/* فلترة حسب التصنيف */
if ($catfilter) {
    $where .= " AND contacts1.category_id = ?";
    $params[] = $catfilter;
    $types .= "i";
}

/* حساب عدد الصفحات */
$count_sql = "SELECT COUNT(*) as total FROM contacts1 $where";
$count_stmt = $conn->prepare($count_sql);

if ($types) {
    $count_stmt->bind_param($types, ...$params);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$count_stmt->close();

$total_pages = ceil($total_rows / $limit);

/* جلب البيانات مع Pagination */
$sql = "
SELECT contacts1.*, categories.category_name
FROM contacts1
LEFT JOIN categories
ON contacts1.category_id = categories.category_id
$where
ORDER BY contacts1.id DESC
LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);

$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

/* قائمة التصنيفات */
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
.pagination{text-align:center;margin:20px;}
.pagination a{
padding:8px 12px;
border:1px solid #ccc;
margin:2px;
text-decoration:none;
color:black;
}
.pagination a.active{
background:#333;
color:white;
}
</style>
</head>
<body>

<h2 style="text-align:center;">Contact Manager</h2>

<div class="topbar">
<a href="add_contact.php">➕ Add Contact</a>
</div>

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

<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>

<td>
<?php if($row['image_path']): ?>
<img src="<?= $row['image_path'] ?>">
<?php else: ?> N/A <?php endif; ?>
</td>

<td>
<a href="contact.php?id=<?= $row['id'] ?>">
<?= htmlspecialchars($row['name']) ?>
</a>
</td>

<td><?= htmlspecialchars($row['email']) ?></td>
<td><?= htmlspecialchars($row['phone']) ?></td>
<td><?= $row['category_name'] ?? '—' ?></td>

<td>
<a href="edit_contact.php?id=<?= $row['id'] ?>">Edit</a> |
<a href="delete_contact.php?id=<?= $row['id'] ?>"
onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>

<div class="pagination">
<?php for ($i=1; $i <= $total_pages; $i++): ?>
<a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&cat=<?= $catfilter ?>"
class="<?= $i==$page?'active':'' ?>">
<?= $i ?>
</a>
<?php endfor; ?>
</div>

</body>
</html>