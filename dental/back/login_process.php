<?php
session_start();
require_once '../front/config.php';

// Handle Logout from Back office
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../front/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $password_md5 = md5($password);

    // 1. Check Admin Table
    $sql_admin = "SELECT id, username FROM admin WHERE username = '$username' AND password = '$password_md5'";
    $result_admin = $conn->query($sql_admin);

    if ($result_admin->num_rows == 1) {
        $row = $result_admin->fetch_assoc();
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_username'] = $row['username'];
        header("Location: admin_dashboard.php");
        exit();
    }

    // 2. Check Employees Table
    $sql_emp = "SELECT id, username, full_name, role, must_change_password FROM employees WHERE username = '$username' AND password = '$password_md5'";
    $result_emp = $conn->query($sql_emp);

    if ($result_emp->num_rows == 1) {
        $row = $result_emp->fetch_assoc();
        
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['full_name'] = $row['full_name'];
        $_SESSION['role'] = $row['role'];

        if ($row['must_change_password'] == 1) {
            header("Location: force_password_change.php");
            exit();
        } else {
            // Redirect to role-specific dashboard
            // e.g., Doctor -> doctor_dashboard.php
            $dashboard = strtolower($row['role']) . "_dashboard.php";
            header("Location: $dashboard");
            exit();
        }
    }

    // Login Failed
    header("Location: ../front/index.php?error=Invalid Credentials");
    exit();

} else {
    header("Location: ../front/index.php");
    exit();
}
$conn->close();
?>
