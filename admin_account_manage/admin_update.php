<?php
require '../_base.php';

// Get admin id from query parameter
$admin_id = req('id');

// Fetch current admin details
$stm = $_db->prepare('SELECT * FROM user WHERE id = ?');
$stm->execute([$admin_id]);
$admin = $stm->fetch();

if (!$admin) {
    temp('error', 'Admin not found');
    redirect('/admin_account_manage/admin.php');
}

// Handle Update Admin functionality
if (is_post()) {
    $email    = req('email');
    $password = req('password');
    $name     = req('name');
    $role     = req('role');
    $f        = get_file('photo');

    // Validation
    if (!$email) {
        $_err['email'] = 'Required';
    } else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    } else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }

    if ($password && (strlen($password) < 5 || strlen($password) > 100)) {
        $_err['password'] = 'Between 5-100 characters';
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

    if ($f && (!str_starts_with($f->type, 'image/') || $f->size > 1 * 1024 * 1024)) {
        $_err['photo'] = 'Must be a valid image of maximum size 1MB';
    }

    if (!$_err) {
        // Save new photo if provided
        if ($f) {
            $photo = save_photo($f, '../photos');
        } else {
            $photo = $admin->photo;
        }

        // Update admin details
        if ($password) {
            $stm = $_db->prepare('UPDATE user SET email = ?, password = SHA1(?), name = ?, photo = ?, role = ? WHERE id = ?');
            $stm->execute([$email, $password, $name, $photo, $role, $admin_id]);
        } else {
            $stm = $_db->prepare('UPDATE user SET email = ?, name = ?, photo = ?, role = ? WHERE id = ?');
            $stm->execute([$email, $name, $photo, $role, $admin_id]);
        }

        temp('info', 'Admin details updated');
        redirect('/admin_account_manage/admin.php');
    }
}

include '../_head.php';
?>

<form method="post" class="form" enctype="multipart/form-data">
    <p><span id="header">Edit Admin</span></p>

    <label for="email">Email</label>
    <p><?= htmlspecialchars($admin->email) ?></p>  <!-- Display email instead of input -->
    <?= err('email') ?>

    <label for="password">New Password (leave blank to keep current)</label>
    <div class="password-wrapper">
        <?= html_password('password', 'maxlength="100" id="password" class="password-input"') ?>
        <img src="/images/eyeclose.png" class="toggle-password" onclick="togglePassword('password', this)" alt="Show/Hide Password">
    </div>
    <?= err('password') ?>

    <label for="name">Name</label>
    <?= html_text('name', 'value="'.$admin->name.'" maxlength="100"') ?>
    <?= err('name') ?>

    <label for="role">Role</label>
    <select name="role">
        <option value="Admin_Product" <?= $admin->role == 'Admin_Product' ? 'selected' : '' ?>>Admin Product</option>
        <option value="Admin_Account" <?= $admin->role == 'Admin_Account' ? 'selected' : '' ?>>Admin Account</option>
    </select>
    <?= err('role') ?>

    <label for="photo">Photo</label>
    <label class="upload" tabindex="0">
        <?= html_file('photo', 'image/*', 'hidden') ?>
        <img src="<?= $admin->photo ? '/photos/' . $admin->photo : '/images/photo.jpg' ?>" alt="Current Photo">
    </label>
    <?= err('photo') ?>

    <section>
        <button type="submit">Update</button>
        <button type="reset">Reset</button>
        <button type="button" onclick="history.back();">Back</button> <!-- Back button added here -->
    </section>
</form>


<script>
    function togglePassword(id, toggleIcon) {  //function for showing and hiding the password input field
        var input = document.getElementById(id); // the input variable store input field element gEBI
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
