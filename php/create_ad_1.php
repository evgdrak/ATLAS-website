<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    if(!isset($_SESSION['username']) || (isset($_SESSION['username']) && $_SESSION['type'] != 'company')) {
        header("location: create_ad.php");
    }

    if(!isset($_SESSION['saved_info'])){
        $_SESSION['saved_info']['clicked_1'] = 0;
        // $_SESSION['saved_info']['clicked_2'] = 0; 
    }
    

    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    if (isset($_GET["id"])) {
        $ad_id = $_GET["id"];
        $sql = "SELECT * FROM ad a WHERE (a.id = '$ad_id')";
        $result = mysqli_query($conn, $sql);
        $rowA = mysqli_fetch_array($result);
        $_SESSION['saved_info']['clicked_1'] = 1;
        ?>
        <script>
            console.log("here");
            localStorage.setItem('title', '<?php echo $rowA['title']; ?>');
            localStorage.setItem('object', '<?php echo $rowA['object']; ?>');
            localStorage.setItem('type', '<?php echo $rowA['type']; ?>');
            localStorage.setItem('duration', '<?php echo $rowA['duration']; ?>');
            localStorage.setItem('shift_start', '<?php echo $rowA['shift_start']; ?>');
            localStorage.setItem('shift_end', '<?php echo $rowA['shift_end']; ?>');
            localStorage.setItem('start', '<?php echo $rowA['start']; ?>');
            localStorage.setItem('espa', '<?php echo $rowA['espa']; ?>');
            localStorage.setItem('salary', '<?php echo $rowA['salary']; ?>');
            localStorage.setItem('no_of_vacancies', '<?php echo $rowA['no_of_vacancies']; ?>');
            localStorage.setItem('job_description', '<?php echo $rowA['job_description']; ?>');
            localStorage.setItem('required_qualification', '<?php echo $rowA['required_qualification']; ?>');
            localStorage.setItem('wanted_qualification', '<?php echo $rowA['wanted_qualification']; ?>');
            localStorage.setItem('company_offers', '<?php echo $rowA['company_offers']; ?>');
            getLocalStoredValue();
        </script>
        
        <?php
    } 

    if (isset($_POST['save_btn']) ) {
        if ( (isset($_SESSION['saved_info']['clicked_1'])) && ($_SESSION['saved_info']['clicked_1'] == 0)) { //
            $title = $_POST['title'];
            $object = $_POST['object'];
            $type = $_POST['type'];
            $duration = $_POST['duration'];
            $shift_start = $_POST['shift_start'];
            $shift_end = $_POST['shift_end'];
            $start = $_POST['start'];
            if ($start === ""){
                $start = date("Y-m-d");
            }
            $espa = $_POST['espa'];
            if ( $espa == 'Με ΕΣΠΑ'){
                $salary = 0;
            } else if(isset($_POST['salary'])) {
                $salary = $_POST['salary'];
            }
            $no_of_vacancies = $_POST['no_of_vacancies'];
            $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
            $required_qualification = mysqli_real_escape_string($conn, $_POST['required_qualification']);
            $wanted_qualification = mysqli_real_escape_string($conn, $_POST['wanted_qualification']);
            $company_offers = mysqli_real_escape_string($conn, $_POST['company_offers']);
            ?>
            <script>
                // let localStorage = window.localStorage;
                let newValue = '<?php echo $title; ?>';
                localStorage.setItem('title', newValue);

                newValue = '<?php echo $object; ?>';
                localStorage.setItem('object', newValue);

                newValue = '<?php echo $type; ?>';
                localStorage.setItem('type', newValue);

                newValue = '<?php echo $duration; ?>';
                localStorage.setItem('duration', newValue);

                newValue = '<?php echo $shift_start; ?>';
                localStorage.setItem('shift_start', newValue);

                newValue = '<?php echo $shift_end; ?>';
                localStorage.setItem('shift_end', newValue);

                newValue = '<?php echo $start; ?>';
                localStorage.setItem('start', newValue);

                newValue = '<?php echo $espa; ?>';
                localStorage.setItem('espa', newValue);

                newValue = '<?php echo $salary; ?>';
                localStorage.setItem('salary', newValue);

                newValue = '<?php echo $no_of_vacancies; ?>';
                localStorage.setItem('no_of_vacancies', newValue);

                newValue = '<?php echo $job_description; ?>';
                localStorage.setItem('job_description', newValue);

                newValue = '<?php echo $required_qualification; ?>';
                localStorage.setItem('required_qualification', newValue);

                newValue = '<?php echo $wanted_qualification; ?>';
                localStorage.setItem('wanted_qualification', newValue);

                newValue = '<?php echo $company_offers; ?>';
                localStorage.setItem('company_offers', newValue);
            </script>

            <?php
            $date = date("Y-m-d");
            $username = $_SESSION['username'];
            $sql = "SELECT c.id FROM user u, company c WHERE (u.username = '$username') AND (c.user_id = u.id)";
            // echo $sql;
            $result2 = mysqli_query($conn, $sql);
            $row2 = mysqli_fetch_array($result2);
            $comp_id = $row2['id'];
            $state = "Προσωρινά Αποθηκευμένη"; 
                    
            $insert_sql = "INSERT INTO `ad`(`title`, `type`, `duration`, `shift_start`, `shift_end`, `start`, `espa`, `salary`, `no_of_vacancies`, `job_description`, `required_qualification`, `wanted_qualification`, `company_offers`, `state`, `date`, `company_id`, `object`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param('ssssssssissssssss', $title, $type, $duration, $shift_start, $shift_end, $start, $espa, $salary, $no_of_vacancies, $job_description, $required_qualification, $wanted_qualification, $company_offers, $state, $date, $comp_id, $object);
            $stmt->execute();

            $_SESSION['saved_info']['clicked_1'] = 1;
            $_SESSION['saved_info']['title'] = $title;
            $_SESSION['saved_info']['object'] = $object;
            $_SESSION['saved_info']['type'] = $type;
            $_SESSION['saved_info']['duration'] = $duration;
            $_SESSION['saved_info']['shift_start'] = $shift_start;
            $_SESSION['saved_info']['shift_end'] = $shift_end;
            $_SESSION['saved_info']['start'] = $start;
            $_SESSION['saved_info']['espa'] = $espa;
            $_SESSION['saved_info']['salary'] = $salary;
            $_SESSION['saved_info']['no_of_vacancies'] = $no_of_vacancies;
            $_SESSION['saved_info']['job_description'] = $job_description;
            $_SESSION['saved_info']['required_qualification'] = $required_qualification;
            $_SESSION['saved_info']['wanted_qualification'] = $wanted_qualification;
            $_SESSION['saved_info']['company_offers'] = $company_offers;


            echo '<script>alert("Έγινε προσωρινή αποθήκευση.")</script>';

        } else if ( (isset($_SESSION['saved_info']['clicked_1'])) && ($_SESSION['saved_info']['clicked_1'] == 1)){

            $title = $_POST['title'];
            $object = $_POST['object'];
            $type = $_POST['type'];
            $duration = $_POST['duration'];
            $shift_start = $_POST['shift_start'];
            $shift_end = $_POST['shift_end'];
            if(isset($_POST['start'])) {
                $start = $_POST['start'];
            }
            $espa = $_POST['espa'];
            if ( $espa == 'Με ΕΣΠΑ'){
                $salary = 0;
            } else if(isset($_POST['salary'])) {
                $salary = $_POST['salary'];
            }
            $no_of_vacancies = $_POST['no_of_vacancies'];
            $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
            $required_qualification = mysqli_real_escape_string($conn, $_POST['required_qualification']);
            $wanted_qualification = mysqli_real_escape_string($conn, $_POST['wanted_qualification']);
            $company_offers = mysqli_real_escape_string($conn, $_POST['company_offers']);
            ?>

            <script>
                // let localStorage = window.localStorage;
                let newValue = '<?php echo $title; ?>';
                localStorage.setItem('title', newValue);

                newValue = '<?php echo $object; ?>';
                localStorage.setItem('object', newValue);

                newValue = '<?php echo $type; ?>';
                localStorage.setItem('type', newValue);

                newValue = '<?php echo $duration; ?>';
                localStorage.setItem('duration', newValue);

                newValue = '<?php echo $shift_start; ?>';
                localStorage.setItem('shift_start', newValue);

                newValue = '<?php echo $shift_end; ?>';
                localStorage.setItem('shift_end', newValue);

                newValue = '<?php echo $start; ?>';
                localStorage.setItem('start', newValue);

                newValue = '<?php echo $espa; ?>';
                localStorage.setItem('espa', newValue);

                newValue = '<?php echo $salary; ?>';
                localStorage.setItem('salary', newValue);

                newValue = '<?php echo $no_of_vacancies; ?>';
                localStorage.setItem('no_of_vacancies', newValue);

                newValue = '<?php echo $job_description; ?>';
                localStorage.setItem('job_description', newValue);

                newValue = '<?php echo $required_qualification; ?>';
                localStorage.setItem('required_qualification', newValue);

                newValue = '<?php echo $wanted_qualification; ?>';
                localStorage.setItem('wanted_qualification', newValue);

                newValue = '<?php echo $company_offers; ?>';
                localStorage.setItem('company_offers', newValue);
            </script>

            <?php
            $date = date("Y-m-d");
            $username = $_SESSION['username'];
            $state = "Προσωρινά Αποθηκευμένη"; 

            if (!isset($_GET["id"])){
                $sql = "SELECT a.id FROM ad a, user u, company c  WHERE (u.username = '$username') AND (c.user_id = u.id) AND (a.company_id = c.id) ORDER BY a.id DESC";
            
                // echo $sql;
                $result2 = mysqli_query($conn, $sql);
                $row2 = mysqli_fetch_array($result2);
                $ad_id = $row2['id'];
                // $state = "Προσωρινά Αποθηκευμένη";
            }

            $update_sql = "UPDATE ad SET title=?, object=?, type=?, duration=?, shift_start=?, shift_end=?, start=?, espa=?, salary=?,
                        no_of_vacancies=?, job_description=?, required_qualification=?, wanted_qualification=?, company_offers=?, state=?, date=? 
                        WHERE id=?";

            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param('sssssssssisssssss', $title, $object, $type, $duration, $shift_start, $shift_end, $start, $espa, $salary, $no_of_vacancies, $job_description, $required_qualification, $wanted_qualification, $company_offers, $state, $date, $ad_id);
            $stmt->execute();

            $_SESSION['saved_info']['title'] = $title;
            $_SESSION['saved_info']['object'] = $object;
            $_SESSION['saved_info']['type'] = $type;
            $_SESSION['saved_info']['duration'] = $duration;
            $_SESSION['saved_info']['shift_start'] = $shift_start;
            $_SESSION['saved_info']['shift_end'] = $shift_end;
            $_SESSION['saved_info']['start'] = $start;
            $_SESSION['saved_info']['espa'] = $espa;
            $_SESSION['saved_info']['salary'] = $salary;
            $_SESSION['saved_info']['no_of_vacancies'] = $no_of_vacancies;
            $_SESSION['saved_info']['job_description'] = $job_description;
            $_SESSION['saved_info']['required_qualification'] = $required_qualification;
            $_SESSION['saved_info']['wanted_qualification'] = $wanted_qualification;
            $_SESSION['saved_info']['company_offers'] = $company_offers;

            echo '<script>alert("Έγινε προσωρινή αποθήκευση.")</script>';
        }
    }

    if (isset($_POST['next_btn']) ) {
        if ( (isset($_SESSION['saved_info']['clicked_1'])) && ($_SESSION['saved_info']['clicked_1'] == 0)) { //
            $title = $_POST['title'];
            $object = $_POST['object'];
            $type = $_POST['type'];
            $duration = $_POST['duration'];
            $shift_start = $_POST['shift_start'];
            $shift_end = $_POST['shift_end'];
            
            if(isset($_POST['start'])) {
                $start = $_POST['start'];
            }
            $espa = $_POST['espa'];
            if ( $espa == 'Με ΕΣΠΑ'){
                $salary = 0;
            } else if(isset($_POST['salary'])) {
                $salary = $_POST['salary'];
            }
            $no_of_vacancies = $_POST['no_of_vacancies'];
            $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
            $required_qualification = mysqli_real_escape_string($conn, $_POST['required_qualification']);
            $wanted_qualification = mysqli_real_escape_string($conn, $_POST['wanted_qualification']);
            $company_offers = mysqli_real_escape_string($conn, $_POST['company_offers']);
            ?>

            <script>
                // let localStorage = window.localStorage;
                let newValue = '<?php echo $title; ?>';
                localStorage.setItem('title', newValue);

                newValue = '<?php echo $object; ?>';
                localStorage.setItem('object', newValue);

                newValue = '<?php echo $type; ?>';
                localStorage.setItem('type', newValue);

                newValue = '<?php echo $duration; ?>';
                localStorage.setItem('duration', newValue);

                newValue = '<?php echo $shift_start; ?>';
                localStorage.setItem('shift_start', newValue);

                newValue = '<?php echo $shift_end; ?>';
                localStorage.setItem('shift_end', newValue);

                newValue = '<?php echo $start; ?>';
                localStorage.setItem('start', newValue);

                newValue = '<?php echo $espa; ?>';
                localStorage.setItem('espa', newValue);

                newValue = '<?php echo $salary; ?>';
                localStorage.setItem('salary', newValue);

                newValue = '<?php echo $no_of_vacancies; ?>';
                localStorage.setItem('no_of_vacancies', newValue);

                newValue = '<?php echo $job_description; ?>';
                localStorage.setItem('job_description', newValue);

                newValue = '<?php echo $required_qualification; ?>';
                localStorage.setItem('required_qualification', newValue);

                newValue = '<?php echo $wanted_qualification; ?>';
                localStorage.setItem('wanted_qualification', newValue);

                newValue = '<?php echo $company_offers; ?>';
                localStorage.setItem('company_offers', newValue);
            </script>

            <?php
            $date = date("Y-m-d");
            $username = $_SESSION['username'];
            $sql = "SELECT c.id FROM user u, company c WHERE (u.username = '$username') AND (c.user_id = u.id)";
            // echo $sql;
            $result2 = mysqli_query($conn, $sql);
            $row2 = mysqli_fetch_array($result2);
            $comp_id = $row2['id'];
            $state = "auto-save";
                    
            $insert_sql = "INSERT INTO ad(title, type, duration, shift_start, shift_end, start, espa, salary, no_of_vacancies, job_description, required_qualification, wanted_qualification, company_offers, state, date, company_id, object) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param('ssssssssissssssss', $title, $type, $duration, $shift_start, $shift_end, $start, $espa, $salary, $no_of_vacancies, $job_description, $required_qualification, $wanted_qualification, $company_offers, $state, $date, $comp_id, $object);
            $stmt->execute();

            $_SESSION['saved_info']['clicked_1'] = 1;
            $_SESSION['saved_info']['title'] = $title;
            $_SESSION['saved_info']['object'] = $object;
            $_SESSION['saved_info']['type'] = $type;
            $_SESSION['saved_info']['duration'] = $duration;
            $_SESSION['saved_info']['shift_start'] = $shift_start;
            $_SESSION['saved_info']['shift_end'] = $shift_end;
            $_SESSION['saved_info']['start'] = $start;
            $_SESSION['saved_info']['espa'] = $espa;
            $_SESSION['saved_info']['salary'] = $salary;
            $_SESSION['saved_info']['no_of_vacancies'] = $no_of_vacancies;
            $_SESSION['saved_info']['job_description'] = $job_description;
            $_SESSION['saved_info']['required_qualification'] = $required_qualification;
            $_SESSION['saved_info']['wanted_qualification'] = $wanted_qualification;
            $_SESSION['saved_info']['company_offers'] = $company_offers;
            ?>
            <script>location.href="create_ad_2.php";</script>
            <?php

            // header("Location: create_ad_2.php");

        } else if ( (isset($_SESSION['saved_info']['clicked_1'])) && ($_SESSION['saved_info']['clicked_1'] == 1)){

            $title = $_POST['title'];
            $object = $_POST['object'];
            $type = $_POST['type'];
            $duration = $_POST['duration'];
            $shift_start = $_POST['shift_start'];
            $shift_end = $_POST['shift_end'];
            
            if(isset($_POST['start'])) {
                $start = $_POST['start'];
            }
            $espa = $_POST['espa'];
            if ( $espa == 'Με ΕΣΠΑ'){
                $salary = 0;
            } else {
                $salary = $_POST['salary'];
            }
            $no_of_vacancies = $_POST['no_of_vacancies'];
            $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
            $required_qualification = mysqli_real_escape_string($conn, $_POST['required_qualification']);
            $wanted_qualification = mysqli_real_escape_string($conn, $_POST['wanted_qualification']);
            $company_offers = mysqli_real_escape_string($conn, $_POST['company_offers']);
            ?>

            <script>
                // let localStorage = window.localStorage;
                let newValue = '<?php echo $title; ?>';
                localStorage.setItem('title', newValue);

                newValue = '<?php echo $object; ?>';
                localStorage.setItem('object', newValue);

                newValue = '<?php echo $type; ?>';
                localStorage.setItem('type', newValue);

                newValue = '<?php echo $duration; ?>';
                localStorage.setItem('duration', newValue);

                newValue = '<?php echo $shift_start; ?>';
                localStorage.setItem('shift_start', newValue);

                newValue = '<?php echo $shift_end; ?>';
                localStorage.setItem('shift_end', newValue);

                newValue = '<?php echo $start; ?>';
                localStorage.setItem('start', newValue);

                newValue = '<?php echo $espa; ?>';
                localStorage.setItem('espa', newValue);

                newValue = '<?php echo $salary; ?>';
                localStorage.setItem('salary', newValue);

                newValue = '<?php echo $no_of_vacancies; ?>';
                localStorage.setItem('no_of_vacancies', newValue);

                newValue = '<?php echo $job_description; ?>';
                localStorage.setItem('job_description', newValue);

                newValue = '<?php echo $required_qualification; ?>';
                localStorage.setItem('required_qualification', newValue);

                newValue = '<?php echo $wanted_qualification; ?>';
                localStorage.setItem('wanted_qualification', newValue);

                newValue = '<?php echo $company_offers; ?>';
                localStorage.setItem('company_offers', newValue);
            </script>

            <?php
            $date = date("Y-m-d");
            $username = $_SESSION['username'];

            if (!isset($_GET["id"])){
                $sql = "SELECT a.id FROM ad a, user u, company c  WHERE (u.username = '$username') AND (c.user_id = u.id) AND (a.company_id = c.id) ORDER BY a.id DESC";
                // echo $sql;
                $result2 = mysqli_query($conn, $sql);
                $row2 = mysqli_fetch_array($result2);
                $ad_id = $row2['id'];
                // $state = "Προσωρινά Αποθηκευμένη";
            }

            

            $update_sql = "UPDATE ad SET title=?, object=?, type=?, duration=?, shift_start=?, shift_end=?, start=?, espa=?, salary=?,
                        no_of_vacancies=?, job_description=?, required_qualification=?, wanted_qualification=?, company_offers=?, date=?  
                        WHERE id=?";

            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param('ssssssssssssssss', $title, $object, $type, $duration, $shift_start, $shift_end, $start, $espa, $salary, $no_of_vacancies, $job_description, $required_qualification, $wanted_qualification, $company_offers, $date, $ad_id);
            $stmt->execute();

            $_SESSION['saved_info']['title'] = $title;
            $_SESSION['saved_info']['object'] = $object;
            $_SESSION['saved_info']['type'] = $type;
            $_SESSION['saved_info']['duration'] = $duration;
            $_SESSION['saved_info']['shift_start'] = $shift_start;
            $_SESSION['saved_info']['shift_end'] = $shift_end;
            $_SESSION['saved_info']['start'] = $start;
            $_SESSION['saved_info']['espa'] = $espa;
            $_SESSION['saved_info']['salary'] = $salary;
            $_SESSION['saved_info']['no_of_vacancies'] = $no_of_vacancies;
            $_SESSION['saved_info']['job_description'] = $job_description;
            $_SESSION['saved_info']['required_qualification'] = $required_qualification;
            $_SESSION['saved_info']['wanted_qualification'] = $wanted_qualification;
            $_SESSION['saved_info']['company_offers'] = $company_offers;
            ?>
            <script>location.href="create_ad_2.php";</script>
            <?php
            // header("Location: create_ad_2.php");
        }
    }

