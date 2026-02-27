<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

/* Get categories */
$cats = mysqli_query($conn, "SELECT * FROM categories");

/* Get contact using Prepared Statement */
$stmt = $conn->prepare("SELECT * FROM contacts1 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Contact not found");
}

$contact = $result->fetch_assoc();
$stmt->close();

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

        $image_path = $contact['image_path'];

        /* Image Upload */
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

            $allowed = ['jpg','jpeg','png','gif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (in_array($ext,$allowed)) {

                if (!is_dir("uploads")) {
                    mkdir("uploads");
                }

                $filename = time().'_'.basename($_FILES['image']['name']);
                $destination = "uploads/".$filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $image_path = $destination;
                }
            }
        }

        /* Update using Prepared Statement */
        $update = $conn->prepare("
            UPDATE contacts1
            SET name=?, email=?, phone=?, category_id=?, image_path=?
            WHERE id=?
        ");

        $update->bind_param(
            "sssisi",
            $name,
            $email,
            $phone,
            $cat,
            $image_path,
            $id
        );

        $update->execute();
        $update->close();

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Contact</title>
<style>
form{width:320px;margin:40px auto;}
label{display:block;margin-top:10px;}
input,select{width:100%;padding:6px;}
img{max-width:100px;margin-top:10px;}
.error{color:red;text-align:center;}
</style>
</head>

<body>

<h2 style="text-align:center;">Edit Contact</h2>

<?php if(!empty($errors)): ?>
<div class="error">
<?php foreach($errors as $e): ?>
<p><?= $e ?></p>
<?php endforeach; ?>
</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

<label>Name</label>
<input name="name" value="<?= htmlspecialchars($contact['name']) ?>" required>

<label>Email</label>
<input name="email" type="email" value="<?= htmlspecialchars($contact['email']) ?>" required>

<label>Phone</label>
<input name="phone" value="<?= htmlspecialchars($contact['phone']) ?>" required>

<label>Category</label>
<select name="category_id" required>
<?php while($c=mysqli_fetch_assoc($cats)): ?>
<option value="<?= $c['category_id'] ?>"
<?= $contact['category_id']==$c['category_id']?'selected':'' ?>>
<?= $c['category_name'] ?>
</option>
<?php endwhile; ?>
</select>

<label>Image</label>
<?php if($contact['image_path']): ?>
<img src="<?= $contact['image_path'] ?>">
<?php endif; ?>
<input type="file" name="image" accept="image/*">

<br><br>
<button>Save Changes</button>

</form>

</body>
</html>