<?php
require_once '../../classes/db/Database.php';
require_once '../../classes/admin/users.php';
require_once __DIR__ . "/../../middleware/authMiddleware.php";

// // Check if admin is logged in
requireAuthAdmin();

$database = new Database();
$db = $database->connect(); 

// Fetch admin details
$admin_id = $_SESSION["admin_id"];
$stmt = $db->prepare("SELECT name, profile_image FROM admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Pass the database connection to Users class
$UsersModal = new Users($db);

// Fetch all users
$users = $UsersModal->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin Dashboard - Users</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- favicon -->
    <link rel="icon" href="../../static_images/favicon.ico" type="image/ico" />
    <link href="../../css/global_style.css" rel="stylesheet" />
    <link href="../../css/adminNavbar.css" rel="stylesheet" />
    <link href="../../css/table.css" rel="stylesheet" />
    
</head>
  <body>
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="admin-info">
          <!-- Admin Info (profile image) -->
          <img src="../../uploads/<?= htmlspecialchars($admin['profile_image']); ?>" alt="Admin Image">
          <p><?= htmlspecialchars($admin['name']); ?></p>
      </div>
      <a href="admin_dashboard.php">Home</a>
      <a href="products.php">Product</a>
      <a href="users.php">Users</a>
      <a href="ManualOrder.php">Manual Order</a>
      
      <a href="#">Checks</a>
      <a href="Categories.php">Categories</a>

      <form method="POST" action="../../controllers/logout.php">
          <button type="submit" class="btn btn-logout w-100 mt-3">Logout</button>
      </form>
    </div>
    <div class="main-content">
      <div class="container-xl" style="">
        <div class="table-responsive ">
          <div class="table-wrapper">
            <div class="table-title">
              <div class="row d-flex justify-between ">
                <div class="col-sm-10 col-12">
                  <h2>Users <b>Details</b></h2>
                </div>
                
                <a class="btn btn-info col-sm-2 px-2 " type="button" 
                data-target="#addcat" href="admin_dashboard.php"> + Add New User</a>

              </div>
            </div>
            <table class="table table-striped table-hover table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name <i class="fa fa-sort"></i></th>
                  <th>Room</th>
                  <th>Image</th>
                  <th>Ext.</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $users = $UsersModal->getAllUsers();
                foreach ($users as $user) {
                    echo "<tr>
                        <td>{$user['user_id']}</td>
                        <td>{$user['name']}</td>
                        <td>{$user['room_number']}</td>
                        <td><img src='../../uploads/users/{$user['profile_image']}' width='50'></td>
                        <td>{$user['ext']}</td>
                        <td>
                            <a href='userDetails.php?user_id={$user['user_id']}' class='view'><i class='material-icons'>&#xE417;</i></a>
                            <a href='updateUser.php?user_id={$user['user_id']}' class='edit'><i class='material-icons'>&#xE254;</i></a>
                            <form action='../../controllers/admin/usersController.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='user_id' value='{$user['user_id']}'>
                                <input type='hidden' name='action' value='delete'>
                                <button type='submit' class='delete' style='border:none; background:none;'><i class='material-icons'>&#xE872;</i></button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
                </tbody>
                
            </table>
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  </body>
</html>
