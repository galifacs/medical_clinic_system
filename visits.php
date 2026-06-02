<?php
include 'auth.php';
include 'db.php';

$theme = $_SESSION['theme'] ?? 'light';
$isAdmin = ($_SESSION['role'] ?? '') === 'ADMIN';

$sql = "
SELECT
    v.visit_id,
    p.first_name AS patient_first_name,
    p.last_name AS patient_last_name,
    d.first_name AS doctor_first_name,
    d.last_name AS doctor_last_name,
    v.visit_date,
    v.status,
    v.notes
FROM visits v
JOIN patients p ON v.patient_id = p.patient_id
JOIN doctors d ON v.doctor_id = d.doctor_id
";

if (!$isAdmin) {
    $sql .= " WHERE v.patient_id = ? ";
}

$sql .= " ORDER BY v.visit_date DESC";

$stmt = $conn->prepare($sql);

if (!$isAdmin) {
    $stmt->bind_param("i", $_SESSION['patient_id']);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visits</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 30px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        background-color: <?= $theme == 'dark' ? '#2b2b2b' : '#ffffff' ?>;
    }

    th, td {
        border: 1px solid <?= $theme == 'dark' ? '#555' : '#ccc' ?>;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: <?= $theme == 'dark' ? '#3b3b3b' : '#f0f0f0' ?>;
    }

    h1 {
        margin-bottom: 20px;
    }

    .status-completed {
        color: #28a745;
        font-weight: bold;
    }

    .status-scheduled {
        color: #ffc107;
        font-weight: bold;
    }

    .status-cancelled {
        color: #dc3545;
        font-weight: bold;
    }
</style>
</head>
<body style="
background-color: <?= $theme == 'dark' ? '#1e1e1e' : '#ffffff' ?>;
color: <?= $theme == 'dark' ? '#ffffff' : '#000000' ?>;
">
<?php include 'menu.php'; ?>
<h1>Visits List</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Visit Date</th>
        <th>Status</th>
        <th>Notes</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['visit_id'] ?></td>

        <td>
            <?= $row['patient_first_name'] ?>
            <?= $row['patient_last_name'] ?>
        </td>

        <td>
            <?= $row['doctor_first_name'] ?>
            <?= $row['doctor_last_name'] ?>
        </td>

        <td><?= $row['visit_date'] ?></td>
        <td><?= $row['status'] ?></td>
        <td><?= $row['notes'] ?></td>
    </tr>
    <?php endwhile; ?>

</table>

</body>
</html>