<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/images/logo.png">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/app.js"></script>
</head>
<body>
    <!-- Flash message -->
    <div id="info"><?= temp('info') ?></div>

    <header>
        <h1><a href="/">Smarttech</a></h1>

        <?php if ($_user): ?>
            <div>
                <?= $_user-> name ?><br>
                <?= $_user-> role ?>
            </div>
            <img src="/photos/<?= $_user->photo ?>">
        <?php endif ?>
    </header>
<!-- // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
    <nav>
        <a href="/">INDEX</a>
        <?php if ($_user?->role === 'Admin' || $_user?->role === 'Admin_Product'): ?>
            <a href="/admin_product/index.php">PRODUCT</a>
        <?php endif ?>
<!-- // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <?php if ($_user?->role === 'Admin' || $_user?->role === 'Admin_Account'): ?>
            <a href="/admin_account_manage/member.php">MEMBER</a>
        <?php endif ?>
<!-- // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <?php if ($_user?->role === 'Admin' || $_user?->role === 'Admin_Account'): ?>
            <a href="/admin_account_manage/admin.php">ADMIN</a>
        <?php endif ?>
<!-- // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <?php if ($_user?-> role == 'Member'): ?>
            <a href="/products/detail.php">PRODUCT</a>
        <?php endif ?>
<!-- // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <?php if ($_user?-> role == 'Member'): ?>
            <a href="/products/wishlist_list.php">
            WISHLIST
            <?php
                $cart = get_wishlist();
                $count = count($cart);
                if ($count) echo "($count)";
            ?>
            </a>
        <?php endif ?>
<!-- // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <?php if ($_user?-> role == 'Member'): ?>
            <a href="/products/cart.php">
            SHOPPING CART
            <?php
                $cart = get_cart();
                $count = count($cart);
                if ($count) echo "($count)";
            ?>
<!-- // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <?php if ($_user?->role == 'Member'): ?>
        <a href="/order/history.php">ORDER HISTORY</a>
        <?php endif ?>
        </a>
        <?php endif ?>
        <div></div>
<!-- // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <?php if ($_user): ?>
            <a href="/user/profile.php">PROFILE</a>
            <a href="/user/password.php">PASSWORD</a>
            <a href="/user/logout.php">LOGOUT</a>
        <?php else: ?>
            <a href="/user/login.php">LOGIN</a>
        <?php endif ?>
    </nav>

    <main>
