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

    $firstname = $lastname = $username = $password = $email = $phone = $department_id = $AM = "";
    
    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error)
        die($conn->connect_error);
    
    $usernames_query = "SELECT user.username FROM user";
    $usernames_list = $conn->query($usernames_query) or die(mysql_error()."<br />".$usernames_query);
    $usernames = array();

    while ($username = mysqli_fetch_array($usernames_list)){
        $usernames[] = $username["username"];
    }

    if(isset($_POST['submit']) && !empty($_POST['submit'])) {
        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $AM = $_POST['AM'];
        $department_id = $_POST['department'];

        $insert = "INSERT INTO user (username, firstname, surname, password, email, phone, type) VALUES ('$username', '$firstname', '$lastname', '$password', '$email', '$phone', 'student')";
        
        $conn->query($insert) or die(mysql_error()."<br />".$insert);

        $last_id = $conn->insert_id;

        $insert = "INSERT INTO student (AM, department_id, user_id) VALUES ('$AM', $department_id, $last_id)";
        
        $conn->query($insert) or die(mysql_error()."<br />".$insert);
        
        echo '<script type="text/javascript">window.location = "register_success.php"</script>';
    }
?>

<!DOCTYPE html>
<html lang="el">
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/register.css">
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
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
            <a href="index.php" style="text-decoration: none">< Αρχική</a>
            <br><br>
            <a href="choose_registration.php" style="text-decoration: none">< Προηγούμενη σελίδα</a>
        </div>
        <div class="registration" style="width: 600px;">
            <h2>Εγγραφή ως Φοιτητής/τρια</h2>
        </div>
        <div class="register-stud-form">
            <div style="text-align: left; padding: 10px;display: flex;">Τα πεδία με <span style="color:#810000">*</span> είναι υποχρεωτικά.</div>
            <form method="post">
                <div class="container">
                    <label for="firstname" class="required">Όνομα</label> 
                    <span>
                        <input type="text" class="inp" name="firstname" id="firstname">
                    </span>
                    <span class="error" id="firstname_error"></span>

                    <label for="lastname" class="required" style="margin-top: 20px">Επώνυμο</label>
                    <span>
                        <input type="text" class="inp" name="lastname" id="lastname">
                    </span>
                    <span class="error" id="lastname_error"></span>

                    <label for="username" class="required" style="margin-top: 20px">Όνομα χρήστη (username)</label>
                    <span>
                        <input type="text" class="inp" name="username" id="username">
                    </span>
                    <span class="error" id="username_error"></span>

                    <label for="password" class="required" style="margin-top: 20px">Κωδικός πρόσβασης</label>
                    <span>
                        <input type="password" class="inp" name="password" id="password">
                    </span>
                    <span class="error" id="password_error"></span>

                    <label for="confirm_password" class="required" style="margin-top: 20px">Επιβεβαίωση κωδικού πρόσβασης</label>
                    <span>
                        <input type="password" class="inp" name="confirm_password" id="confirm_password" readonly>
                    </span>
                    <span class="error" id="confirm_password_error"></span>

                    <label for="email" class="required" style="margin-top: 20px">Email</label>
                    <span>
                        <input type="text" class="inp" name="email" id="email">
                    </span>
                    <span class="error" id="email_error"></span>

                    <label for="confirm_email" class="required" style="margin-top: 20px">Επιβεβαίωση email</label>
                    <span>
                        <input type="text" class="inp" name="confirm_email" id="confirm_email" readonly>
                    </span>
                    <span class="error" id="confirm_email_error"></span>

                    <label for="phone" class="required" style="margin-top: 20px">Τηλέφωνο</label>
                    <span>
                        <input type="number" class="inp" name="phone" id="phone">
                    </span>
                    <span class="error" id="phone_error"></span>

                    <label for="university" class="required" style="margin-top: 20px">Α.Ε.Ι.</label>
                    <span>
                        <select class="select" name="university" id="university">
                            <option value="0" hidden>Επιλογή Α.Ε.Ι.</option>
                            <?php
                                // Get all the categories from category table
                                $sql = "select university.*, GROUP_CONCAT(department.name order by department.name) as department_names, GROUP_CONCAT(department.id order by department.name) as department_ids from university join department on university.id = department.university_id  group by university.id order by university.name;";
                                $all_universities = mysqli_query($conn, $sql);
                                $row_names = array();
                                $row_ids = array();
                                
                                // use a while loop to fetch data
                                // from the $all_universities variable
                                // and individually display as an option
                                while ($university = mysqli_fetch_array($all_universities)):;
                                    $row_names[] = $university["department_names"];
                                    $row_ids[] = $university["department_ids"];
                            ?>
                            <option value="<?php echo $university["id"];?>">
                            <?php echo $university["name"];
                                // To show the university name to the user
                            ?>
                            </option>
                            <?php
                                endwhile;
                                // While loop must be terminated
                            ?>
                        </select>
                    </span>
                    <span class="error" id="university_error"></span>

                    <label for="department" class="required" style="margin-top: 20px">Τμήμα</label>
                    <span>
                        <select class="select" name="department" id="department" disabled>
                            <option value="0" hidden selected>Επιλογή Τμήματος</option>
                        </select>
                    </span>
                    <span class="error" id="department_error"></span>

                    <label for="AM" class="required" style="margin-top: 20px">Αριθμός Μητρώου</label>
                    <span>
                        <input type="text" class="inp" name="AM" id="AM">
                    </span>
                    <span class="error" id="AM_error"></span>

                    <div class="container" style="margin-top: 20px">
                        <button type="submit" class="btn" name="submit" id="submit" disabled>Υποβολή</button>
                    </div>
                </div>
            </form>
        </div>
        <div style="text-align: center; padding: 10px;">
            <p>Έχεις ήδη λογαριασμό; <a href="login.php">Σύνδεση</a>.</p>
        </div> 
        <?php
            include 'bottom_base.php';
        ?>
        <script>
            const firstname = document.getElementById('firstname');
            const lastname = document.getElementById('lastname');
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const confirm_password = document.getElementById('confirm_password');
            const email = document.getElementById('email');
            const confirm_email = document.getElementById('confirm_email');
            const phone = document.getElementById('phone');
            const university = document.getElementById('university');
            const department = document.getElementById('department');
            const AM = document.getElementById('AM');
            const submit = document.getElementById('submit');
            let correct_fields = [false, false, false, false, false, false, false, false, false, false];

            firstname.addEventListener('focusout', (event) => {
                if(firstname.value === ''){
                    document.getElementById('firstname_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    firstname.style.border = "1px solid #810000";

                    correct_fields[0] = false;
                    check_correct_fields_no();
                } else {
                    if(!firstname.value.match(/^[A-Za-zΑ-Ωα-ωίϊΐόάέύϋΰήώ]+$/)) {
                        document.getElementById('firstname_error').innerHTML = "Το όνομα μπορεί να περιέχει μόνο ελληνικούς ή λατινικούς χαρακτήρες.";
                        firstname.style.border = "1px solid #810000";

                        correct_fields[0] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('firstname_error').innerHTML = "";
                        firstname.style.border = "1px solid #00b300";

                        correct_fields[0] = true;
                        check_correct_fields_no();
                    }
                }
            });

            lastname.addEventListener('focusout', (event) => {
                if(lastname.value === ''){
                    document.getElementById('lastname_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    lastname.style.border = "1px solid #810000";

                    correct_fields[1] = false;
                    check_correct_fields_no();
                } else {
                    if(!lastname.value.match(/^[A-Za-zΑ-Ωα-ωίϊΐόάέύϋΰήώ]+$/)) {
                        document.getElementById('lastname_error').innerHTML = "Το επώνυμο μπορεί να περιέχει μόνο ελληνικούς ή λατινικούς χαρακτήρες.";
                        lastname.style.border = "1px solid #810000";

                        correct_fields[1] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('lastname_error').innerHTML = "";
                        lastname.style.border = "1px solid #00b300";

                        correct_fields[1] = true;
                        check_correct_fields_no();
                    }
                }
            });

            username.addEventListener('focusout', (event) => {
                if(username.value === ''){
                    document.getElementById('username_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    username.style.border = "1px solid #810000";
                    
                    correct_fields[2] = false;
                    check_correct_fields_no();
                } else {
                    var usernames_list = JSON.parse('<?php echo json_encode($usernames); ?>');
                    if(!username.value.match(/^[a-zA-Z0-9]+([_ -]?[a-zA-Z0-9])*$/)) {
                        document.getElementById('username_error').innerHTML = "Το όνομα χρήστη μπορεί να περιέχει λατινικούς χαρακτήρες ή ψηφία.";
                        username.style.border = "1px solid #810000";

                        correct_fields[2] = false;
                        check_correct_fields_no();
                    } else if (usernames_list.includes(username.value)) {
                        document.getElementById('username_error').innerHTML = "Αυτό το όνομα χρήστη υπάρχει ήδη.";
                        username.style.border = "1px solid #810000";
                        
                        correct_fields[2] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('username_error').innerHTML = "";
                        username.style.border = "1px solid #00b300";

                        correct_fields[2] = true;
                        check_correct_fields_no();
                    }
                }
            });

            password.addEventListener('focusout', (event) => {
                if(password.value === ''){
                    document.getElementById('password_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    password.style.border = "1px solid #810000";
                    
                    correct_fields[3] = false;
                    check_correct_fields_no();
                } else {
                    if(!password.value.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/)) {
                        document.getElementById('password_error').innerHTML = "Ο κωδικός πρέπει να περιέχει τουλάχιστον 8 χαρακτήρες, 1 πεζό λατινικό χαρακτήρα, 1 κεφαλαίο λατινικό χαρακτήρα και 1 ψηφίο.";
                        password.style.border = "1px solid #810000";

                        correct_fields[3] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('password_error').innerHTML = "";
                        password.style.border = "1px solid #00b300";

                        confirm_password.readOnly = false;
                        
                        correct_fields[3] = true;
                        check_correct_fields_no();
                    }
                }
            });

            confirm_password.addEventListener('focusout', (event) => {
                if(confirm_password.value === '' && confirm_password.readOnly === false){
                    document.getElementById('confirm_password_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    confirm_password.style.border = "1px solid #810000";
                    
                    correct_fields[4] = false;
                    check_correct_fields_no();
                } else if(confirm_password.value !== '') {
                    if(password.value != confirm_password.value) {
                        document.getElementById('confirm_password_error').innerHTML = "Οι κωδικοί δεν ταιριάζουν.";
                        confirm_password.style.border = "1px solid #810000";

                        correct_fields[4] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('confirm_password_error').innerHTML = "";
                        confirm_password.style.border = "1px solid #00b300";
                        
                        correct_fields[4] = true;
                        check_correct_fields_no();
                    }
                }
            });

            email.addEventListener('focusout', (event) => {
                if(email.value === ''){
                    document.getElementById('email_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    email.style.border = "1px solid #810000";
                    
                    correct_fields[5] = false;
                    check_correct_fields_no();
                } else {
                    if(!email.value.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/)) {
                        document.getElementById('email_error').innerHTML = "Λάθος email.";
                        email.style.border = "1px solid #810000";
                    
                        correct_fields[5] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('email_error').innerHTML = "";
                        email.style.border = "1px solid #00b300";
                        
                        confirm_email.readOnly = false;
                        
                        correct_fields[5] = true;
                        check_correct_fields_no();
                    }
                }
            });

            confirm_email.addEventListener('focusout', (event) => {
                if(confirm_email.value === '' && confirm_email.readOnly === false){
                    document.getElementById('confirm_email_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    confirm_email.style.border = "1px solid #810000";
                    
                    correct_fields[6] = false;
                    check_correct_fields_no();
                } else if(confirm_email.value !== '') {
                    if(email.value != confirm_email.value) {
                        document.getElementById('confirm_email_error').innerHTML = "Τα email δεν ταιριάζουν.";
                        confirm_email.style.border = "1px solid #810000";
                    
                        correct_fields[6] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('confirm_email_error').innerHTML = "";
                        confirm_email.style.border = "1px solid #00b300";
                        
                        correct_fields[6] = true;
                        check_correct_fields_no();
                    }
                }
            });

            phone.addEventListener('focusout', (event) => {
                if(phone.value === ''){
                    document.getElementById('phone_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    phone.style.border = "1px solid #810000";
                    
                    correct_fields[7] = false;
                    check_correct_fields_no();
                } else {
                    if(phone.value.length != 10) {
                        document.getElementById('phone_error').innerHTML = "Το τηλέφωνο πρέπει να περιέχει ακριβώς 10 ψηφία.";
                        phone.style.border = "1px solid #810000";
                    
                        correct_fields[7] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('phone_error').innerHTML = "";
                        phone.style.border = "1px solid #00b300";
                        
                        correct_fields[7] = true;
                        check_correct_fields_no();
                    }
                }
            });

            university.addEventListener('focusout', (event) => {
                if(university.selectedIndex <= 0){
                    document.getElementById('university_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    university.style.border = "1px solid #810000";
                    
                    correct_fields[8] = false;
                    check_correct_fields_no();
                }
            });

            university.addEventListener('change', (event) => {
                document.getElementById('university_error').innerHTML = "";
                university.style.border = "1px solid #00b300";
                department.disabled = false;

                var all_department_names = JSON.parse('<?php echo json_encode($row_names); ?>');
                var all_department_ids = JSON.parse('<?php echo json_encode($row_ids); ?>');
                var selected_department_names = all_department_names[university.selectedIndex - 1].split(',');
                var selected_department_ids = all_department_ids[university.selectedIndex - 1].split(',');
                
                department.innerHTML = "";
                var el = document.createElement("option");
                el.textContent = 'Επιλογή Τμήματος';
                el.value = 0;
                el.hidden = true;
                department.appendChild(el);
                for(var i = 0; i < selected_department_names.length; i++) {
                    var el = document.createElement("option");
                    el.textContent = selected_department_names[i];
                    el.value = selected_department_ids[i];
                    department.appendChild(el);
                }
                
                correct_fields[8] = true;
                check_correct_fields_no();
            });

            department.addEventListener('focusout', (event) => {
                if(department.selectedIndex <= 0){
                    document.getElementById('department_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    department.style.border = "1px solid #810000";
                    
                    correct_fields[9] = false;
                    check_correct_fields_no();
                } else {
                    document.getElementById('department_error').innerHTML = "";
                    department.style.border = "1px solid #00b300";

                    correct_fields[9] = true;
                    check_correct_fields_no();
                }
            });

            AM.addEventListener('focusout', (event) => {
                if(AM.value === ''){
                    document.getElementById('AM_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    AM.style.border = "1px solid #810000";
                    
                    correct_fields[10] = false;
                    check_correct_fields_no();
                } else {
                    document.getElementById('AM_error').innerHTML = "";
                    AM.style.border = "1px solid #00b300";
                    
                    correct_fields[10] = true;
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

        </script>
    </body>
</html>