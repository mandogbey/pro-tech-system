<?php

session_start();

require_once "includes/db.php";
require_once "includes/auth.php";


// Get election settings

$setting = $conn->query(
    "SELECT * FROM election_settings LIMIT 1"
)->fetch_assoc();


$currentDateTime = date("Y-m-d H:i:s");


// Function for message pages

function messagePage($icon,$title,$message,$button)
{
    echo "
    <!DOCTYPE html>
    <html>

    <head>

    <title>$title</title>

    <link rel='stylesheet' href='assets/css/style.css'>

    </head>

    <body>

    <div class='message-container'>

        <div class='message-card'>

            <div class='message-icon'>
                $icon
            </div>

            <h2>$title</h2>

            <p>$message</p>

            <a href='index.php' class='modern-btn'>
                $button
            </a>

        </div>

    </div>

    </body>

    </html>
    ";

    exit();
}


// Check election status

if($setting['election_status'] != "Open")
{

    messagePage(
        "🗳️",
        "Voting Closed",
        "The administrator has not opened the election yet.",
        "Back To Home"
    );

}


// Check start time

if($currentDateTime < $setting['start_date'])
{

    messagePage(
        "⏳",
        "Voting Has Not Started",
        "Voting starts on ".date(
            "d M Y h:i A",
            strtotime($setting['start_date'])
        ),
        "Back To Home"
    );

}


// Check end time

if($currentDateTime > $setting['end_date'])
{

    messagePage(
        "🔒",
        "Voting Has Ended",
        "The election ended on ".date(
            "d M Y h:i A",
            strtotime($setting['end_date'])
        ),
        "Back To Home"
    );

}


// Check if user voted

$userID=$_SESSION['id'];

$check=$conn->prepare(
    "SELECT has_voted FROM users WHERE id=?"
);

$check->bind_param("i",$userID);

$check->execute();

$user=$check->get_result()->fetch_assoc();


if($user['has_voted']==1)
{

    messagePage(
        "✅",
        "Vote Already Submitted",
        "Your vote has already been recorded successfully.",
        "Go To Dashboard"
    );

}


// Get positions

$positions=$conn->query(
    "SELECT * FROM positions ORDER BY id ASC"
);

?>


<?php include "includes/header.php"; ?>


<nav class="vote-navbar">


<div class="vote-brand">

<img src="assets/images/logo.png">

<div>

<h2>RISING UNIVERSITY</h2>

<p>Online Voting System</p>

</div>

</div>



<div class="vote-profile">

<i class="fa-solid fa-user-circle"></i>

<?php echo htmlspecialchars($_SESSION['fullname']); ?>

<a href="dashboard.php">

Dashboard

</a>

</div>


</nav>



<section class="vote-banner">


<h1>

<?php echo htmlspecialchars($setting['election_title']); ?>

</h1>


<p>

Select your preferred candidate for each position.

</p>


<div class="instruction">

<i class="fa-solid fa-circle-info"></i>

You can select only one candidate per position.

</div>


</section>




<form action="actions/vote_action.php" method="POST" id="voteForm">



<?php while($position=$positions->fetch_assoc()){ ?>


<section class="position-box">


<h2>

<i class="fa-solid fa-user-tie"></i>

<?php echo htmlspecialchars($position['position_name']); ?>

</h2>



<div class="candidate-grid">


<?php


$candidates=$conn->prepare(
"
SELECT *
FROM candidates
WHERE position_id=?
ORDER BY fullname ASC
"
);


$candidates->bind_param(
"i",
$position['id']
);


$candidates->execute();


$result=$candidates->get_result();



while($candidate=$result->fetch_assoc()){


?>


<label class="candidate-card">


<input

type="radio"

name="position_<?php echo $position['id']; ?>"

value="<?php echo $candidate['id']; ?>"

required

>



<div class="candidate-body">



<img

src="assets/uploads/candidates/<?php echo htmlspecialchars($candidate['photo']); ?>"

alt="Candidate Image"

>



<h3>

<?php echo htmlspecialchars($candidate['fullname']); ?>

</h3>



<span class="candidate-tag">

Candidate

</span>



<h4>

<?php echo htmlspecialchars($candidate['slogan']); ?>

</h4>



<div class="manifesto">

<?php

echo nl2br(
htmlspecialchars($candidate['manifesto'])
);

?>

</div>



<div class="choose">

<i class="fa-solid fa-check"></i>

Select Candidate

</div>



</div>


</label>



<?php } ?>


</div>


</section>



<?php } ?>




<div class="submit-box">


<button type="submit" class="submit-vote">


<i class="fa-solid fa-check-to-slot"></i>

Submit Vote


</button>


</div>




</form>



<script>


document.querySelectorAll(".candidate-card")
.forEach(card=>{


card.addEventListener(
"click",
function(){


let parent=this.closest(".candidate-grid");


parent.querySelectorAll(".candidate-card")
.forEach(item=>{

item.classList.remove("selected");

});


this.classList.add("selected");


});


});



document.getElementById("voteForm")
.addEventListener(
"submit",
function(e){


let confirmVote=
confirm(
"Are you sure you want to submit your vote? You cannot change it later."
);


if(!confirmVote)
{

e.preventDefault();

}


});


</script>


<?php include "includes/footer.php"; ?>