<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #e3c6a8, #b08968);
        }
        .login-container {
            width: 350px;
            background: #a67c52;
            border-radius: 25px;
            overflow: hidden;
            text-align: center;
            color: #fff;
            padding-bottom: 20px;
        }
        .login-container img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom-left-radius: 80px;
            border-bottom-right-radius: 80px;
        }
        .login-container h2 {
            margin: 20px 0;
            font-size: 22px;
            font-weight: bold;
        }
        .input-group {
            margin: 10px 30px;
            text-align: left;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border-radius: 25px;
            border: none;
            font-size: 14px;
        }
        .login-btn {
            background: #5c3d2e;
            color: #fff;
            padding: 12px;
            width: 85%;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
            transition: 0.3s;
        }
        .login-btn:hover {
            background: #3e2a1f;
        }
        .bottom-text {
            margin-top: 10px;
            font-size: 14px;
        }
        .bottom-text a {
            color: #ffcc99;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
        
</head>
<body>
    <div class="login-container">
        <img src="loginimg.jpeg" alt="Coffee">
        <h2>Sign In</h2>
        <form method="POST" action="/controllers/admin/adminAuthController.php">
            <div class="input-group">
                <label>Email Address:</label>
                <input type="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="input-group">
                <label>Password:</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="login-btn">Sign In</button>
        </form>
        
    </div>
</body>
</html>
