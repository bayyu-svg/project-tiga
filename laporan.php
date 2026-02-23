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
| PAGINATION & SEARCH
|--------------------------------------------------------------------------
*/
$limit  = 10;
$page   = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$whereSearch = '';

if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $whereSearch = "AND n.nomor_nota LIKE '%$safe%'";
}

/*
|--------------------------------------------------------------------------
| DATA NOTA PENDING
|--------------------------------------------------------------------------
*/
$data = $conn->query("
    SELECT 
        n.id,
        n.nomor_nota,
        n.tanggal_nota,
        n.total_nominal,
        n.created_at,
        u.name AS admin_name
    FROM nota n
    JOIN users u ON n.admin_id = u.id
    WHERE n.status = 'pending'
    $whereSearch
    ORDER BY n.created_at DESC
    LIMIT $limit OFFSET $offset
");

/*
|--------------------------------------------------------------------------
| TOTAL DATA
|--------------------------------------------------------------------------
*/
$totalData = $conn->query("
    SELECT COUNT(*) total
    FROM nota n
    WHERE n.status = 'pending'
    $whereSearch
")->fetch_assoc()['total'];

$totalPage = max(ceil($totalData / $limit), 1);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <?php include "include/head.php"; ?>
    <title>Laporan Verifikasi Nota</title>
</head>

<body class="bg-gray-100">

    <?php include "include/navbar.php"; ?>

    <div class="flex min-h-screen">

        <?php include "include/sidebar.php"; ?>

        <main class="flex-1 p-8">

            <!-- TITLE -->
            <h1 class="text-2xl font-bold mb-6">
                Laporan <span class="text-gray-500 text-sm">Verifikasi Nota</span>
            </h1>

            <!-- TOP BAR -->
            <div class="flex justify-between items-center mb-6">

                <div class="text-sm text-gray-600">
                    Total nota pending:
                    <span class="font-semibold"><?= $totalData ?></span>
                </div>

                <!-- SEARCH -->
                <form method="GET">
                    <input type="text"
                        name="search"
                        value="<?= htmlspecialchars($search) ?>"
                        placeholder="Cari nomor nota..."
                        class="border rounded px-3 py-2 w-64">
                </form>

            </div>

            <!-- TABLE -->
            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">No</th>
                            <th>Tanggal Nota</th>
                            <th>Nomor Nota</th>
                            <th>Admin</th>
                            <th>Total</th>
                            <th>Waktu Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if ($data->num_rows === 0): ?>
                            <tr>
                                <td colspan="7"
                                    class="text-center py-6 text-gray-500">
                                    Tidak ada nota pending
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php $no = $offset + 1; ?>
                        <?php while ($row = $data->fetch_assoc()): ?>
                            <tr class="border-t">
                                <td class="p-3"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['tanggal_nota']) ?></td>
                                <td class="font-semibold">
                                    <?= htmlspecialchars($row['nomor_nota']) ?>
                                </td>
                                <td><?= htmlspecialchars($row['admin_name']) ?></td>
                                <td>
                                    Rp. <?= number_format($row['total_nominal'], 0, ',', '.') ?>
                                </td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                                <td>
                                    <a href="detail_nota.php?id=<?= $row['id'] ?>"
                                        class="bg-blue-600 hover:bg-blue-700
                                      text-white px-3 py-1 rounded text-xs">
                                        Verifikasi
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="flex justify-end mt-6 gap-2">

                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>"
                        class="px-3 py-1 border rounded">
                        Previous
                    </a>
                <?php endif; ?>

                <span class="px-3 py-1 bg-gray-200 rounded">
                    <?= $page ?> / <?= $totalPage ?>
                </span>

                <?php if ($page < $totalPage): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>"
                        class="px-3 py-1 border rounded">
                        Next
                    </a>
                <?php endif; ?>

            </div>

        </main>
    </div>

</body>

</html>