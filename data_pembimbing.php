<?php
require_once "include/session.php";
$requiredRoles = ['admin'];
require_once "include/auth.php";
require_once "koneksi.php";

/* =========================
   SEARCH
========================= */
$search = $_GET['search'] ?? '';
$where  = '';

if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $where = "WHERE nama_pembimbing LIKE '%$safe%'";
}

/* =========================
   DATA PEMBIMBING
========================= */
$data = $conn->query("
    SELECT * FROM tb_pembimbing
    $where
    ORDER BY id_pembimbing DESC
");
?>

<!DOCTYPE html>
<html>

<head>
    <?php include "include/head.php"; ?>
    <title>Data Pembimbing</title>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">

    <?php include "include/navbar.php"; ?>

    <div class="flex min-h-screen">
        <?php include "include/sidebar.php"; ?>

        <main class="flex-1 p-8">

            <h2 class="text-3xl font-bold text-red-600 text-center mb-10">
                DATA PEMBIMBING
            </h2>

            <!-- TOP BAR -->
            <div class="flex justify-between items-center mb-6">

                <button onclick="openAddModal()"
                    class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700">
                    Tambah Pembimbing
                </button>

                <form method="GET">
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
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($data->num_rows === 0): ?>
                            <tr>
                                <td colspan="6" class="py-6 text-gray-500">
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

                                <td class="space-x-4">

                                    <i onclick="openEditModal(<?= $row['id_pembimbing'] ?>)"
                                        class="fa-solid fa-pen text-blue-500 cursor-pointer"></i>

                                    <i onclick="openDeleteModal(<?= $row['id_pembimbing'] ?>)"
                                        class="fa-solid fa-trash text-red-600 cursor-pointer"></i>

                                </td>
                            </tr>
                        <?php endwhile; ?>

                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <!-- ================= MODAL FORM ================= -->
    <div id="modalForm"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-2xl rounded p-10">

            <h2 id="modalTitle" class="text-xl font-bold mb-8"></h2>

            <form id="formPembimbing">
                <input type="hidden" name="id" id="pb_id">

                <div class="space-y-6">

                    <div class="flex items-center">
                        <label class="w-56">Nama Pembimbing</label>
                        <span class="mr-4">:</span>
                        <input type="text" name="nama"
                            class="border rounded w-96 p-2">
                    </div>

                    <div class="flex items-center">
                        <label class="w-56">NIP</label>
                        <span class="mr-4">:</span>
                        <input type="text" name="nip"
                            class="border rounded w-96 p-2">
                    </div>

                    <div class="flex items-center">
                        <label class="w-56">Jabatan</label>
                        <span class="mr-4">:</span>
                        <input type="text" name="jabatan"
                            class="border rounded w-96 p-2">
                    </div>

                    <div class="flex items-center">
                        <label class="w-56">Divisi</label>
                        <span class="mr-4">:</span>
                        <input type="text" name="divisi"
                            class="border rounded w-96 p-2">
                    </div>

                    <div class="flex items-center">
                        <label class="w-56">Informasi Kontak</label>
                        <span class="mr-4">:</span>
                        <input type="text" name="kontak"
                            class="border rounded w-96 p-2">
                    </div>

                    <div class="flex items-center">
                        <label class="w-56">Email</label>
                        <span class="mr-4">:</span>
                        <input type="text" name="email"
                            class="border rounded w-96 p-2">
                    </div>

                </div>

                <div class="flex justify-end gap-6 mt-10">
                    <button type="button" onclick="closeForm()"
                        class="bg-gray-400 text-white px-6 py-2 rounded">
                        Kembali
                    </button>

                    <button type="submit"
                        class="bg-gray-700 text-white px-6 py-2 rounded">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- ================= MODAL DELETE ================= -->
    <div id="modalDelete"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-md rounded p-10 text-center">

            <div class="text-yellow-500 text-6xl mb-6">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>

            <h2 class="text-xl font-bold mb-6">
                HAPUS DATA PEMBIMBING?
            </h2>

            <div class="flex justify-center gap-6">
                <button onclick="closeDelete()"
                    class="bg-gray-400 text-white px-6 py-2 rounded">
                    BATAL
                </button>

                <button onclick="confirmDelete()"
                    class="bg-gray-700 text-white px-6 py-2 rounded">
                    HAPUS
                </button>
            </div>

        </div>
    </div>

    <script>
        let deleteId = null;

        function openAddModal() {
            document.getElementById('modalTitle').innerText = 'TAMBAH PEMBIMBING';
            document.getElementById('formPembimbing').reset();
            document.getElementById('pb_id').value = '';
            document.getElementById('modalForm').classList.remove('hidden');
        }

        function openEditModal(id) {
            fetch('crud_dataPembimbing.php?action=get&id=' + id)
                .then(res => res.json())
                .then(d => {
                    document.getElementById('modalTitle').innerText = 'EDIT DATA PEMBIMBING';
                    document.querySelector('[name=nama]').value = d.nama_pembimbing;
                    document.querySelector('[name=nip]').value = d.nip;
                    document.querySelector('[name=jabatan]').value = d.jabatan;
                    document.querySelector('[name=divisi]').value = d.divisi;
                    document.querySelector('[name=kontak]').value = d.nomor_hp;
                    document.querySelector('[name=email]').value = d.email;
                    document.getElementById('pb_id').value = d.id_pembimbing;
                    document.getElementById('modalForm').classList.remove('hidden');
                });
        }

        function closeForm() {
            document.getElementById('modalForm').classList.add('hidden');
        }

        document.getElementById('formPembimbing').addEventListener('submit', function(e) {
            e.preventDefault();
            const action = document.getElementById('pb_id').value ? 'update' : 'add';
            fetch('crud_dataPembimbing.php?action=' + action, {
                method: 'POST',
                body: new FormData(this)
            }).then(() => location.reload());
        });

        function openDeleteModal(id) {
            deleteId = id;
            document.getElementById('modalDelete').classList.remove('hidden');
        }

        function closeDelete() {
            document.getElementById('modalDelete').classList.add('hidden');
        }

        function confirmDelete() {
            fetch('crud_dataPembimbing.php?action=delete&id=' + deleteId)
                .then(() => location.reload());
        }
    </script>

</body>

</html>