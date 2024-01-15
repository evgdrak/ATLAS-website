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
            <div style="font-size: 12px; float: left; margin: 10px;">Φορείς Υποδοχής</div>
        </div>

        <br><br>
        <h1 style="text-align: center">Συχνές Ερωτήσεις</h1>
        
        <div class="wrapper">
            <div class="search-box">
                <div><a href="faq.php" style="text-decoration:none; color:black;">> Γενικά</a></div>
                <div><a href="faq_students.php" style="text-decoration:none; color:black;">> Φοιτητές/τριες</a></div>
                <div><b><a href="faq_companies.php" style="text-decoration:none; color:black;">> Φορείς Υποδοχής</a></b></div>
                <div><a href="under_construction.php" style="text-decoration:none; color:black;">> Γραφείο Πρακτικής Άσκησης (ΠΑ)</a></div>
            </div>

            <div class="questions">
                <details>
                    <summary>Πώς μπορώ να δημοσιεύσω μια αγγελία;</summary>
                    <p>Μέσω της <a href="create_ad.php">Δημιουργίας Αγγελίας</a>.</p>
                </details>
                <br>
                <details>
                    <summary> Πώς θα μάθω ποιοι/ες φοιτητές/τριες ενδιαφέρονται για μια θέση;</summary>
                    <p> Από το προφίλ > Αγγελίες μπορείτε να παρακολουθείτε την κατάσταση των αγγελιών σας.</p>
                </details>
                <br>
                <details>
                    <summary> Πώς θα διαλέξω φοιτητές/τριες για μία θέση;</summary>
                    <p> Από το προφίλ > Αγγελίες > Αιτήσεις Φοιτητών/τριων μπορείτε να δεχτείτε ή να απορρίψετε αιτήσεις για κάθε αγγελία ξεχωριστά.</p>
                </details>
                <br>
                <details>
                    <summary> Πώς μπορώ να επικοινωνήσω με τους/τις φοιτητές/τριες που διάλεξα;</summary>
                    <p> Στην αίτηση ενδιαφέροντος κάθε φοιτητή/τριας αναγράφονται τα στοιχεία επικοινωνίας τους.</p>
                </details>
            </div>
        </div>
        
        
        <br><br><br>
        <?php
            include 'bottom_base.php';
        ?>
      </body>
</html>  