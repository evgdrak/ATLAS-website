<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    if(!isset($_SESSION['username']) || (isset($_SESSION['username']) && $_SESSION['type'] != 'company')) {
        header("location: create_ad.php");
    } 

    $username = $_SESSION['username'];

    // if(!isset($_SESSION['saved_info'])){
    //     // $_SESSION['saved_info']['clicked_1'] = 0;
    //     $_SESSION['saved_info']['clicked_2'] = 0;
    // }

    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    // if(isset($_POST['next_btn'])){
    //     foreach ($_POST as $key => $value){
    //         $_SESSION['info'][$key] = $value;
    //     }

    //     $keys = array_keys($_SESSION['info']);

    //     if (in_array('next_btn', $keys)){
    //         unset($_SESSION['info']['next_btn']);
    //     }

    //     if (in_array('save_btn', $keys)){
    //         unset($_SESSION['info']['save_btn']);
    //     }

    //     header("Location: create_ad_2.php");
    // }

    if(isset($_POST['save_btn'])){

        // if ( (isset($_SESSION['saved_info']['clicked_2'])) && ($_SESSION['saved_info']['clicked_2'] == 0) && (!empty($_POST['depArr'])) ){
        if ( (isset($_SESSION['saved_info'])) && (!empty($_POST['depArr'])) ){

            $sql = "SELECT a.id FROM ad a, user u, company c WHERE (u.username = '$username') AND (c.user_id = u.id) AND (c.id = a.company_id) ORDER BY a.id DESC";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);
            $ad_id = $row['id'];

            $dep = $_POST['depArr'];
            // print_r($dep);
            
            // print_r($_POST['depArr']);

            $delete_sql = "DELETE FROM department_ad WHERE ad_id='$ad_id'";
            $result = mysqli_query($conn, $delete_sql);

            $insert_sql = "INSERT INTO department_ad(department_id, ad_id) VALUES ";
            $len = count($dep);
            $last = $dep[$len-1];
            echo $last;
            foreach($dep as $dep_id){
                if ( $dep_id != $last ){
                    $insert_sql .= "('$dep_id', '$ad_id'), ";
                } else {
                    $insert_sql .= "('$dep_id', '$ad_id') ";
                }   
            }
            echo $insert_sql;

            $result = mysqli_query($conn, $insert_sql);

            $dep_uni = "";

            foreach($dep as $dep_id){
                $sql = "SELECT d.name as 'department', u.name as 'university' FROM department d, university u WHERE (d.id = '$dep_id') AND (d.university_id = u.id)"; 
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($result);
                $dep_uni .= $row['department']."(".$row['university'].")";
                if ($dep_id != $last){
                    $dep_uni .= ",  ";
                }
            }

            // $_SESSION['saved_info']['clicked_2'] = 1;
            $_SESSION['saved_info']['departments'] = $dep_uni;

            echo $dep_uni;

            echo '<script>alert("Έγινε προσωρινή αποθήκευση.")</script>';

        } 
    }

    if(isset($_POST['next_btn'])){

        if ( (isset($_SESSION['saved_info'])) && (!empty($_POST['depArr'])) ){

            $sql = "SELECT a.id FROM ad a, user u, company c WHERE (u.username = '$username') AND (c.user_id = u.id) AND (c.id = a.company_id) ORDER BY a.id DESC";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);
            $ad_id = $row['id'];

            $_SESSION['saved_info']['ad_id'] = $ad_id;

            $dep = $_POST['depArr'];

            $delete_sql = "DELETE FROM department_ad WHERE ad_id='$ad_id'";
            $result = mysqli_query($conn, $delete_sql);

            $insert_sql = "INSERT INTO department_ad(department_id, ad_id) VALUES ";
            $len = count($dep);
            $last = $dep[$len-1];
            //echo $last;
            foreach($dep as $dep_id){
                if ( $dep_id != $last ){
                    $insert_sql .= "('$dep_id', '$ad_id'), ";
                } else {
                    $insert_sql .= "('$dep_id', '$ad_id') ";
                }   
            }
            //echo $insert_sql;

            $result = mysqli_query($conn, $insert_sql);

            $dep_uni = "";

            foreach($dep as $dep_id){
                $sql = "SELECT d.name as 'department', u.name as 'university' FROM department d, university u WHERE (d.id = '$dep_id') AND (d.university_id = u.id)"; 
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($result);
                $dep_uni .= $row['department']."(".$row['university'].")";
                if ($dep_id != $last){
                    $dep_uni .= ",  ";
                }
            }

            $_SESSION['saved_info']['departments'] = $dep_uni;

            //echo $dep_uni;

            header("Location: create_ad_3.php");

        } 
    }
  

    if(isset($_GET['submit'])){

        $university = $_GET["university"];
        $department = $_GET["department"];

        $sql = "SELECT u.name as 'university', d.name as 'department', d.id
                FROM university u, department d
                WHERE (d.university_id = u.id) ";

        if ($university != ""){
            $sql .= " AND (u.id = '$university') ";

        } 

        if ($department != ""){
            $sql .= " AND (d.name LIKE '%".$department."%') ";
        }

        $sql .= "ORDER BY u.name, d.name";
        // echo $sql;

        $search_result = filter($conn, $sql);
    } else {
        $sql = "SELECT u.name as 'university', d.name as 'department', d.id
                FROM university u, department d
                WHERE (d.university_id = u.id)
                ORDER BY u.name, d.name;";

        $search_result = filter($conn, $sql);
    }

    function filter($conn, $sql){
        $filter_result = mysqli_query($conn, $sql);
        return $filter_result;
    }
?>


