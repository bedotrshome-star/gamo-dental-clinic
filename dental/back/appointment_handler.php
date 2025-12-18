<?php
session_start();
require_once '../front/config.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: ../front/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $appt_id = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : null;

    $redirect = "doctor_dashboard.php"; // Default fallback

    if ($action == 'assign_doctor') {
        $doctor_id = $_POST['doctor_id'];
        $sql = "UPDATE appointments SET assigned_doctor_id='$doctor_id', status='Assigned' WHERE id='$appt_id'";
        $redirect = "admin_appointments.php";
    } 
    // Doctor Actions (Parallel with Notes)
    elseif ($action == 'send_to_nurse') {
        $note = isset($_POST['note']) ? $conn->real_escape_string($_POST['note']) : '';
        $sql = "UPDATE appointments SET sent_to_nurse=1, nurse_note='$note' WHERE id='$appt_id'";
        $redirect = "doctor_dashboard.php";
    }
    elseif ($action == 'send_to_pharmacy') {
        $note = isset($_POST['note']) ? $conn->real_escape_string($_POST['note']) : '';
        $sql = "UPDATE appointments SET sent_to_pharmacy=1, pharmacy_note='$note' WHERE id='$appt_id'";
        $redirect = "doctor_dashboard.php";
    }
    elseif ($action == 'send_to_cashier') {
        $note = isset($_POST['note']) ? $conn->real_escape_string($_POST['note']) : '';
        $sql = "UPDATE appointments SET sent_to_cashier=1, cashier_note='$note' WHERE id='$appt_id'";
        $redirect = "doctor_dashboard.php";
    }
    elseif ($action == 'doctor_delete') {
        // Doctor marks as fully complete/done (removes from their list)
        $sql = "UPDATE appointments SET status='Completed' WHERE id='$appt_id'";
        $redirect = "doctor_dashboard.php";
    }

    // Role Actions (Mark their specific part as Done)
    elseif ($action == 'nurse_done') {
        $sql = "UPDATE appointments SET sent_to_nurse=2 WHERE id='$appt_id'";
        $redirect = "nurse_dashboard.php";
    }
    elseif ($action == 'pharmacy_done') {
        $sql = "UPDATE appointments SET sent_to_pharmacy=2 WHERE id='$appt_id'";
        $redirect = "pharmacist_dashboard.php";
    }
    elseif ($action == 'cashier_done') {
        $sql = "UPDATE appointments SET sent_to_cashier=2 WHERE id='$appt_id'";
        $redirect = "cashier_dashboard.php";
    }
    
    if ($conn->query($sql) === TRUE) {
        header("Location: $redirect?success=Status Updated");
    } else {
        header("Location: $redirect?error=Error updating status: " . $conn->error);
    }
}
$conn->close();
?>
