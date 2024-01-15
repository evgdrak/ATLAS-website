<?php

    if(!isset($_SESSION)) { 
        session_start(); 
    }

    if(isset($_SESSION['saved_info'])){
        unset($_SESSION['saved_info']);
    }

    if(isset($_SESSION['error'])){
        unset($_SESSION['error']);
    }
    
    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error); 

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT a.*, DATE_FORMAT(a.shift_start, '%H:%i') as `start_time`, DATE_FORMAT(a.shift_end, '%H:%i') as `end_time`, c.company_name, c.address, c.address_number, c.zip_code, c.site, c.company_email, c.company_phone, m.name AS 'municipality', r.name AS 'region', 
                    GROUP_CONCAT( DISTINCT CONCAT(d.name,'(',u.name,')')  ORDER BY u.name  SEPARATOR ', ') as 'departments'
                FROM ad a, company c, municipality m, region r, department d, department_ad da, university u
                WHERE (a.id = '$id') AND (a.company_id = c.id) AND (c.municipality_id = m.id) AND (m.region_id = r.id) AND (da.ad_id = '$id') AND (da.department_id = d.id) AND (d.university_id = u.id)
                ORDER BY a.id DESC";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
    }

    if (isset($_POST['submit']) && !empty($_POST['submit'])) {
        // Data sanitization to prevent SQL injection
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $query = "SELECT * FROM user WHERE user.username='$username' AND user.password='$password'";

        $results = $conn->query($query) or die(mysql_error()."<br />".$query);
        
        // $results = 1 means that one user with the
        // entered username exists
        if (mysqli_num_rows($results) == 1) {
            // Storing username in session variable
            $_SESSION['username'] = $username;
            
            $row2 = mysqli_fetch_array($results);

            $_SESSION['type'] = $row2['type'];
        
            if($row2['type'] == 'student') {
                // echo '<script type="text/javascript">window.location = "create_application.php?id='.$row['id'].'"</script>';
                
                $sql_check = "SELECT sa.id, sa.state FROM student_application sa, student s, ad a, user u
                            WHERE (sa.ad_id = '$id') AND (sa.student_id = s.id) AND (s.user_id = u.id) AND (u.username = '$username') AND (sa.state != 'auto-save')
                            GROUP BY sa.id";
                $result_check = mysqli_query($conn, $sql_check);

                if ( mysqli_num_rows($result_check) == 0 ){ ?>
                    <script>location.href = "create_application.php?id=<?php echo $row['id']; ?>";</script>
                    <?php
                }

                $row_check = mysqli_fetch_array($result_check);
                if ($row_check['state'] == 'Προσωρινά Αποθηκευμένη'){ ?>
                    <script>location.href = "create_application.php?id=<?php echo $row['id'].'&app='.$row_check['id'] ?>";</script>
                    <?php
                } else {?>
                    <script>    document.getElementById("create").style.display = "none";
                    document.getElementById('access_error').innerHTML = "Έχει ήδη υποβληθεί αίτηση.";</script>
                    
            <?php }
            } else {
                echo '<script type="text/javascript">window.location = "details.php?id='.$row['id'].'"</script>';
            }
        } else {
            $_SESSION['error'] = 'error';
        }
    }

    if(isset($_SESSION['saved_info'])){
        unset($_SESSION['saved_info']);
    }
?>


