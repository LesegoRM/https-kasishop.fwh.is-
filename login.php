<?php
session_start();
include 'config.php';  // Your DB connection settings here

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === "" || $password === "") {
        $error = "Please fill in all fields.";
    } else {
        // Prepare and execute query to find user
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                // Password matches, start session
                $_SESSION['username'] = $username;
                header("Location: seller_dashboard.php");
                exit();
            } else {
                $error = "❌ Incorrect password.";
            }
        } else {
            $error = "❌ User not found.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>KasiShop | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #001f3f;
            color: white;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background-color: #1a232e;
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 20px rgba(0,0,0,0.4);
        }
    </style>
</head>
<body>
<div class="login-box">
    <h2 class="text-center mb-4">KasiShop Login</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required autofocus />
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
</div>
</body>
</html>

