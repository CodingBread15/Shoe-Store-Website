<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {

    $id = req('id');

    // ----------------------------------------------------------------------------
    // 1. Get all product photos
    $stm = $_db->prepare('
        SELECT photo
        FROM product_photo
        WHERE product_id = ?
    ');
    $stm->execute([$id]);
    $photos = $stm->fetchAll();

    // ----------------------------------------------------------------------------
    // 2. Delete image files from folder
    foreach ($photos as $p) {

        $path = "../photos/$p->photo";

        if (file_exists($path)) {
            unlink($path);
        }
    }

    // ----------------------------------------------------------------------------
    // 3. Delete records from product_photo table
    $stm = $_db->prepare('
        DELETE FROM product_photo
        WHERE product_id = ?
    ');
    $stm->execute([$id]);

    // ----------------------------------------------------------------------------
    // 4. Delete product itself
    $stm = $_db->prepare('
        DELETE FROM product
        WHERE id = ?
    ');
    $stm->execute([$id]);

    // ----------------------------------------------------------------------------

    temp('info', 'Product and all photos deleted successfully');
}

// ----------------------------------------------------------------------------

redirect('product_index.php');