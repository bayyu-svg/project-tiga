<?php
use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';

function kirimEmail($to, $namaPembimbing, $namaMahasiswa)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'basakon08@gmail.com';
        $mail->Password = 'mlaqdszkvvqrdhtn'; // bukan password biasa
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('basakon08@gmail.com', 'Sistem Penilaian');
        $mail->addAddress($to, $namaPembimbing);

        $mail->isHTML(true);
        $mail->Subject = 'Mahasiswa Baru Perlu Dinilai';
        $mail->Body = "
            Halo <b>$namaPembimbing</b>,<br><br>
            Ada mahasiswa baru yang perlu dinilai:<br>
            <b>$namaMahasiswa</b><br><br>
            Silakan login ke sistem untuk melakukan penilaian.
        ";

        $mail->send();
    } catch (Exception $e) {
        // bisa di-log kalau perlu
    }
}