<?php

include "../includes/auth_admin.php";
include "../includes/db.php";
include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";


$message = "";
$success = "";


// Fetch Positions
$positions = $conn->query("
    SELECT * FROM positions 
    ORDER BY position_name
");


if(isset($_POST['save']))
{

    $fullname  = trim($_POST['fullname']);
    $position  = (int)$_POST['position'];
    $slogan    = trim($_POST['slogan']);
    $manifesto = trim($_POST['manifesto']);


    $photoName = "default.png";


    // Upload Photo

    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0)
    {

        $allowed = ['jpg','jpeg','png'];

        $extension = strtolower(
            pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION)
        );


        if(!in_array($extension,$allowed))
        {
            $message = "Only JPG, JPEG and PNG images are allowed.";
        }


        elseif($_FILES['photo']['size'] > 2 * 1024 * 1024)
        {
            $message = "Image size must not exceed 2MB.";
        }


        else
        {

            $photoName = uniqid("candidate_",true).".".$extension;


            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                "../assets/uploads/candidates/".$photoName
            );

        }

    }



    if($message == "")
    {


        $stmt = $conn->prepare("
            INSERT INTO candidates
            (
                fullname,
                position_id,
                slogan,
                manifesto,
                photo
            )

            VALUES
            (?,?,?,?,?)
        ");



        $stmt->bind_param(
            "sisss",
            $fullname,
            $position,
            $slogan,
            $manifesto,
            $photoName
        );



        if($stmt->execute())
        {

            $success = "Candidate added successfully.";

            $_POST = [];

        }

        else
        {

            $message = "Failed to save candidate.";

        }

    }

}

?>


<div class="page-header">

<div>

<h1>
➕ Add Candidate
</h1>

<p>
Create a new election candidate profile.
</p>

</div>


<a href="candidates.php" class="secondary-btn">
← Back to Candidates
</a>


</div>




<?php if($message!=""){ ?>

<div class="alert error-alert">
⚠️
<?php echo $message; ?>
</div>

<?php } ?>



<?php if($success!=""){ ?>

<div class="alert success-alert">
✔
<?php echo $success; ?>
</div>

<?php } ?>





<div class="form-card">


<form method="POST" enctype="multipart/form-data">



<div class="form-grid">



<div class="form-section">


<label>
Candidate Full Name
</label>


<input 
type="text"
name="fullname"
placeholder="Enter candidate name"
value="<?= $_POST['fullname'] ?? '' ?>"
required>



<label>
Position
</label>


<select name="position" required>


<option value="">
Select Position
</option>


<?php while($row=$positions->fetch_assoc()){ ?>


<option value="<?= $row['id']; ?>">

<?= htmlspecialchars($row['position_name']); ?>

</option>


<?php } ?>


</select>




<label>
Campaign Slogan
</label>


<input
type="text"
name="slogan"
placeholder="Example: Together We Rise"
value="<?= $_POST['slogan'] ?? '' ?>">



<label>
Candidate Photo
</label>



<input
type="file"
name="photo"
id="photo"
accept=".jpg,.jpeg,.png">



</div>





<div class="photo-preview-box">


<h3>
Candidate Photo Preview
</h3>


<img
id="preview"
src="../assets/uploads/candidates/default.png"
class="preview-image">


<p>
Maximum size: 2MB
<br>
Allowed: JPG, JPEG, PNG
</p>


</div>



</div>





<label>
Candidate Manifesto
</label>


<textarea
name="manifesto"
rows="7"
placeholder="Write candidate manifesto..."
><?php echo $_POST['manifesto'] ?? ''; ?></textarea>




<div class="form-actions">


<button
type="submit"
name="save"
class="primary-btn">

💾 Save Candidate

</button>



<a href="candidates.php" class="cancel-btn">

Cancel

</a>


</div>



</form>


</div>





<script>

document.getElementById("photo").addEventListener("change",function(){

const file=this.files[0];

if(file){

document.getElementById("preview").src =
URL.createObjectURL(file);

}

});


</script>



<?php include "../includes/admin_footer.php"; ?>