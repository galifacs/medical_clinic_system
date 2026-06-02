<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$theme = $_SESSION['theme'] ?? 'light';
$role = $_SESSION['role'] ?? '';
?>

<div class="navbar">

    <div>
        <a href="index.php" class="nav-btn">Home</a>

        <?php if ($role === 'ADMIN'): ?>
            <a href="patients.php" class="nav-btn">Patients</a>
            <a href="visits.php" class="nav-btn">All Visits</a>
            <a href="add_visit.php" class="nav-btn">Add Visit</a>
            <a href="admin_register_user.php" class="nav-btn">Register User</a>
        <?php endif; ?>

        <?php if ($role === 'PATIENT'): ?>
            <a href="patients.php" class="nav-btn">👤 My Profile</a>
            <a href="visits.php" class="nav-btn">My Visits</a>
        <?php endif; ?>
    </div>

    <div>
        <span class="user-info">
            <?= $_SESSION['username'] ?? 'Guest' ?> 
            (<?= $_SESSION['role'] ?? 'Unknown' ?>)
        </span>

        <a href="theme.php?theme=<?= $theme == 'light' ? 'dark' : 'light' ?>" class="theme-btn">
            <?= $theme == 'light' ? '🌙 Dark' : '🌞 Light' ?>
        </a>

        <a href="logout.php" class="logout-btn"> Logout</a>
    </div>

</div>

<style>
.navbar {
    background: <?= $theme == 'dark' ? '#2b2b2b' : '#ffffff' ?>;
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-btn {
    display: inline-block;
    background: #2d89ef;
    color: white;
    text-decoration: none;
    padding: 12px 18px;
    margin-right: 8px;
    border-radius: 8px;
    font-weight: bold;
}

.nav-btn:hover {
    background: #1b5fa7;
}

.theme-btn {
    background: #444;
    color: white;
    text-decoration: none;
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: bold;
    margin-left: 8px;
}

.logout-btn {
    background: #dc3545;
    color: white;
    text-decoration: none;
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: bold;
    margin-left: 8px;
}

.user-info {
    color: <?= $theme == 'dark' ? '#ffffff' : '#000000' ?>;
    font-weight: bold;
}
</style>