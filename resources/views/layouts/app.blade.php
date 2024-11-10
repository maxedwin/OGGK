<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Soluciones OGGK</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset('/assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/colors.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('/assets/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('/assets/js/core/app.js') }}"></script>
    <!-- /theme JS files -->

</head>

<body class="sidebar-xs has-detached-left">

    <!-- Main navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-header">
            <!-- <a class="navbar-brand" href="index.html"><img src="assets/images/logo_light.png" alt=""></a> -->
            <a class="navbar-brand" href="index.html"><img src="" alt=""></a>

            <ul class="nav navbar-nav pull-right visible-xs-block">
                <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
                <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
                <li><a class="sidebar-mobile-detached-toggle"><i class="icon-grid7"></i></a></li>
            </ul>
        </div>

        <div class="navbar-collapse collapse" id="navbar-mobile">
            <ul class="nav navbar-nav">
                <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
                <li><a class="sidebar-control sidebar-detached-hide hidden-xs"><i class="icon-drag-left"></i></a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Text link</a></li>

                <li>
                    <a href="#">
                        <i class="icon-cog3"></i>
                        <span class="visible-xs-inline-block position-right">Icon link</span>
                    </a>                        
                </li>

                <li class="dropdown dropdown-user">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <img src="assets/images/image.png" alt="">
                        <span>Arequipa</span>
                        <i class="caret"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#"><i class="icon-user-plus"></i> My profile</a></li>
                        <li><a href="#"><i class="icon-coins"></i> My balance</a></li>
                        <li><a href="#"><span class="badge badge-warning pull-right">58</span> <i class="icon-comment-discussion"></i> Messages</a></li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="icon-cog5"></i> Account settings</a></li>
                        <li><a href="#"><i class="icon-switch2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- /main navbar -->


    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main sidebar -->
            <div class="sidebar sidebar-main">
                <div class="sidebar-content">

                    <!-- User menu -->
                    <div class="sidebar-user">
                        <div class="category-content">
                            <div class="media">
                                <a href="#" class="media-left"><img src="assets/images/image.png" class="img-circle img-sm" alt=""></a>
                                <div class="media-body">
                                    <span class="media-heading text-semibold">Arequipa</span>
                                    <div class="text-size-mini text-muted">
                                        <i class="icon-pin text-size-small"></i> &nbsp;Arequipa, PE
                                    </div>
                                </div>

                                <div class="media-right media-middle">
                                    <ul class="icons-list">
                                        <li>
                                            <a href="#"><i class="icon-cog3"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /user menu -->


                    <!-- Main navigation -->
                    <div class="sidebar-category sidebar-category-visible">
                        <div class="category-content no-padding">
                            <ul class="navigation navigation-main navigation-accordion">

                                <!-- Main -->
                                <li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>
                                <li><a href="../index.html"><i class="icon-home4"></i> <span>Página Principal</span></a></li>
                                <li>
                                    <a href="#"><i class="icon-stack"></i> <span>Starter kit</span></a>
                                    <ul>
                                        <li><a href="horizontal_nav.html">Horizontal navigation</a></li>
                                        <li><a href="1_col.html">1 column</a></li>
                                        <li><a href="2_col.html">2 columns</a></li>
                                        <li>
                                            <a href="#">3 columns</a>
                                            <ul>
                                                <li><a href="3_col_dual.html">Dual sidebars</a></li>
                                                <li><a href="3_col_double.html">Double sidebars</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="4_col.html">4 columns</a></li>
                                        <li>
                                            <a href="#">Detached layout</a>
                                            <ul>
                                                <li class="active"><a href="detached_left.html">Left sidebar</a></li>
                                                <li><a href="detached_right.html">Right sidebar</a></li>
                                                <li><a href="detached_sticky.html">Sticky sidebar</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="layout_boxed.html">Boxed layout</a></li>
                                        <li class="navigation-divider"></li>
                                        <li><a href="layout_navbar_fixed_main.html">Fixed top navbar</a></li>
                                        <li><a href="layout_navbar_fixed_secondary.html">Fixed secondary navbar</a></li>
                                        <li><a href="layout_navbar_fixed_both.html">Both navbars fixed</a></li>
                                        <li><a href="layout_fixed.html">Fixed layout</a></li>
                                    </ul>
                                </li>
                                <li><a href="../changelog.html"><i class="icon-list-unordered"></i> <span>Changelog</span></a></li>
                                <!-- /main -->

                            </ul>
                        </div>
                    </div>
                    <!-- /main navigation -->

                </div>
            </div>
            <!-- /main sidebar -->


            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Page header -->
                <div class="page-header page-header-default">
                    <div class="page-header-content">
                        <div class="page-title">
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Starters</span> - Left Detached</h4>
                        </div>

                        <div class="heading-elements">
                            <a href="#" class="btn btn-labeled btn-labeled-right bg-blue heading-btn">Button <b><i class="icon-menu7"></i></b></a>
                        </div>
                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="index.html"><i class="icon-home2 position-left"></i> Página Principal</a></li>
                            <li><a href="detached_left.html">Starters</a></li>
                            <li class="active">Left detached</li>
                        </ul>

                        <ul class="breadcrumb-elements">
                            <li><a href="#"><i class="icon-comment-discussion position-left"></i> Link</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-gear position-left"></i>
                                    Dropdown
                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="#"><i class="icon-user-lock"></i> Account security</a></li>
                                    <li><a href="#"><i class="icon-statistics"></i> Analytics</a></li>
                                    <li><a href="#"><i class="icon-accessibility"></i> Accessibility</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#"><i class="icon-gear"></i> All settings</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /page header -->


                <!-- Content area -->
                <div class="content">

                    <!-- Detached sidebar -->
                    <div class="sidebar-detached">
                        <div class="sidebar sidebar-default">
                            <div class="sidebar-content">

                                <!-- Sidebar search -->
                                <div class="sidebar-category">
                                    <div class="category-title">
                                        <span>Search</span>
                                        <ul class="icons-list">
                                            <li><a href="#" data-action="collapse"></a></li>
                                        </ul>
                                    </div>

                                    <div class="category-content">
                                        <form action="#">
                                            <div class="has-feedback has-feedback-left">
                                                <input type="search" class="form-control" placeholder="Search">
                                                <div class="form-control-feedback">
                                                    <i class="icon-search4 text-size-base text-muted"></i>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- /sidebar search -->


                                <!-- Sub navigation -->
                                <div class="sidebar-category">
                                    <div class="category-title">
                                        <span>Navigation</span>
                                        <ul class="icons-list">
                                            <li><a href="#" data-action="collapse"></a></li>
                                        </ul>
                                    </div>

                                    <div class="category-content no-padding">
                                        <ul class="navigation navigation-alt navigation-accordion">
                                            <li class="navigation-header">Category title</li>
                                            <li><a href="#"><i class="icon-googleplus5"></i> Link</a></li>
                                            <li><a href="#"><i class="icon-googleplus5"></i> Another link</a></li>
                                            <li><a href="#"><i class="icon-portfolio"></i> Link with label <span class="label bg-success-400">Online</span></a></li>
                                            <li class="navigation-divider"></li>
                                            <li>
                                                <a href="#"><i class="icon-cog3"></i> Menu levels</a>
                                                <ul>
                                                    <li><a href="#"><i class="icon-IE"></i> Second level</a></li>
                                                    <li>
                                                        <a href="#"><i class="icon-firefox"></i> Second level with child</a>
                                                        <ul>
                                                            <li><a href="#"><i class="icon-android"></i> Third level</a></li>
                                                            <li>
                                                                <a href="#"><i class="icon-apple2"></i> Third level with child</a>
                                                                <ul>
                                                                    <li><a href="#"><i class="icon-html5"></i> Fourth level</a></li>
                                                                    <li><a href="#"><i class="icon-css3"></i> Fourth level</a></li>
                                                                </ul>
                                                            </li>
                                                            <li><a href="#"><i class="icon-windows"></i> Third level</a></li>
                                                        </ul>
                                                    </li>
                                                    <li><a href="#"><i class="icon-chrome"></i> Second level</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /sub navigation -->


                                <!-- Form sample -->
                                <div class="sidebar-category">
                                    <div class="category-title">
                                        <span>Form example</span>
                                        <ul class="icons-list">
                                            <li><a href="#" data-action="collapse"></a></li>
                                        </ul>
                                    </div>

                                    <form action="#" class="category-content">
                                        <div class="form-group">
                                            <label>Your name:</label>
                                            <input type="text" class="form-control" placeholder="Username">
                                        </div>

                                        <div class="form-group">
                                            <label>Your password:</label>
                                            <input type="password" class="form-control" placeholder="Password">
                                        </div>

                                        <div class="form-group">
                                            <label>Your message:</label>
                                            <textarea rows="3" cols="3" class="form-control" placeholder="Default textarea"></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-6">
                                                <button type="reset" class="btn btn-danger btn-block">Reset</button>
                                            </div>
                                            <div class="col-xs-6">
                                                <button type="submit" class="btn btn-info btn-block">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /form sample -->

                            </div>
                        </div>
                    </div>
                    <!-- /detached sidebar -->


                    <!-- Detached content -->
                    <div class="container-detached">
                        <div class="content-detached">

                                @if (!Auth::guest())
                                    @yield('contenido')
                                @endif

                        </div>
                    </div>
                    <!-- /detached content -->


                    <!-- Footer -->
                    <div class="footer text-muted">
                        &copy; 2015. <a href="#">Limitless Web App Kit</a> by <a href="http://themeforest.net/user/Kopyov" target="_blank">Eugene Kopyov</a>
                    </div>
                    <!-- /footer -->

                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->

</body>
</html>
