<?php
session_start();
require_once '../front/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../front/index.php");
    exit();
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $password_md5 = md5($new_password);
        
        // Update password and set must_change_password to 0
        $sql = "UPDATE employees SET password = '$password_md5', must_change_password = 0 WHERE id = '$user_id'";
        
        if ($conn->query($sql) === TRUE) {
            // Redirect based on role
            $role = $_SESSION['role'];
            $dashboard = strtolower($role) . "_dashboard.php";
            header("Location: $dashboard");
            exit();
        } else {
            $error = "Error updating password: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Gamo Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../front/assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-warning">
                        <h4>Security Notice</h4>
                    </div>
                    <div class="card-body">
                        <p>For security, you must change your password before continuing.</p>
                        
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
