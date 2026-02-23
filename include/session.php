<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($requiredRoles)) {
    if (!in_array($_SESSION['role'], $requiredRoles)) {
        echo "Akses ditolak";
        exit;
    }
}