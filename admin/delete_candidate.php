<?php

include "../includes/auth_admin.php";
include "../includes/db.php";

if(isset($_GET['id']))
{
    $id = (int)$_GET['id'];

    // Get photo name
    $stmt = $conn->prepare(
        "SELECT photo FROM candidates WHERE id=?"
    );

    $stmt->bind_param("i",$id);
    $stmt->execute();

    $candidate = $stmt->get_result()->fetch_assoc();

    if($candidate)
    {
        if(
            $candidate['photo'] != "default.png" &&
            file_exists("../assets/uploads/candidates/".$candidate['photo'])
        )
        {
            unlink("../assets/uploads/candidates/".$candidate['photo']);
        }

        $delete = $conn->prepare(
            "DELETE FROM candidates WHERE id=?"
        );

        $delete->bind_param("i",$id);
        $delete->execute();
    }
}

header("Location: candidates.php");
exit();