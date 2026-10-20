<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $id   = req('id');
    $unit = req('unit');
    update_cart($id, $unit);
    redirect();
}

$name = req('name');

if (!$name) {
    //query to display all product
    $arr = $_db->query('SELECT * FROM product');
}else{
    //query to search for specific product
    $searchKeyword = $_db->prepare("SELECT * FROM product WHERE name LIKE ?");
    $searchKeyword->execute(["%$name%"]);
    $arr = $searchKeyword->fetchAll();
}

// ----------------------------------------------------------------------------

$_title = 'Product | List';
include '../_head.php';
?>

<style>
    #products {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        gap: 50px;
        margin: 30px 0px;
    }

    .product {
        border: 1px solid black;
        width: 400px;
        height: 400px;
        position: relative;
    }

    .product img {
        display: block;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .product form,
    .product div {
        position: absolute;
        background: rgb(174, 148, 109, 0.6);
        color: #000000;
        padding: 5px;
        text-align: center;
    }

    .product form {
        inset: 0 0 auto auto;
    }

    .product div {
        inset: auto 0 0 0;
    }

    .product-search {
        margin: 20px 115px;
    }
</style>

<form class="product-search">
    <?= html_search("name") ?>
    <button type="submit">search</button>
</form>

<div id="products">
    <?php foreach ($arr as $p): ?>
        <?php
        $cart = get_cart();
        $id   = $p->id;
        $unit = $cart[$p->id] ?? 0;
        $product_photo = get_first_photo($id);
        ?>
        <div class="product">
            <form method="post">
                <?= $unit ? '✅' : '' ?>
                <?= html_hidden('id') ?>
                <?= html_select('unit', $_units, '') ?>
            </form>
                
            <img src="/photos/<?= $product_photo ?>"
                 data-get="/product/detail.php?id=<?= $id ?>">

            <div><?= $p->name ?> | RM <?= $p->price ?></div>
        </div>
    <?php endforeach ?>
</div>

<script>
    $('select').on('change', e => e.target.form.submit());
</script>

<?php
include '../_foot.php';