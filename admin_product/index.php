<?php
include '../_base.php';
auth('Admin','Admin_Product');

// ----------------------------------------------------------------------------

$fields  = [
    'id'        =>'Id',
    'name'      =>'Name',
    'price'     =>"Price"
];
$sort = req('sort');
key_exists($sort, $fields) || $sort = 'id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

$page = req('page', 1);
$name = req('name');

require_once '../lib/SimplePager.php';
$p = new SimplePager("SELECT * FROM product ORDER BY $sort $dir", [], 10, $page);
$arr = $p->result;

$id = null;
$action = null;

if ($name) {
    $stm = $_db->prepare('SELECT * FROM product WHERE name LIKE ?');
    $stm->execute(["%$name%"]);
    $arr= $stm->fetchAll();
}
if (isset($_GET['action']) && isset($_GET['id'])) {     //checks whether a variable is set, to be declared and is not NULL
    $id = $_GET['id'];
    $action = $_GET['action'];
}   
if ($action == 'block') {
    $stm = $_db->prepare('UPDATE product SET status = "Blocked" WHERE id = ?');
    $stm->execute([$id]);
    temp('info', 'product inactivated');
} elseif ($action == 'unblock') {
    $stm = $_db->prepare('UPDATE product SET status = "Active" WHERE id = ?');
    $stm->execute([$id]);
    temp('info', 'product activated');
}
// ----------------------------------------------------------------------------

include '../_head.php';
?>

<style>
    .popup {
        width: 100px;
        height: 100px;
    }
</style>
<form>
    <?= html_search('name') ?>
    <button>Search</button>
</form>
<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
</p>
<p>
    <button data-get="insert.php">Insert</button>
</p>


<table class="table">
    <tr>
    <?= table_headers($fields, $sort, $dir, "page=$page") ?>
    <th></th>
    </tr>

    <?php foreach ($arr as $s): ?>
    <tr>
        <td><?= $s->id ?></td>
        <td><?= $s->name ?></td>
        <td><?= $s->price ?></td>
        <td>
            <button data-get="update.php?id=<?= $s->id ?>">Update</button>
            <form method="get" onsubmit="return confirm('Are you sure you want to inactivated this product?')">
                <input type="hidden" name="id" value="<?= $s->id ?>">
                <button type="submit" name="action" value="block" class="btn btn-danger" <?= $s->status == 'Blocked' ? 'disabled' : '' ?>>Block</button>
                <button type="submit" name="action" value="unblock" class="btn btn-success" <?= $s->status == 'Active' ? 'disabled' : '' ?>>Unblock</button>
            </form>
        </td>
    </tr>
    <?php endforeach ?>
</table>
<br>

<?= $p->html("sort=$sort&dir=$dir") ?>
<?php
include '../_foot.php';
