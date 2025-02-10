<?php
require_once "connaction.php";
require_once "Database.php";

$db = new Database($pdo);
$users = $db->select("users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>User List</h2>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Room No</th>
            <th>Ext</th>
            <th>Profile Picture</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['room_no']) ?></td>
            <td><?= htmlspecialchars($user['ext']) ?></td>
            <td><img src="<?= $user['profile_picture'] ?>" width="50" height="50"></td>
            <td>
                <a href="update.php?id=<?= $user['id'] ?>" class="btn btn-warning">Edit</a>
                <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-danger">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
