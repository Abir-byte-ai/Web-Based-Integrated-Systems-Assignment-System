<?php
require '../_base.php';

//-----------------------------------------------------------------------------
//block/unblock actions
if (isset($_GET['action']) && isset($_GET['id'])) {     //checks whether a variable is set, to be declared and is not NULL
    $id = $_GET['id'];
    $action = $_GET['action'];
//-----------------------------------------------------------------------------

    if ($action == 'block') {
        $stm = $_db->prepare('UPDATE user SET status = "Blocked" WHERE id = ? AND role = ?');
        $stm->execute([$id, 'member']);
        temp('info', 'Member account blocked');
    } elseif ($action == 'unblock') {
        $stm = $_db->prepare('UPDATE user SET status = "Active" WHERE id = ? AND role = ?');
        $stm->execute([$id, 'member']);
        temp('info', 'Member account unblocked');
    }
}

//-----------------------------------------------------------------------------
// Search members
$name = req('name');
$stm = $_db->prepare('SELECT * FROM user WHERE role = ? AND name LIKE ?');
$stm->execute(['member', "%$name%"]);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------

include '../_head.php';
?>
<!-- //----------------------------------------------------------------------------- -->
<form method="get">
    <?= html_search('name') ?>
    <button>Search</button>
</form>
<!-- //----------------------------------------------------------------------------- -->

<p><?= count($arr) ?> record(s)</p>

<!-- //----------------------------------------------------------------------------- -->

<table class="table">
    <tr>
        <th>Id</th>
        <th>Email</th>
        <th>Name</th>
        <th>Role</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php foreach ($arr as $s): ?>
    <tr>
        <td><?= $s->id ?></td>
        <td><?= $s->email ?></td>
        <td><?= $s->name ?></td>
        <td><?= $s->role ?></td>
        <td><?= $s->status ?></td>
        <td>
            <form method="get" onsubmit="return confirm('Are you sure you want to change the status of this member?')">
                <input type="hidden" name="id" value="<?= $s->id ?>">
                <button type="submit" name="action" value="block" class="btn btn-danger" <?= $s->status == 'Blocked' ? 'disabled' : '' ?>>Block</button>
                <button type="submit" name="action" value="unblock" class="btn btn-success" <?= $s->status == 'Active' ? 'disabled' : '' ?>>Unblock</button>
            </form>
            <br>
            <form method="GET" action="/admin_account_manage/member_detail.php" style="display:inline;">
            <input type="hidden" name="id" value="<?= $s->id ?>">
            <button type="submit" class="button">Details</button>
            </form>
            
        </td>
    </tr>
    <?php endforeach ?>
</table>

<!-- //----------------------------------------------------------------------------- -->
<?php
include '../_foot.php';