<!doctype html>  
<html lang="el">  
    <head>
        <title> ΑΤΛΑΣ </title>
        <link rel="stylesheet" href="../css/create_ad_1.css">
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
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="companies.php">Φορείς Υποδοχής</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">Δημιουργία Αγγελίας</div>
        </div>

        <br><br>
        <h1 style="text-align: center">Δημιουργία Αγγελίας</h1>

            <div class="wrapper">
                <!-- <div class="nested"> -->
                    <section class="step-wizard">
                        <ul class="step-wizard-list">
                            <li class="step-wizard-item">
                                <span class="progress-count">1</span>
                                <span class="progress-label">Πληροφορίες Αγγελίας</span>
                            </li>
                            <li class="step-wizard-item current-item">
                                <span class="progress-count">2</span>
                                <span class="progress-label">Επιλογή Τμημάτων</span>
                            </li>
                            <li class="step-wizard-item next-item">
                                <span class="progress-count">3</span>
                                <span class="progress-label">Δημοσίευση Αγγελίας</span>
                            </li>
                        </ul>
                    </section>
                <!-- </div> -->
                <div style="margin-left: 20px">
                    <form action="" method="get">
                        <label for="university" style="margin: 10px">Α.Ε.Ι</label>
                        <!-- <input type="text" class="inp" name="university" id="university" style="width: 25%" placeholder="Επιλογή Α.Ε.Ι."> -->
                        <select class="inp" name="university" id="university" style="width: 30%">
                            <option value="" hidden>Επιλογή Α.Ε.Ι.</option>
                            <?php
                                // Get all the categories from category table
                                $sql = "select university.*, GROUP_CONCAT(department.name order by department.name) as department_names, GROUP_CONCAT(department.id order by department.name) as department_ids from university join department on university.id = department.university_id  group by university.id order by university.name;";
                                $all_universities = mysqli_query($conn, $sql);
                                $row_names = array();
                                $row_ids = array();
                                
                                // use a while loop to fetch data
                                // from the $all_universities variable
                                // and individually display as an option
                                while ($university = mysqli_fetch_array($all_universities)):;
                                    $row_names[] = $university["department_names"];
                                    $row_ids[] = $university["department_ids"];
                            ?>
                            <option value="<?php echo $university["id"];?>">
                            <?php echo $university["name"];
                                // To show the university name to the user
                            ?>
                            </option>
                            <?php
                                endwhile;
                                // While loop must be terminated
                            ?>
                        </select>

                        <label for="department" style="margin: 10px; margin-left: 20px">Τμήμα</label>
                        <input type="text" class="inp" name="department" id="department" style="width: 25%" placeholder="π.χ. Πληροφορικής">

                        <button type="submit" name="submit" class="btn1" style="margin-left: 15px; width: 12%"><img alt="magnifying_glass_solid" src="../img/magnifying-glass-solid.png" style="width: 10%; margin-right: 10px;">Αναζήτηση</button>
                    </form>
                </div>
                
                <span class="total" id="total"></span>
                    
                <form action="create_ad_2.php" method="post">
                    <div>   
                        <table class="t2">
                            <tr>
                                <th>A.E.I</th>
                                <th>Τμήμα</th>
                                <th><label for="select_all" hidden>Επιλογή όλων<?php echo $row['id']?></label><input id="select_all" type="checkbox" onClick="toggle(this)" style="margin-left: 10px; width: 15px; height:15px;"></th>
                            </tr>
                            <?php 
                                if (mysqli_num_rows($search_result)==0) { 
                                    echo "Δεν βρέθηκαν αποτελέσματα"; 
                                } else {
                                    // echo mysqli_num_rows($search_result);
                                    while($row = mysqli_fetch_array($search_result)) :?>
                                        <tr>
                                            <td>
                                                <?php echo $row['university'];?>
                                            </td>
                                            <td>
                                                <?php echo $row['department'];?>
                                            </td>
                                            <td>
                                                <label for="<?php echo $row['id']?>" hidden><?php echo $row['id']?></label>
                                                <input type="checkbox" name="depArr[]" value="<?php echo $row['id']?>" id="<?php echo $row['id']?>" onclick="check_selected(this.id)" style="width: 15px; height:15px;">
                                            </td>
                                        </tr>
                                    <?php endwhile; 
                                }?>
                        </table>
                    </div><br><br><br>

                    <div>
                        <button class="btn1" type="button" onclick="location.href='create_ad_1.php'" style="float: left; background-color: #afb1b1;">Προηγούμενο Βήμα</button>
                        <input type="submit" class="btn1" name="save_btn" style="background-color: #afb1b1;" value="Προσωρινή Αποθήκευση">
                        <input type="submit" onClick="check()" class="btn1" name="next_btn"  value="Επόμενο Βήμα" style="float: right; background-color: #6ccd71;">
                    </div>
                </form>
            </div>
            <br><br><br>
        <?php
            include 'bottom_base.php';
        ?>
      </body>

      <script>

        let total = 0;
        document.getElementById('total').innerHTML = "Έχεις επιλέξει (" + total + ") τμήματα!";

        function toggle(source) {
            checkboxes = document.getElementsByName('depArr[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
                if (source.checked){
                    total++;
                } else {
                    total--;
                } 
            }  

            // total = checkboxes.length;
            document.getElementById('total').textContent = "Έχετε επιλέξει (" + total + ") τμήματα.";
        }

        function check(){
            if (total == 0) {
                // document.getElementById('error').innerHTML = "Δεν έχετε επιλέξει τμήματα!";
                alert("Δεν έχετε επιλέξει τμήματα!");
            } 
        }

        function check_selected(id){
            if (document.getElementById(id).checked){
                total ++;
            } else {
                total --;
            }
            
            document.getElementById('total').textContent = "Έχετε επιλέξει (" + total + ") τμήματα.";
        }

      </script>
</html>  