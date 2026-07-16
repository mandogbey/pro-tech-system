<?php

include "../includes/auth_admin.php";
include "../includes/db.php";
include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";

$id = (int)$_GET['id'];

$stmt = $conn->prepare("
SELECT *
FROM positions
WHERE id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$position = $stmt->get_result()->fetch_assoc();

if(!$position)
{
    header("Location: positions.php");
    exit();
}

if(isset($_POST['update']))
{
    $name = trim($_POST['position_name']);

    $update = $conn->prepare("
        UPDATE positions
        SET position_name=?
        WHERE id=?
    ");

    $update->bind_param("si",$name,$id);

    $update->execute();

    header("Location: positions.php");
    exit();
}

?>

<h1>Edit Position</h1>

<form method="POST">

<label>Position Name</label>

<input
type="text"
name="position_name"
value="<?php echo htmlspecialchars($position['position_name']); ?>"
required>

<br><br>

<button
type="submit"
name="update"
class="btn">

💾 Update Position

</button>

<a
href="positions.php"
class="delete-btn">

Cancel

</a>

</form>

<?php include "../includes/admin_footer.php"; ?>