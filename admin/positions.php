<?php

include "../includes/auth_admin.php";
include "../includes/db.php";
include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";


$message = "";



// Add Position

if(isset($_POST['add'])){


    $position = trim($_POST['position_name']);



    if($position != ""){


        $check = $conn->prepare("

            SELECT id

            FROM positions

            WHERE position_name=?

        ");



        $check->bind_param(
            "s",
            $position
        );


        $check->execute();



        if($check->get_result()->num_rows > 0){


            $message = "Position already exists.";


        }

        else{


            $insert=$conn->prepare("

                INSERT INTO positions(position_name)

                VALUES(?)

            ");



            $insert->bind_param(
                "s",
                $position
            );



            if($insert->execute()){


                $message="Position added successfully.";


            }


        }



    }



}





// Delete Position

if(isset($_GET['delete'])){


    $id=(int)$_GET['delete'];



    $delete=$conn->prepare("

        DELETE FROM positions

        WHERE id=?

    ");



    $delete->bind_param(
        "i",
        $id
    );



    $delete->execute();



    header("Location: positions.php");

    exit();



}






$positions=$conn->query("

SELECT *

FROM positions

ORDER BY position_name

");



?>







<div class="page-header">


<div>


<h1>
🏛 Election Positions
</h1>


<p>
Create and manage available election positions.
</p>


</div>




<div class="student-count">

Total Positions:

<strong>

<?= $positions->num_rows; ?>

</strong>


</div>



</div>







<?php if($message!=""){ ?>


<div class="alert success-alert">

✔ <?= htmlspecialchars($message); ?>

</div>


<?php } ?>









<div class="position-layout">





<!-- ADD POSITION -->


<div class="form-card">


<h2>

➕ Add New Position

</h2>


<p class="form-description">

Example: President, Secretary, Treasurer

</p>




<form method="POST">


<label>
Position Name
</label>


<input

type="text"

name="position_name"

placeholder="Enter position name"

required

>




<button

type="submit"

name="add"

class="primary-btn">

Add Position

</button>



</form>



</div>







<!-- POSITION LIST -->


<div class="content-card">



<h2>

📋 Available Positions

</h2>




<div class="position-list">



<?php if($positions->num_rows > 0){ ?>



<?php while($row=$positions->fetch_assoc()){ ?>



<div class="position-item">



<div class="position-icon">

🏛

</div>




<div class="position-info">


<h3>

<?= htmlspecialchars($row['position_name']); ?>

</h3>


<span>

Position ID: <?= $row['id']; ?>

</span>


</div>





<div class="action-buttons">



<a

href="edit_position.php?id=<?= $row['id']; ?>"

class="edit-btn">

✏ Edit

</a>




<a

href="positions.php?delete=<?= $row['id']; ?>"

class="danger-btn"

onclick="return confirm('Delete this position?')">

🗑 Delete

</a>



</div>




</div>



<?php } ?>



<?php } else { ?>


<div class="empty-state">


<h3>
No Positions Created
</h3>


<p>
Add election positions to begin.
</p>


</div>


<?php } ?>



</div>


</div>




</div>







<?php include "../includes/admin_footer.php"; ?>