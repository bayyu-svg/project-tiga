<?php
require_once "include/session.php";
$requiredRoles = ['admin'];
require_once "include/auth.php";
require_once "koneksi.php";

/* =========================
   PAGINATION
========================= */
$limit  = 10;
$page   = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;

/* =========================
   SEARCH
========================= */
$search = $_GET['search'] ?? '';
$where  = '';

if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $where = "AND m.nama_mahasiswa LIKE '%$safe%'";
}

// Jumlah Pembimbing
$totalPembimbing = $conn->query("
    SELECT COUNT(*) as total FROM tb_pembimbing
")->fetch_assoc()['total'];

// Jumlah Mahasiswa
$totalMahasiswa = $conn->query("
    SELECT COUNT(*) as total FROM tb_mahasiswa
")->fetch_assoc()['total'];

/* =========================
   DATA PEMBIMBING UNTUK DROPDOWN
========================= */
$pembimbingList = $conn->query("SELECT id_pembimbing, nama_pembimbing FROM tb_pembimbing");

$pembimbingArray = [];
while ($p = $pembimbingList->fetch_assoc()) {
    $pembimbingArray[] = $p;
}

/* =========================
   DATA MAHASISWA
========================= */
$data = $conn->query("
    SELECT m.*, p.nama_pembimbing
    FROM tb_mahasiswa m
    LEFT JOIN tb_pembimbing p
        ON m.id_pembimbing = p.id_pembimbing
    WHERE 1=1
    $where
    ORDER BY m.id_mahasiswa DESC
    LIMIT $limit OFFSET $offset
");

$totalData = $conn->query("
    SELECT COUNT(*) total
    FROM tb_mahasiswa m
    WHERE 1=1
    $where
")->fetch_assoc()['total'];

$totalPage = max(ceil($totalData / $limit), 1);
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

            <!-- CARD STATISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

                <!-- CARD PEMBIMBING -->
                <div class="bg-white p-8 rounded shadow text-center">

                    <!-- ICON -->
                    <div class="text-yellow-500 text-5xl mb-4">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>

                    <!-- ANGKA -->
                    <div class="text-5xl font-bold text-black">
                        <?= $totalPembimbing ?>
                    </div>

                    <!-- LABEL -->
                    <p class="text-gray-400 mt-2 text-lg">
                        Jumlah Pembimbing
                    </p>

                </div>

                <!-- CARD MAHASISWA -->
                <div class="bg-white p-8 rounded shadow text-center">

                    <!-- ICON -->
                    <div class="text-cyan-400 text-5xl mb-4">
                        <i class="fa-solid fa-users"></i>
                    </div>

                    <!-- ANGKA -->
                    <div class="text-5xl font-bold text-black">
                        <?= $totalMahasiswa ?>
                    </div>

                    <!-- LABEL -->
                    <p class="text-gray-400 mt-2 text-lg">
                        Jumlah Mahasiswa
                    </p>

                </div>

            </div>

            <!-- TOP BAR -->
            <div class="flex justify-between items-center mb-6">

                <div class="flex gap-3">

                    <button onclick="openAddModal()"
                        class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700">
                        Tambah Mahasiswa
                    </button>

                    <form method="GET">
                        <input type="text" name="search"
                            value="<?= htmlspecialchars($search) ?>"
                            placeholder="Cari nama..."
                            class="border rounded px-3 py-2 w-64">
                    </form>

                </div>
            </div>

            <!-- TABLE -->
            <div class="bg-white rounded shadow p-6">

                <table class="w-full border-collapse text-sm">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Asal Kampus</th>
                            <th>Pembimbing</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($data->num_rows === 0): ?>
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500">
                                    Tidak ada data mahasiswa
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php $no = $offset + 1; ?>
                        <?php while ($row = $data->fetch_assoc()): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                                <td><?= htmlspecialchars($row['nim']) ?></td>
                                <td><?= htmlspecialchars($row['asal_kampus']) ?></td>
                                <td><?= htmlspecialchars($row['nama_pembimbing'] ?? '-') ?></td>

                                <td class="text-center space-x-3">

                                    <i onclick="detailMahasiswa(<?= $row['id_mahasiswa'] ?>)"
                                        class="fa-solid fa-eye text-yellow-500 cursor-pointer"></i>

                                    <i onclick="openEditModal(<?= $row['id_mahasiswa'] ?>)"
                                        class="fa-solid fa-pen-to-square text-blue-500 cursor-pointer"></i>

                                    <i onclick="hapusMahasiswa(<?= $row['id_mahasiswa'] ?>)"
                                        class="fa-solid fa-trash text-red-600 cursor-pointer"></i>

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
                        class="px-3 py-1 border rounded">Previous</a>
                <?php endif; ?>

                <span class="px-3 py-1 bg-gray-200 rounded">
                    <?= $page ?> / <?= $totalPage ?>
                </span>

                <?php if ($page < $totalPage): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>"
                        class="px-3 py-1 border rounded">Next</a>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <!-- ================= MODAL ================= -->
    <div id="modal"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-xl rounded p-6">

            <h2 id="modalTitle" class="text-lg font-bold mb-4"></h2>

            <form id="formMahasiswa">
                <input type="hidden" name="id" id="mhs_id">

                <div id="modalContent"></div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 border rounded">Batal</button>

                    <button type="submit"
                        class="px-4 py-2 bg-gray-700 text-white rounded">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
    <script>
        const pembimbingData = <?= json_encode($pembimbingArray); ?>;
    </script>
    <script>
        function openAddModal() {
            document.getElementById('modalTitle').innerText = 'Tambah Mahasiswa';
            document.getElementById('modalContent').innerHTML = formHTML();
            document.getElementById('mhs_id').value = '';
            document.getElementById('modal').classList.remove('hidden');
        }

        function openEditModal(id) {
            fetch('crud_dataMahasiswa.php?action=get&id=' + id)
                .then(res => res.json())
                .then(d => {
                    document.getElementById('modalTitle').innerText = 'Edit Mahasiswa';
                    document.getElementById('modalContent').innerHTML = formHTML(d);
                    document.getElementById('mhs_id').value = d.id_mahasiswa;
                    document.getElementById('modal').classList.remove('hidden');
                });
        }

        function detailMahasiswa(id) {
            fetch('crud_dataMahasiswa.php?action=detail&id=' + id)
                .then(res => res.json())
                .then(d => {
                    document.getElementById('modalTitle').innerText = 'Detail Mahasiswa';
                    document.getElementById('modalContent').innerHTML = detailHTML(d);
                    document.querySelector('#formMahasiswa button[type="submit"]').style.display = 'none';
                    document.getElementById('modal').classList.remove('hidden');
                });
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.querySelector('#formMahasiswa button[type="submit"]').style.display = 'block';
        }

        function hapusMahasiswa(id) {
            if (confirm('Hapus data ini?')) {
                fetch('crud_dataMahasiswa.php?action=delete&id=' + id)
                    .then(() => location.reload());
            }
        }

        document.getElementById('formMahasiswa').addEventListener('submit', function(e) {
            e.preventDefault();
            const action = document.getElementById('mhs_id').value ? 'update' : 'add';
            fetch('crud_dataMahasiswa.php?action=' + action, {
                method: 'POST',
                body: new FormData(this)
            }).then(() => location.reload());
        });

        function formHTML(d = {}) {

    let options = `<option value="">-- Pilih Pembimbing --</option>`;

    pembimbingData.forEach(p => {
        let selected = d.id_pembimbing == p.id_pembimbing ? 'selected' : '';
        options += `<option value="${p.id_pembimbing}" ${selected}>
                    ${p.nama_pembimbing}
                </option>`;
    });

    return `<div class="mb-3">
<label>Nama</label>
<input type="text" name="nama" value="${d.nama_mahasiswa ?? ''}"
class="w-full border rounded p-2">
</div>

<div class="mb-3">
<label>NIM</label>
<input type="text" name="nim" value="${d.nim ?? ''}"
class="w-full border rounded p-2">
</div>

<div class="mb-3">
<label>Asal Kampus</label>
<input type="text" name="asal" value="${d.asal_kampus ?? ''}"
class="w-full border rounded p-2">
</div>

<div class="mb-3">
<label>Divisi</label>
<input type="text" name="divisi" value="${d.divisi ?? ''}"
class="w-full border rounded p-2">
</div>

<div class="mb-3">
<label>Pembimbing</label>
<select name="pembimbing"
class="w-full border rounded p-2">
${options}
</select>
</div>

<div class="mb-3">
<label>Periode Magang</label>
<input type="text" name="periode" value="${d.periode_magang ?? ''}"
class="w-full border rounded p-2">
</div>

<div class="mb-3">
<label>Kontak</label>
<input type="text" name="kontak" value="${d.nomor_hp ?? ''}"
class="w-full border rounded p-2">
</div>`;
}

        function detailHTML(d) {
            return `<p><b>Nama :</b> ${d.nama_mahasiswa}</p>
            <p><b>NIM :</b> ${d.nim}</p>
            <p><b>Asal Kampus :</b> ${d.asal_kampus}</p>
            <p><b>Divisi :</b> ${d.divisi}</p>
            <p><b>Pembimbing :</b> ${d.nama_pembimbing ?? '-'}</p>
            <p><b>Periode :</b> ${d.periode_magang}</p>
            <p><b>Kontak :</b> ${d.nomor_hp}</p>`;
        }
    </script>

</body>

</html>