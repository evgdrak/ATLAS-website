<!doctype html>  
<html lang="el">  
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/faq.css">
    </head>  
    <body>
        <?php
            include 'top_base.php';
        ?>
        <?php
            include 'nav_bar.php';
        ?>
        
        <div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="index.php">Αρχική Σελίδα </a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="faq.php">Συχνές Ερωτήσεις</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">Φοιτητές/τριες</div>
        </div>

        <br><br>
        <h1 style="text-align: center">Συχνές Ερωτήσεις</h1>
        
        <div class="wrapper">
            <div class="search-box">
                <div><a href="faq.php" style="text-decoration:none; color:black;">> Γενικά</a></div>
                <div><b><a href="faq_students.php" style="text-decoration:none; color:black;">> Φοιτητές/τριες</a></b></div>
                <div><a href="faq_companies.php" style="text-decoration:none; color:black;">> Φορείς Υποδοχής</a></div>
                <div><a href="under_construction.php" style="text-decoration:none; color:black;">> Γραφείο Πρακτικής Άσκησης (ΠΑ)</a></div>
            </div>

            <div class="questions">
                <details>
                    <summary>Πώς μπορώ να βρω μια θέση Πρακτικής Άσκησης (ΠΑ);</summary>
                    <p>Μέσω της <a href="search.php">Αναζήτησης Θέσεων ΠΑ</a>.</p>
                </details>
                <br>
                <details>
                    <summary> Βρήκα κάποιες θέσεις ΠΑ που με ενδιαφέρουν, τι πρέπει να κάνω;</summary>
                    <p> Μπορείτε να κάνετε αίτηση σε όσες θέσεις ΠΑ θέλετε. Για την αίτηση θα χρειαστείτε ένα αντίγραφο της αναλυτικής βαθμολογίας σας και μία παράγραφο με τους λόγους που σας ενδιαφέρει η θέση.</p>
                </details>
                <br>
                <details>
                    <summary> Πώς θα μάθω αν με δέχτηκαν σε μία θέση;</summary>
                    <p> Από το προφίλ > Αιτήσεις μπορείτε να πρακολουθείτε την κατάσταση των αιτήσεων σας.</p>
                </details>
                <br>
                <details>
                    <summary> Πώς μπορώ να επικοινωνήσω με ένα φορέα;</summary>
                    <p> Σε κάθε αγγελία υπάρχουν τα στοιχεία επικοινωνίας με τον αντίστοιχο φορέα υποδοχής.</p>
                </details>
            </div>
        </div>
        
        
        <br><br><br>
        <?php
            include 'bottom_base.php';
        ?>
      </body>
</html>  