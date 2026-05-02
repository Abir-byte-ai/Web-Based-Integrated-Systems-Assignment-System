<?php
require '_base.php';
//-----------------------------------------------------------------------------

if (is_post()) {
    $id   = req('id');
    $unit = req('unit');
    update_cart($id, $unit);
    redirect();
}

$arr = $_db->query('SELECT * FROM product');

// ----------------------------------------------------------------------------

include '_head.php';
?>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    #products {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        justify-content: center; 
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .product {
        border: 1px solid #ddd;
        border-radius: 10px;
        width: 200px;
        overflow: hidden; 
        position: relative;
        text-align: left;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product:hover {
        transform: translateY(-5px); 
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); 
    }

    .product img {
        display: block;
        width: 100%;
        height: 150px;
        cursor: pointer;
        object-fit: cover;
        border-bottom: 1px solid #ddd; 
    }

    .product form {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 5px;
        border-radius: 5px;
        text-align: center;
        font-size: 12px;
    }

    .product .details {
        padding: 15px;
        background: #fff;
    }

    .product .details .name {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
        display: block;
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product .details .price {
        font-size: 16px;
        color: #27ae60;
        font-weight: bold;
        margin-bottom: 10px;
        display: block;
    }

    .product .details .btn {
        display: inline-block;
        padding: 10px 15px;
        background-color: #3498db;
        color: #fff;
        border: none;
        border-radius: 5px;
        text-align: center;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .product .details .btn:hover {
        background-color: #2980b9;
    }
</style>


<div id="mainphoto"><img src="photos/mainphoto.png" alt="." ></div>

<!-- // ---------------------------------------------------------------------------- -->


<div id="products">
    <?php foreach ($arr as $p): ?>
        <?php
        $cart = get_cart();
        $id   = $p->id;
        $unit = $cart[$p->id] ?? 0;
        ?>
        <div class="product">
         
                
            <img src="/upload/<?= $p->photo ?>" data-get="/products/detail.php?id=<?= $p->id ?>">

            <!-- Product details (name and price) -->
            <div class="details">
                <span class="name"><?= $p->name ?></span>
                <span class="price">RM <?= $p->price ?></span>
            </div>
        </div>
    <?php endforeach ?>
</div>

<script>
    $('select').on('change', e => e.target.form.submit());
</script>

<!-- // ---------------------------------------------------------------------------- -->
<?php
include '_foot.php';