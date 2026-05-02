<?php
require '../_base.php';

$member_id = req('id');

$stm = $_db->prepare('SELECT * FROM user WHERE id = ? AND role = ?');
$stm->execute([$member_id, 'member']);
$member = $stm->fetch();

if (!$member) {
    temp('error', 'Member not found');
    redirect('/admin_account_manage/member_list.php');
}


if (is_post() && isset($_POST['shipping_status'])) {
    $shipping_status = $_POST['shipping_status'];
    $order_id = intval($_POST['order_id']);


    $stm = $_db->prepare('UPDATE order_history SET shipping_status = ? WHERE id = ? AND user_id = ?');
    $stm->execute([$shipping_status, $order_id, $member_id]);

    if ($stm->rowCount()) {
        temp('info', 'Shipping status updated successfully');
    } else {
        temp('error', 'Failed to update shipping status');
    }
}

$order_stm = $_db->prepare("
    SELECT id, datetime, quantity, total, payment_status, shipping_status 
    FROM order_history 
    WHERE user_id = ? 
    ORDER BY datetime DESC
");
$order_stm->execute([$member_id]);
$order_history = $order_stm->fetchAll(PDO::FETCH_OBJ);

include '../_head.php';
?>

<h2>Member Details</h2>
<form method="GET" action="/admin_account_manage/member_list.php" style="display:inline;">
    <button type="submit" class="button">Back to Member List</button>
</form>

<table class="table">
    <tr>
        <th>ID</th>
        <td><?= htmlspecialchars($member->id) ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?= htmlspecialchars($member->name) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= htmlspecialchars($member->email) ?></td>
    </tr>
</table>

<h3>Order History</h3>
<table class="table">
    <tr>
        <th>Order ID</th>
        <th>Date & Time</th>
        <th>Quantity</th>
        <th>Total (RM)</th>
        <th>Payment Status</th>
        <th>Shipping Status</th>
    </tr>

    <?php foreach ($order_history as $order): ?>
    <tr>
        <td><?= htmlspecialchars($order->id) ?></td>
        <td><?= htmlspecialchars($order->datetime) ?></td>
        <td><?= htmlspecialchars($order->quantity) ?></td>
        <td>RM <?= number_format($order->total, 2) ?></td>
        <td><?= htmlspecialchars($order->payment_status) ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="order_id" value="<?= $order->id ?>">
                <select name="shipping_status">
                    <option value="Pending" <?= $order->shipping_status == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Shipped" <?= $order->shipping_status == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="Delivered" <?= $order->shipping_status == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                    <option value="Cancelled" <?= $order->shipping_status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php
include '../_foot.php';
?>
