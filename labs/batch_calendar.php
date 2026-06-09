<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Module Timetable System (MTS), Faculty of Medicine, University of Kelaniya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="author" content="W GYATAHRI H">
    <meta name="msapplication-tap-highlight" content="no">
    <!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->

    <link rel="stylesheet" href="../web/pages/assets/scripts/calander/fullcalendar3.4.0.css" />
    <script src="../web/pages/assets/scripts/calander/jquery3.2.1.min.js"></script>
    <script src="../web/pages/assets/scripts/calander/jquery1.12.1-ui.min.js"></script>
    <script src="../web/pages/assets/scripts/calander/moment2.18.1.min.js"></script>
    <script src="../web/pages/assets/scripts/calander/fullcalendar3.4.0.min.js"></script>
    <script src="../web/pages/assets/scripts/calander/bootstrap3.4.1.min.js"></script>

<link href="../web/pages/main.css" rel="stylesheet">

</head>
<body>
    <div class="app-container app-theme-white ">
         <div class="app-main">
             <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <select name="lab" id="lab" >
                                            <option value="0">Select...</option>
                                            <?php
                                            include 'database.php';
                                            $selectedLab = $_REQUEST['lab'] ?? '0';
                                            $sql_lab="SELECT lab_code,lab_nm,lab_id,dep_nm FROM lab JOIN dep ON  lab_dep=dep_code WHERE lab_status=1 ORDER BY dep_nm,lab_nm ";
                                            $result = mysqli_query($labconn, $sql_lab);
                                            while($row_lab =mysqli_fetch_array($result))
                                            {
                                                ?>
                                                <option value="<?php echo $row_lab['lab_code'];?>" <?php if($row_lab['lab_code']==$selectedLab) echo "selected";  ?>><?php echo $row_lab['dep_nm']. ' - '.$row_lab['lab_nm'].' ('.$row_lab['lab_id'].')'; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <div id='mcalendar'></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 <script>
                    // Initialize calendar if a lab is already selected (from URL or page load)
                    $(document).ready(function() {
                        var selectedLab = $("#lab").val();
                        if (selectedLab && selectedLab !== "0") {
                            $("#lab").trigger('change');
                        }
                    });
                 </script>
                    <div class="app-wrapper-footer">
                        <div class="app-footer">
                            <div class="app-footer__inner">
                                <div class="app-footer-left">
                                </div>
                                <div class="app-footer-right">
                                    <p>© 2020, CHIBE, Faculty of Medicine. University of Kelaniya. All Rights Reserved</p>
                                </div>
                            </div>
                        </div>
                    </div>
             </div>
        </div>
    </div>

    <script type="text/javascript" src="batchtimetable-calender.js"></script>



</body>
</html>
