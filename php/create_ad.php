<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    $username = $password = $query = "";
    $_SESSION['success'] = "";

    if(isset($_SESSION['saved_info'])){
        unset($_SESSION['saved_info']);
    }

    if(isset($_SESSION['error'])){
        unset($_SESSION['error']);
    }
    
    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error)
        die($conn->connect_error);

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
                
                $row = mysqli_fetch_array($results);

                $_SESSION['type'] = $row['type'];
                unset($_SESSION['error']);
            
                if($row['type'] == 'student') {
                    echo '<script type="text/javascript">window.location = "create_ad.php"</script>';
                } else {
                    echo '<script type="text/javascript">window.location = "create_ad_1.php"</script>';
                }
            } else {
                $_SESSION['error'] = 'error';
            }
        }
?>

<!doctype html>  
<html lang="el">  
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/create_ad.css">
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
        <!-- <h3 style="text-align: center">Δημοσίευσε εύκολα και γρήγορα μια αγγελία σε 3 βήματα!</h3> -->

            <div class="wrapper">
                <div class="nested">
                    <!-- <div style="font-weight: bold; font-size: 20px;">Βήμα 1 - Δημιουργία Αγγελίας</div> -->
                    <div>Για την αγγελία θα χρειαστεί:
                        <li>Να συμπληρώσεις τις κατάλληλες πληροφορίες που αφορούν τη συγκεκριμένη θέση</li>
                        <li>Να διαλέξεις τα τμήματα που αφορά η συγκεκριμένη θέση</li>
                    </div>
                </div>
                <!-- <button class="btn" onclick="location.href='create_ad_1.php'">Δημιουργία Αγγελίας</button>  -->
                <button class="btn" id="create" onclick="openForm()">Δημιουργία Αγγελίας</button>
                <span class="error" id="access_error"></span>

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
            <br><br><br>
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
            if (isset($_SESSION['username']) && $_SESSION['type'] == 'student'){ ?>
                document.getElementById("create").style.display = "none";
            <?php
            }?>

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
                    if(!username.value.match(/^[A-Za-zΑ-Ωα-ωίϊΐόάέύϋΰήώ]+$/)) {
                        document.getElementById('username_error').innerHTML = "Το όνομα χρήστη μπορεί να περιέχει μόνο ελληνικούς ή λατινικούς χαρακτήρες.";
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

            <?php if(isset($_SESSION['error'])  && !empty($_SESSION['error'])) : ?>
                document.getElementById("myForm").style.display = "block";
            <?php endif; ?>

            function openForm() {
                <?php 
                    if(isset($_SESSION['username']) && $_SESSION['type'] == 'company'){ 
                        ?>
                        location.href = "create_ad_1.php";
                        <?php
                    } else if (isset($_SESSION['username']) && $_SESSION['type'] == 'student'){
                        ?>
                        document.getElementById("create").style.display = "none";
                        <?php
                    } else {?>
                        document.getElementById("myForm").style.display = "block";
                        <?php
                    }?>
            }

            function closeForm() {
                document.getElementById("myForm").style.display = "none";
            }

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
            }
    </script> 

</html>  