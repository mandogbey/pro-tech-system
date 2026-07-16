<?php

include "../includes/auth_admin.php";
include "../includes/db.php";
include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";


// Delete Student

if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    $delete = $conn->prepare(
        "DELETE FROM users WHERE id=? AND role='user'"
    );

    $delete->bind_param("i",$id);

    $delete->execute();


    header("Location: users.php");

    exit();

}



// Change Status

if(isset($_GET['status'])){


    $id = (int)$_GET['status'];


    $update = $conn->prepare("

        UPDATE users

        SET status =
        IF(status='Active','Inactive','Active')

        WHERE id=? AND role='user'

    ");



    $update->bind_param("i",$id);


    $update->execute();


    header("Location: users.php");

    exit();

}



$query = "

SELECT 

users.*,

faculties.faculty_name,

departments.department_name


FROM users


LEFT JOIN faculties

ON users.faculty_id = faculties.id



LEFT JOIN departments

ON users.department_id = departments.id



WHERE users.role='user'

ORDER BY users.fullname ASC

";


$result = $conn->query($query);



?>





<div class="page-header">


<div>

<h1>
👥 Student Management
</h1>

<p>
Manage registered students and voting access.
</p>

</div>


<div class="student-count">

Total Students:

<strong>

<?= $result->num_rows; ?>

</strong>

</div>


</div>







<div class="content-card">



<div class="table-container">


<table class="modern-table">



<thead>


<tr>


<th>
Student
</th>


<th>
Student ID
</th>


<th>
Email
</th>


<th>
Faculty
</th>


<th>
Department
</th>


<th>
Status
</th>


<th>
Actions
</th>


</tr>


</thead>





<tbody>



<?php if($result->num_rows > 0){ ?>



<?php while($student=$result->fetch_assoc()){ ?>



<tr>



<td>


<div class="student-profile">


<div class="student-avatar">

<?= strtoupper(substr($student['fullname'],0,1)); ?>

</div>



<div>


<h4>

<?= htmlspecialchars($student['fullname']); ?>

</h4>


<span>
Student
</span>


</div>


</div>



</td>






<td>

<span class="id-badge">

<?= htmlspecialchars($student['student_id']); ?>

</span>


</td>






<td>

<?= htmlspecialchars($student['email']); ?>

</td>






<td>

<?= htmlspecialchars($student['faculty_name'] ?? 'Not Assigned'); ?>

</td>





<td>

<?= htmlspecialchars($student['department_name'] ?? 'Not Assigned'); ?>

</td>







<td>


<?php if($student['status']=="Active"){ ?>


<span class="active-status">

● Active

</span>


<?php } else { ?>


<span class="inactive-status">

● Inactive

</span>


<?php } ?>


</td>








<td>


<div class="action-buttons">



<a

href="?status=<?= $student['id']; ?>"

class="status-btn">

🔄 Status

</a>




<a

href="?delete=<?= $student['id']; ?>"

class="danger-btn"

onclick="return confirm('Are you sure you want to delete this student?')">

🗑 Delete

</a>



</div>


</td>



</tr>




<?php } ?>



<?php } else { ?>



<tr>

<td colspan="7">


<div class="empty-state">

<h3>
No Students Found
</h3>

<p>
No registered students available.
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