<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'];
?>

<aside class="w-64 bg-red-600 text-white min-h-screen flex flex-col">

    <!-- PROFILE HEADER -->
    <div class="p-6 border-b border-red-400">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center">
                <i class="fa-solid fa-user text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="font-semibold text-lg">
                    <?= ucfirst($role); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- NAVIGATION -->
    <nav class="flex-1 mt-4">

        <!-- ================= ADMIN ================= -->
        <?php if ($role === 'admin'): ?>

            <!-- DASHBOARD ADMIN -->
            <a href="dashboard.php"
                class="flex items-center gap-4 px-6 py-3 transition
                <?= $currentPage == 'dashboard.php'
                    ? 'bg-red-700 border-l-4 border-white'
                    : 'hover:bg-red-700' ?>">

                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center">
                    <i class="fa-solid fa-gauge text-red-600 text-sm"></i>
                </div>

                <span class="font-medium">Dashboard</span>
            </a>

            <!-- DATA MAHASISWA -->
            <a href="data_mahasiswa.php"
                class="flex items-center gap-4 px-6 py-3 transition
                <?= $currentPage == 'data_mahasiswa.php'
                    ? 'bg-red-700 border-l-4 border-white'
                    : 'hover:bg-red-700' ?>">

                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center">
                    <i class="fa-solid fa-users text-red-600 text-sm"></i>
                </div>

                <span class="font-medium">Data Mahasiswa</span>
            </a>

            <!-- DATA PEMBIMBING -->
            <a href="data_pembimbing.php"
                class="flex items-center gap-4 px-6 py-3 transition
                <?= $currentPage == 'data_pembimbing.php'
                    ? 'bg-red-700 border-l-4 border-white'
                    : 'hover:bg-red-700' ?>">

                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center">
                    <i class="fa-solid fa-user-tie text-red-600 text-sm"></i>
                </div>

                <span class="font-medium">Data Pembimbing</span>
            </a>

            <!-- REKAP NILAI -->
            <a href="dataMahasiswa_pembimbing.php"
                class="flex items-center gap-4 px-6 py-3 transition
                <?= $currentPage == 'dataMahasiswa_pembimbing.php'
                    ? 'bg-red-700 border-l-4 border-white'
                    : 'hover:bg-red-700' ?>">

                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center">
                    <i class="fa-solid fa-chart-column text-red-600 text-sm"></i>
                </div>

                <span class="font-medium">Rekap Nilai</span>
            </a>

        <?php endif; ?>


        <!-- ================= PEMBIMBING ================= -->
        <?php if ($role === 'pembimbing'): ?>

            <!-- DASHBOARD PEMBIMBING -->
            <a href="dashboard_pembimbing.php"
                class="flex items-center gap-4 px-6 py-3 transition
                <?= $currentPage == 'dashboard_pembimbing.php'
                    ? 'bg-red-700 border-l-4 border-white'
                    : 'hover:bg-red-700' ?>">

                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center">
                    <i class="fa-solid fa-gauge text-red-600 text-sm"></i>
                </div>

                <span class="font-medium">Dashboard</span>
            </a>

            <!-- DATA MAHASISWA -->
            <a href="dataMahasiswa_pembimbing.php"
                class="flex items-center gap-4 px-6 py-3 transition
                <?= $currentPage == 'dataMahasiswa_pembimbing.php'
                    ? 'bg-red-700 border-l-4 border-white'
                    : 'hover:bg-red-700' ?>">

                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center">
                    <i class="fa-solid fa-users text-red-600 text-sm"></i>
                </div>

                <span class="font-medium">Data Mahasiswa</span>
            </a>

            <!-- DATA PEMBIMBING -->
            <a href="pembimbing.php"
                class="flex items-center gap-4 px-6 py-3 transition
                <?= $currentPage == 'pembimbing.php'
                    ? 'bg-red-700 border-l-4 border-white'
                    : 'hover:bg-red-700' ?>">

                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center">
                    <i class="fa-solid fa-user-tie text-red-600 text-sm"></i>
                </div>

                <span class="font-medium">Data Pembimbing</span>
            </a>

            <!-- PENILAIAN (KHUSUS PEMBIMBING) -->
            <!-- <a href="penilaian.php"
                class="flex items-center gap-4 px-6 py-3 transition
                <?= $currentPage == 'penilaian.php'
                    ? 'bg-red-700 border-l-4 border-white'
                    : 'hover:bg-red-700' ?>">

                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-check text-red-600 text-sm"></i>
                </div>

                <span class="font-medium">Penilaian</span>
            </a> -->

        <?php endif; ?>

    </nav>

</aside>