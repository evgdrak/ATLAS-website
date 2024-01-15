<?php
    
    // If user is already logged in
    // go to home page
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    if(isset($_SESSION['username'])) {
        header("location: index.php");
    }

?>

<!DOCTYPE html>
<html lang="el">
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/register.css">
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    </head>
    <body>
        <?php
            include 'top_base.php';
        ?>
        <div class="english-flag">
            <a href="under_construction.php" class="nav-links">
                <img src="../img/english_flag.png" alt="english flag">
            </a>
        </div>
        <div class="go-back">
            <a href="index.php" style="text-decoration: none">< Επιστροφή στην αρχική</a>
        </div>
        <div class="form">
            <div class="registration">
                <h2>Εγγραφή</h2>
            </div>
            <div class="box">
                <a class="register-box" href="student_register.php">Θέλω να εγγραφώ ως <b>Φοιτητής/τρια</b></a>
                <a class="register-box" href="company_register.php">Θέλω να εγγραφώ ως <b>Φορέας Υποδοχής</b></a>
                <a class="register-box" href="under_construction.php">Θέλω να εγγραφώ ως <b>Γραφείο ΠΑ</b></a>
            </div>
        </div> 
        <div style="text-align: center; padding: 10px;">
            <p>Έχεις ήδη λογαριασμό; <a href="login.php">Σύνδεση</a>.</p>
        </div> 
        <?php
            include 'bottom_base.php';
        ?>
    </body>
</html>