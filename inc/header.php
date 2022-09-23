<?
session_start();
if (!isset($_SESSION["sess_user"])) {
    header("Location: $url");
}
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', TRUE);
require_once('functions.php');
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DIAGO SF s.r.o.</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="<?= $url; ?>vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
    <link rel="stylesheet" href="<?= $url; ?>css/all.min.css">
    <link rel="stylesheet" href="<?= $url; ?>css/font.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
    <link rel="stylesheet" href="<?= $url; ?>css/style.blue.css?v=6" id="theme-stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-html5-1.6.1/b-print-1.6.1/r-2.2.3/datatables.min.css"/>
    <link rel="stylesheet" href="<?= $url; ?>css/custom.css?v=4">
    <link rel="shortcut icon" href="<?= $url; ?>img/favicon.ico">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script>
        var uid = "<? echo $_SESSION['id'] ?>";
        var OneSignal = OneSignal || [];
        OneSignal.push(["init", {
            appId: "335dbc82-ddd3-41b4-9fcb-e5d5ad832d44",
        }]);
        OneSignal.push(function () {
            OneSignal.setExternalUserId(uid);
            OneSignal.getUserId(function (userId) {
                $.post("/inc/alert.php", {
                    'uid': uid,
                    'eid': userId,
                });
            });
        });
    </script>
</head>
<body>
<header class="header">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="navbar-header">
                <a href="<?= $url; ?>" class="navbar-brand">
                    <div class="brand-text brand-big visible text-uppercase"><strong
                                class="text-primary">DOCHÁDZKA</strong></div>
                    <div class="brand-text brand-sm"><strong class="text-primary">D</strong><strong>A</strong></div>
                </a>
                <button class="sidebar-toggle"><i class="fas fa-bars"></i></button> <!-- animated infinite tada -->
            </div>
            <? if ($_SESSION['is_admin'] == 1){ ?>
            <div class="right-menu list-inline no-margin-bottom">
                <div class="list-inline-item dropdown"><a id="requests" href="#" data-toggle="dropdown"
                                                          aria-haspopup="true" aria-expanded="false"
                                                          class="nav-link messages-toggle"><i class="fad fa-envelope"></i><span class="badge dashbg-1"><?= alerts(0); ?></span></a>
                    <div aria-labelledby="requests" class="dropdown-menu tasks-list">
                        <? foreach (limit(get_alerts(0), 5) as $alert) { ?>
                            <a href="#" class="dropdown-item message d-flex align-items-center">
                                <div class="content"><strong class="d-block"><?= name($alert['uid']); ?></strong><span
                                            class="d-block"><?= d_type($alert['type']); ?></span><small
                                            class="date d-block"><?= req_date(nd($alert['date']), nd($alert['date_to'])) ?></small>
                                </div>
                            </a>
                        <? } ?>
                        </a><a href="<?= $url ?>requests" class="dropdown-item text-center message"> <strong>Zobraziť
                                všetky <i class="fas fa-angle-right"></i></strong></a></div>
                </div>
                <? } ?>
                <div class="list-inline-item logout"><a id="user" href="<?= $url ?>user" class="nav-link"><i class="fad fa-user"></i></a></div>
                <div class="list-inline-item logout"><a id="logout" href="<?= $url ?>logout" class="nav-link"> <span
                                class="d-none d-sm-inline">Odhlásenie </span><i class="fad fa-sign-out-alt"></i></a></div>
            </div>
        </div>
    </nav>
</header>