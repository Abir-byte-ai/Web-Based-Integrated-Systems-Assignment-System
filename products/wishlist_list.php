<?php
include '../_base.php';
// --------------------------------------------------------------------------------
if (is_post()) {
    $product_id = req('id');

    if (isset($_POST['remove_from_wishlist'])) {
        remove_from_wishlist($product_id);
        redirect('?'); // Reload the page to reflect the changes
    }

// --------------------------------------------------------------------------------
   
    // if (isset($_POST['add_to_cart'])) {
    //     $quantity = req('quantity', 1);
    //     update_cart($product_id, $quantity);
    //     remove_from_wishlist($product_id); 
    //     redirect('?');
    // }
}
// --------------------------------------------------------------------------------

$wishlist = get_wishlist();
$products = [];

if ($wishlist) {
    $ids = array_keys($wishlist);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
// --------------------------------------------------------------------------------
    $stm = $_db->prepare("SELECT * FROM product WHERE id IN ($placeholders)");
    $stm->execute($ids);
    $products = $stm->fetchAll();
}
// --------------------------------------------------------------------------------
include '../_head.php';
?>

<?php if ($products): ?>
    <table class="table">
        <tr>
            <th>Product</th>
            <th>Name</th>
            <th>Price (RM)</th>
            <th></th>
        </tr>
        <?php foreach ($products as $p): ?>
            <tr>
                <td>
                    <a href="detail.php?id=<?= urlencode($p->id) ?>">
                        <img src="/upload/<?= htmlentities($p->photo) ?>" alt="<?= htmlentities($p->name) ?>">
                    </a>
                </td>
                <td>
                    <a class="product-name" ?>
                        <?= htmlentities($p->name) ?>
                    </a>
                </td>
                <td>RM <?= number_format($p->price, 2) ?></td>
                <td class="action-buttons">
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= htmlentities($p->id) ?>">
                        <button type="submit" name="remove_from_wishlist" value="1" class="btn-remove">Remove</button>
                    </form>
                    <!-- <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= htmlentities($p->id) ?>">
                        <input type="number" name="quantity" value="1" min="1" style="width: 50px;">
                        <button type="submit" name="add_to_cart" value="1" class="btn-add-cart">Add to Cart</button>
                    </form> -->
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Oops! Your wishlist is empty. Let’s add some amazing products.</p>
<?php endif; ?>

<?php
include '../_foot.php';
?>
