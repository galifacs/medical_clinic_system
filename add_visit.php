<?php
include 'auth.php';
include 'db.php';

if ($_SESSION['role'] !== 'ADMIN') {
    header("Location: index.php");
    exit();
}

$theme = $_SESSION['theme'] ?? 'light';
$message = '';

$patients = $conn->query("SELECT patient_id, first_name, last_name FROM patients ORDER BY last_name");
$doctors = $conn->query("SELECT doctor_id, first_name, last_name FROM doctors ORDER BY last_name");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $visit_date = $_POST['visit_date'];
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $notes = $_POST['notes'];
    $status = $_POST['status'];

    $sql = "INSERT INTO visits (visit_date, patient_id, doctor_id, notes, status)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiss", $visit_date, $patient_id, $doctor_id, $notes, $status);

    if ($stmt->execute()) {
        $message = "Visit added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Visit</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: <?= $theme == 'dark' ? '#1e1e1e' : '#f5f5f5' ?>;
            color: <?= $theme == 'dark' ? '#ffffff' : '#000000' ?>;
        }

        .container {
            width: 600px;
            margin: auto;
            background: <?= $theme == 'dark' ? '#2b2b2b' : '#ffffff' ?>;
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
            color: <?= $theme == 'dark' ? '#cccccc' : '#666666' ?>;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        input, select, textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid <?= $theme == 'dark' ? '#555' : '#ccc' ?>;
            border-radius: 8px;
            font-size: 15px;
            background: <?= $theme == 'dark' ? '#1e1e1e' : '#ffffff' ?>;
            color: <?= $theme == 'dark' ? '#ffffff' : '#000000' ?>;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
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

        .success {
            background: #28a745;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }

        .error {
            background: #dc3545;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #2d89ef;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

<?php include 'menu.php'; ?>

<div class="container">

    <h1>Add Visit</h1>
    <p class="subtitle">Schedule a new patient visit</p>

    <?php if($message): ?>
        <div class="<?= str_starts_with($message, 'Error') ? 'error' : 'success' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label>Visit date</label>
        <input type="datetime-local" name="visit_date" required>

        <label>Patient</label>
        <select name="patient_id" required>
            <option value="">Select patient</option>
            <?php while($p = $patients->fetch_assoc()): ?>
                <option value="<?= $p['patient_id'] ?>">
                    <?= $p['last_name'] . " " . $p['first_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Doctor</label>
        <select name="doctor_id" required>
            <option value="">Select doctor</option>
            <?php while($d = $doctors->fetch_assoc()): ?>
                <option value="<?= $d['doctor_id'] ?>">
                    <?= $d['last_name'] . " " . $d['first_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Status</label>
        <select name="status" required>
            <option value="Scheduled">Scheduled</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
        </select>

        <label>Notes</label>
        <textarea name="notes" placeholder="Enter visit notes..."></textarea>

        <button type="submit">Add Visit</button>
    </form>

    <a class="back-link" href="visits.php">Back to visits list</a>

</div>

</body>
</html>