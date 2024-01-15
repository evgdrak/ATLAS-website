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

    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error)
        die($conn->connect_error);
    
    $username = $firstname = $surname = $password = $user_email = $user_phone = $identity_card = $company_name = $address = $address_number = $zip_code = $country = $site = $activity_field = $type = $VAT = $tax_office = $company_email = $company_phone = $municipality_id = "";
    $usernames_query = "SELECT user.username FROM user";
    $usernames_list = $conn->query($usernames_query) or die(mysql_error()."<br />".$usernames_query);
    $usernames = array();

    while ($username = mysqli_fetch_array($usernames_list)){
        $usernames[] = $username["username"];
    }

    if(isset($_POST['submit']) && !empty($_POST['submit'])) {
        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $surname = $_POST['surname'];
        $password = $_POST['password'];
        $user_email = $_POST['user_email'];
        $user_phone = $_POST['user_phone'];
        $identity_card = $_POST['identity_card_text'].$_POST['identity_card_number'];
        $company_name = $_POST['company_name'];
        $address = $_POST['address_text'];
        $address_number = $_POST['address_number'];
        $zip_code = $_POST['zip_code'];
        $country = $_POST['country'];
        $site = $_POST['site'];
        $activity_field = $_POST['activity_field'];
        $type = $_POST['type'];
        $VAT = $_POST['VAT'];
        $tax_office = $_POST['tax_office'];
        $company_email = $_POST['company_email'];
        $company_phone = $_POST['company_phone'];
        $municipality_id = $_POST['municipality'];

        $insert = "INSERT INTO user (username, firstname, surname, password, email, phone, type) VALUES ('$username', '$firstname', '$surname', '$password', '$user_email', '$user_phone', 'company');";
        
        $conn->query($insert) or die(mysql_error()."<br />".$insert);

        $last_id = $conn->insert_id;

        $insert = "INSERT INTO company (identity_card, company_name, address, address_number, zip_code, country, site, activity_field, type, VAT, tax_office, company_email, company_phone, municipality_id, user_id) VALUES ('$identity_card', '$company_name', '$address', $address_number, $zip_code, '$country', '$site', '$activity_field', '$type', '$VAT', '$tax_office', '$company_email', '$company_phone', $municipality_id, $last_id)";
        
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
        <br>
        <div class="form">
            <div class="registration">
                <h2>Εγγραφή ως Φορέας Υποδοχής</h2>
            </div>
            <div class="register-form">
                <div style="text-align: left; padding: 10px; border: 1px solid;display: flex;">Τα πεδία με <span style="color:#810000">*</span> είναι υποχρεωτικά.</div>
                <form method="post">
                    <div class="container_data">
                        <div class="container container_left">
                            <h2>Στοιχεία Λογαριασμού Χρήστη</h2>
                            <label for="firstname" class="required">Όνομα</label>
                            <span>
                                <input type="text" class="inp" name="firstname" id="firstname">
                            </span>
                            <span class="error" id="firstname_error"></span>

                            <label for="surname" class="required" style="margin-top: 25px">Επώνυμο</label>
                            <span>
                                <input type="text" class="inp" name="surname" id="surname">
                            </span>
                            <span class="error" id="surname_error"></span>

                            <label for="username" class="required" style="margin-top: 25px">Όνομα χρήστη (username)</label>
                            <span>
                                <input type="text" class="inp" name="username" id="username">
                            </span>
                            <span class="error" id="username_error"></span>

                            <label for="password" class="required" style="margin-top: 25px">Κωδικός πρόσβασης</label>
                            <span>
                                <input type="password" class="inp" name="password" id="password">
                            </span>
                            <span class="error" id="password_error"></span>

                            <label for="confirm_password" class="required" style="margin-top: 25px">Επιβεβαίωση κωδικού πρόσβασης</label>
                            <span>
                                <input type="password" class="inp" name="confirm_password" id="confirm_password" readonly>
                            </span>
                            <span class="error" id="confirm_password_error"></span>

                            <label for="user_email" class="required" style="margin-top: 25px">Email χρήστη</label>
                            <span>
                                <input type="text" class="inp" name="user_email" id="user_email">
                            </span>
                            <span class="error" id="user_email_error"></span>

                            <label for="confirm_user_email" class="required" style="margin-top: 25px">Επιβεβαίωση email χρήστη</label>
                            <span>
                                <input type="text" class="inp" name="confirm_user_email" id="confirm_user_email" readonly>
                            </span>
                            <span class="error" id="confirm_user_email_error"></span>

                            <label for="user_phone" class="required" style="margin-top: 25px">Τηλέφωνο χρήστη</label>
                            <span>
                                <input type="number" class="inp" name="user_phone" id="user_phone">
                            </span>
                            <span class="error" id="user_phone_error"></span>

                            <label class="required" style="margin-top: 25px">Αριθμός Ταυτότητας</label>
                            <span class="divide">
                                <div class="divide_fields">
                                    <label for="identity_card_text" class="required">Χαρακτήρες</label>
                                    <input type="text" class="inp" name="identity_card_text" id="identity_card_text" style="width: 70px;">
                                </div>
                                <div class="divide_fields">
                                    <label for="identity_card_number" class="required">Ψηφία</label>
                                    <input type="number" class="inp" name="identity_card_number" id="identity_card_number" style="width: 100px;">
                                </div>
                            </span>
                            <span class="error" id="identity_card_error"></span>
                        </div>

                        <div class="container container_right" style="background-color: #e4c487;">
                            <h2>Στοιχεία Φορέα Υποδοχής Πρακτικής Άσκησης</h2>
                            <label for="company_name" class="required">Όνομα εταιρίας</label>
                            <span>
                                <input type="text" class="inp" name="company_name" id="company_name">
                            </span>
                            <span class="error" id="company_name_error"></span>

                            <label for="country" class="required" style="margin-top: 25px">Χώρα</label>
                            <span>
                                <select class="select" name="country" id="country">
                                    <option value="0" hidden selected>Επιλογή Χώρας</option>
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

                            <label class="required" style="margin-top: 25px">Διεύθυνση</label>
                            <span class="divide">
                                <div class="divide_fields">
                                    <label for="address_text" class="required">Οδός</label>
                                    <input type="text" class="inp" name="address_text" id="address_text">
                                </div>
                                <div class="divide_fields">
                                    <label for="address_number" class="required">Αριθμός</label>
                                    <input type="number" class="inp" name="address_number" id="address_number" style="width: 70px;">
                                </div>
                                <div class="divide_fields">
                                    <label for="zip_code" class="required">Τ.Κ.</label>
                                    <input type="number" class="inp" name="zip_code" id="zip_code" style="width: 100px;">
                                </div>
                            </span>
                            <span class="error" id="address_error"></span>

                            <label for="region" class="required" style="margin-top: 25px">Περιφέρεια</label>
                            <span>
                                <select class="select" name="region" id="region">
                                    <option value="0" hidden>Επιλογή Περιφέρειας</option>
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

                            <label for="municipality" class="required" style="margin-top: 25px">Δήμος</label>
                            <span>
                                <select class="select" name="municipality" id="municipality" disabled>
                                    <option value="0" hidden>Επιλογή Δήμου</option>
                                </select>
                            </span>
                            <span class="error" id="municipality_error"></span>

                            <label for="site" class="required" style="margin-top: 25px">Ιστοσελίδα</label>
                            <span>
                                <input type="text" class="inp" name="site" id="site">
                            </span>
                            <span class="error" id="site_error"></span>

                            <label for="activity_field" class="required" style="margin-top: 25px">Πεδίο δραστηριότητας</label>
                            <span>
                                <select class="select" name="activity_field" id="activity_field">
                                    <option value="0" hidden selected>Επιλογή Πεδίου Δραστηριότητας</option>
                                    <option value="Αθλητισμός">Αθλητισμός</option>
                                    <option value="Άλλο">Άλλο</option>
                                    <option value="Βιοϊατρική">Βιοϊατρική</option>
                                    <option value="Βιομηχανία (γενικά)">Βιομηχανία (γενικά)</option>
                                    <option value="Βιοτεχνολογία">Βιοτεχνολογία</option>
                                    <option value="Γεωργία, Κτηνοτροφία">Γεωργία, Κτηνοτροφία</option>
                                    <option value="Γραφιστική, Σχέδιο">Γραφιστική, Σχέδιο</option>
                                    <option value="Δημόσιες σχέσεις">Δημόσιες σχέσεις</option>
                                    <option value="Δημόσιες υπηρεσίες">Δημόσιες υπηρεσίες</option>
                                    <option value="Διατροφή, Γαστρονομί">Διατροφή, Γαστρονομία</option>
                                    <option value="Διαφήμιση">Διαφήμιση</option>
                                    <option value="Διαχείριση ακινήτων">Διαχείριση ακινήτων</option>
                                    <option value="Διαχείριση ανθρώπινου δυναμικού">Διαχείριση ανθρώπινου δυναμικού</option>
                                    <option value="Εκδόσεις, Εκτυπώσεις">Εκδόσεις, Εκτυπώσεις</option>
                                    <option value="Εκπαιδευτικοί φορείς">Εκπαιδευτικοί φορείς</option>
                                    <option value="Ενέργεια">Ενέργεια</option>
                                    <option value="Ηλεκτρονικά">Ηλεκτρονικά</option>
                                    <option value="Ηλεκτρονικό εμπόριο">Ηλεκτρονικό εμπόριο</option>
                                    <option value="Θέρμανση, Κλιματισμός">Θέρμανση, Κλιματισμός</option>
                                    <option value="Ιατρική">Ιατρική</option>
                                    <option value="Κοινωνικές Υπηρεσίες">Κοινωνικές Υπηρεσίες</option>
                                    <option value="Λιανικό εμπόριο">Λιανικό εμπόριο</option>
                                    <option value="Λογισμικό">Λογισμικό</option>
                                    <option value="Μάρκετινγκ">Μάρκετινγκ</option>
                                    <option value="Μέσα Μαζικής Ενημέρωσης">Μέσα Μαζικής Ενημέρωσης</option>
                                    <option value="Μεταφορές, Logistic">Μεταφορές, Logistics</option>
                                    <option value="Μηχανολογία">Μηχανολογία</option>
                                    <option value="Μικροηλεκτρονική">Μικροηλεκτρονική</option>
                                    <option value="Μοριακή βιολογία">Μοριακή βιολογία</option>
                                    <option value="Νομικά">Νομικά</option>
                                    <option value="Οικολογία, Προστασία Περιβάλλοντος, Ανακύκλωση">Οικολογία, Προστασία Περιβάλλοντος, Ανακύκλωση</option>
                                    <option value="Οικονομία, Τραπεζική, Ασφάλειες">Οικονομία, Τραπεζική, Ασφάλειες</option>
                                    <option value="Οπτικά">Οπτικά</option>
                                    <option value="Παροχή δικτυακών υπηρεσιών">Παροχή δικτυακών υπηρεσιών</option>
                                    <option value="Πετρελαιοειδή">Πετρελαιοειδή</option>
                                    <option value="Πληροφορική">Πληροφορική</option>
                                    <option value="Σύμβουλοι Επιχειρήσεων">Σύμβουλοι Επιχειρήσεων</option>
                                    <option value="Τέχνη, Μουσεία">Τέχνη, Μουσεία</option>
                                    <option value="Τεχνικά γραφεία/εταιρείες, Κατασκευές">Τεχνικά γραφεία/εταιρείες, Κατασκευές</option>
                                    <option value="Τηλεοπτικές παραγωγές">Τηλεοπτικές παραγωγές</option>
                                    <option value="Τηλεπικοινωνίες">Τηλεπικοινωνίες</option>
                                    <option value="Τοπική/Κεντρική Διοίκηση">Τοπική/Κεντρική Διοίκηση</option>
                                    <option value="Τουριστικές επιχειρήσεις">Τουριστικές επιχειρήσεις</option>
                                    <option value="Υφάσματα, Ένδυση, Μόδα">Υφάσματα, Ένδυση, Μόδα</option>
                                    <option value="Φωτογραφία">Φωτογραφία</option>
                                    <option value="Χημικά προϊόντα, Φάρμακα">Χημικά προϊόντα, Φάρμακα</option>
                                </select>
                            </span>
                            <span class="error" id="activity_field_error"></span>

                            <label for="type" class="required" style="margin-top: 25px">Είδος φορέα</label>
                            <span>
                                <select class="select" name="type" id="type">
                                    <option value="0" hidden selected>Επιλογή Είδους Φορέα</option>
                                    <option value="private">Ιδιωτικός Φορέας</option>
                                    <option value="public">Δημόσιος Φορέας</option>
                                    <option value="NGO">Μ.Κ.Ο.</option>
                                    <option value="other">Άλλο</option>
                                </select>
                            </span>
                            <span class="error" id="type_error"></span>

                            <label for="VAT" class="required" style="margin-top: 25px">Α.Φ.Μ.</label>
                            <span>
                                <input type="number" class="inp" name="VAT" id="VAT">
                            </span>
                            <span class="error" id="VAT_error"></span>

                            <label for="tax_office" class="required" style="margin-top: 25px">Δ.Ο.Υ.</label>
                            <span>
                                <select class="select" name="tax_office" id="tax_office">
                                    <option value="0" hidden selected>Επιλογή Δ.Ο.Υ.</option>
                                    <option value="ΑΓΙΟΥ ΝΙΚΟΛΑΟΥ">ΑΓΙΟΥ ΝΙΚΟΛΑΟΥ</option>
                                    <option value="ΑΓΙΩΝ ΑΝΑΡΓΥΡΩΝ">ΑΓΙΩΝ ΑΝΑΡΓΥΡΩΝ</option>
                                    <option value="ΑΓΡΙΝΙΟΥ">ΑΓΡΙΝΙΟΥ</option>
                                    <option value="ΑΘΗΝΩΝ Α">ΑΘΗΝΩΝ Α</option>
                                    <option value="ΑΘΗΝΩΝ Δ">ΑΘΗΝΩΝ Δ</option>
                                    <option value="ΑΘΗΝΩΝ Ι">ΑΘΗΝΩΝ ΙΒ</option>
                                    <option value="ΑΘΗΝΩΝ ΙΓ">ΑΘΗΝΩΝ ΙΓ</option>
                                    <option value="ΑΘΗΝΩΝ ΙΖ">ΑΘΗΝΩΝ ΙΖ</option>
                                    <option value="ΑΘΗΝΩΝ ΦΑΕ">ΑΘΗΝΩΝ ΦΑΕ</option>
                                    <option value="ΑΙΓΑΛΕΩ">ΑΙΓΑΛΕΩ</option>
                                    <option value="ΑΙΓΙΟΥ">ΑΙΓΙΟΥ</option>
                                    <option value="ΑΛΕΞΑΝΔΡΟΥΠΟΛΗΣ">ΑΛΕΞΑΝΔΡΟΥΠΟΛΗΣ</option>
                                    <option value="ΑΜΑΛΙΑΔΑΣ">ΑΜΑΛΙΑΔΑΣ</option>
                                    <option value="ΑΜΑΡΟΥΣΙΟΥ">ΑΜΑΡΟΥΣΙΟΥ</option>
                                    <option value="ΑΜΠΕΛΟΚΗΠΩΝ">ΑΜΠΕΛΟΚΗΠΩΝ</option>
                                    <option value="ΑΜΦΙΣΣΑΣ">ΑΜΦΙΣΣΑΣ</option>
                                    <option value="ΑΡΓΟΣΤΟΛΙΟΥ">ΑΡΓΟΣΤΟΛΙΟΥ</option>
                                    <option value="ΑΡΓΟΥΣ">ΑΡΓΟΥΣ</option>
                                    <option value="ΑΡΤΑΣ">ΑΡΤΑΣ</option>
                                    <option value="ΒΕΡΟΙΑΣ">ΒΕΡΟΙΑΣ</option>
                                    <option value="ΒΟΛΟΥ">ΒΟΛΟΥ</option>
                                    <option value="ΓΙΑΝΝΙΤΣΩΝ">ΓΙΑΝΝΙΤΣΩΝ</option>
                                    <option value="ΓΛΥΦΑΔΑΣ">ΓΛΥΦΑΔΑΣ</option>
                                    <option value="ΓΡΕΒΕΝΩΝ">ΓΡΕΒΕΝΩΝ</option>
                                    <option value="ΔΡΑΜΑΣ">ΔΡΑΜΑΣ</option>
                                    <option value="ΕΔΕΣΣΑΣ">ΕΔΕΣΣΑΣ</option>
                                    <option value="ΕΛΕΥΣΙΝΑΣ">ΕΛΕΥΣΙΝΑΣ</option>
                                    <option value="ΖΑΚΥΝΘΟΥ">ΖΑΚΥΝΘΟΥ</option>
                                    <option value="ΗΓΟΥΜΕΝΙΤΣΑΣ">ΗΓΟΥΜΕΝΙΤΣΑΣ</option>
                                    <option value="ΗΛΙΟΥΠΟΛΗΣ">ΗΛΙΟΥΠΟΛΗΣ</option>
                                    <option value="ΗΡΑΚΛΕΙΟΥ">ΗΡΑΚΛΕΙΟΥ</option>
                                    <option value="ΘΕΣΣΑΛΟΝΙΚΗΣ Α">ΘΕΣΣΑΛΟΝΙΚΗΣ Α</option>
                                    <option value="ΘΕΣΣΑΛΟΝΙΚΗΣ Δ">ΘΕΣΣΑΛΟΝΙΚΗΣ Δ</option>
                                    <option value="ΘΕΣΣΑΛΟΝΙΚΗΣ Ε">ΘΕΣΣΑΛΟΝΙΚΗΣ Ε</option>
                                    <option value="ΘΕΣΣΑΛΟΝΙΚΗΣ Ζ">ΘΕΣΣΑΛΟΝΙΚΗΣ Ζ</option>
                                    <option value="ΘΕΣΣΑΛΟΝΙΚΗΣ Η">ΘΕΣΣΑΛΟΝΙΚΗΣ Η</option>
                                    <option value="ΘΕΣΣΑΛΟΝΙΚΗΣ ΦΑΕ">ΘΕΣΣΑΛΟΝΙΚΗΣ ΦΑΕ</option>
                                    <option value="ΘΗΒΩΝ">ΘΗΒΩΝ</option>
                                    <option value="ΘΗΡΑΣ">ΘΗΡΑΣ</option>
                                    <option value="ΙΩΑΝΝΙΝΩΝ">ΙΩΑΝΝΙΝΩΝ</option>
                                    <option value="ΙΩΝΙΑΣ ΘΕΣΣΑΛΟΝΙΚΗΣ">ΙΩΝΙΑΣ ΘΕΣΣΑΛΟΝΙΚΗΣ</option>
                                    <option value="ΚΑΒΑΛΑΣ">ΚΑΒΑΛΑΣ</option>
                                    <option value="ΚΑΛΑΜΑΡΙΑΣ">ΚΑΛΑΜΑΡΙΑΣ</option>
                                    <option value="ΚΑΛΑΜΑΤΑΣ">ΚΑΛΑΜΑΤΑΣ</option>
                                    <option value="ΚΑΛΛΙΘΕΑΣ">ΚΑΛΛΙΘΕΑΣ</option>
                                    <option value="ΚΑΡΔΙΤΣΑΣ">ΚΑΡΔΙΤΣΑΣ</option>
                                    <option value="ΚΑΡΠΕΝΗΣΙΟΥ">ΚΑΡΠΕΝΗΣΙΟΥ</option>
                                    <option value="ΚΑΣΤΟΡΙΑΣ">ΚΑΣΤΟΡΙΑΣ</option>
                                    <option value="ΚΑΤΕΡΙΝΗΣ">ΚΑΤΕΡΙΝΗΣ</option>
                                    <option value="ΚΑΤΟΙΚΩΝ ΕΞΩΤΕΡΙΚΟΥ">ΚΑΤΟΙΚΩΝ ΕΞΩΤΕΡΙΚΟΥ</option>
                                    <option value="ΚΕΡΚΥΡΑΣ">ΚΕΡΚΥΡΑΣ</option>
                                    <option value="ΚΗΦΙΣΙΑΣ">ΚΗΦΙΣΙΑΣ</option>
                                    <option value="ΚΙΛΚΙΣ">ΚΙΛΚΙΣ</option>
                                    <option value="ΚΟΖΑΝΗΣ">ΚΟΖΑΝΗΣ</option>
                                    <option value="ΚΟΜΟΤΗΝΗΣ">ΚΟΜΟΤΗΝΗΣ</option>
                                    <option value="ΚΟΡΙΝΘΟΥ">ΚΟΡΙΝΘΟΥ</option>
                                    <option value="ΚΟΡΩΠΙΟΥ">ΚΟΡΩΠΙΟΥ</option>
                                    <option value="ΚΥΜΗΣ">ΚΥΜΗΣ</option>
                                    <option value="ΚΩ">ΚΩ</option>
                                    <option value="ΛΑΓΚΑΔΑ">ΛΑΓΚΑΔΑ</option>
                                    <option value="ΛΑΜΙΑΣ">ΛΑΜΙΑΣ</option>
                                    <option value="ΛΑΡΙΣΑΣ">ΛΑΡΙΣΑΣ</option>
                                    <option value="ΛΕΥΚΑΔΑΣ">ΛΕΥΚΑΔΑΣ</option>
                                    <option value="ΛΙΒΑΔΕΙΑΣ">ΛΙΒΑΔΕΙΑΣ</option>
                                    <option value="ΜΕΣΟΛΟΓΓΙΟΥ">ΜΕΣΟΛΟΓΓΙΟΥ</option>
                                    <option value="ΜΟΣΧΑΤΟΥ">ΜΟΣΧΑΤΟΥ</option>
                                    <option value="ΜΥΚΟΝΟΥ">ΜΥΚΟΝΟΥ</option>
                                    <option value="ΜΥΤΙΛΗΝΗΣ">ΜΥΤΙΛΗΝΗΣ</option>
                                    <option value="Ν. ΙΩΝΙΑΣ ΜΑΓΝΗΣΙΑΣ">Ν. ΙΩΝΙΑΣ ΜΑΓΝΗΣΙΑΣ</option>
                                    <option value="ΝΑΞΟΥ">ΝΑΞΟΥ</option>
                                    <option value="ΝΑΥΠΛΙΟΥ">ΝΑΥΠΛΙΟΥ</option>
                                    <option value="ΝΕΑΣ ΙΩΝΙΑΣ">ΝΕΑΣ ΙΩΝΙΑΣ</option>
                                    <option value="ΝΕΩΝ ΜΟΥΔΑΝΙΩΝ">ΝΕΩΝ ΜΟΥΔΑΝΙΩΝ</option>
                                    <option value="ΝΙΚΑΙΑΣ">ΝΙΚΑΙΑΣ</option>
                                    <option value="ΞΑΝΘΗΣ">ΞΑΝΘΗΣ</option>
                                    <option value="ΟΡΕΣΤΙΑΔΑΣ">ΟΡΕΣΤΙΑΔΑΣ</option>
                                    <option value="ΠΑΛΛΗΝΗΣ">ΠΑΛΛΗΝΗΣ</option>
                                    <option value="ΠΑΡΟΥ">ΠΑΡΟΥ</option>
                                    <option value="ΠΑΤΡΩΝ Α">ΠΑΤΡΩΝ Α</option>
                                    <option value="ΠΑΤΡΩΝ Γ">ΠΑΤΡΩΝ Γ</option>
                                    <option value="ΠΕΙΡΑΙΑ Α">ΠΕΙΡΑΙΑ Α</option>
                                    <option value="ΠΕΙΡΑΙΑ Ε">ΠΕΙΡΑΙΑ Ε</option>
                                    <option value="ΠΕΙΡΑΙΑ ΦΑΕ">ΠΕΙΡΑΙΑ ΦΑΕ</option>
                                    <option value="ΠΕΡΙΣΤΕΡΙΟΥ">ΠΕΡΙΣΤΕΡΙΟΥ</option>
                                    <option value="ΠΛΟΙΩΝ ΠΕΙΡΑΙΑ">ΠΛΟΙΩΝ ΠΕΙΡΑΙΑ</option>
                                    <option value="ΠΟΛΥΓΥΡΟΥ">ΠΟΛΥΓΥΡΟΥ</option>
                                    <option value="ΠΡΕΒΕΖΑΣ">ΠΡΕΒΕΖΑΣ</option>
                                    <option value="ΠΤΟΛΕΜΑΙΔΑΣ">ΠΤΟΛΕΜΑΙΔΑΣ</option>
                                    <option value="ΠΥΡΓΟΥ">ΠΥΡΓΟΥ</option>
                                    <option value="ΡΕΘΥΜΝΟΥ">ΡΕΘΥΜΝΟΥ</option>
                                    <option value="ΡΟΔΟΥ">ΡΟΔΟΥ</option>
                                    <option value="ΣΑΜΟΥ">ΣΑΜΟΥ</option>
                                    <option value="ΣΕΡΡΩΝ">ΣΕΡΡΩΝ</option>
                                    <option value="ΣΠΑΡΤΗΣ">ΣΠΑΡΤΗΣ</option>
                                    <option value="ΣΥΡΟΥ">ΣΥΡΟΥ</option>
                                    <option value="ΤΡΙΚΑΛΩΝ">ΤΡΙΚΑΛΩΝ</option>
                                    <option value="ΤΡΙΠΟΛΗΣ">ΤΡΙΠΟΛΗΣ</option>
                                    <option value="ΦΛΩΡΙΝΑΣ">ΦΛΩΡΙΝΑΣ</option>
                                    <option value="ΧΑΛΚΙΔΑΣ">ΧΑΛΚΙΔΑΣ</option>
                                    <option value="ΧΑΝΙΩΝ">ΧΑΝΙΩΝ</option>
                                    <option value="ΧΙΟΥ">ΧΙΟΥ</option>
                                    <option value="ΧΟΛΑΡΓΟΥ">ΧΟΛΑΡΓΟΥ</option>
                                    <option value="ΨΥΧΙΚΟΥ">ΨΥΧΙΚΟΥ</option>
                                    <option value="ΜΕΓΑΛΩΝ ΕΠΙΧΕΙΡΗΣΕΩΝ">ΜΕΓΑΛΩΝ ΕΠΙΧΕΙΡΗΣΕΩΝ</option>
                                </select>
                            </span>
                            <span class="error" id="tax_office_error"></span>

                            <label for="company_email" class="required" style="margin-top: 25px">Email εταιρίας</label>
                            <span>
                                <input type="text" class="inp" name="company_email" id="company_email">
                            </span>
                            <span class="error" id="company_email_error"></span>

                            <label for="company_phone" class="required" style="margin-top: 25px">Τηλέφωνο εταιρίας (σταθερό)</label>
                            <span>
                                <input type="number" class="inp" name="company_phone" id="company_phone">
                            </span>
                            <span class="error" id="company_phone_error"></span>
                        </div>
                    </div>
                    <div class="container container_top" style="/* margin-top: 25px; */">
                        <button type="submit" class="btn" name="submit" id="submit" disabled>Υποβολή</button>
                    </div>
                </form>
            </div>
        </div>
        <div style="text-align: center; padding: 10px;">
            <p>Έχεις ήδη λογαριασμό; <a href="login.php">Σύνδεση</a>.</p>
        </div> 
        <?php
            include 'bottom_base.php';
        ?>
        <script>
            const firstname = document.getElementById('firstname');
            const surname = document.getElementById('surname');
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const confirm_password = document.getElementById('confirm_password');
            const user_email = document.getElementById('user_email');
            const confirm_user_email = document.getElementById('confirm_user_email');
            const user_phone = document.getElementById('user_phone');
            const identity_card_text = document.getElementById('identity_card_text');
            const identity_card_number = document.getElementById('identity_card_number');
            const company_name = document.getElementById('company_name');
            const address_text = document.getElementById('address_text');
            const address_number = document.getElementById('address_number');
            const zip_code = document.getElementById('zip_code');
            const country = document.getElementById('country');
            const site = document.getElementById('site');
            const activity_field = document.getElementById('activity_field');
            const type = document.getElementById('type');
            const VAT = document.getElementById('VAT');
            const tax_office = document.getElementById('tax_office');
            const company_email = document.getElementById('company_email');
            const company_phone = document.getElementById('company_phone');
            const region = document.getElementById('region');
            const municipality = document.getElementById('municipality');
            const submit = document.getElementById('submit');
            let correct_fields = [false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false];

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

            surname.addEventListener('focusout', (event) => {
                if(surname.value === ''){
                    document.getElementById('surname_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    surname.style.border = "1px solid #810000";

                    correct_fields[1] = false;
                    check_correct_fields_no();
                } else {
                    if(!surname.value.match(/^[A-Za-zΑ-Ωα-ωίϊΐόάέύϋΰήώ]+$/)) {
                        document.getElementById('surname_error').innerHTML = "Το επώνυμο μπορεί να περιέχει μόνο ελληνικούς ή λατινικούς χαρακτήρες.";
                        surname.style.border = "1px solid #810000";

                        correct_fields[1] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('surname_error').innerHTML = "";
                        surname.style.border = "1px solid #00b300";

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

            user_email.addEventListener('focusout', (event) => {
                if(user_email.value === ''){
                    document.getElementById('user_email_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    user_email.style.border = "1px solid #810000";
                    
                    correct_fields[5] = false;
                    check_correct_fields_no();
                } else {
                    if(!user_email.value.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/)) {
                        document.getElementById('user_email_error').innerHTML = "Λάθος email.";
                        user_email.style.border = "1px solid #810000";
                    
                        correct_fields[5] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('user_email_error').innerHTML = "";
                        user_email.style.border = "1px solid #00b300";
                        
                        confirm_user_email.readOnly = false;
                        
                        correct_fields[5] = true;
                        check_correct_fields_no();
                    }
                }
            });

            confirm_user_email.addEventListener('focusout', (event) => {
                if(confirm_user_email.value === '' && confirm_user_email.readOnly === false){
                    document.getElementById('confirm_user_email_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    confirm_user_email.style.border = "1px solid #810000";
                    
                    correct_fields[6] = false;
                    check_correct_fields_no();
                } else if(confirm_user_email.value !== '') {
                    if(user_email.value != confirm_user_email.value) {
                        document.getElementById('confirm_user_email_error').innerHTML = "Τα email δεν ταιριάζουν.";
                        confirm_user_email.style.border = "1px solid #810000";
                    
                        correct_fields[6] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('confirm_user_email_error').innerHTML = "";
                        confirm_user_email.style.border = "1px solid #00b300";
                        
                        correct_fields[6] = true;
                        check_correct_fields_no();
                    }
                }
            });

            user_phone.addEventListener('focusout', (event) => {
                if(user_phone.value === ''){
                    document.getElementById('user_phone_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    user_phone.style.border = "1px solid #810000";
                    
                    correct_fields[7] = false;
                    check_correct_fields_no();
                } else {
                    if(user_phone.value.length != 10) {
                        document.getElementById('user_phone_error').innerHTML = "Το τηλέφωνο πρέπει να περιέχει ακριβώς 10 ψηφία.";
                        user_phone.style.border = "1px solid #810000";
                    
                        correct_fields[7] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('user_phone_error').innerHTML = "";
                        user_phone.style.border = "1px solid #00b300";
                        
                        correct_fields[7] = true;
                        check_correct_fields_no();
                    }
                }
            });

            identity_card_text.addEventListener('focusout', (event) => {
                if(identity_card_text.value === ''){
                    document.getElementById('identity_card_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    identity_card_text.style.border = "1px solid #810000";
                    
                    correct_fields[8] = false;
                    check_correct_fields_no();
                } else {
                    if(!identity_card_text.value.match(/^[ΑΒΕΖΗΙΚΜΝΟΡΤΥΧ]+$/)) {
                        document.getElementById('identity_card_error').innerHTML = "Ο αριθμός ταυτότητας πρέπει να περιέχει μόνο τα γράμματα Α, Β, Ε, Ζ, Η, Ι, Κ, Μ, Ν, Ο, Ρ, Τ, Υ και Χ.";
                        identity_card_text.style.border = "1px solid #810000";
                    
                        correct_fields[5] = false;
                        check_correct_fields_no();
                    } else if(identity_card_text.value.length != 2) {
                        document.getElementById('identity_card_error').innerHTML = "Ο αριθμός ταυτότητας πρέπει να περιέχει ακριβώς 2 γράμματα.";
                        identity_card_text.style.border = "1px solid #810000";
                    
                        correct_fields[8] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('identity_card_error').innerHTML = "";
                        identity_card_text.style.border = "1px solid #00b300";
                        
                        correct_fields[8] = true;
                        check_correct_fields_no();
                    }
                }
            });

            identity_card_number.addEventListener('focusout', (event) => {
                if(identity_card_number.value === ''){
                    document.getElementById('identity_card_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    identity_card_number.style.border = "1px solid #810000";
                    
                    correct_fields[9] = false;
                    check_correct_fields_no();
                } else {
                    if(identity_card_number.value.length != 6) {
                        document.getElementById('identity_card_error').innerHTML = "Ο αριθμός ταυτότητας πρέπει να περιέχει ακριβώς 6 ψηφία.";
                        identity_card_number.style.border = "1px solid #810000";
                    
                        correct_fields[9] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('identity_card_error').innerHTML = "";
                        identity_card_number.style.border = "1px solid #00b300";
                        
                        correct_fields[9] = true;
                        check_correct_fields_no();
                    }
                }
            });

            company_name.addEventListener('focusout', (event) => {
                if(company_name.value === ''){
                    document.getElementById('company_name_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    company_name.style.border = "1px solid #810000";
                    
                    correct_fields[10] = false;
                    check_correct_fields_no();
                } else {
                    document.getElementById('company_name_error').innerHTML = "";
                    company_name.style.border = "1px solid #00b300";
                    
                    correct_fields[10] = true;
                    check_correct_fields_no();
                }
            });

            address_text.addEventListener('focusout', (event) => {
                if(address_text.value === ''){
                    document.getElementById('address_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    address_text.style.border = "1px solid #810000";
                    
                    correct_fields[11] = false;
                    check_correct_fields_no();
                } else {
                    document.getElementById('address_error').innerHTML = "";
                    address_text.style.border = "1px solid #00b300";
                    
                    correct_fields[11] = true;
                    check_correct_fields_no();
                }
            });

            address_number.addEventListener('focusout', (event) => {
                if(address_number.value === ''){
                    document.getElementById('address_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    address_number.style.border = "1px solid #810000";
                    
                    correct_fields[12] = false;
                    check_correct_fields_no();
                } else {
                    document.getElementById('address_error').innerHTML = "";
                    address_number.style.border = "1px solid #00b300";
                    
                    correct_fields[12] = true;
                    check_correct_fields_no();
                }
            });

            zip_code.addEventListener('focusout', (event) => {
                if(zip_code.value === ''){
                    document.getElementById('address_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    zip_code.style.border = "1px solid #810000";
                    
                    correct_fields[13] = false;
                    check_correct_fields_no();
                } else {
                    if(zip_code.value.length != 5) {
                        document.getElementById('address_error').innerHTML = "Ο Τ.Κ. πρέπει να περιέχει ακριβώς 5 ψηφία.";
                        zip_code.style.border = "1px solid #810000";
                    
                        correct_fields[13] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('address_error').innerHTML = "";
                        zip_code.style.border = "1px solid #00b300";
                        
                        correct_fields[13] = true;
                        check_correct_fields_no();
                    }
                }
            });

            country.addEventListener('focusout', (event) => {
                console.log(country.selectedIndex );
                if(country.selectedIndex == '0'){
                    document.getElementById('country_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    country.style.border = "1px solid #810000";
                    
                    correct_fields[14] = false;
                    check_correct_fields_no();
                } else {
                    document.getElementById('country_error').innerHTML = "";
                    country.style.border = "1px solid #00b300";

                    correct_fields[14] = true;
                    check_correct_fields_no();
                }
            });

            site.addEventListener('focusout', (event) => {
                if(site.value === ''){
                    document.getElementById('site_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    site.style.border = "1px solid #810000";
                    
                    correct_fields[15] = false;
                    check_correct_fields_no();
                } else {
                    if(!site.value.match(/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/)) {
                        document.getElementById('site_error').innerHTML = "Λάθος ιστοσελίδα.";
                        site.style.border = "1px solid #810000";
                    
                        correct_fields[15] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('site_error').innerHTML = "";
                        site.style.border = "1px solid #00b300";
                        
                        correct_fields[15] = true;
                        check_correct_fields_no();
                    }
                }
            });

            activity_field.addEventListener('focusout', (event) => {
                console.log(activity_field.selectedIndex );
                if(activity_field.selectedIndex == '0'){
                    document.getElementById('activity_field_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    activity_field.style.border = "1px solid #810000";
                    
                    correct_fields[16] = false;
                    check_correct_fields_no();
                } else {
                    document.getElementById('activity_field_error').innerHTML = "";
                    activity_field.style.border = "1px solid #00b300";

                    correct_fields[16] = true;
                    check_correct_fields_no();
                }
            });

            type.addEventListener('focusout', (event) => {
                console.log(type.selectedIndex );
                if(type.selectedIndex == '0'){
                    document.getElementById('type_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    type.style.border = "1px solid #810000";
                    
                    correct_fields[17] = false;
                    check_correct_fields_no();
                } else {
                    document.getElementById('type_error').innerHTML = "";
                    type.style.border = "1px solid #00b300";

                    correct_fields[17] = true;
                    check_correct_fields_no();
                }
            });

            VAT.addEventListener('focusout', (event) => {
                if(VAT.value === ''){
                    document.getElementById('VAT_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    VAT.style.border = "1px solid #810000";
                    
                    correct_fields[18] = false;
                    check_correct_fields_no();
                } else {
                    if(VAT.value.length != 9) {
                        document.getElementById('VAT_error').innerHTML = "Το Α.Φ.Μ. πρέπει να περιέχει ακριβώς 9 ψηφία.";
                        VAT.style.border = "1px solid #810000";
                    
                        correct_fields[18] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('VAT_error').innerHTML = "";
                        VAT.style.border = "1px solid #00b300";
                        
                        correct_fields[18] = true;
                        check_correct_fields_no();
                    }
                }
            });

            tax_office.addEventListener('focusout', (event) => {
                console.log(tax_office.selectedIndex );
                if(tax_office.selectedIndex == '0'){
                    document.getElementById('tax_office_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    tax_office.style.border = "1px solid #810000";
                    
                    correct_fields[19] = false;
                    check_correct_fields_no();
                } else {
                    document.getElementById('tax_office_error').innerHTML = "";
                    tax_office.style.border = "1px solid #00b300";

                    correct_fields[19] = true;
                    check_correct_fields_no();
                }
            });

            company_email.addEventListener('focusout', (event) => {
                if(company_email.value === ''){
                    document.getElementById('company_email_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    company_email.style.border = "1px solid #810000";
                    
                    correct_fields[20] = false;
                    check_correct_fields_no();
                } else {
                    if(!company_email.value.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/)) {
                        document.getElementById('company_email_error').innerHTML = "Λάθος email.";
                        company_email.style.border = "1px solid #810000";
                    
                        correct_fields[20] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('company_email_error').innerHTML = "";
                        company_email.style.border = "1px solid #00b300";
                        
                        correct_fields[20] = true;
                        check_correct_fields_no();
                    }
                }
            });

            company_phone.addEventListener('focusout', (event) => {
                if(company_phone.value === ''){
                    document.getElementById('company_phone_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    company_phone.style.border = "1px solid #810000";
                    
                    correct_fields[21] = false;
                    check_correct_fields_no();
                } else {
                    if(company_phone.value.length != 10) {
                        document.getElementById('company_phone_error').innerHTML = "Το τηλέφωνο πρέπει να περιέχει ακριβώς 10 ψηφία.";
                        company_phone.style.border = "1px solid #810000";
                    
                        correct_fields[21] = false;
                        check_correct_fields_no();
                    } else {
                        document.getElementById('company_phone_error').innerHTML = "";
                        company_phone.style.border = "1px solid #00b300";
                        
                        correct_fields[21] = true;
                        check_correct_fields_no();
                    }
                }
            });

            region.addEventListener('focusout', (event) => {
                if(region.selectedIndex <= 0){
                    document.getElementById('region_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    region.style.border = "1px solid #810000";
                    
                    correct_fields[22] = false;
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
                
                correct_fields[22] = true;
                check_correct_fields_no();
            });

            municipality.addEventListener('focusout', (event) => {
                if(municipality.selectedIndex <= 0){
                    document.getElementById('municipality_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    municipality.style.border = "1px solid #810000";
                    
                    correct_fields[23] = false;
                    check_correct_fields_no();
                } else {
                    document.getElementById('municipality_error').innerHTML = "";
                    municipality.style.border = "1px solid #00b300";

                    correct_fields[23] = true;
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