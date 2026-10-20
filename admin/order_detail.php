<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// (1) Authorization (member)
auth('Admin');

// (2) Return order (based on id) belong to the user
$id = req('id');
$stm = $_db->prepare('
    SELECT * FROM `order`
    WHERE id = ?
');
$stm->execute([$id]);
$o = $stm->fetch();
if (!$o) redirect('order_listing.php');

// (3) Return items (and products) belong to the order
$stm = $_db->prepare('
    SELECT i.*, p.name
    FROM item AS i, product AS p
    WHERE i.product_id = p.id
    AND i.order_id = ?
');
$stm->execute([$id]);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Order | Detail';
include '../_head.php';
?>

<p class="top-form-gap"></p>

<form class="form">
    <label>Order Id</label>
    <b><?= $o->id ?></b>
    <br>

    <label>Datetime</label>
    <div><?= $o->datetime ?></div>
    <br>

    <label>Count</label>
    <div><?= $o->count ?></div>
    <br>

    <label>Total</label>
    <div>RM <?= $o->total ?></div>
    <br>
</form>

<p class="order-count"><?= count($arr) ?> item(s)</p>

<table class="order">
    <tr>
        <th>Product Id</th>
        <th>Product Name</th>
        <th>Price (RM)</th>
        <th>Unit</th>
        <th>Subtotal (RM)</th>
        <th></th>
    </tr>

    <?php foreach ($arr as $i): ?>
    <tr>
        <td><?= $i->product_id ?></td>
        <td><?= $i->name ?></td>
        <td class="right"><?= $i->price ?></td>
        <td class="right"><?= $i->unit ?></td>
        <td class="right">
            <?= $i->subtotal ?>
        </td>
        <td>
            <!-- get first image of the product -->
            <?php 
                $product_photo = get_first_photo($i->product_id);
            ?>
            <img src="/photos/<?= $product_photo ?>" class="small-photo">
        </td>
    </tr>
    <?php endforeach ?>

    <tr>
        <th colspan="3"></th>
        <th class="right"><?= $o->count ?></th>
        <th class="right"><?= $o->total ?></th>
        <th></th>
    </tr>
</table>

<p class="bottom-button">
    <button data-get="order_listing.php">back</button>
</p>

<?php
include '../_foot.php';