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
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
 <?php include 'header-top.php'; ?>
        <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
 <?php  include 'assets/scripts/backend/select_val.php';?>
<div class="app-main">
<?php include 'navbar-left.php'; ?>
      <div class="app-main__outer">
            <div class="app-main__inner">
                       
         	<div class="main-card mb-3 card">
                <div class="card-body">
<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="registry_home.php">User Management</a></li>
            <li class="active breadcrumb-item" aria-current="page">Add User</li>
        </ol>
    </nav>

    <div class="main-card mb-3 card">
        <div class="card-header"><i class="header-icon lnr-license icon-gradient bg-plum-plate"> </i>User management
            <div class="btn-actions-pane-right">
                <div role="group" class="btn-group-sm nav btn-group">
                    <a data-toggle="tab" href="#tab-eg1-0" class="btn-shadow active btn btn-primary">| Register User | </a>
                    <a data-toggle="tab" href="#tab-eg1-1" class="btn-shadow  btn btn-primary">| Reset Password | </a>
                    <a data-toggle="tab" href="#tab-eg1-2" class="btn-shadow  btn btn-primary">| Disable User</a>

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab-eg1-0" role="tabpanel">
                    <h5 class="card-title">ADD USER</h5>
                    <form class="needs-validation" novalidate id="user_form">
                        <div class="form-row">
                            <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label>User Category</label>
                                        <?php loadUserCat();?>
                                    </div>
                                    <div class="position-relative form-group">
                                        <label>User Name</label>
                                        <input name="username" id="username" placeholder="user name" type="text" class="form-control" required>
                                        <div class="invalid-feedback">
                                            Please provide a valid user name.
                                        </div>
                                    </div>

                                    <div class="position-relative form-group">
                                        <label>Password</label>
                                        <input name="userpwd" id="userpwd"  type="password" class="form-control" required>
                                        <div class="invalid-feedback">
                                            Please provide a valid password.
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <input type="hidden" value="1" name="type">
                        <input type="reset" class="btn btn-default"  value="Reset">
                        <button class="btn btn-primary" id="btn-add">SAVE</button>
                    </form>
                </div>
                <div class="tab-pane" id="tab-eg1-1" role="tabpanel">
                    <h5 class="card-title">RESET PASSWORD</h5>
                    <form class="needs-validation" novalidate id="update_form">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label>User Name</label>
                                    <input name="username1" id="username1" placeholder="user name" type="text" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Please provide a valid user name.
                                    </div>
                                </div>

                                <div class="position-relative form-group">
                                    <label>Password</label>
                                    <input name="userpwd1" id="userpwd1" type="password" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Please provide a valid password.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="2" name="type">
                        <input type="reset" class="btn btn-default"  value="Reset">
                        <button type="button" class="btn btn-alternate" id="update">Update</button>
                    </form>
                </div>
                <div class="tab-pane" id="tab-eg1-2" role="tabpanel">
                    <h5 class="card-title">DISABLE USER</h5>
                    <form class="needs-validation" novalidate id="update_form">
                        <div class="form-row">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th  align="center">USER NAME</th>
                                    <th  align="center">CATEGORY</th>
                                    <th  align="center">STATUS</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $result = mysqli_query($conn,"SELECT user_nm, user_cat, user_st FROM USERS WHERE userid >1 AND USER_ST=1 ORDER BY user_cat, user_nm;");
                                $i=1;
                                while($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr id="<?php echo $row["b_no"]; ?>">
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row["user_nm"]; ?></td>
                                        <td><?php echo $row["user_cat"]; ?></td>
                                        <td><?php echo $row["user_st"]; ?></td>
                                        <td align="center">
                                            <input type="checkbox" checked data-toggle="toggle" data-on="Ready" data-off="Not Ready" data-onstyle="success" data-offstyle="danger">
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" value="2" name="type">
                        <input type="reset" class="btn btn-default"  value="Reset">
                        <button type="button" class="btn btn-warning" id="update">Disable</button>
                    </form>
                </div>

            </div>
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
<script src="assets/scripts/user.js"></script>
</html>
<?php } ?>