<?php
    require_once 'database.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    if(isset($_GET['submit'])){

        $title = $_GET["title"];
        $company = $_GET["company"];
        $location = $_GET["location"];
        $type = $_GET["type"];
        $duration = $_GET["duration"];
        $espa = $_GET["espa"];
        $start = $_GET["start"];
        $object = $_GET["object"];
        $university = $_GET["university"];
        $department = $_GET["department"];

        // $sql = "SELECT a.id, a.title, c.company_name, m.name AS 'municipality', r.name AS 'region', a.type, a.duration, a.espa, a.start, a.date 
        //         FROM ad a, company c, municipality m, region r, department d, department_ad da, university u
        //         WHERE (a.company_id = c.id) AND (c.municipality_id = m.id) AND (m.region_id = r.id)  AND (da.ad_id = a.id) AND (da.department_id = d.id) AND (d.university_id = u.id)
        //             AND ( (a.title LIKE '%".$title."%') OR (c.company_name LIKE '%".$company."%') OR (m.name LIKE '%".$location."%') OR (r.name LIKE '%".$location."%') OR (a.type = '$type') OR (a.duration = '$duration') OR 
        //                 (a.start >= '$start') OR (a.object = '$object') OR (u.name = '$university') OR (d.name = '$department'))
        //         GROUP BY a.id
        //         ORDER BY a.id DESC";

        // $search_result = filter($conn, $sql);

        $sql = "SELECT a.id, a.title, c.company_name, m.name AS 'municipality', r.name AS 'region', a.type, a.duration, a.espa, a.start, a.date 
                FROM ad a, company c, municipality m, region r, department d, department_ad da, university u
                WHERE (a.company_id = c.id) AND (c.municipality_id = m.id) AND (m.region_id = r.id)  AND (da.ad_id = a.id) AND (da.department_id = d.id) AND (d.university_id = u.id)
                    AND (a.start >= '$start') AND (a.no_of_vacancies > 0) AND (a.state = 'Δημοσιευμένη') ";

        if ($title != ""){
            $sql .= "AND (a.title LIKE '%".$title."%') ";
        }

        if ($company != ""){
            $sql .= "AND (c.company_name LIKE '%".$company."%') ";
        }

        if ($location != ""){
            $sql .= "AND ( (m.name LIKE '%".$location."%') OR (r.name LIKE '%".$location."%') ) ";
        }

        if ($type != ""){
            $sql .= "AND (a.type = '$type') ";
        }

        if ($duration != ""){
            $sql .= "AND (a.duration = '$duration') ";
        }

        if ($espa != ""){
            $sql .= "AND (a.espa = '$espa') ";
        }

        if ($object != ""){
            $sql .= "AND (a.object = '$object') ";
        }

        if ($university != ""){
            $sql .= "AND (u.id = '$university') ";
        }

        if ($department != ""){
            $sql .= "AND (d.id = '$department') ";
        }

        $sql .= " GROUP BY a.id ORDER BY a.id DESC";
        // echo $sql;

        $search_result = filter($conn, $sql);

    } else {

        $date = date("Y-m-d");
        $sql = "SELECT a.id, a.title, c.company_name, m.name AS 'municipality', r.name AS 'region', a.type, a.duration, a.espa, a.start, a.date 
                    FROM ad a, company c, municipality m, region r 
                    WHERE (a.company_id = c.id) AND (c.municipality_id = m.id) AND (m.region_id = r.id) AND (a.start >= '$date') AND (a.no_of_vacancies > 0) AND (a.state = 'Δημοσιευμένη') ";

        if (isset($_GET["title"])) {
            $title = $_GET["title"];
            $sql .= "  AND ( (a.title LIKE '%".$title."%') OR (c.company_name LIKE '%".$title."%') OR (m.name LIKE '%".$title."%') OR (r.name LIKE '%".$title."%') ) ";

        } 
            
        $sql .= "ORDER BY a.id DESC";
        $search_result = filter($conn, $sql);
        
    }

    if(isset($_SESSION['saved_info'])){
        unset($_SESSION['saved_info']);
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
        <link rel="stylesheet" href="../css/search.css">
<meta name='viewport' content='width=device-width, initial-scale=1'>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>  
    <body onload="getLocalStoredValue()">
        <?php
            include 'top_base.php';
        ?>
        <?php
            include 'nav_bar.php';
        ?>
        
        <div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="index.php">Αρχική Σελίδα</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;"><a href="students.php">Φοιτητές/τριες</a></div>
            <div style="font-size: 12px; float: left; margin: 10px; margin-left: 2px; margin-right: 2px;">/</div>
            <div style="font-size: 12px; float: left; margin: 10px;">Αναζήτηση θέσεων ΠΑ</div>
        </div>
        <!-- <div class="pagination">
            <a href="#">&laquo;</a>
            <a href="#" class="active">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">5</a>
            <a href="#">6</a>
            <a href="#">&raquo;</a>
        </div> -->
        <br><br>

        <div class="wrapper">
            <div class="search-box">
                <div><h2 style="text-align:center">Φίλτρα Αναζήτησης</h2></div>
                <form action="search.php" method="GET">    
                    <div>
                        <label class="field" for="title">Τίτλος Θέσης</label><br>
                        <input type="text" class="text_inp" name="title" id="title" placeholder="π.χ. Βοηθός Λογιστή">
                        <button type="button" onclick="cleanTitle()" id="clean_title" style="display:none" class="clean_btn">X</button>
                    </div>
                    <div>
                        <label class="field" for="company">Φορέας Υποδοχής</label><br>
                        <input type="text" class="text_inp" name="company" id="company" placeholder="π.χ. Microsoft">
                        <button type="button" onclick="cleanCompany()" id="clean_company" style="display:none" class="clean_btn">X</button>
                    </div>
                    <div>
                        <label class="field" for="location_inp"><img alt="location_icon" src="../img/location.png" style="width: 6%; margin-right: 8px; position:relative;"> Τοποθεσία</label><br>
                        <input type="text" class="text_inp" name="location" id="location_inp" placeholder="π.χ. Μαρούσι">
                        <button type="button" onclick="cleanLocation()" id="clean_location" style="display:none" class="clean_btn">X</button>
                    </div>
                    <div>
                        <label class="field"><img alt="briefcase" src="../img/briefcase.png" style="width: 7%; margin-right: 8px; position:relative;"> Τύπος απασχόλησης</label>
                        <button type="button" id="clean_type" onclick="cleanType()" style="margin-left: 15px; display:none;" class="clean_btn">X</button>
                        <div class="radio_inp">
                            <input type="radio" name="type" id="type_p" value="Πλήρης">
                            <label for="type_p">Πλήρης</label>
                        </div>
                        <div class="radio_inp">
                            <input type="radio" name="type" id="type_m" value="Μερική">
                            <label for="type_m">Μερική</label>
                        </div>
                        <div class="radio_inp">
                            <input type="radio" name="type" id="type_hid" value="" checked hidden>
                            <label for="type_hid" hidden>-</label>
                        </div>
                    </div>
                    <div>
                        <label class="field"><img alt="clock" src="../img/clock.png" style="width: 7%; margin-right: 8px; position:relative;"> Διάρκεια πρακτικής</label>
                        <button type="button" id="clean_duration" onclick="cleanDuration()" style="margin-left: 20px; display:none;" class="clean_btn">X</button>
                        <div class="radio_inp">
                            <input type="radio" name="duration" id="duration3" value="3">
                            <label for="duration3">3μηνη</label>
                        </div>
                        <div class="radio_inp">
                            <input type="radio" name="duration" id="duration6" value="6">
                            <label for="duration6">6μηνη</label>
                        </div>
                        <div class="radio_inp">
                            <input type="radio" name="duration" id="duration_hid" value="" checked hidden>
                            <label for="duration_hid" hidden>-</label>
                        </div>
                    </div>
                    <div>
                        <label class="field">Με/Χωρίς ΕΣΠΑ</label>
                        <button type="button" id="clean_espa" onclick="cleanEspa()" style="margin-left: 20px; display:none;" class="clean_btn">X</button>
                        <div class="radio_inp">
                            <input type="radio" name="espa" id="espa_m" value="Με ΕΣΠΑ">
                            <label for="espa_m">Με ΕΣΠΑ</label>
                        </div>
                        <div class="radio_inp">
                            <input type="radio" name="espa" id="espa_x" value="Χωρίς ΕΣΠΑ">
                            <label for="espa_x">Χωρίς ΕΣΠΑ</label>
                        </div>
                        <div class="radio_inp">
                            <input type="radio" name="espa" id="espa_hid" value="" checked hidden>
                            <label for="espa_hid" hidden>-</label>
                        </div>
                    </div>
                    <div>
                        <label for="start" class="field">Ημερομηνίες εκτέλεσης ΠΑ<br> (έναρξη ΠΑ από:)</label><br>
                        <input type="date" class="text_inp" id="start" name="start" min="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>">
                        <button type="button" id="clean_start" onclick="cleanStart()" style="display:none" class="clean_btn">X</button>
                    </div>
                    <div>
                        <label class="field">Γνωστικό Αντικείμενο</label><br>
                        <select class="text_inp" name="object" id="object">
                            <option value="" selected="selected" hidden>Επιλογή Αντικειμένου</option>
                            <option value="Ανθρώπινο Δυναμικό">Ανθρώπινο Δυναμικό</option>
                            <option value="Βοηθητικό Προσωπικό">Βοηθητικό Προσωπικό</option>
                            <option value="Δημόσιες Σχέσεις">Δημόσιες Σχέσεις</option>
                            <option value="Διοίκηση">Διοίκηση</option>
                            <option value="Εκπαίδευση">Εκπαίδευση</option>
                            <option value="Εξυπηρέτηση Πελατών">Εξυπηρέτηση Πελατών</option>
                            <option value="Ιατρική">Ιατρική</option>
                            <option value="Μάρκετινγκ">Μάρκετινγκ</option>
                            <option value="Μηχανικός">Μηχανικός</option>
                            <option value="Νομική">Νομική</option>
                            <option value="Οικονομικά">Οικονομικά</option>
                            <option value="Πληροφορική">Πληροφορική</option>
                            <option value="Πωλήσεις">Πωλήσεις</option>
                            <option value="Σύμβουλος">Σύμβουλος</option>
                        </select>
                        <button type="button" id="clean_object" onclick="cleanObject()" style="display:none" class="clean_btn">X</button>
                    </div>
                    <div>
                        <label class="field">Α.Ε.Ι</label><br>
                        <select class="text_inp" name="university" id="university">
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
                        <button type="button" id="clean_university" onclick="cleanUniversity()" style="display:none" class="clean_btn">X</button>
                    </div>
                    <div>
                        <label class="field">Τμήμα<span style="font-size:13px; margin-left:10px;">(*επιλογή Α.Ε.Ι. πρώτα)</span></label><br>
                        <select class="text_inp" name="department" id="department" readonly>
                            <option value="" selected>Επιλογή Τμήματος</option>
                        </select>
                        <button type="button" id="clean_department" onclick="cleanDepartment()" style="display:none" class="clean_btn">X</button>
                    </div>
                    <div>
                        <button type="submit" class="btn" name="submit" id="submit">Αναζήτηση</button>
                        <button type="button" class="btn" name="clean" id="clean" onclick="cleanFilters()" style="margin-top: 25px; background-color: #afb1b1;">Καθαρισμός</button>
                        <!-- <button type="button" class="btn" name="clean" id="clean" onclick="cleanFilters()" style="margin-top: 25px; background-color: #eab77c;">Καθαρισμός</button> -->
                    </div>
                </form>
            </div>

            <div>
                <ul class="ad-list">
                    <?php 
                        if (mysqli_num_rows($search_result)==0) { 
                            echo "Δεν βρέθηκαν αποτελέσματα"; 
                        } else {
                            // echo mysqli_num_rows($search_result);
                            while($row = mysqli_fetch_array($search_result)) :?>
                            <li class="ad-info">
                                <!-- <div class="logo-pic">
                                    <img alt="company logo" src="../img/logo.png" class="pic" style="width:70%; margin-left: 10px;">
                                </div> -->
                                <!-- <div class="code">
                                    κωδ. <?php echo $row['id'];?>
                                </div> -->
                                <div class="title">
                                    <?php echo $row['title'];?>
                                </div>
                                <div class="company">
                                    <?php echo $row['company_name'];?>
                                </div>
                                <div class="location">
                                    <img alt="location pin" src="../img/location.png" style="width:7%; margin-right: 5px;"> 
                                    <?php echo $row['municipality'] . ", ";?>
                                    <?php echo $row['region'];?>
                                </div>
                                <div class="type">
                                    <img alt="briefcase" src="../img/briefcase.png" style="width:4%; margin-right: 5px;"> 
                                    <?php echo $row['type'];?> Απασχόληση
                                </div>
                                <div class="duration">
                                    <img alt="clock" src="../img/clock.png" style="width:4%; margin-right: 5px;">
                                    <?php echo $row['duration'];?> μήνες
                                </div>
                                <div class="espa">
                                    <?php echo $row['espa'];?>
                                </div>
                                <div class="published">
                                    Δημοσίευση: <?php echo $row['date'];?>
                                </div>
                                <div class="start-date">
                                    Έναρξη ΠΑ μέχρι: <?php echo $row['start'];?>
                                </div>
                                <!-- <div class="heart">
                                    <img alt="heart" src="../img/heart-regular.png" class="pic" style="width: auto;cursor: pointer;">
                                </div> -->
                                <div class="btn-position">
                                    <button class="btn"><a href="details.php?id=<?php echo $row['id']; ?>" target="_blank" style="text-decoration: none; color:black;">Δες τη θέση</a></button>
                                </div>
                            </li>
                            <?php endwhile; 
                        }?>
                        
                </ul>
            </div>
        </div>
      
        <?php
            include 'bottom_base.php';
        ?>
    </body>
        
        <script>
            let title = document.getElementById("title");
            let location_inp = document.getElementById("location_inp");
            let object = document.getElementById("object");
            let type_p = document.getElementById("type_p");
            let type_m = document.getElementById("type_m");
            let duration3 = document.getElementById("duration3");
            let duration6 = document.getElementById("duration6");
            let start = document.getElementById("start");
            let espa_m = document.getElementById("espa_m");
            let espa_x = document.getElementById("espa_x");
            let company = document.getElementById("company");
            let university = document.getElementById("university");
            let department = document.getElementById("department");

            let localStorage = window.localStorage;
            
            university.addEventListener('change', (event) => {
                // document.getElementById('university_error').innerHTML = "";
                // university.style.border = "1px solid #00b300";
                department.readonly = false;

                var all_department_names = JSON.parse('<?php echo json_encode($row_names); ?>');
                var all_department_ids = JSON.parse('<?php echo json_encode($row_ids); ?>');
                var selected_department_names = all_department_names[university.selectedIndex - 1].split(',');
                var selected_department_ids = all_department_ids[university.selectedIndex - 1].split(',');
                
                department.innerHTML = "";
                var el = document.createElement("option");
                el.textContent = 'Επιλογή Τμήματος';
                el.value = "";
                el.hidden = true;
                department.appendChild(el);

                // console.log(selected_department_names);

                for(var i = 0; i < selected_department_names.length; i++) {
                    var el = document.createElement("option");
                    el.textContent = selected_department_names[i];
                    el.value = selected_department_ids[i];
                    // console.log(el.textContent, el.value);
                    department.appendChild(el);
                }

                let newValue = university.value;
                localStorage.setItem('s_university', newValue);
                document.getElementById("clean_university").style.display = "inline-block";
                
            });

            
            title.addEventListener("change", (event) => {
                let newValue = title.value;
                localStorage.setItem('s_title', newValue);
                document.getElementById("clean_title").style.display = "inline-block";
            });

            object.addEventListener("change", (event) => {
                let newValue = object.value;
                localStorage.setItem('s_object', newValue);
                document.getElementById("clean_object").style.display = "inline-block";
            });

            location_inp.addEventListener("change", (event) => {
                let newValue = location_inp.value;
                localStorage.setItem('s_location', newValue);
                document.getElementById("clean_location").style.display = "inline-block";
            });

            type_p.addEventListener("change", (event) => {
                let newValue = type_p.value;
                localStorage.setItem('s_type', newValue);
                document.getElementById("clean_type").style.display = "inline-block";
            });

            type_m.addEventListener("change", (event) => {
                let newValue = type_m.value;
                localStorage.setItem('s_type', newValue);
                document.getElementById("clean_type").style.display = "inline-block";
            });

            duration3.addEventListener("change", (event) => {
                let newValue = duration3.value;
                localStorage.setItem('s_duration', newValue);
                document.getElementById("clean_duration").style.display = "inline-block";
            });

            duration6.addEventListener("change", (event) => {
                let newValue = duration6.value;
                localStorage.setItem('s_duration', newValue);
                document.getElementById("clean_duration").style.display = "inline-block";
            });

            start.addEventListener("change", (event) => {
                let newValue = start.value;
                localStorage.setItem('s_start', newValue);
                document.getElementById("clean_start").style.display = "inline-block";
            });

            espa_m.addEventListener("change", (event) => {
                let newValue = espa_m.value;
                localStorage.setItem('s_espa', newValue);
                document.getElementById("clean_espa").style.display = "inline-block";
            });

            espa_x.addEventListener("change", (event) => {
                let newValue = espa_x.value;
                localStorage.setItem('s_espa', newValue);
                document.getElementById("clean_espa").style.display = "inline-block";
            });

            company.addEventListener("change", (event) => {
                let newValue = company.value;
                localStorage.setItem('s_company', newValue);
                document.getElementById("clean_company").style.display = "inline-block";
            });

            department.addEventListener("change", (event) => {
                let newValue = department.value;
                localStorage.setItem('s_department', newValue);
                newValue = department.options[department.selectedIndex].text;
                console.log(department.options[department.selectedIndex].text);
                localStorage.setItem('s_department_name', newValue);
                document.getElementById("clean_department").style.display = "inline-block";
            });

            function getDepartments(){

                if ( university.value != "" ){

                    department.readonly = false;

                    var all_department_names = JSON.parse('<?php echo json_encode($row_names); ?>');
                    var all_department_ids = JSON.parse('<?php echo json_encode($row_ids); ?>');
                    var selected_department_names = all_department_names[university.selectedIndex - 1].split(',');
                    var selected_department_ids = all_department_ids[university.selectedIndex - 1].split(',');
                    
                    department.innerHTML = "";
                    var el = document.createElement("option");
                    el.textContent = 'Επιλογή Τμήματος';
                    el.value = "";
                    el.hidden = true;
                    department.appendChild(el);

                    // console.log(selected_department_names);

                    for(var i = 0; i < selected_department_names.length; i++) {
                        var el = document.createElement("option");
                        el.textContent = selected_department_names[i];
                        el.value = selected_department_ids[i];
                        // console.log(el.textContent, el.value);
                        department.appendChild(el);
                    }

                } else {
                    console.log('foo');
                    department.innerHTML = "";

                    var el = document.createElement("option");
                    el.textContent = 'Επιλογή Τμήματος';
                    el.value = "";
                    el.hidden = true;
                    department.appendChild(el);

                    el2 = document.createElement("option");
                    el2.textContent = localStorage.getItem('s_department_name');
                    el2.value = localStorage.getItem('s_department');
                    // el.hidden = true;
                    department.appendChild(el2);
                }
                

            }

            function getLocalStoredValue(){

                let value = localStorage.getItem('s_title');
                if (value != null) {
                    title.value = value;
                    document.getElementById("clean_title").style.display = "inline-block";
                }

                value = localStorage.getItem('s_company');
                if (value != null){
                    company.value = value;
                    document.getElementById("clean_company").style.display = "inline-block";
                }

                value = localStorage.getItem('s_location');
                if (value != null){
                    location_inp.value = value;
                    document.getElementById("clean_location").style.display = "inline-block";
                }

                value = localStorage.getItem('s_type');
                if (value != null){
                    if ( value == "Πλήρης"){
                        type_p.checked= true; 
                        type_m.checked= false; 
                    } else {
                        type_m.checked= true; 
                        type_p.checked= false; 
                    }
                    document.getElementById("clean_type").style.display = "inline-block";
                }

                value = localStorage.getItem('s_duration');
                if (value != null){
                    if ( value == "3"){
                        duration3.checked= true; 
                        duration6.checked= false; 
                    } else {
                        duration6.checked= true; 
                        duration3.checked= false; 
                    }
                    document.getElementById("clean_duration").style.display = "inline-block";
                }

                value = localStorage.getItem('s_start');
                if (value != null){
                    start.value = value;
                    document.getElementById("clean_start").style.display = "inline-block";
                }

                value = localStorage.getItem('s_espa');
                if (value != null){
                    if ( value == "Με ΕΣΠΑ"){
                        espa_m.checked= true; 
                        espa_x.checked= false; 
                    } else {
                        espa_x.checked= true; 
                        espa_m.checked= false; 
                    }
                    document.getElementById("clean_espa").style.display = "inline-block";
                }
                
                value = localStorage.getItem('s_object');
                if (value != null){
                    object.value = value;
                    document.getElementById("clean_object").style.display = "inline-block";
                }

                value = localStorage.getItem('s_university');
                if (value != null){
                    university.value = value;
                    document.getElementById("clean_university").style.display = "inline-block";
                }

                value = localStorage.getItem('s_department');
                if (value != null){
                    getDepartments();
                   department.value = value; 
                   document.getElementById("clean_department").style.display = "inline-block";
                } 

            }

            function cleanFilters(){
                localStorage.removeItem('s_title');
                title.value="";
                document.getElementById("clean_title").style.display = "none";

                localStorage.removeItem('s_company');
                company.value="";
                document.getElementById("clean_company").style.display = "none";

                localStorage.removeItem('s_location');
                location_inp.value="";
                document.getElementById("clean_location").style.display = "none";

                localStorage.removeItem('s_type');
                type_p.checked = false;
                type_m.checked = false;
                document.getElementById("type_hid").checked = true;
                document.getElementById("clean_type").style.display = "none";

                localStorage.removeItem('s_duration');
                duration3.checked = false;
                duration6.checked = false;
                document.getElementById("duration_hid").checked = true;
                document.getElementById("clean_duration").style.display = "none";

                localStorage.removeItem('s_start');
                start.value = "<?php echo date("Y-m-d") ?>";
                document.getElementById("clean_start").style.display = "none";

                localStorage.removeItem('s_espa');
                espa_m.checked = false;
                espa_x.checked = false;
                document.getElementById("espa_hid").checked = true;
                document.getElementById("clean_espa").style.display = "none";

                localStorage.removeItem('s_object');
                object.value="";
                document.getElementById("clean_object").style.display = "none";

                localStorage.removeItem('s_university');
                university.value="";
                document.getElementById("clean_university").style.display = "none";

                localStorage.removeItem('s_department');
                localStorage.removeItem('s_department_name');
                department.value="";
                document.getElementById("clean_department").style.display = "none";
                // location.reload();
            }

            function cleanTitle(){
                localStorage.removeItem('s_title');
                title.value="";
                document.getElementById("clean_title").style.display = "none";
            }

            function cleanCompany(){
                localStorage.removeItem('s_company');
                company.value="";
                document.getElementById("clean_company").style.display = "none";
            }

            function cleanLocation(){
                localStorage.removeItem('s_location');
                location_inp.value="";
                document.getElementById("clean_location").style.display = "none";
            }

            function cleanType(){
                localStorage.removeItem('s_type');
                type_p.checked = false;
                type_m.checked = false;
                document.getElementById("type_hid").checked = true;
                document.getElementById("clean_type").style.display = "none";
            }

            function cleanDuration(){
                localStorage.removeItem('s_duration');
                duration3.checked = false;
                duration6.checked = false;
                document.getElementById("duration_hid").checked = true;
                document.getElementById("clean_duration").style.display = "none";
            }

            function cleanEspa(){
                localStorage.removeItem('s_espa');
                espa_m.checked = false;
                espa_x.checked = false;
                document.getElementById("espa_hid").checked = true;
                document.getElementById("clean_espa").style.display = "none";
            }

            function cleanStart(){
                localStorage.removeItem('s_start');
                start.value = "<?php echo date("Y-m-d") ?>";
                document.getElementById("clean_start").style.display = "none";
            }

            function cleanObject(){
                localStorage.removeItem('s_object');
                object.value = "";
                document.getElementById("clean_object").style.display = "none";
            }

            function cleanUniversity(){
                localStorage.removeItem('s_university');
                university.value = "";
                document.getElementById("clean_university").style.display = "none";
            }

            function cleanDepartment(){
                localStorage.removeItem('s_department');
                localStorage.removeItem('s_department_name');
                department.value = "";
                document.getElementById("clean_department").style.display = "none";
            }
        </script>

    
</html>  