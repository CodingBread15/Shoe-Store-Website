<?php
require "../_base.php";

auth("Admin");

$id = req('id');

$stm = $_db->prepare("SELECT id, name, email, gender, phone, photo FROM user WHERE id = ?");
$stm->execute([$id]);

//Member Details 
$mD = $stm->fetch();

if (!$mD){
    redirect("/");
}

$_title = "Member Detail";
include "../_head.php";
?>

<table class="member-detail">
    <tr>
        <th>Id</th>
        <td><?= $mD->id ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?= $mD->name ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= $mD->email ?></td>
    </tr>
    <tr>
        <th>Gender</th>
        <td><?= $mD->gender ?></td>
    </tr>
    <tr>
        <th>Phone</th>
        <td><?= $mD->phone ?></td>
    </tr>
    <tr>
        <th>Photo</th>
        <td>
            <?php 
                if(!$mD->photo) {
                    echo '<img src="../images/photo.jpg" alt="profile picture">';
                }
                else {
                    echo "<img src='../photos/{$mD->photo}' alt='profile picture'>";
                }
            ?>
        </td>
    </tr>
    <tr>
        <td id="back-button" colspan="2">
            <button data-get="memberIndex.php">Back</button>
        </td>
    </tr>
</table>


<?php
include "../_foot.php";