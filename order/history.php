<?php
include '../_base.php';

if (!isset($_user->id)) {
    redirect('/user/login.php');
}

if (is_post() && isset($_POST['cancel_order_id'])) {
    $cancel_order_id = intval($_POST['cancel_order_id']);
    $stm = $_db->prepare('UPDATE order_history SET payment_status = "Cancelled" WHERE id = ? AND user_id = ? AND payment_status != "Cancelled"');
    $stm->execute([$cancel_order_id, $_user->id]);

    if ($stm->rowCount()) {
        temp('info', 'Order has been successfully cancelled.');
    } else {
        temp('error', 'Order cancellation failed or the order was already cancelled.');
    }


    redirect('/order/history.php');
}


$allowed_columns = ['datetime', 'quantity', 'total', 'payment_status', 'shipping_status'];
$allowed_orders = ['asc', 'desc'];

$sort_column = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_columns) ? $_GET['sort'] : 'datetime';
$sort_order = isset($_GET['order']) && in_array(strtolower($_GET['order']), $allowed_orders) ? strtolower($_GET['order']) : 'desc';

$next_order = $sort_order === 'asc' ? 'desc' : 'asc';

$stm = $_db->prepare("
    SELECT id, datetime, quantity, total AS subtotal, payment_status, shipping_status 
    FROM `order_history`
    WHERE user_id = ?
    ORDER BY $sort_column $sort_order
");
$stm->execute([$_user->id]);
$order_history = $stm->fetchAll(PDO::FETCH_OBJ);

include '../_head.php';
?>

<h1>Order History</h1>

<p><?= count($order_history) ?> record(s)</p>

<table class="table">
    <tr>
        <th><a href="?sort=datetime&order=<?= $next_order ?>">Datetime<?= sort_arrow('datetime', $sort_column, $sort_order) ?></a></th>
        <th><a href="?sort=quantity&order=<?= $next_order ?>">Quantity<?= sort_arrow('quantity', $sort_column, $sort_order) ?></a></th>
        <th><a href="?sort=subtotal&order=<?= $next_order ?>">Subtotal (RM)<?= sort_arrow('subtotal', $sort_column, $sort_order) ?></a></th>
        <th><a href="?sort=payment_status&order=<?= $next_order ?>">Payment Status<?= sort_arrow('payment_status', $sort_column, $sort_order) ?></a></th>
        <th><a href="?sort=shipping_status&order=<?= $next_order ?>">Shipping Status<?= sort_arrow('shipping_status', $sort_column, $sort_order) ?></a></th>
        <th>Action</th>
    </tr>

    <?php foreach ($order_history as $order): ?>
    <tr>
        <td><?= isset($order->datetime) ? htmlspecialchars($order->datetime) : 'N/A' ?></td>
        <td class="right"><?= htmlspecialchars($order->quantity) ?></td>
        <td class="right">RM <?= isset($order->subtotal) ? number_format($order->subtotal, 2) : '0.00' ?></td>
        <td><?= htmlspecialchars($order->payment_status) ?></td>
        <td><?= htmlspecialchars($order->shipping_status) ?></td>
        <td>
            <button data-get="detail.php?id=<?= $order->id ?>" class="btn btn-primary">Detail</button>
            <?php if ($order->payment_status !== 'Cancelled'): ?>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="cancel_order_id" value="<?= $order->id ?>">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel Order</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<?php
include '../_foot.php';
?>
