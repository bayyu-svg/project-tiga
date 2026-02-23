<?php
date_default_timezone_set('Asia/Makassar');
session_start();
require_once "koneksi.php";

/*
|--------------------------------------------------------------------------
| AUTH FUNCTION (MD5 VERSION)
|--------------------------------------------------------------------------
*/
function authenticateUser($username, $password, $conn)
{
    $query = "
        SELECT 
            u.id_user,
            u.username,
            u.password,
            r.nama_role
        FROM tb_user u
        JOIN tb_role r ON u.id_role = r.id_role
        WHERE u.username = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // 🔐 Cek Password MD5
        if (md5($password) === $user['password']) {
            return $user;
        }
    }

    return false;
}

/*
|--------------------------------------------------------------------------
| HANDLE LOGIN
|--------------------------------------------------------------------------
*/
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user = authenticateUser($username, $password, $conn);

    if ($user) {

        // SET SESSION
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['nama_role'];

        // REDIRECT BERDASARKAN ROLE
        if ($user['nama_role'] === 'admin') {
            header("Location: dashboard.php");
        } elseif ($user['nama_role'] === 'pembimbing') {
            header("Location: dashboard_pembimbing.php");
        } else {
            header("Location: login.php");
        }
        exit;
    } else {
        $error_message = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Sistem Penilaian Magang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex">

    <!-- LEFT LOGO -->
    <div class="w-1/2 bg-gray-100 hidden md:flex items-center justify-center">
        <img src="assets/img/foto/telkom_logo.png" class="w-80">
    </div>

    <!-- RIGHT LOGIN -->
    <div class="w-full md:w-1/2 bg-red-600 flex items-center justify-center">
        <div class="w-full max-w-md text-center text-white">

            <h1 class="text-3xl font-bold mb-10">
                Sistem Informasi Penilaian Kinerja Mahasiswa Magang
            </h1>

            <?php if (!empty($error_message)): ?>
                <div class="mb-4 p-3 bg-red-200 text-red-800 rounded">
                    <?= $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">

                <input type="text" name="username"
                    class="w-full px-4 py-3 rounded-lg text-black"
                    placeholder="Username" required>

                <input type="password" name="password"
                    class="w-full px-4 py-3 rounded-lg text-black"
                    placeholder="Password" required>

                <button type="submit"
                    class="px-8 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Login
                </button>

            </form>

        </div>
    </div>

</body>

</html>