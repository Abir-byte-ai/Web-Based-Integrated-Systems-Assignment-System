<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// (1) Authorization (member)
auth('Member');

// Define allowed columns for sorting
$allowed_columns = ['id', 
                    'datetime',
                    'count',
                    'total'];
$allowed_orders = ['asc','desc'];

$sort_column = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_columns) ? $_GET['sort'] : 'id';
$sort_order = isset($_GET['order']) && in_array(strtolower($_GET['order']), $allowed_orders) ? strtolower($_GET['order']) : 'desc';

$next_order = $sort_order === 'asc' ? 'desc' : 'asc';

$stm = $_db->prepare("
    SELECT * FROM `order`
    WHERE user_id = ?
    ORDER BY $sort_column $sort_order
");
$stm->execute([$_user->id]);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------

include '../_head.php';
?>

<!-- (B) EXTRA: CSS -->
<style>
    tr:hover .popup  {
        display: grid !important;
        grid: auto / repeat(5, auto);
        gap: 1px;
        border: none;
    } 

    .popup img {
        width: 50px;
        height: 50px;
        outline: 1px solid #333;
    }
</style>

<p>
    <button data-post="history.php" >HISTORY</button>
</p>

<p><?= count($arr) ?> record(s)</p>

<table class="table">
    <tr>
        <th><a href="?sort=id&order=<?= $next_order ?>">Id<?= sort_arrow('id', $sort_column, $sort_order) ?></a></th>
        <th><a href="?sort=datetime&order=<?= $next_order ?>">Datetime<?= sort_arrow('datetime', $sort_column, $sort_order) ?></a></th>
        <th><a href="?sort=count&order=<?= $next_order ?>">Count<?= sort_arrow('count', $sort_column, $sort_order) ?></a></th>
        <th><a href="?sort=total&order=<?= $next_order ?>">Total (RM)<?= sort_arrow('total', $sort_column, $sort_order) ?></a></th>
        <th></th>
    </tr>

    <?php foreach ($arr as $o): ?>
    <tr>
        <td><?= $o->id ?></td>
        <td><?= $o->datetime ?></td>
        <td class="right"><?= $o->count ?></td>
        <td class="right"><?= $o->total ?></td>
        <td>
            <button data-get="detail.php?id=<?= $o->id ?>">Detail</button>
            <!-- (A) EXTRA: Product photos -->
            <div class="popup">
                <?php
                    $stm = $_db->prepare('
                        SELECT p.photo 
                        FROM item AS i, product AS p
                        WHERE i.product_id = p.id
                        AND i.order_id = ?
                    ');
                    $stm->execute([$o->id]);
                    $photos = $stm->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($photos as $photo) {
                        echo "<img src='/upload/$photo'>";
                    }
                ?>
            </div>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<?php
include '../_foot.php';
