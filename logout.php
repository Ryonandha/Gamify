<?php 
session_start();
    if (isset($_SESSION['email'])) {
    session_destroy();
}
    $ref = @$_GET['q'] ?: 'index.php';
    header("Location: $ref");
exit();
?>