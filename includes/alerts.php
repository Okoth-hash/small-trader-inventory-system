<?php
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> ' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
if (isset($_SESSION['warning'])) {
    echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> ' . htmlspecialchars($_SESSION['warning']) . '</div>';
    unset($_SESSION['warning']);
}
?>
