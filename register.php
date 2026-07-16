<?php
include 'includes/db.php';

$message = "";

if(isset($_POST['register']))
{
    $fullname = trim($_POST['fullname']);
    $student_id = trim($_POST['student_id']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if($password != $confirmPassword)
    {
        $message = "Passwords do not match.";
    }
    elseif(empty($student_id))
    {
        $message = "Student ID is required.";
    }
    else
    {
        $check = $conn->prepare("SELECT id FROM users WHERE email=?");
        $check->bind_param("s",$email);
        $check->execute();

        if($check->get_result()->num_rows > 0)
        {
            $message = "Email already exists.";
        }
        else
        {
            $password = password_hash($password,PASSWORD_DEFAULT);

            $role="user";

            $stmt=$conn->prepare("INSERT INTO users(student_id,fullname,email,password,role)
                                  VALUES(?,?,?,?,?)");

            $stmt->bind_param("sssss",
                $student_id,
                $fullname,
                $email,
                $password,
                $role
            );

            if($stmt->execute())
            {
                header("Location: login.php?success=1");
                exit();
            }
            else
            {
                $message="Registration failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Registration</title>

<link rel="stylesheet" href="assets/css/style.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body class="login-page">

<div class="login-container">

<!-- LEFT SIDE -->

<div class="login-left">

<div class="overlay">

<img src="assets/images/logo.png" class="login-logo">

<h1>RISING UNIVERSITY</h1>

<h2>Online Voting System</h2>

<p>

Register to participate in the student elections.

</p>

</div>

</div>

<!-- RIGHT SIDE -->

<div class="login-right">

<div class="login-box">

<i class="fa-solid fa-user-plus login-icon"></i>

<h2>Create Account</h2>

<p class="subtitle">

Complete the form below.

</p>

<?php if($message!=""){ ?>

<div class="alert error">

<i class="fa-solid fa-circle-exclamation"></i>

<?php echo $message; ?>

</div>

<?php } ?>

<form method="POST">

<div class="input-group">

<i class="fa-solid fa-user"></i>

<input
type="text"
name="fullname"
placeholder="Full Name"
required>

</div>

<div class="input-group">

<i class="fa-solid fa-id-card"></i>

<input
type="text"
name="student_id"
placeholder="Student ID"
required>

</div>

<div class="input-group">

<i class="fa-solid fa-envelope"></i>

<input
type="email"
name="email"
placeholder="University Email"
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

<div class="input-group">

<i class="fa-solid fa-lock"></i>

<input
type="password"
name="confirm_password"
placeholder="Confirm Password"
required>

</div>

<button
type="submit"
name="register"
class="login-btn">

<i class="fa-solid fa-user-plus"></i>

Create Account

</button>

</form>

<div class="extra-links">

Already have an account?

<a href="login.php">

Login

</a>

<br><br>

<a href="index.php">

<i class="fa-solid fa-house"></i>

Back Home

</a>

</div>

</div>

</div>

</div>

</body>

</html>