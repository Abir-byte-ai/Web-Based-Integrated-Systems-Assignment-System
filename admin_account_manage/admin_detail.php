<?php
require '../_base.php';

// Fetch admin ID from the request
$admin_id = req('id');

// Fetch Admin Details
$stm = $_db->prepare('SELECT * FROM user WHERE id = ?');
$stm->execute([$admin_id]);
$admin = $stm->fetch();

if (!$admin) {
    die('Admin not found.');
}

// ----------------------------------------------------------------------------
include '../_head.php';
?>

<h1>Admin Details</h1>
<form method="GET" action="/admin_account_manage/admin.php" style="display:inline;">
    <button type="submit" class="button">Back</button>
</form>


<table class="table">
    <tr>
        <th>Id</th>
        <td><?= htmlspecialchars($admin->id) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= htmlspecialchars($admin->email) ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?= htmlspecialchars($admin->name) ?></td>
    </tr>
    <tr>
        <th>Role</th>
        <td><?= ucfirst(htmlspecialchars($admin->role)) ?></td>
    </tr>
    <tr>
</table>

<?php
include '../_foot.php';
?>
