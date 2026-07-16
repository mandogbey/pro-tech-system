<?php

session_start();

include "../includes/db.php";


$message = "";
$success = "";


if(isset($_POST['register'])){


    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];



    if($password != $confirm){

        $message = "Passwords do not match.";

    }

    else{


        // Check existing email

        $check = $conn->prepare("
            SELECT id 
            FROM users
            WHERE email=?
        ");


        $check->bind_param("s",$email);

        $check->execute();


        $result = $check->get_result();



        if($result->num_rows > 0){


            $message="Email already exists.";


        }

        else{


            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            $role = "admin";



            $stmt = $conn->prepare("
                INSERT INTO users
                (
                    fullname,
                    email,
                    password,
                    role
                )

                VALUES
                (?,?,?,?)
            ");



            $stmt->bind_param(
                "ssss",
                $fullname,
                $email,
                $hashed_password,
                $role
            );



            if($stmt->execute()){


                $success =
                "Admin account created successfully. You can login now.";


            }

            else{


                $message =
                "Failed to create account.";

            }


        }


    }


}


?>


<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1.0">


<title>
Create Admin Account
</title>


<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">


<link rel="stylesheet" href="../assets/css/login.css">


</head>


<body>



<div class="login-wrapper">


<div class="login-brand">


<div class="university-logo">
🎓
</div>


<h1>
Rising University
</h1>


<p>
Admin Registration
</p>


<div class="brand-text">

Create an administrator account
to manage elections securely.

</div>


</div>





<div class="login-card">


<h2>
Create Admin Account
</h2>


<p class="subtitle">
Register a new election administrator
</p>




<?php if($message!=""){ ?>

<div class="login-error">

⚠️ <?= $message; ?>

</div>

<?php } ?>



<?php if($success!=""){ ?>

<div class="success-message">

✔ <?= $success; ?>

</div>

<?php } ?>





<form method="POST">



<div class="input-group">

<label>
Full Name
</label>

<input
type="text"
name="fullname"
placeholder="Enter full name"
required>

</div>





<div class="input-group">

<label>
Email Address
</label>

<input
type="email"
name="email"
placeholder="Enter email"
required>

</div>





<div class="input-group">

<label>
Password
</label>

<input
type="password"
name="password"
placeholder="Create password"
required>

</div>





<div class="input-group">

<label>
Confirm Password
</label>

<input
type="password"
name="confirm_password"
placeholder="Confirm password"
required>

</div>





<button
type="submit"
name="register"
class="login-btn">

Create Account

</button>



</form>




<a href="login.php" class="back-link">

← Back to Login

</a>



</div>


</div>


</body>

</html>