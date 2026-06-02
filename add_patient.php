<?php
include 'auth.php';
include 'db.php';

$theme = $_SESSION['theme'] ?? 'light';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];

    $pesel = $_POST['pesel'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "INSERT INTO patients
    (first_name, last_name, pesel_encrypted, birth_date, phone_encrypted, address_encrypted)
    VALUES (?, ?, AES_ENCRYPT(?, 'secret_key'), ?, AES_ENCRYPT(?, 'secret_key'), AES_ENCRYPT(?, 'secret_key'))";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssss",
        $first_name,
        $last_name,
        $pesel,
        $birth_date,
        $phone,
        $address
    );

    if ($stmt->execute()) {
        $message = "Patient added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Patient</title>
</head>

<body style="
background-color: <?= $theme == 'dark' ? '#1e1e1e' : '#ffffff' ?>;
color: <?= $theme == 'dark' ? '#ffffff' : '#000000' ?>;
">

<?php include 'menu.php'; ?>

<h1>Add Patient</h1>

<?php if($message): ?>
    <h3><?= $message ?></h3>
<?php endif; ?>

<form method="POST">

    First Name:<br>
    <input type="text" name="first_name" required><br><br>

    Last Name:<br>
    <input type="text" name="last_name" required><br><br>

    PESEL:<br>
    <input type="text" name="pesel" required><br><br>

    Birth Date:<br>
    <input type="date" name="birth_date" required><br><br>

    Phone:<br>
    <input type="text" name="phone"><br><br>

    Address:<br>
    <input type="text" name="address"><br><br>

    <button type="submit">Add Patient</button>

</form>

</body>
</html>