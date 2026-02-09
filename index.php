<?php
include 'db_connect.php';

$result = mysqli_query($conn, "SELECT * FROM contacts1");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Contacts</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background: #f2f2f2; }
        img { width: 50px; height: 50px; object-fit: cover; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>

<h2 style="text-align:center;">My Contacts</h2>

<div style="text-align:center; margin-bottom:15px;">
    <a href="add_contact.php">➕ Add New Contact</a>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id']; ?></td>
        <td>
            <?php if (!empty($row['image_path'])): ?>
                <img src="<?= $row['image_path']; ?>">
            <?php else: ?>
                N/A
            <?php endif; ?>
        </td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['email']; ?></td>
        <td><?= $row['phone']; ?></td>
        <td>
            <a href="edit_contact.php?id=<?= $row['id']; ?>">Edit</a> |
            <a href="delete_contact.php?id=<?= $row['id']; ?>" 
               onclick="return confirm('Are you sure?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>

</table>

</body>
</html>
