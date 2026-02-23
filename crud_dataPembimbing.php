<?php
require_once "include/session.php";
require_once "koneksi.php";

$action = $_GET['action'] ?? '';

/* ===============================
   GET DATA (UNTUK EDIT)
================================ */
if ($action == 'get') {

    $id = (int)$_GET['id'];

    $data = $conn->query("
        SELECT *
        FROM tb_pembimbing
        WHERE id_pembimbing = $id
    ")->fetch_assoc();

    echo json_encode($data);
    exit;
}

/* ===============================
   TAMBAH DATA
================================ */
if ($action == 'add') {

    $nama     = $conn->real_escape_string($_POST['nama']);
    $nip      = $conn->real_escape_string($_POST['nip']);
    $jabatan  = $conn->real_escape_string($_POST['jabatan']);
    $divisi   = $conn->real_escape_string($_POST['divisi']);
    $kontak   = $conn->real_escape_string($_POST['kontak']);

    /* ===============================
       1. Buat Username Otomatis
    =============================== */

    $lastUser = $conn->query("
        SELECT COUNT(*) as total 
        FROM tb_user 
        WHERE id_role = 2
    ")->fetch_assoc();

    $number = $lastUser['total'] + 1;
    $username = "pembimbing" . $number;

    $password = md5("123456"); // password default

    /* ===============================
       2. Insert ke tb_user
    =============================== */

    $conn->query("
        INSERT INTO tb_user (username, password, id_role, created_at)
        VALUES ('$username', '$password', 2, NOW())
    ");

    $id_user_baru = $conn->insert_id;

    /* ===============================
       3. Insert ke tb_pembimbing
    =============================== */

    $conn->query("
        INSERT INTO tb_pembimbing
        (nama_pembimbing, nip, jabatan, divisi, nomor_hp, id_user)
        VALUES
        ('$nama','$nip','$jabatan','$divisi','$kontak','$id_user_baru')
    ");

    echo json_encode(['status' => 'success']);
    exit;
}

/* ===============================
   UPDATE DATA
================================ */
if ($action == 'update') {

    $id       = (int)$_POST['id'];
    $nama     = $_POST['nama'];
    $nip      = $_POST['nip'];
    $jabatan  = $_POST['jabatan'];
    $divisi   = $_POST['divisi'];
    $kontak   = $_POST['kontak'];

    $conn->query("
        UPDATE tb_pembimbing SET
        nama_pembimbing = '$nama',
        nip = '$nip',
        jabatan = '$jabatan',
        divisi = '$divisi',
        nomor_hp = '$kontak'
        WHERE id_pembimbing = $id
    ");

    echo json_encode(['status' => 'updated']);
    exit;
}

/* ===============================
   DELETE DATA
================================ */
if ($action == 'delete') {

    $id = (int)$_GET['id'];

    $conn->query("
        DELETE FROM tb_pembimbing
        WHERE id_pembimbing = $id
    ");

    echo json_encode(['status' => 'deleted']);
    exit;
}
