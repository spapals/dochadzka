<?
session_start();
if ($_SESSION) {
    header('Location: /home');
    die();
}
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', TRUE);
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dochádzkový systém - DIAGO SF s.r.o.</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
    <link rel="stylesheet" href="css/style.blue.css" id="theme-stylesheet">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="shortcut icon" href="img/favicon.ico">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>
<body>
<div class="login-page">
    <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">
            <div class="row">
                <div class="col-lg-6">
                    <div class="info d-flex align-items-center">
                        <div class="content">
                            <div class="logo">
                                <h1>Dochádzkový systém</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form d-flex align-items-center">
                        <div class="content">
                            <form method="post" class="form-validate mb-4">
                                <div class="form-group">
                                    <input id="login-username" type="text" name="user" required
                                           data-msg="Prosím zadajte prihl. meno" class="input-material">
                                    <label for="login-username" class="label-material">Prihl. meno</label>
                                </div>
                                <div class="form-group">
                                    <input id="login-password" type="password" name="pass" required
                                           data-msg="Prosím zadajte heslo" class="input-material">
                                    <label for="login-password" class="label-material">Heslo</label>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary">Prihlásenie</button>
                            </form>
                            <?
                            if (isset($_POST["submit"])) {
                                if (!empty($_POST['user']) && !empty($_POST['pass'])) {
                                    if (login($_POST)) {
                                        header("Location: /home");
                                    } else {
                                        echo '<div class="alert alert-danger" role="alert">
                                            Prihlasovacie údaje nie sú správne!
                                        </div>';
                                    }
                                } else {
                                    echo "Všetky polia su povinné!";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyrights text-center">
        <p class="no-margin-bottom"><?= date('Y') ?> &copy; DIAGO SF s.r.o. </p>
    </div>
</div>
<!-- JavaScript files-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/popper.js/umd/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/jquery.cookie/jquery.cookie.js"></script>
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="js/front.js"></script>
</body>
</html>