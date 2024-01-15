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
            <div style="font-size: 12px; float: left; margin: 10px;">Φοιτητές/τριες
        </div>

        <br><br>
        <h1 style="text-align: center">Είσαι Φοιτητής/τρια;</h1>
        <h3 style="text-align: center">Βρες εύκολα και γρήγορα μια θέση πρακτικής άσκησης σε 3 βήματα!</h3>
        <!-- <div class="all">
            <h1>Είσαι Φοιτητής/τρια;</h1>
            <div class="box">
                <a class="choose-box" href="search.php">
                    <h2 class="field" style="margin: 5px;">Αναζήτηση Θέσεων ΠΑ</h2>
                    <p class="field" style="margin: 5px;">Βρες μία θέση ΠΑ πάνω στο αντικείμενο που σε ενδιαφέρει.</p>
                </a>
                <a class="choose-box" href="">
                    <h2 class="field" style="margin: 5px;">Δημιουργία αίτησης</h2>
                    <p class="field" style="margin: 5px;">Κάνε μία αίτηση στη θέση ΠΑ που διάλεξες.</p>
                </a>
                <a class="choose-box" href="">
                    <h2 class="field" style="margin: 5px;">Αναζήτηση Φορέα Υποδοχής</h2>
                    <p class="field" style="margin: 5px;">Βρες για κάθε φορέα τις τρέχουσες αγγελίες του καθώς και αξιολογήσεις για αυτόν.</p>
                </a>
            </div>
        </div> -->
            <div class="wrapper">
                <div class="nested">
                    <div style="font-weight: bold; font-size: 20px;">Βήμα 1 - Αναζήτηση Θέσεων ΠΑ</div>
                    <div>Βρες μία θέση Πρακτικής Άσκησης (ΠΑ) πάνω στο αντικείμενο που σε ενδιαφέρει.</div>
                    <span><a href="search.php">Αναζήτηση Θέσεων ΠΑ</a></span>
                </div>
                <div class="nested">
                    <div style="font-weight: bold; font-size: 20px;">Βήμα 2 - Δημιουργία Αίτησης</div>
                    <div>Κάνε μία αίτηση στη θέση ΠΑ που διάλεξες.</div>
                    <div>Για την αίτηση θα χρειαστείς:
                        <li>Ένα αντίγραφο της αναλυτικής βαθμολογίας σου σε μορφή pdf</li>
                        <li>Μια παράγραφο με τους λόγους που σε ενδιαφέρει αυτή η θέση</li>
                    </div>
                </div>
                <div class="nested">
                    <div style="font-weight: bold; font-size: 20px;">Βήμα 3 - Παρακολούθηση αίτησης</div>
                    <div>Παρακολούθησε την κατάσταση των αιτήσεων σου από το προφίλ σου > Αιτήσεις.</div>
                    <span><a href="#" hidden>Αιτήσεις</a></span>
                </div>
            </div>
            <br><br><br>
        <?php
            include 'bottom_base.php';
        ?>
      </body>
</html>  