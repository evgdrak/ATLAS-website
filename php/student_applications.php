<?php
    // If user is already logged in
    // go to home page
    if(!isset($_SESSION))  { 
        session_start(); 
    }

    if(!isset($_SESSION['username']) || (isset($_SESSION['username']) && $_SESSION['type'] != 'student')) {
        header("location: javascript:history.go(-1)");
    }
    
    if(isset($_SESSION['saved_info'])){
        unset($_SESSION['saved_info']);
    }

    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);


    $username = $_SESSION['username'];
    $sql = "SELECT sa.id as 'app_id', a.id as 'ad_id', a.title, sa.state, sa.date, sa.company_accepted, sa.rejection_reasons
            FROM user u, student s, student_application sa, ad a
            WHERE (u.username = '$username') AND (s.user_id = u.id) AND (s.id = sa.student_id) AND (sa.ad_id = a.id) AND (sa.state != 'auto-save')
            ORDER BY sa.date DESC, a.title";

    $result = mysqli_query($conn, $sql);

    $sql2 = "SELECT sa.id as 'app_id', a.id as 'ad_id', a.title, sa.state, sa.date, sa.company_accepted, sa.rejection_reasons
            FROM user u, student s, student_application sa, ad a
            WHERE (u.username = '$username') AND (s.user_id = u.id) AND (s.id = sa.student_id) AND (sa.ad_id = a.id) AND (sa.company_accepted = false OR sa.company_accepted = true) AND (sa.notification_send = 0) AND (sa.state != 'auto-save')
            ORDER BY sa.date DESC, a.title";

    $result2 = mysqli_query($conn, $sql2);

    $result3 = mysqli_query($conn, $sql2);

    while($row2 = mysqli_fetch_array($result3)){
        $update = "UPDATE student_application sa SET sa.notification_send = 1 where sa.id=".$row2['app_id'].";";    
    
        $conn->query($update) or die(mysql_error()."<br />".$update);    
    }

    if (isset($_GET['search_btn']) ) {
        $state = $_GET['state'];
        $sql = "SELECT sa.id as 'app_id', a.id as 'ad_id', a.title, sa.state, sa.date, sa.company_accepted, sa.rejection_reasons
                FROM user u, student s, student_application sa, ad a
                WHERE (u.username = '$username') AND (s.user_id = u.id) AND (s.id = sa.student_id) AND (sa.ad_id = a.id) ";

        if ($state != "Όλες"){
            $sql .= " AND (sa.state = '$state') ";
        } else {
            $sql .= " AND (sa.state != 'auto-save') ";
        }

        $sql .= " ORDER BY sa.date DESC, a.title";

        $result = mysqli_query($conn, $sql);
    } 

?>

