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
        $id = $_GET["id"];
        $sql = "SELECT a.title, c.company_name, sa.rejection_reasons FROM student_application sa, ad a, company c WHERE (sa.id = '$id') AND (sa.ad_id = a.id) AND (a.company_id = c.id)";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
    }

?>

<!DOCTYPE html>
<html lang="el">
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/profile.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            .back:hover{
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <?php
            include 'top_base.php';
        ?>
        <?php
            include 'nav_bar.php';
        ?>
        
        <div style="padding-bottom: 20px">
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="index.php">Αρχική Σελίδα</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">Προφίλ</div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">Αιτήσεις</div>
        </div> <br>

        <?php
            include 'profile_menu.php';
        ?>
        <br><br>
        <div><a class="back" onclick="window.close()" style="cursor:pointer; margin-left:60px;">< Πίσω</a></div>

        <div style="margin: 50px; margin-left: 80px;">
            <div><b>Η αίτηση σου για τη θέση "<?php echo $row['title']."  -  ".$row['company_name'] ;?>"  δεν εγκρίθηκε.</b></div><br><br>
            <div class="nested" style="width: 35%;">
            <b style="margin-right: 15px">Λόγοι Απόρριψης:</b>
                <div style="white-space: pre-wrap"><?php echo str_ireplace("\\r\\n", "\r\n", $row['rejection_reasons']);?></div>
            </div>
        </div> 

        <br><br><br><br><br><br><br>
        <?php
            include 'bottom_base.php';
        ?>

        <script>
            document.getElementById("profile_menu_student_applications").classList.add("active");
        </script>
    </body>
</html>