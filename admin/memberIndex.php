<?php
require "../_base.php";

auth("Admin");

$name = req('name');

if (!$name) {
    //query to display members
    $members = $_db->query("SELECT id, name, email, gender, phone FROM user WHERE role = 'Member'")->fetchAll();
}
else {
    //query to search for specific member
    $members = $_db->prepare("SELECT id, name, email, gender, phone FROM user WHERE role = 'Member' AND name LIKE ?");
    $members->execute(["%$name%"]);
    $members = $members->fetchAll();
}


$_title = "Member Index";
include "../_head.php";
?>

<form action="memberIndex.php" class="member-search">
    <?= html_search("name") ?>
    <button type="submit">search</button>
</form>

<p class="member-count"><?= count($members) ?> record(s)</p>

<table class="member-index">
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Email</th>
        <th>Gender</th>
        <th>phone</th>
        <th class="detail-button"></th>
    </tr>

    <?php foreach($members as $m): ?>
        <tr>
            <td><?= $m->id ?></td>
            <td><?= $m->name ?></td>
            <td><?= $m->email ?></td>
            <td><?= $m->gender ?></td>
            <td><?= $m->phone ?></td>
            <td class="detail-button">
                <button data-get="memberDetail.php?id=<?= $m->id ?>">detail</button>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php
include "../_foot.php";