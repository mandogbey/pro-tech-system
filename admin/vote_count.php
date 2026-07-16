<?php

require_once "../includes/auth_admin.php";
require_once "../includes/db.php";
require_once "../includes/admin_header.php";
require_once "../includes/admin_sidebar.php";


// Total Students

$students = $conn->query("
    SELECT COUNT(*) total
    FROM users
    WHERE role='user'
")->fetch_assoc()['total'];



// Total Votes

$votes = $conn->query("
    SELECT COUNT(*) total
    FROM votes
")->fetch_assoc()['total'];



// Voting Percentage

if($students > 0){

    $percentage = round(
        ($votes / ($students * 5)) * 100,
        2
    );

}else{

    $percentage = 0;

}



// Election Status

$election = $conn->query("
    SELECT *
    FROM election_settings
    LIMIT 1
")->fetch_assoc();



?>



<div class="page-header">

<div>

<h1>
📊 Election Vote Count
</h1>

<p>
Monitor election participation and candidate performance.
</p>

</div>


<a href="../index.php" class="secondary-btn">
🏠 Home
</a>


</div>





<!-- Statistics Cards -->


<div class="stats-grid">



<div class="stat-card blue">

<div class="stat-icon">
👥
</div>

<div>

<h3>
Students
</h3>

<h1>
<?= $students; ?>
</h1>

</div>

</div>





<div class="stat-card green">

<div class="stat-icon">
🗳️
</div>


<div>

<h3>
Votes Cast
</h3>

<h1>
<?= $votes; ?>
</h1>


</div>


</div>







<div class="stat-card purple">


<div class="stat-icon">
📈
</div>


<div>

<h3>
Voting Rate
</h3>


<h1>
<?= $percentage; ?>%
</h1>


</div>


</div>







<div class="stat-card orange">


<div class="stat-icon">
⚙️
</div>


<div>

<h3>
Election Status
</h3>


<span class="status-badge">

<?= htmlspecialchars($election['election_status'] ?? 'Not Set'); ?>

</span>


</div>


</div>



</div>





<!-- Progress -->


<div class="content-card">


<h2>
Voting Progress
</h2>



<div class="progress-container">


<div 
class="progress-bar"
style="width:<?= $percentage; ?>%">

</div>


</div>


<p class="progress-text">

<?= $percentage; ?>% of expected votes completed

</p>



</div>







<h2 class="section-title">

🏆 Results By Position

</h2>







<?php


$positions=$conn->query("
    SELECT *
    FROM positions
");



while($position=$positions->fetch_assoc()){


?>



<div class="result-card">



<div class="result-header">


<h2>

<?= htmlspecialchars($position['position_name']); ?>

</h2>


<span>
Election Result
</span>


</div>






<table class="modern-table">


<thead>

<tr>

<th>
Candidate
</th>


<th>
Votes
</th>


</tr>


</thead>



<tbody>




<?php


$candidates=$conn->prepare("

SELECT

c.fullname,

COUNT(v.id) total_votes


FROM candidates c


LEFT JOIN votes v

ON c.id=v.candidate_id


WHERE c.position_id=?


GROUP BY c.id


ORDER BY total_votes DESC

");



$candidates->bind_param(
"i",
$position['id']
);



$candidates->execute();


$result=$candidates->get_result();



$winner="";



if($result->num_rows > 0){



while($candidate=$result->fetch_assoc()){


if($winner==""){

    $winner=$candidate['fullname'];

}



?>



<tr>


<td>

<?= htmlspecialchars($candidate['fullname']); ?>

</td>



<td>

<strong>

<?= $candidate['total_votes']; ?>

</strong>


</td>


</tr>



<?php


}



}else{


?>

<tr>

<td colspan="2">

No candidates found.

</td>

</tr>


<?php

}


?>



</tbody>


</table>






<div class="winner-box">


🏆 Winner:

<strong>

<?= htmlspecialchars($winner ?: "No winner yet"); ?>

</strong>


</div>




</div>




<?php

}

?>





<?php include "../includes/admin_footer.php"; ?>