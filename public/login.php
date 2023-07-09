<?php
session_start();
include ('connect.php');

if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
    $username = mysqli_real_escape_string($conn, $_REQUEST['username']);
    $password = mysqli_real_escape_string($conn, $_REQUEST['password']);
    $up = mysqli_query($conn, "SELECT * FROM users WHERE username='" . $username . "' AND password='" . $password . "'");
    if ($up) {
        if (mysqli_num_rows($up) > 0) {
            $data = mysqli_fetch_array($up);
            $_SESSION['roles'] = $data['role'];
            $_SESSION['nama'] = $data['username'];
            $_SESSION['user_id'] = $data['id'];
            header("Location:Dashboard.php");
            exit();
        } else {
            header("Location: Login_Page.php?error=Invalid Login Details");
            exit();
        }
    } else {
        header("Location: Login_Page.php?error=Query Error: " . mysqli_error($conn));
        exit();
    }
} else {
    header("Location: Login_Page.php?error=Please Enter Email and Password");
    exit();
}
if (!$up) {
    $message = "Query Error: " . mysqli_error($conn);
    header("Location: Login_Page.php?error=" . urlencode($message));
    exit();
}
?>