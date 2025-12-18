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

    if ($action == 'create') {
        $full_name = $conn->real_escape_string($_POST['full_name']);
        $username = $conn->real_escape_string($_POST['username']);
        $role = $conn->real_escape_string($_POST['role']);
        $password = md5($_POST['password']); 

        // must_change_password defaults to 1 in DB
        $sql = "INSERT INTO employees (full_name, username, password, role) VALUES ('$full_name', '$username', '$password', '$role')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: manage_employees.php?success=Employee Created Successfully");
        } else {
            header("Location: manage_employees.php?error=Error: " . $conn->error);
        }
    } 
    elseif ($action == 'delete') {
        $id = $conn->real_escape_string($_POST['id']);
        
        $sql = "DELETE FROM employees WHERE id = '$id'";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: manage_employees.php?success=Employee Deleted Successfully");
        } else {
            header("Location: manage_employees.php?error=Error deleting record: " . $conn->error);
        }
    }
    elseif ($action == 'change_password') {
        $id = $conn->real_escape_string($_POST['id']);
        $new_password = md5($_POST['new_password']);
        
        $sql = "UPDATE employees SET password = '$new_password' WHERE id = '$id'";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: manage_employees.php?success=Password Updated Successfully");
        } else {
            header("Location: manage_employees.php?error=Error updating password: " . $conn->error);
        }
    }
    else {
         header("Location: manage_employees.php?error=Invalid Action");
    }
} else {
    header("Location: manage_employees.php");
}
$conn->close();
?>
