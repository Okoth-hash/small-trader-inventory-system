<?php
session_start();
define('BASE_URL', '/small-trader-inventory-system/');
$pageTitle = 'Sales History';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$uid=$_SESSION['user_id'];
$from=$_GET['from']??date('Y-m-01'); $to=$_GET['to']??date('Y-m-d');
$sales=$conn->query("SELECT s.*,p.product_name FROM sales s JOIN products p ON s.product_id=p.id WHERE s.user_id=$uid AND s.sale_date BETWEEN '$from' AND '$to' ORDER BY s.sale_date DESC");
$tot=$conn->query("SELECT COALESCE(SUM(total_amount),0) as gt,COUNT(*) as c FROM sales WHERE user_id=$uid AND sale_date BETWEEN '$from' AND '$to'")->fetch_assoc();
?>
<div class="wrapper">
<?php include '../../includes/sidebar.php'; ?>
<main class="main-content">
    <div class="topbar"><h1><i class="fas fa-history"></i> Sales History</h1><a href="record_sale.php" class="btn btn-primary"><i class="fas fa-plus"></i> Record Sale</a></div>
    <?php include '../../includes/alerts.php'; ?>
    <div class="card">
        <form method="GET" class="filter-form"><div class="form-row">
            <div class="form-group"><label>From</label><input type="date" name="from" value="<?php echo $from; ?>"></div>
            <div class="form-group"><label>To</label><input type="date" name="to" value="<?php echo $to; ?>"></div>
            <div class="form-group align-end"><button type="submit" class="btn btn-primary">Filter</button></div>
        </div></form>
        <div class="summary-bar"><span>Transactions: <strong><?php echo $tot['c']; ?></strong></span><span>Total: <strong>KES <?php echo number_format($tot['gt'],2); ?></strong></span></div>
        <table class="table">
            <thead><tr><th>#</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Total</th><th>Payment</th><th>MPesa Code</th><th>Customer</th><th>Date</th></tr></thead>
            <tbody>
            <?php $i=1; while($s=$sales->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($s['product_name']); ?></td>
                <td><?php echo $s['quantity_sold']; ?></td>
                <td>KES <?php echo number_format($s['unit_price'],2); ?></td>
                <td>KES <?php echo number_format($s['total_amount'],2); ?></td>
                <td><span class="badge <?php echo $s['payment_method']==='mpesa'?'badge-green':'badge-blue'; ?>"><?php echo strtoupper($s['payment_method']); ?></span></td>
                <td><?php echo htmlspecialchars($s['mpesa_code']??'-'); ?></td>
                <td><?php echo htmlspecialchars($s['customer_name']??'-'); ?></td>
                <td><?php echo $s['sale_date']; ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
</div>
<?php include '../../includes/footer.php'; ?>
