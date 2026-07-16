<?php

session_start();

include "../includes/db.php";


$message = "";


if(isset($_POST['login'])){


    $email = trim($_POST['email']);

    $password = $_POST['password'];

    



    $stmt = $conn->prepare("
        SELECT *
        FROM users
        WHERE email=? 
        AND role='admin'
    ");



    $stmt->bind_param("s",$email);

    $stmt->execute();


    $result = $stmt->get_result();



    if($result->num_rows == 1){


        $admin = $result->fetch_assoc();



        if(password_verify($password,$admin['password'])){


            $_SESSION['id'] = $admin['id'];

            $_SESSION['fullname'] = $admin['fullname'];

            $_SESSION['role'] = $admin['role'];



            header("Location: dashboard.php");

            exit();


        }


        else{


            $message="Incorrect password.";

        }



    }


    else{


        $message="Admin account not found.";

    }


}


?>


<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
Rising University | Admin Login
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
Online Voting System
</p>



<div class="brand-text">

Secure administration portal
<br>
Manage elections, candidates and votes.

</div>


</div>







<div class="login-card">


<h2>
Administrator Login
</h2>


<p class="subtitle">
Sign in to access the admin dashboard
</p>




<?php if($message!=""){ ?>


<div class="login-error">

⚠️ <?= htmlspecialchars($message); ?>

</div>


<?php } ?>





<form method="POST">





<div class="input-group">


<label>
Email Address
</label>


<input

type="email"

name="email"

placeholder="Enter admin email"

required

>


</div>





<div class="input-group">


<label>
Password
</label>



<div class="password-box">


<input

type="password"

name="password"

id="password"

placeholder="Enter password"

required

>


<button

type="button"

onclick="togglePassword()"

class="show-password">

👁

</button>



</div>


</div>





<button

type="submit"

name="login"

class="login-btn">

Login to Dashboard

</button>




</form>





<a href="../index.php" class="back-link">

← Back to Home

</a>

<a href="register.php" class="create-account">
   Create Admin Account
</a>



</div>



</div>







<script>


function togglePassword(){


let password=document.getElementById("password");


if(password.type==="password"){

password.type="text";

}

else{

password.type="password";

}


}


</script>



</body>

</html>