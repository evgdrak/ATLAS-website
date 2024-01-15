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

    if(isset($_SESSION['saved_info'])){
        unset($_SESSION['saved_info']);
    }
    
    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error)
        die($conn->connect_error);

    $username = $_SESSION['username'];
    $sql = "SELECT a.id as 'ad_id', a.title, a.state, a.date
            FROM user u, company c, ad a
            WHERE (u.username = '$username') AND (c.user_id = u.id) AND (a.company_id = c.id) AND (a.state != 'auto-save')
            ORDER BY a.id DESC";

    $result = mysqli_query($conn, $sql);

    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $sql2 = "SELECT a.id as 'ad_id', a.title
                FROM user u, company c, student_application sa, ad a
                WHERE (u.username = '$username') AND (c.user_id = u.id) AND (c.id = a.company_id) AND (sa.ad_id = a.id) AND (sa.company_accepted IS NULL AND sa.read = false) AND (sa.state != 'auto-save')
                GROUP BY a.id";
        
        $result2 = mysqli_query($conn, $sql2);
    }

    if (isset($_GET['search_btn']) ) {
        $state = $_GET['state'];
        $sql = "SELECT a.id as 'ad_id', a.title, a.state, a.date
                FROM user u, company c, ad a
                WHERE (u.username = '$username') AND (c.user_id = u.id) AND (a.company_id = c.id) ";

        if ($state != "Όλες"){
            $sql .= " AND (a.state = '$state') ";
        } else {
            $sql .= " AND (a.state != 'auto-save') ";
        }

        $sql .= " ORDER BY a.id DESC";

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
            <div style="font-size: 12px; float: left; margin: 10px;">Αγγελίες</div>
        </div>

        <?php
            include 'profile_menu.php';
        ?>

        <br><br>

        <?php while($row2 = mysqli_fetch_array($result2)) :?>
            <div class="notification_alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                Υπάρχει νέα αίτηση στην θέση "<a style="color:black;" href="ad_student_applications.php?id=<?php echo $row2['ad_id']; ?>" target="_blank"><?php echo $row2['title']; ?></a>".
            </div>
        <?php endwhile; ?>

        <br><br>
        <div>
            <form action="" method="get">    
                <div class="wrapper">
                    <div style="padding: 12px;">
                        <label for="state">Κατάσταση Αγγελίας:</label>
                    </div>
                    <div>
                        <select id="state" name="state">
                            <option value="Όλες">Όλες</option>
                            <option value="Προσωρινά Αποθηκευμένη">Προσωρινά Αποθηκευμένη</option>
                            <option value="Δημοσιευμένη">Δημοσιευμένη</option>
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
                        <th>Αγγελία</th>
                        <th>Ημερομηνία Δημοσίευσης</th>
                        <th>Κατάσταση Αγγελίας</th>
                        <th>Ενέργεια</th>
                    </tr>
                    <?php while($row = mysqli_fetch_array($result)) :?> 
                        <tr>
                            <td>
                                <?php if ( $row['state'] == "Προσωρινά Αποθηκευμένη" ){ ?>
                                <h3><img alt="file_search" src="../img/file_search.png" style="width: 20px; margin-right: 10px;"><?php echo $row['title'];?></h3>
                                <?php } else if ( $row['state'] == "Δημοσιευμένη" ) { ?>
                                <h3><a href="details.php?id=<?php echo $row['ad_id']; ?>" target="_blank" style="text-decoration: none; color:black;"><img alt="file_search" src="../img/file_search.png" style="width: 20px; margin-right: 10px;"><?php echo $row['title'];?></a></h3>
                                <?php } ?>
                            </td>
                            <td><?php echo $row['date'];?></td>
                            <td><?php echo $row['state']; ?></td>
                            <td>
                                <?php if ( $row['state'] == "Προσωρινά Αποθηκευμένη" ){ ?>
                                    <button class="ad_info_btn" style="background-color: #9cbeee;"><img alt="pen" src="../img/pen.png" style="width: 15px; margin-right: 10px;"><a href="create_ad_1.php?id=<?php echo $row['ad_id']; ?>" style="text-decoration: none; color:black;">Επεξεργασία</a></button>
                                    <button class="ad_info_btn" onclick="confirmDelete(<?php echo $row['ad_id']; ?>, '<?php echo $row['title']; ?>')" style="background-color: #f37f7f;"><img alt="trash" src="../img/trash.png" style="width: 13px; margin-right: 10px;">Διαγραφή</button>
                                <?php } else if ( $row['state'] == "Δημοσιευμένη" ) { ?>
                                    <button class="ad_info_btn" style="background-color: #9cbeee;"><img alt="file_search" src="../img/file_search.png" style="width: 17px; margin-right: 10px;"><a href="ad_student_applications.php?id=<?php echo $row['ad_id']; ?>" target="_blank" style="text-decoration: none; color:black;">Αιτήσεις Φοιτητών/τριων</a></button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div> <br><br><br><br><br><br><br><br>
        <?php
            include 'bottom_base.php';
        ?>

        <script>
            document.getElementById("profile_menu_company_ads").classList.add("active");
            function confirmDelete(id, title){
                console.log(id);
                console.log(title);
                let message1 = "Θέλετε να διαγράψετε την αγγελία της θέσης ";
                let message2 = message1 + title + " ;"

                if (confirm(message2) == true) {
                    location.href = "delete_ad.php?id="+id;
                }
            }

            function cleanStorage(){
                let localStorage = window.localStorage;
                localStorage.removeItem('title');
                localStorage.removeItem('object');
                localStorage.removeItem('type');
                localStorage.removeItem('duration');
                // console.log("clean");
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
    </body>
</html>