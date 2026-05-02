<?php
include '../_base.php';

if (is_post()) {
    $product_id = req('id');

    if (isset($_POST['add_to_wishlist'])) {
        if (!isset($_user->id)) {
            redirect('/user/login.php');
        } else {
            add_to_wishlist($product_id);
            redirect('?');
        }
    } elseif (isset($_POST['remove_from_wishlist'])) {
        remove_from_wishlist($product_id);
        redirect('?');
    }
}

// ----------------------------------------------------------------------------
$category = req('category', ''); 
$name = req('name', ''); 
$price_range = req('price_range', ''); 

$query = 'SELECT * FROM product WHERE status = "Active"';

if ($category) {
    $query .= ' AND category = ?';
}

if ($price_range) {
    switch ($price_range) {
        case '0-400':
            $query .= ' AND price BETWEEN 0 AND 400';
            break;
        case '400-800':
            $query .= ' AND price BETWEEN 400 AND 800';
            break;
        case '800-1200':
            $query .= ' AND price BETWEEN 800 AND 1200';
            break;
    }
}
// ----------------------------------------------------------------------------
$stm = $_db->prepare($query);
$parameters = $category ? [$category] : [];
$stm->execute($parameters);
$arr = $stm->fetchAll();

// the product name is searched, filter the fetched array
if ($name) {
    $arr = array_filter($arr, function($product) use ($name) {
        return stripos($product->name, $name) !== false;
    });
}

// ----------------------------------------------------------------------------

include '../_head.php';
?>

<div id="category-buttons">
    <a href="?"><button>All</button></a>
    <a href="?category=mouse"><button>Mouse</button></a>
    <a href="?category=keyboard"><button>Keyboard</button></a>
    <a href="?category=speaker"><button>Speaker</button></a>
    <a href="?category=headset"><button>Headset</button></a>
    <a href="?category=monitor"><button>Monitor</button></a>
</div>
<!-- // ---------------------------------------------------------------------------- -->
<div id="price-buttons">
    <a href="?price_range=0-400"><button>RM 0 - 400</button></a>
    <a href="?price_range=400-800"><button>RM 400 - 800</button></a>
    <a href="?price_range=800-1200"><button>RM 800 - 1200</button></a>
</div>
<!-- // ---------------------------------------------------------------------------- -->
<form method="get">
    <input type="hidden" name="category" value="<?= htmlentities($category) ?>">
    <input type="text" name="name" placeholder="Search product..." value="<?= htmlentities($name) ?>">
    <button type="submit">Search</button>
</form>
<!-- // ---------------------------------------------------------------------------- -->
<div id="products">
    <?php
    $wishlist = get_wishlist();
    foreach ($arr as $p): ?>
        <div class="product">
            <img src="/upload/<?= $p->photo ?>" data-get="/products/detail.php?id=<?= $p->id ?>">

            <div class="details">
                <span class="name"><?= $p->name ?></span>
                <span class="price">RM <?= $p->price ?></span>

            <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $p->id ?>">
                    <?php if (isset($wishlist[$p->id])): ?>
                        <button type="submit" name="remove_from_wishlist" value="1" class="btn btn-danger">Remove from Wishlist</button>
                    <?php else: ?>
                    <button type="submit" name="add_to_wishlist" value="1" class="btn btn-secondary">Add to Wishlist</button>
                    <?php endif; ?>
</form>
            </div>
        </div>
    <?php endforeach ?>
</div>

<?php
include '../_foot.php';
?>
