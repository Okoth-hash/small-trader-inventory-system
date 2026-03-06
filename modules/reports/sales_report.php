<?php
session_start();
define('BASE_URL', '/small-trader-inventory-system/');
$pageTitle = 'Sales Report';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$uid=$_SESSION['user_id'];
$from=$_GET['from']??date('Y-m-01'); $to=$_GET['to']??date('Y-m-d');
$sales=$conn->query("SELECT s.*,p.product_name,p.buying_price FROM sales s JOIN products p ON s.product_id=p.id WHERE s.user_id=$uid AND s.sale_date BETWEEN '$from' AND '$to' ORDER BY s.sale_date DESC");
$sum=$conn->query("SELECT COALESCE(SUM(s.total_amount),0) as rev,COALESCE(SUM(s.quantity_sold*p.buying_price),0) as cogs,COUNT(*) as c FROM sales s JOIN products p ON s.product_id=p.id WHERE s.user_id=$uid AND s.sale_date BETWEEN '$from' AND '$to'")->fetch_assoc();
$gp=$sum['rev']-$sum['cogs'];
?>
<div class="wrapper">
<?php include '../../includes/sidebar.php'; ?>
<main class="main-content">
    <div class="topbar"><h1><i class="fas fa-chart-bar"></i> Sales Report</h1></div>
    <?php include '../../includes/alerts.php'; ?>
    <div class="card">
        <form method="GET" class="filter-form"><div class="form-row">
            <div class="form-group"><label>From</label><input type="date" name="from" value="<?php echo $from; ?>"></div>
            <div class="form-group"><label>To</label><input type="date" name="to" value="<?php echo $to; ?>"></div>
            <div class="form-group align-end"><button type="submit" class="btn btn-primary">Generate</button></div>
        </div></form>
        <div class="stats-grid" style="padding:16px 20px;">
            <div class="stat-card green"><div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div><div class="stat-info"><h3>KES <?php echo number_format($sum['rev'],2); ?></h3><p>Revenue</p></div></div>
            <div class="stat-card orange"><div class="stat-icon"><i class="fas fa-shopping-cart"></i></div><div class="stat-info"><h3>KES <?php echo number_format($sum['cogs'],2); ?></h3><p>Cost of Goods</p></div></div>
            <div class="stat-card blue"><div class="stat-icon"><i class="fas fa-chart-line"></i></div><div class="stat-info"><h3>KES <?php echo number_format($gp,2); ?></h3><p>Gross Profit</p></div></div>
            <div class="stat-card purple"><div class="stat-icon"><i class="fas fa-receipt"></i></div><div class="stat-info"><h3><?php echo $sum['c']; ?></h3><p>Transactions</p></div></div>
        </div>
        <table class="table">
            <thead><tr><th>#</th><th>Product</th><th>Qty</th><th>Revenue</th><th>Cost</th><th>Gross Profit</th><th>Payment</th><th>Date</th></tr></thead>
            <tbody>
            <?php $i=1; while($s=$sales->fetch_assoc()): $gpr=$s['total_amount']-($s['quantity_sold']*$s['buying_price']); ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($s['product_name']); ?></td>
                <td><?php echo $s['quantity_sold']; ?></td>
                <td>KES <?php echo number_format($s['total_amount'],2); ?></td>
                <td>KES <?php echo number_format($s['quantity_sold']*$s['buying_price'],2); ?></td>
                <td class="<?php echo $gpr>=0?'text-green':'text-red'; ?>">KES <?php echo number_format($gpr,2); ?></td>
                <td><span class="badge <?php echo $s['payment_method']==='mpesa'?'badge-green':'badge-blue'; ?>"><?php echo strtoupper($s['payment_method']); ?></span></td>
                <td><?php echo $s['sale_date']; ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
</div>
<?php include '../../includes/footer.php'; ?>
