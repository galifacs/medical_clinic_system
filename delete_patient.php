<?php
include 'auth.php';
include 'db.php';

if ($_SESSION['role'] !== 'ADMIN') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['patient_id'])) {
    header("Location: patients.php");
    exit();
}

$patient_id = intval($_GET['patient_id']);

$conn->begin_transaction();

try {
    $sql = "DELETE FROM visits_has_diseases 
            WHERE visits_visit_id IN (SELECT visit_id FROM visits WHERE patient_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    $sql = "DELETE FROM visits_has_medicines 
            WHERE visits_visit_id IN (SELECT visit_id FROM visits WHERE patient_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    $sql = "DELETE FROM visits WHERE patient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    $sql = "DELETE FROM users WHERE patient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    $sql = "DELETE FROM patients WHERE patient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
}

header("Location: patients.php");
exit();
?>