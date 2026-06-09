<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true){
    {
        header('Location:../login.php');
        die();
    }
}else {
    ?>
    <?php include 'headtag.php'; ?>
    <body>
    <?php include 'header-top.php'; ?>
    <?php include 'layout.php'; ?>
    <div class="app-main">
        <?php include 'navbar-left.php'; ?>
        <div class="app-main__outer">
            <?php include 'home.php'; ?>
            <?php include 'footer.php'; ?>
        </div>
        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    </div>

    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    </body>
    </html>
    <?php
}
?>