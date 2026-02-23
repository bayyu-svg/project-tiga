<?php
require_once "koneksi.php";

$id = (int)$_GET['id'];

/* =============================
   Cek apakah sudah dinilai
============================= */
$nilai = $conn->query("
SELECT * FROM tb_penilaian
WHERE id_mahasiswa = $id
")->fetch_assoc();

/* =============================
   Ambil data mahasiswa
============================= */
$mhs = $conn->query("
SELECT m.*, p.nama_pembimbing
FROM tb_mahasiswa m
LEFT JOIN tb_pembimbing p
ON m.id_pembimbing = p.id_pembimbing
WHERE m.id_mahasiswa = $id
")->fetch_assoc();
?>

<div class="mb-4">
    <p><strong>Nama:</strong> <?= $mhs['nama_mahasiswa'] ?></p>
    <p><strong>Asal Kampus:</strong> <?= $mhs['asal_kampus'] ?></p>
</div>

<?php if ($nilai): ?>
    <!-- ================= SUDAH DINILAI ================= -->

    <div class="space-y-3">

        <p><strong>Disiplin:</strong> <?= $nilai['nilai_disiplin'] ?></p>
        <p><strong>Tanggung Jawab:</strong> <?= $nilai['nilai_tanggung_jawab'] ?></p>
        <p><strong>Etika:</strong> <?= $nilai['nilai_etika'] ?></p>
        <p><strong>Komunikasi:</strong> <?= $nilai['nilai_komunikasi'] ?></p>

        <p class="mt-3"><strong>Komentar:</strong></p>
        <div class="border rounded p-3 bg-gray-50">
            <?= $nilai['komentar'] ?>
        </div>

        <div class="text-right mt-4">
            <span class="text-green-600 font-semibold">
                ✔ Sudah Dinilai
            </span>
        </div>

    </div>

<?php else: ?>
    <!-- ================= BELUM DINILAI ================= -->

    <form method="POST" action="proses_penilaian.php">
        <input type="hidden" name="id_mahasiswa" value="<?= $id ?>">

        <div class="space-y-3">

            <label>Disiplin</label>
            <input type="number" name="nilai_disiplin"
                class="border rounded w-full p-2" required>

            <label>Tanggung Jawab</label>
            <input type="number" name="nilai_tanggung_jawab"
                class="border rounded w-full p-2" required>

            <label>Etika</label>
            <input type="number" name="nilai_etika"
                class="border rounded w-full p-2" required>

            <label>Komunikasi</label>
            <input type="number" name="nilai_komunikasi"
                class="border rounded w-full p-2" required>

            <label>Komentar</label>
            <textarea name="komentar"
                class="border rounded w-full p-2"></textarea>

            <button class="bg-gray-700 text-white px-4 py-2 rounded mt-3">
                Simpan Nilai
            </button>

        </div>
    </form>

<?php endif; ?>