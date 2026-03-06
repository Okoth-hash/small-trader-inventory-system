<?php
session_start();
define('BASE_URL', '/small-trader-inventory-system/');
$pageTitle = 'Edit Product';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$uid = $_SESSION['user_id'];
$id  = intval($_GET['id'] ?? 0);
$product = $conn->query("SELECT * FROM products WHERE id=$id AND user_id=$uid")->fetch_assoc();
if (!$product) { $_SESSION['error']='Product not found.'; header('Location: products.php'); exit(); }
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name=$_POST['product_name']; $category=$_POST['category'];
    $buy=floatval($_POST['buying_price']); $sell=floatval($_POST['selling_price']);
    $qty=intval($_POST['quantity']); $thresh=intval($_POST['low_stock_threshold']);
    $unit=$_POST['unit']; $desc=$_POST['description'];
    $stmt=$conn->prepare("UPDATE products SET product_name=?,category=?,buying_price=?,selling_price=?,quantity=?,low_stock_threshold=?,unit=?,description=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssddiissii",$name,$category,$buy,$sell,$qty,$thresh,$unit,$desc,$id,$uid);
    if ($stmt->execute()) { $_SESSION['success']='Product updated!'; header('Location: products.php'); exit(); }
}
?>
<div class="wrapper">
<?php include '../../includes/sidebar.php'; ?>
<main class="main-content">
    <div class="topbar"><h1><i class="fas fa-edit"></i> Edit Product</h1><a href="products.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a></div>
    <?php include '../../includes/alerts.php'; ?>
    <div class="card form-card">
        <form method="POST">
            <div class="form-row">
                <div class="form-group"><label>Product Name *</label><input type="text" name="product_name" required value="<?php echo htmlspecialchars($product['product_name']); ?>"></div>
                <div class="form-group"><label>Category</label><input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Buying Price (KES)</label><input type="number" step="0.01" name="buying_price" value="<?php echo $product['buying_price']; ?>"></div>
                <div class="form-group"><label>Selling Price (KES)</label><input type="number" step="0.01" name="selling_price" value="<?php echo $product['selling_price']; ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Quantity</label><input type="number" name="quantity" value="<?php echo $product['quantity']; ?>"></div>
                <div class="form-group"><label>Unit</label>
                    <select name="unit">
                    <?php foreach(['piece','kg','litre','packet','box','dozen'] as $u): ?>
                    <option value="<?php echo $u; ?>" <?php echo $product['unit']===$u?'selected':''; ?>><?php echo ucfirst($u); ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row"><div class="form-group"><label>Low Stock Threshold</label><input type="number" name="low_stock_threshold" value="<?php echo $product['low_stock_threshold']; ?>"></div></div>
            <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Product</button>
        </form>
    </div>
</main>
</div>
<?php include '../../includes/footer.php'; ?>
