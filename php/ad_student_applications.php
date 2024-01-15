<?php
    // If user is already logged in
    // go to home page
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    if(!isset($_SESSION['username']) || (isset($_SESSION['username']) && $_SESSION['type'] != 'company')) {
        header("location: javascript:history.go(-1)");
    }
    
    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    $username = $_SESSION['username'];
    if (isset($_GET["id"])) {
        $_SESSION['ad_id'] = $_GET["id"];
        $sql = "SELECT sa.id as 'app_id', a.id as 'ad_id', a.title, sa.state, sa.date, u.firstname, u.surname, sa.company_accepted, sa.notification_send, sa.read, sa.rejection_reasons
                FROM user u, company c, student_application sa, ad a, student s 
                WHERE (s.user_id = u.id) AND (a.company_id = c.id) AND (sa.ad_id = a.id) AND (sa.student_id = s.id) AND (a.id = ".$_SESSION['ad_id'].") AND (a.state != 'auto-save')
                ORDER BY sa.id DESC";

        $result = mysqli_query($conn, $sql);

    }

    if (isset($_SESSION['ad_id'])) {
        $sql = "SELECT a.title, a.no_of_vacancies
                FROM ad a
                WHERE a.id = ".$_SESSION['ad_id'];

        $result_ad = mysqli_query($conn, $sql);
        $ad = mysqli_fetch_array($result_ad);
    }

    if (isset($_GET['search_btn']) ) {

        $state = $_GET['state'];
        $sql = "SELECT sa.id as 'app_id', a.id as 'ad_id', a.title, sa.state, sa.date, u.firstname, u.surname, sa.company_accepted, sa.notification_send, sa.read, sa.rejection_reasons
                FROM user u, company c, student_application sa, ad a, student s 
                WHERE (s.user_id = u.id) AND (a.company_id = c.id) AND (sa.ad_id = a.id) AND (sa.student_id = s.id) AND (a.id = ".$_SESSION['ad_id'].") AND (a.state != 'auto-save') ";

        if ($state != "Όλες"){
            if ( $state == "Μη Διαβασμένη" ){
                $sql .= " AND (sa.read = '0') AND (sa.company_accepted is null) ";
            } else if ( $state == "Διαβασμένη" ){
                $sql .= " AND (sa.read = '1') AND (sa.company_accepted is null) ";
            } else if ( $state == "Εγκεκριμένη" ){
                $sql .= " AND (sa.company_accepted = '1') ";
            } else if ( $state == "Απορρίφθηκε" ){
                $sql .= " AND (sa.company_accepted = '0') ";
            }
        } else {
            $sql .= " AND (sa.state != 'auto-save') ";
        }

        $sql .= " ORDER BY sa.date DESC, a.title";

        $result = mysqli_query($conn, $sql);
    }

    if (isset($_POST['accept_btn']) ) {
        foreach( $_POST['accept_btn'] as $app_id => $value ) {
            $_SESSION['app_id'] = $app_id;

            $update = "UPDATE student_application sa SET sa.company_accepted='1', sa.state='Εγκεκριμένη' where sa.id = $app_id;";
    
            $conn->query($update) or die(mysql_error()."<br />".$update);

            $no_of_vacancies = $ad['no_of_vacancies'] - 1;
            $update = "UPDATE ad a SET a.no_of_vacancies=$no_of_vacancies where a.id = ".$_SESSION['ad_id'].";";
    
            $conn->query($update) or die(mysql_error()."<br />".$update);

            $sql = "SELECT u.firstname, u.surname
                    FROM user u, student_application sa, student s 
                    WHERE (s.user_id = u.id) AND (sa.id = $app_id) AND (sa.student_id = s.id)";
                    
            $result1 = mysqli_query($conn, $sql);
            $row1 = mysqli_fetch_array($result1);
            $fullname = $row1['firstname'] . "  " . $row1['surname'];

            echo '<script type="text/javascript">window.location = "#accept-modal"</script>';
        }
    }

    if (isset($_POST['reject_btn']) ) {
        foreach( $_POST['reject_btn'] as $app_id => $value ) {
            $_SESSION['app_id'] = $app_id;
        }

        echo '<script type="text/javascript">window.location = "#rejection-modal"</script>';
    }

    if (isset($_POST['submit_reasons']) ) {
        $rejection_reasons = mysqli_real_escape_string($conn, $_POST['rejection_reasons']);
        $update = "UPDATE student_application sa SET sa.company_accepted='0', sa.state='Μη Εγκεκριμένη', sa.rejection_reasons='".$rejection_reasons."' where sa.id = ".$_SESSION['app_id'].";";
    
        $conn->query($update) or die(mysql_error()."<br />".$update);

        unset($_SESSION['app_id']);
        echo '<script type="text/javascript">window.location = ""</script>';
        
        header("Refresh:0");
    }

