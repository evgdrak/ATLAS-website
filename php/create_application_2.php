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
        $sql = "SELECT a.title, c.company_name FROM ad a, company c WHERE (a.id = $id) AND (a.company_id = c.id)";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
    }

    $username = $_SESSION['username'];
    $sql2 = "SELECT u.id, u.firstname, u.surname, u.phone, u.email FROM user u WHERE (u.username = '$username')";
    $result2 = mysqli_query($conn, $sql2);
    $stud = mysqli_fetch_array($result2);



    if (isset($_POST['submit_btn'])) {
        
        // if ( $_SESSION['saved_info']['clicked'] == 0 ) { //
            
            $date = date("Y-m-d");
            $username = $_SESSION['username'];
            
            $sql = "SELECT s.id as 'stud_id', a.id as 'app_id' FROM user u, student s, student_application a
                    WHERE (u.username = '$username') AND (s.user_id = u.id) AND (s.id = a.student_id) AND (a.ad_id = '$id')
                    ORDER BY a.id DESC";
            
            $result2 = mysqli_query($conn, $sql);
            $row2 = mysqli_fetch_array($result2);
            $stud_id = $row2['stud_id'];
            $app_id = $row2['app_id'];
                    
                    
            $update_sql = "UPDATE student_application SET state='Σε εκκρεμότητα', date='$date' WHERE id='$app_id'";
            $result3 = mysqli_query($conn, $update_sql);

            unset($_SESSION['saved_info']);

            header("Location: create_application_3.php?id=$id");

        // } 
    }


?>

<!doctype html>  
<html lang="el">  
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/create_application_2.css">
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
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="students.php">Φοιτητές/τριες</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="search.php">Αναζήτηση Θέσεων ΠΑ</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">κωδ. <?php echo $id?></div>
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
                            <li class="step-wizard-item current-item">
                                <span class="progress-count">2</span>
                                <span class="progress-label">Υποβολή Αίτησης</span>
                            </li>
                        </ul>
                    </section> 
                <!-- </div> -->
                <div class="nested">
                    
                   
                    <div style="text-align:right"><b>Αίτηση για Πρακτική Άσκηση (ΠΑ) στη θέση:</b></div>
                    <div><?php echo $row['title']?> - <?php echo $row['company_name']?></div>

                    <!-- <div class="view"><b>Προεπισκόπηση Αίτησης:</b></div> -->
                    <div class="list" style="text-align:right">Ονοματεπώνυμο:</div>
                    <div><?php echo $stud['firstname'] . "  " . $stud['surname'];?></div>

                    <div class="list" style="text-align:right">Στοιχεία Επικοινωνίας:</div>
                    <div><?php echo $stud['phone']. ", " . $stud['email'];?></div>

                    <div class="list" style="text-align:right">Αναλυτική Βαθμολογία:</div>
                    <div><?php echo $_SESSION['saved_info']['file_name'];?></div>

                    <div class="list" style="text-align:right">Η θέση με ενδιαφέρει γιατί:</div>
                    <div style="white-space: pre-wrap"><?php echo str_ireplace("\\r\\n", "\r\n", $_SESSION['saved_info']['reasons']);?></div>
                </div>

                <form action="" method="post">
                    <div><button class="btn1" type="button" onclick="location.href='create_application.php?id=<?php echo $id; ?>'" style="float: left; background-color: #afb1b1;">Προηγούμενο Βήμα</button>
                        <button name="submit_btn" type="submit" class="btn1" style="float: right; background-color: #6ccd71;">Υποβολή Αίτησης</button>
                    </div> 
                </form>
            </div>
            <br><br><br>
        <?php
            include 'bottom_base.php';
        ?>
      </body>
</html>  