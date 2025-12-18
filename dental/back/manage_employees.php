<?php
session_start();
require_once '../front/config.php';

// Check if admin is logged in
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
    <title>Manage Employees - Gamo Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../front/assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php">Gamo Dental Admin</a>
            <div class="d-flex">
                <a href="admin_dashboard.php" class="btn btn-outline-light btn-sm me-2">Back to Dashboard</a>
                <a href="../front/index.php?logout=true" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Add New Employee Form -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-user-plus"></i> Add New Employee
                    </div>
                    <div class="card-body">
                        <form action="employee_actions.php" method="POST">
                            <input type="hidden" name="action" value="create">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select" name="role" required>
                                    <option value="Employer">Employer</option>
                                    <option value="Doctor">Doctor</option>
                                    <option value="Nurse">Nurse</option>
                                    <option value="Cashier">Cashier</option>
                                    <option value="Pharmacist">Pharmacist</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="empPassword" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Create Employee</button>
                            </div>
                        </form>
                        
                        <script>
                            const togglePassword = document.querySelector('#togglePassword');
                            const password = document.querySelector('#empPassword');

                            togglePassword.addEventListener('click', function (e) {
                                // toggle the type attribute
                                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                                password.setAttribute('type', type);
                                // toggle the eye slash icon
                                this.querySelector('i').classList.toggle('fa-eye-slash');
                            });
                        </script>
                    </div>
                </div>
            </div>

            <!-- Employee List -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-users"></i> Employee List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Username</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM employees ORDER BY id DESC";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                                            echo "<td><span class='badge bg-info text-dark'>" . htmlspecialchars($row['role']) . "</span></td>";
                                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                            echo "<td>
                                                <button class='btn btn-warning btn-sm me-1' data-bs-toggle='modal' data-bs-target='#changePassModal" . $row['id'] . "'><i class='fas fa-key'></i></button>
                                                <form action='employee_actions.php' method='POST' class='d-inline' onsubmit=\"return confirm('Are you sure you want to delete this employee?');\">
                                                    <input type='hidden' name='action' value='delete'>
                                                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                                                    <button type='submit' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></button>
                                                </form>
                                            </td>";
                                            echo "</tr>";

                                            // Modal for Password Change
                                            echo "
                                            <div class='modal fade' id='changePassModal" . $row['id'] . "' tabindex='-1' aria-hidden='true'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h5 class='modal-title'>Change Password for " . htmlspecialchars($row['full_name']) . "</h5>
                                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                        </div>
                                                        <form action='employee_actions.php' method='POST'>
                                                            <div class='modal-body'>
                                                                <input type='hidden' name='action' value='change_password'>
                                                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                                                <div class='mb-3'>
                                                                    <label class='form-label'>New Password</label>
                                                                    <input type='password' class='form-control' name='new_password' required>
                                                                </div>
                                                            </div>
                                                            <div class='modal-footer'>
                                                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                                                <button type='submit' class='btn btn-primary'>Save changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='text-center'>No employees found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
