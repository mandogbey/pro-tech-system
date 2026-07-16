<?php

include "../includes/auth_admin.php";
include "../includes/db.php";
include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";

$query = "
SELECT
    c.id,
    c.fullname,
    c.photo,
    c.manifesto,
    p.position_name

FROM candidates c

LEFT JOIN positions p
ON c.position_id = p.id

ORDER BY p.position_name, c.fullname
";

$result = $conn->query($query);

?>


<div class="page-header">

    <div>
        <h1>
            🗳 Candidate Management
        </h1>

        <p>
            Manage all election candidates and their information.
        </p>
    </div>


    <a href="add_candidate.php" class="primary-btn">
        ➕ Add New Candidate
    </a>

</div>


<?php if(isset($_GET['success'])) { ?>

<div class="alert success-alert">
    <span>✔</span>
    Candidate saved successfully.
</div>

<?php } ?>


<?php if(isset($_GET['updated'])) { ?>

<div class="alert success-alert">
    <span>✔</span>
    Candidate updated successfully.
</div>

<?php } ?>



<div class="content-card">


<div class="table-container">

<table class="modern-table">


<thead>

<tr>

<th>Candidate</th>

<th>Position</th>

<th>Manifesto</th>

<th>Actions</th>

</tr>

</thead>



<tbody>


<?php if($result->num_rows > 0) { ?>


<?php while($row = $result->fetch_assoc()) { ?>


<tr>


<td>

<div class="candidate-profile">


<img
src="../assets/uploads/candidates/<?php echo htmlspecialchars($row['photo']); ?>"
class="candidate-img"
alt="Candidate Photo">


<div>

<h4>
<?= htmlspecialchars($row['fullname']); ?>
</h4>

<span>
Candidate
</span>

</div>


</div>

</td>



<td>

<span class="position-badge">

<?= htmlspecialchars($row['position_name']); ?>

</span>

</td>



<td>

<p class="manifesto">

<?= htmlspecialchars(substr($row['manifesto'],0,100)); ?>

<?php if(strlen($row['manifesto']) > 100) echo "..."; ?>

</p>

</td>



<td>


<div class="action-buttons">


<a
href="edit_candidate.php?id=<?= $row['id']; ?>"
class="edit-btn">

✏ Edit

</a>



<a
href="delete_candidate.php?id=<?= $row['id']; ?>"
class="danger-btn"
onclick="return confirm('Are you sure you want to delete this candidate?')">

🗑 Delete

</a>


</div>


</td>


</tr>


<?php } ?>


<?php } else { ?>


<tr>

<td colspan="4">

<div class="empty-state">

<h3>No Candidates Found</h3>

<p>
Add candidates to begin managing elections.
</p>

</div>

</td>

</tr>


<?php } ?>


</tbody>


</table>

</div>


</div>


<?php include "../includes/admin_footer.php"; ?>