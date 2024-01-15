<?php
    // If user is already logged in
    // go to home page
    if(!isset($_SESSION))  { 
        session_start(); 
    }

    if(!isset($_SESSION['username'])) {
        header("location: javascript:history.go(-1)");
    }
    

    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    $username = $_SESSION['username'];

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        if(isset($_SESSION['type']) and $_SESSION['type'] == 'student'){
            $sql = "SELECT a.title, c.company_name, u.firstname, u.surname, u.phone, u.email, sa.reasons
                    FROM user u, student s, student_application sa, ad a, company c
                    WHERE (u.username = '$username') AND (s.user_id = u.id) AND (s.id = sa.student_id) AND (sa.id = '$id') AND (sa.ad_id = a.id) AND (a.company_id = c.id)";

            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);
        } else {
            $sql = "SELECT sa.id as 'app_id', a.id as 'ad_id', a.title, c.company_name, u.firstname, u.surname, u.phone, u.email, sa.reasons
                    FROM user u, student s, student_application sa, ad a, company c
                    WHERE (s.user_id = u.id) AND (s.id = sa.student_id) AND (sa.id = '$id') AND (sa.ad_id = a.id) AND (a.company_id = c.id)";

            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);

            $update = "UPDATE student_application sa SET sa.read='1' where sa.id = ".$row['app_id'].";";

            $conn->query($update) or die(mysql_error()."<br />".$update);
        }
    }

?>

<!DOCTYPE html>
<html lang="el">
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/profile.css">
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
            <?php if(isset($_SESSION['type']) and $_SESSION['type'] == 'student') : ?>
                <div style="font-size: 12px; float: left; margin: 10px;">Αιτήσεις</div>
            <?php else : ?>
                <div style="font-size: 12px; float: left; margin: 10px;"><a href="company_ads.php">Αγγελίες</a></div>
                <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
                <div style="font-size: 12px; float: left; margin: 10px;"><a href="ad_student_applications.php?id=<?php echo $row['ad_id']; ?>" target="_blank">Αιτήσεις Φοιτητών/τριων</a></div>
                <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
                <div style="font-size: 12px; float: left; margin: 10px;">Αίτηση <?php echo $row['firstname'] . "  " . $row['surname'];?></div>
            <?php endif; ?>
        </div> <br>

        <?php
            include 'profile_menu.php';
        ?>
        <br><br>
        <?php if (isset($_SESSION['type']) and $_SESSION['type'] == 'student') { ?>
                <div><a class="back" onclick="window.close()" style="cursor:pointer; margin-left:60px;">< Πίσω</a></div>
        <?php    } else if (isset($_SESSION['type']) and $_SESSION['type'] == 'company'){ ?>
                <div><a class="back" href="ad_student_applications.php?id=<?php echo $row['ad_id']; ?>" style="cursor:pointer; margin-left:60px;">< Πίσω</a></div>
        <?php    }?>
        
        <div style="margin: 50px; margin-left: 80px;">
            <div>
                <b style="margin-right: 15px">Αίτηση για Πρακτική Άσκηση (ΠΑ) στη θέση:</b>
                <?php echo $row['title']?> - <?php echo $row['company_name']?>
            </div>
            <br><br>
            
            <div><b style="margin-right: 85px">Ονοματεπώνυμο:</b> <?php echo $row['firstname'] . "  " . $row['surname'];?></div><br>

            <div><b style="margin-right: 42px">Στοιχεία Επικοινωνίας:</b> <?php echo $row['phone']. ", " . $row['email'];?></div><br>

            <div><b style="margin-right: 41px">Αναλυτική Βαθμολογία:</b> <a href="download.php?id=<?php echo $id; ?>" target="_blank">Αρχείο</a></div> <br>

            <div class="nested">
                <b style="margin-right: 15px;">Η θέση με ενδιαφέρει γιατί:</b>
                <div style="white-space: pre-wrap"><?php echo str_ireplace("\\r\\n", "\r\n", $row['reasons']);?></div>
            </div>
        </div> 

        <br><br>
        <?php
            include 'bottom_base.php';
        ?>

        <script>
            <?php if(isset($_SESSION['type']) and $_SESSION['type'] == 'student') : ?>
                document.getElementById("profile_menu_student_applications").classList.add("active");
            <?php else : ?>
                document.getElementById("profile_menu_company_ads").classList.add("active");
            <?php endif; ?>
        </script>
    </body>
</html>