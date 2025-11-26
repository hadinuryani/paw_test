<?php
require_once 'config/config.php';
session_start();
// hapus session
session_unset();
session_destroy();

header('Location: ' . BASE_URL . 'login.php');
exit;
?>
