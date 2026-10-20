<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// GET: Load product data
if (is_get()) {

    $id = req('id');

    // Load product
    $stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
    $stm->execute([$id]);
    $p = $stm->fetch();

    if (!$p) {
        redirect('index.php');
    }

    $name  = $p->name;
    $price = $p->price;
}

// ----------------------------------------------------------------------------
// POST: Update product + add new photos
if (is_post()) {

    $id    = req('id');
    $name  = req('name');
    $price = req('price');

    $files = $_FILES['photo'];

    // ----------------------------------------------------------------------------
    // VALIDATION: NAME
    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    // ----------------------------------------------------------------------------
    // VALIDATION: PRICE
    if ($price == '') {
        $_err['price'] = 'Required';
    }
    else if (!is_money($price)) {
        $_err['price'] = 'Invalid price format';
    }
    else if ((float)$price < 0.01 || (float)$price > 999.99) {
        $_err['price'] = 'Must be between 0.01 - 999.99';
    }

    // ----------------------------------------------------------------------------
    // VALIDATION: MULTIPLE PHOTOS (optional in update)
    if (!empty($files['name'][0])) {

        for ($i = 0; $i < count($files['name']); $i++) {

            $type = $files['type'][$i];
            $size = $files['size'][$i];

            if (!str_starts_with($type, 'image/')) {
                $_err['photo'] = 'All files must be images';
                break;
            }

            if ($size > 1 * 1024 * 1024) {
                $_err['photo'] = 'Each image must be ≤ 1MB';
                break;
            }
        }
    }

    // ----------------------------------------------------------------------------
    // DATABASE UPDATE
    if (!$_err) {

        // 1. Update product info (NO photo column anymore)
        $stm = $_db->prepare('
            UPDATE product
            SET name = ?, price = ?
            WHERE id = ?
        ');
        $stm->execute([$name, $price, $id]);

        // 2. Add NEW uploaded photos (if any)
        if (!empty($files['name'][0])) {

            for ($i = 0; $i < count($files['name']); $i++) {

                if ($files['error'][$i] == 0) {

                    $f = (object)[
                        'name'     => $files['name'][$i],
                        'type'     => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'size'     => $files['size'][$i],
                        'error'    => $files['error'][$i],
                    ];

                    $photo = save_photo($f, '../photos');

                    $stm = $_db->prepare('
                        INSERT INTO product_photo (product_id, photo)
                        VALUES (?, ?)
                    ');
                    $stm->execute([$id, $photo]);

                }
            }
        }

        temp('info', 'Record updated successfully');
        redirect('product_index.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Product | Update';
include '../_head.php';
?>

<!-- BACK BUTTON -->
<p class="top-button">
    <button data-get="product_index.php">Back to Index</button>
</p>

<!-- FORM -->
<form method="post" class="form" enctype="multipart/form-data" novalidate>

    <!-- ID -->
    <label>Id</label>
    <b><?= $id ?></b>
    <input type="hidden" name="id" value="<?= $id ?>">
    <span></span>

    <!-- NAME -->
    <label>Name</label>
    <?php html_text('name', 'maxlength="100"'); ?>
    <?php err('name'); ?>

    <!-- PRICE -->
    <label>Price</label>
    <?php html_number('price', 0.01, 999.99, 0.01); ?>
    <?php err('price'); ?>

    <!-- MULTIPLE PHOTO UPLOAD -->
    <label>Add Photos</label>
    <label class="upload" tabindex="0">
        <input type="file" name="photo[]" accept="image/*" multiple>
        <img src="/images/photo.jpg" alt="upload">
    </label>
    <?php err('photo'); ?>
    <span></span>

    <!-- BUTTONS -->
    <section>
        <button type="submit">Update</button>
        <button type="reset">Reset</button>
    </section>
    <span></span>

</form>

<?php
include '../_foot.php';
?>