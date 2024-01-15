<?php 
    if(!isset($_SESSION)) { 
        session_start(); 
    }

    if(!isset($_SESSION['username']) || (isset($_SESSION['username']) && $_SESSION['type'] != 'student')) {
        header("location: javascript:history.go(-1)");
    }
    

    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT a.title, c.company_name
                FROM ad a, company c
                WHERE (a.id = '$id') AND (a.company_id = c.id)";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
    }

    if(!isset($_SESSION['saved_info'])){
        $_SESSION['saved_info']['clicked'] = 0;
    }

    if (isset($_GET["app"])) {
        $app_id = $_GET["app"];
        $sql = "SELECT sa.reasons FROM student_application sa WHERE (sa.id = '$app_id')";
        $result = mysqli_query($conn, $sql);
        $rowA = mysqli_fetch_array($result);
        $_SESSION['saved_info']['clicked'] = 1;
        ?>
        <script>
           localStorage.setItem('reasons', '<?php echo $rowA['reasons']; ?>');
           getLocalStoredValue();
        </script>
        
        <?php
    } 

    // print_r( $_SESSION['saved_info']);

    if (isset($_POST['save_btn'])) {
            //a $_FILES 'error' value of zero means success. Anything else and something wrong with attached file.
            // if ($_FILES['pdf_file']['error'] != 0) {
            //     echo 'Something wrong with the file.';
            // } else
             if ( (isset($_SESSION['saved_info']['clicked'])) && ($_SESSION['saved_info']['clicked'] == 0)) { //
    
                $reasons = mysqli_real_escape_string($conn, $_POST['reasons']); ?>
                <script>
                    let newValue = '<?php echo $reasons; ?>';
                    let localStorage = window.localStorage;
                    console.log(newValue);
                    localStorage.setItem('reasons', newValue);
                </script>
                
                <?php
                $file_name = "";
                
                if (!empty($_FILES['pdf_file']['name'])) {
                    $file_name = $_FILES['pdf_file']['name'];
                    $file_tmp = $_FILES['pdf_file']['tmp_name'];
                    ?>
                            <script>
                                let newValue = '<?php echo $file_name; ?>';
                                // correct_fields[1] = true;
                                let localStorage = window.localStorage;
                                localStorage.setItem('pdf_file', newValue);
                            </script>
                    <?php
                    $pdf_blob = fopen($file_tmp, "r");
                }
                
                        $date = date("Y-m-d");
                        $username = $_SESSION['username'];
                        $sql = "SELECT s.id FROM user u, student s WHERE (u.username = '$username') AND (s.user_id = u.id)";
                        $result2 = mysqli_query($conn, $sql);
                        $row2 = mysqli_fetch_array($result2);
                        $stud = $row2['id'];
                        $state = "Προσωρινά Αποθηκευμένη";
                        // $company_accepted = 0;
                        
                        if ( ($reasons != "")  && ($file_name == "") ){
                            $insert_sql = "INSERT INTO student_application(reasons, date, state, student_id, ad_id) VALUES (?, ?, ?, ?, ?)";
                            $stmt = $conn->prepare($insert_sql);
                            $stmt->bind_param('sssii', $reasons, $date, $state, $stud, $id);
                            $stmt->execute();
    
                            $_SESSION['saved_info']['clicked'] = 1;
                            // $_SESSION['saved_info']['file_name'] = $file_name;
                            $_SESSION['saved_info']['reasons'] = $reasons;
    
                        } else if ( ($reasons == "")  && ($file_name != "")) {
                            $insert_sql = "INSERT INTO student_application(file, date, state, student_id, ad_id) VALUES (?, ?, ?, ?, ?)";
                            $stmt = $conn->prepare($insert_sql);
                            $stmt->bind_param('bssii', $pdf_blob, $date, $state, $stud, $id);
                            while (!feof($pdf_blob)) {
                                $stmt->send_long_data(0, fread($pdf_blob, 8192));
                            }
                            fclose($pdf_blob);
                            $stmt->execute();
    
                            $_SESSION['saved_info']['clicked'] = 1;
                            $_SESSION['saved_info']['file_name'] = $file_name;
                            // $_SESSION['saved_info']['reasons'] = $reasons;
                            
    
                        } else {
                            $insert_sql = "INSERT INTO student_application(file, reasons, date, state, student_id, ad_id) VALUES (?, ?, ?, ?, ?, ?)";
                            $stmt = $conn->prepare($insert_sql);
                            $stmt->bind_param('bsssii', $pdf_blob, $reasons, $date, $state, $stud, $id);
                            while (!feof($pdf_blob)) {
                                $stmt->send_long_data(0, fread($pdf_blob, 8192));
                            }
                            fclose($pdf_blob);
                            $stmt->execute();
    
                            $_SESSION['saved_info']['clicked'] = 1;
                            $_SESSION['saved_info']['file_name'] = $file_name;
                            $_SESSION['saved_info']['reasons'] = $reasons;
                        }
                        
    
                        echo '<script>alert("Έγινε προσωρινή αποθήκευση.")</script>';
    
            } else if ( (isset($_SESSION['saved_info']['clicked'])) && ($_SESSION['saved_info']['clicked'] == 1)){
    
                $reasons = mysqli_real_escape_string($conn, $_POST['reasons']);?>
                <script>
                    let newValue = '<?php echo $reasons; ?>';
                    let localStorage = window.localStorage;
                    localStorage.setItem('reasons', newValue);
                </script>
                
                <?php
                $file_name = "";
    
    
                if (!empty($_FILES['pdf_file']['name'])) {
                    $file_name = $_FILES['pdf_file']['name'];
                    $file_tmp = $_FILES['pdf_file']['tmp_name'];
                    ?>
                            <script>
                                let newValue = '<?php echo $file_name; ?>';
                                // correct_fields[1] = true;
                                let localStorage = window.localStorage;
                                localStorage.setItem('pdf_file', newValue);
                            </script>
                    <?php
                    $pdf_blob = fopen($file_tmp, "r");
                }
                
                
                        $date = date("Y-m-d");
                        $username = $_SESSION['username'];
                        $state = "Προσωρινά Αποθηκευμένη";
    
                        if (!isset($_GET["app"])){
                            $sql = "SELECT s.id as 'stud_id', a.id as 'app_id' FROM user u, student s, student_application a
                                WHERE (u.username = '$username') AND (s.user_id = u.id) AND (s.id = a.student_id) AND (a.ad_id = '$id')
                                ORDER BY a.id DESC";
                        
                            $result2 = mysqli_query($conn, $sql);
                            $row2 = mysqli_fetch_array($result2);
                            $stud_id = $row2['stud_id'];
                            $app_id = $row2['app_id'];
                        }
    
                        if ( ($reasons != "") && ($file_name == "") ) {
                            $update_sql = "UPDATE student_application SET reasons=?, date=?, state=?  WHERE id=?";
                            $stmt = $conn->prepare($update_sql);
    
                            $stmt->bind_param('sssi', $reasons, $date, $state, $app_id);
                            $stmt->execute();
    
                            // $_SESSION['saved_info']['clicked'] = 1;
                            // $_SESSION['saved_info']['file_name'] = $file_name;
                            $_SESSION['saved_info']['reasons'] = $reasons;
                        
                        } else if ( ($reasons == "") && ($file_name != "")) {
                            $update_sql = "UPDATE student_application SET file=?, date=?, state=?  WHERE id=?";
                            $stmt = $conn->prepare($update_sql);
    
                            $stmt->bind_param('bsssi', $pdf_blob, $date, $state, $app_id);
    
                            while (!feof($pdf_blob)) {
                                $stmt->send_long_data(0, fread($pdf_blob, 8192));
                            }
                            fclose($pdf_blob);
                            $stmt->execute();
    
                            // $_SESSION['saved_info']['clicked'] = 1;
                            $_SESSION['saved_info']['file_name'] = $file_name;
                            // $_SESSION['saved_info']['reasons'] = $reasons;
                        
                        } else {
                            $update_sql = "UPDATE student_application SET file=?, reasons=?, date=?, state=?  WHERE id=?";
                            $stmt = $conn->prepare($update_sql);
    
                            $stmt->bind_param('bsssi', $pdf_blob, $reasons, $date, $state, $app_id);
    
                            while (!feof($pdf_blob)) {
                                $stmt->send_long_data(0, fread($pdf_blob, 8192));
                            }
                            fclose($pdf_blob);
                            $stmt->execute();
    
                            // $_SESSION['saved_info']['clicked'] = 1;
                            $_SESSION['saved_info']['file_name'] = $file_name;
                            $_SESSION['saved_info']['reasons'] = $reasons;
                        } 
    
                        echo '<script>alert("Έγινε προσωρινή αποθήκευση.")</script>';
            }
        }
    
        // if (isset($_POST['next_btn']) && !empty($_FILES['pdf_file']['name'])) {
        if (isset($_POST['next_btn']) ) {
            //a $_FILES 'error' value of zero means success. Anything else and something wrong with attached file.
            // if ($_FILES['pdf_file']['error'] != 0) {
            //     echo 'Something wrong with the file.';
    
            // } else 
            if ( (isset($_SESSION['saved_info']['clicked'])) && ($_SESSION['saved_info']['clicked'] == 0)) { //
    
                $reasons = mysqli_real_escape_string($conn, $_POST['reasons']);?>
                <script>
                    let newValue = '<?php echo $reasons; ?>';
                    let localStorage = window.localStorage;
                    console.log(newValue);
                    localStorage.setItem('reasons', newValue);
                </script>
                
                <?php
                
                $file_name = "";
    
                if (!empty($_FILES['pdf_file']['name'])) {
                    $file_name = $_FILES['pdf_file']['name'];
                    $file_tmp = $_FILES['pdf_file']['tmp_name'];
                    ?>
                            <script>
                                let newValue = '<?php echo $file_name; ?>';
                                // correct_fields[1] = true;
                                let localStorage = window.localStorage;
                                localStorage.setItem('pdf_file', newValue);
                            </script>
                    <?php
                    $pdf_blob = fopen($file_tmp, "r");
                }
                
                        $date = date("Y-m-d");
                        $username = $_SESSION['username'];
                        $sql = "SELECT s.id FROM user u, student s WHERE (u.username = '$username') AND (s.user_id = u.id)";
                        $result2 = mysqli_query($conn, $sql);
                        $row2 = mysqli_fetch_array($result2);
                        $stud = $row2['id'];
                        $state = "auto-save";
                        // $company_accepted = 0;
                        
                        $insert_sql = "INSERT INTO student_application(file, reasons, date, state, student_id, ad_id) VALUES (?, ?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($insert_sql);
                        // $stmt->bind_param(1, $pdf_blob, PDO::PARAM_LOB);
                        // $stmt->bind_param(2, $reasons);
                        $stmt->bind_param('bsssii', $pdf_blob, $reasons, $date, $state, $stud, $id);
                        // $insert_sql = "INSERT INTO student_application(file, reasons, date, state, company_accepted, student_id, ad_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        // $stmt = $conn->prepare($insert_sql);
                        // // $stmt->bind_param(1, $pdf_blob, PDO::PARAM_LOB);
                        // // $stmt->bind_param(2, $reasons);
                        // $stmt->bind_param('bsssiii', $pdf_blob, $reasons, $date, $state, $company_accepted, $stud, $id);
                        while (!feof($pdf_blob)) {
                            $stmt->send_long_data(0, fread($pdf_blob, 8192));
                        }
                        fclose($pdf_blob);
                        $stmt->execute();
    
                        $_SESSION['saved_info']['clicked'] = 1;
                        $_SESSION['saved_info']['file_name'] = $file_name;
                        $_SESSION['saved_info']['reasons'] = $reasons;
    
                        header("Location: create_application_2.php?id=$id");
    
            } else if ((isset($_SESSION['saved_info']['clicked'])) && ($_SESSION['saved_info']['clicked'] == 1)){
                
                $reasons = mysqli_real_escape_string($conn, $_POST['reasons']);?>
                <script>
                    let newValue = '<?php echo $reasons; ?>';
                    let localStorage = window.localStorage;
                    console.log(newValue);
                    localStorage.setItem('reasons', newValue);
                </script>
                
                <?php
                $file_name = "";
    
                if (!empty($_FILES['pdf_file']['name'])) {
                    $file_name = $_FILES['pdf_file']['name'];
                    $file_tmp = $_FILES['pdf_file']['tmp_name'];
                    ?>
                            <script>
                                let newValue = '<?php echo $file_name; ?>';
                                // correct_fields[1] = true;
                                let localStorage = window.localStorage;
                                localStorage.setItem('pdf_file', newValue);
                            </script>
                    <?php
                    $pdf_blob = fopen($file_tmp, "r");
                }
                
                
                        $date = date("Y-m-d");
                        $username = $_SESSION['username'];
    
                        if (!isset($_GET["app"])){
                            $sql = "SELECT s.id as 'stud_id', a.id as 'app_id' FROM user u, student s, student_application a
                                WHERE (u.username = '$username') AND (s.user_id = u.id) AND (s.id = a.student_id) AND (a.ad_id = '$id')
                                ORDER BY a.id DESC";
                        
                            $result2 = mysqli_query($conn, $sql);
                            $row2 = mysqli_fetch_array($result2);
                            $stud_id = $row2['stud_id'];
                            $app_id = $row2['app_id'];
                        }
    
                        if ( ($file_name == "") ){
                            $update_sql = "UPDATE student_application SET reasons=?, date=?  WHERE id=?";
                            $stmt = $conn->prepare($update_sql);
    
                            $stmt->bind_param('ssi', $reasons, $date, $app_id);
                            $stmt->execute();
    
                            // $_SESSION['saved_info']['clicked'] = 1;
                            // $_SESSION['saved_info']['file_name'] = $file_name;
                            $_SESSION['saved_info']['reasons'] = $reasons;
                        
                        } else {
                            $update_sql = "UPDATE student_application SET file=?, reasons=?, date=?  WHERE id=?";
                            $stmt = $conn->prepare($update_sql);
    
                            $stmt->bind_param('bssi', $pdf_blob, $reasons, $date, $app_id);
    
                            while (!feof($pdf_blob)) {
                                $stmt->send_long_data(0, fread($pdf_blob, 8192));
                            }
                            fclose($pdf_blob);
                            $stmt->execute();
    
                            // $_SESSION['saved_info']['clicked'] = 1;
                            $_SESSION['saved_info']['file_name'] = $file_name;
                            $_SESSION['saved_info']['reasons'] = $reasons;
                        }
    
                        header("Location: create_application_2.php?id=$id");
            }
        }
         
