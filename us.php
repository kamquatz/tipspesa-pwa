<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipspesa_tkn'])) {
    $_SESSION['tipspesa_tkn'] = $_POST['tipspesa_tkn'];
    echo 'ud';
}

?>