?>

<!DOCTYPE html>
<html lang="el">
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/profile.css">
        <link rel="stylesheet" href="../css/company_ads.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            .back:hover{
                text-decoration: underline;
            }
        </style>
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
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="company_ads.php">Αγγελίες</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">Αιτήσεις Φοιτητών/τριων</div>
        </div>

        <?php
            include 'profile_menu.php';
        ?>
        
        <div id="rejection-modal" class="modal">
            <div class="modal_content">
                <div class="rejection_reasons_modal" style="display: grid;justify-items: center;">
                    <form method="post">
                        
                        <label for="rejection_reasons"><h1>
                            Παρακαλώ συμπληρώστε τους λόγους απόρριψης της αίτησης:
                        </h1></label>
                        <textarea id="rejection_reasons" name="rejection_reasons" style="margin-bottom: 0;"></textarea>
                        <span class="error" id="rejection_reasons_error" style="margin-bottom: 0;margin-top: 15px;"></span>

                        <div style="display: flex;justify-content: center;">
                            <button type="submit" class="btn" name="submit_reasons" id="submit_reasons" style="float: right; background-color: #6ccd71;margin:15px" disabled>Υποβολή</button>
                        </div>
                    </form>
                    <button class="btn modal_close" id="modal_close" style="margin:15px">Κλείσιμο</button>
                </div>
            </div>
        </div>

        <div id="accept-modal" class="modal">
            <div class="modal_content">
                <div class="accept_modal" style="display: grid;justify-items: center;">
                    <h1>
                        Η αποδοχή του/της φοιτητή/τριας “<?php echo $fullname;?>” ολοκληρώθηκε επιτυχώς!<br>

                        Έγινε ενημέρωσή του.

                    </h1>

                    <button class="btn modal_close" id="modal_close2" style="margin:15px">Κλείσιμο</button>
                </div>
            </div>
        </div>

        <br><br>
        <div><a class="back" onclick="window.close()" style="cursor:pointer; margin-left:60px;">< Πίσω</a></div>

        <h1 style="text-align: center;">
            Αιτήσεις "<?php echo $ad['title'];?>"
        </h1>
            
        <p style="text-align: center;">
            Απομένουν <?php echo $ad['no_of_vacancies'];?> κενές θέσεις.
        </p>

        <div>
            <form action="" method="get">    
                <div class="wrapper">
                    <div style="padding: 12px;">
                        <label for="state">Κατάσταση Αίτησης:</label>
                    </div>
                    <div>
                        <select id="state" name="state">
                            <option value="Όλες">Όλες</option>
                            <option value="Μη Διαβασμένη">Μη Διαβασμένη</option>
                            <option value="Διαβασμένη">Διαβασμένη</option>
                            <option value="Εγκεκριμένη">Εγκεκριμένη</option>
                            <option value="Απορρίφθηκε">Απορρίφθηκε</option>
                        </select>
                    </div>
                    <div style="padding: 5px;">
                        <button name="search_btn" type="submit" class="ad_info_btn" style="background-color: #c4cdda; margin-left: 10px;"><img alt="magnifying_glass_solid" src="../img/magnifying-glass-solid.png" style="width: 15px; margin-right: 10px;">Αναζήτηση</button>
                    </div>
                </div>
            </form>

            <br>
            <div>
                <form method="post">
                    <table>
                        <tr class="header">
                            <th>Ονοματεπώνυμο</th>
                            <th>Ημερομηνία Δημοσίευσης</th>
                            <th>Κατάσταση Αίτησης</th>
                            <th>Ενέργεια</th>
                            <!-- <th>
                                <input id="select_all" class="checkbox" type="checkbox" onClick="toggle(this)">
                                <span class="checkmark"></span>
                            </th> -->
                        </tr>
                        <?php while($row = mysqli_fetch_array($result)) :?> 
                            <tr <?php if ( $row['company_accepted'] == null && $row['read'] == "0" ){ echo "style='font-weight: bold; background-color: lightgray;'"; }?>>
                                <td>
                                    <?php echo $row['firstname'] . "  " . $row['surname'];?>
                                </td>
                                <td><?php echo $row['date'];?></td>
                                <td>
                                    <?php
                                        if ( $row['company_accepted'] == "1" ){
                                            echo 'Εγκεκριμένη';
                                        } else if ( $row['company_accepted'] == "0" ){
                                            echo 'Απορρίφθηκε';
                                        } else if ( $row['read'] == "0" ){
                                            echo 'Μη Διαβασμένη';
                                        } else if ( $row['company_accepted'] == null ){
                                            echo 'Διαβασμένη';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php if ( $row['company_accepted'] == null ){ ?>
                                        <button class="ad_info_btn" style="background-color: #9cbeee;"><img alt="file_search" src="../img/file_search.png" style="width: 13px; margin-right: 10px;"><a href="app_details.php?id=<?php echo $row['app_id']; ?>" style="text-decoration: none; color:black;">Λεπτομέρειες</a></button>
                                        <?php if ( $ad['no_of_vacancies'] > 0 ){ ?>
                                            <button class="ad_info_btn" name="accept_btn[<?php echo $row['app_id']; ?>]" type="submit" style="background-color: #59ce72;"><img alt="accept" src="../img/accept.png" style="width: 13px; margin-right: 10px;">Αποδοχή</button>
                                        <?php } ?>
                                        <button class="ad_info_btn" name="reject_btn[<?php echo $row['app_id']; ?>]" type="submit" style="background-color: #f37f7f;/"><img alt="reject" src="../img/reject.png" style="width: 13px; margin-right: 10px;">Απόρριψη</button>
                                    <?php } else {?>
                                        <button class="ad_info_btn" style="background-color: #9cbeee;"><img alt="file_search" src="../img/file_search.png" style="width: 13px; margin-right: 10px;"><a href="app_details.php?id=<?php echo $row['app_id']; ?>" style="text-decoration: none; color:black;">Λεπτομέρειες</a></button>
                                    <?php } ?>
                                </td>
                                <!-- <td class="checkbox">
                                    <input name="accept" class="checkbox" type="checkbox">
                                    <span class="checkmark"></span>
                                </td> -->
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </form>
            </div>
        </div> <br><br><br><br><br><br><br><br>
        <?php
            include 'bottom_base.php';
        ?>

        <script>
            document.getElementById("profile_menu_company_ads").classList.add("active");

            const rejection_reasons = document.getElementById('rejection_reasons');
            const submit_reasons = document.getElementById('submit_reasons');
            const modal_close = document.getElementById('modal_close');
            const modal_close2 = document.getElementById('modal_close2');
            
            rejection_reasons.addEventListener('focusout', (event) => {
                if(rejection_reasons.value === ''){
                    document.getElementById('rejection_reasons_error').innerHTML = "Υποχρεωτικό πεδίο.";
                    rejection_reasons.style.border = "1px solid #810000";

                    submit_reasons.disabled = true;
                } else {
                    document.getElementById('rejection_reasons_error').innerHTML = "";
                    rejection_reasons.style.border = "1px solid #00b300";

                    submit_reasons.disabled = false;
                }
            });

            function toggle(source) {
                checkboxes = document.getElementsByName('accept');
                for(var i=0, n=checkboxes.length;i<n;i++) {
                    checkboxes[i].checked = source.checked;
                }
            }

            modal_close.addEventListener('click', (event) => {
                window.location = "";
            });

            modal_close2.addEventListener('click', (event) => {
                window.location = "";
                
            });

        </script>
    </body>
</html>