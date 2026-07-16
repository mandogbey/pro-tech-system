<?php

session_start();

require_once "../includes/db.php";


if(!isset($_SESSION['id']))
{
    header("Location: ../login.php");
    exit();
}


$user_id = $_SESSION['id'];


// Check election status

$setting = $conn->query(
    "SELECT election_status 
     FROM election_settings 
     LIMIT 1"
)->fetch_assoc();


if($setting['election_status'] != "Open")
{
    die("Voting is currently closed.");
}


// Check submitted votes

foreach($_POST as $key => $candidate_id)
{

    if(strpos($key, "position_") === 0)
    {

        $position_id = str_replace(
            "position_",
            "",
            $key
        );


        // Check if user already voted for this position

        $check = $conn->prepare(
            "
            SELECT id 
            FROM votes
            WHERE user_id=?
            AND position_id=?
            "
        );


        $check->bind_param(
            "ii",
            $user_id,
            $position_id
        );


        $check->execute();


        $result = $check->get_result();



        if($result->num_rows > 0)
        {
            echo 
            "You have already voted for this position.";

            exit();
        }



        // Insert vote

        $insert = $conn->prepare(
            "
            INSERT INTO votes
            (
            user_id,
            candidate_id,
            position_id
            )

            VALUES
            (?,?,?)
            "
        );


        $insert->bind_param(
            "iii",
            $user_id,
            $candidate_id,
            $position_id
        );


        $insert->execute();

    }

}



// Update voter status

$update = $conn->prepare(
"
UPDATE users

SET has_voted=1

WHERE id=?
"
);


$update->bind_param(
"i",
$user_id
);


$update->execute();



header(
"Location: ../results.php?success=1"
);
$update = $conn->prepare("
    UPDATE users
    SET has_voted = 1
    WHERE id = ?
");

$update->bind_param("i", $user_id);
$update->execute();


exit();

?>