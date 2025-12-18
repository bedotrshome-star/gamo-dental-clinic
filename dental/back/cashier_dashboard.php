<?php
session_start();
require_once '../front/config.php';

// Check if Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Cashier') {
    header("Location: ../front/index.php");
    exit();
}

$sql = "SELECT * FROM appointments WHERE sent_to_cashier = 1 ORDER BY appointment_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cashier Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-secondary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Cashier Dashboard</span>
            <span class="text-white">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
            <a href="../back/login_process.php?logout=true" class="btn btn-light btn-sm text-secondary">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <!-- Payment Queue Content -->
            <div class="col-md-8">
                <h2 class="mb-4">Payment Queue</h2>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Treatment</th>
                                    <th>Bill Details</th>
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
                                                <?php if (!empty($row['cashier_note'])): ?>
                                                    <div class="alert alert-secondary p-2 mb-0 small">
                                                        <?php echo nl2br(htmlspecialchars($row['cashier_note'])); ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small">Standard Bill</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="appointment_handler.php" method="POST">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="action" value="cashier_done">
                                                    <button type="submit" class="btn btn-success btn-sm">Process Payment & Done</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center">No pending payments.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Calculator Widget -->
            <div class="col-md-4">
                <h2 class="mb-4">Calculator</h2>
                <div class="card shadow-sm bg-dark text-white">
                    <div class="card-body p-4">
                        <input type="text" id="calc-display" class="form-control mb-3 text-end fs-2 fw-bold bg-secondary text-white border-0" value="0" readonly>
                        <div class="row g-2">
                            <div class="col-3"><button class="btn btn-light w-100 fw-bold" onclick="appendCalc('7')">7</button></div>
                            <div class="col-3"><button class="btn btn-light w-100 fw-bold" onclick="appendCalc('8')">8</button></div>
                            <div class="col-3"><button class="btn btn-light w-100 fw-bold" onclick="appendCalc('9')">9</button></div>
                            <div class="col-3"><button class="btn btn-warning w-100 fw-bold" onclick="setOp('/')">/</button></div>
                            
                            <div class="col-3"><button class="btn btn-light w-100 fw-bold" onclick="appendCalc('4')">4</button></div>
                            <div class="col-3"><button class="btn btn-light w-100 fw-bold" onclick="appendCalc('5')">5</button></div>
                            <div class="col-3"><button class="btn btn-light w-100 fw-bold" onclick="appendCalc('6')">6</button></div>
                            <div class="col-3"><button class="btn btn-warning w-100 fw-bold" onclick="setOp('*')">x</button></div>
                            
                            <div class="col-3"><button class="btn btn-light w-100 fw-bold" onclick="appendCalc('1')">1</button></div>
                            <div class="col-3"><button class="btn btn-light w-100 fw-bold" onclick="appendCalc('2')">2</button></div>
                            <div class="col-3"><button class="btn btn-light w-100 fw-bold" onclick="appendCalc('3')">3</button></div>
                            <div class="col-3"><button class="btn btn-warning w-100 fw-bold" onclick="setOp('-')">-</button></div>
                            
                            <div class="col-3"><button class="btn btn-danger w-100 fw-bold" onclick="clearCalc()">C</button></div>
                            <div class="col-3"><button class="btn btn-light w-100 fw-bold" onclick="appendCalc('0')">0</button></div>
                            <div class="col-3"><button class="btn btn-success w-100 fw-bold" onclick="calculate()">=</button></div>
                            <div class="col-3"><button class="btn btn-warning w-100 fw-bold" onclick="setOp('+')">+</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentInput = '';
        let operator = '';
        let previousInput = '';
        let calculationDone = false;
        const display = document.getElementById('calc-display');

        function appendCalc(val) {
            if (calculationDone) {
                currentInput = '';
                calculationDone = false;
            }
            if (currentInput === '0') currentInput = '';
            currentInput += val;
            // Show what is being typed, if operator exists show "prev op current"
            if (operator !== '') {
                display.value = previousInput + ' ' + operator + ' ' + currentInput;
            } else {
                display.value = currentInput;
            }
        }

        function setOp(op) {
            if (currentInput === '' && !calculationDone) return;
            if (previousInput !== '' && !calculationDone && currentInput !== '') calculate();
            
            if (calculationDone) {
                // Continue with previous result
                calculationDone = false;
            }
            
            operator = op;
            previousInput = currentInput; // currentInput holds the result or number
            currentInput = '';
            display.value = previousInput + ' ' + operator;
        }

        function clearCalc() {
            currentInput = '0';
            previousInput = '';
            operator = '';
            calculationDone = false;
            display.value = '0';
        }

        function calculate() {
            let result;
            const prev = parseFloat(previousInput);
            const current = parseFloat(currentInput);
            if (isNaN(prev) || isNaN(current)) return;

            switch (operator) {
                case '+': result = prev + current; break;
                case '-': result = prev - current; break;
                case '*': result = prev * current; break;
                case '/': result = prev / current; break;
                default: return;
            }
            
            // Display: 4 + 5 = 9
            display.value = previousInput + ' ' + operator + ' ' + currentInput + ' = ' + result;
            
            currentInput = result.toString();
            operator = '';
            previousInput = '';
            calculationDone = true;
        }
    </script>
</body>
</html>
