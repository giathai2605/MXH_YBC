<?php
$page  = 'dashboard';
$wo['all_pages'] = scandir('admin-panel/pages');
unset($wo['all_pages'][0]);
unset($wo['all_pages'][1]);
unset($wo['all_pages'][2]);
$pages = array(
    'general-settings',
    'dashboard',
    'site-settings',
    'dashboard',
    'site-features',
    'amazon-settings',
    'email-settings',
    'social-login',
    'video-settings',
    'manage-languages',
    'add-language',
    'edit-lang',
    'manage-users',
    'manage-stories',
    'manage-profile-fields',
    'add-new-profile-field',
    'edit-profile-field',
    'manage-verification-reqeusts',
    'payment-reqeuests',
    'affiliates-settings',
    'referrals-list',
    'pro-memebers',
    'pro-settings',
    'pro-payments',
    'payment-settings',
    'manage-pages',
    'manage-groups',
    'manage-posts',
    'manage-articles',
    'manage-events',
    'manage-forum-sections',
    'manage-forum-forums',
    'manage-forum-threads',
    'manage-forum-messages',
    'create-new-section',
    'create-new-forum',
    'manage-movies',
    'add-new-movies',
    'manage-games',
    'add-new-game',
    'ads-settings',
    'manage-site-ads',
    'manage-user-ads',
    'manage-themes',
    'manage-site-design',
    'manage-announcements',
    'mass-notifications',
    'ban-users',
    'generate-sitemap',
    'manage-invitation-keys',
    'backups',
    'manage-custom-pages',
    'add-new-custom-page',
    'edit-custom-page',
    'edit-terms-pages',
    'manage_terms_pages',
    'manage-reports',
    'push-notifications-system',
    'manage-api-access-keys',
    'verfiy-applications',
    'manage-updates',
    'changelog',
    'online-users',
    'custom-code',
    'manage-third-psites',
    'edit-movie',
    'auto-delete',
    'manage-gifts',
    'add-new-gift',
    'post-settings',
    'manage-stickers',
    'add-new-sticker',
    'manage-apps',
    'auto-friend',
    'fake-users',
    'manage-genders',
    'pages-categories',
    'groups-categories',
    'blogs-categories',
    'products-categories',
    'bank-receipts',
    'manage-currencies',
    'manage-colored-posts',
    'job-categories',
    'manage-fund',
    'manage-jobs',
    'auto-like',
    'auto-join',
    'manage-reactions',
    'pages-sub-categories',
    'groups-sub-categories',
    'products-sub-categories',
    'pages-fields',
    'groups-fields',
    'products-fields',
    'pro-features',
    'pro-refund',
    'manage-offers',
    'manage-invitation',
    'send_email',
    'live',
    'node',
    'manage_emails',
    'ffmpeg',
    'manage-permissions',
    'store-settings',
    'manage-products',
    'manage-orders',
    'manage-reviews',
    'website_mode',
    'user_reports',
    'edit-forum',
    'edit-section',
    'cronjob_settings',
    'system_status',
    "upload-to-storage",
    "ai-settings",
);
$wo['mod_pages'] = array('dashboard', 'post-settings', 'manage-stickers', 'manage-gifts', 'manage-users', 'online-users', 'manage-stories', 'manage-pages', 'manage-groups', 'manage-posts', 'manage-articles', 'manage-events', 'manage-forum-threads', 'manage-forum-messages', 'manage-movies', 'manage-games', 'add-new-game', 'manage-user-ads', 'manage-reports', 'manage-third-psites', 'edit-movie', 'bank-receipts', 'job-categories', 'manage-jobs');


if (!empty($_GET['page'])) {
    $page = Wo_Secure($_GET['page'], 0);
}
$wo['decode_android_v']  = $wo['config']['footer_background'];
$wo['decode_android_value']  = base64_decode('I2FhYQ==');

$wo['decode_android_n_v']  = $wo['config']['footer_background_n'];
$wo['decode_android_n_value']  = base64_decode('I2FhYQ==');

$wo['decode_ios_v']  = $wo['config']['footer_background_2'];
$wo['decode_ios_value']  = base64_decode('I2FhYQ==');

$wo['decode_windwos_v']  = $wo['config']['footer_text_color'];
$wo['decode_windwos_value']  = base64_decode('I2RkZA==');
if ($is_moderoter && !empty($wo['user']['permission'])) {
    $wo['user']['permission'] = json_decode($wo['user']['permission'], true);

    if (!in_array($page, array_keys($wo['user']['permission']))) {
        $wo['user']['permission'][$page] = 0;
        $permission = json_encode($wo['user']['permission']);
        $db->where('user_id', $wo['user']['user_id'])->update(T_USERS, array('permission' => $permission));

        cache($wo['user']['id'], 'users', 'delete');
        header("Location: " . Wo_LoadAdminLinkSettings($page));
        exit();
    } else {
        if ($wo['user']['permission'][$page] == 0) {
            foreach ($wo['user']['permission'] as $key => $value) {
                if ($value == 1) {
                    header("Location: " . Wo_LoadAdminLinkSettings($key));
                    exit();
                }
            }
        }
    }
} elseif ($is_moderoter && empty($wo['user']['permission'])) {
    $permission = array();
    if (!empty($wo['all_pages'])) {
        foreach ($wo['all_pages']  as $key => $value) {
            if (in_array($value, $wo['mod_pages'])) {
                $permission[$value] = 1;
            } else {
                $permission[$value] = 0;
            }
        }
    }
    $permission = json_encode($permission);
    $db->where('user_id', $wo['user']['user_id'])->update(T_USERS, array('permission' => $permission));
    cache($wo['user']['id'], 'users', 'delete');
    $wo['user'] = Wo_UserData($wo['user']['user_id']);
}

// if ($is_moderoter == true && $is_admin == false) {
//     if (!in_array($page, $wo['mod_pages'])) {
//         header("Location: " . Wo_SeoLink('index.php?link1=admin-cp'));
//         exit();
//     }
// }
if (in_array($page, $pages)) {
    $page_loaded = Wo_LoadAdminPage("$page/content");
}
if (empty($page_loaded)) {
    header("Location: " . Wo_SeoLink('index.php?link1=admin-cp'));
    exit();
}

