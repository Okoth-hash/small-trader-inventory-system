<?php
session_start();
define('BASE_URL', '/');
$pageTitle = 'All Expenses';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$uid=$_SESSION['user_id'];
$from=$_GET['from']??date('Y-m-01'); $to=$_GET['to']??date('Y-m-d');
$expenses=$conn->query("SELECT * FROM expenses WHERE user_id=$uid AND expense_date BETWEEN '$from' AND '$to' ORDER BY expense_date DESC");
$tot=$conn->query("SELECT COALESCE(SUM(amount),0) as gt,COUNT(*) as c FROM expenses WHERE user_id=$uid AND expense_date BETWEEN '$from' AND '$to'")->fetch_assoc();
?>
<div class="wrapper">
<?php include '../../includes/sidebar.php'; ?>
<main class="main-content">
    <div class="topbar"><h1><i class="fas fa-list"></i> All Expenses</h1><a href="add_expense.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Expense</a></div>
    <?php include '../../includes/alerts.php'; ?>
    <div class="card">
        <form method="GET" class="filter-form"><div class="form-row">
            <div class="form-group"><label>From</label><input type="date" name="from" value="<?php echo $from; ?>"></div>
            <div class="form-group"><label>To</label><input type="date" name="to" value="<?php echo $to; ?>"></div>
            <div class="form-group align-end"><button type="submit" class="btn btn-primary">Filter</button></div>
        </div></form>
        <div class="summary-bar"><span>Total: <strong><?php echo $tot['c']; ?></strong></span><span>Amount: <strong>KES <?php echo number_format($tot['gt'],2); ?></strong></span></div>
        <table class="table">
            <thead><tr><th>#</th><th>Title</th><th>Category</th><th>Amount</th><th>Payment</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
            <?php $i=1; while($e=$expenses->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($e['expense_title']); ?></td>
                <td><?php echo htmlspecialchars($e['category']); ?></td>
                <td>KES <?php echo number_format($e['amount'],2); ?></td>
                <td><span class="badge <?php echo $e['payment_method']==='mpesa'?'badge-green':'badge-blue'; ?>"><?php echo strtoupper($e['payment_method']); ?></span></td>
                <td><?php echo $e['expense_date']; ?></td>
                <td><a href="delete_expense.php?id=<?php echo $e['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
</div>
<?php include '../../includes/footer.php'; ?>
