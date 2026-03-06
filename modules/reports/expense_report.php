<?php
session_start();
define('BASE_URL', '/small-trader-inventory-system/');
$pageTitle = 'Expense Report';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$uid=$_SESSION['user_id'];
$from=$_GET['from']??date('Y-m-01'); $to=$_GET['to']??date('Y-m-d');
$expenses=$conn->query("SELECT * FROM expenses WHERE user_id=$uid AND expense_date BETWEEN '$from' AND '$to' ORDER BY expense_date DESC");
$sum=$conn->query("SELECT COALESCE(SUM(amount),0) as gt,COUNT(*) as c FROM expenses WHERE user_id=$uid AND expense_date BETWEEN '$from' AND '$to'")->fetch_assoc();
$bycat=$conn->query("SELECT category,SUM(amount) as total FROM expenses WHERE user_id=$uid AND expense_date BETWEEN '$from' AND '$to' GROUP BY category ORDER BY total DESC");
?>
<div class="wrapper">
<?php include '../../includes/sidebar.php'; ?>
<main class="main-content">
    <div class="topbar"><h1><i class="fas fa-chart-pie"></i> Expense Report</h1></div>
    <?php include '../../includes/alerts.php'; ?>
    <div class="card">
        <form method="GET" class="filter-form"><div class="form-row">
            <div class="form-group"><label>From</label><input type="date" name="from" value="<?php echo $from; ?>"></div>
            <div class="form-group"><label>To</label><input type="date" name="to" value="<?php echo $to; ?>"></div>
            <div class="form-group align-end"><button type="submit" class="btn btn-primary">Generate</button></div>
        </div></form>
        <div class="stats-grid" style="padding:16px 20px;">
            <div class="stat-card red"><div class="stat-icon"><i class="fas fa-money-bill"></i></div><div class="stat-info"><h3>KES <?php echo number_format($sum['gt'],2); ?></h3><p>Total Expenses</p></div></div>
            <div class="stat-card blue"><div class="stat-icon"><i class="fas fa-list"></i></div><div class="stat-info"><h3><?php echo $sum['c']; ?></h3><p>Entries</p></div></div>
        </div>
        <h3 class="section-title">By Category</h3>
        <table class="table"><thead><tr><th>Category</th><th>Total (KES)</th></tr></thead>
        <tbody><?php while($c=$bycat->fetch_assoc()): ?><tr><td><?php echo htmlspecialchars($c['category']?:'Uncategorized'); ?></td><td>KES <?php echo number_format($c['total'],2); ?></td></tr><?php endwhile; ?></tbody></table>
        <h3 class="section-title">All Entries</h3>
        <table class="table">
            <thead><tr><th>#</th><th>Title</th><th>Category</th><th>Amount</th><th>Payment</th><th>Date</th></tr></thead>
            <tbody>
            <?php $i=1; while($e=$expenses->fetch_assoc()): ?>
            <tr><td><?php echo $i++; ?></td><td><?php echo htmlspecialchars($e['expense_title']); ?></td><td><?php echo htmlspecialchars($e['category']); ?></td><td>KES <?php echo number_format($e['amount'],2); ?></td><td><span class="badge <?php echo $e['payment_method']==='mpesa'?'badge-green':'badge-blue'; ?>"><?php echo strtoupper($e['payment_method']); ?></span></td><td><?php echo $e['expense_date']; ?></td></tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
</div>
<?php include '../../includes/footer.php'; ?>
