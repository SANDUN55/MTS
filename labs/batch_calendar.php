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

    <!-- FullCalendar & Dependencies -->
    <link rel="stylesheet" href="../web/pages/assets/scripts/calander/fullcalendar3.4.0.css" />
    <script src="../web/pages/assets/scripts/calander/jquery3.2.1.min.js"></script>
    <script src="../web/pages/assets/scripts/calander/jquery1.12.1-ui.min.js"></script>
    <script src="../web/pages/assets/scripts/calander/moment2.18.1.min.js"></script>
    <script src="../web/pages/assets/scripts/calander/fullcalendar3.4.0.min.js"></script>
    <script src="../web/pages/assets/scripts/calander/bootstrap3.4.1.min.js"></script>

    <link href="../web/pages/main.css" rel="stylesheet">

    <!-- ====================== CUSTOM STYLING ====================== -->
    <style>
        /* Lab Selector Styling */
        .custom-select {
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            padding: 5px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 600px;
        }

        .custom-select:focus {
            border-color: #8ac3ff;
            box-shadow: 0 0 0 0.2rem rgba(132, 183, 236, 0.25);
            outline: none;
        }

        /* Calendar Container */
        #mcalendar {
            max-width: 100%;
            margin: 20px auto 0;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }

        /* FullCalendar Improvements */
        .fc {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .fc-toolbar {
            padding: 15px 20px !important;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .fc-event {
            border-radius: 5px !important;
            padding: 4px 6px !important;
            font-size: 13.5px !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 2px 0;
        }

        /* Reserved Events - Red */
        .fc-event.reserved,
        .fc-event.booked {
            background-color: #f5bbc1 !important;
            border-color: #e4a7ad !important;
            color: black !important;
            font-weight: 600;
        }

        .fc-event:hover {
            opacity: 0.95;
            transform: scale(1.02);
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .fc-time {
            font-weight: bold;
        }

        .fc-title {
            white-space: normal;
            line-height: 1.3;
        }
        .control-label {
            font-size: 18px;
            color: #333;
            padding-right: 10px;
        }

     .calendar-header span {
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding-left: 15px;
            padding-top: 20px;
             color: #141313a9;
             align-items: center;
        }
    </style>
    <!-- ======================================================== -->

</head>
<body>
    <div class="app-container app-theme-white ">
        <div class="app-main">
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
                            
                            <div class="main-card mb-3 card">
                                <!-- Card Header -->
                                <div class="calendar-header">
                                    <i class="pe-7s-calendar"></i>
                                    <span>Laboratory Reservation Calendar</span>
                                </div>
                                
                                <div class="card-body">
                                    
                                    <!-- Lab Selection -->
                                    <div class="form-group mb-4">
                                        <label class="control-label " for="lab">
                                            Laboratory
                                        </label>
                                        <select name="lab" id="lab" class="custom-select">
                                            <option value="0">—— Select Laboratory ——</option>
                                            <?php
                                            include 'database.php';
                                            $selectedLab = $_REQUEST['lab'] ?? '0';
                                            $sql_lab = "SELECT lab_code, lab_nm, lab_id, dep_nm 
                                                        FROM lab 
                                                        JOIN dep ON lab_dep = dep_code 
                                                        WHERE lab_status = 1 
                                                        ORDER BY dep_nm, lab_nm";
                                            $result = mysqli_query($labconn, $sql_lab);
                                            while($row_lab = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?php echo $row_lab['lab_code'];?>" 
                                                        <?php if($row_lab['lab_code'] == $selectedLab) echo "selected"; ?>>
                                                    <?php echo $row_lab['dep_nm'] . ' - ' . $row_lab['lab_nm'] . ' (' . $row_lab['lab_id'] . ')'; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Calendar -->
                                    <div id='mcalendar'></div>

                                </div> <!-- /.card-body -->
                            </div> <!-- /.main-card -->

                        </div>
                    </div>
                </div>

                <!-- Initialize Calendar Script -->
                <script>
                    $(document).ready(function() {
                        var selectedLab = $("#lab").val();
                        if (selectedLab && selectedLab !== "0") {
                            $("#lab").trigger('change');
                        }
                    });
                </script>

                <!-- Footer -->
                <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="app-footer__inner">
                            <div class="app-footer-left"></div>
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