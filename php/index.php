<?php
    // If user is already logged in
    // go to home page
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    
    if(isset($_SESSION['saved_info'])){
        unset($_SESSION['saved_info']);
    }

    $no_of_rows = 0;

    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        if($_SESSION['type'] == 'student'){
            $sql = "SELECT sa.id as 'app_id'
                    FROM user u, student s, student_application sa, ad a
                    WHERE (u.username = '$username') AND (s.user_id = u.id) AND (s.id = sa.student_id) AND (sa.ad_id = a.id) AND (sa.company_accepted = false OR sa.company_accepted = true) AND (sa.notification_send = 0) AND (sa.state != 'auto-save')
                    ORDER BY sa.date DESC, a.title";
        
            $result = mysqli_query($conn, $sql);
        } else {
            $sql = "SELECT a.id as 'ad_id'
                    FROM user u, company c, student_application sa, ad a
                    WHERE (u.username = '$username') AND (c.user_id = u.id) AND (c.id = a.company_id) AND (sa.ad_id = a.id) AND (sa.company_accepted IS NULL AND sa.read = false) AND (sa.state != 'auto-save')";
        
            $result = mysqli_query($conn, $sql);
        }
        $no_of_rows = mysqli_num_rows( $result );
    }
    // $search = "";

    // if(isset($_GET['submit'])){
    //     $date = date("Y-m-d");
    //     $sql = "SELECT a.id, a.title, c.company_name, m.name AS 'municipality', r.name AS 'region', a.type, a.duration, a.espa, a.start, a.date 
    //             FROM ad a, company c, municipality m, region r 
    //             WHERE (a.company_id = c.id) AND (c.municipality_id = m.id) AND (m.region_id = r.id) AND (a.start >= '$date')  ";

    //     if ($title != ""){
    //         $sql .= " AND (a.title LIKE '%".$title."%') ";
    //     }

    //     $sql .= " ORDER BY a.id DESC";
        
    //     $search_result = filter($conn, $sql);
    // }

    // function filter($conn, $sql){
    //     $filter_result = mysqli_query($conn, $sql);
    //     return $filter_result;
    // }
?>


