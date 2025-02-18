<?php include 'config.php'; ?>

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



<!-- Main Content -->
<div class="container mt-4">
    <h2 class="mb-4">Add Product</h2>
    <form action="/phpproject/Cafeteria-project-ITI/views/users/save_product.php" method="POST">
        
        <!-- Product Name -->
        <div class="mb-4">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <!-- Price & EGP beside each other -->
        <div class="mb-4">
            <label class="form-label">Price</label>
            <div class="input-group">
                <input type="number" class="form-control" name="price" placeholder="Enter Price" required>
                <span class="input-group-text">EGP</span>
            </div>
        </div>

        <!-- Category & Add Category beside each other -->
        <div class="mb-4">
            <label class="form-label">Category</label>
            <div class="d-flex gap-5">
                <select class="form-select" name="category" required>
                    <option value="1">Drinks</option>
                    <option value="2">Snacks</option>
                </select>
                <a href="add_category.php" class="btn btn-outline-primary">Add Category</a>
            </div>
        </div>

        <!-- Product Picture -->
        <div class="mb-4">
            <label class="form-label">Product Picture</label>
            <input type="file" class="form-control" name="image">
        </div>

        <!-- Submit & Reset Buttons -->
        <div class="d-flex gap-5">
            <button type="submit" class="btn btn-success">Save</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>
</div>


</body>
</html>



