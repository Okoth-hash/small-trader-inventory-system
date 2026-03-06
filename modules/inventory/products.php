<?php
session_start();
define('BASE_URL', '/');
$pageTitle = 'Products';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$uid    = $_SESSION['user_id'];
$filter = $_GET['filter'] ?? '';
$search = trim($_GET['search'] ?? '');
$sql    = "SELECT * FROM products WHERE user_id=$uid";
if ($filter==='low_stock') $sql .= " AND quantity<=low_stock_threshold";
if ($search) $sql .= " AND product_name LIKE '%" . $conn->real_escape_string($search) . "%'";
$sql .= " ORDER BY created_at DESC";
$products = $conn->query($sql);
?>
<div class="wrapper">
<?php include '../../includes/sidebar.php'; ?>
<main class="main-content">
    <div class="topbar"><h1><i class="fas fa-boxes"></i> Products</h1><a href="add_product.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</a></div>
    <?php include '../../includes/alerts.php'; ?>
    <div class="card">
        <div class="card-header">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Search</button>
                <?php if($search||$filter): ?><a href="products.php" class="btn btn-outline">Clear</a><?php endif; ?>
            </form>
        </div>
        <table class="table">
            <thead><tr><th>#</th><th>Product</th><th>Category</th><th>Buy Price</th><th>Sell Price</th><th>Qty</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php $i=1; while($p=$products->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($p['product_name']); ?></td>
                <td><?php echo htmlspecialchars($p['category']); ?></td>
                <td>KES <?php echo number_format($p['buying_price'],2); ?></td>
                <td>KES <?php echo number_format($p['selling_price'],2); ?></td>
                <td><?php echo $p['quantity'].' '.$p['unit']; ?></td>
                <td><?php if($p['quantity']<=$p['low_stock_threshold']): ?><span class="badge badge-red">Low Stock</span><?php else: ?><span class="badge badge-green">In Stock</span><?php endif; ?></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                    <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
</div>
<?php include '../../includes/footer.php'; ?>
