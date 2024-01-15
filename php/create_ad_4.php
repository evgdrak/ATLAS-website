<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    if(!isset($_SESSION['username']) || (isset($_SESSION['username']) && $_SESSION['type'] != 'company')) {
        header("location: create_ad.php");
    }

    $title = $_SESSION['saved_info']['title'];
    // echo $title;
    // unset($_SESSION['saved_info']);
?>


<!doctype html>  
<html lang="el">  
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/create_ad_1.css">
    </head>  
    <body onload="cleanStorage()">
        <?php
            include 'top_base.php';
        ?>
        <?php
            include 'nav_bar.php';
        ?>
        
        <div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="index.php">Αρχική Σελίδα </a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="companies.php">Φορείς Υποδοχής</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">Δημιουργία Αγγελίας</div>
        </div>

        <br><br>
        <h1 style="text-align: center">Δημιουργία Αγγελίας</h1>

            <div class="wrapper">
                <!-- <div class="nested"> -->
                    <section class="step-wizard">
                        <ul class="step-wizard-list">
                            <li class="step-wizard-item">
                                <span class="progress-count">1</span>
                                <span class="progress-label">Πληροφορίες Αγγελίας</span>
                            </li>
                            <li class="step-wizard-item">
                                <span class="progress-count">2</span>
                                <span class="progress-label">Επιλογή Τμημάτων</span>
                            </li>
                            <li class="step-wizard-item">
                                <span class="progress-count">3</span>
                                <span class="progress-label">Δημοσίευση Αγγελίας</span>
                            </li>
                        </ul>
                    </section>
                <!-- </div> -->
                
                <h2 style="text-align: center">Η αγγελία σου για τη θέση <?php echo $title ?> δημοσιεύτηκε επιτυχώς!</h2>

                <h3 style="text-align: center">Μπορείς να παρακολουθείς τις αγγελίες σου από το προφίλ <img alt="black_arrow" src="../img/black_arrow.png" style="width: 18px; margin-left: 5px;"> Αγγελίες</h3>
                <div><button class="btn1" onclick="location.href='company_ads.php'" style="margin-left: 40%">Αγγελίες</button></div>
                
                <div class="go-back">
                    <a href="index.php" style="text-decoration: none;">< Επιστροφή στην αρχική</a><br><br><br>
                    <a href="create_ad.php" style="text-decoration: none;">< Δημοσίευση νέας αγγελίας</a>
                </div>
                <br><br>
                
            </div> 
            <br><br><br>
        <?php
            include 'bottom_base.php';
        ?>
      </body>

      <script>
        function cleanStorage(){
            let localStorage = window.localStorage;
            localStorage.removeItem('title');
            localStorage.removeItem('object');
            localStorage.removeItem('type');
            localStorage.removeItem('duration');
            localStorage.removeItem('shift_start');
            localStorage.removeItem('shift_end');
            localStorage.removeItem('start');
            localStorage.removeItem('espa');
            localStorage.removeItem('salary');
            localStorage.removeItem('no_of_vacancies');
            localStorage.removeItem('job_description');
            localStorage.removeItem('required_qualification');
            localStorage.removeItem('wanted_qualification');
            localStorage.removeItem('company_offers');
        }
      </script>
</html>  