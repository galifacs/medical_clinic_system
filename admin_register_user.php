<?php
include 'auth.php';
include 'db.php';

if ($_SESSION['role'] !== 'ADMIN') {
    header("Location: index.php");
    exit();
}
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];
    $pesel = $_POST['pesel'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $role = 'PATIENT';

    $conn->begin_transaction();

    try {
        $sqlPatient = "INSERT INTO patients
        (first_name, last_name, pesel_encrypted, birth_date, phone_encrypted, address_encrypted)
        VALUES (?, ?, AES_ENCRYPT(?, 'secret_key'), ?, AES_ENCRYPT(?, 'secret_key'), AES_ENCRYPT(?, 'secret_key'))";

        $stmtPatient = $conn->prepare($sqlPatient);
        $stmtPatient->bind_param("ssssss", $first_name, $last_name, $pesel, $birth_date, $phone, $address);
        $stmtPatient->execute();

        $patient_id = $conn->insert_id;

        $sqlUser = "INSERT INTO users
        (username, password_hash, role, patient_id)
        VALUES (?, ?, ?, ?)";

        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bind_param("sssi", $username, $passwordHash, $role, $patient_id);
        $stmtUser->execute();

        $conn->commit();
       $message = "Patient registered successfully!";

    } catch (Exception $e) {
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Patient</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 40px;
        }

        .container {
            width: 520px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        input {
    width: 100%;
    box-sizing: border-box;
    padding: 12px;
    margin-top: 6px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 7px;
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

        .message {
            text-align: center;
            color: #28a745;
            font-weight: bold;
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #2d89ef;
            text-decoration: none;
            font-weight: bold;
        }

        .row {
    display: flex;
    gap: 15px;
}

.row div {
    flex: 1;
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
    <h1>Patient Registration</h1>
    <p class="subtitle">Create your patient account</p>

    <?php if($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">

        Username:
        <input type="text" name="username" required>

        Password:
        <input type="password" name="password" required>

        <div class="row">
            <div>
                First Name:
                <input type="text" name="first_name" required>
            </div>

            <div>
                Last Name:
                <input type="text" name="last_name" required>
            </div>
        </div>

        PESEL:
        <input type="text" name="pesel" required>

        Birth Date:
        <input
    type="date"
    name="birth_date"
    max="<?= date('Y-m-d') ?>"
    required
>

        Phone:
        <input type="text" name="phone">

        Address:
        <input type="text" name="address">

        <button type="submit">Register</button>
    </form>

    <a class="login-link" href="index.php">Back to administration panel</a>
</div>

</body>
</html>