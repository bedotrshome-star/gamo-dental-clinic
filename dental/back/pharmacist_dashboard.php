<?php
session_start();
require_once '../front/config.php';

// Check if Pharmacist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Pharmacist') {
    header("Location: ../front/index.php");
    exit();
}

$sql = "SELECT * FROM appointments WHERE sent_to_pharmacy = 1 ORDER BY appointment_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacist Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-warning">
        <div class="container">
            <span class="navbar-brand mb-0 h1 text-dark">Pharmacist Dashboard</span>
            <span class="text-dark">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
            <a href="../back/login_process.php?logout=true" class="btn btn-dark btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Pharmacy Queue</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Treatment</th>
                            <th>Prescription</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['treatment_type']); ?></td>
                                    <td>
                                        <?php if (!empty($row['pharmacy_note'])): ?>
                                            <div class="alert alert-warning p-2 mb-0 small text-dark border-warning">
                                                <strong>Rx:</strong><br>
                                                <?php echo nl2br(htmlspecialchars($row['pharmacy_note'])); ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small">No specific prescription note</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form action="appointment_handler.php" method="POST">
                                            <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="action" value="pharmacy_done">
                                            <button type="submit" class="btn btn-success btn-sm">Dispense & Done</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No patients at pharmacy.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
