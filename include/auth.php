<?php
// Pastikan session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| DEFAULT ROLE YANG BOLEH AKSES
|--------------------------------------------------------------------------
| Jika halaman tidak mendefinisikan $requiredRoles,
| maka hanya ADMIN yang boleh masuk
*/
$allowedRoles = ['admin'];

/*
|--------------------------------------------------------------------------
| OVERRIDE ROLE (OPSIONAL)
|--------------------------------------------------------------------------
| Di halaman lain, kamu bisa set:
| $requiredRoles = ['admin', 'manager'];
*/
if (isset($requiredRoles) && is_array($requiredRoles)) {
    $allowedRoles = $requiredRoles;
}

/*
|--------------------------------------------------------------------------
| VALIDASI LOGIN & ROLE
|--------------------------------------------------------------------------
*/
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    !in_array($_SESSION['role'], $allowedRoles)
) {
    http_response_code(403);
    echo "Akses ditolak";
    exit;
}