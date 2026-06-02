<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['patient_id'] = $user['patient_id'];
            $_SESSION['doctor_id'] = $user['doctor_id'];

            header("Location: index.php");
            exit();
        }
    }

    $error = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Medical Clinic</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 40px;
        }

        .container {
            width: 420px;
            margin: 100px auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 15px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #2d89ef;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #1b5fa7;
        }

        .error {
            background: #dc3545;
            color: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .register-link {
            display: block;
            margin-top: 20px;
            color: #2d89ef;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .success {
    background: #28a745;
    color: white;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: bold;
}
    </style>
</head>

<body>



<div class="container">
    <?php if(isset($_GET['registered'])): ?>
    <div class="success">Registration successful! You can now log in.</div>
<?php endif; ?>

    <h1>Login</h1>
    <p class="subtitle">Medical Clinic Management System</p>

    <?php if($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <a class="register-link" href="register_user.php">
        Create patient account
    </a>
</div>

</body>
</html>