<?php
session_start();
define('BASE_URL', '/');
$pageTitle = 'Add Product';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$uid = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name       = trim($_POST['product_name']);
    $category   = trim($_POST['category']);
    $buy_price  = floatval($_POST['buying_price']);
    $sell_price = floatval($_POST['selling_price']);
    $quantity   = intval($_POST['quantity']);
    $threshold  = intval($_POST['low_stock_threshold']);
    $unit       = trim($_POST['unit']);
    $desc       = trim($_POST['description']);
    $stmt = $conn->prepare("INSERT INTO products (user_id,product_name,category,buying_price,selling_price,quantity,low_stock_threshold,unit,description) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("issddiiss",$uid,$name,$category,$buy_price,$sell_price,$quantity,$threshold,$unit,$desc);
    if ($stmt->execute()) { $_SESSION['success']='Product added!'; header('Location: products.php'); exit(); }
    else { $_SESSION['error']='Failed to add product.'; }
}
?>
<div class="wrapper">
<?php include '../../includes/sidebar.php'; ?>
<main class="main-content">
    <div class="topbar"><h1><i class="fas fa-plus-circle"></i> Add Product</h1><a href="products.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a></div>
    <?php include '../../includes/alerts.php'; ?>
    <div class="card form-card">
        <form method="POST">
            <div class="form-row">
                <div class="form-group"><label>Product Name *</label><input type="text" name="product_name" required placeholder="e.g. Unga Ndovu 2kg"></div>
                <div class="form-group"><label>Category</label><input type="text" name="category" placeholder="e.g. Flour, Beverages"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Buying Price (KES) *</label><input type="number" step="0.01" name="buying_price" required placeholder="0.00"></div>
                <div class="form-group"><label>Selling Price (KES) *</label><input type="number" step="0.01" name="selling_price" required placeholder="0.00"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Quantity *</label><input type="number" name="quantity" required placeholder="0"></div>
                <div class="form-group"><label>Unit</label>
                    <select name="unit"><option value="piece">Piece</option><option value="kg">KG</option><option value="litre">Litre</option><option value="packet">Packet</option><option value="box">Box</option><option value="dozen">Dozen</option></select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Low Stock Threshold</label><input type="number" name="low_stock_threshold" value="5" min="1"></div>
            </div>
            <div class="form-group"><label>Description</label><textarea name="description" rows="3" placeholder="Optional"></textarea></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Product</button>
        </form>
    </div>
</main>
</div>
<?php include '../../includes/footer.php'; ?>
