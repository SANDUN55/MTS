<?php
session_start();
if(!empty($_SESSION["userMtsFom"]) && $_SESSION["userLoggedIn"] === true)
{
   echo "<script> alert('Please logout from the previous session');";
    header('Location:pages/index.php');
    die();
}else {
    $userName = $userPass = "";
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        extract($_POST);
        include 'pages/assets/scripts/backend/database.php';
       $recNo = '';
        $sqlog = "SELECT user_nm, user_cat, user_pw, user_auth   FROM users WHERE user_nm  = ? AND user_st = 1";
        $stmtLog = mysqli_prepare($conn, $sqlog);
        mysqli_stmt_bind_param($stmtLog, 's', $userName);
        mysqli_stmt_execute($stmtLog);
        mysqli_stmt_store_result($stmtLog);
        $recNo=mysqli_stmt_num_rows($stmtLog);
        if($recNo == 1){
            $dbunm = $dbucat = $dbupwd = $dbuauth = '';
            mysqli_stmt_bind_result($stmtLog, $dbunm, $dbucat, $dbupwd, $dbuauth);
            mysqli_stmt_fetch($stmtLog);
            // Check if the username is in the db
            if($dbuauth == 'm')
            {
                //system users
                echo "<script> alert('manual account ');</script>";
                if(password_verify($_POST['userPass'], $dbupwd))
                {
                    // Password is correct, so start a new session
                    //echo "<script> alert(' manual account Correct pwd ');</script>";
                    $_SESSION["userLoggedIn"] = true;
                    $_SESSION["userMtsFom"] = $dbunm;
                    $_SESSION["cat"] = $dbucat;

                    mysqli_stmt_free_result($stmtLog);
                    mysqli_stmt_close($stmtLog);
                    mysqli_close($conn);
                    header('Location:index.php');

                } else
                {
                    echo "<script> alert('Invalid User Login!');document.history(-1);</script>";
                    mysqli_stmt_free_result($stmtLog);
                    mysqli_stmt_close($stmtLog);
                    mysqli_close($conn);
                }
            }elseif ($dbuauth == 'l')
            {
                //MODULE CORDINATORS AND TIMETABLE MANAGERS
                echo "<script> alert('ldap account ');</script>";
                $_SESSION["userLoggedIn"] = true;
                $_SESSION["userMtsFom"] = $dbunm;
                $_SESSION["cat"] = $dbucat;
                header('Location:index.php');
                //check ldap pwd correct
                /*
                if(password_verify($userPass, $dbupwd))
                {
                    // Password is correct, so start a new session
                    echo "<script> alert(' ldap account Correct pwd ');</script>";
                    mysqli_stmt_free_result($stmtLog);
                    mysqli_stmt_close($stmtLog);
                    mysqli_close($conn);
                    //header("location: index.php");

                } else
                {
                    echo "<script> alert('ldap account INCORCCT  pwd ');</script>";
                }*/

            }
        }else {
            //TEACHERS
            echo "<script> alert('Check Staff Table ');</script>";
            $sqlog = "SELECT t_nm, firstname, surname,  username, st_cat    FROM staff WHERE username = ? AND st_cat =1";
            $stmtLog = mysqli_prepare($conn, $sqlog) or die (mysqli_error($conn));
            mysqli_stmt_bind_param($stmtLog, 's', $userName) or die (mysqli_error($conn));
            mysqli_stmt_execute($stmtLog);
            mysqli_stmt_store_result($stmtLog);
            $recNo=mysqli_stmt_num_rows($stmtLog);
            echo $recNo;
            if($recNo == 1) {
                mysqli_stmt_bind_result($stmtLog, $title, $fname, $surname, $usernm);
                mysqli_stmt_fetch($stmtLog);
                echo "<script> alert('Check LDAP pwd ');</script>";
                //$_SESSION["userLoggedIn"] = true;
                //$_SESSION["userMtsFom"] = $dbunm;
                //$_SESSION["cat"] = 6;
                mysqli_stmt_free_result($stmtLog);
                mysqli_stmt_close($stmtLog);
            }else{
                echo "<script> alert('You do not have permission to Login! Please contact  System Administrator');</script>";
            }
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
        <link href="pages/main.css" rel="stylesheet">
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
                height: 400px;
                width: 350px;
                margin-top: auto;
                margin-bottom: auto;
                background: #efe2ba; /*#57ba96;/*#fbeec1;/*f39c12*/
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
        <link rel="icon" href="pages/assets/images/favicon.png" sizes="32x32"/>
        <link rel="icon" href="pages/assets/images/favicon.png" sizes="192x192"/>
        <link rel="apple-touch-icon" href="pages/assets/images/favicon.png"/>
        <meta name="msapplication-TileImage" content="pages/assets/images/favicon.png"/>
    </head>
    <body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="pages/assets/images/login-logo.png" class="brand_logo" alt="Logo">

                    </div>
                </div>
                <div class="d-flex justify-content-center form_container">
                    <form action="" method="post">
                        <div align="center"><h2>Faculty of Medicine <br>University of Kelaniya</h2></div>
                        <br>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="userName" class="form-control input_user" placeholder="username"
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
                            <br>© 2020, CHIBE, Faculty of Medicine.<br> All Rights Reserved
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