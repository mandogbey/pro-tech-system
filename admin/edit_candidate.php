<?php

include "../includes/auth_admin.php";
include "../includes/db.php";
include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: candidates.php");
    exit();

}


$id = (int) $_GET['id'];



// Fetch candidate

$stmt = $conn->prepare("
    SELECT *
    FROM candidates
    WHERE id = ?
");

$stmt->bind_param("i",$id);

$stmt->execute();

$candidate = $stmt->get_result()->fetch_assoc();



if(!$candidate){

    header("Location:candidates.php");
    exit();

}



// Fetch positions

$positions = $conn->query("
    SELECT *
    FROM positions
    ORDER BY position_name
");



$message = "";



if(isset($_POST['update'])){


    $fullname  = trim($_POST['fullname']);
    $position  = (int)$_POST['position'];
    $slogan    = trim($_POST['slogan']);
    $manifesto = trim($_POST['manifesto']);


    $photoName = $candidate['photo'];



    if(isset($_FILES['photo']) && $_FILES['photo']['error']==0){


        $allowed = ['jpg','jpeg','png'];

        $extension = strtolower(
            pathinfo($_FILES['photo']['name'],PATHINFO_EXTENSION)
        );



        if(!in_array($extension,$allowed)){


            $message="Only JPG, JPEG and PNG files are allowed.";


        }

        elseif($_FILES['photo']['size'] > 2*1024*1024){


            $message="Image size must not exceed 2MB.";


        }

        else{


            $photoName = uniqid("candidate_",true).".".$extension;


            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                "../assets/uploads/candidates/".$photoName
            );



            if(
                $candidate['photo']!="default.png" &&
                file_exists("../assets/uploads/candidates/".$candidate['photo'])
            ){

                unlink("../assets/uploads/candidates/".$candidate['photo']);

            }


        }


    }



    if($message==""){



        $update=$conn->prepare("
            UPDATE candidates

            SET

            fullname=?,
            position_id=?,
            slogan=?,
            manifesto=?,
            photo=?

            WHERE id=?

        ");



        $update->bind_param(
            "sisssi",
            $fullname,
            $position,
            $slogan,
            $manifesto,
            $photoName,
            $id
        );



        if($update->execute()){


            header("Location:candidates.php?updated=1");
            exit();


        }

        else{


            $message="Failed to update candidate.";

        }


    }


}

?>



<div class="page-header">

<div>

<h1>
✏ Edit Candidate
</h1>

<p>
Update candidate information and election profile.
</p>

</div>



<a href="candidates.php" class="secondary-btn">
← Back
</a>


</div>





<?php if($message!=""){ ?>

<div class="alert error-alert">

⚠️ <?= $message; ?>

</div>


<?php } ?>






<div class="form-card">


<form method="POST" enctype="multipart/form-data">



<div class="form-grid">



<div class="form-section">


<label>
Candidate Name
</label>


<input
type="text"
name="fullname"
value="<?= htmlspecialchars($candidate['fullname']); ?>"
required>




<label>
Position
</label>


<select name="position" required>


<?php while($row=$positions->fetch_assoc()){ ?>


<option

value="<?= $row['id']; ?>"

<?= ($row['id']==$candidate['position_id'])?'selected':''; ?>

>

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
value="<?= htmlspecialchars($candidate['slogan']); ?>"
>





<label>
Change Candidate Photo
</label>


<input
type="file"
name="photo"
id="photo"
accept=".jpg,.jpeg,.png">


</div>





<div class="photo-preview-box">


<h3>
Photo Preview
</h3>



<img

src="../assets/uploads/candidates/<?= htmlspecialchars($candidate['photo']); ?>"

id="currentPhoto"

class="preview-image"

>



<img

id="preview"

class="preview-image new-preview"

style="display:none;"

>



<p>
Current photo will be replaced if you upload a new one.
</p>


</div>



</div>





<label>
Candidate Manifesto
</label>



<textarea
name="manifesto"
rows="7"
><?= htmlspecialchars($candidate['manifesto']); ?></textarea>





<div class="form-actions">


<button
type="submit"
name="update"
class="primary-btn">

💾 Update Candidate

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


let preview=document.getElementById("preview");

preview.src=URL.createObjectURL(file);

preview.style.display="block";


document.getElementById("currentPhoto").style.display="none";


}


});


</script>



<?php include "../includes/admin_footer.php"; ?>