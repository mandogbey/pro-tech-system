<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Pro-Tech Online Voting System</title>

    <meta name="description" content="Pro-Tech Online Voting System - Admin Panel">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">

</head>

<body>

<div class="admin-wrapper">

    <header class="admin-header">

        <div class="logo">
            <a href="dashboard.php">
                <span class="logo-icon">🎓</span>
                <span class="logo-text">Pro-Tech</span>
            </a>
        </div>

        <div class="header-right">

            <div class="admin-user">
                Welcome,
                <strong>
                    <?php echo $_SESSION['admin_name'] ?? 'Administrator'; ?>
                </strong>
            </div>

            <a href="../logout.php" class="logout-btn">
                Logout
            </a>

        </div>

    </header>