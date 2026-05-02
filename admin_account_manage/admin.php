<?php
require '../_base.php';

// Fetch form inputs
$name = req('name');
$delete_admin_id = req('delete_admin_id');

// Handle Delete Admin functionality
if ($delete_admin_id) {
    $del_stm = $_db->prepare('DELETE FROM user WHERE id = ?');
    $del_stm->execute([$delete_admin_id]);
}

// Fetch Admin Records
$stm = $_db->prepare('SELECT * FROM user WHERE role IN ("Admin", "Admin_Product", "Admin_Account") AND name LIKE ?');
$stm->execute(["%$name%"]);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------
include '../_head.php';
?>

<!-- Search Form -->
<form>
    <?= html_search('name') ?>
    <button>Search</button>
</form>

<p><?= count($arr) ?> record(s)</p>

<!-- Link to Add Admin Page -->
<form method="GET" action="/admin_account_manage/admin_add.php" style="display:inline;">
    <button type="submit" class="button">ADD ADMIN</button>
</form>

<!-- Admin Records Table -->
<table class="table">
    <tr>
        <th>Id</th>
        <th>Email</th>
        <th>Name</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($arr as $s): ?>
    <tr>
        <td><?= $s->id ?></td>
        <td><?= $s->email ?></td>
        <td><?= $s->name ?></td>
        <td><?= ucfirst($s->role) ?></td>
        <td>
    <?php if ($s->role !== 'Admin'): ?>
        <!-- View Admin Details Button -->
        <form method="GET" action="/admin_account_manage/admin_detail.php" style="display:inline;">
            <input type="hidden" name="id" value="<?= $s->id ?>">
            <button type="submit" class="button">Details</button>
        </form>
        
        <!-- Update Admin Button -->
        <form method="GET" action="/admin_account_manage/admin_update.php" style="display:inline;">
            <input type="hidden" name="id" value="<?= $s->id ?>">
            <button type="submit" class="button">Edit</button>
        </form>
        
        <!-- Delete Admin Button -->
        <form method="POST" style="display:inline;">
            <input type="hidden" name="delete_admin_id" value="<?= $s->id ?>">
            <button type="submit" onclick="return confirm('Are you sure you want to delete this admin?')">Delete</button>
        </form>
    <?php endif; ?>
</td>



    </tr>
    <?php endforeach ?>
</table>




<?php
include '../_foot.php';
?>
