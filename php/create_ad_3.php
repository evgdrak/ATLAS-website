<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    if(!isset($_SESSION['username']) || (isset($_SESSION['username']) && $_SESSION['type'] != 'company')) {
        header("location: create_ad.php");
    }

    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    $username = $_SESSION['username'];
    $sql2 = "SELECT c.company_email, c.company_phone, c.site, c.address, c.address_number, c.zip_code, m.name as 'municipality', r.name as 'region'
            FROM user u, company c, municipality m, region r
            WHERE (u.username = '$username') AND (c.user_id = u.id) AND (c.municipality_id = m.id) AND (m.region_id = r.id)";
    $result2 = mysqli_query($conn, $sql2);
    $row = mysqli_fetch_array($result2);

    if (isset($_POST['submit_btn'])) {
            
            $date = date("Y-m-d");
            $username = $_SESSION['username'];
            
            // $sql = "SELECT a.id FROM ad a, company c WHERE (a.company_id = c.id) AND (c.username = '$username') ORDER BY a.id DESC";
            
            // $result2 = mysqli_query($conn, $sql);
            // $row2 = mysqli_fetch_array($result2);
            // $ad_id = $row2['id'];
            
            $ad_id = $_SESSION['saved_info']['ad_id'];
                    
            $update_sql = "UPDATE ad SET state='Δημοσιευμένη', date='$date' WHERE id='$ad_id'";
            $result3 = mysqli_query($conn, $update_sql);


            header("Location: create_ad_4.php");
    }

?>


<!doctype html>  
<html lang="el">  
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/create_ad_1.css">
    </head>  
    <body>
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
                            <li class="step-wizard-item current-item">
                                <span class="progress-count">3</span>
                                <span class="progress-label">Δημοσίευση Αγγελίας</span>
                            </li>
                        </ul>
                    </section>
                <!-- </div> -->
                
                <h3 style="text-align: center">Προεπισκόπηση Αγγελίας</h3>

                <div class="nested">
                   
                    <div><span style="color:#810000">*</span>Τίτλος Θέσης</div>
                    <div><?php echo $_SESSION['saved_info']['title'] ?></div>

                    <div><span style="color:#810000">*</span>Γνωστικό Αντικείμενο</div>
                    <div><?php echo $_SESSION['saved_info']['object'] ?></div>

                    <div><span style="color:#810000">*</span>Τύπος Απασχόλησης</div>
                    <div><?php echo $_SESSION['saved_info']['type'] ?></div>

                    <div><span style="color:#810000">*</span>Διάρκεια</div>
                    <div><?php echo $_SESSION['saved_info']['duration'] ?> μήνες</div>

                    <div><span style="color:#810000">*</span>Ωράριο</div>
                    <div><?php echo $_SESSION['saved_info']['shift_start'] ?> - <?php echo $_SESSION['saved_info']['shift_end'] ?></div>

                    <div><span style="color:#810000">*</span>Έναρξη ΠΑ μέχρι</div>
                    <div><?php echo $_SESSION['saved_info']['start'] ?></div>

                    <div><span style="color:#810000">*</span>Με/Χωρίς ΕΣΠΑ</div>
                    <div><?php echo $_SESSION['saved_info']['espa'] ?></div>

                    <div><span style="color:#810000">*</span>Μισθός</div>
                    <div>
                        <?php if ($_SESSION['saved_info']['espa'] == 'Με ΕΣΠΑ'){
                            echo "-";
                        } else {
                            echo $_SESSION['saved_info']['salary']. "   €";
                        }?>
                    </div>

                    <div><span style="color:#810000">*</span>Αριθμός Θέσεων</div>
                    <div><?php echo $_SESSION['saved_info']['no_of_vacancies'] ?></div>

                    
                    <div><span style="color:#810000">*</span>Περιγραφή Θέσης</div>
                    <div style="white-space: pre-wrap"><?php echo str_ireplace("\\r\\n", "\r\n", $_SESSION['saved_info']['job_description']); ?>
                    </div>

                    <div><span style="color:#810000">*</span>Απαραίτητα Προσόντα</div>
                    <div style="white-space: pre-wrap"><?php echo str_ireplace("\\r\\n", "\r\n", $_SESSION['saved_info']['required_qualification']); ?>
                    </div>

                    <div><span style="color:#810000">*</span>Επιθυμητά Προσόντα</div>
                    <div style="white-space: pre-wrap"><?php echo str_ireplace("\\r\\n", "\r\n", $_SESSION['saved_info']['wanted_qualification']); ?>
                    </div>

                    <div><span style="color:#810000">*</span>Η εταιρεία προσφέρει</div>
                    <div style="white-space: pre-wrap"><?php echo str_ireplace("\\r\\n", "\r\n", $_SESSION['saved_info']['company_offers']); ?>
                    </div>

                    <div><span style="color:#810000">*</span>Τμήμα</div>
                    <div><?php echo $_SESSION['saved_info']['departments']; ?></div>

                    <div><span style="color:#810000">*</span>Τηλέφωνο Επικοινωνίας</div>
                    <div><?php echo $row['company_phone']?></div>

                    <div><span style="color:#810000">*</span>Email</div>
                    <div><?php echo $row['company_email']?></div>

                    <div><span style="color:#810000">*</span>Ιστοχώρος</div>
                    <div><?php echo $row['site']?></div>

                    
                    <div><span style="color:#810000">*</span>Τοποθεσία</div>
                    <div><?php echo $row['address']. "  ". $row['address_number']. ",  ". $row['zip_code']. ",  ". $row['municipality']. ",  ". $row['region']?></div>
                    
                </div>

                <form action="" method="post">
                    <div><button type="button" class="btn1" onclick="location.href='create_ad_2.php'" style="float: left; background-color: #afb1b1;">Προηγούμενο Βήμα</button>
                        <button name="submit_btn" type="submit" class="btn1" onclick="location.href='create_ad_4.php'" style="float: right; background-color: #6ccd71;">Δημοσίευση Αγγελίας</button>
                    </div>
                </form>
            </div>
            <br><br><br>
        <?php
            include 'bottom_base.php';
        ?>
      </body>
</html>  