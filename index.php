<?php 
require '_base.php';

$_title = 'Home';
include '_head.php';
?>

<div class="home-page">
    <img src="/images/shoe_2.jpg" alt="backgroud-image">
    <div class="home-page-welcome">
        <p>WELCOME TO SHOE SHOP</p>
        <?php if($_user?->role == "Member" || !$_user): ?>
            <a href="/product/list.php" class="home-page-button">Browse Now</a>
        <?php endif ?>
    </div>
</div>

<?php
include '_foot.php';