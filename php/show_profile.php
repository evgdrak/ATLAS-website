<?php
    // Starting the session, necessary
    // for using session variables
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    // Logout button will destroy the session, and
    // will unset the session variables
    // User will be headed to 'index.php'
    // after logging out
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        unset($_SESSION['type']);
        header("location: index.php");
    }
?>

<!doctype html>  
<html lang="el">  
    <head>
        <link rel="stylesheet" href="../css/show_profile.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    </head>
    <body>
        <div class="show_profile_bar">
            <div class="show_profile_data">
                <a href="under_construction.php" class="nav-links">
                    <img src="../img/english_flag.png" alt="english flag">
                </a>
            </div>

            <?php if(isset($_SESSION['username'])) : ?>
                <div class="profile_dropdown" style="float:right;">
                    <button class="profile_dropbtn" onclick="location.href='profile.php'"><i class="fa fa-fw fa-user"></i><?php echo $_SESSION['username'] ?></button>
                    <div class="profile_dropdown-content">
                        <a href="profile.php"><i style='font-size:12px' class='fa fa-fw fa-user'></i> Στοιχεία Λογαριασμού</a>

                        <?php if(isset($_SESSION['type']) and $_SESSION['type'] == 'student') : ?>
                            <a href="student_applications.php"><i style='font-size:12px' class='fas'>&#xf15c;</i> Αιτήσεις</a>
                        <?php else : ?>
                            <a href="company_ads.php"><i style='font-size:12px' class='fas'>&#xf15c;</i> Αγγελίες</a>
                        <?php endif; ?>

                        <a href="index.php?logout='1'"><i style='font-size:12px' class='fas'>&#xf2f6;</i> Αποσύνδεση</a>
                    </div>
                </div class="show_profile_data">
            <?php else : ?>
                <div class="show_profile_data">
                    <a href="login.php">Είσοδος</a>
                    /
                    <a href="choose_registration.php">Εγγραφή</a>
                </div class="show_profile_data">
            <?php endif; ?>

            <div class="show_profile_data">
                <a href="contact.php">Επικοινωνία</a>
            </div>
        </div>
      </body>
</html>