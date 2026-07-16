<?php

if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}


if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit();
}

$checkVote = $conn->prepare(
"
SELECT COUNT(*) total
FROM votes
WHERE user_id=?
"
);

$checkVote->bind_param(
"i",
$_SESSION['id']
);

$checkVote->execute();

$totalVotes = $checkVote
->get_result()
->fetch_assoc()['total'];


if($totalVotes >= 5)
{
    die("You have already completed your voting.");
}

?>