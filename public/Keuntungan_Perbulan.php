<?php
include('connect.php');
session_start();

//keuntungan

$query = "SELECT SUM(keuntungan) AS total_keuntungan FROM transaksi WHERE tanggal_transaksi <= CURDATE()";

$result = $conn->query($query);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $totalKeuntunganHariIni = $row["total_keuntungan"];
} else {
  $totalKeuntunganHariIni = 0;
}

//total bbm terjual hari 
$queryBbmTerjualHariIni = "SELECT SUM(jumlah_liters) AS total_bbm_terjual FROM transaksi WHERE DATE(tanggal_transaksi) = CURDATE()";
$resultBbmTerjual = $conn->query($queryBbmTerjualHariIni);
$rowBbmTerjual = $resultBbmTerjual->fetch_assoc();
$totalBbmTerjualHariIni = $rowBbmTerjual["total_bbm_terjual"];

//total penjualan hari ini 
$queryPenjualanHariIni = "SELECT SUM(keuntungan) AS total_penjualan FROM transaksi WHERE DATE(tanggal_transaksi) = CURDATE()";
$resultPenjualan = $conn->query($queryPenjualanHariIni);
$rowPenjualan = $resultPenjualan->fetch_assoc();
$totalPenjualanHariIni = $rowPenjualan["total_penjualan"];

// Mendapatkan data penjualan dari tabel transaksi dan jenis_bbm
$queryPie = "SELECT jenis_bbm.nama AS nama_bbm, SUM(transaksi.jumlah_liters) AS total_penjualan
              FROM transaksi
              JOIN jenis_bbm ON transaksi.jenis_bbm_id = jenis_bbm.id
              GROUP BY jenis_bbm.nama";
$resultPie = $conn->query($queryPie);

// Membuat array untuk menyimpan data penjualan
$labelsPieChart = array();
$dataPieChart = array();

// Menyimpan data dari query ke dalam array
if ($resultPie->num_rows > 0) {
  while ($row = $resultPie->fetch_assoc()) {
    $namaBBM = $row["nama_bbm"];
    $totalPenjualan = $row["total_penjualan"];
    $labelsPieChart[] = $namaBBM;
    $dataPieChart[] = $totalPenjualan;
  }
}

// Mendapatkan data keuntungan dari tabel transaksi tiap bulan
$queryLine = "SELECT MONTH(tanggal_transaksi) AS bulan, SUM(keuntungan) AS total_keuntungan
              FROM transaksi
              GROUP BY MONTH(tanggal_transaksi)
              ORDER BY MONTH(tanggal_transaksi)";
$resultLine = $conn->query($queryLine);

// Membuat array untuk menyimpan data keuntungan
$labelsLine = array();
$dataLine = array();

// Menyimpan data dari query ke dalam array
if ($resultLine->num_rows > 0) {
  while ($row = $resultLine->fetch_assoc()) {
    $bulan = $row["bulan"];
    $totalKeuntungan = $row["total_keuntungan"];
    $labelsLine[] = "Bulan " . $bulan;
    $dataLine[] = $totalKeuntungan;
  }
}



// Menutup koneksi ke database
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuntungag Perbulan</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    /* Style CSS untuk menyesuaikan tampilan chart */
    canvas {
        max-width: 100%;
        height: auto;
    }
    </style>

    <style>
    /* Style CSS untuk menyesuaikan tampilan chart */
    canvas {
        max-width: 100%;
        height: auto;
    }

    .bg-top-30 {
        background-color: #f7fafc;
        height: 30vh;
    }
    </style>
</head>

