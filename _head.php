<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../js/shoe_shop.js"></script>
    <title><?= $_title ?? "Unknown" ?></title>
</head>
<body>
    <!-- Flash message -->
    <div id="info"><?= temp('info') ?></div>

    <header>

        <nav class="navbar">
            <div class="navbar-left">
                <a id="logo" href="/">Home</a>
            </div>

            <div class="navbar-right">
                <!-- Admin's Nav Bar -->
                <?php if ($_user?->role == "Admin"): ?>
                    <a class="links" href="/admin/order_listing.php">Orders</a>
                    <a class="links" href="/admin/memberIndex.php">Member Index</a>
                    <a class="links" href="/admin/product_index.php">Product Index</a>
                <?php endif ?>

                <!--Member's Nav Bar-->
                <?php if ($_user?->role == "Member"): ?>
                    <a class="links" href="/order/history.php">Order History</a>
                    <a class="links" href="/product/list.php">Products</a>
                    <a class="links" href="/order/cart.php">Cart</a>
                <?php endif ?>

                <?php if (!$_user):?>
                <!-- Not logged in user's Nav Bar -->
                <a class="links" href="/product/list.php">Products</a>
                <a class="links" href="/order/cart.php">Cart</a>

                <div class="nav-user-pf">
                    <img src="/images/photo.jpg">
                    <div class="nav-user-text">
                        <div class="user-name">-----</div>
                        <div class="user-role">-----</div>
                    </div>
                </div>
                <?php endif ?>
                
                <!-- user's profile picture -->
                <?php if ($_user): ?>
                    <div class="nav-user-pf">
                        <img src="/photos/<?= $_user->photo ?>">
                        <div class="nav-user-text">
                            <div class="user-name"><?= $_user->name ?></div>
                            <div class="user-role"><?= $_user->role ?></div>
                        </div>
                    </div>
                <?php endif ?>
               </div>
        </nav>

        <nav class="navbar-lower">
            <div class="navbar-lower-right">
                <?php if ($_user): ?>
                    <!-- Logged in user's Nav Bar -->
                    <a class="links" href="/user/profile.php">Profile</a>
                    <div class="vertical-line"></div>
                    <a class="links" href="/user/password.php">Reset Password</a>
                    <div class="vertical-line"></div>
                    <a class="links gap" href="/logout.php">Logout</a>
                <?php else: ?>
                    <!-- Not logged in user's Nav Bar -->
                    <a class="links" href="/login.php">Login</a>
                    <div class="vertical-line"></div>
                    <a class="links gap" href="/user/memberRegistration.php">Register</a>
                <?php endif ?>
            </div>        
        </nav>
        
    </header>

    <main>