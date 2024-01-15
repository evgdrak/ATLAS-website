<?php
    if(!isset($_SESSION))  { 
        session_start(); 
    }

    if(!isset($_SESSION['username']) || ((isset($_SESSION['username']) && $_SESSION['type'] != 'company'))) {
        header("location: javascript:history.go(-1)");
    }

    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);


    // $username = $_SESSION['username'];
    if (isset($_GET["id"])) {
        $ad_id = $_GET["id"];
        $sql1 = "DELETE FROM department_ad WHERE ad_id='$ad_id'";
        $result1 = mysqli_query($conn, $sql1);

        $sql2 = "DELETE FROM ad WHERE id='$ad_id'";
        $result2 = mysqli_query($conn, $sql2);
        
        header("location: company_ads.php");
    }
?>