<!doctype html>  
<html lang="el">  
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/details.css">
        <meta name='viewport' content='width=device-width, initial-scale=1'>
    </head>  
    <body onload="cleanStorage()">   
        <?php
            include 'top_base.php';
        ?>
        <?php
            include 'nav_bar.php';
        ?>
        
        <div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="index.php">Αρχική Σελίδα</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="students.php">Φοιτητές/τριες</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="search.php">Αναζήτηση θέσεων ΠΑ</a></div>    
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">κωδ. <?php echo $row['id']?></div> 
        </div>
        <br><br><br>
        <div><a onclick="window.close()" style="text-decoration: none; cursor:pointer; margin-left:60px;">< Πίσω</a></div>
        <br>
        <div class="wrapper">
            <div class="name">
                <?php echo $row['title'];?>
            </div>
            <div class="company">
                <?php echo $row['company_name'];?>
            </div>
            <div class="published">
                Δημοσίευση: <?php echo $row['date'];?>
            </div>
            <div class="start-date">
                Έναρξη ΠΑ μέχρι: <?php echo $row['start'];?>
            </div>
            <div class="location">
                <!-- <img alt="location pin" src="../img/location.png" style="width:15px; margin-right: 10px;"><b style="margin-right: 10px;">Τοποθεσία: </b> <?php echo $row['address']. " " . $row['address_number']. " "  . $row['zip_code']. ", "  . $row['municipality']. ", " . $row['region'];?> -->
                <img alt="location pin" src="../img/location.png" style="width:15px; margin-right: 10px;"><b style="margin-right: 10px;">Τοποθεσία: </b>
            </div>
            <div class="location_data"> <?php echo $row['address']. " " . $row['address_number']. " "  . $row['zip_code']. ", "  . $row['municipality']. ", " . $row['region'];?></div>
            <div class="type">
                <img alt="briefcase" src="../img/briefcase.png" style="width:18px; margin-right: 10px;"><b style="margin-right: 10px;">Τύπος Απασχόλησης: </b> 
            </div>
            <div class="type_data"> <?php echo $row['type']?> Απασχόληση</div>
            <div class="duration">
                <img alt="clock" src="../img/clock.png" style="width:18px; margin-right: 10px;"><b style="margin-right: 10px;">Διάρκεια: </b> 
            </div>
            <div class="duration_data"> <?php echo $row['duration']?> μήνες</div>
            <!-- <div class="espa">
                <?php echo $row['espa']?>
            </div> -->
            <div class="salary">
                <b>Μισθός:</b> 
                <!-- <?php if ($row['espa'] == "Με ΕΣΠΑ"){
                    echo "-";
                } else {
                    echo $row['salary']."  €";
                }?>
                <span style="margin-left: 20px"><?php echo "(".$row['espa'].")"?></span> -->
            </div>
            <div class="salary_data"> 
                <?php if ($row['espa'] == "Με ΕΣΠΑ"){
                    echo "-";
                } else {
                    echo $row['salary']."  €";
                }?>
                <span style="margin-left: 20px"><?php echo "(".$row['espa'].")"?></span>
            </div>
            <div class="hours">
                <b>Ωράριο:</b> 
            </div>
            <div class="hours_data"> <?php echo $row['start_time']?> - <?php echo $row['end_time']?></div>
            <div class="no">
                <b>Αριθμός Διαθέσιμων Θέσεων:</b> 
            </div>
            <div class="no_data"> <?php echo $row['no_of_vacancies']?></div>
            <div class="department">
                <b>Τμήμα:</b> 
            </div>
            <div class="department_data"> <?php echo $row['departments']?> </div>
            <div class="object">
                <b>Γνωστικό Αντικείμενο:</b> 
            </div>
            <div class="object_data"> <?php echo $row['object']?></div>
            <div class="job_descr">
                <b>Περιγραφή Θέσης:</b>
            </div>
            <div class="job_descr_data"> <?php echo str_ireplace("\\r\\n", "\r\n", $row['job_description'])?> </div>
            <div class="required">
                <b>Απαραίτητα Προσόντα:</b>
            </div>
            <div class="required_data"> <?php echo str_ireplace("\\r\\n", "\r\n", $row['required_qualification'])?> </div>
            <div class="wanted">
                <b>Επιθυμητά Προσόντα:</b>
            </div>
            <div class="wanted_data"> <?php echo str_ireplace("\\r\\n", "\r\n", $row['wanted_qualification'])?> </div>
            <div class="offers">
                <b>Η εταιρεία προσφέρει:</b>
            </div>
            <div class="offers_data"> <?php echo str_ireplace("\\r\\n", "\r\n", $row['company_offers'])?> </div>
            <div class="site">
                <b>Ιστοχώρος:</b> 
            </div>
            <div class="site_data"> <?php echo $row['site']?> </div>
            <div class="info">
                <b>Στοιχεία Επικοινωνίας: </b>
            </div>
            <div class="phone">
                <img alt="phone" src="../img/phone-solid.png" style="width: 7%; margin-right: 6px;"><?php echo $row['company_phone']?>
            </div>
            <div class="email">
                <img alt="email" src="../img/envelope-solid.png" style="width: 4%; margin-right: 6px;"><?php echo $row['company_email']?>
            </div>
            <div class="code">
                Κωδ. <?php echo $row['id']?></div>
            <!-- <div class="heart">
                <img alt="heart" src="../img/heart-regular.png" class="pic" style="width: 12%;cursor: pointer;">
            </div> -->
            <div class="btn-position">
                <!-- <button class="btn"><a href='create_application.php' target="_blank" style="text-decoration: none; color:black;">Κάνε Αίτηση</a></button> -->
                <!-- <button class="btn" onclick="location.href='create_application.php?id=<?php echo $row['id']; ?>'">Κάνε Αίτηση</button> -->
                <button class="btn" id="create" onclick="openForm()">Κάνε Αίτηση</button>
            </div>
            <!-- <br><br> -->
            <div class="error" id="access_error"></div>

            <div class="form-popup" id="myForm">
                <form class="form-container" method="post">
                    <h2>Σύνδεση</h2>
                    <div class="container">
                        <label class="required" for="username" style="font-weight:bold">Όνομα χρήστη (username)</label>
                        <span>
                            <input type="text" class="inp" name="username" id="username" style="margin: 5px 0 5px 0;">
                        </span>
                        <span class="error" id="username_error" style="display: flex;justify-content: center; margin-bottom: 15px;"></span>

                        <label class="required" for="password" style="font-weight:bold">Κωδικός πρόσβασης</label>
                        <span>
                            <input type="password" class="inp" name="password" id="password" style="margin: 5px 0 5px 0;">
                        </span>
                        <span class="error" id="password_error" style="display: flex;justify-content: center; margin-bottom: 5px;"></span>
                        
                        <?php if(isset($_SESSION['error'])  && !empty($_SESSION['error'])) : ?>
                            <span class="error" id="login_error">Το όνομα χρήστη ή ο κωδικός πρόσβασης ήταν λάθος. Δοκιμάστε ξανά.</span>
                        <?php endif; ?>

                        <p>
                            <a href="under_construction.php">Ξέχασα τον κωδικό πρόσβασης</a>
                        </p>
                        <div class="container">
                            <button type="submit" class="btn" name="submit" id="submit" disabled>Είσοδος</button>
                            <button type="button" class="btn cancel" onclick="closeForm()">Ακύρωση</button>
                        </div>
                        <p>Δεν έχεις λογαριασμό; Κάνε <a href="choose_registration.php">εγγραφή</a> τώρα!</p>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <?php
            include 'bottom_base.php';
        ?>
      </body>

      <script>
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const submit = document.getElementById('submit');
            let correct_fields = [false, false];

            <?php 
            if (isset($_SESSION['username']) && $_SESSION['type'] == 'company'){ ?>
                document.getElementById("create").style.display = "none";
            <?php
            } ?>

            <?php if(isset($_SESSION['username']) && $_SESSION['type'] == 'student'){ 
                $username = $_SESSION['username'];
                $sql_check = "SELECT sa.id, sa.state FROM student_application sa, student s, ad a, user u
                        WHERE (sa.ad_id = '$id') AND (sa.student_id = s.id) AND (s.user_id = u.id) AND (u.username = '$username') AND (sa.state != 'auto-save')
                        GROUP BY sa.id";
                $result_check = mysqli_query($conn, $sql_check);

                

                if ( mysqli_num_rows($result_check) == 1 ){
                    $row_check = mysqli_fetch_array($result_check);
                    if ($row_check['state'] != 'Προσωρινά Αποθηκευμένη'){ ?>
                        document.getElementById("create").style.display = "none";
                        console.log("δημοσιευμενη");
                        document.getElementById('access_error').innerHTML = "Έχει ήδη υποβληθεί αίτηση.";
                        <?php
                    }
                }
            } ?>

            username.addEventListener('focusout', (event) => {
                if(document.getElementById('login_error')){
                    document.getElementById('login_error').innerHTML = "";
                }
                if(username.value === ''){
                    document.getElementById('username_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    username.style.border = "1px solid #810000";
                    
                    correct_fields[0] = false;
                    check_correct_fields_no();
                } else {
                    if(!username.value.match(/^[a-zA-Z0-9]+([_ -]?[a-zA-Z0-9])*$/)) {
                        document.getElementById('username_error').innerHTML = "Το όνομα χρήστη μπορεί να περιέχει λατινικούς χαρακτήρες ή ψηφία.";
                        username.style.border = "1px solid #810000";

                        correct_fields[0] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('username_error').innerHTML = "";
                        username.style.border = "1px solid #00b300";

                        correct_fields[0] = true;
                        check_correct_fields_no();
                    }
                }
            });

            password.addEventListener('focusout', (event) => {
                if(document.getElementById('login_error')){
                    document.getElementById('login_error').innerHTML = "";
                }
                if(password.value === ''){
                    document.getElementById('password_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    password.style.border = "1px solid #810000";
                    
                    correct_fields[1] = false;
                    check_correct_fields_no();
                } else {
                    document.getElementById('password_error').innerHTML = "";
                    password.style.border = "1px solid #00b300";

                    correct_fields[1] = true;
                    check_correct_fields_no();
                }
            });

            submit.addEventListener('click', (event) => {
                submit.value = "btn_clicked";
            });

            function check_correct_fields_no() {
                if(correct_fields.every(v => v === true)) {
                    document.getElementById('submit').disabled = false;
                } else {
                    document.getElementById('submit').disabled = true;
                }
            }

            function login_error() {
                document.getElementById('login_error').innerHTML = "Το όνομα χρήστη ή ο κωδικός πρόσβασης είναι λάθος.";
            }

            function openForm() {
            <?php 
                if(isset($_SESSION['username']) && $_SESSION['type'] == 'student'){ 
                    $username = $_SESSION['username'];
                    $sql_check = "SELECT sa.id, sa.state FROM student_application sa, student s, ad a, user u
                            WHERE (sa.ad_id = '$id') AND (sa.student_id = s.id) AND (s.user_id = u.id) AND (u.username = '$username') AND (sa.state != 'auto-save')
                            GROUP BY sa.id";
                    $result_check = mysqli_query($conn, $sql_check);

                    if ( mysqli_num_rows($result_check) == 0 ){ ?>
                        console.log("openform");
                        location.href = "create_application.php?id=<?php echo $row['id']; ?>";
                        <?php
                    } else {
                        $row_check = mysqli_fetch_array($result_check);
                        if ($row_check['state'] == 'Προσωρινά Αποθηκευμένη'){ ?>
                            location.href = "create_application.php?id=<?php echo $row['id'].'&app='.$row_check['id'] ?>";
                            <?php
                        } else {?>
                            document.getElementById("create").style.display = "none";
                            document.getElementById('access_error').innerHTML = "Έχει ήδη υποβληθεί αίτηση.";
                        
                        <?php }
                    }
                } else {?>
                    document.getElementById("myForm").style.display = "block";
                    <?php
                }?>
        }

            <?php if(isset($_SESSION['error'])  && !empty($_SESSION['error'])) : ?>
                document.getElementById("myForm").style.display = "block";
            <?php endif; ?>

            function closeForm() {
                document.getElementById("myForm").style.display = "none";
            }

            function cleanStorage(){
                let localStorage = window.localStorage;
                localStorage.removeItem('pdf_file');
                localStorage.removeItem('reasons');
            }
    </script>
</html>  