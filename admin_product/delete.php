<?php
include '../_base.php';
auth('Admin','Admin_Product');
// ----------------------------------------------------------------------------

if (is_post()) {
    $id = req('id');

    $stm = $_db->prepare('UPDATE product SET active inactive WHERE id = ?');
    $stm->execute([$id]);
    temp('info', 'Record Inactivated');
}


redirect('index.php');

// ----------------------------------------------------------------------------
