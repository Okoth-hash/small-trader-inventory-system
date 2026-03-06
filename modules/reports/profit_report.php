<?php
session_start();
define('BASE_URL', '/small-trader-inventory-system/');
$pageTitle = 'Profit & Loss';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$uid=$_SESSION['user_id'];
$from=$_GET['from']??date('Y-m-01'); $to=$_GET['to']??date('Y-m-d');
$revenue=$conn->query("SELECT COALESCE(SUM(total_amount),0) as t FROM sales WHERE user_id=$uid AND sale_date BETWEEN '$from' AND '$to'")->fetch_assoc()['t'];
$cogs=$conn->query("SELECT COALESCE(SUM(s.quantity_sold*p.buying_price),0) as t FROM sales s JOIN products p ON s.product_id=p.id WHERE s.user_id=$uid AND s.sale_date BETWEEN '$from' AND '$to'")->fetch_assoc()['t'];
$expenses=$conn->query("SELECT COALESCE(SUM(amount),0) as t FROM expenses WHERE user_id=$uid AND expense_date BETWEEN '$from' AND '$to'")->fetch_assoc()['t'];
$gross=$revenue-$cogs; $net=$gross-$expenses;
$margin=$revenue>0?($net/$revenue)*100:0;
?>
<div class="wrapper">
<?php include '../../includes/sidebar.php'; ?>
<main class="main-content">
    <div class="topbar"><h1><i class="fas fa-chart-line"></i> Profit & Loss Report</h1></div>
    <?php include '../../includes/alerts.php'; ?>
    <div class="card">
        <form method="GET" class="filter-form"><div class="form-row">
            <div class="form-group"><label>From</label><input type="date" name="from" value="<?php echo $from; ?>"></div>
            <div class="form-group"><label>To</label><input type="date" name="to" value="<?php echo $to; ?>"></div>
            <div class="form-group align-end"><button type="submit" class="btn btn-primary">Generate</button></div>
        </div></form>
        <div class="pnl-statement">
            <h3 style="margin-bottom:16px;">Statement: <?php echo date('d M Y',strtotime($from)); ?> to <?php echo date('d M Y',strtotime($to)); ?></h3>
            <table class="table pnl-table">
                <tr class="section-header"><td colspan="2"><strong>REVENUE</strong></td></tr>
                <tr><td>Total Sales Revenue</td><td class="text-right">KES <?php echo number_format($revenue,2); ?></td></tr>
                <tr class="section-header"><td colspan="2"><strong>COST OF GOODS SOLD</strong></td></tr>
                <tr><td>Cost of Products Sold</td><td class="text-right text-red">KES <?php echo number_format($cogs,2); ?></td></tr>
                <tr class="subtotal"><td><strong>GROSS PROFIT</strong></td><td class="text-right"><strong>KES <?php echo number_format($gross,2); ?></strong></td></tr>
                <tr class="section-header"><td colspan="2"><strong>OPERATING EXPENSES</strong></td></tr>
                <tr><td>Total Operating Expenses</td><td class="text-right text-red">KES <?php echo number_format($expenses,2); ?></td></tr>
                <tr class="total <?php echo $net>=0?'profit':'loss'; ?>">
                    <td><strong>NET <?php echo $net>=0?'PROFIT':'LOSS'; ?></strong></td>
                    <td class="text-right"><strong>KES <?php echo number_format(abs($net),2); ?></strong></td>
                </tr>
                <tr><td>Profit Margin</td><td class="text-right"><?php echo number_format($margin,1); ?>%</td></tr>
            </table>
        </div>
    </div>
</main>
</div>
<?php include '../../includes/footer.php'; ?>
