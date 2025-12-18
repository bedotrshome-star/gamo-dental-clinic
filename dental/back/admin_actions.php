<?php
session_start();
require_once '../front/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../front/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'change_admin_password') {
        $admin_id = $_SESSION['admin_id'];
        $current_password = md5($_POST['current_password']);
        $new_password = md5($_POST['new_password']);
        $confirm_password = md5($_POST['confirm_password']);

        if ($new_password !== $confirm_password) {
            header("Location: admin_dashboard.php?error=New passwords do not match");
            exit();
        }

        // Verify current password
        $sql = "SELECT * FROM admin WHERE id = '$admin_id' AND password = '$current_password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Update to new password
            $sql_update = "UPDATE admin SET password = '$new_password' WHERE id = '$admin_id'";
            if ($conn->query($sql_update) === TRUE) {
                header("Location: admin_dashboard.php?success=Your password has been updated.");
            } else {
                header("Location: admin_dashboard.php?error=Error updating password: " . $conn->error);
            }
        } else {
            header("Location: admin_dashboard.php?error=Incorrect current password");
        }
    } else {
        header("Location: admin_dashboard.php?error=Invalid Action");
    }
} else {
    header("Location: admin_dashboard.php");
}
$conn->close();
?>
