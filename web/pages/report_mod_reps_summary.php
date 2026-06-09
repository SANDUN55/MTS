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
    </script>
    <script src="assets/scripts/add_class.js"></script>
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
            <li class="active breadcrumb-item" aria-current="page">Module Reps Summary</li>
        </ol>
    </nav>
    <div class="main-card mb-3 card">
        <div class="card-body"><h5 class="card-title">Module Reps Comments Summary</h5>
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
                        <input type="reset" class="btn btn-secondary"   value="RESET">
                        <input type="submit" class="btn btn-primary" name="btnDisplay" value="DISPLAY REPORT">
                    </div>
                    <div class="col-md-8" height="100%"  >
                        <p></p>
                        <div class="col" id="displayTT" height="100%" >

                            <?php
                            if(isset($_POST['btnDisplay'])){
                           // print_r($_POST);
                            $bno = $_POST['selectBatch'];
                            $module = $_POST['selectModule'];
                            $sql ="SELECT tp.activity, ac.a_name,  tp.staff, CONCAT(t_nm, ' ', firstname, ' ', surname) AS stnm, tp.class_topic,  tp. dep_code,
                                sc.class_status, sc.rep1_comment, sc.rep2_comment, sc.rep3_comment, sc.rep4_comment, sc.class_start, TIME(sc.class_end) as st
                                FROM `classtopics` tp
                                JOIN classschedules sc ON sc.class_topic_id = tp.topic_id
                                JOIN staff st ON tp.staff = st.st_id
                                LEFT JOIN activity ac on tp.activity = ac.a_id 
                                WHERE tp.b_no = $bno AND tp.m_code='$module' ORDER BY sc.class_start;";
                            $res = mysqli_query($conn, $sql);

                            ?>
                            <div id="printDoc">
                            <h3><?php echo $bno . ' - ' .$module;  ?></h3>
                           <b><em> NC :Not-Comment,  Y :Conducted,  N :Not-Conducted,  P :Postponed</em> </b>
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th  align="center">LECTURER</th>
                                    <th  align="center">SESSION</th>
                                    <th  align="center">CLASS STATUS</th>
                                    <th  align="center">REP 1</th>
                                    <th  align="center">REP 2</th>
                                    <th  align="center">REP 3</th>
                                    <th  align="center">REP 4</th>
                                </tr>
                                </thead>
                                <tbody>
                            <?php
                                function getCode($val){
                                    switch ($val){
                                        case '0' : echo "<td>NC</td>";
                                                    break;
                                        case '1' : echo "<td>Y</td>";
                                            break;
                                        case '2' : echo "<td>N</td>";
                                            break;
                                        case '3' : echo "<td>P</td>";
                                            break;

                                    }
                                }

                               $tm = strtotime(0);
                                while($row = mysqli_fetch_assoc($res)){
                                    $tm = $tm + $row["TimeDif"];
                                    $rep1 = $row["rep1_comment"];$rep2 = $row["rep2_comment"];$rep3 = $row["rep3_comment"];$rep4 = $row["rep4_comment"];
                                    $clst = $row["class_status"];
                                    if($clst== '0') $stext =  'cancel';
                                    else $stext = '';
                                    echo "<tr>";
                                    echo "<td>" . $row["stnm"] ."</td>";
                                    echo "<td>" . $row["class_start"]  . ' - ' .  $row["st"] . '<br>' . $row["a_name"] . ':' .  $row["class_topic"] ."</td>";
                                    echo "<td>" . $stext  ."</td>";
                                    echo getCode($rep1);
                                    echo getCode($rep2);
                                    echo getCode($rep3);
                                    echo getCode($rep4);
                                    echo "</tr>";
                                }
                            }
                            ?>
                                </tbody>
                            </table>
                        </div>
                            <input type='button' id='btn' value='PRINT REPORT' onclick="document.title='REPS REPORT <?php echo $bno . ' - ' .$module; ?>';printContent('printDoc');" >
                            <p></p>
                    </div>
                </div>

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