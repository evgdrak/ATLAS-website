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
        <link rel="stylesheet" href="../css/register_success.css">
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
        <div class="registration_success">
            <div class="checkmark_box">
                <i class="checkmark">✓</i>
            </div>
            <h1>Η εγγραφή σου ολοκληρώθηκε με επιτυχία!</h1>
            <p>Πήγαινε στην <a href="index.php">αρχική</a> ή κάνε <a href="login.php">σύνδεση</a>.</p>
        </div>
        <br><br><br>
        <?php
            include 'bottom_base.php';
        ?>
    </body>
</html>