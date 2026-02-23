<?php
require_once "koneksi.php";

$id = (int)$_GET['id'];

/* =========================
   Ambil Data Mahasiswa
========================= */
$mhs = $conn->query("
SELECT m.*, p.nama_pembimbing
FROM tb_mahasiswa m
LEFT JOIN tb_pembimbing p
ON m.id_pembimbing = p.id_pembimbing
WHERE m.id_mahasiswa = $id
")->fetch_assoc();

/* =========================
   Ambil Data Penilaian
========================= */
$nilai = $conn->query("
SELECT * FROM tb_penilaian
WHERE id_mahasiswa = $id
")->fetch_assoc();

if (!$nilai) {
    echo "<p class='text-gray-500'>Mahasiswa belum dinilai.</p>";
    exit;
}

require_once "koneksi.php";

function getKeterangan($nilai)
{
    if ($nilai >= 90) {
        return "Sangat Baik";
    } elseif ($nilai >= 80) {
        return "Baik";
    } elseif ($nilai >= 70) {
        return "Cukup";
    } else {
        return "Kurang";
    }
}
?>

<h2 class="text-xl font-bold text-red-600 mb-4">
    REKAP NILAI
</h2>

<!-- ================= INFO MAHASISWA ================= -->
<div class="bg-gray-50 p-4 rounded border mb-4 text-sm space-y-1">
    <p><strong>Nama Mahasiswa:</strong> <?= $mhs['nama_mahasiswa'] ?></p>
    <p><strong>Asal Kampus:</strong> <?= $mhs['asal_kampus'] ?></p>
    <p><strong>Divisi:</strong> <?= $mhs['divisi'] ?></p>
    <p><strong>Pembimbing:</strong> <?= $mhs['nama_pembimbing'] ?></p>
    <p><strong>Periode Magang:</strong> <?= $mhs['periode_magang'] ?></p>
</div>

<!-- ================= TABEL NILAI ================= -->
<table class="w-full border text-sm text-center mb-4">
    <thead class="bg-gray-100">
        <tr>
            <th>Aspek</th>
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
    </tbody>
</table>

<!-- ================= CATATAN ================= -->
<div class="mb-4">
    <strong>Catatan Pembimbing:</strong>
    <div class="border rounded p-3 bg-gray-50 mt-1">
        <?= $nilai['komentar'] ?>
    </div>
</div>

<!-- ================= BUTTON ================= -->
<div class="text-right space-x-3">
    <a href="rekap_nilai.php?id=<?= $id ?>" target="_blank"
        class="bg-gray-700 text-white px-4 py-2 rounded">
        Unduh
    </a>
</div>