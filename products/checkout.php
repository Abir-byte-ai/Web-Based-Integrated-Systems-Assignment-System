<?php
include '../_base.php';

if (!isset($_user->id)) {
    redirect('/user/login.php');
}

$cart = get_cart();
if (!$cart) {
    redirect('/products/cart.php');
}

$stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
$total_amount = 0;
$cart_products = [];

foreach ($cart as $id => $unit) {
    $stm->execute([$id]);
    $product = $stm->fetch();
    $product->unit = $unit;
    $product->subtotal = $product->price * $unit;
    $cart_products[] = $product;
    $total_amount += $product->subtotal;
}
// --------------------------------------------------------------------------------------------------
$fake_payment_details = [
    ['username' => 'vincent', 'password' => '123456'],
    ['username' => 'terence', 'password' => '123456'],
    ['username' => 'weisheng', 'password' => '123456'],
    ['username' => 'abir', 'password' => '123456']
];
// --------------------------------------------------------------------------------------------------
if (is_post()) {

    $name = req('name');
    $address = req('address');
    $email = req('email');
    $payment_option = req('payment_option');
    $username = req('username');
    $password = req('password');

    // Check payment success
    $is_payment_successful = false;
    foreach ($fake_payment_details as $details) {
        if ($username === $details['username'] && $password === $details['password']) {
            $is_payment_successful = true;
            break;
        }
    }

    include '../_head.php';
    ?>

    <h1>Order Confirmation</h1>
    
    <?php if ($is_payment_successful): ?>
        
        <div class="form">
            <h4>Payment Successful!</h4>
            <p>Your order has been placed successfully. Thank you for your purchase!</p>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
            <p><strong>Payment Method:</strong> Bank Transfer</p>
            <p><strong>E-Receipt Sent To:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Total Amount:</strong> RM <?= sprintf('%.2f', $total_amount) ?></p>
            <a href="/order/history.php" class="btn btn-primary">Go to Order History</a>
            
        </div>

        <?php
        // Determine the payment status
        $payment_status = $is_payment_successful ? 'Successful' : 'Failed';

        // Save order details into the order_history table
        $order_stm = $_db->prepare('
            INSERT INTO order_history 
            (user_id, product_id, quantity, name, address, payment_option, payment_status, created_at, datetime, total) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)
        ');

        foreach ($cart_products as $product) {
            $order_stm->execute([
                $_user->id,
                $product->id,
                $product->unit,
                $name,
                $address,
                $payment_option,
                $payment_status,
                $product->subtotal
            ]);
        }
        
        clear_cart(); // Clear the cart after successful order


// --------------------------------E-receipt------------------------------------------------------------------------
        // Generate Order History URL
        $order_history_url = 'http://' . $_SERVER['HTTP_HOST'] . '/order/history.php';

        // Prepare E-Receipt Email with Product Images and Order History Link
        $subject = "E-Receipt for Your Purchase";
        $message = "
            <h1>Thank you for your purchase!</h1>
            <p>Dear $name,</p>
            <p>Your order has been placed successfully. Here are the details of your purchase:</p>
            <ul>";

        $mail = get_mail();
        
        foreach ($cart_products as $index => $product) {
            // Embed product image
            $image_path = $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $product->photo;
            $cid = 'product' . $index;
            if (file_exists($image_path)) {
                $mail->addEmbeddedImage($image_path, $cid);
            }
// -------------------------------------------------------------------------------------------------------- -->

            $message .= "               
                <li>
                    <img src='cid:$cid' alt='" . htmlspecialchars($product->name) . "' style='width: 100px; height: 100px;'><br>
                    <strong>Product:</strong> " . htmlspecialchars($product->name) . "<br>
                    <strong>Quantity:</strong> " . htmlspecialchars($product->unit) . "<br>
                    <strong>Price per item:</strong> RM " . htmlspecialchars($product->price) . "<br>
                    <strong>Subtotal:</strong> RM " . number_format($product->subtotal, 2) . "<br>
                </li><br>";
        }
// -------------------------------------------------------------------------------------------------------- -->
        $message .= "
                <li><strong>Total Amount:</strong> RM " . number_format($total_amount, 2) . "</li>
                <li><strong>Payment Method:</strong> " . htmlspecialchars($payment_option) . "</li>
            </ul>
            <p>You can view your order history <a href='$order_history_url'>here</a>.</p>
            <p>If you have any questions, feel free to contact us at rsdassignment@gmail.com.</p>
            <p>Thank you for shopping with us!</p>
        ";

        $mail->addAddress($email, $name); // Use provided email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if ($mail->send()) {
            temp('info', 'E-Receipt has been sent to your email.');
        } else {
            temp('error', 'Failed to send E-Receipt.');
        }

        ?>
