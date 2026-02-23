<?php
require_once "include/session.php";
require_once "koneksi.php";

$action = $_GET['action'] ?? '';

if ($action == 'get') {
    $id = (int)$_GET['id'];
    $data = $conn->query("
SELECT * FROM tb_mahasiswa
WHERE id_mahasiswa=$id
")->fetch_assoc();
    echo json_encode($data);
}

if ($action == 'detail') {
    $id = (int)$_GET['id'];
    $data = $conn->query("
SELECT m.*, p.nama_pembimbing
FROM tb_mahasiswa m
LEFT JOIN tb_pembimbing p
ON m.id_pembimbing=p.id_pembimbing
WHERE m.id_mahasiswa=$id
")->fetch_assoc();
    echo json_encode($data);
}

if ($action == 'add') {
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $asal = $_POST['asal'];
    $divisi = $_POST['divisi'];
    $pembimbing = $_POST['pembimbing'];
    $periode = $_POST['periode'];
    $kontak = $_POST['kontak'];

    $conn->query("
INSERT INTO tb_mahasiswa
(nama_mahasiswa,nim,asal_kampus,divisi,id_pembimbing,periode_magang,nomor_hp)
VALUES
('$nama','$nim','$asal','$divisi','$pembimbing','$periode','$kontak')
");
}

if ($action == 'update') {
    $id = (int)$_POST['id'];
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $asal = $_POST['asal'];
    $divisi = $_POST['divisi'];
    $pembimbing = $_POST['pembimbing'];
    $periode = $_POST['periode'];
    $kontak = $_POST['kontak'];

    $conn->query("
UPDATE tb_mahasiswa SET
nama_mahasiswa='$nama',
nim='$nim',
asal_kampus='$asal',
divisi='$divisi',
id_pembimbing='$pembimbing',
periode_magang='$periode',
nomor_hp='$kontak'
WHERE id_mahasiswa=$id
");
}

if ($action == 'delete') {
    $id = (int)$_GET['id'];
    $conn->query("DELETE FROM tb_mahasiswa WHERE id_mahasiswa=$id");
}
