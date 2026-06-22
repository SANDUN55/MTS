<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Module Timetable System (MTS) - Batch Calendar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />

    <link rel="stylesheet" href="../../web/pages/assets/scripts/calander/fullcalendar3.4.0.css" />
    <script src="../../web/pages/assets/scripts/calander/jquery3.2.1.min.js"></script>
    <script src="../../web/pages/assets/scripts/calander/jquery1.12.1-ui.min.js"></script>
    <script src="../../web/pages/assets/scripts/calander/moment2.18.1.min.js"></script>
    <script src="../../web/pages/assets/scripts/calander/fullcalendar3.4.0.min.js"></script>
    <script src="../../web/pages/assets/scripts/calander/bootstrap3.4.1.min.js"></script>

    <link href="../../web/pages/main.css" rel="stylesheet">
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow">
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                        <span class="hamburger-box"><span class="hamburger-inner"></span></span>
                    </button>
                </div>
            </div>

            <div class="app-header__mobile-menu">
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                    <span class="hamburger-box"><span class="hamburger-inner"></span></span>
                </button>
            </div>

            <div class="app-header__content">
                <div class="app-header-left">
                    <?php
                    include 'env/database.php';

                    $batch = '';
                    if (isset($_GET['bt'])) {
                        $decoded = json_decode(base64_decode($_GET['bt']), true);
                        if (is_string($decoded)) {
                            $parts = preg_split('/[\s-]+/', trim($decoded));
                            $batch = $parts[0] ?? $decoded;
                        } elseif (is_array($decoded)) {
                            $batch = $decoded['b_no'] ?? $decoded[0] ?? '';
                        }
                    }
                    ?>
                    <h2 align="center">Batch <?php echo htmlspecialchars($batch ?: 'Unknown'); ?></h2>
                </div>

                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-right header-user-info ml-3">
                                    <button type="button" class="btn-shadow btn btn-primary">
                                        <a href="../index.php"><i class="fa text-white fa-home"></i></a>
                                    </button>
                                    <button type="button" class="btn-shadow btn btn-success">
                                        <a href="../../web/login.php"><i class="fa text-white fa-sign-in-alt"></i></a>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-main">
            <div class="app-sidebar sidebar-shadow">
                <div class="scrollbar-sidebar">
                    <img src="../sidenav-logo.png" style="margin-top:-10px;" />
                    <div class="app-sidebar__inner">
                        <ul class="vertical-nav-menu">
                            <li class="app-sidebar__heading">
                                <a href="../index.php">HOME</a>
                            </li>
                            <?php
                            $sql = "SELECT b_no, batchmodule.m_code, CONCAT(m.m_name, ' ', m.m_phase) AS modnm,
                                    CONCAT(b_no, '-', m.m_code, ' - ', m.m_name, ' ', m.m_phase) as val
                                    FROM batchmodule
                                    JOIN module m ON m.m_code = batchmodule.m_code
                                    WHERE en_dt >= CURDATE() AND ttprogress = 3 
                                    ORDER BY b_no, modnm DESC";

                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_array($result)) {
                                $urlVal = base64_encode(json_encode($row['val']));
                            ?>
                                <li class="app-sidebar__heading">
                                    <a href="module-calendar.php?bt=<?php echo $urlVal; ?>" 
                                       title="Batch <?php echo $row['b_no']; ?>">
                                        BATCH <?php echo $row['b_no'] . ' : ' . $row['m_code']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div id='mcalendar'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="app-footer__inner">
                            <div class="app-footer-right">
                                <p>&copy; <?php echo date('Y'); ?>, Health Data Science Unit (HDSU), Faculty of Medicine, University of Kelaniya. All Rights Reserved</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="main.js"></script>
    <script type="text/javascript" src="batchtimetable-calender.js" 
            data-batch="<?php echo htmlspecialchars($batch); ?>"></script>
</body>
</html>