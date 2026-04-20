<?php
require_once "include/session.php";
$requiredRoles = ['admin', 'pembimbing'];
require_once "include/auth.php";
require_once "koneksi.php";

$search = $_GET['search'] ?? '';
$where = '';
$filterPembimbing = '';

if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $where = "AND m.nama_mahasiswa LIKE '%$safe%'";
}

if ($_SESSION['role'] == 'pembimbing') {

    $id_user = $_SESSION['user_id'];

    $pembimbing = $conn->query("
        SELECT id_pembimbing 
        FROM tb_pembimbing
        WHERE id_user = $id_user
    ")->fetch_assoc();

    if ($pembimbing) {
        $id_pembimbing = $pembimbing['id_pembimbing'];
        $filterPembimbing = "AND m.id_pembimbing = $id_pembimbing";
    }
}

$data = $conn->query("
    SELECT m.*, p.nama_pembimbing,
        (SELECT COUNT(*) FROM tb_penilaian n 
         WHERE n.id_mahasiswa = m.id_mahasiswa) as sudah_dinilai
    FROM tb_mahasiswa m
    LEFT JOIN tb_pembimbing p 
        ON m.id_pembimbing = p.id_pembimbing
    WHERE 1=1
    $filterPembimbing
    $where
    ORDER BY m.id_mahasiswa DESC
");
?>

<!DOCTYPE html>
<html>

<head>
    <?php include "include/head.php"; ?>
    <title>Data Mahasiswa</title>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">

    <?php include "include/navbar.php"; ?>

    <div class="flex min-h-screen">
        <?php include "include/sidebar.php"; ?>

        <main class="flex-1 p-8">

            <h2 class="text-2xl font-bold text-red-600 mb-6">
                DATA MAHASISWA
            </h2>

            <!-- SEARCH -->
            <div class="flex justify-end mb-6">
                <form method="GET">
                    <input type="text" name="search"
                        value="<?= htmlspecialchars($search) ?>"
                        placeholder="Cari..."
                        class="border rounded px-3 py-2 w-64">
                </form>
            </div>

            <!-- TABLE -->
            <div class="bg-white rounded shadow p-6">

                <table class="w-full border-collapse text-sm text-center">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left w-12">No</th>
                            <th class="text-left w-48">Nama</th>
                            <th class="text-left w-48">Asal Kampus</th>
                            <th class="text-left w-48">Pembimbing</th>
                            <th class="text-left w-40">Deadline</th>
                            <th class="text-center w-40">Status Penilaian</th>
                            <th class="text-center w-32">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($data->num_rows === 0): ?>
                            <tr>
                                <td colspan="6" class="py-6 text-gray-500">
                                    Tidak ada data mahasiswa
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php 
                        $no = 1;
                        $today = date('Y-m-d');
                        while ($row = $data->fetch_assoc()): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <!-- NO -->
                                <td class="p-3 text-left"><?= $no++ ?></td>
                                <!-- NAMA -->
                                <td class="text-left"><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                                <!-- ASAL -->
                                <td class="text-left"><?= htmlspecialchars($row['asal_kampus']) ?></td>
                                <!-- PEMBIMBING -->
                                <td class="text-left"><?= htmlspecialchars($row['nama_pembimbing']) ?></td>
                                <!-- DEADLINE -->
                                <td class="text-left whitespace-nowrap">
                                    <?= $row['deadline'] ?? '-' ?>
                                    <?php
                                    if (!empty($row['deadline'])) {
                                        // 🔴 TERLAMBAT (BELUM DINILAI)
                                        if ($row['deadline'] < $today && $row['sudah_dinilai'] == 0) {
                                            echo "<span class='text-red-600 ml-2 font-semibold'>Terlambat</span>";
                                        }
                                        // 🟡 HARI INI (BELUM DINILAI)
                                        elseif ($row['deadline'] == $today && $row['sudah_dinilai'] == 0) {
                                            echo "<span class='text-yellow-500 ml-2 font-semibold'>Hari ini</span>";
                                        }
                                        // 🟢 SUDAH DINILAI (AMAN)
                                        elseif ($row['sudah_dinilai'] > 0) {
                                            echo "<span class='text-green-600 ml-2 font-semibold'>Selesai</span>";
                                        }
                                    }
                                    ?>
                                </td>
                    
                                <!-- STATUS -->
                                <td class="space-x-3">

                                    <!-- Icon View -->
                                    <i onclick="openPenilaian(<?= $row['id_mahasiswa'] ?>)"
                                        class="fa-solid fa-eye text-yellow-500 cursor-pointer"></i>

                                    <?php if ($row['sudah_dinilai'] > 0): ?>
                                        <!-- Sudah dinilai -->
                                        <i class="fa-solid fa-circle-check text-green-600"></i>
                                    <?php else: ?>
                                        <!-- Belum dinilai -->
                                        <i class="fa-solid fa-clock text-gray-500"></i>
                                    <?php endif; ?>

                                </td>

                                <!-- AKSI -->
                                <td>
                                    <i onclick="openDetail(<?= $row['id_mahasiswa'] ?>)"
                                        class="fa-solid fa-circle-info text-gray-700 cursor-pointer"></i>
                                </td>

                            </tr>
                        <?php endwhile; ?>

                    </tbody>
                </table>

            </div>

        </main>
    </div>

    <!-- ================= MODAL ================= -->
    <div id="modal"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-2xl rounded p-6 relative">

            <h2 id="modalTitle"
                class="text-xl font-bold mb-4 text-red-600"></h2>

            <div id="modalContent"></div>

            <div class="text-right mt-6">
                <button onclick="closeModal()"
                    class="bg-gray-500 text-white px-5 py-2 rounded">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <script>
        function openPenilaian(id) {

            fetch('ajax_penilaian.php?id=' + id)
                .then(res => res.text())
                .then(html => {

                    document.getElementById('modalTitle').innerText = "FORM PENILAIAN";
                    document.getElementById('modalContent').innerHTML = html;
                    document.getElementById('modal').classList.remove('hidden');

                });
        }

        function openDetail(id) {

            fetch('ajax_detail_mahasiswa.php?id=' + id)
                .then(res => res.text())
                .then(html => {

                    document.getElementById('modalTitle').innerText = "DETAIL MAHASISWA";
                    document.getElementById('modalContent').innerHTML = html;
                    document.getElementById('modal').classList.remove('hidden');

                });
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>

</body>

</html>