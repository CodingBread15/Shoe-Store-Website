<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// (1) Authorization (member)
auth('Member');

// (2) Return orders belong to the user (descending)
$stm = $_db->prepare('
    SELECT * FROM `order`
    WHERE user_id = ?
    ORDER BY id DESC
');
$stm->execute([$_user->id]);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Order | History';
include '../_head.php';
?>

<p class="top-button">
    <button data-post="reset.php" data-confirm>Reset</button>
</p>

<p class="order-count"><?= count($arr) ?> record(s)</p>

<table class="order">
    <tr>
        <th>Id</th>
        <th>Datetime</th>
        <th>Count</th>
        <th>Total (RM)</th>
        <th></th>
    </tr>

    <?php foreach ($arr as $o): ?>
    <tr>
        <td><?= $o->id ?></td>
        <td><?= $o->datetime ?></td>
        <td class="right"><?= $o->count ?></td>
        <td class="right"><?= $o->total ?></td>
        <td>
            <!-- (A) EXTRA: Product photos -->
            <div>
                <?php
                    $stm = $_db->prepare('  SELECT i.product_id 
                                            FROM item AS i, product AS p
                                            WHERE i.product_id = p.id
                                            AND i.order_id = ?
                                        ');
                    $stm->execute([$o->id]);
                    $product_id = $stm->fetchAll(PDO::FETCH_COLUMN);
                    
                    foreach($product_id as $id){
                        $product_photo = get_first_photo($id);
                        echo "<img src='/photos/$product_photo' class='small-photo'>";
                    }
                ?>
            </div>
        </td>
        <td class="action-button">
            <button data-get="detail.php?id=<?= $o->id ?>">Detail</button>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<?php
include '../_foot.php';
