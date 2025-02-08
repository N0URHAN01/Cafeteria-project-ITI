<?php
require_once "connaction.php";
require_once "Database.php";

$db = new Database($pdo);

if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
    $user = $db->select("users");

    
    $current_user = null;
    foreach ($user as $u) {
        if ($u['id'] == $user_id) {
            $current_user = $u;
            break;
        }
    }

    
    if (!$current_user) {
        header("Location: user-table.php");
        exit();
    }
}

if (isset($_POST["confirm_delete"]) && $_POST["confirm_delete"] == "yes") {
    $db->delete("users", $_GET["id"]);
    header("Location: user-table.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Deletion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Are you sure you want to delete this user?</h2>
    <div class="mb-3">
        <strong>Name:</strong> <?= htmlspecialchars($current_user['name']) ?><br>
        <strong>Email:</strong> <?= htmlspecialchars($current_user['email']) ?>
    </div>

    <form action="delete.php?id=<?= $current_user['id'] ?>" method="POST">
        <button type="submit" name="confirm_delete" value="yes" class="btn btn-danger">Yes, Delete</button>
        <a href="user-table.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