$notify_count = $db->where('recipient_id', 0)->where('admin', 1)->where('seen', 0)->getValue(T_NOTIFICATION, 'COUNT(*)');
$notifications = $db->where('recipient_id', 0)->where('admin', 1)->where('seen', 0)->orderBy('id', 'DESC')->get(T_NOTIFICATION);
$old_notifications = $db->where('recipient_id', 0)->where('admin', 1)->where('seen', 0, '!=')->orderBy('id', 'DESC')->get(T_NOTIFICATION, 5);
$mode = 'day';
if (!empty($_COOKIE['mode']) && $_COOKIE['mode'] == 'night') {
    $mode = 'night';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bảng điều khiển | <?php echo $wo['config']['siteTitle']; ?></title>
    <link rel="icon" href="<?php echo $wo['config']['theme_url']; ?>/img/icon.png" type="image/png">


    <!-- Main css -->
    <link rel="stylesheet" href="<?php echo (Wo_LoadAdminLink('vendors/bundle.css')) ?>" type="text/css">

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Daterangepicker -->
    <link rel="stylesheet" href="<?php echo (Wo_LoadAdminLink('vendors/datepicker/daterangepicker.css')) ?>" type="text/css">

    <!-- DataTable -->
    <link rel="stylesheet" href="<?php echo (Wo_LoadAdminLink('vendors/dataTable/datatables.min.css')) ?>" type="text/css">

    <!-- App css -->
    <link rel="stylesheet" href="<?php echo (Wo_LoadAdminLink('assets/css/app.css')) ?>" type="text/css">
    <!-- Main scripts -->
    <script src="<?php echo (Wo_LoadAdminLink('vendors/bundle.js')) ?>"></script>

    <!-- Apex chart -->
    <script src="<?php echo (Wo_LoadAdminLink('vendors/charts/apex/apexcharts.min.js')) ?>"></script>

    <!-- Daterangepicker -->
    <script src="<?php echo (Wo_LoadAdminLink('vendors/datepicker/daterangepicker.js')) ?>"></script>

    <!-- DataTable -->
    <script src="<?php echo (Wo_LoadAdminLink('vendors/dataTable/datatables.min.js')) ?>"></script>

    <!-- Dashboard scripts -->
    <script src="<?php echo (Wo_LoadAdminLink('assets/js/examples/pages/dashboard.js')) ?>"></script>
    <script src="<?php echo Wo_LoadAdminLink('vendors/charts/chartjs/chart.min.js'); ?>"></script>

    <!-- App scripts -->

    <link href="<?php echo Wo_LoadAdminLink('vendors/sweetalert/sweetalert.css'); ?>" rel="stylesheet" />
    <script src="<?php echo Wo_LoadAdminLink('assets/js/admin.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo (Wo_LoadAdminLink('vendors/select2/css/select2.min.css')) ?>" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <?php //if ($page == 'create-article' || $page == 'edit-article' || $page == 'manage-announcements' || $page == 'newsletters') { 
    ?>
    <script src="<?php echo Wo_LoadAdminLink('vendors/tinymce/js/tinymce/tinymce.min.js'); ?>"></script>
    <script src="<?php echo Wo_LoadAdminLink('vendors/bootstrap-tagsinput/src/bootstrap-tagsinput.js'); ?>"></script>
    <link href="<?php echo Wo_LoadAdminLink('vendors/bootstrap-tagsinput/src/bootstrap-tagsinput.css'); ?>" rel="stylesheet" />
    <?php //} 
    ?>
    <?php //if ($page == 'custom-code') { 
    ?>
    <script src="<?php echo Wo_LoadAdminLink('vendors/codemirror-5.30.0/lib/codemirror.js'); ?>"></script>
    <script src="<?php echo Wo_LoadAdminLink('vendors/codemirror-5.30.0/mode/css/css.js'); ?>"></script>
    <script src="<?php echo Wo_LoadAdminLink('vendors/codemirror-5.30.0/mode/javascript/javascript.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo Wo_LoadAdminLink('vendors/codemirror-5.30.0/lib/codemirror.css'); ?>">
    <?php //} 
    ?>


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php if ($page == 'bank-receipts' || $page == 'manage-verification-reqeusts' || $page == 'monetization-requests' || $page == 'manage-user-ads') { ?>
        <!-- Css -->
        <link rel="stylesheet" href="<?php echo (Wo_LoadAdminLink('vendors/lightbox/magnific-popup.css')) ?>" type="text/css">

        <!-- Javascript -->
        <script src="<?php echo (Wo_LoadAdminLink('vendors/lightbox/jquery.magnific-popup.min.js')) ?>"></script>
        <script src="<?php echo (Wo_LoadAdminLink('vendors/charts/justgage/raphael-2.1.4.min.js')) ?>"></script>
        <script src="<?php echo (Wo_LoadAdminLink('vendors/charts/justgage/justgage.js')) ?>"></script>
    <?php } ?>
    <script src="<?php echo Wo_LoadAdminLink('assets/js/jquery.form.min.js'); ?>"></script>
    <script>
        function Wo_Ajax_Requests_File() {
            return "<?php echo $wo['config']['site_url'] . '/requests.php'; ?>"
        }

        function Wo_Ajax_Requests_File_load() {
            return "<?php echo $wo['config']['site_url'] . '/admin_load.php'; ?>"
        }
    </script>
    <style>
        body {
            background-color: #222;
        }

        .btn.btn-primary,
        a.btn[href="#next"],
        a.btn[href="#previous"] {
            color: #fff !important;
            background: #C32E3A;
            border-color: #C32E3A;
        }

        .btn.btn-primary:not(:disabled):not(.disabled):hover,
        a.btn[href="#next"]:not(:disabled):not(.disabled):hover,
        a.btn[href="#previous"]:not(:disabled):not(.disabled):hover,
        .btn.btn-primary:not(:disabled):not(.disabled):focus,
        a.btn[href="#next"]:not(:disabled):not(.disabled):focus,
        a.btn[href="#previous"]:not(:disabled):not(.disabled):focus,
        .btn.btn-primary:not(:disabled):not(.disabled):active,
        a.btn[href="#next"]:not(:disabled):not(.disabled):active,
        a.btn[href="#previous"]:not(:disabled):not(.disabled):active,
        .btn.btn-primary:not(:disabled):not(.disabled).active,
        a.btn[href="#next"]:not(:disabled):not(.disabled).active,
        a.btn[href="#previous"]:not(:disabled):not(.disabled).active {
            background: #CE3643;
            border-color: #CE3643;
        }

        body.dark .navigation .navigation-menu-body ul li a.active,
        .breadcrumb .breadcrumb-item.active,
        body.dark .breadcrumb li.breadcrumb-item.active,
        body.dark .navigation .navigation-menu-body ul li a.active .nav-link-icon {
            color: #C32E3A !important;
        }

        .card form .form-check-inline input:checked {
            background-color: #C32E3A;
        }

        .card form .form-check-inline input:checked+label::before,
        .card form .form-check-inline input:active+label::before {
            border-color: #C32E3A;
        }

        .card form .form-check-inline label::after {
            background-color: #C32E3A;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 2px solid #C32E3A !important;
        }
    </style>
</head>
<script type="text/javascript">
    $(function() {

        $(document).on('click', 'a[data-ajax]', function(e) {
            $(document).off('click', '.ranges ul li');
            $(document).off('click', '.applyBtn');
            e.preventDefault();
            if (($(this)[0].hasAttribute("data-sent") && $(this).attr('data-sent') == '0') || !$(this)[0]
                .hasAttribute("data-sent")) {
                if (!$(this)[0].hasAttribute("data-sent") && !$(this).hasClass('waves-effect')) {
                    $('.navigation-menu-body').find('a').removeClass('active');
                    $(this).addClass('active');
                }
                window.history.pushState({
                    state: 'new'
                }, '', $(this).attr('href'));
                $(".barloading").css("display", "block");
                if ($(this)[0].hasAttribute("data-sent")) {
                    $(this).attr('data-sent', "1");
                }
                var url = $(this).attr('data-ajax');
                $.post(Wo_Ajax_Requests_File_load() + url, {
                    url: url
                }, function(data) {
                    $(".barloading").css("display", "none");
                    if ($('#redirect_link')[0].hasAttribute("data-sent")) {
                        $('#redirect_link').attr('data-sent', "0");
                    }
                    json_data = JSON.parse($(data).filter('#json-data').val());
                    $('.content').html(data);
                    setTimeout(function() {
                        $(".content").getNiceScroll().resize()
                    }, 500);
                    $(".content").animate({
                        scrollTop: 0
                    }, "slow");
                });
            }
        });
        $(window).on("popstate", function(e) {
            location.reload();
        });
    });
</script>

<body <?php echo ($mode == 'night' ? 'class="dark"' : ''); ?>>
    <div class="barloading"></div>
    <a id="redirect_link" href="" data-ajax="" data-sent="0"></a>
    <input type="hidden" class="main_session" value="<?php echo Wo_CreateMainSession(); ?>">
    <div class="colors">
        <!-- To use theme colors with Javascript -->
        <div class="bg-primary"></div>
        <div class="bg-primary-bright"></div>
        <div class="bg-secondary"></div>
        <div class="bg-secondary-bright"></div>
        <div class="bg-info"></div>
        <div class="bg-info-bright"></div>
        <div class="bg-success"></div>
        <div class="bg-success-bright"></div>
        <div class="bg-danger"></div>
        <div class="bg-danger-bright"></div>
        <div class="bg-warning"></div>
        <div class="bg-warning-bright"></div>
    </div>
    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-icon"></div>
        <span>Đang tải...</span>
    </div>
    <!-- ./ Preloader -->

    <!-- Sidebar group -->
    <div class="sidebar-group">

    </div>
    <!-- ./ Sidebar group -->

    <!-- Layout wrapper -->
    <div class="layout-wrapper">

        <!-- Header -->
        <div class="header d-print-none">
            <div class="header-container">
                <div class="header-left">
                    <div class="navigation-toggler">
                        <a href="#" data-action="navigation-toggler">
                            <i data-feather="menu"></i>
                        </a>
                    </div>

                    <div class="header-logo">
                        <a href="<?php echo $wo['config']['site_url'] ?>">
                            <img class="logo" src="<?php echo $wo['config']['theme_url']; ?>/img/<?php echo ($wo['config']['theme'] == 'sunshine' ? 'night-' : '') ?>logo.<?php echo $wo['config']['logo_extension']; ?>" alt="logo">
                        </a>
                    </div>
                </div>

                <div class="header-body">
                    <div class="header-body-left">
                        <ul class="navbar-nav">
                            <li class="nav-item mr-3">
                                <div class="header-search-form">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn">
                                                <i data-feather="search"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Search" onkeyup="searchInFiles($(this).val())">
                                        <div class="pt_admin_hdr_srch_reslts" id="search_for_bar"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="header-body-right">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link <?php if ($notify_count > 0) { ?> nav-link-notify<?php } ?>" title="Notifications" data-toggle="dropdown">
                                    <i data-feather="bell"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                                    <div class="border-bottom px-4 py-3 text-center d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Thông báo</h5>
                                        <?php if ($notify_count > 0) { ?>
                                            <small class="opacity-7"><?php echo $notify_count; ?> Thông báo chưa đọc</small>
                                        <?php } ?>
                                    </div>
                                    <div class="dropdown-scroll">
                                        <ul class="list-group list-group-flush">
                                            <?php if ($notify_count > 0) { ?>
                                                <li class="px-4 py-2 text-center small text-muted bg-light">Thông báo chưa đọc</li>
                                                <?php if (!empty($notifications)) {
                                                    foreach ($notifications as $key => $notify) {
                                                        $page_ = '';
                                                        $text = '';
                                                        if ($notify->type == 'bank') {
                                                            $page_ = 'bank-receipts';
                                                            $text = 'You have a new bank payment awaiting your approval';
                                                        } elseif ($notify->type == 'verify') {
                                                            $page_ = 'manage-verification-reqeusts';
                                                            $text = 'You have a new verification requests awaiting your approval';
                                                        } elseif ($notify->type == 'refund') {
                                                            $page_ = 'pro-refund';
                                                            $text = 'You have a new refund requests awaiting your approval';
                                                        } elseif ($notify->type == 'with') {
                                                            $page_ = 'payment-reqeuests';
                                                            $text = 'You have a new withdrawal requests awaiting your approval';
                                                        } elseif ($notify->type == 'report') {
                                                            $page_ = 'manage-reports';
                                                            $text = 'You have a new reports awaiting your approval';
                                                        } elseif ($notify->type == 'user_reports') {
                                                            $page_ = 'user_reports';
                                                            $text = 'You have a new reports awaiting your approval';
                                                        }
                                                ?>
                                                        <li class="px-4 py-3 list-group-item">
                                                            <a href="<?php echo Wo_LoadAdminLinkSettings($page_); ?>" class="d-flex align-items-center hide-show-toggler">
                                                                <div class="flex-shrink-0">
                                                                    <figure class="avatar mr-3">
                                                                        <span class="avatar-title bg-info-bright text-info rounded-circle">
                                                                            <?php if ($notify->type == 'bank') { ?>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card">
                                                                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                                                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                                                                </svg>
                                                                            <?php } elseif ($notify->type == 'verify') { ?>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                                                    <path fill="#2196f3" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z">
                                                                                    </path>
                                                                                </svg>
                                                                            <?php } elseif ($notify->type == 'refund') { ?>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw">
                                                                                    <polyline points="23 4 23 10 17 10"></polyline>
                                                                                    <polyline points="1 20 1 14 7 14"></polyline>
                                                                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15">
                                                                                    </path>
                                                                                </svg>
                                                                            <?php } elseif ($notify->type == 'with') { ?>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                                                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                                                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6">
                                                                                    </path>
                                                                                </svg>
                                                                            <?php } elseif ($notify->type == 'report' || $notify->type == 'user_reports') { ?>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-flag">
                                                                                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z">
                                                                                    </path>
                                                                                    <line x1="4" y1="22" x2="4" y2="15"></line>
                                                                                </svg>
                                                                            <?php } ?>

                                                                        </span>
                                                                    </figure>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <p class="mb-0 line-height-20 d-flex justify-content-between">
                                                                        <?php echo $text; ?>
                                                                    </p>
                                                                    <span class="text-muted small"><?php echo Wo_Time_Elapsed_String($notify->time); ?></span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                <?php }
                                                } ?>
                                            <?php } ?>
                                            <?php if ($notify_count == 0 && !empty($old_notifications)) { ?>
                                                <li class="px-4 py-2 text-center small text-muted bg-light">Thông báo cũ</li>
                                                <?php
                                                foreach ($old_notifications as $key => $notify) {
                                                    $page_ = '';
                                                    $text = '';
                                                    if ($notify->type == 'bank') {
                                                        $page_ = 'bank-receipts';
                                                        $text = 'You have a new bank payment awaiting your approval';
                                                    } elseif ($notify->type == 'verify') {
                                                        $page_ = 'verification-requests';
                                                        $text = 'You have a new verification requests awaiting your approval';
                                                    } elseif ($notify->type == 'refund') {
                                                        $page_ = 'pro-refund';
                                                        $text = 'You have a new refund requests awaiting your approval';
                                                    } elseif ($notify->type == 'with') {
                                                        $page_ = 'payment-reqeuests';
                                                        $text = 'You have a new withdrawal requests awaiting your approval';
                                                    } elseif ($notify->type == 'report') {
                                                        $page_ = 'manage-reports';
                                                        $text = 'You have a new reports awaiting your approval';
                                                    } elseif ($notify->type == 'user_reports') {
                                                        $page_ = 'user_reports';
                                                        $text = 'You have a new reports awaiting your approval';
                                                    }
                                                ?>
                                                    <li class="px-4 py-3 list-group-item">
                                                        <a href="<?php echo Wo_LoadAdminLinkSettings($page_); ?>" class="d-flex align-items-center hide-show-toggler">
                                                            <div class="flex-shrink-0">
                                                                <figure class="avatar mr-3">
                                                                    <span class="avatar-title bg-secondary-bright text-secondary rounded-circle">
                                                                        <?php if ($notify->type == 'bank') { ?>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card">
                                                                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                                                                <line x1="1" y1="10" x2="23" y2="10"></line>
                                                                            </svg>
                                                                        <?php } elseif ($notify->type == 'verify') { ?>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                                                <path fill="#2196f3" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z">
                                                                                </path>
                                                                            </svg>
                                                                        <?php } elseif ($notify->type == 'refund') { ?>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw">
                                                                                <polyline points="23 4 23 10 17 10"></polyline>
                                                                                <polyline points="1 20 1 14 7 14"></polyline>
                                                                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15">
                                                                                </path>
                                                                            </svg>
                                                                        <?php } elseif ($notify->type == 'with') { ?>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                                                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6">
                                                                                </path>
                                                                            </svg>
                                                                        <?php } elseif ($notify->type == 'report' || $notify->type == 'user_reports') { ?>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-flag">
                                                                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z">
                                                                                </path>
                                                                                <line x1="4" y1="22" x2="4" y2="15"></line>
                                                                            </svg>
                                                                        <?php } ?>
                                                                    </span>
                                                                </figure>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <p class="mb-0 line-height-20 d-flex justify-content-between">
                                                                    <?php echo $text; ?>
                                                                </p>
                                                                <span class="text-muted small"><?php echo Wo_Time_Elapsed_String($notify->time); ?></span>
                                                            </div>
                                                        </a>
                                                    </li>
                                            <?php }
                                            } ?>
                                        </ul>
                                    </div>
                                    <?php if ($notify_count > 0) { ?>
                                        <div class="px-4 py-3 text-right border-top">
                                            <ul class="list-inline small">
                                                <li class="list-inline-item mb-0">
                                                    <a href="javascript:void(0)" onclick="ReadNotify()">Đánh dấu đã đọc tất cả</a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" title="User menu" data-toggle="dropdown">
                                    <figure class="avatar avatar-sm">
                                        <img src="<?php echo $wo['user']['avatar']; ?>" class="rounded-circle" alt="avatar">
                                    </figure>
                                    <span class="ml-2 d-sm-inline d-none"><?php echo $wo['user']['name']; ?></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                                    <div class="text-center py-4">
                                        <figure class="avatar avatar-lg mb-3 border-0">
                                            <img src="<?php echo $wo['user']['avatar']; ?>" class="rounded-circle" alt="image">
                                        </figure>
                                        <h5 class="text-center"><?php echo $wo['user']['name']; ?></h5>
                                        <div class="mb-3 small text-center text-muted">
                                            <?php echo $wo['user']['email']; ?></div>
                                        <a href="<?php echo $wo['user']['url']; ?>" class="btn btn-outline-light btn-rounded">Xem hồ sơ</a>
                                    </div>
                                    <div class="list-group">
                                        <a href="<?php echo (Wo_Link('logout')) ?>" class="list-group-item text-danger">Đăng xuất!</a>
                                        <?php if ($mode == 'night') { ?>
                                            <a href="javascript:void(0)" class="list-group-item admin_mode" onclick="ChangeMode('day')">
                                                <span id="night-mode-text">Chế độ ban ngày</span>
                                                <svg class="feather feather-moon float-right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                                                </svg>
                                            </a>
                                        <?php } else { ?>
                                            <a href="javascript:void(0)" class="list-group-item admin_mode" onclick="ChangeMode('night')">
                                                <span id="night-mode-text">Chế độ ban đêm</span>
                                                <svg class="feather feather-moon float-right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                                                </svg>
                                            </a>
                                        <?php } ?>

                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item header-toggler">
                        <a href="#" class="nav-link">
                            <i data-feather="arrow-down"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ./ Header -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
            <!-- begin::navigation -->
            <div class="navigation">
                <div class="navigation-header">
                    <span>Thanh điều hướng</span>
                    <a href="#">
                        <i class="ti-close"></i>
                    </a>
                </div>
                <div class="navigation-menu-body">
                    <ul>
                        <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['dashboard'] == 1)) { ?>
                            <li>
                                <a <?php echo ($page == 'dashboard') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings(''); ?>" data-ajax="?path=dashboard">
                                    <span class="nav-link-icon">
                                        <svg style="width:24px;height:24px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                        </svg>

                                    </span>
                                    <span>Bảng điều khiển</span>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['post-settings'] == 1 || $wo['user']['permission']['manage-colored-posts'] == 1 || $wo['user']['permission']['manage-reactions'] == 1 || $wo['user']['permission']['live'] == 1 || $wo['user']['permission']['general-settings'] == 1 || $wo['user']['permission']['site-settings'] == 1 || $wo['user']['permission']['amazon-settings'] == 1 || $wo['user']['permission']['email-settings'] == 1 || $wo['user']['permission']['video-settings'] == 1 || $wo['user']['permission']['social-login'] == 1 || $wo['user']['permission']['node'] == 1 || $wo['user']['permission']['cronjob_settings'] == 1))) { ?>
                            <li <?php echo ($page == 'general-settings' || $page == 'post-settings' || $page == 'site-settings' || $page == 'email-settings' || $page == 'social-login' || $page == 'site-features' || $page == 'amazon-settings' ||  $page == 'video-settings' || $page == 'manage-currencies' || $page == 'manage-colored-posts' || $page == 'live' || $page == 'node' || $page == 'manage-reactions' || $page == 'ffmpeg' || $page == 'cronjob_settings') ? 'class="open"' : ''; ?>>
                                <a href="#">
                                    <span class="nav-link-icon">
                                        <svg style="width:24px;height: 24px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>

                                    </span>
                                    <span>Cài đặt</span>
                                </a>
                                <ul class="ml-menu">
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['website_mode'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'website_mode') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('website_mode'); ?>" data-ajax="?path=website_mode">Chế độ trang web</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['general-settings'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'general-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('general-settings'); ?>" data-ajax="?path=general-settings">Cấu hình tổng quan</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['site-settings'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'site-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('site-settings'); ?>" data-ajax="?path=site-settings">Thông tin trang web</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['amazon-settings'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'amazon-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('amazon-settings'); ?>" data-ajax="?path=amazon-settings">Cấu hình tải lên tập tin</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['email-settings'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'email-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('email-settings'); ?>" data-ajax="?path=email-settings">Thiết lập E-mail & SMS</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['video-settings'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'video-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('video-settings'); ?>" data-ajax="?path=video-settings">Chat & Video/Audio</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['social-login'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'social-login') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('social-login'); ?>" data-ajax="?path=social-login">Đăng nhập bằng mạng xã hội</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['node'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'node') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('node'); ?>" data-ajax="?path=node">Cài đặt NodeJS</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['cronjob_settings'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'cronjob_settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('cronjob_settings'); ?>" data-ajax="?path=cronjob_settings">Cài đặt CronJob</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['ai-settings'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'ai-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('ai-settings'); ?>" data-ajax="?path=ai-settings">Cài đặt AI</a>
                                        </li>
                                    <?php } ?>

                                    <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['post-settings'] == 1 || $wo['user']['permission']['manage-colored-posts'] == 1 || $wo['user']['permission']['manage-reactions'] == 1 || $wo['user']['permission']['live'] == 1))) { ?>
                                        <li>
                                            <a <?php echo ($page == 'post-settings' || $page == 'manage-colored-posts' || $page == 'manage-reactions') ? 'class="open"' : ''; ?> href="javascript:void(0);">Cài đặt bài đăng</a>
                                            <ul class="ml-menu">
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['post-settings'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'post-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('post-settings'); ?>" data-ajax="?path=post-settings">
                                                            <span>Cài đặt bài đăng</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-colored-posts'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-colored-posts') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-colored-posts'); ?>" data-ajax="?path=manage-colored-posts">
                                                            <span>Quản lý màu bài viết</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-reactions'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-reactions') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-reactions'); ?>" data-ajax="?path=manage-reactions">
                                                            <span>Cài đặt biểu cảm</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['live'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'live') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('live'); ?>" data-ajax="?path=live">Thiết lập phát sóng trực tiếp</a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>

                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['manage-apps'] == 1 || $wo['user']['permission']['manage-pages'] == 1 || $wo['user']['permission']['manage-stickers'] == 1 || $wo['user']['permission']['add-new-sticker'] == 1 || $wo['user']['permission']['manage-gifts'] == 1 || $wo['user']['permission']['add-new-gift'] == 1 || $wo['user']['permission']['manage-groups'] == 1 || $wo['user']['permission']['manage-posts'] == 1 || $wo['user']['permission']['manage-articles'] == 1 || $wo['user']['permission']['manage-events'] == 1 || $wo['user']['permission']['manage-forum-sections'] == 1 || $wo['user']['permission']['manage-forum-forums'] == 1 || $wo['user']['permission']['manage-forum-threads'] == 1 || $wo['user']['permission']['manage-forum-messages'] == 1 || $wo['user']['permission']['create-new-forum'] == 1 || $wo['user']['permission']['create-new-section'] == 1 || $wo['user']['permission']['manage-movies'] == 1 || $wo['user']['permission']['add-new-movies'] == 1 || $wo['user']['permission']['manage-games'] == 1 || $wo['user']['permission']['add-new-game'] == 1 || $wo['user']['permission']['edit-movie'] == 1 || $wo['user']['permission']['pages-categories'] == 1 || $wo['user']['permission']['pages-sub-categories'] == 1 || $wo['user']['permission']['groups-sub-categories'] == 1 || $wo['user']['permission']['products-sub-categories'] == 1 || $wo['user']['permission']['groups-categories'] == 1 || $wo['user']['permission']['blogs-categories'] == 1 || $wo['user']['permission']['products-categories'] == 1 || $wo['user']['permission']['manage-fund'] == 1 || $wo['user']['permission']['manage-jobs'] == 1 || $wo['user']['permission']['manage-offers'] == 1 || $wo['user']['permission']['pages-fields'] == 1 || $wo['user']['permission']['groups-fields'] == 1 || $wo['user']['permission']['products-fields'] == 1))) { ?>

                            <li <?php echo ($page == 'manage-apps' || $page == 'manage-pages' || $page == 'manage-stickers' || $page == 'add-new-sticker' || $page == 'manage-gifts' || $page == 'add-new-gift' || $page == 'manage-groups' || $page == 'manage-posts' || $page == 'manage-articles' || $page == 'manage-events' ||  $page == 'manage-forum-sections' || $page == 'manage-forum-forums' || $page == 'manage-forum-threads' || $page == 'manage-forum-messages' || $page == 'create-new-forum' || $page == 'create-new-section' || $page == 'manage-movies' || $page == 'add-new-movies' || $page == 'manage-games' || $page == 'add-new-game' || $page == 'edit-movie' || $page == 'pages-categories' || $page == 'pages-sub-categories' || $page == 'groups-sub-categories' || $page == 'products-sub-categories' || $page == 'groups-categories' || $page == 'blogs-categories' || $page == 'products-categories' || $page == 'manage-fund' || $page == 'manage-jobs' || $page == 'manage-offers' || $page == 'pages-fields' || $page == 'groups-fields' || $page == 'products-fields') ? 'class="open"' : ''; ?>>
                                <a href="#">
                                    <span class="nav-link-icon">
                                        <svg style="width:24px;height: 24px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                        </svg>

                                    </span>
                                    <span>Quản lý tính năng</span>
                                </a>

                                <ul class="ml-menu">
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['site-features'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'site-features') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('site-features'); ?>" data-ajax="?path=site-features">Kích hoạt/ Vô hiệu hóa tính năng</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-apps'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-apps') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-apps'); ?>" data-ajax="?path=manage-apps">Ứng dụng</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-pages'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-pages') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-pages'); ?>" data-ajax="?path=manage-pages">Trang</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-groups'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-groups') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-groups'); ?>" data-ajax="?path=manage-groups">Nhóm</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-posts'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-posts') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-posts'); ?>" data-ajax="?path=manage-posts">Bài đăng</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-fund'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-fund') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-fund'); ?>" data-ajax="?path=manage-fund">Donate</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-jobs'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-jobs') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-jobs'); ?>" data-ajax="?path=manage-jobs">Công việc</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-offers'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-offers') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-offers'); ?>" data-ajax="?path=manage-offers">Lời mời</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-articles'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-articles') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-articles'); ?>" data-ajax="?path=manage-articles">Bài viết</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-events'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-events') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-events'); ?>" data-ajax="?path=manage-events">Sự kiện</a>
                                        </li>
                                    <?php } ?>


                                    <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['store-settings'] == 1 || $wo['user']['permission']['manage-products'] == 1 || $wo['user']['permission']['manage-orders'] == 1 || $wo['user']['permission']['manage-reviews'] == 1))) { ?>
                                        <li <?php echo ($page == 'store-settings' || $page == 'manage-products' || $page == 'manage-orders' || $page == 'manage-reviews') ? 'class="open"' : ''; ?>>
                                            <a href="javascript:void(0);">Cửa hàng</a>
                                            <ul class="ml-menu">
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['store-settings'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'store-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('store-settings'); ?>" data-ajax="?path=store-settings">
                                                            <span>Cài đặt cửa hàng</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-products'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-products') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-products'); ?>" data-ajax="?path=manage-products">
                                                            <span>Quản lý sản phẩm</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-orders'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-orders') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-orders'); ?>" data-ajax="?path=manage-orders">
                                                            <span>Quản lý đơn hàng</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-reviews'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-reviews') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-reviews'); ?>" data-ajax="?path=manage-reviews">
                                                            <span>Quản lý đánh giá</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>









                                    <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['manage-forum-sections'] == 1 || $wo['user']['permission']['manage-forum-forums'] == 1 || $wo['user']['permission']['manage-forum-threads'] == 1 || $wo['user']['permission']['manage-forum-messages'] == 1 || $wo['user']['permission']['create-new-forum'] == 1 || $wo['user']['permission']['create-new-section'] == 1))) { ?>
                                        <li <?php echo ($page == 'manage-forum-sections' || $page == 'manage-forum-forums' || $page == 'manage-forum-threads' || $page == 'manage-forum-messages' || $page == 'create-new-forum' || $page == 'create-new-section') ? 'class="open"' : ''; ?>>
                                            <a href="javascript:void(0);">Diễn đàn</a>
                                            <ul class="ml-menu">
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-forum-sections'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-forum-sections') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-forum-sections'); ?>" data-ajax="?path=manage-forum-sections">
                                                            <span>Quản lý diễn đàn</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-forum-forums'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-forum-forums') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-forum-forums'); ?>" data-ajax="?path=manage-forum-forums">
                                                            <span>Quản lý diễn đàn</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-forum-threads'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-forum-threads') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-forum-threads'); ?>" data-ajax="?path=manage-forum-threads">
                                                            <span>Quản lý chủ đề</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-forum-messages'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-forum-messages') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-forum-messages'); ?>" data-ajax="?path=manage-forum-messages">
                                                            <span>Quản lý phản hồi</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['create-new-section'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'create-new-section') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('create-new-section'); ?>" data-ajax="?path=create-new-section">
                                                            <span>Tạo phần mới</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['create-new-forum'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'create-new-forum') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('create-new-forum'); ?>" data-ajax="?path=create-new-forum">
                                                            <span>Tạo diễn đàn mới</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['manage-movies'] == 1 || $wo['user']['permission']['add-new-movies'] == 1))) { ?>
                                        <li <?php echo ($page == 'manage-movies' || $page == 'add-new-movies') ? 'class="open"' : ''; ?>>
                                            <a href="javascript:void(0);">Chia sẻ kiến thức kinh doanh</a>
                                            <ul class="ml-menu">
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-movies'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-movies') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-movies'); ?>" data-ajax="?path=manage-movies">
                                                            <span>Quản lý chia sẻ kiến thức kinh doanh</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['add-new-movies'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'add-new-movies') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('add-new-movies'); ?>" data-ajax="?path=add-new-movies">
                                                            <span>Thêm chia sẻ kiến thức kinh doanh mới</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['manage-games'] == 1 || $wo['user']['permission']['add-new-game'] == 1))) { ?>

                                        <li <?php echo ($page == 'manage-games' || $page == 'add-new-game') ? 'class="open"' : ''; ?>>
                                            <a href="javascript:void(0);">Ứng dụng hỗ trợ</a>
                                            <ul class="ml-menu">
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-games'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-games') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-games'); ?>" data-ajax="?path=manage-games">
                                                            <span>Quản lý ứng dụng hỗ trợ</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['add-new-game'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'add-new-game') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('add-new-game'); ?>" data-ajax="?path=add-new-game">
                                                            <span>Thêm ứng dụng hỗ trợ mới</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['pages-categories'] == 1 || $wo['user']['permission']['pages-sub-categories'] == 1 || $wo['user']['permission']['groups-sub-categories'] == 1 || $wo['user']['permission']['products-sub-categories'] == 1 || $wo['user']['permission']['groups-categories'] == 1 || $wo['user']['permission']['blogs-categories'] == 1 || $wo['user']['permission']['products-categories'] == 1))) { ?>
                                        <li <?php echo ($page == 'pages-categories' || $page == 'pages-sub-categories' || $page == 'groups-sub-categories' || $page == 'products-sub-categories' || $page == 'groups-categories' || $page == 'blogs-categories' || $page == 'products-categories') ? 'class="open"' : ''; ?>>
                                            <a href="javascript:void(0);">Danh mục</a>
                                            <ul class="ml-menu">
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['pages-categories'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'pages-categories') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('pages-categories'); ?>" data-ajax="?path=pages-categories">
                                                            <span>Trang danh mục</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['pages-sub-categories'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'pages-sub-categories') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('pages-sub-categories'); ?>" data-ajax="?path=pages-sub-categories">
                                                            <span>Trang danh mục con</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['groups-categories'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'groups-categories') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('groups-categories'); ?>" data-ajax="?path=groups-categories">
                                                            <span>Nhóm danh mục</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['groups-sub-categories'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'groups-sub-categories') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('groups-sub-categories'); ?>" data-ajax="?path=groups-sub-categories">
                                                            <span>Nhóm danh mục con</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['blogs-categories'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'blogs-categories') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('blogs-categories'); ?>" data-ajax="?path=blogs-categories">
                                                            <span>Danh mục nhật ký</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['products-categories'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'products-categories') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('products-categories'); ?>" data-ajax="?path=products-categories">
                                                            <span>Danh mục sản phẩm</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['products-sub-categories'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'products-sub-categories') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('products-sub-categories'); ?>" data-ajax="?path=products-sub-categories">
                                                            <span>Danh mục sản phẩm con</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['job-categories'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'job-categories') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('job-categories'); ?>" data-ajax="?path=job-categories">
                                                            <span>Danh mục công việc</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['add-new-gift'] == 1 || $wo['user']['permission']['manage-gifts'] == 1))) { ?>
                                        <?php if ($wo['config']['gift_system'] == 1) { ?>
                                            <li <?php echo ($page == 'manage-gifts' || $page == 'add-new-gift') ? 'class="open"' : ''; ?>>
                                                <a href="javascript:void(0);">Quà tặng</a>
                                                <ul class="ml-menu">
                                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-gifts'] == 1)) { ?>
                                                        <li>
                                                            <a <?php echo ($page == 'manage-gifts') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-gifts'); ?>" data-ajax="?path=manage-gifts">
                                                                <span>Quản lý quà tặng</span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['add-new-gift'] == 1)) { ?>
                                                        <li>
                                                            <a <?php echo ($page == 'add-new-gift') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('add-new-gift'); ?>" data-ajax="?path=add-new-gift">
                                                                <span>Thêm quà tặng mới</span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['manage-stickers'] == 1 || $wo['user']['permission']['add-new-sticker'] == 1))) { ?>
                                        <?php if ($wo['config']['stickers_system'] == 1) { ?>
                                            <li <?php echo ($page == 'manage-stickers' || $page == 'add-new-sticker') ? 'class="open"' : ''; ?>>
                                                <a href="javascript:void(0);">Nhãn dán</a>
                                                <ul class="ml-menu">
                                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-stickers'] == 1)) { ?>
                                                        <li>
                                                            <a <?php echo ($page == 'manage-stickers') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-stickers'); ?>" data-ajax="?path=manage-stickers">
                                                                <span>Quản lý nhãn dán</span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['add-new-sticker'] == 1)) { ?>
                                                        <li>
                                                            <a <?php echo ($page == 'add-new-sticker') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('add-new-sticker'); ?>" data-ajax="?path=add-new-sticker">
                                                                <span>Thêm nhãn dán mới</span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['pages-fields'] == 1 || $wo['user']['permission']['groups-fields'] == 1 || $wo['user']['permission']['products-fields'] == 1 || $wo['user']['permission']['manage-profile-fields'] == 1))) { ?>
                                        <li <?php echo ($page == 'pages-fields' || $page == 'groups-fields' || $page == 'products-fields' || $page == 'manage-profile-fields') ? 'class="open"' : ''; ?>>
                                            <a href="javascript:void(0);">Trường tùy chỉnh</a>
                                            <ul class="ml-menu">
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-profile-fields'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'manage-profile-fields') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-profile-fields'); ?>" data-ajax="?path=manage-profile-fields">Trường tùy chỉnh người dùng</a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['pages-fields'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'pages-fields') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('pages-fields'); ?>" data-ajax="?path=pages-fields">
                                                            <span>Trường tùy chỉnh trang</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['groups-fields'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'groups-fields') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('groups-fields'); ?>" data-ajax="?path=groups-fields">
                                                            <span>Trường tùy chỉnh nhóm</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['products-fields'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'products-fields') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('products-fields'); ?>" data-ajax="?path=products-fields">
                                                            <span>Trường tùy chỉnh sản phẩm</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['manage-languages'] == 1 || $wo['user']['permission']['add-language'] == 1 || $wo['user']['permission']['edit-lang'] == 1))) { ?>
                            <li <?php echo ($page == 'manage-languages' || $page == 'add-language' || $page == 'edit-lang') ? 'class="open"' : ''; ?>>
                                <a href="#">
                                    <span class="nav-link-icon">
                                        <svg style="width:24px;height: 24px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802" />
                                        </svg>
                                    </span>
                                    <span>Ngôn ngữ</span>
                                </a>
                                <ul <?php echo ($page == 'manage-languages' || $page == 'add-language' || $page == 'edit-lang') ? 'style="display: block;"' : ''; ?>>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['add-language'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'add-language') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('add-language'); ?>" data-ajax="?path=add-language">Thêm ngôn ngữ và khóa mới</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-languages'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-languages' || $page == 'edit-lang') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-languages'); ?>" data-ajax="?path=manage-languages">Quản lý ngôn ngữ</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['manage-users'] == 1 || $wo['user']['permission']['manage-stories'] == 1 || $wo['user']['permission']['manage-profile-fields'] == 1 || $wo['user']['permission']['add-new-profile-field'] == 1 || $wo['user']['permission']['manage-verification-reqeusts'] == 1 || $wo['user']['permission']['affiliates-settings'] == 1 || $wo['user']['permission']['payment-reqeuests'] == 1 || $wo['user']['permission']['referrals-list'] == 1 || $wo['user']['permission']['online-users'] == 1 || $wo['user']['permission']['manage-genders'] == 1))) { ?>
                            <li <?php echo ($page == 'manage-users' || $page == 'manage-stories' || $page == 'manage-profile-fields' || $page == 'add-new-profile-field' || $page == 'edit-profile-field' || $page == 'manage-verification-reqeusts' || $page == 'affiliates-settings' || $page == 'payment-reqeuests' || $page == 'referrals-list' || $page == 'online-users' || $page == 'manage-genders') ? 'class="open"' : ''; ?>>
                                <a href="#">
                                    <span class="nav-link-icon">
                                        <svg style="width:24px;height: 24px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>

                                    </span>
                                    <span>Người dùng</span>
                                </a>
                                <ul class="ml-menu">
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-users'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-users') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-users'); ?>" data-ajax="?path=manage-users">Quản lý người dùng</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['online-users'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'online-users') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('online-users'); ?>" data-ajax="?path=online-users">Người dùng đang hoạt động</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-stories'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-stories') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-stories'); ?>" data-ajax="?path=manage-stories">Quản lý trạng thái/tin của người dùng</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-verification-reqeusts'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-verification-reqeusts') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-verification-reqeusts'); ?>" data-ajax="?path=manage-verification-reqeusts">Quản lý yêu cầu xác thực</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['affiliates-settings'] == 1 || $wo['user']['permission']['payment-reqeuests'] == 1 || $wo['user']['permission']['referrals-list'] == 1))) { ?>

                                        <li>
                                            <a <?php echo ($page == 'affiliates-settings' || $page == 'payment-reqeuests' || $page == 'referrals-list') ? 'class="active"' : ''; ?> href="javascript:void(0);">Hệ thống Affiliates</a>
                                            <ul class="ml-menu">
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['affiliates-settings'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'affiliates-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('affiliates-settings'); ?>" data-ajax="?path=affiliates-settings">
                                                            <span>Cài đặt Affiliates</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['payment-reqeuests'] == 1)) { ?>
                                                    <li>
                                                        <a <?php echo ($page == 'payment-reqeuests') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('payment-reqeuests'); ?>" data-ajax="?path=payment-reqeuests">
                                                            <span>Yêu cầu thanh toán</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-genders'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-genders') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-genders'); ?>" data-ajax="?path=manage-genders">Quản lý giới tính</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['ads-settings'] == 1 || $wo['user']['permission']['manage-site-ads'] == 1 || $wo['user']['permission']['manage-user-ads'] == 1 || $wo['user']['permission']['bank-receipts'] == 1 || $wo['user']['permission']['payment-settings'] == 1 || $wo['user']['permission']['manage-currencies'] == 1))) { ?>
                            <li <?php echo ($page == 'ads-settings' || $page == 'manage-site-ads' || $page == 'manage-user-ads' || $page == 'bank-receipts' || $page == 'payment-settings' || $page == 'manage-currencies') ? 'class="open"' : ''; ?>>
                                <a href="#">
                                    <span class="nav-link-icon">
                                        <svg style="width:24px;height: 24px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                        </svg>

                                    </span>
                                    <span>Thanh toán và quảng cáo</span>
                                </a>
                                <ul class="ml-menu">
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['payment-settings'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'payment-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('payment-settings'); ?>" data-ajax="?path=payment-settings">Cấu hình thanh toán</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['ads-settings'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'ads-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('ads-settings'); ?>" data-ajax="?path=ads-settings">Cài đặt quảng cáo </a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-currencies'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-currencies') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-currencies'); ?>" data-ajax="?path=manage-currencies">Quản lý tiền tệ</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-site-ads'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-site-ads') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-site-ads'); ?>" data-ajax="?path=manage-site-ads">Quản lý quảng cáo của trang</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-user-ads'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-user-ads') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-user-ads'); ?>" data-ajax="?path=manage-user-ads">Quản lý quảng cáo của người dùng</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['bank-receipts'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'bank-receipts') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('bank-receipts'); ?>" data-ajax="?path=bank-receipts">
                                                <span>Quản lý biên lai ngân hàng</span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['pro-settings'] == 1 || $wo['user']['permission']['pro-memebers'] == 1 || $wo['user']['permission']['pro-payments'] == 1 || $wo['user']['permission']['pro-features'] == 1 || $wo['user']['permission']['pro-refund'] == 1))) { ?>
                            <li <?php echo ($page == 'pro-settings' || $page == 'pro-memebers' || $page == 'pro-payments' || $page == 'pro-features' || $page == 'pro-refund') ? 'class="open"' : ''; ?>>
                                <a href="#">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" style="width:24px;height: 24px;" />
                                        </svg>

                                    </span>
                                    <span>Hệ thống Pro</span>
                                </a>
                                <ul class="ml-menu">
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['pro-settings'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'pro-settings') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('pro-settings'); ?>" data-ajax="?path=pro-settings">Cài đặt hệ thống Pro</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['pro-payments'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'pro-payments') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('pro-payments'); ?>" data-ajax="?path=pro-payments">Quản lý thanh toán</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['pro-memebers'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'pro-memebers') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('pro-memebers'); ?>" data-ajax="?path=pro-memebers">Quản lý thành viên</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['pro-refund'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'pro-refund') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('pro-refund'); ?>" data-ajax="?path=pro-refund">Quản lý yêu cầu tài trợ</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>

                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['manage-themes'] == 1 || $wo['user']['permission']['manage-site-design'] == 1 || $wo['user']['permission']['custom-code'] == 1))) { ?>
                            <li <?php echo ($page == 'manage-themes' || $page == 'manage-site-design' || $page == 'custom-code') ? 'class="open"' : ''; ?>>
                                <a href="#">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" style="width:24px;height: 24px;" />
                                        </svg>

                                    </span>
                                    <span>Thiết kế</span>
                                </a>
                                <ul class="ml-menu">
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-themes'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-themes') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-themes'); ?>" data-ajax="?path=manage-themes">Chủ đề</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-site-design'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-site-design') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-site-design'); ?>" data-ajax="?path=manage-site-design">Thay đổi thiết kế trang web</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['custom-code'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'custom-code') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('custom-code'); ?>" data-ajax="?path=custom-code">Tùy chỉnh JS / CSS</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['manage-announcements'] == 1 || $wo['user']['permission']['mass-notifications'] == 1 || $wo['user']['permission']['ban-users'] == 1 || $wo['user']['permission']['generate-sitemap'] == 1 || $wo['user']['permission']['manage-invitation-keys'] == 1 || $wo['user']['permission']['backups'] == 1 || $wo['user']['permission']['auto-delete'] == 1 || $wo['user']['permission']['auto-friend'] == 1 || $wo['user']['permission']['fake-users'] == 1 || $wo['user']['permission']['auto-like'] == 1 || $wo['user']['permission']['auto-join'] == 1 || $wo['user']['permission']['send_email'] == 1 || $wo['user']['permission']['manage-invitation'] == 1))) { ?>
                            <li <?php echo ($page == 'manage-announcements' || $page == 'mass-notifications' || $page == 'ban-users' || $page == 'generate-sitemap' || $page == 'manage-invitation-keys' || $page == 'backups' || $page == 'auto-delete' || $page == 'auto-friend' || $page == 'fake-users' || $page == 'auto-like' || $page == 'auto-join' || $page == 'send_email' || $page == 'manage-invitation') ? 'class="open"' : ''; ?>>
                                <a href="#">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" style="width:24px;height: 24px;" />
                                        </svg>

                                    </span>
                                    <span>Công cụ</span>
                                </a>
                                <ul class="ml-menu">
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage_emails'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage_emails') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage_emails'); ?>" data-ajax="?path=manage_emails">Quản lý Emails</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-invitation'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-invitation') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-invitation'); ?>" data-ajax="?path=manage-invitation">Lời mời người dùng</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['send_email'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'send_email') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('send_email'); ?>" data-ajax="?path=send_email">Gửi E-mail</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-announcements'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-announcements') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-announcements'); ?>" data-ajax="?path=manage-announcements">Thông báo</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['auto-delete'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'auto-delete') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('auto-delete'); ?>" data-ajax="?path=auto-delete">Tự động xóa dữ liệu</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['auto-friend'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'auto-friend') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('auto-friend'); ?>" data-ajax="?path=auto-friend">Tự động kết bạn</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['auto-like'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'auto-like') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('auto-like'); ?>" data-ajax="?path=auto-like">Tự động thích trang</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['auto-join'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'auto-join') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('auto-join'); ?>" data-ajax="?path=auto-join">Tự động tham gia nhóm</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['fake-users'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'fake-users') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('fake-users'); ?>" data-ajax="?path=fake-users">Tạo người dùng giả</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['mass-notifications'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'mass-notifications') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('mass-notifications'); ?>" data-ajax="?path=mass-notifications">Thông báo hàng loạt</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['ban-users'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'ban-users') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('ban-users'); ?>" data-ajax="?path=ban-users">Danh sách đen</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['generate-sitemap'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'generate-sitemap') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('generate-sitemap'); ?>" data-ajax="?path=generate-sitemap">Tạo bản đồ trang web</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-invitation-keys'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-invitation-keys') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-invitation-keys'); ?>" data-ajax="?path=manage-invitation-keys">Mã lời mời</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['backups'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'backups') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('backups'); ?>" data-ajax="?path=backups">Sao lưu SQL & Files</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['edit-terms-pages'] == 1 || $wo['user']['permission']['manage_terms_pages'] == 1 || $wo['user']['permission']['manage-custom-pages'] == 1 || $wo['user']['permission']['add-new-custom-page'] == 1 || $wo['user']['permission']['edit-custom-page'] == 1))) { ?>
                            <li <?php echo ($page == 'edit-terms-pages' || $page == 'manage_terms_pages' || $page == 'manage-custom-pages' || $page == 'add-new-custom-page' || $page == 'edit-custom-page') ? 'class="open"' : ''; ?>>
                                <a href="#">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" style="width:24px;height: 24px;" />
                                        </svg>

                                    </span>
                                    <span>Trang</span>
                                </a>
                                <ul class="ml-menu">
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-custom-pages'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-custom-pages') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-custom-pages'); ?>" data-ajax="?path=manage-custom-pages">Quản lý trang tùy chỉnh</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['edit-terms-pages'] == 1 && $wo['user']['permission']['manage_terms_pages'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage_terms_pages' || $page == 'edit-terms-pages') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage_terms_pages'); ?>" data-ajax="?path=manage_terms_pages">Quản lý trang điều khoản</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['manage-reports'] == 1 || $wo['user']['permission']['user_reports'] == 1))) { ?>
                            <li <?php echo ($page == 'manage-reports' || $page == 'user_reports') ? 'class="open"' : ''; ?>>
                                <a href="#">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" style="width:24px;height: 24px;" />
                                        </svg>

                                    </span>
                                    <span>Báo cáo</span>
                                </a>
                                <ul class="ml-menu">
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-reports'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-reports') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-reports'); ?>" data-ajax="?path=manage-reports">Quản lý báo cáo</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['user_reports'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'user_reports') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('user_reports'); ?>" data-ajax="?path=user_reports">Quản lý báo cáo người dùng</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['verfiy-applications'] == 1 || $wo['user']['permission']['push-notifications-system'] == 1 || $wo['user']['permission']['manage-api-access-keys'] == 1 || $wo['user']['permission']['manage-third-psites'] == 1))) { ?>
                            <li <?php echo ($page == 'verfiy-applications' || $page == 'push-notifications-system' || $page == 'manage-api-access-keys' || $page == 'manage-third-psites') ? 'class="open"' : ''; ?>>
                                <a href="#">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" style="width:24px;height: 24px;" />
                                        </svg>

                                    </span>
                                    <span>Cài đặt API</span>
                                </a>
                                <ul class="ml-menu">
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-api-access-keys'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-api-access-keys') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-api-access-keys'); ?>" data-ajax="?path=manage-api-access-keys">Quản lý khóa máy chủ API</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['push-notifications-system'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'push-notifications-system') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('push-notifications-system'); ?>" data-ajax="?path=push-notifications-system">Cài đặt thông báo đẩy</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['verfiy-applications'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'verfiy-applications') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('verfiy-applications'); ?>" data-ajax="?path=verfiy-applications">Xác thực ứng dụng</a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-third-psites'] == 1)) { ?>
                                        <li>
                                            <a <?php echo ($page == 'manage-third-psites') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-third-psites'); ?>" data-ajax="?path=manage-third-psites">Tập lệnh của bên thứ ba</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['manage-updates'] == 1))) { ?>
                            <!--  <li <?php echo ($page == 'manage-updates') ? 'class="active"' : ''; ?>>
                        <a href="#">
                            <span class="nav-link-icon">
                                <i class="material-icons">cloud_download</i>
                            </span>
                            <span>Updates</span>
                        </a>
                        <ul class="ml-menu">
                            <?php if ($is_admin || ($is_moderoter && $wo['user']['permission']['manage-updates'] == 1)) { ?>
                            <li>
                                <a <?php echo ($page == 'manage-updates') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('manage-updates'); ?>" data-ajax="?path=manage-updates">Updates & Bug Fixes</a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li> -->
                        <?php } ?>
                        <li>
                            <a <?php echo ($page == 'system_status') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('system_status'); ?>" data-ajax="?path=system_status">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" style="width:24px;height: 24px;" />
                                    </svg>

                                </span>
                                <span>Trạng thái hệ thống</span>
                            </a>
                        </li>
                        <?php if ($is_admin || ($is_moderoter && ($wo['user']['permission']['changelog'] == 1))) { ?>
                            <li>
                                <a <?php echo ($page == 'changelog') ? 'class="active"' : ''; ?> href="<?php echo Wo_LoadAdminLinkSettings('changelog'); ?>" data-ajax="?path=changelog">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" style="width:24px;height: 24px;" />
                                        </svg>

                                    </span>
                                    <span>Changelogs</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($is_admin == true) { ?>
                            <li>
                                <a href="http://docs.wowonder.com/#faq" target="_blank">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" style="width:24px;height: 24px;" />
                                        </svg>

                                    </span>
                                    <span>FAQs</span>
                                </a>
                            </li>
                        <?php } ?>
                        <a class="pow_link" href="https://bit.ly/2R2jrcz" target="_blank">
                            <p>Được cung cấp bởi</p>
                            <img src="https://demo.wowonder.com/themes/default/img/logo.png">
                            <b class="badge">v<?php echo $wo['config']['version']; ?></b>
                        </a>
                    </ul>
                </div>
            </div>
            <!-- end::navigation -->

            <!-- Content body -->
            <div class="content-body">
                <!-- Content -->
                <div class="content ">
                    <?php echo $page_loaded; ?>
                </div>
                <!-- ./ Content -->

            </div>
            <!-- ./ Content body -->
        </div>
        <!-- ./ Content wrapper -->
    </div>
    <!-- ./ Layout wrapper -->
    <div class="select_pro_model"></div>
    <script src="<?php echo Wo_LoadAdminLink('vendors/sweetalert/sweetalert.min.js'); ?>"></script>
    <script src="<?php echo (Wo_LoadAdminLink('vendors/select2/js/select2.min.js')) ?>"></script>
    <script src="<?php echo (Wo_LoadAdminLink('assets/js/examples/select2.js')) ?>"></script>
    <script src="<?php echo (Wo_LoadAdminLink('assets/js/app.min.js')) ?>"></script>
    <script type="text/javascript">
        function Wo_SubmitSelectProForm(self) {
            let form_select_pro = $('.SelectProModalForm');
            form_select_pro.ajaxForm({
                url: Wo_Ajax_Requests_File() + '?f=admin_setting&s=select_pro_package',
                beforeSend: function() {
                    form_select_pro.find('.waves-effect').text('Please wait..');
                },
                success: function(data) {
                    form_select_pro.find('.waves-effect').text('Save');
                    $('#SelectProModal').animate({
                        scrollTop: 0 // Scroll to top of body
                    }, 500);
                    if (data.status == 200) {
                        $('#SelectProModalAlert').html(
                            '<div class="alert alert-success"><i class="fa fa-check"></i> Settings updated successfully</div>'
                        );
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        $('#SelectProModalAlert').html('<div class="alert alert-danger">' + data.message +
                            '</div>');
                    }
                }
            });
            form_select_pro.submit();
        }

        function SelectProModel(type, self) {
            if ($(self).val() == 'pro') {
                hash_id = $('#hash_id').val();
                $.get(Wo_Ajax_Requests_File(), {
                    f: 'admin_setting',
                    s: 'select_pro_model',
                    hash_id: hash_id,
                    type: type
                }, function(data) {
                    $('.select_pro_model').html('');
                    $('.select_pro_model').html(data.html);
                    $('#SelectProModal').modal('show');
                });
            }

        }

        function ChangeMode(mode) {
            if (mode == 'day') {
                $('body').removeClass('dark');
                $('.admin_mode').html(
                    '<span id="night-mode-text">Night mode</span><svg class="feather feather-moon float-right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>'
                );
                $('.admin_mode').attr('onclick', "ChangeMode('night')");
            } else {
                $('body').addClass('dark');
                $('.admin_mode').html(
                    '<span id="night-mode-text">Day mode</span><svg class="feather feather-moon float-right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>'
                );
                $('.admin_mode').attr('onclick', "ChangeMode('day')");
            }
            hash_id = $('#hash_id').val();
            $.get(Wo_Ajax_Requests_File(), {
                f: 'admin_setting',
                s: 'change_mode',
                hash_id: hash_id
            }, function(data) {});
        }
        $(document).ready(function() {
            $('[data-toggle="popover"]').popover();
            var hash = $('.main_session').val();
            $.ajaxSetup({
                data: {
                    hash: hash
                },
                cache: false
            });
        });
        $('body').on('click', function(e) {
            $('.dropdown-animating').removeClass('show');
            $('.dropdown-menu').removeClass('show');
        });

        function searchInFiles(keyword) {
            if (keyword.length > 2) {
                $.post(Wo_Ajax_Requests_File() + '?f=admin_setting&s=search_in_pages', {
                    keyword: keyword
                }, function(data, textStatus, xhr) {
                    if (data.html != '') {
                        $('#search_for_bar').html(data.html)
                    } else {
                        $('#search_for_bar').html('')
                    }
                });
            } else {
                $('#search_for_bar').html('')
            }
        }
        jQuery(document).ready(function($) {
            jQuery.fn.highlight = function(str, className) {
                if (str != '') {
                    var aTags = document.getElementsByTagName("h2");
                    var bTags = document.getElementsByTagName("label");
                    var cTags = document.getElementsByTagName("h3");
                    var dTags = document.getElementsByTagName("h6");
                    var searchText = str.toLowerCase();

                    if (aTags.length > 0) {
                        for (var i = 0; i < aTags.length; i++) {
                            var tag_text = aTags[i].textContent.toLowerCase();
                            if (tag_text.indexOf(searchText) != -1) {
                                $(aTags[i]).addClass(className)
                            }
                        }
                    }

                    if (bTags.length > 0) {
                        for (var i = 0; i < bTags.length; i++) {
                            var tag_text = bTags[i].textContent.toLowerCase();
                            if (tag_text.indexOf(searchText) != -1) {
                                $(bTags[i]).addClass(className)
                            }
                        }
                    }

                    if (cTags.length > 0) {
                        for (var i = 0; i < cTags.length; i++) {
                            var tag_text = cTags[i].textContent.toLowerCase();
                            if (tag_text.indexOf(searchText) != -1) {
                                $(cTags[i]).addClass(className)
                            }
                        }
                    }

                    if (dTags.length > 0) {
                        for (var i = 0; i < dTags.length; i++) {
                            var tag_text = dTags[i].textContent.toLowerCase();
                            if (tag_text.indexOf(searchText) != -1) {
                                $(dTags[i]).addClass(className)
                            }
                        }
                    }
                }
            };
            jQuery.fn.highlight("<?php echo (!empty($_GET['highlight']) ? $_GET['highlight'] : '') ?>",
                'highlight_text');
            $.get(Wo_Ajax_Requests_File(), {
                f: 'admin_setting',
                s: 'exchange'
            });
        });
        $(document).on('click', '#search_for_bar a', function(event) {
            event.preventDefault();
            location.href = $(this).attr('href');
        });

        function ReadNotify() {
            hash_id = $('#hash_id').val();
            $.get(Wo_Ajax_Requests_File(), {
                f: 'admin_setting',
                s: 'ReadNotify',
                hash_id: hash_id
            });
            location.reload();
        }

        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this,
                    args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function() {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }
        let container_fluid_height = $('.container-fluid').height();

        setInterval(function() {
            if (container_fluid_height != $('.container-fluid').height()) {
                container_fluid_height = $('.container-fluid').height();
                $(".content").getNiceScroll().resize();
            }
        }, 500);
    </script>

</body>

</html>