<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employer') {
    header("Location: ../front/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employer Dashboard - Gamo Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../front/assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark p-3 mb-4">
        <span class="navbar-brand text-white">Employer Dashboard</span>
        <span class="navbar-text text-white"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
        <a href="../front/index.php?logout=true" class="btn btn-light btn-sm ms-3">Logout</a>
    </nav>
    <div class="container">
        <h1>Welcome.</h1>
        <p>General staff dashboard.</p>
    </div>
</body>
</html>
