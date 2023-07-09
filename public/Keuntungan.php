<?php
 session_start();
 include ('connect.php');
// Query untuk mengambil semua jenis BBM
$query_jenis_bbm = "SELECT DISTINCT nama FROM jenis_bbm";
$result_jenis_bbm = mysqli_query($conn, $query_jenis_bbm);

$query = "SELECT ROW_NUMBER() OVER (ORDER BY transaksi.id) AS 'No.',
          MONTH(transaksi.tanggal_transaksi) AS 'Bulan',
          YEAR(transaksi.tanggal_transaksi) AS 'Tahun',
          SUM(transaksi.keuntungan) AS 'Total Keuntungan',
          SUM(transaksi.jumlah_liters) AS 'Total BBM Terjual (liter)'
          FROM transaksi JOIN jenis_bbm ON transaksi.jenis_bbm_id = jenis_bbm.id GROUP BY
          MONTH(transaksi.tanggal_transaksi),
          YEAR(transaksi.tanggal_transaksi) ORDER BY
          YEAR(transaksi.tanggal_transaksi),
          MONTH(transaksi.tanggal_transaksi) ";

$result_filtered = mysqli_query($conn, $query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $result_filtered = mysqli_query($conn, $query);
    $jenis_bbm = $_POST["jenis_bbm"];

    if ($jenis_bbm == "Semua Data") {
        $query = "SELECT ROW_NUMBER() OVER (ORDER BY transaksi.id) AS 'No.',
        MONTH(transaksi.tanggal_transaksi) AS 'Bulan',
        YEAR(transaksi.tanggal_transaksi) AS 'Tahun',
        SUM(transaksi.keuntungan) AS 'Total Keuntungan',
        SUM(transaksi.jumlah_liters) AS 'Total BBM Terjual (liter)'
        FROM transaksi JOIN jenis_bbm ON transaksi.jenis_bbm_id = jenis_bbm.id GROUP BY
        MONTH(transaksi.tanggal_transaksi),
        YEAR(transaksi.tanggal_transaksi) ORDER BY
        YEAR(transaksi.tanggal_transaksi),
        MONTH(transaksi.tanggal_transaksi)";
    } else {
    //     $query = "SELECT jenis_bbm.nama, 
    //     MONTH(transaksi.tanggal_transaksi) AS 'Bulan',
    //     YEAR(transaksi.tanggal_transaksi) AS 'Tahun',
    //     SUM(transaksi.keuntungan) AS 'Total Keuntungan'
    // FROM transaksi 
    // JOIN jenis_bbm ON transaksi.jenis_bbm_id = jenis_bbm.id
    // GROUP BY jenis_bbm.nama, MONTH(transaksi.tanggal_transaksi), YEAR(transaksi.tanggal_transaksi)
    // ORDER BY YEAR(transaksi.tanggal_transaksi), MONTH(transaksi.tanggal_transaksi)";
    $query= "SELECT ROW_NUMBER() OVER (ORDER BY transaksi.id) AS 'No.',
                            MONTH(transaksi.tanggal_transaksi) AS 'Bulan',
                            YEAR(transaksi.tanggal_transaksi) AS 'Tahun',
                            SUM(transaksi.keuntungan) AS 'Total Keuntungan',
                            SUM(transaksi.jumlah_liters) AS 'Total BBM Terjual (liter)'
                            FROM transaksi JOIN jenis_bbm ON transaksi.jenis_bbm_id = jenis_bbm.id
                            WHERE jenis_bbm.nama = '$jenis_bbm'
                            GROUP BY MONTH(transaksi.tanggal_transaksi), YEAR(transaksi.tanggal_transaksi)
                            ORDER BY YEAR(transaksi.tanggal_transaksi), MONTH(transaksi.tanggal_transaksi)";
    }

    $result_filtered = mysqli_query($conn, $query);

    if ($result_filtered) {
        // Menggunakan mysqli_num_rows() hanya jika hasil query valid
        $row_count = mysqli_num_rows($result_filtered);

       
    } else {
        // Handle kesalahan query
        echo "Terjadi kesalahan dalam menjalankan query: " . mysqli_error($conn);
    }
    
}