<!DOCTYPE html>
<html lang="el">
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/profile.css">
        <link rel="stylesheet" href="../css/company_ads.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body onload="cleanStorage()">
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
            <div style="font-size: 12px; float: left; margin: 10px;">Αιτήσεις</div>
        </div> <br>

        <?php
            include 'profile_menu.php';
        ?>
        <br><br>
        
        <?php while($row1 = mysqli_fetch_array($result2)) :?>
            <?php if ( $row1['state'] == "Εγκεκριμένη" ) { ?>
                <div class="accepted_alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    Η αίτηση σου για την θέση "<?php echo $row1['title']; ?>" έγινε δεκτή.
                </div>
            <?php } else if ( $row1['state'] == "Μη Εγκεκριμένη" ) { ?>
                <div class="rejected_alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    Η αίτηση σου για την θέση "<?php echo $row1['title']; ?>" δεν έγινε δεκτή.
                </div>
            <?php } ?>
        <?php endwhile; ?>

        <br><br>
        <div>
            <form action="" method="get">    
                <div class="wrapper">
                    <div style="padding: 12px;">
                        <label for="state">Κατάσταση Αίτησης:</label>
                    </div>
                    <div>
                        <select id="state" name="state">
                            <option value="Όλες">Όλες</option>
                            <option value="Προσωρινά Αποθηκευμένη">Προσωρινά Αποθηκευμένη</option>
                            <option value="Σε Εκκρεμότητα">Σε Εκκρεμότητα</option>
                            <option value="Εγκεκριμένη">Εγκεκριμένη</option>
                            <option value="Μη Εγκεκριμένη">Μη Εγκεκριμένη</option>
                        </select>
                    </div>
                    <div style="padding: 5px;">
                        <button name="search_btn" type="submit" class="ad_info_btn" style="background-color: #c4cdda; margin-left: 10px;"><img alt="magnifying_glass_solid" src="../img/magnifying-glass-solid.png" style="width: 15px; margin-right: 10px;">Αναζήτηση</button>
                    </div>
                </div>
            </form>
            
            <br>
            <div>
                <table>
                    <tr class="header">
                        <th>Θέση</th>
                        <th>Ημερομηνία Δημοσίευσης</th>
                        <th>Κατάσταση Αίτησης</th>
                        <th>Ενέργεια</th>
                    </tr>
                    <?php while($row = mysqli_fetch_array($result)) :?> 
                        <tr>
                            <td>
                                <h3><a href="details.php?id=<?php echo $row['ad_id']; ?>" target="_blank" style="text-decoration: none; color:black;"><img alt="magnifying_glass_solid" src="../img/file_search.png" style="width: 20px; margin-right: 10px;"><?php echo $row['title'];?></a></h3>
                            </td>
                            <td><?php echo $row['date'];?></td>
                            <?php if ( $row['state'] == "Εγκεκριμένη" ){
                                echo "<td style='color:#2a7d3c; font-weight: bold;'>Εγκεκριμένη</td>";
                                }else { echo '<td>'.$row['state'].'</td>'; }?>
                            <td>
                                <?php if ( $row['state'] == "Προσωρινά Αποθηκευμένη" ){ ?>
                                    <button class="ad_info_btn" style="background-color: #9cbeee;"><img alt="pen" src="../img/pen.png" style="width: 15px; margin-right: 10px;"><a href="create_application.php?id=<?php echo $row['ad_id'].'&app='.$row['app_id']; ?>" style="text-decoration: none; color:black;">Επεξεργασία</a></button>
                                    <button class="ad_info_btn" onclick="confirmDelete(<?php echo $row['app_id']; ?>, '<?php echo $row['title']; ?>')" style="background-color: #f37f7f;"><img alt="trash" src="../img/trash.png" style="width: 13px; margin-right: 10px;">Διαγραφή</button>
                                <?php } else if ( $row['state'] == "Σε εκκρεμότητα" ) {?>
                                    <button class="ad_info_btn" style="background-color: #9cbeee;"><img alt="file_search" src="../img/file_search.png" style="width: 17px; margin-right: 10px;"><a href="app_details.php?id=<?php echo $row['app_id']; ?>" target="_blank" style="text-decoration: none; color:black;">Προεπισκόπηση</a></button>
                                    <button class="ad_info_btn" onclick="confirmDelete(<?php echo $row['app_id']; ?>, '<?php echo $row['title']; ?>')" style="background-color: #f37f7f;"><img alt="trash" src="../img/trash.png" style="width: 13px; margin-right: 10px;">Διαγραφή</button>
                                <?php } else if ( $row['state'] == "Εγκεκριμένη" ) { ?>
                                    <button class="ad_info_btn" style="background-color: #9cbeee;"><img alt="file_search" src="../img/file_search.png" style="width: 17px; margin-right: 10px;"><a href="app_details.php?id=<?php echo $row['app_id']; ?>" target="_blank" style="text-decoration: none; color:black;">Προεπισκόπηση</a></button>
                                <?php } else if ( $row['state'] == "Μη Εγκεκριμένη" ) { ?>
                                    <button class="ad_info_btn" style="background-color: #9cbeee;"><img alt="file_search" src="../img/file_search.png" style="width: 17px; margin-right: 10px;"><a href="app_details.php?id=<?php echo $row['app_id']; ?>" target="_blank" style="text-decoration: none; color:black;">Προεπισκόπηση</a></button>
                                    <button class="ad_info_btn" style="background-color: #9cbeee;"><img alt="file_xmark" src="../img/file_xmark.png" style="width: 16px; margin-right: 10px;"><a href="app_rejected.php?id=<?php echo $row['app_id']; ?>" target="_blank" style="text-decoration: none; color:black;">Λόγοι Απόρριψης</a></button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div> <br><br><br><br><br><br><br>
        <?php
            include 'bottom_base.php';
        ?>

        <script>
            document.getElementById("profile_menu_student_applications").classList.add("active");

            function confirmDelete(id, title){
                console.log(id);
                console.log(title);
                let message1 = "Θέλετε να διαγράψετε την αίτηση σας για τη θέση ";
                let message2 = message1 + title + " ;"

                if (confirm(message2) == true) {
                    location.href = "delete_app.php?id="+id;
                }
            }

            function cleanStorage(){
                let localStorage = window.localStorage;
                localStorage.removeItem('pdf_file');
                localStorage.removeItem('reasons');
            }
        </script>
    </body>
</html>