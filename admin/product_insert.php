<?php

use function PHPSTORM_META\type;

require '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {

    $id    = req('id');
    $name  = req('name');
    $price = req('price');

    $files = $_FILES['photo'];

    // ----------------------------------------------------------------------------
    // VALIDATION: ID
    $id = (int) $id;
    if ($id == '') {
        $_err['id'] = 'Required';
    }
    else if (!is_int($id)) {
        $_err['id'] = 'Numbers Only';
    }
    else if (!is_unique($id, 'product', 'id')) {
        $_err['id'] = 'Duplicated ID';
    }
    $id = (string) $id;

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
        $_err['price'] = 'Must be valid money format';
    }
    else if ((float)$price < 0.01 || (float)$price > 999.99) {
        $_err['price'] = 'Must be between 0.01 - 999.99';
    }

    // ----------------------------------------------------------------------------
    // VALIDATION: MULTIPLE PHOTOS
    if (empty($files['name'][0])) {
        $_err['photo'] = 'At least 1 image is required';
    }
    else {
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
    // DATABASE INSERT
    if (!$_err) {

        // 1. Insert product (NO photo column anymore)
        $stm = $_db->prepare('
            INSERT INTO product (id, name, price)
            VALUES (?, ?, ?)
        ');
        $stm->execute([$id, $name, $price]);

        // 2. Save multiple photos
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

        // Success message
        temp('info', 'Record inserted successfully');
        redirect('product_index.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Product | Insert';
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
    <?php html_text('id', 'maxlength="4" placeholder="e.g 1,2,3" data-upper'); ?>
    <?php err('id'); ?>

    <!-- NAME -->
    <label>Name</label>
    <?php html_text('name', 'maxlength="100"'); ?>
    <?php err('name'); ?>

    <!-- PRICE -->
    <label>Price</label>
    <?php html_number('price', 0.01, 999.99, 0.01); ?>
    <?php err('price'); ?>

    <!-- MULTIPLE PHOTO UPLOAD -->
    <label>Photos</label>
    <label class="upload" tabindex="0">

        <input type="file" name="photo[]" accept="image/*" multiple>
        <img src="/images/photo.jpg" alt="upload preview">

    </label>
    <?php err('photo'); ?>

    <!-- BUTTONS -->
    <section>
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
    </section>

</form>

<?php
include '../_foot.php';
?>