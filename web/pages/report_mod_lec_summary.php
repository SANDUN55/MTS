<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
    <?php include 'headtag.php'; ?>
    <?php  include 'assets/scripts/backend/select_val.php';?>
    <script>
        function printContent(el){
            var restorepage = document.body.innerHTML;
            var printcontent = document.getElementById(el).innerHTML;
            document.body.innerHTML = printcontent;
            window.print();
            document.body.innerHTML = restorepage;
        }
       /* function displayName() {
            var selVal = selectModule.options[selectModule.selectedIndex].text;
            //alert(selVal);
            document.getElementById("head1").innerHTML = selVal;
        }*/
    </script>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <?php include 'header-top.php'; ?>
<div class="app-main">
<?php include 'navbar-left.php'; ?>
    <div class="app-main__outer">
            <div class="app-main__inner">
         	<div class="main-card mb-3 card">
                <div class="card-body">
<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="registry_home.php">Reports</a></li>
            <li class="active breadcrumb-item" aria-current="page">Module Lecture Summary</li>
        </ol>
    </nav>
    <div class="main-card mb-3 card">
        <div class="card-body"><h5 class="card-title">Module Lecture Summary  -------------- TESTING</h5>
            <form class="needs-validation" novalidate id="user_form" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>"">
                <div class="form-row" >
                    <div class="col-md-4" style="border: 5px;">
                        <fieldset class="border p-2">
                            <legend  class="w-auto"></legend>
                            <div class="form-group">
                                <div class="position-relative form-group">
                                    <label>Batch</label>
                                    <?php loadBatch();?>
                                </div>
                                <div class="position-relative form-group">
                                    <label>Module</label>
                                    <?php loadModule();?>
                                </div>
                            </div>
                        </fieldset>
                        <br>
                        <script>

                        </script>
                     <!--   <input type="reset" class="btn btn-secondary"   value="RESET">-->
                        <input type="submit" class="btn btn-info" name="btnDisplay3" style="width: 100%" value="DISPLAY LECTURERS SUMMARY REPORT" ><br><br>
                        <input type="submit" class="btn btn-primary" name="btnDisplay" style="width: 100%" value="DISPLAY LECTURERS DETAIL REPORT"><br><br>
                        <input type="submit" class="btn btn-success" name="btnDisplay2" style="width: 100%" value="DISPLAY ACTIVITY REPORT"><br><br>
                    </div>
                    <div class="col-md-8" height="100%"  >
                        <div class="col" id="displayTT" height="100%" ><?php
    if(isset($_POST['btnDisplay3'])){
    //print_r($_POST);
    $bno = $_POST['selectBatch'];
    $module = $_POST['selectModule'];
    $sql = "SELECT tp.staff,tp.dep_code, dv.div_nm, CONCAT(t_nm, ' ', firstname, ' ', surname) AS stnm, tp.dep_code, SEC_TO_TIME(SUM(TIME_TO_SEC(class_end) - TIME_TO_SEC(class_start))) AS TimeDif
                                        FROM `classtopics` tp
                                        LEFT JOIN classschedules sc ON sc.class_topic_id = tp.topic_id
                                        LEFT JOIN staff st ON (tp.staff = st.st_id AND tp.staff <> tp.dep_code)
                                        LEFT JOIN divisions dv ON (tp.dep_code = dv.div_id AND tp.staff = tp.dep_code)
                                        WHERE sc.class_status = 1 AND tp.b_no = $bno AND tp.m_code='$module' 
                                        GROUP BY tp.staff
                                        ORDER BY stnm ";
    // echo $sql;
    $res = mysqli_query($conn, $sql);
    ?>
    <div id="printDoc">
        <h3 id="head1"><?php echo $bno . ' - ' .$module;  ?></h3>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th  align="center">STAFF NAME</th>
            <th  align="center">TOTAL TIME (H:M)</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while($row = mysqli_fetch_assoc($res)){
            echo "<tr>";
            echo "<td>"  . $row["stnm"] . $row["div_nm"] . "</td>";
            echo "<td align=\"right\">" . substr($row["TimeDif"],0,-3) ."</td>";
            echo "</tr>";
        } ?>
        </tbody>
    </table>
    </div>
        <input type='button' id='btn' value='PRINT REPORT' onclick="document.title='LECTURERS SUMMARY REPORT <?php echo $bno . ' - ' .$module; ?>';printContent('printDoc');" >
<?php } ?>

     <?php
                            if(isset($_POST['btnDisplay'])){
                            //print_r($_POST);
                            $bno = $_POST['selectBatch'];
                            $module = $_POST['selectModule'];
       /*SQL V1                     $sql ="SELECT tp.activity, ac.a_name,  tp.staff, CONCAT(t_nm, ' ', firstname, ' ', surname) AS stnm, tp.dep_code, TIME(SUM(TIMEDIFF(class_end, class_start))) AS TimeDif
                                        FROM `classtopics` tp
                                        JOIN classschedules sc ON sc.class_topic_id = tp.topic_id
                                        JOIN staff st ON tp.staff = st.st_id
                                        LEFT JOIN activity ac on tp.activity = ac.a_id 
                                        WHERE sc.class_status = 1 AND tp.b_no = $bno AND tp.m_code='$module' AND tp.staff<>tp.dep_code
                                        GROUP BY tp.activity, tp.staff ;";*/
                            $sql = "SELECT tp.activity, ac.a_name,  tp.staff,tp.dep_code, dv.div_nm, CONCAT(t_nm, ' ', firstname, ' ', surname) AS stnm, tp.dep_code, SEC_TO_TIME(SUM(TIME_TO_SEC(class_end) - TIME_TO_SEC(class_start))) AS TimeDif
                                        FROM `classtopics` tp
                                        LEFT JOIN classschedules sc ON sc.class_topic_id = tp.topic_id
                                        LEFT JOIN staff st ON (tp.staff = st.st_id AND tp.staff <> tp.dep_code)
                                        LEFT JOIN activity ac on tp.activity = ac.a_id 
                                        LEFT JOIN divisions dv ON (tp.dep_code = dv.div_id AND tp.staff = tp.dep_code)
                                        WHERE sc.class_status = 1 AND tp.b_no = $bno AND tp.m_code='$module' 
                                        GROUP BY tp.staff,  tp.activity;";
                           // echo $sql;
                            $res = mysqli_query($conn, $sql);
                            ?>
                             <div id="printDoc1">
                            <h3><?php echo $bno . ' - ' .$module;  ?></h3>
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th  align="center">LECTURER</th>
                                    <th  align="center">SESSION</th>
                                    <th  align="center">TOTAL TIME (H:M)</th>
                                </tr>
                                </thead>
                                <tbody>
                            <?php
                               //$tm = strtotime(0);
                            $tm = 0;
                                while($row = mysqli_fetch_assoc($res)){
                                   // $cltime = $row["TimeDif"];
                                   // $cltm = strtotime($cltime);
                                    $tm  += $row["TimeDif"];
                                  /*  if(!is_null($row["TimeDif"])){
                                        $displayTm = date('h:i', strtotime($row["TimeDif"]));
                                    }*/
                                    echo "<tr>";
                                    echo "<td>" . $row["stnm"] . $row["div_nm"] . "</td>";
                                    echo "<td>" . $row["a_name"] ."</td>";
                                    //echo "<td>" . date('h:i', $cltm) . " - " . substr($row["TimeDif"],0,-3) ."</td>";
                                    echo "<td>" . substr($row["TimeDif"],0,-3) ."</td>";
                                    echo "</tr>";
                                }
                            /*$hrs=floor($tm/(24)).'.'.($tm%60);
                                echo "<tr>";
                                echo "<td colspan='2'><b> TOTAL </b></td>";
                                echo "<td><hr>" . $tm. '-' . $hrs. '-'.date("H:i:", $tm) . "</td>";
                                echo "</tr>";*/ ?>
                                </tbody>
                            </table>
                            </div>
                     <input type='button' id='btn' value='PRINT REPORT' onclick="document.title='LECTURERS DETAIL REPORT <?php echo $bno . ' - ' .$module; ?>';printContent('printDoc1');" >
                         <?php
                            }
                            if(isset($_POST['btnDisplay2'])){
                                //print_r($_POST);
                                $bno = $_POST['selectBatch'];
                                $module = $_POST['selectModule'];
                                $sql = "SELECT tp.activity, ac.a_name,  SEC_TO_TIME(SUM(TIME_TO_SEC(class_end) - TIME_TO_SEC(class_start))) AS TimeDif
                                        FROM `classtopics` tp
                                        LEFT JOIN classschedules sc ON sc.class_topic_id = tp.topic_id
                                        LEFT JOIN activity ac on tp.activity = ac.a_id 
                                        WHERE sc.class_status = 1 AND tp.b_no = $bno AND tp.m_code='$module' 
                                        GROUP BY tp.activity;";
                               // echo $sql;
                                $res = mysqli_query($conn, $sql);
                                ?>
                            <div id="printDoc3">
                                <h3><?php echo $bno . ' - ' .$module;  ?></h3>
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th  align="center">ACTIVITY</th>
                                        <th  align="center">TOTAL TIME (H:M)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    while($row = mysqli_fetch_assoc($res)){
                                        echo "<tr>";
                                        echo "<td>" . $row["a_name"]. "</td>";
                                        echo "<td align=\"right\">" . substr($row["TimeDif"],0,-3) ."</td>";
                                        echo "</tr>";
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                                <input type='button' id='btn' value='PRINT REPORT' onclick="document.title='ACTIVITY REPORT <?php echo $bno . ' - ' .$module; ?>';printContent('printDoc3');" >
                            <?php } ?>
                        </div>
                        <p></p>
                    </div>
                </div>
              <!--  <input type="reset" class="btn btn-secondary"   value="RESET">
                <input type="submit" class="btn btn-primary" name="btnDisplay" value="DISPLAY REPORT">-->
            </form>
        </div>
    </div>
				</div>
			</div>		
                    </div>
                <?php include 'footer.php'; ?>
            </div>
        </div>
    </div>
<script type="text/javascript" src="assets/scripts/main.js"></script></body>
</html>
<?php } ?>