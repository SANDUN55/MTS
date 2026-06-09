<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
<?php include 'headtag.php'; ?>
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
            <li class="breadcrumb-item"><a href="registry_home.php">Class Management</a></li>
            <li class="active breadcrumb-item" aria-current="page">Reschedule Class</li>
        </ol>
    </nav>
    <div class="main-card mb-3 card">
        <div class="card-body"><h5 class="card-title">RESCHEDULE CLASS DATE </h5>
            <form class="needs-validation" novalidate id="user_form">
                <div class="form-row" >
                    <div class="col-md-4" style="border: 5px;">
                        <fieldset class="border p-2">
                            <legend  class="w-auto"></legend>
                            <div class="form-group">
                                <label>Batch - Module</label>
                                     <select name="selectModule" id="selectModule" class="form-control" onchange="loadClass();">
                                         <option value="">select</option>
                                    <?php
                                    $user = $_SESSION["userMtsFom"];
                                    $result = mysqli_query($conn, "SELECT CONCAT( b_no, '-' , m.m_code ) as val, 
                                        CONCAT(b_no, ' - ', m.m_name, ' ', m.m_phase) as val2  
                                        FROM `batchmodule` 
                                        JOIN module m ON m.m_code = batchmodule.m_code
                                         WHERE (comp_on = '0000-00-00 00:00:00' OR comp_on IS NULL) AND (conf_on = '0000-00-00 00:00:00' OR conf_on IS NULL) 
                                         AND st_dt <> '0000-00-00 00:00:00' AND 	(en_dt <> '0000-00-00 00:00:00' OR en_dt IS NULL) 
                                         AND
                                        ( cordi IN (SELECT st_id FROM staff WHERE username='$user')
                                         OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user')
                                         OR ttmng1  IN (SELECT st_id FROM staff WHERE username='$user')
                                         OR ttmng2  IN (SELECT st_id FROM staff WHERE username='$user')
                                         OR ttmng3  IN (SELECT st_id FROM staff WHERE username='$user')
                                         OR ttmng4  IN (SELECT st_id FROM staff WHERE username='$user')
                                         OR ttmng5  IN (SELECT st_id FROM staff WHERE username='$user')
                                         OR ttmng6  IN (SELECT st_id FROM staff WHERE username='$user')) ORDER BY b_no DESC; ") or die(mysqli_error($conn));
                                    while($row = mysqli_fetch_array($result)) {
                                        $val = $row["val"];
                                        $val2 = $row["val2"]; ?>
                                        <option value="<?php echo $val; ?>"><?php echo $val2; ?></option>
                                    <?php }     ?>
    </select>
                            </div>
                            <script>
                                $('#selectModule').on('change', function() {
                                    var val = this.value;
                                    //alert(val);
                                    $.ajax({
                                        type: "POST",
                                        url: "assets/scripts/backend/get_val.php",
                                        data:'fid=' +  7  +  '&fval=' + val,
                                        success: function(data){
                                            $("#classList").html(data);
                                        }
                                    });
                                    var durl = "timetableDisplay.php?id=" + val;
                                    $("#displayTT #myIframe").attr('src', durl);
                                });
                            </script>
                            <div class="position-relative form-group">
                                <label>Class Dates</label>
                                <select name="classList" id="classList" required class="form-control"></select>
                            </div>
                            <div class="position-relative form-group">
                                <label>Reschedule Date</label>
                                <input type="date" id="date1" name="classDate"  class="form-control" required >
                                <div class="invalid-feedback">
                                    Please provide a valid date.
                                </div>
                            </div>
                          <!--  <div class="position-relative form-group">
                                <input type="checkbox" id="chkRes" name="chkRes"> <label>Reschedule All Class Accordingly</label>
                            </div>-->
                        </fieldset>
                    </div>
                    <div class="col-md-8" height="100%"  >
                        <p></p>
                        <div class="col" id="displayTT" height="100%" >
                            <iframe id="myIframe" src=""  scrolling="yes" height="350px" width="100%" frameborder="0" src = ></iframe>
                        </div>
                        <p></p>
                    </div>
                </div>
               <input type="hidden" value="6" name="type">
                <input type="reset" class="btn btn-secondary"  value="RESET">
                <button class="btn btn-primary" id="btn-res" >RESCHEDULE CLASS</button>
                <button class="btn btn-warning" id="btn-resDay" >RESCHEDULE DAY</button>
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