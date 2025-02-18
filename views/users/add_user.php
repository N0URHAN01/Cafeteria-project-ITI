<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    
    <style>
        body {
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .admin-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-box img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid #ccc;
        }

         
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Home</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Users</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Manual Order</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Checks</a></li>
            </ul>
        </div>
        <!-- Admin Profile -->
        <div class="admin-box">
            <span><a class="nav-link" href="#">Admin</a></span>
            <img src="/phpproject/Cafeteria-project-ITI/views/users/public/image/3.png" alt="Admin" />
        </div>
    </div>
</nav>

<!-- User Form -->
<div class="container">
    <h2 class="text-center">Add User</h2>
    <form action="process.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Room No.</label>
            <input type="text" class="form-control" name="room_no">
        </div>
        <div class="mb-3">
            <label class="form-label">Ext.</label>
            <input type="text" class="form-control" name="ext">
        </div>
        <div class="mb-3">
            <label class="form-label">Profile Picture</label>
            <input type="file" class="form-control" name="profile_picture">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </form>
</div>

</body>
</html>
