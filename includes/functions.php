<?php

function clean($data)
{
    return htmlspecialchars(
        trim($data)
    );
}

function redirect($page)
{
    header("Location: ".$page);
    exit();
}

function isLoggedIn()
{
    return isset($_SESSION['id']);
}

function isAdmin()
{
    return isset($_SESSION['role'])
        && $_SESSION['role']=="admin";
}

function electionStatus($conn)
{
    $result=$conn->query(
        "SELECT election_status
         FROM election_settings
         LIMIT 1"
    );

    $row=$result->fetch_assoc();

    return $row['election_status'];
}