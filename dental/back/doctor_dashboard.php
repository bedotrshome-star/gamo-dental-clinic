<?php
session_start();
require_once '../front/config.php';

// Check if Employee is logged in and is Doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../front/index.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];
// Fetch appointments assigned to this doctor that are NOT completed
$sql = "SELECT * FROM appointments WHERE assigned_doctor_id = '$doctor_id' AND status != 'Completed' ORDER BY appointment_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Doctor Dashboard</span>
            <span class="text-white">Welcome, Dr. <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
            <a href="../back/login_process.php?logout=true" class="btn btn-light btn-sm text-primary">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">My Patients</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Treatment</th>
                            <th>Status Flags</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($row['patient_name']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['appointment_date']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['treatment_type']); ?></td>
                                    <td>
                                        <!-- Visual indicators of where the patient is -->
                                        <?php if($row['sent_to_nurse'] == 1) echo '<span class="badge bg-info">Nurse</span> '; ?>
                                        <?php if($row['sent_to_pharmacy'] == 1) echo '<span class="badge bg-warning text-dark">Pharm</span> '; ?>
                                        <?php if($row['sent_to_cashier'] == 1) echo '<span class="badge bg-success">Cashier</span> '; ?>
                                        
                                        <?php if($row['sent_to_nurse'] == 2) echo '<span class="badge bg-secondary">Nurse Done</span> '; ?>
                                        <?php if($row['sent_to_pharmacy'] == 2) echo '<span class="badge bg-secondary">Pharm Done</span> '; ?>
                                        <?php if($row['sent_to_cashier'] == 2) echo '<span class="badge bg-secondary">Paid</span> '; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <!-- Send to Nurse Modal Trigger -->
                                            <?php if($row['sent_to_nurse'] == 0): ?>
                                            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#nurseModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-user-nurse"></i> Nurse
                                            </button>
                                            
                                            <!-- Nurse Modal -->
                                            <div class="modal fade" id="nurseModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Send to Nurse</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="appointment_handler.php" method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                                                <input type="hidden" name="action" value="send_to_nurse">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Instructions for Nurse</label>
                                                                    <textarea class="form-control" name="note" rows="3" placeholder="Check vitals, prep for extraction..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Send</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <!-- Send to Pharmacy Modal Trigger -->
                                            <?php if($row['sent_to_pharmacy'] == 0): ?>
                                            <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#pharmModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-pills"></i> Pharm
                                            </button>

                                            <!-- Pharmacy Modal -->
                                            <div class="modal fade" id="pharmModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Send to Pharmacy</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="appointment_handler.php" method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                                                <input type="hidden" name="action" value="send_to_pharmacy">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Prescription / Medication</label>
                                                                    <textarea class="form-control" name="note" rows="3" placeholder="Amoxicillin 500mg, Ibuprofen..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Send</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <!-- Send to Cashier Modal Trigger -->
                                            <?php if($row['sent_to_cashier'] == 0): ?>
                                            <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#cashierModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-cash-register"></i> Cashier
                                            </button>

                                            <!-- Cashier Modal -->
                                            <div class="modal fade" id="cashierModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Send to Cashier</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="appointment_handler.php" method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                                                <input type="hidden" name="action" value="send_to_cashier">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Bill Details / Amount</label>
                                                                    <textarea class="form-control" name="note" rows="3" placeholder="Consultation: 200, X-Ray: 300..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Send</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <!-- Delete/Done -->
                                            <form action="appointment_handler.php" method="POST" onsubmit="return confirm('Are you sure you want to remove this patient from your list?');">
                                                <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="action" value="doctor_delete">
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Remove</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No active patients.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
