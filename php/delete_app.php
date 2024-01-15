<?php
    if(!isset($_SESSION))  { 
        session_start(); 
    }

    if(!isset($_SESSION['username']) || (isset($_SESSION['username']) && $_SESSION['type'] != 'student')) {
        header("location: javascript:history.go(-1)");
    }

    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);


    // $username = $_SESSION['username'];
    if (isset($_GET["id"])) {
        $app_id = $_GET["id"];
        $sql = "DELETE FROM student_application WHERE id='$app_id'";
        $result = mysqli_query($conn, $sql);
        
        header("location: student_applications.php");
    }
?>