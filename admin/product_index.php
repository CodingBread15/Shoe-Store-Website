<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// SEARCH
$keyword = req('keyword');

if ($keyword != '') {

    $stm = $_db->prepare('
        SELECT * FROM product
        WHERE name LIKE ?
    ');
    $stm->execute(["%$keyword%"]);
    $arr = $stm->fetchAll();

} else {

    $arr = $_db->query('SELECT * FROM product')->fetchAll();
}

$_title = 'Product | Index';
include '../_head.php';
?>

<!-- SEARCH FORM -->
<form method="get" class="product-search">
    <input type="text" name="keyword"
           placeholder="Search product name"
           value="<?= htmlspecialchars($keyword) ?>">
    <button type="submit">Search</button>
</form>

<!-- INSERT BUTTON -->
<p class="product-insert">
    <button data-get="product_insert.php">Insert Product</button>
</p>

<!-- RECORD COUNT -->
<p class="product-count"><?= count($arr) ?> record(s)</p>

<!-- TABLE -->
<table class="product-index" >
    <tr>
        <th>Id</th>
        <th>Photo</th>
        <th>Name</th>
        <th>Price</th>
    </tr>

    <?php foreach ($arr as $p): ?>

        <!-- get first image of the product -->
        <?php
            $product_photo = get_first_photo($p->id);
        ?>

        <tr>
            <td><?= $p->id ?></td>

            <!-- PHOTO -->
            <td>
                <?php if ($product_photo): ?>
                    <img src="/photos/<?= $product_photo ?>">
                <?php else: ?>
                    <img src="/images/photo.jpg">
                <?php endif ?>
            </td>

            <!-- NAME -->
            <td><?= htmlspecialchars($p->name) ?></td>

            <!-- PRICE -->
            <td><?= number_format($p->price, 2) ?></td>

            <!-- ACTIONS -->
            <td class="action-button">
                <button data-get="product_update.php?id=<?= $p->id ?>">Update</button>
                <button data-post="product_delete.php?id=<?= $p->id ?>" data-confirm>Delete</button>
            </td>
        </tr>

    <?php endforeach ?>
</table>

<?php
include '../_foot.php';
?>