<!doctype html>
<html lang="el">
    <head>
        <link rel="stylesheet" href="../css/nav_bar.css">
    </head>
    <body>
        <?php
            include 'show_profile.php';
        ?>
        <br>
        <div class="topnav">
            <div class="dropdown">
                <button class="dropbtn" onclick="location.href='faq.php'">Συχνές Ερωτήσεις<img alt="arrow_down" src="../img/white_arrow.png" style="width: 7%; margin-left: 10px;"></button>
                <div class="dropdown-content">
                    <a href="faq.php">Γενικά</a>
                    <a href="faq_students.php">Φοιτητές/τριες</a>
                    <a href="faq_companies.php">Φορείς Υποδοχής</a>
                    <a href="under_construction.php">Γραφείο ΠΑ</a>
                </div>
            </div>
            <a href="announcements.php">Ανακοινώσεις</a>
            <a href="under_construction.php">Γραφείο ΠΑ</a>
            <div class="dropdown">
                <button class="dropbtn" onclick="location.href='companies.php'">Φορείς Υποδοχής<img alt="arrow_down" src="../img/white_arrow.png" style="width: 7%; margin-left: 10px;"></button>
                <div class="dropdown-content">
                <a href="create_ad.php">Δημιουργία Αγγελίας</a>
                <!-- <a href="#">Αναζήτηση Φορέων Υποδοχής</a> -->
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn" onclick="location.href='students.php'"> Φοιτητές/τριες<img alt="arrow_down" src="../img/white_arrow.png" style="width: 7%; margin-left: 10px;"></button>
                <div class="dropdown-content">
                    <a href="search.php">Αναζήτηση Θέσεων ΠΑ</a>
                </div>
            </div>
            <a href="index.php"> Αρχική </a>
        </div>
    </body>
    
    <script>
        // window.onscroll = function() {myFunction()};

        // var navbar = document.getElementById("navbar");
        // var sticky = navbar.offsetTop;

        // function myFunction() {
        //     if (window.pageYOffset >= sticky) {
        //         navbar.classList.add("sticky")
        //     } else {
        //         navbar.classList.remove("sticky");
        //     }
        // }
    </script>
</html>  