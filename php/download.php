<?php
    // If user is already logged in
    // go to home page
    if(!isset($_SESSION))  { 
        session_start(); 
    }

    if(!isset($_SESSION['username'])) {
        header("location: javascript:history.go(-1)");
    }
    

    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    // $username = $_SESSION['username'];

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT sa.file FROM student_application sa WHERE (sa.id = '$id') ";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);

        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="ΒΑΘΜΟΛΟΓΙΑ.pdf"');
        echo $row['file'];
    }

?>