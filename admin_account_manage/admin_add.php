<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email    = req('email');
    $password = req('password');
    $confirm  = req('confirm');
    $name     = req('name');
    $role     = req('role');
    $f = get_file('photo');

    //----------------------VALIDATION-------------------------------------------------------
    if (!$email) {
        $_err['email'] = 'Required';
    } else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    } else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    } else if (!is_unique($email, 'user', 'email')) {
        $_err['email'] = 'Duplicated';
    }

    if (!$password) {
        $_err['password'] = 'Required';
    } else if (strlen($password) < 5 || strlen($password) > 100) {
        $_err['password'] = 'Between 5-100 characters';
    }

    if (!$confirm) {
        $_err['confirm'] = 'Required';
    } else if (strlen($confirm) < 5 || strlen($confirm) > 100) {
        $_err['confirm'] = 'Between 5-100 characters';
    } else if ($confirm != $password) {
        $_err['confirm'] = 'Not matched';
    }

    if (!$name) {
        $_err['name'] = 'Required';
    } else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    if (!$role) {
        $_err['role'] = 'Required';
    } else if (!in_array($role, ['Admin', 'Admin_Product', 'Admin_Account'])) {
        $_err['role'] = 'Invalid role selected';
    }

    if (!$f) {
        $_err['photo'] = 'Required';
    } else if (!str_starts_with($f->type, 'image/')) {
        $_err['photo'] = 'Must be image';
    } else if ($f->size > 1 * 1024 * 1024) {
        $_err['photo'] = 'Maximum 1MB';
    }
    //----------------------VALIDATION-------------------------------------------------------

    if (!$_err) {
        // Save photo
        $photo = save_photo($f, '../photos');

        // Insert user with selected role
        $stm = $_db->prepare('
            INSERT INTO user (email, password, name, photo, role)
            VALUES (?, SHA1(?), ?, ?, ?)
        ');
        $stm->execute([$email, $password, $name, $photo, $role]);

        temp('info', 'Admin record inserted');
        redirect('/admin_account_manage/admin.php');
    }
}

//------------------------------------------------------------------------------------

include '../_head.php';
?>

<!-- Button to go back -->
<form method="GET" action="/admin_account_manage/admin.php" style="display:inline;">
    <button type="submit" class="button">Back</button>
</form>

<form method="post" class="form" enctype="multipart/form-data">
    <p><span id="header">Register Admin</span></p>

    <label for="email">Email</label>
    <?= html_text('email', 'maxlength="100"') ?>
    <?= err('email') ?>

    <label for="password">Password</label>
    <div class="password-wrapper">
        <?= html_password('password', 'maxlength="100" id="password" class="password-input"') ?>
        <img src="/images/eyeclose.png" class="toggle-password" onclick="togglePassword('password', this)" alt="Show/Hide Password">
    </div>
    <?= err('password') ?>

    <label for="confirm">Confirm Password</label>
    <div class="password-wrapper">
        <?= html_password('confirm', 'maxlength="100" id="confirm" class="password-input"') ?>
        <img src="/images/eyeclose.png" class="toggle-password" onclick="togglePassword('confirm', this)" alt="Show/Hide Password">
    </div>
    <?= err('confirm') ?>

    <label for="name">Name</label>
    <?= html_text('name', 'maxlength="100"') ?>
    <?= err('name') ?>

    <label for="role">Role</label>
    <select name="role" id="role">
        <option value="Admin_Product">Admin Product</option>
        <option value="Admin_Account">Admin Account</option>
    </select>
    <?= err('role') ?>

    <label for="photo">Photo</label>
    <label class="upload" tabindex="0">
        <?= html_file('photo', 'image/*', 'hidden') ?>
        <img src="/images/photo.jpg">
    </label>
    <?= err('photo') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<script>
function togglePassword(id, toggleIcon) {
    var input = document.getElementById(id);
    if (input.type === "password") { 
        input.type = "text";
        toggleIcon.src = "/images/eyeopen.png";
    } else {
        input.type = "password";
        toggleIcon.src = "/images/eyeclose.png";
    }
}
</script>

<style>
.password-wrapper {
    position: relative;
}
.password-input {
    width: 98%;
}
.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    width: 20px;
    height: 20px;
}
</style>

<?php
include '../_foot.php';
?>
