<?php
$rand = substr(uniqid('', true), -6);
$userPw = password_hash($rand, PASSWORD_DEFAULT);

$sql2= "INSERT IGNORE INTO `users` (`user_nm`, `user_cat`, `user_pw`, `user_auth`) SELECT username, '4' as cat, '$userPw' as usrpwd, 'l' as auth FROM staff WHERE st_id IN ($c1, $c2)";
echo $sql2;


/*
include 'pages/assets/scripts/backend/database.php';
$sqlog = "SELECT user_nm, user_cat, user_pw, user_auth   FROM users WHERE user_nm  = 'gayathri'";
$result = mysqli_query($conn,"SELECT user_nm, user_cat, user_pw, user_auth   FROM users WHERE user_nm  = 'gayathri'");

while($row = mysqli_fetch_array($result)) {
    var_dump($row['user_pw']);
   $pwd=$row['user_pw'];
    $hashed = password_hash('asd', PASSWORD_DEFAULT);
    var_dump($hashed);
    $password = 'asd';
    $pwd='asd';
    if (password_verify($pwd, $hashed)) {
        echo 'success';
    } else {
        echo 'fail';
    }
}*/
?>