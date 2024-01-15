<?php

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    if(!isset($_SESSION['username']) || (isset($_SESSION['username']) && $_SESSION['type'] != 'student')) {
        header("location: create_application.php");
    }


    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT a.*, c.company_name, c.address, c.address_number, c.zip_code, c.site, c.company_email, c.company_phone, m.name AS 'municipality', r.name AS 'region' 
                FROM ad a, company c, municipality m, region r 
                WHERE (a.id = $id) AND (a.company_id = c.id) AND (c.municipality_id = m.id) AND (m.region_id = r.id) 
                ORDER BY a.id DESC";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
    }

    unset($_SESSION['saved_info']);
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
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="students.php">Φοιτητές/τριες</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="search.php">Αναζήτηση Θέσεων ΠΑ</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">κωδ. <?php echo $row['id']?></div>
        </div>

        <br><br>
        <h1 style="text-align: center">Δημιουργία Αίτησης</h1>

            <div class="wrapper">
                <!-- <div class="nested"> -->
                    <section class="step-wizard">
                        <ul class="step-wizard-list">
                            <li class="step-wizard-item">
                                <span class="progress-count">1</span>
                                <span class="progress-label">Πληροφορίες Αίτησης</span>
                            </li>
                            <li class="step-wizard-item">
                                <span class="progress-count">2</span>
                                <span class="progress-label">Υποβολή Αίτησης</span>
                            </li>
                        </ul>
                    </section>
                <!-- </div> -->
                
                <h2 style="text-align: center">Η αίτηση σου για τη θέση <?php echo $row['title']?> (κωδ. <?php echo $row['id']?>) - <?php echo $row['company_name']?> υποβλήθηκε επιτυχώς!</h2>

                <h3 style="text-align: center">Μπορείς να παρακολουθήσεις τις αιτήσεις σου από το προφίλ <img alt="black_arrow" src="../img/black_arrow.png" style="width: 18px; margin-left: 5px;"> Αιτήσεις</h3>
                <div><button class="btn1" onclick="location.href='student_applications.php'" style="margin-left: 40%">Αιτήσεις</button></div>
                
                <div class="go-back">
                    <a href="index.php" style="text-decoration: none;">< Επιστροφή στην αρχική σελίδα</a><br><br><br>
                    <a href="search.php" style="text-decoration: none;">< Αναζήτηση νέας θέσης</a>
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
            localStorage.removeItem('pdf_file');
            localStorage.removeItem('reasons');
        }
      </script>
</html>  