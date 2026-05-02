<?php
include '../_base.php';
auth('Admin', 'Admin_Product');

// ----------------------------------------------------------------------------

if (is_post()) {
    $name              = req('name');
    $price             = req('price');
    $f_main            = get_file('photo');    
    $additional_files  = get_files('photos');        
    $details           = req('details');    
    $category          = req('category');         
    $_err              = [];

    // Input validation
    if ($name == '') {
        $_err['name'] = 'Required';
    } elseif (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    if ($price == '') {
        $_err['price'] = 'Required';
    } elseif (!is_money($price)) {
        $_err['price'] = 'Must be money';
    } elseif ($price < 0.01 || $price > 9999.99) { 
        $_err['price'] = 'Must be between 0.01 - 9999.99';
    }

    if (!$f_main) {
        $_err['photo'] = 'Required';
    } elseif (!str_starts_with($f_main->type, 'image/')) {
        $_err['photo'] = 'Must be image';
    } elseif ($f_main->size > 1 * 1024 * 1024) {
        $_err['photo'] = 'Maximum 1MB';
    }

    foreach ($additional_files as $file) {
        if (!str_starts_with($file->type, 'image/')) {
            $_err['photos'] = 'All additional photos must be images';
            break;
        } elseif ($file->size > 2 * 1024 * 1024) {
            $_err['photos'] = 'Each additional photo must be less than 2MB';
            break;
        }
    }

    if (trim($details) == '') {
        $_err['details'] = 'Product description is required';
    } elseif (strlen($details) > 2000) {
        $_err['details'] = 'Maximum 2000 characters';
    }

    // -----------------------------MAIN PHOTO UPLOADING-------------------------------------------------------
    if (!$_err) {
        $_db->beginTransaction();
        try {
            // Get the next product ID
            $result = $_db->query("SELECT MAX(id) AS max_id FROM product");
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $max_id = $row['max_id'];

            // Generate new ID
            if ($max_id) {
                $number = (int)substr($max_id, 1); // Remove 'P' and convert to integer
                $number++; // Increment
            } else {
                $number = 1; // Start at 1 if no existing IDs
            }
            $id = 'P' . str_pad($number, 3, '0', STR_PAD_LEFT); // Format to "P000"

            // Save main photo
            $photo = save_photo($f_main, '../upload', 400, 400);
            $stm = $_db->prepare('
                INSERT INTO product (id, name, price, photo, details, category)
                VALUES (?, ?, ?, ?, ?, ?)
            ');
            $stm->execute([$id, $name, $price, $photo, $details, $category]);

            // --------------------- Uploading additional photos -------------------------------------------------
            foreach ($additional_files as $file) {
                $new_image_path = save_photo($file, '../upload', 400, 400);
                if ($new_image_path) {
                    $stm = $_db->prepare('INSERT INTO product_images (product_id, image_path) VALUES (?, ?)');
                    $stm->execute([$id, $new_image_path]);
                }
            }

            $_db->commit();
            temp('info', 'Record inserted successfully');
            redirect('index.php');

        } catch (Exception $e) {
            $_db->rollBack(); 
            $_err['general'] = 'An error occurred: ' . $e->getMessage();
        }
    }
}

include '../_head.php';
?>

<!-- // ---------------------------------------------------------------------------- -->
 
<p>
    <button data-get="index.php">Back</button>
</p>

<?php if (isset($_err['general'])): ?>
    <div class="error"><?= htmlspecialchars($_err['general']) ?></div>
<?php endif; ?>

<!-- Insert Product Form -->
<form method="post" class="form" enctype="multipart/form-data" novalidate>
    

    <label for="name">Name</label>
    <?= html_text('name', 'maxlength="100"') ?>
    <?= err('name') ?>

    <label for="price">Price</label>
    <?= html_number('price', 0.01, 9999.99, 0.01) ?>
    <?= err('price') ?>

    <label for="photo">Main Photo</label>
    <label class="upload" tabindex="0">
        <?= html_file('photo', 'image/*', 'hidden') ?>
        <img src="/images/photo.jpg">
    </label>
    <?= err('photo') ?>

    <label for="photos">Additional Photos</label>
    <?= html_file('photos[]', 'image/*', 'multiple') ?>
    <?= err('photos') ?>

    <label for="details">Product Detail</label>
    <?= html_textarea('details', 'maxlength="2000" rows="6"') ?>
    <?= err('details') ?>

    <label for="category">Category</label>
    <select name="category" id="category" required>
        <option value="">Select a category</option>
        <option value="mouse">Mouse</option>
        <option value="keyboard">Keyboard</option>
        <option value="speaker">Speaker</option>
        <option value="headset">Headset</option>
        <option value="monitor">Monitor</option>
    </select>
    <?= err('category') ?>

    <section>
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '../_foot.php';
?>
