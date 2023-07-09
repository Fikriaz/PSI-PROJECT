<?php
session_start();
include('connect.php');

$message = ""; // Variabel untuk menyimpan pesan

if (isset($_POST['submit'])) {
    // Get input values from the form
    $jenis_bbm_id = $_POST['jenis_bbm_id'];
    $jumlah = $_POST['jumlah'];
    $tanggal = $_POST['tanggal'];
    $ide = $_SESSION['user_id'];
    // Query to get harga based on jenis_bbm_id
    $query = "SELECT harga FROM jenis_bbm WHERE id = '$jenis_bbm_id'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $harga = $row['harga'];
        $total_harga = $jumlah * $harga;
        $keuntungan = 0.05 * $total_harga;

        // Insert the data into the transaksi table
        $insert = "INSERT INTO transaksi (jenis_bbm_id, user_id, jumlah_liters, total_harga, keuntungan, tanggal_transaksi)
        VALUES ('$jenis_bbm_id',$ide , $jumlah, $total_harga, $keuntungan, '$tanggal')";
        $hasil = $conn->query($insert);

        if ($hasil) {
            $message = "Data berhasil ditambahkan."; // Pesan jika data berhasil ditambahkan
        } else {
            $message = "Gagal menambahkan data."; // Pesan jika data gagal ditambahkan
        }
    } else {
        $message = "Gagal mendapatkan harga dari database.";
    }
    echo $message; // Mengirimkan pesan ke JavaScript
}
?>