<?php
session_start();
//print_r($_SESSION);
if(!isset($_SESSION["staffMtsFom"]) || $_SESSION["staffLoggedIn"] !== true)
{
    header('Location:login.php');
    die();
}else {
?>
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
    <script src="../app/assets/jquery3.2.1.min.js"></script>
    <script src="../app/assets/jquery1.12.1-ui.min.js"></script>
    <script src="../app/assets/moment2.18.1.min.js"></script>
    <script src="../app/assets/bootstrap3.4.1.min.js"></script>
<link href="../web/pages/main.css" rel="stylesheet">
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Activity Hours</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333333;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .form-label {
            font-weight: bold;
            color: #555555;
        }
        .form-select,
        .form-control,
        .btn1 {
            /* width: 100%; */
            padding: 10px;
            font-size: 16px;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }
        .btn1 {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            cursor: pointer;
            /* left: 50%; */
        }
        .btn1:hover {
            background-color: #0056b3;
        }
        #result {
            margin-top: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #dddddd;
        }
        table th {
            background-color: #f2f2f2;
        }
        .heading{
            font-family: "Times New Roman", Times, serif;
            color:#ff0000;
        }
       
    </style>
</head>
<body>

<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">


        <div class="app-header header-shadow">
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>

            <div class="app-header__content">
             <div class="app-header-left">
             <img src="header.png" />
            </div>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-right header-user-info ml-3">
                                    <button type="button" class="btn-shadow btn btn-primary">
                                       <a href="index.php">  <i class="fa text-white fa-home"></i></a>
                                    </button>
                                    <button type="button" class="btn-shadow btn btn-danger">
                                        <a href="logout.php"> <i class="fa text-white fa-sign-out-alt"></i></a>
                                    </button>
                                    <?php
                                    $userID = $_SESSION["staffID"];            //                        echo $userID;
                                      ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



         <div class="app-main">
             <?php include 'navbar.php'; ?>
             <div class="app-main__outer">
                 <?php include 'ribbon.php'; ?>
                 <div class="container">
                 <div class="container">
                    <div class="heading">
                         <h1>Total Activity Hours</h1>
                    </div>
        <form action="" method="POST">
            
            <div class="col-md-6">
                <label for="year" class="form-label">Year:</label>
                <input type="number" id="year" name="year" class="form-control" placeholder="YYYY" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn1">Get Activity Hours</button>
            </div>
        </form>

        <!-- Results Section -->
        <?php if ( isset($_POST['year'])): ?>
        <div id="result">
            <h3> Year: <?php echo htmlspecialchars($_POST['year']); ?></h3>
            <table>
                <thead>
                    
                    <tr>
                        
                        <th>Activity</th>
                        <th>Hours</th>
                        <th>Total Hours</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include("database.php");
                    $lec_mail = "SELECT st_em  FROM staff where st_id = $userID";
                    $raw = $conn->query($lec_mail);
                    $lecturer_id = mysqli_fetch_assoc($raw);
                    $staff_email = $lecturer_id['st_em'];
                   
                    $year = $_POST['year'];

                    // SQL Query for activity hours
                    // $sql = "CALL mts.ch_lecHoursList($year.'01-01', $year.'12-31', $lecturer_id); ";
                    $year1 = $year . "-01-01";
                    $year2 = $year . "-12-31";
                    $sql = "CALL mts.ch_lecHours('$year1', '$year2', '$staff_email');";
                    
                    // Prepare and bind parameters
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $staff_email, $year);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    // Display Results
                    while ($row = $result->fetch_assoc()) {
                        

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['activity_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_hours']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['w_hours']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['first_class_start']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['last_class_end']) . "</td>";
                        echo "</tr>";
                    }

                    // Close the statement
                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        </div>
        </div>
     </div>
    </div>
        
                   
               
            

                     

                 <?php include 'footer.php'; ?>
             
       
<script type="text/javascript" src="../app/assets/main.js"></script>
</body>
</html>
<?php } ?>