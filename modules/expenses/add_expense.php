<?php
session_start();
define('BASE_URL', '/');
$pageTitle = 'Add Expense';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$uid=$_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title=trim($_POST['expense_title']); $category=trim($_POST['category']);
    $amount=floatval($_POST['amount']); $pay=$_POST['payment_method'];
    $mpesa=trim($_POST['mpesa_code']??''); $edate=$_POST['expense_date']; $notes=trim($_POST['notes']??'');
    $stmt=$conn->prepare("INSERT INTO expenses (user_id,expense_title,category,amount,payment_method,mpesa_code,expense_date,notes) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("issdssss",$uid,$title,$category,$amount,$pay,$mpesa,$edate,$notes);
    if ($stmt->execute()) { $_SESSION['success']='Expense recorded!'; header('Location: expenses_list.php'); exit(); }
    else { $_SESSION['error']='Failed to save expense.'; }
}
?>
<div class="wrapper">
<?php include '../../includes/sidebar.php'; ?>
<main class="main-content">
    <div class="topbar"><h1><i class="fas fa-receipt"></i> Add Expense</h1><a href="expenses_list.php" class="btn btn-outline"><i class="fas fa-list"></i> All Expenses</a></div>
    <?php include '../../includes/alerts.php'; ?>
    <div class="card form-card">
        <form method="POST">
            <div class="form-row">
                <div class="form-group"><label>Expense Title *</label><input type="text" name="expense_title" required placeholder="e.g. Shop Rent"></div>
                <div class="form-group"><label>Category</label>
                    <select name="category"><option value="">-- Select --</option><option>Rent</option><option>Utilities</option><option>Transport</option><option>Salaries</option><option>Stock Purchase</option><option>Marketing</option><option>Equipment</option><option>Other</option></select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Amount (KES) *</label><input type="number" step="0.01" name="amount" required placeholder="0.00"></div>
                <div class="form-group"><label>Date *</label><input type="date" name="expense_date" required value="<?php echo date('Y-m-d'); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Payment</label>
                    <select name="payment_method" onchange="document.getElementById('mf').style.display=this.value==='mpesa'?'block':'none'">
                        <option value="cash">Cash</option><option value="mpesa">MPesa</option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="mf" style="display:none;"><label>MPesa Code</label><input type="text" name="mpesa_code" placeholder="Transaction code"></div>
            <div class="form-group"><label>Notes</label><textarea name="notes" rows="2"></textarea></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Expense</button>
        </form>
    </div>
</main>
</div>
<?php include '../../includes/footer.php'; ?>
