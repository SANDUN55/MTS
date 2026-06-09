<?php
include 'database.php';

if(count($_POST)>0){
	if($_POST['type']==1){
		$userNm = trim($_POST['username']);
        $usrCt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as usrCt  FROM users  WHERE user_nm LIKE '$userNm'")) ;
        if($usrCt['usrCt']=='0') {
            $userCat = $_POST['selectUserCat'];
            $userPw1 = $_POST['userpwd'];
            $userPw = password_hash($userPw1, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` (`user_nm`, `user_cat`, `user_pw`) VALUES (?, ?, ?)";
            $stmtLog = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmtLog, 'sss', $userNm, $userCat, $userPw);
            if (mysqli_stmt_execute($stmtLog)) {

                echo json_encode(array("statusCode" => 200));
            } else {
                echo '<pre>' . mysqli_error($conn) . '</pre>';
            }
            mysqli_close($conn);
        }else {
            echo 'User Name exist !';
        }
	}

}
if(count($_POST)>0){
	if($_POST['type']==2){
		//username1=SuperAdmin&userpwd1=SuAd*453%2F4&type=2
        $userNm = trim($_POST['username1']);
        $userPw1 = $_POST['userpwd1'];
        $userPw = password_hash($userPw1, PASSWORD_DEFAULT);
		$sql = "UPDATE `users` SET `user_pw`='$userPw' WHERE user_nm LIKE '$userNm'";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==3){
		$id=$_POST['id'];
		$sql = "DELETE FROM `batch` WHERE b_no=$id ";
		if (mysqli_query($conn, $sql)) {
			echo $id;
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==4){
		$id=$_POST['id'];
		$sql = "UPDATE batch SET `batchstatus`=0 WHERE b_no=$id ";
		if (mysqli_query($conn, $sql)) {
			echo $id;
		}
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}

?>
