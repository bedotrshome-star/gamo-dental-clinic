<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../front/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gamo Dental Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../front/assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gamo Dental Admin</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                </span>
                <a href="../front/index.php?logout=true" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Dashboard
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Welcome to the Management System</h5>
                        <p class="card-text">This is the secure admin area. You can manage patients, appointments, and other clinic operations here.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3">
                                <div class="card text-center h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Manage Employees</h5>
                                        <p class="card-text">Create, delete, and manage staff accounts.</p>
                                        <a href="manage_employees.php" class="btn btn-primary">Go to Management</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card text-center h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Patient Requests</h5>
                                        <p class="card-text">View and assign new patient appointments.</p>
                                        <a href="admin_appointments.php" class="btn btn-warning text-dark">Manage Appointments</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card text-center h-100 bg-secondary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Settings</h5>
                                        <p class="card-text">Update your admin profile and security.</p>
                                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                            Change Password
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Messages -->
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success mt-3"><?php echo htmlspecialchars($_GET['success']); ?></div>
                        <?php endif; ?>
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($_GET['error']); ?></div>
                        <?php endif; ?>
                        
                        <div class="alert alert-info mt-4">
                            System Status: Active
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Admin Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="admin_actions.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="change_admin_password">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="current_password" id="currentPassword" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('currentPassword', this)">
                                    👁️
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="new_password" id="newPassword" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('newPassword', this)">
                                    👁️
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="confirm_password" id="confirmPassword" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword', this)">
                                    👁️
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../front/assets/js/main.js"></script>
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerText = '🙈'; // Monkey covering eyes (Hide)
            } else {
                input.type = 'password';
                btn.innerText = '👁️'; // Eye (Show)
            }
        }
    </script>
</body>
</html>
