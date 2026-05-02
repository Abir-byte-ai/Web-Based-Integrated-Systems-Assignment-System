<?php
include '../_base.php';
auth('Admin', 'Admin_Product');
// ----------------------------------------------------------------------------

if (is_get()) {
    $id = req('id');

    // Fetch product details
    $stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
    $stm->execute([$id]);
    $p = $stm->fetch();

    if (!$p) {
        redirect('index.php');
    }

    extract((array)$p);
    $_SESSION['photo'] = $p->photo;

    // Fetch existing additional images
    $stm = $_db->prepare('SELECT * FROM product_images WHERE product_id = ?');
    $stm->execute([$id]);
    $additional_images = $stm->fetchAll();

    $category_stm = $_db->query('SELECT DISTINCT category FROM product');
    $categories = $category_stm->fetchAll(PDO::FETCH_COLUMN);
}

if (is_post()) {
    $id              = req('id');
    $name            = req('name');
    $price           = req('price');
    $f_main          = get_file('photo');       // Main photo
    $additional_files = get_files('photos');     // Additional photos
    $photo           = $_SESSION['photo']; 
    $details         = req('details'); // Product description
    $category = req('category');

    // Handle image deletions
    $delete_image_ids = req('delete_images', []); // Array of image IDs to delete

    // Validate: name
    if ($name == '') {
        $_err['name'] = 'Required';
    }
    elseif (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    // Validate: price
    if ($price == '') {
        $_err['price'] = 'Required';
    }
    elseif (!is_money($price)) {
        $_err['price'] = 'Must be money';
    }
    elseif ($price < 0.01 || $price > 9999.99) {
        $_err['price'] = 'Must be between 0.01 - 9999.99';
    }

    // Validate: main photo (optional if not changing)
    if ($f_main) {
        if (!str_starts_with($f_main->type, 'image/')) {
            $_err['photo'] = 'Main photo must be an image';
        }
        elseif ($f_main->size > 1 * 1024 * 1024) { // 1MB limit
            $_err['photo'] = 'Main photo maximum 1MB';
        }
    }

    // Validate: additional photos (optional)
    foreach ($additional_files as $file) {
        if (!str_starts_with($file->type, 'image/')) {
            $_err['photos'] = 'All additional photos must be images';
            break;
        }
        elseif ($file->size > 2 * 1024 * 1024) { // 2MB per additional photo
            $_err['photos'] = 'Each additional photo must be less than 2MB';
            break;
        }
    }

    // Validate: details (Product Description)
    if (trim($details) == '') {
        $_err['details'] = 'Product description is required';
    }
    // Optionally, set a maximum length if desired
    // else if (strlen($details) > 2000) {
    //     $_err['details'] = 'Maximum 2000 characters';
    // }

    // DB operation
    if (!$_err) {
        // Begin transaction
        $_db->beginTransaction();
        try {
            // Handle main photo upload if a new one is provided
            if ($f_main) {
                if (file_exists("../upload/$photo")) {
                    unlink("../upload/$photo");
                }
                $photo = save_photo($f_main, '../upload', 400, 400); // Adjust size as needed
            }

            // Update product details
            $stm = $_db->prepare('
                UPDATE product
                SET name = ?, price = ?, photo = ?, details = ?, category = ?
                WHERE id = ?
            ');
            $stm->execute([$name, $price, $photo, $details, $category, $id]);

            // Handle deletion of additional images
            if (!empty($delete_image_ids)) {
                // Fetch image paths to delete from filesystem
                $placeholders = implode(',', array_fill(0, count($delete_image_ids), '?'));
                $stm = $_db->prepare("SELECT image_path FROM product_images WHERE id IN ($placeholders) AND product_id = ?");
                $params = array_merge($delete_image_ids, [$id]);
                $stm->execute($params);
                $images_to_delete = $stm->fetchAll();

                foreach ($images_to_delete as $img) {
                    if (file_exists("../upload/{$img->image_path}")) {
                        unlink("../upload/{$img->image_path}");
                    }
                }

                // Delete from database
                $stm = $_db->prepare("DELETE FROM product_images WHERE id IN ($placeholders) AND product_id = ?");
                $stm->execute($params);
            }

            // Handle uploading of additional photos
            foreach ($additional_files as $file) {
                $new_image_path = save_photo($file, '../upload', 400, 400); // Adjust size as needed
                if ($new_image_path) {
                    $stm = $_db->prepare('INSERT INTO product_images (product_id, image_path) VALUES (?, ?)');
                    $stm->execute([$id, $new_image_path]);
                }
            }

            // Commit transaction
            $_db->commit();

            temp('info', 'Record updated successfully');
            redirect('index.php?id=' . $id);
        } catch (Exception $e) {
            // Rollback on error
            $_db->rollBack();
            $_err['general'] = 'An error occurred: ' . $e->getMessage();
        }
    }

    // If there was an error, fetch existing additional images again
    $stm = $_db->prepare('SELECT * FROM product_images WHERE product_id = ?');
    $stm->execute([$id]);
    $additional_images = $stm->fetchAll();
}

// ----------------------------------------------------------------------------

include '../_head.php';
?>

<p>
    <button data-get="index.php">Back</button>
</p>

<?php if (isset($_err['general'])): ?>
    <div class="error"><?= htmlspecialchars($_err['general']) ?></div>
<?php endif; ?>

<form method="post" class="form" enctype="multipart/form-data" novalidate>
    <br>
    <div class="form-group">
        <label for="id">Id</label>
        <b><?= htmlspecialchars($id) ?></b>
    </div>
    <br>
    <div class="form-group">
        <label for="name">Name</label>
        <?= html_text('name', 'id="name" maxlength="100"') ?>
        <?= err('name') ?>
    </div>
    <br>

    <div class="form-group">
        <label for="price">Price</label>
        <?= html_number('price', 0.01, 9999.99, 0.01) ?>
        <?= err('price') ?>
    </div>
    <br>

    <div class="form-group">
        <label for="photo">Main Photo</label>
        <label class="upload" tabindex="0">
            <?= html_file('photo', 'image/*', 'hidden') ?>
            <img src="/upload/<?= htmlspecialchars($photo) ?>" alt="Product Photo" style="max-width: 200px; max-height: 200px;">
        </label>
        <?= err('photo') ?>
    </div>
    <br>

    <div class="form-group">
        <label for="photos">Additional Photos</label>
        <?= html_file('photos[]', 'image/*', 'multiple') ?>
        <?= err('photos') ?>
    </div>
    <br>

    <?php if (!empty($additional_images)): ?>
        <div class="form-group">
            <label>Existing Additional Images:</label>
            <div style="display: flex; flex-wrap: wrap;">
                <?php foreach ($additional_images as $img): ?>
                    <div style="margin: 10px; text-align: center;">
                        <img src="/upload/<?= htmlspecialchars($img->image_path) ?>" alt="Additional Image" style="max-width: 150px; max-height: 150px;"><br>
                        <label>
                            <input type="checkbox" name="delete_images[]" value="<?= htmlspecialchars($img->id) ?>">
                            Delete
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <br>
    <?php endif; ?>

    <div class="form-group">
        <label for="details">Product Description</label>
        <?= html_textarea('details', 'id="details" rows="5" cols="50"') ?>
        <?= err('details') ?>
    </div>
    <label for="category">Category</label>
    <select name="category" id="category" required>
        <option value="">Select a category</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= encode($cat) ?>" <?= $cat === $category ? 'selected' : '' ?>>
                <?= encode($cat) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?= err('category') ?>


    <br>
    <section>
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '../_foot.php';
?>
