<?php
include 'auth.php';

$theme = $_SESSION['theme'] ?? 'light';
$role = $_SESSION['role'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medical Clinic System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: <?= $theme == 'dark' ? '#1e1e1e' : '#f5f5f5' ?>;
            color: <?= $theme == 'dark' ? 'white' : 'black' ?>;
        }

        .container {
            width: 500px;
            margin: auto;
            background: <?= $theme == 'dark' ? '#2b2b2b' : 'white' ?>;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }

        a {
            display: block;
            margin: 15px;
            padding: 15px;
            background-color: #2d89ef;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 18px;
        }

        a:hover {
            background-color: #1b5fa7;
        }

        .logout {
            background:#dc3545;
        }

        .theme {
            background:#444;
        }
    </style>
</head>

<body>

<div class="container">

    <?php if($role === 'ADMIN'): ?>
        <h1>Administration Panel</h1>
        <p>Logged as: <strong><?= $_SESSION['username'] ?></strong> (ADMIN)</p>

        <a href="patients.php">Patients List</a>
        <a href="visits.php">Visits List</a>
        <a href="add_visit.php">Add Visit</a>
        <a href="admin_register_user.php" class="nav-btn">Register User</a>
    <?php endif; ?>

    <?php if($role === 'PATIENT'): ?>
        <h1>Patient Portal</h1>
        <p>Logged as: <strong><?= $_SESSION['username'] ?></strong> (PATIENT)</p>

        <a href="patients.php">👤 My Profile</a>
        <a href="visits.php">My Visits</a>
    <?php endif; ?>

    <a class="theme" href="theme.php?theme=<?= $theme == 'light' ? 'dark' : 'light' ?>">
        <?= $theme == 'light' ? '🌙 Dark Mode' : '🌞 Light Mode' ?>
    </a>

    <a class="logout" href="logout.php">Logout</a>

</div>

</body>
</html>