<?php
include 'auth.php';
include 'db.php';

$isAdmin = ($_SESSION['role'] ?? '') === 'ADMIN';
$theme = $_SESSION['theme'] ?? 'light';

if ($isAdmin) {
    $sql = "
    SELECT 
        patient_id,
        first_name,
        last_name,
        birth_date,
        CAST(AES_DECRYPT(pesel_encrypted, 'secret_key') AS CHAR) AS pesel,
        CAST(AES_DECRYPT(phone_encrypted, 'secret_key') AS CHAR) AS phone,
        CAST(AES_DECRYPT(address_encrypted, 'secret_key') AS CHAR) AS address
    FROM patients
    ORDER BY patient_id ASC";

    $result = $conn->query($sql);
} else {
    $sql = "
    SELECT 
        patient_id,
        first_name,
        last_name,
        birth_date,
        CAST(AES_DECRYPT(pesel_encrypted, 'secret_key') AS CHAR) AS pesel,
        CAST(AES_DECRYPT(phone_encrypted, 'secret_key') AS CHAR) AS phone,
        CAST(AES_DECRYPT(address_encrypted, 'secret_key') AS CHAR) AS address
    FROM patients
    WHERE patient_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['patient_id']);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $isAdmin ? 'Patients List' : 'My Profile' ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: <?= $theme == 'dark' ? '#1e1e1e' : '#ffffff' ?>;
            color: <?= $theme == 'dark' ? '#ffffff' : '#000000' ?>;
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

        .delete-btn {
            color: white;
            background: #dc3545;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-btn:hover {
            background: #b02a37;
        }
    </style>
</head>

<body>

<?php include 'menu.php'; ?>

<h1><?= $isAdmin ? 'Patients List' : 'My Patient Profile' ?></h1>

<table>
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Birth Date</th>
        <th>PESEL</th>
        <th>Phone</th>
        <th>Address</th>

        <?php if($isAdmin): ?>
            <th>Actions</th>
        <?php endif; ?>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['patient_id'] ?></td>
        <td><?= $row['first_name'] ?></td>
        <td><?= $row['last_name'] ?></td>
        <td><?= $row['birth_date'] ?></td>
        <td><?= $row['pesel'] ?></td>
        <td><?= $row['phone'] ?></td>
        <td><?= $row['address'] ?></td>

        <?php if($isAdmin): ?>
        <td>
            <a class="delete-btn"
               href="delete_patient.php?patient_id=<?= $row['patient_id'] ?>"
               onclick="return confirm('Are you sure you want to delete this patient?');">
               Delete
            </a>
        </td>
        <?php endif; ?>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>