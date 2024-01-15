<?php
    // If user is already logged in
    // go to home page
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    $firstname = $surname = $username = $email = $phone = $university = $department = $AM = "";
    
    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error)
        die($conn->connect_error);

    // Data sanitization to prevent SQL injection
    $username = $_SESSION['username'];

    if($_SESSION['type'] == 'student'){
        $query = "SELECT user.*, student.AM as AM, department.name as department, university.name as university FROM user, student, department, university WHERE user.username='$username' and user.id = student.user_id and department.id = student.department_id and department.university_id = university.id;";
        $results = $conn->query($query) or die(mysql_error()."<br />".$query);

        $row = mysqli_fetch_array($results);
        $firstname = $row['firstname'];
        $surname = $row['surname'];
        $email = $row['email'];
        $phone = $row['phone'];
        $AM = $row['AM'];
        $university = $row['university'];
        $department = $row['department'];
    } else {
        $query = "SELECT user.*, company.id as com_id, company.*, municipality.name as municipality, municipality.id as municipality_id, region.name as region, region.id as region_id FROM user, company, municipality, region WHERE user.username='$username' and user.id = company.user_id and municipality.id = company.municipality_id and municipality.region_id = region.id;";
        $results = $conn->query($query) or die(mysql_error()."<br />".$query);
    
        $row = mysqli_fetch_array($results);
        $firstname = $row['firstname'];
        $surname = $row['surname'];
    
        $identity_card = $row['identity_card'];
        $identity_card_array = preg_split('/(?=\d)/', $identity_card, 2);
        $identity_card_text = $identity_card_array[0];
        $identity_card_number = $identity_card_array[1];
    
        $email = $row['email'];
        $phone = $row['phone'];

        $company_name = $row['company_name'];
        $address_text = $row['address'];
        $address_number = $row['address_number'];
        $zip_code = $row['zip_code'];
        $country = $row['country'];
        $site = $row['site'];
        $activity_field = $row['activity_field'];
    
        if($row['type'] == 'public') {
            $type = 'Ιδιωτικός Φορέας';
        } else if($row['type'] == 'private') {
            $type = 'Δημόσιος Φορέας';
        } else if($row['type'] == 'NGO') {
            $type = 'Μ.Κ.Ο.';
        } else {
            $type = 'Άλλο';
        }
    
        $VAT = $row['VAT'];
        $tax_office = $row['tax_office'];
        $company_email = $row['company_email'];
        $company_phone = $row['company_phone'];
        $municipality = $row['municipality'];
        $municipality_id = $row['municipality_id'];
        $region = $row['region'];
        $region_id = $row['region_id'];
        $id = $row['com_id'];
    }

    if(isset($_POST['phone_submit']) && !empty($_POST['phone_submit'])) {
        $phone = $_POST['phone'];

        $update = "UPDATE user SET user.phone='$phone' where user.username='$username';";
        
        $conn->query($update) or die(mysql_error()."<br />".$update);

        echo '<script type="text/javascript">window.location = "#success-modal"</script>';
    }

    if(isset($_POST['email_submit']) && !empty($_POST['email_submit'])) {
        $email = $_POST['email'];

        $update = "UPDATE user SET user.email='$email' where user.username='$username';";

        $conn->query($update) or die(mysql_error()."<br />".$update);

        echo '<script type="text/javascript">window.location = "#success-modal"</script>';
    }

    if(isset($_POST['company_phone_submit']) && !empty($_POST['company_phone_submit'])) {
        $phone = $_POST['company_phone'];

        $update = "UPDATE company SET company.company_phone='$phone' where company.id='$id';";

        $conn->query($update) or die(mysql_error()."<br />".$update);

        echo '<script type="text/javascript">window.location = "#success-modal"</script>';
    }

    if(isset($_POST['company_email_submit']) && !empty($_POST['company_email_submit'])) {
        $email = $_POST['company_email'];

        $update = "UPDATE company SET company.company_email='$email' where company.id='$id';";

        $conn->query($update) or die(mysql_error()."<br />".$update);

        echo '<script type="text/javascript">window.location = "#success-modal"</script>';
    }

    if(isset($_POST['location_submit']) && !empty($_POST['location_submit'])) {
        $country = $_POST['country'];
        $address_text = $_POST['address_text'];
        $address_number = $_POST['address_number'];
        $zip_code = $_POST['zip_code'];
        $municipality_id = $_POST['municipality'];

        $update = "UPDATE company SET company.country='$country', company.address='$address_text', company.address_number='$address_number', company.zip_code='$zip_code', company.municipality_id='$municipality_id' where company.id='$id';";    

        $conn->query($update) or die(mysql_error()."<br />".$update);

        echo '<script type="text/javascript">window.location = "#success-modal"</script>';
    }
