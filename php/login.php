<?php
    // If user is already logged in
    // go to home page
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    if(isset($_SESSION['username'])) {
        header("location: index.php");
    }

    $username = $password = $radio_value = $query = "";
    $_SESSION['success'] = "";
    
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
            
            echo '<script type="text/javascript">window.location = "index.php"</script>';
        } else {
            $_SESSION['error'] = 'error';
        }
    }
?>

<!DOCTYPE html>
<html lang="el">
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/login.css">
    </head>
    <body>
        <?php
            include 'top_base.php';
        ?>
        <div class="english-flag">
            <a href="under_construction.php" class="nav-links">
                <img src="../img/english_flag.png" alt="english flag">
            </a>
        </div>
        <div class="go-back">
            <a href="index.php" style="text-decoration: none;">< Αρχική</a>
        </div>
        <div class="login-form">
            <form method="post">
                <h2>Σύνδεση</h2>
                <div class="container">
                    <label class="required" for="username" style="font-weight:bold">Όνομα χρήστη (username)</label>
                    <span>
                        <input type="text" class="inp" name="username" id="username">
                    </span>
                    <span class="error" id="username_error"></span>

                    <label class="required" for="password" style="font-weight:bold; margin-top: 20px;">Κωδικός πρόσβασης</label>
                    <span>
                        <input type="password" class="inp" name="password" id="password">
                    </span>
                    <span class="error" id="password_error"></span>
                    
                    <?php if(isset($_SESSION['error'])  && !empty($_SESSION['error'])) : ?>
                        <span class="error" id="login_error">Το όνομα χρήστη ή ο κωδικός πρόσβασης ήταν λάθος. Δοκιμάστε ξανά.</span>
                    <?php endif; ?>

                    <p>
                        <a href="under_construction.php">Ξέχασα τον κωδικό πρόσβασης</a>
                    </p>
                    <div class="container">
                        <button type="submit" class="btn" name="submit" id="submit" disabled>Είσοδος</button>
                    </div>
                    <p>Δεν έχεις λογαριασμό; Κάνε <a href="choose_registration.php">εγγραφή</a> τώρα!</p>
                </div>
            </form>
        </div>
        <br><br>
        <?php
            include 'bottom_base.php';
        ?>
        <script>
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const submit = document.getElementById('submit');
            let correct_fields = [false, false];
            ['input','focusout'].forEach( evt => 
                username.addEventListener(evt, username_check, false)
            );

            function username_check() {
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
            }

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

        </script>
    </body>
</html>