// ============================================================
// Small Trader Inventory System - Main JavaScript
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

    // --- Sidebar Toggle (mobile) ---
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar   = document.getElementById('sidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });
    }

    // --- Auto-dismiss alerts after 5 seconds ---
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(function () { alert.remove(); }, 500);
        }, 5000);
    });

    // --- Confirm before delete actions ---
    const deleteBtns = document.querySelectorAll('[data-confirm]');
    deleteBtns.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (!confirm(btn.dataset.confirm || 'Are you sure?')) {
                e.preventDefault();
            }
        });
    });

    // --- Highlight active sidebar link ---
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    sidebarLinks.forEach(function (link) {
        if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });

    // --- Input number formatting (prevent negatives) ---
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            if (parseFloat(this.value) < 0) this.value = 0;
        });
    });

    console.log('Small Trader Inventory System loaded successfully.');
});
