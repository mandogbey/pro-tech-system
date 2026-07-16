<?php
session_start();

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit();
}

include "includes/db.php";

$userId = $_SESSION['id'];

/* Get latest voting status */
$stmt = $conn->prepare("SELECT has_voted FROM users WHERE id=?");
$stmt->bind_param("i",$userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$_SESSION['has_voted'] = $user['has_voted'];

/* Election information */
$election = $conn->query("SELECT * FROM election_settings LIMIT 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Dashboard</title>

<link rel="stylesheet" href="assets/css/style.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<!-- NAVIGATION -->

<nav class="dashboard-nav">

<div class="brand">

<img src="assets/images/logo.png" class="nav-logo">

<h2>RISING UNIVERSITY</h2>

</div>

<div class="nav-user">

<i class="fa-solid fa-circle-user"></i>

<span><?php echo htmlspecialchars($_SESSION['fullname']); ?></span>

<a href="logout.php" class="logout">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>

</div>

</nav>

<!-- HERO -->

<section class="dashboard-header">

<h1>

Welcome,

<?php echo htmlspecialchars($_SESSION['fullname']); ?>

👋

</h1>

<p>

Student Election Portal

</p>

</section>

<!-- DASHBOARD -->

<section class="dashboard-grid">

<!-- STATUS -->

<div class="dashboard-card">

<i class="fa-solid fa-check-to-slot"></i>

<h3>Voting Status</h3>

<?php if($_SESSION['has_voted']){ ?>

<p class="success-text">

✅ You have successfully voted.

</p>

<?php } else { ?>

<p class="warning-text">

🟡 You have not voted yet.

</p>

<a href="vote.php" class="action-btn">

Vote Now

</a>

<?php } ?>

</div>

<!-- RESULTS -->

<div class="dashboard-card">

<i class="fa-solid fa-chart-column"></i>

<h3>Election Results</h3>

<p>

View current election standings.

</p>

<a href="results.php" class="action-btn">

View Results

</a>

</div>

<!-- ELECTION -->

<div class="dashboard-card">

<i class="fa-solid fa-calendar-days"></i>

<h3>Election Information</h3>

<p>

<strong>Title</strong><br>

<?php echo htmlspecialchars($election['election_title']); ?>

</p>

<br>

<p>

<strong>Status:</strong>

<?php

if($election['election_status']=="Open")
{
echo "<span class='open'>OPEN</span>";
}
else
{
echo "<span class='closed'>CLOSED</span>";
}

?>

</p>

</div>

<!-- PROFILE -->

<div class="dashboard-card">

<i class="fa-solid fa-id-card"></i>

<h3>My Profile</h3>

<p>

<strong>Name</strong><br>

<?php echo htmlspecialchars($_SESSION['fullname']); ?>

</p>

<br>

<p>

<strong>Role</strong><br>

Student

</p>

</div>

</section>

<!-- QUICK LINKS -->

<section class="quick-links">

<h2>

Quick Actions

</h2>

<div class="links-grid">

<a href="vote.php">

<i class="fa-solid fa-check-to-slot"></i>

Vote

</a>

<a href="results.php">

<i class="fa-solid fa-chart-simple"></i>

Results

</a>

<a href="logout.php">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>

</div>

</section>

<footer>

© <?php echo date("Y"); ?>

Rising University Online Voting System

</footer>

</body>

</html>