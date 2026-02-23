<?php
session_start();
require_once "koneksi.php";

/* =========================
   CEK LOGIN & ROLE
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

/* =========================
   STATISTIK DATA
========================= */

// Jumlah Pembimbing
$totalPembimbing = $conn->query("
    SELECT COUNT(*) as total FROM tb_pembimbing
")->fetch_assoc()['total'];

// Jumlah Mahasiswa
$totalMahasiswa = $conn->query("
    SELECT COUNT(*) as total FROM tb_mahasiswa
")->fetch_assoc()['total'];

/* =========================
   GRAFIK PENILAIAN PER BULAN
========================= */

$grafik = array_fill(1, 12, 0);

$qGrafik = $conn->query("
    SELECT MONTH(created_at) bulan, COUNT(*) total
    FROM tb_penilaian
    WHERE YEAR(created_at) = YEAR(CURDATE())
    GROUP BY MONTH(created_at)
");

while ($row = $qGrafik->fetch_assoc()) {
    $grafik[(int)$row['bulan']] = (int)$row['total'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-200">

    <!-- HEADER -->
    <?php include "include/navbar.php"; ?>

    <div class="flex">

        <!-- SIDEBAR -->
        <?php include "include/sidebar.php"; ?>

        <!-- CONTENT -->
        <div class="flex-1 p-8">

            <h2 class="text-2xl font-bold text-red-600 mb-6">
                SISTEM PENILAIAN MAHASISWA MAGANG
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

            <!-- GRAFIK -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="mb-4 font-semibold">
                    Grafik Penilaian per Bulan
                </h3>

                <div class="w-full md:w-1/2 mx-auto">
                    <canvas id="chart" height="250"></canvas>
                </div>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const ctx = document.getElementById('chart');

            if (ctx) {

                /* global Chart */
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: [
                            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                        ],
                        datasets: [{
                            data: <?= json_encode(array_values($grafik)) ?>,
                            backgroundColor: [
                                '#dc2626', '#ef4444', '#f87171', '#fca5a5',
                                '#991b1b', '#7f1d1d', '#b91c1c', '#e11d48',
                                '#fb7185', '#fecaca', '#450a0a', '#be123c'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

        });
    </script>

</body>

</html>