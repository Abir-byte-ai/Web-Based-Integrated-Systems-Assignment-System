<?php
include '../_base.php';

if (!isset($_user->id)) {
    redirect('/user/login.php');
}

$product_id = req('id');
$quantity = req('quantity', 1);
$photo = req('photo');

$stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
$stm->execute([$product_id]);
$product = $stm->fetch();

if (!$product) {
    redirect('/products/list.php');
}

$fake_payment_details = [
    ['username' => 'vincent', 'password' => '123456'],
    ['username' => 'terence', 'password' => '123456'],
    ['username' => 'weisheng', 'password' => '123456'],
    ['username' => 'abir', 'password' => '123456']
];

if (is_post()) {
    $name = req('name');
    $address = req('address');
    $payment_option = req('payment_option');
    $username = req('username');
    $password = req('password');
    $email = req('email'); // Capture email from form

    // Check payment success
    $is_payment_successful = false;
    foreach ($fake_payment_details as $details) {
        if ($username === $details['username'] && $password === $details['password']) {
            $is_payment_successful = true;
            break;
        }
    }

    $subtotal = $product->price * $quantity;
    $payment_status = $is_payment_successful ? 'Successful' : 'Failed';

    // Insert order into order_history table with subtotal
    $stm = $_db->prepare('
        INSERT INTO order_history (user_id, product_id, quantity, name, address, payment_option, payment_status, total)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stm->execute([
        $_user->id, 
        $product_id, 
        $quantity, 
        $name, 
        $address, 
        $payment_option, 
        $payment_status, 
        $subtotal
    ]);

    include '../_head.php';
// --------------------------------E-receipt------------------------------------------------------------------------
    // If payment is successful, send the E-Receipt
    if ($is_payment_successful) {
        // Generate Order History URL
        $order_history_url = 'http://' . $_SERVER['HTTP_HOST'] . '/order/history.php';

        // Prepare E-Receipt Email with Product Image
        $subject = "E-Receipt for Your Purchase";
        $message = "
            <h1>Thank you for your purchase!</h1>
            <p>Dear $name,</p>
            <p>Your order has been placed successfully. Here are the details of your purchase:</p>
            <ul>
                <li><img src='cid:productImage' alt='Product Image' style='width: 100px; height: 100px;'></li>
                <li><strong>Product:</strong> " . htmlspecialchars($product->name) . "</li>
                <li><strong>Quantity:</strong> " . htmlspecialchars($quantity) . "</li>
                <li><strong>Price per item:</strong> RM " . htmlspecialchars($product->price) . "</li>
                <li><strong>Total Amount:</strong> RM " . number_format($subtotal, 2) . "</li>
                <li><strong>Payment Method:</strong> " . htmlspecialchars($payment_option) . "</li>
            </ul>
        ";

        $message .= "
            <u/>
            <p>You can view your order history <a href='$order_history_url'>here</a>.</p>
            <p>If you have any questions, feel free to contact us at rsdassignment@gmail.com.</p>
            <p>Thank you for shopping with us!</p>
        ";
// -------------------------------------------------------------------------------------------------------- -->
        $mail = get_mail();

        // Embed product image
        $image_path = $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $product->photo;
        if (file_exists($image_path)) {
            $mail->addEmbeddedImage($image_path, 'productImage');
        }

        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if ($mail->send()) {
            temp('info', 'E-Receipt has been sent to your email.');
        } else {
            temp('error', 'Failed to send E-Receipt.');
        }
    }

    ?>
<!-- // -------------------------------------------------------------------------------------------------------- -->
    <h1>Order Confirmation</h1>

    <?php if ($is_payment_successful): ?>
        <div class="form">
            <h4>Payment Successful!</h4>
            <p>Your order has been placed successfully. Thank you for your purchase!</p>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
            <p><strong>Payment Method:</strong> Bank Transfer</p>
            <p><strong>E-Receipt Sent To:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Total Amount:</strong> RM <?= number_format($subtotal, 2) ?></p>
            <a href="/order/history.php" class="btn btn-primary">Go to Order History</a>
            
        </div>
    <?php else: ?>
        <div class="form">
            <h4>Payment Failed</h4>
            <p>The payment details you provided are incorrect. Please try again.</p>
            <a href="/products/list.php" class="btn btn-primary">Try Again</a>
        </div>
    <?php endif; ?>

    <?php
    include '../_foot.php';
    exit(); // Stop further execution to prevent redirection
} else {
    include '../_head.php';
    ?>
<!-- // -------------------------------------------------------------------------------------------------------- -->
    <h1>Payment Page</h1>

    <div class="form">
        <img src="/upload/<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($product->name) ?>" style="width: 150px; height: 150px;">
        <div>
            <strong>Product:</strong> <?= htmlspecialchars($product->name) ?><br>
            <strong>Quantity:</strong> <?= htmlspecialchars($quantity) ?><br>
            <strong>Price per item:</strong> RM <?= htmlspecialchars($product->price) ?><br>
            <strong>Total Price:</strong> RM <?= htmlspecialchars($product->price * $quantity) ?><br>
        </div>
    </div>

    <form id="paymentForm" method="post">
        <div class="form">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" required>

            <label for="email">Email (for E-Receipt)</label>
            <input type="email" id="email" name="email" class="form-control" required>

            <label for="address">Address</label>
            <textarea id="address" name="address" style="width: 400px; height:70px" required></textarea>

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

    <script>
        function confirmPayment() {
            var name = document.getElementById('name').value;
            var address = document.getElementById('address').value;
            var email = document.getElementById('email').value; // Capture email value
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

    <?php
    include '../_foot.php';
}
?>
