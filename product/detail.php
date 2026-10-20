<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $id   = req('id');
    $unit = req('unit');
    update_cart($id, $unit);
    redirect();
}

$id  = req('id');
$stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
$stm->execute([$id]);
$p = $stm->fetch();
if (!$p) redirect('list.php');

// ----------------------------------------------------------------------------

$_title = 'Product | Detail';
include '../_head.php';
?>

<div class="product-detail-photo">
    <?php 
        //for slider
        $stm = $_db->prepare("SELECT photo FROM product_photo WHERE product_id = ?");
        $stm->execute([$id]);
        
        While($row = $stm->fetch(PDO::FETCH_ASSOC)){
            echo "<img src='/photos/" . $row['photo'] . "' alt='details' class='slides'>";
        }
    ?>
    <div class="slider-button">
        <button onclick="prevSlide()" class="prev"> &#10094; </button>
        <button onclick="nextSlide()" class="next"> &#10095; </button>
    </div>
</div>

<table class="product-detail">
    <tr>
        <th>Id</th>
        <td><?= $p->id ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?= $p->name ?></td>
    </tr>
    <tr>
        <th>Price</th>
        <td>RM <?= $p->price ?></td>
    </tr>
    <tr>
        <th>Unit</th>
        <td>
            <?php
            $cart = get_cart();
            $id   = $p->id;
            $unit = $cart[$p->id] ?? 0; 
            ?>
            <form method="post">
                <?= html_hidden('id') ?>
                <?= html_select('unit', $_units, '') ?>
                <?= $unit ? '✅' : '' ?>
            </form>
        </td>
    </tr>
</table>

<p class="product-detail-back">
    <button data-get="list.php">List</button>
    <img src="" alt="">
</p>

<script>
    $('select').on('change', e => e.target.form.submit());
</script>

<?php
include '../_foot.php';