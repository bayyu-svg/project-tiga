<?php
require_once "include/session.php";
require_once "koneksi.php";

/*
|--------------------------------------------------------------------------
| ROLE PROTECTION (MANAGER ONLY)
|--------------------------------------------------------------------------
*/
$requiredRoles = ['manager'];
require_once "include/auth.php";

/*
|--------------------------------------------------------------------------
| HANDLE SUBMIT
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = md5($_POST['password']); // sesuai sistemmu

    // Ambil role admin
    $role = $conn->query("
        SELECT id FROM roles WHERE role_name = 'admin' LIMIT 1
    ")->fetch_assoc();

    $role_id = $role['id'];

    $stmt = $conn->prepare("
        INSERT INTO users (name, email, password, role_id, is_active, created_at)
        VALUES (?, ?, ?, ?, 1, NOW())
    ");
    $stmt->bind_param("sssi", $name, $email, $password, $role_id);

    if ($stmt->execute()) {
        header("Location: kelola_admin.php?success=1");
        exit;
    }

    $error = "Gagal menambahkan admin";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <?php include "include/head.php"; ?>
    <title>Tambah Admin</title>
</head>

<body class="bg-gray-100">

    <?php include "include/navbar.php"; ?>

    <div class="flex min-h-screen">
        <?php include "include/sidebar.php"; ?>

        <main class="flex-1 p-8 max-w-xl">

            <h1 class="text-2xl font-bold mb-6">Tambah Admin</h1>

            <?php if (!empty($error)): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="bg-white p-6 rounded shadow space-y-4">

                <div>
                    <label class="block mb-1">Nama</label>
                    <input type="text" name="name"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block mb-1">Email</label>
                    <input type="email" name="email"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="kelola_admin.php"
                        class="px-4 py-2 border rounded">
                        Batal
                    </a>

                    <button type="submit"
                        class="bg-[#00529B] text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                </div>

            </form>

        </main>
    </div>

</body>

</html>