<!doctype html>  
<html lang="el">  
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/main.css">
    </head>  
    <body onload="cleanStorage()">
        <?php
            include 'top_base.php';
        ?>
        
        <?php if(isset($_SESSION['username']) && $no_of_rows > 0) :?>
            <div class="notification_alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                <?php if(isset($_SESSION['type']) and $_SESSION['type'] == 'student') : ?>
                    Υπάρχει νέα ειδοποίηση στις <a style="color:black;" href="student_applications.php">αιτήσεις</a> σου.
                <?php else : ?>
                    Υπάρχει νέα ειδοποίηση στις <a style="color:black;" href="company_ads.php">αγγελίες</a> σου.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php
            include 'nav_bar.php';
        ?>
        
        <div style="padding-bottom: 33px">
            <div style="font-size: 12px; float: left; margin: 10px;">Αρχική Σελίδα</div>
        </div>

        <!-- <br> -->
        <div>
            <div class="stud-image">
                <div class="stud-text">
                    <h2 style="font-size: 35px">Είσαι φοιτητής/τρια; Βρες εύκολα και γρήγορα μια θέση πρακτικής άσκησης!</h1>
                    <div class="container">
                        <form action="search.php" method="get">
                            <label class="field" for="title" hidden>Τίτλος Θέσης</label>
                            <input class="inp" type="text" id="title" name="title" placeholder="π.χ. Λογιστής" style="float: left;">
                            <button class="btn" type="sumbit"><img alt="magnifying_glass_solid" src="../img/magnifying-glass-solid.png" style="width: 10%; margin-right: 10px;">Αναζήτηση</button> 
                        </form>
                    </div>
                </div>
                <div style="text-align: justify; font-size: 22px; margin-top: 430px; margin-bottom: 10px; margin-left: 160px;">
                    Είσαι φορέας υποδοχής; Φτιάξε μια νέα <a href="create_ad.php" style="color: black">αγγελία</a>!
                </div>
            </div>

            <div class="wrapper">
                <div class="nested">
                    <div style="font-weight: bold; font-size: 20px;">Φοιτητές/τριες</div>
                    <div><a href="faq_students.php" style="text-decoration: none; color: black;"><img alt="question_mark" src="../img/circle-question-regular.png" style="width: 4%; margin-right: 10px;"> Πώς μπορώ να βρω μια θέση Πρακτικής Άσκησης (ΠΑ);</a></div>
                    <div><a href="faq_students.php" style="text-decoration: none; color: black;"><img alt="question_mark" src="../img/circle-question-regular.png" style="width: 4%; margin-right: 10px;"> Βρήκα κάποιες θέσεις ΠΑ που με ενδιαφέρουν, τι πρέπει να κάνω;</a></div>
                    <div><a href="faq_students.php" style="text-decoration: none; color: black;"><img alt="question_mark" src="../img/circle-question-regular.png" style="width: 4%; margin-right: 10px;"> Πώς θα μάθω αν με δέχτηκαν σε μία θέση;</a></div>
                    <span><h4><a href="faq_students.php" style="text-decoration: none; color: black;">Περισσότερα<img alt="black_arrow" src="../img/black_arrow.png" style="width: 4%; margin-left: 5px;"></h4></a></span>
                </div>
                <div class="nested">
                    <div style="font-weight: bold; font-size: 20px;">Φορείς Υποδοχής</div>
                    <div><a href="faq_companies.php" style="text-decoration: none; color: black;"><img alt="question_mark" src="../img/circle-question-regular.png" style="width: 4%; margin-right: 10px;"> Πώς μπορώ να δημοσιεύσω μια αγγελία;</a></div>
                    <div><a href="faq_companies.php" style="text-decoration: none; color: black;"><img alt="question_mark" src="../img/circle-question-regular.png" style="width: 4%; margin-right: 10px;"> Πώς θα μάθω ποιοι/ες φοιτητές/τριες ενδιαφέρονται για μια θέση;</a></div>
                    <span><h4><a href="faq_companies.php" style="text-decoration: none; color: black;">Περισσότερα<img alt="black_arrow" src="../img/black_arrow.png" style="width: 4%; margin-left: 5px;"></h4></a></span>
                </div>
                <div class="nested">
                    <div style="font-weight: bold; font-size: 20px;">Γραφείο Πρακτικής Άσκησης (ΠΑ)</div>
                    <div><img alt="question_mark" src="../img/circle-question-regular.png" style="width: 4%; margin-right: 10px;"><a href="under_construction.php" style="text-decoration: none; color: black;"> Πώς θα δω ποιες αγγελίες αφορούν το τμήμα μου;</a></div>
                    <div><img alt="question_mark" src="../img/circle-question-regular.png" style="width: 4%; margin-right: 10px;"><a href="under_construction.php" style="text-decoration: none; color: black;"> Πώς μπορώ να ορίσω επόπτη/τρια σε μία εγκεκριμένη αίτηση;</a></div>
                    <span><h4><a href="under_construction.php" style="text-decoration: none; color: black;">Περισσότερα<img alt="black_arrow" src="../img/black_arrow.png" style="width: 4%; margin-left: 5px;"></a></h4></span>
                </div>
            </div>


            <div class="wrapper2">
                <span style="font-weight: bold; font-size: 20px;">Ανακοινώσεις </span>
                <div><li><a href="announcements_5.php" style="text-decoration:none; color:black;">26/09/2022   -   Υποχρεωτικότητα καταγραφής Πρακτικής άσκησης</a></li></div>
                <div><li><a href="announcements_4.php" style="text-decoration:none; color:black;">13/05/2021   -   Επανέρναρξη πρακτικών ασκήσεων με φυσική παρουσία</a></li></div>
                <div><li><a href="announcements_3.php" style="text-decoration:none; color:black;">20/03/2020   -   Επικαιροποίηση προδιαγραφών διασύνδεσης ΠΣ Ιδρυμάτων με το ΑΤΛΑΣ</a></li></div>
                <span><h4><a href="announcements.php" style="text-decoration: none; color: black;">Περισσότερα<img alt="black_arrow" src="../img/black_arrow.png" style="width: 1%; margin-left: 5px;"></a></h4></span>
            </div>

        <?php
            include 'bottom_base.php';
        ?>
      </body>

      <script>
        function cleanStorage(){
            let localStorage = window.localStorage;
            localStorage.removeItem('pdf_file');
            localStorage.removeItem('reasons');
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
            
            localStorage.removeItem('s_title');
            localStorage.removeItem('s_company');
            localStorage.removeItem('s_location');
            localStorage.removeItem('s_type');
            localStorage.removeItem('s_duration');
            localStorage.removeItem('s_start');
            localStorage.removeItem('s_espa');
            localStorage.removeItem('s_object');
            localStorage.removeItem('s_university');
            localStorage.removeItem('s_department');
            localStorage.removeItem('s_department_name');
        }
      </script>
</html>  