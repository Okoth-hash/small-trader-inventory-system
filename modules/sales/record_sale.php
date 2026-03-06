<?php
session_start();
define('BASE_URL', '/');
$pageTitle = 'Record Sale';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$uid      = $_SESSION['user_id'];
$products = $conn->query("SELECT * FROM products WHERE user_id=$uid AND quantity>0 ORDER BY product_name");
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $pid=$_POST['product_id']; $qty=intval($_POST['quantity_sold']);
    $price=floatval($_POST['unit_price']); $total=$qty*$price;
    $pay=$_POST['payment_method']; $mpesa=trim($_POST['mpesa_code']??'');
    $cname=trim($_POST['customer_name']??''); $cphone=trim($_POST['customer_phone']??'');
    $sdate=$_POST['sale_date']; $notes=trim($_POST['notes']??'');
    $stock=$conn->query("SELECT quantity FROM products WHERE id=$pid AND user_id=$uid")->fetch_assoc();
    if (!$stock||$stock['quantity']<$qty) {
        $_SESSION['error']='Not enough stock.';
    } else {
        $stmt=$conn->prepare("INSERT INTO sales (user_id,product_id,quantity_sold,unit_price,total_amount,payment_method,mpesa_code,customer_name,customer_phone,sale_date,notes) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("iiiddssssss",$uid,$pid,$qty,$price,$total,$pay,$mpesa,$cname,$cphone,$sdate,$notes);
        if ($stmt->execute()) {
            $conn->query("UPDATE products SET quantity=quantity-$qty WHERE id=$pid");
            $_SESSION['success']='Sale recorded! Total: KES '.number_format($total,2);
            header('Location: record_sale.php'); exit();
        }
    }
}
?>
<div class="wrapper">
<?php include '../../includes/sidebar.php'; ?>
<main class="main-content">
    <div class="topbar"><h1><i class="fas fa-cash-register"></i> Record Sale</h1><a href="sales_history.php" class="btn btn-outline"><i class="fas fa-history"></i> History</a></div>
    <?php include '../../includes/alerts.php'; ?>
    <div class="card form-card">
        <form method="POST">
            <div class="form-row">
                <div class="form-group"><label>Product *</label>
                    <select name="product_id" id="productSelect" required onchange="fillPrice()">
                        <option value="">-- Choose Product --</option>
                        <?php while($p=$products->fetch_assoc()): ?>
                        <option value="<?php echo $p['id']; ?>" data-price="<?php echo $p['selling_price']; ?>">
                            <?php echo htmlspecialchars($p['product_name']); ?> (Stock: <?php echo $p['quantity']; ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group"><label>Sale Date *</label><input type="date" name="sale_date" required value="<?php echo date('Y-m-d'); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Quantity *</label><input type="number" name="quantity_sold" id="qtySold" min="1" required oninput="calcTotal()"></div>
                <div class="form-group"><label>Unit Price (KES) *</label><input type="number" step="0.01" name="unit_price" id="unitPrice" required oninput="calcTotal()"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Total</label><input type="text" id="totalDisplay" readonly class="readonly-field" placeholder="0.00"></div>
                <div class="form-group"><label>Payment *</label>
                    <select name="payment_method" id="paymentMethod" onchange="toggleMpesa()">
                        <option value="cash">Cash</option><option value="mpesa">MPesa</option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="mpesaField" style="display:none;"><label>MPesa Code</label><input type="text" name="mpesa_code" placeholder="e.g. QGH7YK9L2M"></div>
            <div class="form-row">
                <div class="form-group"><label>Customer Name</label><input type="text" name="customer_name" placeholder="Optional"></div>
                <div class="form-group"><label>Customer Phone</label><input type="text" name="customer_phone" placeholder="Optional"></div>
            </div>
            <div class="form-group"><label>Notes</label><textarea name="notes" rows="2"></textarea></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Record Sale</button>
        </form>
    </div>
</main>
</div>
<script>
function fillPrice(){var s=document.getElementById('productSelect'),o=s.options[s.selectedIndex];document.getElementById('unitPrice').value=o.dataset.price||'';calcTotal();}
function calcTotal(){var q=parseFloat(document.getElementById('qtySold').value)||0,p=parseFloat(document.getElementById('unitPrice').value)||0;document.getElementById('totalDisplay').value='KES '+(q*p).toFixed(2);}
function toggleMpesa(){document.getElementById('mpesaField').style.display=document.getElementById('paymentMethod').value==='mpesa'?'block':'none';}
</script>
<?php include '../../includes/footer.php'; ?>