<body class=" m-0  text-base antialiased font-normal  leading-default text-slate-500 font-[Poppins]">
    <header>
        <span class="absolute text-white text-4xl top-5 left-4 cursor-pointer" onclick="Openbar()">
            <i class="bi bi-filter-left px-2 bg-gray-900 rounded-md"></i>
        </span>
        <div class="sidebar fixed top-0 bottom-0 lg:left-0 left-[-300px] duration-1000
      p-2 w-[300px] overflow-y-auto text-center bg-white shadow-2xl h-screen">
            <div class="text-black text-xl w-60">

                <!-- nutup svg -->
                <div class="p-2.5 mt-1 flex items-center rounded-md ">

                    <h1 class=" text-[#E62029] items-center align-middle text-4xl ml-3  font-bold py-2">My<span
                            class=" text-[#006CB7]">SPBU</span></h1>
                    <div class="ml-20 cursor-pointer lg:hidden" onclick="Openbar()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd"
                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                </div>
                <hr class="my-2 text-black">
                <!-- Dashboard -->
                <div>
                    <a href="../public/Dashboard.php">
                        <div
                            class="p-2.5 mt-2 flex items-center  rounded-md px-4 duration-300 cursor-pointer  hover:bg-[#0090C1]">
                            <div class="px-1 py-1 flex flex-row">
                                <img src="../img/dashboard.png" class="w-7 mr-4">
                                <span class="text-[15px] ml-2 text-black">Dashboard</span>
                            </div>
                        </div>
                </div>
            </div>
            <!-- Transaksi -->
            <a href="../public/Tabel_Transaksi.php">
                <div
                    class="p-2.5 mt-2 flex items-center  rounded-md px-4 duration-300 cursor-pointer  hover:bg-[#0090C1]">
                    <div class="px-1 py-1 flex flex-row">
                        <img src="../img/transaction.png" class="w-7 mr-4">
                        <span class="text-[15px] ml-2 text-black">Transaksi</span>
                    </div>

                </div>
            </a>
            <!-- Keuntungan -->
            <a href="../public/Keuntungan.php">
                <div
                    class="p-2.5 mt-2 flex items-center  rounded-md px-4 duration-300 cursor-pointer  hover:bg-[#0090C1]">
                    <div class="px-1 py-1 flex flex-row">
                        <img src="../img/money-bag.png" class="w-7 mr-4">
                        <span class="text-[15px] ml-2 text-black">Keuntungan</span>
                    </div>

                </div>
            </a>
            <!-- Keuntungan -->

            </a>
            <!-- Grafik -->
            <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-[#0090C1]">
                <div class="ml-2"></div>
                <img src="../img/bars.png" class="w-7">
                <div class="flex justify-between w-full items-center" onclick="dropDown()">
                    <span class="text-[15px] ml-4 text-black">Grafik</span>
                    <span class="text-sm rotate-180" id="arrow">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </div>
            </div>
            <div class=" leading-7 text-left text-sm  mt-2 w-4/5 mx-auto" id="submenu">
                <a href="../public/BBM_Terjual.php">
                    <div
                        class="p-2.5  flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-[#0090C1]">
                        <img src="../img/gas-cylinder.png" class="w-7 mr-4">
                        <span class="text-[15px] ml-2 text-black">BBM Terjual</span>
                    </div>
                    <a href="../public/Keuntungan_Perbulan.php">
                        <div
                            class="p-2.5  flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-[#0090C1]">
                            <img src="../img/financial-profit.png" class="w-7 mr-4">
                            <span class="text-[15px] ml-4 text-black">Keuntungan Perbulan</span>
                        </div>
            </div>



            <!-- Info -->
            <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-[#0090C1]">
                <div class="ml-2 "></div>
                <img src="../img/info.png" class="w-7">
                <div class="flex justify-between w-full items-center" onclick="dropDown2()">
                    <span class="text-[15px] ml-4  text-black">Info</span>
                    <span class="text-sm rotate-180" id="arrow2">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </div>
            </div>
            <div class=" leading-7 text-left text-sm  mt-2 w-4/5 mx-auto" id="submenu2">
                <div class="p-2.5  flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-[#0090C1]">
                    <img src="../img/placeholder (3).png" class="w-7 mr-4">
                    <span class="text-[15px] ml-2 text-black">Peta Sebaran</span>
                </div>
                <div class="p-2.5  flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-[#0090C1]">
                    <img src="../img/oil-bottle.png" class="w-7 mr-4">
                    <span class="text-[15px] ml-2 text-black">Harga Baru BBM</span>
                </div>
            </div>

        </div>
        </div>
    </header>
    <main class="lg:ml-80">
        <div class="flex items-center justify-between text-black w-full py-1 mx-auto flex-wrap-inherit mr-2">
            <nav>
                <!-- navbar -->
                <ol class="flex flex-wrap pt-1 bg-transparent rounded-lg sm:mr-16">
                    <li class="text-sm leading-normal">
                        <a class="text-black opacity-50" href="javascript:;">Pages</a>
                    </li>
                    <li class="text-sm capitalize leading-normal text-black before:float-left before:pr-2 before:text-black before:content-['/']"
                        aria-current="page">Grafik/ Keuntungan Perbulan</li>
                </ol>

            </nav>

            <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
                <div class="flex items-center md:ml-auto md:pr-4">
                    <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease">
                        <span
                            class="text-sm ease leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                            <i class="fas fa-search" aria-hidden="true"></i>
                        </span>
                        <input type="text"
                            class="pl-9 text-sm focus:shadow-primary-outline ease w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow"
                            placeholder="Type here...">
                    </div>
                </div>
                <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                    <!-- online builder btn  -->
                    <!-- <li class="flex items-center">
            <a class="inline-block px-8 py-2 mb-0 mr-4 text-xs font-bold text-center text-blue-500 uppercase align-middle transition-all ease-in bg-transparent border border-blue-500 border-solid rounded-lg shadow-none cursor-pointer leading-pro hover:-translate-y-px active:shadow-xs hover:border-blue-500 active:bg-blue-500 active:hover:text-blue-500 hover:text-blue-500 tracking-tight-rem hover:bg-transparent hover:opacity-75 hover:shadow-none active:text-white active:hover:bg-transparent" target="_blank" href="https://www.creative-tim.com/builder/soft-ui?ref=navbar-dashboard&amp;_ga=2.76518741.1192788655.1647724933-1242940210.1644448053">Online Builder</a>
          </li> -->
                    <li class="flex items-center mr-5">
                        <a href="./pages/sign-in.php"
                            class="block px-0 py-2 text-sm font-semibold text-white transition-all ease-nav-brand">
                            <i class="fa fa-user sm:mr-1" aria-hidden="true"></i>
                            <span class="hidden text-black sm:inline">Sign In</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="w-full h-full py-5">
            <!-- Bagian Atas -->
            <h1 class="text-2xl font-semibold text-slate-900 mb-2">Keuntungan Perbulan </h1>
            <div class="flex flex-col md:flex-row items-center md:justify-between mb-4">
            </div>


            <div class="grid gap-2 grid-cols-1 md:grid-cols-3 mb-3  py-2  ">
                <div class="card  rounded-md px-5 py-5 bg-[#E62029] shadow-gray-300 shadow-2xl">
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div class="widget-label">
                                <h2 class="text-white font-semibold text-2xl">
                                    Keuntungan
                                    <br>

                                </h2>

                                <h1 class="text-white  py-2 text-2xl">
                                    <span>Rp. </span><?php echo number_format($totalKeuntunganHariIni,0,',','.') ; ?>
                                </h1>
                            </div>
                            <img src="../img/keuntungan.png" alt="Ikon Client"
                                class="icon  widget-icon w-14 pb-10 text-green-500 inline-block">
                        </div>
                    </div>
                </div>
                <div class="card bg-[#20A500] rounded-md px-5 py-5 shadow-gray-300 shadow-2xl">
                    <div class="card-content ">
                        <div class="flex items-center justify-between">
                            <div class="widget-label">
                                <h2 class="text-white font-semibold text-2xl">
                                    BBM Terjual
                                    <br>

                                </h2>

                                <h1 class="text-white  py-2 text-2xl">
                                    <?php echo $totalBbmTerjualHariIni; ?> Liter
                                </h1>
                            </div>
                            <img src="../img/BBM.png" alt="Ikon Client"
                                class="icon widget-icon w-14 pb-10 text-green-500 inline-block">
                        </div>
                    </div>
                </div>

                <div class="card bg-[#006CB7] rounded-md px-5 py-5 shadow-gray-300 shadow-2xl">
                    <div class="card-content ">
                        <div class="flex items-center justify-between">
                            <div class="widget-label">
                                <h2 class="text-white font-semibold text-2xl">
                                    Total Penjualan
                                    <br>

                                </h2>

                                <h1 class="text-white  py-2 text-2xl">
                                    <span>Rp.</span><?php echo number_format($totalPenjualanHariIni,0,',','.') ; ?>
                                </h1>
                            </div>
                            <img src="../img/Total.png" alt="Ikon Client"
                                class="icon widget-icon w-14 pb-10 text-green-500 inline-block">
                        </div>
                    </div>
                </div>
            </div>


            <!-- Line Chart -->
            <div class="w-full h-full">
                <div class="w-full h-full bg-white shadow-xl rounded-xl">
                    <h2 class="text-2xl font-semibold mb-4 px-5 py-3">BBM Terjual Perbulan</h2>
                    <div class="max-h-96">
                        <canvas id="lineChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>


    </main>

    <!-- ini javascript sidebar -->
    <script>
    function dropDown() {
        document.querySelector('#submenu').classList.toggle('hidden')
        document.querySelector('#arrow').classList.toggle('rotate-0')
    }
    dropDown()

    function dropDown2() {
        document.querySelector('#submenu2').classList.toggle('hidden')
        document.querySelector('#arrow2').classList.toggle('rotate-0')
    }
    dropDown2()

    function Openbar() {
        document.querySelector('.sidebar').classList.toggle('left-[-300px]')
    }
    // Panggil fungsi Openbar() saat halaman web dimuat
    window.addEventListener('load', Openbar);

    // Initialization for ES Users
    </script>

    <script>
    const pieChartCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieChartCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($labelsPieChart); ?>,
            datasets: [{
                label: 'Penjualan BBM',
                data: <?php echo json_encode($dataPieChart); ?>,
                backgroundColor: ['rgba(0, 123, 255, 0.7)', 'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
    </script>

    <script>
    const ctx = document.getElementById('lineChart').getContext('2d');
    var gradientStroke1 = ctx.createLinearGradient(0, 230, 0, 50);
    gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.5)');
    gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.2)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labelsLine); ?>,
            datasets: [{
                label: 'Keuntungan',
                data: <?php echo json_encode($dataLine); ?>,
                borderColor: 'rgba(94, 114, 228)',
                backgroundColor: gradientStroke1,
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 100
                    }
                }
            }
        }
    });
    </script>

    <!-- ini javascript sidebar -->
    <script>
    function dropDown() {
        document.querySelector('#submenu').classList.toggle('hidden')
        document.querySelector('#arrow').classList.toggle('rotate-0')
    }
    dropDown()

    function dropDown2() {
        document.querySelector('#submenu2').classList.toggle('hidden')
        document.querySelector('#arrow2').classList.toggle('rotate-0')
    }
    dropDown2()

    function Openbar() {
        document.querySelector('.sidebar').classList.toggle('left-[-300px]')
    }
    // Panggil fungsi Openbar() saat halaman web dimuat
    window.addEventListener('load', Openbar);

    // Initialization for ES Users
    </script>
</body>

</html>