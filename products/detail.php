<?php
include '../_base.php';

if (is_post()) {
    $product_id = req('id');
    $quantity = req('quantity', 1);

    if (isset($_POST['add_to_cart'])) {
        if (!isset($_user->id)) {
            redirect('/user/login.php');
        } else {
            update_cart($product_id, $quantity);
            redirect('/products/cart.php');
        }
    } else {
        redirect();
    }
}
// --------------------------------------------------------------------------------------------------------------
$id = req('id');
$stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
$stm->execute([$id]);
$p = $stm->fetch();
if (!$p) redirect('/products/list.php');
// --------------------------------------------------------------------------------------------------------------
$stm = $_db->prepare('SELECT * FROM product_images WHERE product_id = ?');  // images associated the product
$stm->execute([$id]);
$images = $stm->fetchAll();

include '../_head.php';
?>
<!-- //-------------------------------------------------------------------------------------------------- -->

<!-- user_friendly button  -->
<button type="button" onclick="history.back();">Back</button> 

<!-- -------------------------------Main Swiper Container -------------------------------------------------->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">    <!-- First photo is the main photo -->
            <img src="/upload/<?= htmlspecialchars($p->photo) ?>" alt="<?= htmlspecialchars($p->name) ?>">
        </div>
        <?php foreach ($images as $img): ?>
            <div class="swiper-slide">
                <img src="/upload/<?= htmlspecialchars($img->image_path) ?>" alt="<?= htmlspecialchars($p->name) ?>">
            </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

<div class="thumbnail-container">
    <div class="thumbnail active" data-slide-index="0">
        <img src="/upload/<?= htmlspecialchars($p->photo) ?>" alt="<?= htmlspecialchars($p->name) ?>">
    </div>
    <?php foreach ($images as $index => $img): ?>
        <div class="thumbnail" data-slide-index="<?= $index + 1 ?>">
            <img src="/upload/<?= htmlspecialchars($img->image_path) ?>" alt="<?= htmlspecialchars($p->name) ?>">
        </div>
    <?php endforeach; ?>
</div>

<?php if ($_user?->role === 'Member'): ?>
    <form method="post" style="display:inline;">
        <input type="hidden" name="id" value="<?= $p->id ?>">
        <div class="form-group">
            <br>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control" style="width: 60px; display:inline-block;">
        </div>
        <br>
        <button type="submit" name="add_to_cart" value="1" class="btn btn-primary">Add to Cart</button>
        <button type="button" 
        onclick="location.href='/products/buy_now.php?id=<?= $p->id ?>&quantity=' + document.getElementById('quantity').value + '&photo=<?= urlencode($p->photo) ?>'" 
        class="btn btn-success">Buy Now</button>

    </form>
<?php endif ?>
<!-- //-------------------------------------------------------------------------------------------------- -->

<table class="table detail">
    <tr>
        <th>Id</th>
        <td><?= htmlspecialchars($p->id) ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?= htmlspecialchars($p->name) ?></td>
    </tr>
    <tr>
        <th>Price</th>
        <td>RM <?= htmlspecialchars($p->price) ?></td>
    </tr>
    <tr>
        <th>Detail</th>
        <td><?= nl2br(htmlspecialchars($p->details)) ?></td>
    </tr>
</table>

<!-- //------------------           SLIDER FUNCTION -------------------------------------------------- -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper('.swiper-container', {
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });

// -------  Thumbnail Navigation &&&& Update active thumbnail on slide change  ---------------------------------
    document.querySelectorAll('.thumbnail').forEach((thumbnail) => {
        thumbnail.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-slide-index'));
            swiper.slideToLoop(index);
            document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
            this.classList.add('active');
        });
    });
    swiper.on('slideChange', function () {
        const activeIndex = swiper.realIndex;
        document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
        document.querySelector(`.thumbnail[data-slide-index="${activeIndex}"]`).classList.add('active');
    });
</script>

<?php
include '../_foot.php';
?>