<?php
session_start();
define('BASE_URL', '/small-trader-inventory-system/');
$pageTitle = 'Dashboard';
require_once 'config/database.php';
require_once 'includes/header.php';

$uid = $_SESSION['user_id'];

$total_products  = $conn->query("SELECT COUNT(*) as t FROM products WHERE user_id=$uid")->fetch_assoc()['t'];
$low_stock_count = $conn->query("SELECT COUNT(*) as t FROM products WHERE user_id=$uid AND quantity <= low_stock_threshold")->fetch_assoc()['t'];
$today           = date('Y-m-d');
$month_start     = date('Y-m-01');
$sales_today     = $conn->query("SELECT COALESCE(SUM(total_amount),0) as t FROM sales WHERE user_id=$uid AND sale_date='$today'")->fetch_assoc()['t'];
$expenses_total  = $conn->query("SELECT COALESCE(SUM(amount),0) as t FROM expenses WHERE user_id=$uid AND expense_date>='$month_start'")->fetch_assoc()['t'];
$sales_month     = $conn->query("SELECT COALESCE(SUM(total_amount),0) as t FROM sales WHERE user_id=$uid AND sale_date>='$month_start'")->fetch_assoc()['t'];
$cogs_total      = $conn->query("SELECT COALESCE(SUM(s.quantity_sold*p.buying_price),0) as t FROM sales s JOIN products p ON s.product_id=p.id WHERE s.user_id=$uid AND s.sale_date>='$month_start'")->fetch_assoc()['t'];
$net_profit      = $sales_month - $cogs_total - $expenses_total;
$recent_sales    = $conn->query("SELECT s.*,p.product_name FROM sales s JOIN products p ON s.product_id=p.id WHERE s.user_id=$uid ORDER BY s.created_at DESC LIMIT 5");
$low_products    = $conn->query("SELECT * FROM products WHERE user_id=$uid AND quantity<=low_stock_threshold ORDER BY quantity ASC LIMIT 5");
?>
<div class="wrapper">
<?php include 'includes/sidebar.php'; ?>
<main class="main-content">
    <div class="topbar">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> &mdash; <?php echo date('l, d F Y'); ?></span>
    </div>
    <?php include 'includes/alerts.php'; ?>
    <div class="stats-grid">
        <div class="stat-card blue"><div class="stat-icon"><i class="fas fa-boxes"></i></div><div class="stat-info"><h3><?php echo $total_products; ?></h3><p>Total Products</p></div></div>
        <div class="stat-card green"><div class="stat-icon"><i class="fas fa-cash-register"></i></div><div class="stat-info"><h3>KES <?php echo number_format($sales_today,2); ?></h3><p>Today's Sales</p></div></div>
        <div class="stat-card orange"><div class="stat-icon"><i class="fas fa-receipt"></i></div><div class="stat-info"><h3>KES <?php echo number_format($expenses_total,2); ?></h3><p>Expenses This Month</p></div></div>
        <div class="stat-card <?php echo $net_profit>=0?'purple':'red'; ?>"><div class="stat-icon"><i class="fas fa-chart-line"></i></div><div class="stat-info"><h3>KES <?php echo number_format($net_profit,2); ?></h3><p>Net Profit This Month</p></div></div>
    </div>
    <?php if ($low_stock_count > 0): ?>
    <div class="alert alert-warning" style="margin:16px 28px;">
        <i class="fas fa-exclamation-triangle"></i>
        <strong><?php echo $low_stock_count; ?> product(s)</strong> are running low on stock!
        <a href="modules/inventory/products.php?filter=low_stock">View &rarr;</a>
    </div>
    <?php endif; ?>
    <div class="dashboard-grid">
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-history"></i> Recent Sales</h3><a href="modules/sales/sales_history.php">View All</a></div>
            <table class="table">
                <thead><tr><th>Product</th><th>Qty</th><th>Amount</th><th>Payment</th><th>Date</th></tr></thead>
                <tbody>
                <?php while($s=$recent_sales->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['product_name']); ?></td>
                    <td><?php echo $s['quantity_sold']; ?></td>
                    <td>KES <?php echo number_format($s['total_amount'],2); ?></td>
                    <td><span class="badge <?php echo $s['payment_method']==='mpesa'?'badge-green':'badge-blue'; ?>"><?php echo strtoupper($s['payment_method']); ?></span></td>
                    <td><?php echo $s['sale_date']; ?></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-exclamation-circle"></i> Low Stock Products</h3><a href="modules/inventory/products.php">View All</a></div>
            <table class="table">
                <thead><tr><th>Product</th><th>Quantity</th><th>Threshold</th></tr></thead>
                <tbody>
                <?php while($p=$low_products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['product_name']); ?></td>
                    <td><span class="badge badge-red"><?php echo $p['quantity']; ?></span></td>
                    <td><?php echo $p['low_stock_threshold']; ?></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</div>
<?php include 'includes/footer.php'; ?>
