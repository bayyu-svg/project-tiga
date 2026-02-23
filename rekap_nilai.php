<?php
require_once "include/session.php";
$requiredRoles = ['admin', 'pembimbing'];
$backUrl = ($_SESSION['role'] == 'admin')
    ? 'dataMahasiswa_pembimbing.php'
    : 'dataMahasiswa_pembimbing.php';
require_once "include/auth.php";
require_once "koneksi.php";

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = (int)$_GET['id'];

/* ===============================
   FUNCTION KETERANGAN OTOMATIS
================================ */
function getKeterangan($nilai)
{
    if ($nilai >= 90) return "Sangat Baik";
    elseif ($nilai >= 80) return "Baik";
    elseif ($nilai >= 70) return "Cukup";
    else return "Kurang";
}

/* ===============================
   DATA MAHASISWA
================================ */
$mahasiswa = $conn->query("
    SELECT m.*, p.nama_pembimbing
    FROM tb_mahasiswa m
    LEFT JOIN tb_pembimbing p 
        ON m.id_pembimbing = p.id_pembimbing
    WHERE m.id_mahasiswa = $id
")->fetch_assoc();

/* ===============================
   DATA NILAI
================================ */
$nilai = $conn->query("
    SELECT *
    FROM tb_penilaian
    WHERE id_mahasiswa = $id
")->fetch_assoc();

if (!$nilai) {
    die("Mahasiswa belum dinilai.");
}

/* ===============================
   HITUNG RATA-RATA
================================ */
$rata = (
    $nilai['nilai_disiplin'] +
    $nilai['nilai_tanggung_jawab'] +
    $nilai['nilai_etika'] +
    $nilai['nilai_komunikasi']
) / 4;
?>

<!DOCTYPE html>
<html>

<head>
    <?php include "include/head.php"; ?>
    <title>Rekap Nilai</title>
</head>

<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto bg-white p-10 rounded shadow mt-10">

        <h1 class="text-3xl font-bold text-red-600 mb-8">
            REKAP NILAI
        </h1>

        <!-- DATA MAHASISWA -->
        <div class="border p-6 rounded mb-8 bg-gray-50">
            <p><strong>Nama Mahasiswa:</strong> <?= $mahasiswa['nama_mahasiswa'] ?></p>
            <p><strong>Asal Kampus:</strong> <?= $mahasiswa['asal_kampus'] ?></p>
            <p><strong>Divisi:</strong> <?= $mahasiswa['divisi'] ?></p>
            <p><strong>Pembimbing:</strong> <?= $mahasiswa['nama_pembimbing'] ?></p>
            <p><strong>Periode Magang:</strong> <?= $mahasiswa['periode_magang'] ?></p>
        </div>

        <!-- TABEL NILAI -->
        <table class="w-full border text-center mb-8">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">Aspek</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>

                <tr class="border-t">
                    <td>Disiplin</td>
                    <td><?= $nilai['nilai_disiplin'] ?></td>
                    <td><?= getKeterangan($nilai['nilai_disiplin']) ?></td>
                </tr>

                <tr class="border-t">
                    <td>Tanggung Jawab</td>
                    <td><?= $nilai['nilai_tanggung_jawab'] ?></td>
                    <td><?= getKeterangan($nilai['nilai_tanggung_jawab']) ?></td>
                </tr>

                <tr class="border-t">
                    <td>Etika</td>
                    <td><?= $nilai['nilai_etika'] ?></td>
                    <td><?= getKeterangan($nilai['nilai_etika']) ?></td>
                </tr>

                <tr class="border-t">
                    <td>Komunikasi</td>
                    <td><?= $nilai['nilai_komunikasi'] ?></td>
                    <td><?= getKeterangan($nilai['nilai_komunikasi']) ?></td>
                </tr>

                <!-- RATA-RATA -->
                <tr class="border-t font-bold bg-gray-100">
                    <td>Rata-rata</td>
                    <td><?= round($rata, 2) ?></td>
                    <td><?= getKeterangan($rata) ?></td>
                </tr>

            </tbody>
        </table>

        <!-- CATATAN -->
        <div class="mb-8">
            <h3 class="font-bold mb-2">Catatan Pembimbing:</h3>
            <div class="border rounded p-4 bg-gray-50">
                <?= $nilai['komentar'] ?? '-' ?>
            </div>
        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-4">
            <a href="<?= $backUrl ?>"
                class="bg-gray-500 text-white px-6 py-2 rounded">
                Kembali
            </a>

            <button onclick="window.print()"
                class="bg-gray-800 text-white px-6 py-2 rounded">
                Unduh
            </button>
        </div>

    </div>

</body>

</html>