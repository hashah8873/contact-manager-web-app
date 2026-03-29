<?php
include 'db_connect.php';

/* Statistics */
$total_contacts = $conn->query("SELECT COUNT(*) as total FROM contacts1")->fetch_assoc()['total'];
$total_categories = $conn->query("SELECT COUNT(*) as total FROM categories")->fetch_assoc()['total'];

/* Pagination */
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

/* Search & Filter */
$search = $_GET['search'] ?? '';
$catfilter = $_GET['cat'] ?? '';

$where = "WHERE 1";
$params = [];
$types = "";

/* Search */
if ($search) {
    $where .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

/* Category */
if ($catfilter) {
    $where .= " AND contacts1.category_id = ?";
    $params[] = $catfilter;
    $types .= "i";
}

/* Count */
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

/* Get Data */
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

/* Categories */
$catlist = mysqli_query($conn,"SELECT * FROM categories");
?>

<!DOCTYPE html>
<html>

<head>
<title>Contact Manager System</title>

<style>
body{font-family:Arial;background:#f4f6f9;margin:0;}
h2{text-align:center;margin-top:25px;}
.topbar{text-align:center;margin:20px;}
.topbar a{
background:#3498db;color:white;padding:10px 15px;
border-radius:6px;text-decoration:none;font-weight:bold;margin:5px;
}
.topbar a:hover{background:#2980b9;}
.stats{display:flex;justify-content:center;gap:30px;margin-bottom:25px;}
.card{
background:white;padding:25px;width:200px;text-align:center;
border-radius:10px;box-shadow:0 3px 10px rgba(0,0,0,0.1);
}
.card h3{margin:0;font-size:32px;color:#3498db;}
form{display:inline-block;}
input,select{padding:7px;border:1px solid #ccc;border-radius:5px;}
button{
padding:7px 12px;background:#27ae60;color:white;
border:none;border-radius:5px;cursor:pointer;
}
button:hover{background:#1e8449;}
table{
border-collapse:collapse;width:90%;margin:auto;background:white;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
}
th,td{border:1px solid #eee;padding:12px;text-align:left;}
th{background:#2c3e50;color:white;}
tr:hover{background:#f9f9f9;}
img{width:55px;height:55px;object-fit:cover;border-radius:8px;}
.pagination{text-align:center;margin:25px;}
.pagination a{
padding:8px 12px;border:1px solid #ccc;margin:3px;
text-decoration:none;color:black;border-radius:4px;
}
.pagination a.active{background:#2c3e50;color:white;}
.success{
text-align:center;
background:#2ecc71;
color:white;
padding:10px;
margin:10px auto;
width:50%;
border-radius:5px;
}
footer{text-align:center;margin:30px;color:#777;}
</style>

</head>

<body>

<h2>Contact Manager System</h2>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
<div class="success">
Contact deleted successfully
</div>
<?php endif; ?>

<div class="topbar">
<a href="dashboard.php">Dashboard</a>
<a href="add_contact.php">Add Contact</a>
<a href="export_contacts.php">Export CSV</a>
</div>

<div class="stats">
<div class="card">
<h3><?= $total_contacts ?></h3>
<p>Total Contacts</p>
</div>

<div class="card">
<h3><?= $total_categories ?></h3>
<p>Total Categories</p>
</div>
</div>

<div class="topbar">
<form>
<input name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">

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
<?php if(!empty($row['image_path']) && file_exists($row['image_path'])): ?>
<img src="<?= $row['image_path'] ?>">
<?php else: ?>
<img src="https://via.placeholder.com/55">
<?php endif; ?>
</td>

<td>
<a href="contact.php?id=<?= $row['id'] ?>">
<?= htmlspecialchars($row['name']) ?>
</a>
</td>

<td><?= htmlspecialchars($row['email']) ?></td>
<td><?= htmlspecialchars($row['phone']) ?></td>
<td><?= $row['category_name'] ?? '-' ?></td>

<td>
<a href="edit_contact.php?id=<?= $row['id'] ?>">Edit</a> |
<a href="delete_contact.php?id=<?= $row['id'] ?>"
onclick="return confirm('Are you sure you want to delete this contact?')">
Delete
</a>
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

<footer>
Capstone Project – Contact Manager System
</footer>

</body>
</html>