<!-- // -------------------------------------------------------------------------------------------------------- -->
    <?php else: ?>
        <div class="form">
            <h4>Payment Failed</h4>
            <p>The payment details you provided are incorrect. Please try again.</p>
            <button type="button" onclick="history.back()" class="btn btn-warning">Try Again</button>
        </div>
    <?php endif; ?>

    <?php
    include '../_foot.php';
    exit;
}
// <!-- // -------------------------------------------------------------------------------------------------------- -->

include '../_head.php';
?>

<h1>Payment Page</h1>

<div class="form">
    <?php foreach ($cart_products as $product): ?>
    <div class="product-card">
        <img src="/upload/<?= htmlspecialchars($product->photo) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="product-photo">
        <div class="product-details">
            <h4><?= htmlspecialchars($product->name) ?></h4>
            <p>Price: RM <?= htmlspecialchars($product->price) ?></p>
            <p>Quantity: <?= htmlspecialchars($product->unit) ?></p>
            <p>Subtotal: RM <?= sprintf('%.2f', $product->subtotal) ?></p>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="total-amount">
        <h3>Total Amount: RM <?= sprintf('%.2f', $total_amount) ?></h3>
    </div>
</div>

<form id="paymentForm" method="post">
    <div class="form">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" class="form-control" required>
        <label for="address">Address</label>
        <textarea id="address" name="address" class="form-control" style="width: 400px; height:70px" required></textarea>
        
        <label for="email">Email (for E-Receipt)</label>
        <input type="email" id="email" name="email" class="form-control" required>

        <label for="payment_option">Payment Option</label>
        <select id="payment_option" name="payment_option" class="form-control" required>
            <option value="bank_transfer">Online Banking</option>
        </select>
        <label for="username">Bank Username</label>
        <input type="text" id="username" name="username" class="form-control" required>
        <label for="password">Bank Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
        <br>
    <button type="button" class="btn btn-success" onclick="confirmPayment()">Submit Payment</button>
    <button type="button" onclick="history.back()" class="btn btn-secondary">Back</button>
    </div>
</form>

<!-- // -------------------------------------------------------------------------------------------------------- -->

<script>
    function confirmPayment() {
        var name = document.getElementById('name').value;
        var address = document.getElementById('address').value;
        var email = document.getElementById('email').value;
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;

        if (name && address && email && username && password) {
            if (confirm('Are you sure you want to proceed with this payment?')) {
                document.getElementById('paymentForm').submit();
            }
        } else {
            alert('Please fill in all fields before proceeding.');
        }
    }
</script>

<!-- // -------------------------------------------------------------------------------------------------------- -->

<style>
    .checkout-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .product-card {
        border: 1px solid #ddd;
        padding: 10px;
        width: 300px;
        display: flex;
        gap: 10px;
    }
    .product-photo {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
    .product-details {
        flex-grow: 1;
    }
    .total-amount {
        width: 100%;
        text-align: right;
        margin-top: 20px;
    }
</style>

<!-- // -------------------------------------------------------------------------------------------------------- -->

<?php
include '../_foot.php';
?>