?>


<!doctype html>  
<html lang="el">  
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/create_ad_1.css">
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    </head>  
    <body onload="getLocalStoredValue()">
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
                            <!-- <li class="step-wizard-item">
                                <span class="progress-count">1</span>
                                <span class="progress-label">Πληροφορίες Αγγελίας</span>
                            </li> -->
                            <li class="step-wizard-item current-item">
                                <span class="progress-count">1</span>
                                <span class="progress-label">Πληροφορίες Αγγελίας</span>
                            </li>
                            <li class="step-wizard-item next-item">
                                <span class="progress-count">2</span>
                                <span class="progress-label">Επιλογή Τμημάτων</span>
                            </li>
                            <li class="step-wizard-item next-item">
                                <span class="progress-count">3</span>
                                <span class="progress-label">Δημοσίευση Αγγελίας</span>
                            </li>
                        </ul>
                    </section>
                <!-- </div> -->
                <div style="margin-left: 150px">Τα πεδία με <span style="color:#810000">*</span> είναι υποχρεωτικά.</div>
                <form action="" method="post">
                   
                    <div class="nested">
                        
                            <div><label for="title"><span style="color:#810000">*</span>Τίτλος Θέσης</label></div>
                            <div>
                                <input type="text" class="inp" name="title" id="title"  value="">
                                <span class="error" id="title_error"></span>
                            </div>

                            <div><label for="object"><span style="color:#810000">*</span>Γνωστικό Αντικείμενο</label></div>
                            <div><select name="object" id="object" style="width: 40%">
                                    <option value="" selected="selected" hidden>Επιλογή Αντικειμένου</option>
                                    <option value="Ανθρώπινο Δυναμικό">Ανθρώπινο Δυναμικό</option>
                                    <option value="Βοηθητικό Προσωπικό">Βοηθητικό Προσωπικό</option>
                                    <option value="Δημόσιες Σχέσεις">Δημόσιες Σχέσεις</option>
                                    <option value="Διοίκηση">Διοίκηση</option>
                                    <option value="Εκπαίδευση">Εκπαίδευση</option>
                                    <option value="Εξυπηρέτηση Πελατών">Εξυπηρέτηση Πελατών</option>
                                    <option value="Ιατρική">Ιατρική</option>
                                    <option value="Μάρκετινγκ">Μάρκετινγκ</option>
                                    <option value="Μηχανικός">Μηχανικός</option>
                                    <option value="Νομική">Νομική</option>
                                    <option value="Οικονομικά">Οικονομικά</option>
                                    <option value="Πληροφορική">Πληροφορική</option>
                                    <option value="Πωλήσεις">Πωλήσεις</option>
                                    <option value="Σύμβουλος">Σύμβουλος</option>
                                </select>
                                <span class="error" id="object_error"></span>
                            </div>

                            <div><label for="type"><span style="color:#810000">*</span>Τύπος Απασχόλησης</label></div>
                            <div><select name="type" id="type">
                                    <option value="" selected="selected" hidden>Επιλογή</option>
                                    <option value="Πλήρης">Πλήρης</option>
                                    <option value="Μερική">Μερική</option>
                                </select>
                                <span class="error" id="type_error"></span>
                            </div>

                            <div><label for="duration"><span style="color:#810000">*</span>Διάρκεια</label></div>
                            <div><select name="duration" id="duration">
                                    <option value="" selected="selected" hidden>Επιλογή</option>
                                    <option value="3">3 μήνες</option>
                                    <option value="6">6 μήνες</option>
                                </select>
                                <span class="error" id="duration_error"></span>
                            </div>

                            <div><span style="color:#810000">*</span>Ωράριο</div>
                            <div>
                                <label for="shift_start" hidden>Αρχή</label>
                                <input type="time" class="inp" name="shift_start" id="shift_start" style="width: 130px;">
                                -
                                <label for="shift_end" hidden>Τέλος</label>
                                <input type="time" class="inp" name="shift_end" id="shift_end" style="width: 130px;">
                                <span class="error" id="shift_error"></span>
                            </div>

                            <div><label for="start"><span style="color:#810000">*</span>Έναρξη ΠΑ μέχρι</label></div>
                            <div>
                                <input type="date" class="inp" name="start" id="start" min="<?php echo date("Y-m-d"); ?>" style="width: 150px;">
                                <span class="error" id="start_error"></span>
                            </div>

                            <div><label for="espa"><span style="color:#810000">*</span>Με/Χωρίς ΕΣΠΑ</label></div>
                            <div><select name="espa" id="espa">
                                    <option value="" selected="selected" hidden>Επιλογή</option>
                                    <option value="Με ΕΣΠΑ">Με ΕΣΠΑ</option>
                                    <option value="Χωρίς ΕΣΠΑ">Χωρίς ΕΣΠΑ</option>
                                </select>
                                <span class="error" id="espa_error"></span>
                            </div>

                            <div><label for="salary"><span style="color:#810000">*</span>Μισθός</label></div>
                            <div>
                                <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="inp" placeholder="-" name="salary" value="" id="salary" style="width: 70px; margin-right: 10px;" min="500" disabled>€
                                <span class="error" id="salary_error"></span>
                            </div>

                            <div><label for="no_of_vacancies"><span style="color:#810000">*</span>Αριθμός Θέσεων</label></div>
                            <div><select name="no_of_vacancies" id="no_of_vacancies">
                                    <option value="" selected="selected" hidden>Επιλογή</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                                <span class="error" id="no_of_vacancies_error"></span>
                            </div>

                            <div><label for="job_description"><span style="color:#810000">*</span>Περιγραφή Θέσης</label></div>
                            <div>
                                <textarea id="job_description" name="job_description" value="" rows="7" cols="70"></textarea>
                                <span class="error" id="job_description_error"></span>
                            </div>

                            <div><label for="required_qualification"><span style="color:#810000">*</span>Απαραίτητα Προσόντα</label></div>
                            <div>
                                <textarea id="required_qualification" name="required_qualification" value="" rows="7" cols="70"></textarea>
                                <span class="error" id="required_qualification_error"></span>
                            </div>

                            <div><label for="wanted_qualification"><span style="color:#810000">*</span>Επιθυμητά Προσόντα</label></div>
                            <div>
                                <textarea id="wanted_qualification" name="wanted_qualification" value="" rows="7" cols="70"></textarea>
                                <span class="error" id="wanted_qualification_error"></span>
                            </div>

                            <div><label for="company_offers"><span style="color:#810000">*</span>Η εταιρεία προσφέρει</label></div>
                            <div>
                                <textarea id="company_offers" name="company_offers" value="" rows="7" cols="70"></textarea>
                                <span class="error" id="company_offers_error"></span>
                            </div>
                        
                    </div><br><br><br>
                    
                    <div>
                        <button class="btn1" type="button" onclick="location.href='create_ad.php'" style="float: left; background-color: #afb1b1;">Ακύρωση</button>
                        <button type="submit" class="btn1" name="save_btn" style="background-color: #afb1b1;">Προσωρινή Αποθήκευση</button>
                        <button onclick="location.href='create_ad_2.php'" class="btn1" name="next_btn" id="next_btn"  style="float: right; background-color: #6ccd71;" disabled>Επόμενο Βήμα</button>
                    </div>
                </form>
                
            </div>
            <br><br><br>
        <?php
            include 'bottom_base.php';
        ?>
      </body>


      <script>

        let title = document.getElementById("title");
        let object = document.getElementById("object");
        let type = document.getElementById("type");
        let duration = document.getElementById("duration");
        let shift_start = document.getElementById("shift_start");
        let shift_end = document.getElementById("shift_end");
        let start = document.getElementById("start");
        let espa = document.getElementById("espa");
        let salary = document.getElementById("salary");
        let no_of_vacancies = document.getElementById("no_of_vacancies");
        let job_description = document.getElementById("job_description");
        let required_qualification = document.getElementById("required_qualification");
        let wanted_qualification = document.getElementById("wanted_qualification");
        let company_offers = document.getElementById("company_offers");

        let localStorage = window.localStorage;
        let correct_fields = [false, false, false, false, false, false, false, false, false, false, false, false, false, false];

        title.addEventListener("change", (event) => {
            let newValue = title.value;
            localStorage.setItem('title', newValue);
        });

        object.addEventListener("change", (event) => {
            let newValue = object.value;
            localStorage.setItem('object', newValue);
        });

        type.addEventListener("change", (event) => {
            let newValue = type.value;
            localStorage.setItem('type', newValue);
        });

        duration.addEventListener("change", (event) => {
            let newValue = duration.value;
            localStorage.setItem('duration', newValue);
        });

        shift_start.addEventListener("change", (event) => {
            let newValue = shift_start.value;
            localStorage.setItem('shift_start', newValue);
        });

        shift_end.addEventListener("change", (event) => {
            let newValue = shift_end.value;
            localStorage.setItem('shift_end', newValue);
        });

        start.addEventListener("change", (event) => {
            let newValue = start.value;
            localStorage.setItem('start', newValue);
        });
        
        espa.addEventListener("change", (event) => {

            let newValue = espa.value;
            localStorage.setItem('espa', newValue);

            if(espa.value == "Με ΕΣΠΑ") {
                document.getElementById("salary").disabled = true;
                document.getElementById("salary").placeholder = "-";
                document.getElementById("salary").value = "";
                document.getElementById('salary_error').innerHTML = "";
                salary.style.border = "2px solid transparent";
            } else {
                document.getElementById("salary").disabled = false;
                document.getElementById("salary").placeholder = "";
            }
            
        });

        salary.addEventListener("change", (event) => {
            let newValue = salary.value;
            localStorage.setItem('salary', newValue);
        });

        no_of_vacancies.addEventListener("change", (event) => {
            let newValue = no_of_vacancies.value;
            localStorage.setItem('no_of_vacancies', newValue);
        });

        job_description.addEventListener("change", (event) => {
            let newValue = job_description.value;
            localStorage.setItem('job_description', newValue);
        });

        required_qualification.addEventListener("change", (event) => {
            let newValue = required_qualification.value;
            localStorage.setItem('required_qualification', newValue);
        });

        wanted_qualification.addEventListener("change", (event) => {
            let newValue = wanted_qualification.value;
            localStorage.setItem('wanted_qualification', newValue);
        });

        company_offers.addEventListener("change", (event) => {
            let newValue = company_offers.value;
            localStorage.setItem('company_offers', newValue);
        });

        

        title.addEventListener('focusout', (event) => {
                if(title.value === ''){
                    document.getElementById('title_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    title.style.border = "2px solid #810000";
                    correct_fields[0] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('title_error').innerHTML = "";
                    title.style.border = "2px solid #00b300";
                    correct_fields[0] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        object.addEventListener('focusout', (event) => {
                if(object.value === ''){
                    document.getElementById('object_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    object.style.border = "2px solid #810000";
                    correct_fields[1] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('object_error').innerHTML = "";
                    object.style.border = "2px solid #00b300";
                    correct_fields[1] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        type.addEventListener('focusout', (event) => {
                if(type.value === ''){
                    document.getElementById('type_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    type.style.border = "2px solid #810000";
                    correct_fields[2] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('type_error').innerHTML = "";
                    type.style.border = "2px solid #00b300";
                    correct_fields[2] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        duration.addEventListener('focusout', (event) => {
                if(duration.value === ''){
                    document.getElementById('duration_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    duration.style.border = "2px solid #810000";
                    correct_fields[3] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('duration_error').innerHTML = "";
                    duration.style.border = "2px solid #00b300";
                    correct_fields[3] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        shift_start.addEventListener('focusout', (event) => {
                if(shift_start.value === ''){
                    document.getElementById('shift_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    shift_start.style.border = "2px solid #810000";
                    correct_fields[4] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('shift_error').innerHTML = "";
                    shift_start.style.border = "2px solid #00b300";
                    correct_fields[4] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        shift_end.addEventListener('focusout', (event) => {
                if(shift_end.value === ''){
                    document.getElementById('shift_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    shift_end.style.border = "2px solid #810000";
                    correct_fields[5] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('shift_error').innerHTML = "";
                    shift_end.style.border = "2px solid #00b300";
                    correct_fields[5] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        start.addEventListener('focusout', (event) => {
                if(start.value === ''){
                    document.getElementById('start_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    start.style.border = "2px solid #810000";
                    correct_fields[6] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('start_error').innerHTML = "";
                    start.style.border = "2px solid #00b300";
                    correct_fields[6] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        espa.addEventListener('focusout', (event) => {
                if(espa.value === ''){
                    document.getElementById('espa_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    espa.style.border = "2px solid #810000";
                    correct_fields[7] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('espa_error').innerHTML = "";
                    espa.style.border = "2px solid #00b300";
                    correct_fields[7] = true;

                    if (espa.value === 'Με ΕΣΠΑ'){
                        correct_fields[8] = true;
                        console.log("me espa salary  " + correct_fields);
                        // check_correct_fields_no();
                    } else {
                        correct_fields[8] = false;
                        console.log("xwris espa salary  " + correct_fields);
                    }
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        salary.addEventListener('focusout', (event) => {
                if(salary.value === ''){
                    document.getElementById('salary_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    salary.style.border = "2px solid #810000";
                    correct_fields[8] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else if (salary.value < 500){
                    document.getElementById('salary_error').innerHTML = "Ο μισθός πρέπει να είναι τουλάχιστον 500€.";
                    salary.style.border = "2px solid #810000";
                    correct_fields[8] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('salary_error').innerHTML = "";
                    salary.style.border = "2px solid #00b300";
                    correct_fields[8] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        no_of_vacancies.addEventListener('focusout', (event) => {
                if(no_of_vacancies.value === ''){
                    document.getElementById('no_of_vacancies_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    no_of_vacancies.style.border = "2px solid #810000";
                    correct_fields[9] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('no_of_vacancies_error').innerHTML = "";
                    no_of_vacancies.style.border = "2px solid #00b300";
                    correct_fields[9] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        job_description.addEventListener('focusout', (event) => {
                if(job_description.value === ''){
                    document.getElementById('job_description_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    job_description.style.border = "2px solid #810000";
                    correct_fields[10] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('job_description_error').innerHTML = "";
                    job_description.style.border = "2px solid #00b300";
                    correct_fields[10] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        required_qualification.addEventListener('focusout', (event) => {
                if(required_qualification.value === ''){
                    document.getElementById('required_qualification_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    required_qualification.style.border = "2px solid #810000";
                    correct_fields[11] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('required_qualification_error').innerHTML = "";
                    required_qualification.style.border = "2px solid #00b300";
                    correct_fields[11] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        wanted_qualification.addEventListener('focusout', (event) => {
                if(wanted_qualification.value === ''){
                    document.getElementById('wanted_qualification_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    wanted_qualification.style.border = "2px solid #810000";
                    correct_fields[12] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('wanted_qualification_error').innerHTML = "";
                    wanted_qualification.style.border = "2px solid #00b300";
                    correct_fields[12] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        company_offers.addEventListener('focusout', (event) => {
                if(company_offers.value === ''){
                    document.getElementById('company_offers_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    company_offers.style.border = "2px solid #810000";
                    correct_fields[13] = false;
                    console.log(correct_fields);
                    check_correct_fields_no();
                } else {
                    document.getElementById('company_offers_error').innerHTML = "";
                    company_offers.style.border = "2px solid #00b300";
                    correct_fields[13] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });


        function getLocalStoredValue() {

            let title = document.getElementById("title");
            let object = document.getElementById("object");
            let type = document.getElementById("type");
            let duration = document.getElementById("duration");
            let shift_start = document.getElementById("shift_start");
            let shift_end = document.getElementById("shift_end");
            let start = document.getElementById("start");
            let espa = document.getElementById("espa");
            let salary = document.getElementById("salary");
            let no_of_vacancies = document.getElementById("no_of_vacancies");
            let job_description = document.getElementById("job_description");
            let required_qualification = document.getElementById("required_qualification");
            let wanted_qualification = document.getElementById("wanted_qualification");
            let company_offers = document.getElementById("company_offers");

            let value = localStorage.getItem('title');
            if ((value != "") && (value != null)){
                title.value = value;
                correct_fields[0] = true;
            }
            
            value = localStorage.getItem('object');
            if ((value != "") && (value != null)){
                object.value = value;
                correct_fields[1] = true;
            }
            
            value = localStorage.getItem('type');
            if ((value != "") && (value != null)){
                type.value = value;
                correct_fields[2] = true;
            }
            
            value = localStorage.getItem('duration');
            if ((value != "") && (value != null)){
                duration.value = value;
                correct_fields[3] = true;
            }

            value = localStorage.getItem('shift_start');
            if ((value != "") && (value != null)){
                shift_start.value = value;
                correct_fields[4] = true;
            }

            value = localStorage.getItem('shift_end');
            if ((value != "") && (value != null)){
                shift_end.value = value;
                correct_fields[5] = true;
            }

            value = localStorage.getItem('start');
            if ((value != "") && (value != null)){
                start.value = value;
                correct_fields[6] = true;
            }

            value = localStorage.getItem('espa');
            if ((value != "") && (value != null)){
                espa.value = value;
                correct_fields[7] = true;
                if(value == "Με ΕΣΠΑ") {
                    document.getElementById("salary").disabled = true;
                    document.getElementById("salary").placeholder = "-";
                    document.getElementById('salary_error').innerHTML = "";
                    salary.style.border = "2px solid transparent";
                    correct_fields[8] = true;
                } else {
                    document.getElementById("salary").disabled = false;
                    document.getElementById("salary").placeholder = "";
                    svalue = localStorage.getItem('salary');
                    if ((svalue != "") && (svalue != null)){
                        salary.value = svalue;
                        correct_fields[8] = true;
                    }
                }
            }
            
            

            value = localStorage.getItem('no_of_vacancies');
            if ((value != "") && (value != null)){
                no_of_vacancies.value = value;
                correct_fields[9] = true;
            }

            value = localStorage.getItem('job_description');
            if ((value != "") && (value != null)){
                job_description.value = value;
                correct_fields[10] = true;
            }

            value = localStorage.getItem('required_qualification');
            if ((value != "") && (value != null)){
                required_qualification.value = value;
                correct_fields[11] = true;
            }
            
            value = localStorage.getItem('wanted_qualification');
            if ((value != "") && (value != null)){
                wanted_qualification.value = value;
                correct_fields[12] = true;
            }
            
            value = localStorage.getItem('company_offers');
            if ((value != "") && (value != null)){
                company_offers.value = value;
                correct_fields[13] = true;
            }

            console.log(correct_fields);

            check_correct_fields_no();

        }

        function check_correct_fields_no() {
                if(correct_fields.every(v => v === true)) {
                    document.getElementById('next_btn').disabled = false;
                } else {
                    document.getElementById('next_btn').disabled = true;
                }
            }

      </script>
</html>  