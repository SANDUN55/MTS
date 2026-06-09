<?php
//error_reporting(E_ALL);
session_start();
if(!empty($_SESSION["stMtsFom"]) && $_SESSION["stLoggedIn"] === true)
{
//    echo "<script> alert('Please logout from the previous session');";
    header('Location:index.php');
    die();
}else {
    
    $userName = $userPass = "";
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        ;
        extract($_POST);
        include 'database.php';
        $recNo = '';
        //TEACHERS
            // echo "<script> alert('Batch Representative Login ');</script>";
            $sqlog = "SELECT rep_id, st_no, b_no   FROM batchreps WHERE net_id = ? AND st_status =1";
            $stmtLog = mysqli_prepare($conn, $sqlog) or die (mysqli_error($conn));
            mysqli_stmt_bind_param($stmtLog, 's', $userName) or die (mysqli_error($conn));
            mysqli_stmt_execute($stmtLog);
            mysqli_stmt_store_result($stmtLog);
            $recNo=mysqli_stmt_num_rows($stmtLog);
           // echo $recNo;
            if($recNo == 1) {

                $dbrepid = $dbstno = $dbstnm = '';
                mysqli_stmt_bind_result($stmtLog, $dbrepid, $dbstno , $dbstnm );
                mysqli_stmt_fetch($stmtLog);
                //////////////////////////////////////////////////////////////////
                $_SESSION["stLoggedIn"] = true;
                $_SESSION["stMtsFom"] = $dbstno;
                $_SESSION["stbMtsFom"] = $dbstnm;
                $_SESSION["stID"] = $dbrepid;
                mysqli_stmt_free_result($stmtLog);
                mysqli_stmt_close($stmtLog);
                mysqli_close($conn);
                header('Location:index-reps.php');
                mysqli_stmt_free_result($stmtLog);
                mysqli_stmt_close($stmtLog);

                /////////////////////////////////////////////////////////////////
                $userName = $_POST['userName'];
                $userPass = $_POST['userPass'];
                

                    if (!empty($userName) && !empty($userPass)) {
                        
                        // Initialize LDAP authentication
                        require_once '../ldap_auth.php';
                        $ldapSrv = new ExpLdap("172.16.1.21", "OU=Staff,DC=Kln,DC=ac,DC=lk", "kln.ac.lk");
                       
                        
                        
                        $res = $ldapSrv->user_auth($userName, $userPass);
                        // print_r($res);
                        if ($res['loginStatus'] === true) {  
                            // echo "OKKKKKKKKKKKKKKKKKKKKKKK";

                            $_SESSION["stLoggedIn"] = true;
                            $_SESSION["stMtsFom"] = $dbstno;
                            $_SESSION["stbMtsFom"] = $dbstnm;
                            $_SESSION["stID"] = $dbrepid;
                            mysqli_stmt_free_result($stmtLog);
                            mysqli_stmt_close($stmtLog);
                            mysqli_close($conn);
                            header('Location:index-reps.php');
                            mysqli_stmt_free_result($stmtLog);
                            mysqli_stmt_close($stmtLog);
                            // Authentication successful, set session variables
                            // $_SESSION["staffLoggedIn"] = true;
                            // $_SESSION["staffMtsFom"] = $dbunm;
                            // $_SESSION["cat"] = 6;
                            // $_SESSION["staffID"] = $dbsid;
                
                            $logAction = "Login Success";
                            // writeLog($logAction);
                
                            // mysqli_stmt_free_result($stmtLog);
                            // mysqli_stmt_close($stmtLog);
                            // mysqli_close($conn);
                
                            // header('Location: index.php');
                            exit;

                        }}
                /////////////////////////////////////////////////////////////////
            }else{
                echo "<script> alert('Invalid Login! Please contact  System Administrator');</script>";
            }

    }
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Language" content="en">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>MTS, FoM, UoK</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"/>
        <meta name="author" content="W GYATAHRI H"/>
        <meta name="msapplication-tap-highlight" content="no">
        <link href="../web/pages/main.css" rel="stylesheet">
        <style>
            /* https://bootsnipp.com/snippets/3522X*/
            body,
            html {
                margin: 0;
                padding: 0;
                height: 100%;
                background: #60a3bc !important;
            }

            .user_card {
              /*  height: 400px;*/
                width: 350px;
                margin-top: auto;
                margin-bottom: auto;
                background: #F2D1C9; /*#57ba96;/*#fbeec1;/*f39c12*/
                position: relative;
                display: flex;
                justify-content: center;
                flex-direction: column;
                padding: 10px;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                -moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                border-radius: 5px;

            }

            .brand_logo_container {
                position: absolute;
                height: 170px;
                width: 170px;
                top: -75px;
                border-radius: 50%;
                background: #60a3bc;
                padding: 10px;
                text-align: center;
            }

            .brand_logo {
                height: 150px;
                width: 150px;
                border-radius: 50%;
                border: 2px solid white;
            }

            .form_container {
                margin-top: 100px;
            }

            .login_btn {
                width: 100%;
                background: #c0392b !important;
                color: white !important;
            }

            .login_btn:focus {
                box-shadow: none !important;
                outline: 0px !important;
            }

            .login_btn:hover {
                box-shadow: none !important;
                outline: 0px !important;
                background: #dc9922 !important;
            }

            .login_container {
                padding: 0 2rem;
            }

            .input-group-text {
                background: #c0392b !important;
                color: white !important;
                border: 0 !important;
                border-radius: 0.25rem 0 0 0.25rem !important;
            }

            .input_user,
            .input_pass:focus {
                box-shadow: none !important;
                outline: 0px !important;
            }
        </style>
        <link rel="icon" href="../web/pages/assets/images/favicon.png" sizes="32x32"/>
        <link rel="icon" href="../web/pages/assets/images/favicon.png" sizes="192x192"/>
        <link rel="apple-touch-icon" href="../web/pages/assets/images/favicon.png"/>
        <meta name="msapplication-TileImage" content="../web/pages/assets/images/favicon.png"/>
    </head>
    <body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="../web/pages/assets/images/login-logo.png" class="brand_logo" alt="Logo">

                    </div>
                </div>
                <div class="d-flex justify-content-center form_container">
                    <form action="" method="post">
                        <div align="center"><h4>Faculty of Medicine <br>University of Kelaniya</h4>
                            <h4><b>Module Timetable System</b></h4>
                            <h5><b>BATCH REPS PORTAL</b></h5>
                        </div>
                        <br>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="userName" class="form-control input_user" placeholder="Kelani Net Id"
                                   required>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="userPass" class="form-control input_pass"  required>
                        </div>
                        <div class="d-flex justify-content-center mt-3 login_container">
                            <button type="submit" name="button" class="btn login_btn">Login</button>
                        </div>

                        <div align="center">
                            <br>
                     <!--       <i class="text-secondary pe-7s-home"></i> <a href="../app/index.php">HOME</a> |  <i class="text-secondary pe-7s-users"></i> <a href="../app/index.php">ABOUT</a> -->
      <a href="../app/index.php"> <i class="fa text-primary fa-home"></i></a> 
|  <a href="../app/index.php"> <i class="fa text-primary fa-info-circle"></i></a>                       
 <br><p>&copy; <?php echo date('Y'); ?>, Health Data Science Unit (HDSU), <br>Faculty of Medicine. University of Kelaniya. <br> All Rights Reserved</p>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div>
    </div>
    </body>
    </html>
    <?php
}
?>
