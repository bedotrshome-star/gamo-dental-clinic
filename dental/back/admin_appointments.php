<?php
session_start();
require_once '../front/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../front/index.php");
    exit();
}

// Fetch Pending Appointments
$sql = "SELECT * FROM appointments WHERE status = 'Pending' ORDER BY appointment_date ASC";
$result = $conn->query($sql);

// Fetch Doctors for dropdown
$sql_docs = "SELECT id, full_name FROM employees WHERE role = 'Doctor'";
$result_docs = $conn->query($sql_docs);
$doctors = [];
if ($result_docs->num_rows > 0) {
    while($row = $result_docs->fetch_assoc()) {
        $doctors[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Appointments - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Gamo Dental Admin</a>
            <a href="../back/login_process.php?logout=true" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Pending Appointments</h2>
        <a href="admin_dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Phone</th>
                            <th>Date</th>
                            <th>Treatment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['treatment_type']); ?></td>
                                    <td>
                                        <form action="appointment_handler.php" method="POST" class="d-flex gap-2">
                                            <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="action" value="assign_doctor">
                                            <select name="doctor_id" class="form-select form-select-sm" required>
                                                <option value="">Select Doctor</option>
                                                <?php foreach($doctors as $doc): ?>
                                                    <option value="<?php echo $doc['id']; ?>"><?php echo htmlspecialchars($doc['full_name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm">Assign</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No pending appointments.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