?>

<!doctype html>  
<html lang="el">  
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/create_application.css">
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
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="students.php">Φοιτητές/τριες</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="search.php">Αναζήτηση Θέσεων ΠΑ</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">κωδ. <?php echo $id?></div>
        </div>

        <br><br>
        <h1 style="text-align: center">Δημιουργία Αίτησης</h1>

            <div class="wrapper">
                
                <section class="step-wizard">
                    <ul class="step-wizard-list">
                        <li class="step-wizard-item current-item">
                            <span class="progress-count">1</span>
                            <span class="progress-label">Πληροφορίες Αίτησης</span>
                        </li>
                        <li class="step-wizard-item next-item">
                            <span class="progress-count">2</span>
                            <span class="progress-label">Υποβολή Αίτησης</span>
                        </li>
                    </ul>
                </section>
                
                <div style="margin-left: 160px">Τα πεδία με <span style="color:#810000">*</span> είναι υποχρεωτικά.</div>
                <form method="post" enctype="multipart/form-data">
                    <div class="nested">
                    
                        <div>
                            <b>Αίτηση για Πρακτική Άσκηση (ΠΑ) στη θέση:</b>
                        </div>
                        <div><?php echo $row['title']?> - <?php echo $row['company_name']?></div>

                        <div><label for="pdf_file"><span style="color:#810000">*</span>Αναλυτική Βαθμολογία:</label></div>
                        <div>
                            <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" value="">
                            <span class="error" id="pdf_file_error"></span>
                        </div>

                        <div><label for="reasons" class="required" style="margin-top: 25px"><span style="color:#810000">*</span>Η θέση με ενδιαφέρει γιατί:</label></div>
                        <div>
                            <textarea id="reasons" name="reasons" rows="7" cols="70" placeholder="Λόγοι για τους οποίους με ενδιαφέρει αυτή η θέση..." value=""  onchange="setLocalStorageValueR()"></textarea>
                            <span class="error" id="reasons_error"></span>
                        </div>

                    </div><br><br><br>

                    
                    <div><button class="btn1" type="button" onclick="location.href='details.php?id=<?php echo $id; ?>'" style="float: left; background-color: #afb1b1;">Ακύρωση</button>
                        <button class="btn1" name="save_btn" type="submit" style="background-color: #afb1b1;">Προσωρινή Αποθήκευση</button>
                        <button name="next_btn" id="next_btn" type="submit" class="btn1" style="float: right; background-color: #6ccd71;" disabled>Επόμενο Βήμα</button>
                    </div>
                </form>
            </div>
            <br><br><br>
        <?php
            include 'bottom_base.php';
        ?>
      </body>

      <script type="text/javascript">

        let pdf_file = document.getElementById('pdf_file');
        let reasons = document.getElementById('reasons');
        let correct_fields = [false, false];

        reasons.addEventListener('focusout', (event) => {
                if(reasons.value === ''){
                    document.getElementById('reasons_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    reasons.style.border = "2px solid #810000";
                    correct_fields[1] = false;
                    console.log(correct_fields);
                    localStorage.removeItem('reasons');
                    check_correct_fields_no();
                } else {
                    document.getElementById('reasons_error').innerHTML = "";
                    reasons.style.border = "2px solid #00b300";
                    correct_fields[1] = true;
                    console.log(correct_fields);
                    check_correct_fields_no();
                }
            });

        pdf_file.addEventListener('focusout', (event) => {
                if(pdf_file.value === ''){
                    document.getElementById('pdf_file_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    pdf_file.style.border = "2px solid #810000";
                    correct_fields[0] = false;
                    console.log("pdf  "+correct_fields);
                    localStorage.removeItem('pdf_file');
                    check_correct_fields_no();
                } else {
                    document.getElementById('pdf_file_error').innerHTML = "";
                    pdf_file.style.border = "2px solid #00b300";
                    correct_fields[0] = true;
                    console.log("pdf  "+correct_fields);
                    check_correct_fields_no();
                }
            });

        function setLocalStorageValueR()
        {
            let reasons = document.getElementById('reasons');
            let myNewValue = reasons.value;
            // correct_fields[1] = true;

            let localStorage = window.localStorage;
            localStorage.setItem('reasons', myNewValue);
        }

        // function setLocalStorageValueF()
        // {
        //     let pdf_file = document.getElementById('pdf_file');
        //     let myNewValue = pdf_file.value;
        //     correct_fields[0] = true;

        //     let localStorage = window.localStorage;
        //     localStorage.setItem('pdf_file', myNewValue);
        // }

        function getLocalStoredValue()
        {
            let localStorage = window.localStorage;
            let valueR = localStorage.getItem('reasons');

            let reasons = document.getElementById('reasons');
            reasons.value = valueR;

            if (reasons.value != ""){
                correct_fields[1] = true;
                console.log("getLocalStorageValue   "+ correct_fields);
            }

            let valueF = localStorage.getItem('pdf_file');
            if (valueF != null){
                correct_fields[0] = true;
                console.log("getLocalStorageValue   "+ correct_fields);
            }

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