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
    <body >
    <?php include 'header-top.php'; ?>
    <?php include 'layout.php'; ?>
    <div class="app-main">
        <?php include 'navbar-left.php'; ?>
        <div class="app-main__outer">
            <div class="app-main__inner">
                <iframe id="myIframe" src="../../labs/batch_calendar.php" height="100%"  width="100%" scrolling="yes" frameborder="0"></iframe>
            <?php include 'footer.php'; ?>
        </div>
    </div>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    </body>
    </html>
    <?php
}
?>