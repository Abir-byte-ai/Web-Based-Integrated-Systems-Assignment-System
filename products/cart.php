<?php
include '../_base.php';
// --------------------------------------------------------------------------------------------------------------

$cart = get_cart();

error_log('Cart contents: ' . print_r($cart, true));
error_log('User role: ' . ($_user?->role ?? 'Not logged in'));
// --------------------------------------------------------------------------------------------------------------

if (is_post()) {
    $btn = req('btn');
    $id = req('id');
    if ($btn == 'clear') { 
        if ($id) {
            clear_cart($id);
        }
        redirect();
    } elseif ($btn == 'clear_all') {
        clear_cart();
        redirect('?');
    } else {
        $unit = req('unit');
        update_cart($id, $unit);
        redirect();
    }
}

$search = req('search', '');

include '../_head.php';
?>
<!-- // -------------------------------------------------------------------------------------------------------------- -->
<form method="get">
    <input type="text" name="search" placeholder="Search product..." value="<?= htmlentities($search) ?>">
    <button type="submit">Search</button>
</form>

<table class="table">
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Price (RM)</th>
        <th>Unit</th>
        <th>Subtotal (RM)</th>
        <th></th>
    </tr>
<!-- // -------------------------------------------------------------------------------------------------------------- -->

    <?php
        $count = 0;
        $total = 0;

        $stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
        $cart = get_cart();

        foreach ($cart as $id => $unit):
            $stm->execute([$id]);
            $p = $stm->fetch();

            if ($search && stripos($p->name, $search) === false) {
                continue;
            }

            $subtotal = $p->price * $unit;
            $count += $unit;
            $total += $subtotal;
    ?>
        <tr>
            <td><?= $p->id ?></td>
            <td><?= $p->name ?></td>
            <td class="right"><?= $p->price ?></td>
            <td>
                <form method="post">
                    <?= html_hidden('id', $id) ?>
                    <?= html_select('unit', $_units, $unit) ?>
                </form>            
            </td>
            <td class="right">
                <?= sprintf('%.2f', $subtotal) ?>
            </td>
            <td>
                <form method="post">
                    <?= html_hidden('id', $id) ?>
                    <button type="submit" name="btn" value="clear">Clear</button> 
                    <img src="/upload/<?= $p->photo ?>" class="popup">
                </form>
            </td>
        </tr>
    <?php endforeach ?>

    <tr>
        <th colspan="3"></th>
        <th class="right"><?= $count ?></th>
        <th class="right"><?= sprintf('%.2f', $total) ?></th>
        <th></th>
    </tr>
</table>
<!-- // -------------------------------------------------------------------------------------------------------------- -->
<p>
    <?php if ($cart): ?>
        <form method="post" style="display:inline;">
            <button type="submit" name="btn" value="clear_all">Clear All</button>
        </form>
        
        <?php if ($_user?->role == 'Member'): ?>
            <!-- Button for the multi-product checkout -->
            <button type="button" onclick="location.href='/products/checkout.php'" class="btn btn-success">Checkout</button>
        <?php endif ?>
    <?php endif ?>
</p>
<!-- // --------------------------------------------------------------------------------------------------------- -->

<style>
    .popup {
        width: 100px;
        height: 100px;
    }
</style>

<script>
    $('select').on('change', e => e.target.form.submit());
</script>

<?php
include '../_foot.php';
?>