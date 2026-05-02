<?php
include '../_base.php';

if (!isset($_user->id)) {
    redirect('/user/login.php');
}

$order_id = req('id');

if (!$order_id) {
    redirect('history.php');
}

$stm = $_db->prepare("
    SELECT oh.*, p.name AS product_name, p.price AS product_price, p.photo AS product_image, oh.shipping_status
    FROM order_history oh
    JOIN product p ON oh.product_id = p.id
    WHERE oh.id = ? AND oh.user_id = ?
");
$stm->execute([$order_id, $_user->id]);
$order_details = $stm->fetch(PDO::FETCH_OBJ);

if (!$order_details) {
    redirect('history.php');
}

include '../_head.php';
?>

<h1>Order Details</h1>
<button type="button" onclick="history.back();">Back</button>
<table class="table">
    <tr>
        <th>Date & Time</th>
        <td><?= htmlspecialchars($order_details->datetime) ?></td>
    </tr>
    <tr>
        <th>Product Name</th>
        <td><?= htmlspecialchars($order_details->product_name) ?></td>
    </tr>
    <tr>
        <th>Product Image</th>
        <td>
            <img src="/upload/<?= htmlspecialchars($order_details->product_image) ?>" alt="<?= htmlspecialchars($order_details->product_name) ?>" style="width: 100px; height: 100px; object-fit: cover;">
        </td>
    </tr>
    <tr>
        <th>Quantity</th>
        <td><?= htmlspecialchars($order_details->quantity) ?></td>
    </tr>
    <tr>
        <th>Price (RM)</th>
        <td>RM <?= number_format($order_details->product_price, 2) ?></td>
    </tr>
    <tr>
        <th>Total (RM)</th>
        <td>RM <?= number_format($order_details->total, 2) ?></td>
    </tr>
    <tr>
        <th>Payment Status</th>
        <td><?= htmlspecialchars($order_details->payment_status) ?></td>
    </tr>
    <tr>
    <th>Shipping Status</th>
    <td><?= htmlspecialchars($order_details->shipping_status) ?></td>
    </tr>
    <tr>
        <th>Shipping Address</th>
        <td><?= htmlspecialchars($order_details->address) ?></td>
    </tr>
    <tr>
        <th>Payment Option</th>
        <td><?= htmlspecialchars($order_details->payment_option) ?></td>
    </tr>
</table>


<?php
include '../_foot.php';
?>
