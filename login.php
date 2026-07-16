<?php
session_start();
include 'includes/db.php';

$message = "";

if(isset($_POST['login']))
{
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows == 1)
    {
        $user = $result->fetch_assoc();

        if(password_verify($password,$user['password']))
        {
            $_SESSION['id']=$user['id'];
            $_SESSION['fullname']=$user['fullname'];
            $_SESSION['role']=$user['role'];
            $_SESSION['has_voted']=$user['has_voted'];

            if($user['role']=="admin")
            {
                header("Location: admin/dashboard.php");
            }
            else
            {
                header("Location: dashboard.php");
            }
            exit();
        }
        else
        {
            $message="Incorrect password.";
        }
    }
    else
    {
        $message="Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Login</title>

<link rel="stylesheet" href="assets/css/style.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body class="login-page">

<div class="login-container">

<div class="login-left">

<div class="overlay">

<img src="assets/images/logo.png" class="login-logo">

<h1>RISING UNIVERSITY</h1>

<h2>Online Voting System</h2>

<p>

Secure • Transparent • Reliable

</p>

</div>

</div>

<div class="login-right">

<div class="login-box">

<i class="fa-solid fa-user-lock login-icon"></i>

<h2>Student Login</h2>

<p class="subtitle">

Login to cast your vote.

</p>

<?php if($message!=""){ ?>

<div class="alert error">

<i class="fa-solid fa-circle-exclamation"></i>

<?php echo $message; ?>

</div>

<?php } ?>

<?php if(isset($_GET['success'])){ ?>

<div class="alert success">

<i class="fa-solid fa-circle-check"></i>

Registration successful.

</div>

<?php } ?>

<form method="POST">

<div class="input-group">

<i class="fa-solid fa-envelope"></i>

<input
type="email"
name="email"
placeholder="Email Address"
required>

</div>

<div class="input-group">

<i class="fa-solid fa-lock"></i>

<input
type="password"
name="password"
placeholder="Password"
required>

</div>

<button
type="submit"
name="login"
class="login-btn">

<i class="fa-solid fa-right-to-bracket"></i>

Login

</button>

</form>

<div class="extra-links">

<a href="register.php">

Create Account

</a>

|

<a href="index.php">

Home

</a>

</div>

</div>

</div>

</div>

</body>

</html>