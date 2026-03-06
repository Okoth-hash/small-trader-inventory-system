<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo"><i class="fas fa-store"></i><span>Small Trader</span></div>
        <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    </div>
    <ul class="sidebar-menu">
        <li><a href="<?php echo BASE_URL; ?>dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <li class="menu-header"><span>Inventory</span></li>
        <li><a href="<?php echo BASE_URL; ?>modules/inventory/products.php"><i class="fas fa-boxes"></i><span>Products</span></a></li>
        <li><a href="<?php echo BASE_URL; ?>modules/inventory/add_product.php"><i class="fas fa-plus-circle"></i><span>Add Product</span></a></li>
        <li class="menu-header"><span>Sales</span></li>
        <li><a href="<?php echo BASE_URL; ?>modules/sales/record_sale.php"><i class="fas fa-cash-register"></i><span>Record Sale</span></a></li>
        <li><a href="<?php echo BASE_URL; ?>modules/sales/sales_history.php"><i class="fas fa-history"></i><span>Sales History</span></a></li>
        <li class="menu-header"><span>Expenses</span></li>
        <li><a href="<?php echo BASE_URL; ?>modules/expenses/add_expense.php"><i class="fas fa-receipt"></i><span>Add Expense</span></a></li>
        <li><a href="<?php echo BASE_URL; ?>modules/expenses/expenses_list.php"><i class="fas fa-list"></i><span>All Expenses</span></a></li>
        <li class="menu-header"><span>Reports</span></li>
        <li><a href="<?php echo BASE_URL; ?>modules/reports/sales_report.php"><i class="fas fa-chart-bar"></i><span>Sales Report</span></a></li>
        <li><a href="<?php echo BASE_URL; ?>modules/reports/expense_report.php"><i class="fas fa-chart-pie"></i><span>Expense Report</span></a></li>
        <li><a href="<?php echo BASE_URL; ?>modules/reports/profit_report.php"><i class="fas fa-chart-line"></i><span>Profit & Loss</span></a></li>
        <li class="menu-header"><span>Account</span></li>
        <li><a href="<?php echo BASE_URL; ?>auth/logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
    </ul>
</nav>
