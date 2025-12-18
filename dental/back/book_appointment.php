<?php
require_once '../front/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = $conn->real_escape_string($_POST['patient_name']);
    $phone = $conn->real_escape_string($_POST['phone_number']);
    $date = $conn->real_escape_string($_POST['appointment_date']);
    $treatment = $conn->real_escape_string($_POST['treatment']);

    $sql = "INSERT INTO appointments (patient_name, phone, appointment_date, treatment_type, status) 
            VALUES ('$patient_name', '$phone', '$date', '$treatment', 'Pending')";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to index with success message
        header("Location: ../front/index.php?success=Appointment Requested Successfully. We will contact you shortly.");
    } else {
        header("Location: ../front/index.php?error=Error: " . $conn->error);
    }
} else {
    header("Location: ../front/index.php");
}
$conn->close();
?>
