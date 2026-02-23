<?php
require_once "include/session.php";
$requiredRoles = ['pembimbing'];
require_once "include/auth.php";
require_once "koneksi.php";

/* =========================
   PAGINATION / LIMIT
========================= */
$limit = $_GET['limit'] ?? 10;
$limit = (int)$limit;

/* =========================
   SEARCH
========================= */
$search = $_GET['search'] ?? '';
$where = '';

if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $where = "WHERE nama_pembimbing LIKE '%$safe%'
              OR nip LIKE '%$safe%'
              OR jabatan LIKE '%$safe%'
              OR divisi LIKE '%$safe%'";
}

/* =========================
   QUERY DATA
========================= */
$data = $conn->query("
    SELECT *
    FROM tb_pembimbing
    $where
    ORDER BY id_pembimbing DESC
    LIMIT $limit
");
?>

<!DOCTYPE html>
<html>

<head>
    <?php include "include/head.php"; ?>
    <title>Data Pembimbing</title>
</head>

<body class="bg-gray-100">

    <?php include "include/navbar.php"; ?>

    <div class="flex min-h-screen">
        <?php include "include/sidebar.php"; ?>

        <main class="flex-1 p-8">

            <h2 class="text-3xl font-bold text-red-600 text-center mb-10">
                DATA PEMBIMBING
            </h2>

            <!-- TOP FILTER -->
            <div class="flex justify-between items-center mb-6">

                <form method="GET" class="flex items-center gap-3">
                    <label>Tampilkan:</label>
                    <select name="limit" onchange="this.form.submit()"
                        class="border rounded px-2 py-1">
                        <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
                        <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                    </select>
                </form>

                <form method="GET">
                    <input type="hidden" name="limit" value="<?= $limit ?>">
                    <input type="text" name="search"
                        value="<?= htmlspecialchars($search) ?>"
                        placeholder="Cari..."
                        class="border rounded px-3 py-2 w-64">
                </form>

            </div>

            <!-- TABLE -->
            <div class="bg-white rounded shadow p-6">

                <table class="w-full border text-center">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">No</th>
                            <th>Nama Pembimbing</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>Divisi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($data->num_rows == 0): ?>
                            <tr>
                                <td colspan="5" class="py-6 text-gray-500">
                                    Tidak ada data pembimbing
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php $no = 1;
                        while ($row = $data->fetch_assoc()): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3"><?= $no++ ?></td>
                                <td><?= $row['nama_pembimbing'] ?></td>
                                <td><?= $row['nip'] ?></td>
                                <td><?= $row['jabatan'] ?></td>
                                <td><?= $row['divisi'] ?></td>
                            </tr>
                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </main>
    </div>

</body>

</html>