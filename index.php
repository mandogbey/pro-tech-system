<?php
require_once "includes/config.php";
require_once "includes/db.php";

$result = $conn->query("SELECT * FROM election_settings LIMIT 1");
$election = $result->fetch_assoc();

$status = $election['election_status'];
$title = $election['election_title'];
$start = $election['start_date'];
$end = $election['end_date'];

/* Statistics */
$totalCandidates = $conn->query("SELECT COUNT(*) total FROM candidates")->fetch_assoc()['total'];
$totalPositions  = $conn->query("SELECT COUNT(*) total FROM positions")->fetch_assoc()['total'];
$totalVotes      = $conn->query("SELECT COUNT(*) total FROM votes")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $title; ?></title>

<link rel="stylesheet" href="assets/css/style.css">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<!-- ================= NAVBAR ================= -->

<nav class="navbar">

<div class="logo">

<img src="assets/images/logo.png" class="logo-img">

<span>RISING UNIVERSITY</span>

</div>

<ul>

<li><a href="#">Home</a></li>

<li><a href="login.php">Student Login</a></li>

<li><a href="register.php">Register</a></li>

<li><a href="admin/login.php">Admin</a></li>

</ul>

</nav>

<!-- ================= HERO ================= -->

<section class="hero">

<div class="overlay">

<h1>

ONLINE VOTING SYSTEM

</h1>

<h2>

<?php echo $title; ?>

</h2>

<p>

Secure • Transparent • Reliable • One Student One Vote

</p>

<div class="hero-buttons">

<a href="login.php" class="btn">

<i class="fa-solid fa-right-to-bracket"></i>

Student Login

</a>

<a href="register.php" class="btn btn-success">

<i class="fa-solid fa-user-plus"></i>

Register

</a>

<a href="admin/login.php" class="btn btn-danger">

<i class="fa-solid fa-user-shield"></i>

Admin Login

</a>

</div>

</div>

</section>

<!-- ================= STATISTICS ================= -->

<section class="stats">

<div class="stat-card">

<i class="fa-solid fa-circle-check"></i>

<h3>Status</h3>

<?php

if($status=="Open")

echo "<span class='open'>OPEN</span>";

else

echo "<span class='closed'>CLOSED</span>";

?>

</div>

<div class="stat-card">

<i class="fa-solid fa-users"></i>

<h3>Candidates</h3>

<h1><?php echo $totalCandidates; ?></h1>

</div>

<div class="stat-card">

<i class="fa-solid fa-layer-group"></i>

<h3>Positions</h3>

<h1><?php echo $totalPositions; ?></h1>

</div>

<div class="stat-card">

<i class="fa-solid fa-square-poll-vertical"></i>

<h3>Total Votes</h3>

<h1><?php echo $totalVotes; ?></h1>

</div>

</section>

<!-- ================= COUNTDOWN ================= -->

<section class="countdown">

<h2>

Election Ends In

</h2>

<div class="timer">

<div class="time-box">

<h1 id="days">00</h1>

<p>Days</p>

</div>

<div class="time-box">

<h1 id="hours">00</h1>

<p>Hours</p>

</div>

<div class="time-box">

<h1 id="minutes">00</h1>

<p>Minutes</p>

</div>

<div class="time-box">

<h1 id="seconds">00</h1>

<p>Seconds</p>

</div>

</div>

</section>

<!-- ================= HOW IT WORKS ================= -->

<section class="how">

<h2>

How It Works

</h2>

<div class="steps">

<div class="step">

<i class="fa-solid fa-user-plus"></i>

<h3>Register</h3>

<p>Create your student account.</p>

</div>

<div class="step">

<i class="fa-solid fa-right-to-bracket"></i>

<h3>Login</h3>

<p>Access your secure dashboard.</p>

</div>

<div class="step">

<i class="fa-solid fa-check-to-slot"></i>

<h3>Vote</h3>

<p>Select your preferred candidates.</p>

</div>

<div class="step">

<i class="fa-solid fa-chart-column"></i>

<h3>Results</h3>

<p>View election results instantly.</p>

</div>

</div>

</section>

<!-- ================= FEATURES ================= -->

<section class="features">

<h2>

Why Use Our Voting System?

</h2>

<div class="feature-grid">

<div class="feature">

<i class="fa-solid fa-lock"></i>

<h3>Secure</h3>

<p>Encrypted authentication.</p>

</div>

<div class="feature">

<i class="fa-solid fa-mobile-screen-button"></i>

<h3>Responsive</h3>

<p>Works on phones and computers.</p>

</div>

<div class="feature">

<i class="fa-solid fa-bolt"></i>

<h3>Fast</h3>

<p>Real-time vote counting.</p>

</div>

<div class="feature">

<i class="fa-solid fa-shield-halved"></i>

<h3>Transparent</h3>

<p>Fair and trusted election process.</p>

</div>

</div>

</section>

<!-- ================= ELECTION INFO ================= -->

<section class="info-section">

<h2>Election Schedule</h2>

<div class="info-grid">

<div class="info-card">

<h3>Start Date</h3>

<p><?php echo date("d M Y h:i A",strtotime($start)); ?></p>

</div>

<div class="info-card">

<h3>End Date</h3>

<p><?php echo date("d M Y h:i A",strtotime($end)); ?></p>

</div>

</div>

</section>

<!-- ================= CTA ================= -->

<section class="cta">

<h2>

Ready to Cast Your Vote?

</h2>

<p>

Make your voice count today.

</p>

<a href="login.php" class="btn">

Vote Now

</a>

</section>

<!-- ================= FOOTER ================= -->

<footer>

<p>

© <?php echo date("Y"); ?>

Rising University Online Voting System

</p>

</footer>

<script>

const countDownDate=new Date("<?php echo $end;?>").getTime();

setInterval(function(){

const now=new Date().getTime();

const distance=countDownDate-now;

document.getElementById("days").innerHTML=Math.floor(distance/(1000*60*60*24));

document.getElementById("hours").innerHTML=Math.floor((distance%(1000*60*60*24))/(1000*60*60));

document.getElementById("minutes").innerHTML=Math.floor((distance%(1000*60*60))/(1000*60));

document.getElementById("seconds").innerHTML=Math.floor((distance%(1000*60))/1000);

},1000);

</script>

</body>

</html>