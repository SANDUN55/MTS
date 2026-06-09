<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
<?php include 'headtag.php'; ?>

<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header"><?php include 'header-top.php'; ?>
<div class="app-main"> <?php include 'navbar-left.php'; ?>
    <div class="app-main__outer">
        <div class="app-main__inner">
         	<div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-3" style="text-align: center" >
                                <div class="card-shadow-success border mb-3 card card-body border-success">
                                    <h2><i class="pe-7s-ba"></i>
                                        <h5 class="card-title"><a href="registry_batch.php">BATCH  <br><br>
                                            <img src="assets/images/crud2.png" width="100%">
                                            </a>
                                        </h5>
                                    </h2>
                                </div>
                            </div>
                            <div class="col-sm-3" style="text-align: center" >
                                <div class="card-shadow-success border mb-3 card card-body border-success">
                                    <h2><i class="pe-7s-ba"></i>
                                        <h5 class="card-title"><a href="registry_phase.php">PHASE  <br><br>
                                                <img src="assets/images/crud2.png" width="100%">
                                            </a>
                                        </h5>
                                    </h2>
                                </div>
                            </div>
                            <div class="col-sm-3" style="text-align: center" >
                                <div class="card-shadow-success border mb-3 card card-body border-success">
                                    <h2><i class="pe-7s-ba"></i>
                                        <h5 class="card-title"><a href="registry_strand.php">STRAND  <br><br>
                                                <img src="assets/images/crud2.png" width="100%">
                                            </a>
                                        </h5>
                                    </h2>
                                </div>
                            </div>
                            <div class="col-sm-3" style="text-align: center" >
                                <div class="card-shadow-success border mb-3 card card-body border-success">
                                    <h2><i class="pe-7s-ba"></i>
                                        <h5 class="card-title"><a href="registry_module.php">MODULE  <br><br>
                                                <img src="assets/images/crud2.png" width="100%">
                                            </a>
                                        </h5>
                                    </h2>
                                </div>
                            </div>
                            <div class="col-sm-3" style="text-align: center" >
                                <div class="card-shadow-success border mb-3 card card-body border-success">
                                    <h2><i class="pe-7s-ba"></i>
                                        <h5 class="card-title"><a href="registry_holidays.php">HOLIDAYS  <br><br>
                                                <img src="assets/images/crud2.png" width="100%">
                                            </a>
                                        </h5>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>		
        </div> <?php include 'footer.php'; ?>

    </div>
</div>
</div>
<script type="text/javascript" src="assets/scripts/main.js"></script>
</body>

</html>
<?php } ?>