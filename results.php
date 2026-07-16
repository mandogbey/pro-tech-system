<?php
require_once "includes/db.php";
require_once "includes/auth.php";

$election = $conn->query("
SELECT * FROM election_settings
LIMIT 1
")->fetch_assoc();
?>

<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<div class="container py-5">

    <!-- HERO -->
    <div class="card shadow-lg border-0 bg-primary text-white mb-5 rounded-4">
        <div class="card-body text-center p-5">

            <div class="display-1 mb-3">
                🗳️
            </div>

            <h1 class="fw-bold">
                RISING UNIVERSITY
            </h1>

            <h3 class="mb-3">
                Election Results
            </h3>

            <p class="lead">
                <?= htmlspecialchars($election['election_title']); ?>
            </p>

            <span class="badge bg-warning text-dark fs-6 px-4 py-2">
                <?= htmlspecialchars($election['election_status']); ?>
            </span>

        </div>
    </div>

<?php

$positions=$conn->query("
SELECT *
FROM positions
ORDER BY position_name
");

while($position=$positions->fetch_assoc()){

$candidates=$conn->prepare("
SELECT
c.fullname,
c.photo,
COUNT(v.id) total_votes
FROM candidates c
LEFT JOIN votes v
ON c.id=v.candidate_id
WHERE c.position_id=?
GROUP BY c.id
ORDER BY total_votes DESC
");

$candidates->bind_param("i",$position['id']);
$candidates->execute();
$result=$candidates->get_result();

$list=[];
$totalVotes=0;

while($row=$result->fetch_assoc()){
    $list[]=$row;
    $totalVotes+=$row['total_votes'];
}
?>

<div class="card shadow-sm border-0 rounded-4 mb-5">

<div class="card-header bg-dark text-white rounded-top-4">

<div class="d-flex justify-content-between align-items-center">

<h3 class="mb-0">
🏛 <?= htmlspecialchars($position['position_name']); ?>
</h3>

<span class="badge bg-success">
<?= $totalVotes ?> Votes
</span>

</div>

</div>

<div class="card-body">

<?php
$rank=1;

foreach($list as $candidate){

$percentage=0;

if($totalVotes>0){
    $percentage=round(($candidate['total_votes']/$totalVotes)*100,1);
}

$badge="🥇";

if($rank==2)$badge="🥈";
if($rank==3)$badge="🥉";

?>

<div class="card mb-3 border-0 shadow-sm <?= $rank==1?'border border-warning':''; ?>">

<div class="card-body">

<div class="row align-items-center">

<div class="col-md-2 text-center">

<img
src="assets/uploads/candidates/<?= htmlspecialchars($candidate['photo']); ?>"
class="rounded-circle shadow"
width="90"
height="90"
style="object-fit:cover;">

</div>

<div class="col-md-7">

<h4>

<?= $badge ?>

<?= htmlspecialchars($candidate['fullname']); ?>

<?php if($rank==1){ ?>

<span class="badge bg-warning text-dark">
Winner
</span>

<?php } ?>

</h4>

<div class="progress mt-3" style="height:18px;">

<div
class="progress-bar bg-success progress-bar-striped progress-bar-animated"
style="width:<?= $percentage ?>%">
<?= $percentage ?>%
</div>

</div>

</div>

<div class="col-md-3 text-center">

<div class="display-6 fw-bold text-primary">

<?= $candidate['total_votes']; ?>

</div>

<div>
Votes
</div>

</div>

</div>

</div>

</div>

<?php
$rank++;
}
?>

</div>

</div>

<?php } ?>

<div class="text-center">

<a href="index.php" class="btn btn-primary btn-lg rounded-pill px-5">
🏠 Back Home
</a>

</div>

</div>

<?php include "includes/footer.php"; ?>