function bulann($bulan) {
  $namaBulan = [
      1 => 'Januari',
      2 => 'Februari',
      3 => 'Maret',
      4 => 'April',
      5 => 'Mei',
      6 => 'Juni',
      7 => 'Juli',
      8 => 'Agustus',
      9 => 'September',
      10 => 'Oktober',
      11 => 'November',
      12 => 'Desember'
  ];

  return $namaBulan[$bulan];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuntungan</title>
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
                <a href="../public/Dashboard.php">
                    <div
                        class="p-2.5 mt-2 flex items-center  rounded-md px-4 duration-300 cursor-pointer  hover:bg-[#0090C1]">
                        <div class="px-1 py-1 flex flex-row">
                            <img src="../img/dashboard.png" class="w-7 mr-4">
                            <span class="text-[15px] ml-2 text-black">Dashboard</span>
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
                        aria-current="page">Keuntungan</li>
                </ol>
                <h6 class="mb-0 font-semibold text-black capitalize">Keuntungan</h6>
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
                        <a href="./pages/sign-in.html"
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
            <h1 class="text-2xl font-semibold text-slate-900 mb-5">Keuntungan </h1>
            <div class="flex flex-col md:flex-row items-center md:justify-between mb-4">
                <!-- Dropdown Data -->
                <div class="w-full md:w-1/5 mb-1 md:mb-0">
                    <p class="text-base font-semibold text-slate-400">Data</p>
                    <form method="POST" class="flex flex-row items-center">
                        <select name="jenis_bbm"
                            class="text-slate-800 text-sm bg-white border border-gray-300 shadow-md py-2 px-3 pr-3 rounded-md w-full mr-2"
                            style="font-size: 16px; padding: 10px;">
                            <!-- Dropdown -->
                            <option value="Semua Data">Semua Data</option>
                            <?php
        $query_jenis_bbm = "SELECT DISTINCT nama FROM jenis_bbm";
        $result_jenis_bbm = mysqli_query($conn, $query_jenis_bbm);
        while ($row_jenis_bbm = mysqli_fetch_assoc($result_jenis_bbm)) :
            $nama_bbm = $row_jenis_bbm['nama'];
            $selected = ($jenis_bbm == $nama_bbm) ? 'selected' : '';
            echo "<option value=\"$nama_bbm\" $selected>$nama_bbm</option>";
        endwhile;
        ?>
                        </select>
                        <button type="submit"
                            class="bg-[#E62029] hover:bg-red-700 rounded-md text-white py-2 px-4">Filter</button>
                    </form>

                </div>
                <!-- Dropdown Expor -->
                <div class="w-4 md:w-1/5 mr-20">
                    <select name="" id=""
                        class="text-slate-800 text-sm w-40 bg-white border shadow-md py-2 px-3 pr-3 rounded-md ">
                        <option selected>Exspor</option>
                        <option value="PDF">.pdf</option>
                        <option value="XLSX">.xlsx</option>
                    </select>
                </div>
            </div>
            <!-- Bagian Tengah -->
            <div class="flex flex-col md:flex-row mb-7 items-center md:justify-between">
                <!-- Drop Down Tampilan Data -->
                <div class="w-full md:w-1/4 mb-4 md:mb-0">
                    <p class="w-full text-base font-semibold text-slate-800">
                        Tampilkan
                        <select name="" id=""
                            class="text-slate-800 text-sm font-normal bg-gr border border-gray-300 shadow-md py-2 px-3 pr-2 rounded-md">
                            <option value="OP1">10</option>
                            <option value="OP2">25</option>
                            <option value="OP3">50</option>
                            <option value="OP4">100</option>
                            <option value="OP4">All</option>
                        </select>
                        Data
                    </p>
                </div>
                <!-- Kolom Pencarian -->
                <div class="w-full md:w-1/4 flex items-center mr-4">
                    <input name="" id="" type="text" placeholder="Pencarian"
                        class="text-slate-800 text-sm bg-white border border-gray-300 shadow-md py-2 px-3 gap-5  pr-8 rounded-md w-full mr-5">
                    <button name="" id="" type="submit"
                        class="text-sm font-semibold text-white bg-red-600 border border-gray-300 shadow-md hover:bg-red-700 py-2 px-3  rounded-md">Cari</button>
                </div>
            </div>
            <!-- Bagian Tabel -->
            <div class="relative overflow-x-auto shadow-md">

                <table class="w-full text-sm text-center text-slate-800">

                    <thead class="border border-gray-300 bg-white">
                        <tr>
                            <th scope="col" class="py-3 px-4">No.</th>
                            <th scope="col" class="py-3 px-4">Bulan</th>
                            <th scope="col" class="py-3 px-4">Tahun</th>
                            <th scope="col" class="py-3 px-4">Total BBM Terjual(liter)</th>
                            <th scope="col" class="py-3 px-4">Keuntungan</th>
                        </tr>
                    </thead>
                    <tbody class="border border-gray-300  text-sm font-normal">
                        <?php while ($row = mysqli_fetch_assoc($result_filtered)): ?>

                        <tr>
                            <td scope="col" class="py-2 px-4 bg-custom-gray-100"><?php echo $row['No.']; ?></td>
                            <td scope="col" class="py-2 px-4 bg-custom-gray-100"><?php echo bulann( $row ['Bulan']); ?>
                            </td>
                            <td scope="col" class="py-2 px-4 bg-custom-gray-100"><?php echo $row['Tahun']; ?></td>
                            <td scope="col" class="py-2 px-4 bg-custom-gray-100">
                                <?php echo $row['Total BBM Terjual (liter)']; ?> Liter</td>
                            <td scope="col" class="py-2 px-4 bg-custom-gray-100">Rp.
                                <?php echo number_format($row['Total Keuntungan'], 0, ',', '.'); ?></td>


                        </tr>

                        <?php endwhile; ?>



                    </tbody>
                    <tfoot class="border border-gray-300 bg-white">
                        <tr>
                            <td scope="col" colspan="4" class="text-left py-3 px-5">
                                <h2 class="text-sm font-normal text-slate-800">Menampilkan 1 sampai 5 dari 40 data</h2>
                            </td>
                            <td> <a href="#" class="text-sm font-semibold text-blue-600 hover:underline w-full">Lihat
                                    Grafik</a></td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </main>

</body>
<!-- sidebar -->
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

</html>