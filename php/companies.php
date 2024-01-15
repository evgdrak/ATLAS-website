<!doctype html>  
<html lang="el">  
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/students.css">
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
            <div style="font-size: 12px; float: left; margin: 10px;">Φορείς Υποδοχής</div>
        </div>

        <br><br>
        <h1 style="text-align: center">Είσαι Φορέας Υποδοχής;</h1>
        <h3 style="text-align: center">Βρες εύκολα και γρήγορα άτομα για τις αγγελίες που θα δημοσιεύσεις σε 3 βήματα!</h3>

            <div class="wrapper">
                <div class="nested">
                    <div style="font-weight: bold; font-size: 20px;">Βήμα 1 - Δημιουργία Αγγελίας</div>
                    <div>Συμπλήρωσε την αγγελία σου με τις κατάλληλες πληροφορίες που αφορούν τη συγκεκριμένη θέση.</div>
                    <span><a href="create_ad.php">Δημιουργία Αγγελίας</a></span>
                </div>
                <div class="nested">
                    <div style="font-weight: bold; font-size: 20px;">Βήμα 2 - Παρακολούθηση Αγγελίας</div>
                    <div>Παρακολούθησε την κατάσταση των αγγελιών σου από το προφίλ σου > Αγγελίες.</div>
                    <!-- <span><a href="#">Αγγελίες</a></span> -->
                </div>
                <div class="nested">
                    <div style="font-weight: bold; font-size: 20px;">Βήμα 3 - Επιλογή Φοιτητών/τριων</div>
                    <div>Δες από το προφίλ σου ποιοι/ες φοιτητές/τριες έχουν δηλώσει ότι ενδιαφέρονται για τη θέση και διάλεξε το άτομο με τα κατάλληλα προσόντα.</div>
                </div>
            </div>
            <br><br><br>
        <?php
            include 'bottom_base.php';
        ?>
      </body>
</html>  