?>

<!DOCTYPE html>
<html lang="el">
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/profile.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <?php
            include 'top_base.php';
        ?>
        <?php
            include 'nav_bar.php';
        ?>
         
        <div style="padding-bottom: 20px">
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="index.php">Αρχική Σελίδα</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">Προφίλ</div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">Στοιχεία Λογαριασμού</div>
        </div>

        <?php
            include 'profile_menu.php';
        ?>

        <div id="success-modal" class="modal">
            <div class="modal_content">
                <div class="success">
                    <div class="checkmark_box">
                        <i class="checkmark">✓</i>
                    </div>
                    <h1>Η ενημέρωση των στοιχείων σου ολοκληρώθηκε με επιτυχία!</h1>

                    <button type="submit" class="btn modal_close" id="modal_close">Κλείσιμο</button>
                </div>
            </div>
        </div>

        <div class="information">
            <div class="column"> 
                <div class="column">
                    <div>
                        <i class='fas' style="padding-top: 15px;">&#xf2bd;</i>
                        <h2>Στοιχεία <?php echo $firstname . " " . $surname; ?></h2>
                    </div>
                    <div class="personal_info">
                        <div>
                            <label for="username">Όνομα χρήστη (username)</label>
                            <span style="margin-bottom: 30px">
                                <input readonly type="text" class="inp" name="username" id="username" value="<?php echo $username; ?>">
                            </span>

                            <?php if(isset($_SESSION['type']) and $_SESSION['type'] == 'student') : ?>
                                <label for="university">Σχολή</label>
                                <span style="margin-bottom: 30px">
                                    <input readonly type="text" class="inp" name="university" id="university" value="<?php echo $university; ?>">
                                </span>

                                <label for="department">Τμήμα</label>
                                <span style="margin-bottom: 30px">
                                    <input readonly type="text" class="inp" name="department" id="department" value="<?php echo $department; ?>">
                                </span>

                                <label for="AM">Αριθμός Μητρώου</label>
                                <span style="margin-bottom: 30px">
                                    <input readonly type="text" class="inp" name="AM" id="AM" value="<?php echo $AM; ?>">
                                </span>
                            <?php else : ?>
                                <label for="">Αριθμός Ταυτότητας</label>
                                <span class="divide">
                                    <div class="divide_fields">
                                        <label for="identity_card_text">Ψηφία</label>
                                        <input readonly type="text" class="inp" name="identity_card_text" id="identity_card_text" value="<?php echo $identity_card_text; ?>" style="width: 70px;">
                                    </div>
                                    <div class="divide_fields">
                                        <label for="identity_card_number">Χαρακτήρες</label>
                                        <input readonly type="number" class="inp" name="identity_card_number" id="identity_card_number" value="<?php echo $identity_card_number; ?>" style="width: 100px;">
                                    </div>
                                </span>
                            <?php endif; ?>

                            <form method="post">
                                <label for="email">Email</label>
                                <span style="display:flex;margin-bottom: 0;margin-right: 23px;">
                                    <input type="text" style="width:fit-content;margin-right:5px;" class="inp" name="email" id="email" value="<?php echo $email; ?>">
                                    <button type="submit" class="edit-btn" name="email_submit" id="email_submit"><img alt="pen" src="../img/pen.png" style="width: 12px; margin-right: 10px;">Αλλαγή</button>
                                </span>
                                <span class="error" id="email_error"></span>
                            </form>

                            <form method="post">
                                <label for="phone">Τηλέφωνο</label>
                                <span style="display:flex;margin-bottom: 0;margin-right: 23px;">
                                    <input type="number" style="width:fit-content;margin-right:5px;" class="inp" name="phone" id="phone" value="<?php echo $phone; ?>">
                                    <button type="submit" class="edit-btn" name="phone_submit" id="phone_submit"><img alt="pen" src="../img/pen.png" style="width: 12px; margin-right: 10px;">Αλλαγή</button>
                                </span>
                                <span class="error" id="phone_error"></span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(isset($_SESSION['type']) and $_SESSION['type'] == 'company') : ?>
                <div class="column">
                    <div>
                        <i class='fas' style="padding-top: 15px; font-size: 70px;">&#xf1ad;</i>
                    </div>
                    <div class="personal_info">
                        <div>
                            <label for="site">Ιστοσελίδα</label>
                            <span>
                                <input readonly type="text" class="inp" name="site" id="site" value="<?php echo $site; ?>">
                            </span>

                            <label for="activity_field">Πεδίο δραστηριότητας</label>
                            <span>
                                <input readonly type="text" class="inp" name="activity_field" id="activity_field" value="<?php echo $activity_field; ?>">
                            </span>

                            <label for="type">Είδος Φορέα</label>
                            <span>
                                <input readonly type="text" class="inp" name="type" id="type" value="<?php echo $type; ?>">
                            </span>

                            <label for="VAT">Α.Φ.Μ.</label>
                            <span>
                                <input readonly type="text" class="inp" name="VAT" id="VAT" value="<?php echo $VAT; ?>">
                            </span>

                            <label for="tax_office">Δ.Ο.Υ.</label>
                            <span>
                                <input readonly type="text" class="inp" name="tax_office" id="tax_office" value="<?php echo $tax_office; ?>">
                            </span>

                            <form method="post">
                                <label for="company_email">Email</label>
                                <span style="display:flex;margin-bottom: 0">
                                    <input type="text" style="width:fit-content;margin-right:5px;" class="inp" name="company_email" id="company_email" value="<?php echo $company_email; ?>">
                                    <button type="submit" class="edit-btn" name="company_email_submit" id="company_email_submit"><img alt="pen" src="../img/pen.png" style="width: 12px; margin-right: 10px;">Αλλαγή</button>
                                </span>
                                <span class="error" id="company_email_error"></span>
                            </form>

                            <form method="post">
                                <label for="company_phone">Τηλέφωνο (σταθερό)</label>
                                <span style="display:flex;margin-bottom: 0">
                                    <input type="number" style="width:fit-content;margin-right:5px;" class="inp" name="company_phone" id="company_phone" value="<?php echo $company_phone; ?>">
                                    <button type="submit" class="edit-btn" name="company_phone_submit" id="company_phone_submit"><img alt="pen" src="../img/pen.png" style="width: 12px; margin-right: 10px;">Αλλαγή</button>
                                </span>
                                <span class="error" id="company_phone_error"></span>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div>
                        <i class='fas' style="padding-top: 15px; font-size: 70px;">&#xf279;</i>
                    </div>
                    <form method="post" class="personal_info">
                        <div>
                            <label>Χώρα</label>
                            <span style="margin-bottom: 0">
                                <select class="select" name="country" id="country">
                                    <option value="<?php echo $country; ?>" hidden selected><?php echo $country; ?></option>
                                    <option value="Ελλάδα">Ελλάδα</option>
                                    <option value="Ολλανδία">Ολλανδία</option>
                                    <option value="Μάλτα">Μάλτα</option>
                                    <option value="Πολωνία">Πολωνία</option>
                                    <option value="Γερμανία">Γερμανία</option>
                                    <option value="Αυστρία">Αυστρία</option>
                                    <option value="Λιθουανία">Λιθουανία</option>
                                    <option value="Ουγγαρία">Ουγγαρία</option>
                                    <option value="Ρουμανία">Ρουμανία</option>
                                    <option value="Βέλγιο">Βέλγιο</option>
                                    <option value="Ιρλανδία">Ιρλανδία</option>
                                    <option value="Φινλανδία">Φινλανδία</option>
                                    <option value="Κροατία">Κροατία</option>
                                    <option value="Δανία">Δανία</option>
                                    <option value="Κύπρος">Κύπρος</option>
                                    <option value="Σλοβενία">Σλοβενία</option>
                                    <option value="Πορτογαλία">Πορτογαλία</option>
                                    <option value="Λουξεμβούργο">Λουξεμβούργο</option>
                                    <option value="Ισπανία">Ισπανία</option>
                                    <option value="Σλοβακία">Σλοβακία</option>
                                    <option value="Γαλλία">Γαλλία</option>
                                    <option value="Τσεχία">Τσεχία</option>
                                    <option value="Λετονία">Λετονία</option>
                                    <option value="Ιταλία">Ιταλία</option>
                                    <option value="Βουλγαρία">Βουλγαρία</option>
                                    <option value="Σουηδία">Σουηδία</option>
                                    <option value="Εσθονία">Εσθονία</option>
                                </select>
                            </span>
                            <span class="error" id="country_error"></span>

                            <label>Διεύθυνση</label>
                            <span style="margin-bottom: 0" class="divide">
                                <div class="divide_fields">
                                    <label for="address_text">Οδός</label>
                                    <input type="text" class="inp" name="address_text" id="address_text" value="<?php echo $address_text; ?>">
                                </div>
                                <div class="divide_fields">
                                    <label for="address_number">Αριθμός</label>
                                    <input type="number" class="inp" name="address_number" id="address_number" style="width: 70px;" value="<?php echo $address_number; ?>">
                                </div>
                                <div class="divide_fields">
                                    <label for="zip_code">Τ.Κ.</label>
                                    <input type="number" class="inp" name="zip_code" id="zip_code" style="width: 100px;" value="<?php echo $zip_code; ?>">
                                </div>
                            </span>
                            <span class="error" id="address_error"></span>

                            <label>Περιφέρεια</label>
                            <span style="margin-bottom: 0">
                                <select class="select" name="region" id="region">
                                    <option value="<?php echo $region; ?>" hidden selected><?php echo $region; ?></option>
                                    <?php
                                        // Get all the categories from category table
                                        $sql = "select region.*, GROUP_CONCAT(municipality.name order by municipality.name) as municipality_names, GROUP_CONCAT(municipality.id order by municipality.name) as municipality_ids from region join municipality on region.id = municipality.region_id  group by region.id order by region.name;";
                                        $all_regions = mysqli_query($conn, $sql);
                                        $row_names = array();
                                        $row_ids = array();
                                        
                                        // use a while loop to fetch data
                                        // from the $all_regions variable
                                        // and individually display as an option
                                        while ($region = mysqli_fetch_array($all_regions)):;
                                            $row_names[] = $region["municipality_names"];
                                            $row_ids[] = $region["municipality_ids"];
                                    ?>
                                    <option value="<?php echo $region["id"];?>">
                                    <?php echo $region["name"];
                                        // To show the region name to the user
                                    ?>
                                    </option>
                                    <?php
                                        endwhile;
                                        // While loop must be terminated
                                    ?>
                                </select>
                            </span>
                            <span class="error" id="region_error"></span>

                            <label>Δήμος</label>
                            <span style="margin-bottom: 0">
                                <select class="select" name="municipality" id="municipality" value="<?php echo $municipality; ?>">
                                    <option value="<?php echo $municipality_id; ?>" hidden selected><?php echo $municipality; ?></option>
                                    <?php
                                        // Get all the categories from category table
                                        $sql = "select municipality.name, municipality.id from municipality where municipality.region_id = $region_id order by municipality.name;";
                                        $all_municipalities = mysqli_query($conn, $sql);
                                        
                                        // use a while loop to fetch data
                                        // from the $all_municipalities variable
                                        // and individually display as an option
                                        while ($municipality = mysqli_fetch_array($all_municipalities)):;
                                    ?>
                                    <option value="<?php echo $municipality["id"];?>">
                                    <?php echo $municipality["name"];
                                        // To show the municipality name to the user
                                    ?>
                                    </option>
                                    <?php
                                        endwhile;
                                        // While loop must be terminated
                                    ?>
                                </select>
                            </span>
                            <span class="error" id="municipality_error"></span>

                            <div class="container">
                                <button type="submit" class="btn" name="location_submit" id="location_submit"><img alt="pen" src="../img/pen.png" style="width: 12px; margin-right: 10px;">Αλλαγή</button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
        <?php
            include 'bottom_base.php';
        ?>
        
        <script>
            document.getElementById("profile_menu_profile").classList.add("active");
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const email_submit = document.getElementById('email_submit');
            const phone_submit = document.getElementById('phone_submit');

            email.addEventListener('input', (event) => {
                if(email.value !== ''){
                    if(!email.value.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/)) {
                        document.getElementById('email_error').innerHTML = "Λάθος email.";
                        email.style.border = "1px solid #810000";

                        document.getElementById('email_submit').disabled = true;
                    } else {
                        document.getElementById('email_error').innerHTML = "";
                        email.style.border = "1px solid #00b300";

                        document.getElementById('email_submit').disabled = false;
                    }
                }
            });

            phone.addEventListener('input', (event) => {
                if(phone.value !== ''){
                    if(phone.value.length != 10) {
                        document.getElementById('phone_error').innerHTML = "Το τηλέφωνο πρέπει να περιέχει ακριβώς 10 ψηφία.";
                        phone.style.border = "1px solid #810000";
                    
                        document.getElementById('phone_submit').disabled = true;
                    } else {
                        document.getElementById('phone_error').innerHTML = "";
                        phone.style.border = "1px solid #00b300";
                        
                        document.getElementById('phone_submit').disabled = false;
                    }
                }
            });

            email_submit.addEventListener('click', (event) => {
                var result = confirm("Είσαι σίγουρος/η ότι θες να αλλάξεις το email σου?");
                if (result) {
                    email_submit.value = "btn_clicked";
                }
            });

            phone_submit.addEventListener('click', (event) => {
                var result = confirm("Είσαι σίγουρος/η ότι θες να αλλάξεις το τηλέφωνό σου?");
                if (result) {
                    phone_submit.value = "btn_clicked";
                }
            });

            modal_close.addEventListener('click', (event) => {
                window.location = "";
            });

            <?php if(isset($_SESSION['type']) and $_SESSION['type'] == 'company') : ?>
                const company_email = document.getElementById('company_email');
                const company_phone = document.getElementById('company_phone');
                const address_text = document.getElementById('address_text');
                const address_number = document.getElementById('address_number');
                const zip_code = document.getElementById('zip_code');
                const country = document.getElementById('country');
                const region = document.getElementById('region');
                const municipality = document.getElementById('municipality');
                const company_email_submit = document.getElementById('company_email_submit');
                const company_phone_submit = document.getElementById('company_phone_submit');
                const location_submit = document.getElementById('location_submit');
                let correct_fields = [true, true, true, true, true, true];
                company_email.addEventListener('focusout', (event) => {
                    if(company_email.value !== ''){
                        if(!company_email.value.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/)) {
                            document.getElementById('company_email_error').innerHTML = "Λάθος email.";
                            company_email.style.border = "1px solid #810000";

                            document.getElementById('company_email_submit').disabled = true;
                        } else {
                            document.getElementById('company_email_error').innerHTML = "";
                            company_email.style.border = "1px solid #00b300";

                            document.getElementById('company_email_submit').disabled = false;
                        }
                    }
                });

                company_phone.addEventListener('focusout', (event) => {
                    if(company_phone.value !== ''){
                        if(company_phone.value.length != 10) {
                            document.getElementById('company_phone_error').innerHTML = "Το τηλέφωνο πρέπει να περιέχει ακριβώς 10 ψηφία.";
                            company_phone.style.border = "1px solid #810000";
                        
                            document.getElementById('company_phone_submit').disabled = true;
                        } else {
                            document.getElementById('company_phone_error').innerHTML = "";
                            company_phone.style.border = "1px solid #00b300";
                            
                            document.getElementById('company_phone_submit').disabled = false;
                        }
                    }
                });

                address_text.addEventListener('focusout', (event) => {
                    if(address_text.value !== ''){
                        document.getElementById('address_error').innerHTML = "";
                        address_text.style.border = "1px solid #00b300";
                        
                        correct_fields[0] = true;
                        check_correct_fields_no();
                    }
                });

                address_number.addEventListener('focusout', (event) => {
                    if(address_number.value !== ''){
                        document.getElementById('address_error').innerHTML = "";
                        address_number.style.border = "1px solid #00b300";
                        
                        correct_fields[1] = true;
                        check_correct_fields_no();
                    }
                });

                zip_code.addEventListener('focusout', (event) => {
                    if(zip_code.value !== ''){
                        if(zip_code.value.length != 5) {
                            document.getElementById('address_error').innerHTML = "Ο Τ.Κ. πρέπει να περιέχει ακριβώς 5 ψηφία.";
                            zip_code.style.border = "1px solid #810000";
                        
                            correct_fields[2] = false;
                            check_correct_fields_no();
                        } else {
                            document.getElementById('address_error').innerHTML = "";
                            zip_code.style.border = "1px solid #00b300";
                            
                            correct_fields[2] = true;
                            check_correct_fields_no();
                        }
                    }
                });

                country.addEventListener('focusout', (event) => {
                    if(country.selectedIndex != 0){
                        document.getElementById('country_error').innerHTML = "";
                        country.style.border = "1px solid #00b300";

                        correct_fields[3] = true;
                        check_correct_fields_no();
                    }
                });

                region.addEventListener('change', (event) => {
                    document.getElementById('region_error').innerHTML = "";
                    region.style.border = "1px solid #00b300";
                    municipality.disabled = false;

                    var all_municipality_names = JSON.parse('<?php echo json_encode($row_names); ?>');
                    var all_municipality_ids = JSON.parse('<?php echo json_encode($row_ids); ?>');
                    var selected_municipality_names = all_municipality_names[region.selectedIndex - 1].split(',');
                    var selected_municipality_ids = all_municipality_ids[region.selectedIndex - 1].split(',');
                    
                    municipality.innerHTML = "";
                    var el = document.createElement("option");
                    el.textContent = 'Επιλογή Δήμου';
                    el.value = 0;
                    el.hidden = true;
                    municipality.appendChild(el);
                    for(var i = 0; i < selected_municipality_names.length; i++) {
                        var el = document.createElement("option");
                        el.textContent = selected_municipality_names[i];
                        el.value = selected_municipality_ids[i];
                        municipality.appendChild(el);
                    }
                    
                    correct_fields[4] = true;
                    correct_fields[5] = false;
                    check_correct_fields_no();
                });

                municipality.addEventListener('focusout', (event) => {
                    if(municipality.selectedIndex != 0){
                        document.getElementById('municipality_error').innerHTML = "";
                        municipality.style.border = "1px solid #00b300";

                        correct_fields[5] = true;
                        check_correct_fields_no();
                    }
                });

                company_email_submit.addEventListener('click', (event) => {
                    var result = confirm("Είσαι σίγουρος/η ότι θες να αλλάξεις το email του φορέα υποδοχής?");
                    if (result) {
                        company_email_submit.value = "btn_clicked";
                    }
                });

                company_phone_submit.addEventListener('click', (event) => {
                    var result = confirm("Είσαι σίγουρος/η ότι θες να αλλάξεις το τηλέφωνο του φορέα υποδοχής?");
                    if (result) {
                        company_phone_submit.value = "btn_clicked";
                    }
                });

                location_submit.addEventListener('click', (event) => {
                    var result = confirm("Είσαι σίγουρος/η ότι θες να αλλάξεις την τοποθεσία του φορέα υποδοχής?");
                    if (result) {
                        location_submit.value = "btn_clicked";
                    }
                });

                function check_correct_fields_no() {
                    if(correct_fields.every(v => v === true)) {
                        document.getElementById('location_submit').disabled = false;
                    } else {
                        document.getElementById('location_submit').disabled = true;
                    }
                }
            <?php endif; ?>

        </script>
    </body>
</html>