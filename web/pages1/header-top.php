<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <div class="app-header header-shadow bg-alternate header-text-light">

        <div class="app-header__logo">
            <div class="logo-src"></div>
            <div class="header__pane ml-auto">
                <div>
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="app-header__mobile-menu">
            <div>
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                </button>
            </div>
        </div>
        <div class="app-header__logo">
            <div class="logo-src"></div>
            <div class="header__pane ml-auto"></div>
        </div>
        <div class="app-header__mobile-menu"></div>
        <div class="app-header__menu"></div>
        <div class="app-header__content">
            <div class="app-header-left"><!--
                                   <div class="search-wrapper">
                        <div class="input-holder">
                            <input type="text" class="search-input" placeholder="Type to search">
                            <button class="search-icon"><span></span></button>
                        </div>
                        <button class="close"></button>
                    </div>
                                        -->
                <div class="ml-3"><img src="assets/images/header.png" alt=
                    "#########"> <!--       <div class="widget-heading">
                                        Module Timetable System
                                    </div>
                                    <div class="widget-subheading">
                                        Faculty of Medicine, University of Kelaniya
                                    </div>--></div>
                <!--
                                    <ul class="header-menu nav">
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                <i class="nav-link-icon fa fa-database"> </i>
                                                Statistics
                                            </a>
                                        </li>
                                        <li class="btn-group nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                <i class="nav-link-icon fa fa-edit"></i>
                                                Projects
                                            </a>
                                        </li>
                                        <li class="dropdown nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                <i class="nav-link-icon fa fa-cog"></i>
                                                Settings
                                            </a>
                                        </li>
                                    </ul>  --></div>
            <div class="app-header-right">
                <div class="header-btn-lg pr-0">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left"><!--     <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <img width="42" class="rounded-circle" src="assets/images/avatars/1.jpg" alt="">
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <button type="button" tabindex="0" class="dropdown-item">User Account</button>
                                            <button type="button" tabindex="0" class="dropdown-item">Settings</button>
                                            <h6 tabindex="-1" class="dropdown-header">Header</h6>
                                            <button type="button" tabindex="0" class="dropdown-item">Actions</button>
                                            <div tabindex="-1" class="dropdown-divider"></div>
                                            <button type="button" tabindex="0" class="dropdown-item">Dividers</button>
                                        </div>
                                    </div>--></div>
                            <div class="widget-content-left ml-3 header-user-info">
                                <div class="widget-heading"><?php echo $_SESSION["userMtsFom"]. ', cat: '. $_SESSION["cat"]; ?></div>
                                <div class="widget-subheading">02/Oct/2020 05:30 </div>
                            </div>
                            <!--
                                                            <div class="widget-content-right header-user-info ml-3">
                                                                <button type="button" class="btn-shadow p-1 btn btn-primary btn-sm show-toastr-example">
                                                                    <i class="fa text-white fa-calendar pr-1 pl-1"></i>
                                                                </button>
                                                            </div>-->
                            <div class="widget-content-left">
                                <div class="btn-group"><a data-toggle="dropdown" aria-haspopup=
                                    "true" aria-expanded="false" class="p-0 btn">
                                        <!--  <img width="42" class="rounded-circle" src="assets/images/avatars/1.jpg" alt="">--></a>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class=
                                    "dropdown-menu dropdown-menu-right"><button type="button" tabindex=
                                        "0" class="dropdown-item">User Account</button> <button type=
                                                                                                "button" tabindex="0" class="dropdown-item">Settings</button>
                                        <h6 tabindex="-1" class="dropdown-header">Header</h6>
                                        <button type="button" tabindex="0" class=
                                        "dropdown-item">Actions</button>
                                        <div tabindex="-1" class="dropdown-divider"></div>
                                        <button type="button" tabindex="0" class="dropdown-item"><a href=
                                                                                                    "../logout.php">Logout</a></button> <a href=
                                                                                                                                           "../logout.php"></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>