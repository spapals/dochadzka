<?
require_once 'functions.php';
require_once 'Mobile_Detect.php';
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', TRUE);
?>
<!DOCTYPE html>
<html>
<? require_once __DIR__ . '/inc/header.php'; ?>
<div class="d-flex align-items-stretch">
    <? require_once __DIR__ . '/inc/sidebar.php'; ?>
    <div class="page-content">
        <?
        if ($_POST) {
            $id = $_SESSION['id'];
            $type = $_POST['type'];
            $time = $_POST['time'];
            if (isset($_POST['hp'])) {
                $t = 'H';
            } elseif (isset($_POST['obed'])) {
                $t = 'O';
            } elseif (isset($_POST['lekar'])) {
                $t = 'L';
            } elseif (isset($_POST['lcl'])) {
                $t = 'LC';
            } elseif (isset($_POST['sukr-odchod'])) {
                $t = 'SU';
            } elseif (isset($_POST['sluz-odchod'])) {
                $t = 'SO';
            } elseif (isset($_POST['sluz-cesta'])) {
                $t = 'SC';
            } elseif (isset($_POST['sc1'])) {
                $t = 'SC1';
            } elseif (isset($_POST['ocr'])) {
                $t = 'OC';
            } elseif (isset($_POST['paragraf'])) {
                $t = 'P';
            } elseif (isset($_POST['fa'])) {
                $t = 'FA';
            }
            echo '<script>' . att_alert($id, $t) . '</script>';
            if (isset($time)) {
                add_att($id, $type, $time);
            }
        }

        $detect = new Mobile_Detect;
        if ($detect->isMobile() ) {
            //echo 'MMM';
        }
        ?>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="block">
                            <div class="w-100" role="group">
                                <div class="container mb-4">
                                    <div class="row text-center aaa">
                                        <div class="container-fluid col-sm-12 col-lg-4 text-center">
                                            <div class="container-fluid shadow-sm mb-2">
                                                <? echo work($_SESSION['id']) ?>
                                            </div>
                                            <div class="col-12 font-weight-bold shadow-sm">
                                                <span>Posledná akcia<br>
                                                <? echo last_action_type($_SESSION['id']) ?><br>
                                                    <span><? echo lat($_SESSION['id']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form method="post">
                                    <div class="btn-group form-group w-100 mb-1">
                                        <button type="submit" name="hp"
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <h5><?= work_inout($_SESSION['id']) ?></h5>
                                        </button>
                                        <button type="submit" name="obed"
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <i class="fad fa-utensils-alt fa-3x"></i>
                                            <h5>Obed</h5>
                                        </button>
                                        <button type="submit" name="lekar"
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <i class="fad fa-user-md fa-3x"></i>
                                            <h5>Návšteva lekára</h5>
                                        </button>
                                    </div>
                                    <div class="btn-group form-group w-100 mb-1">
                                        <button type="submit" name="lcl"
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <i class="fad fa-users-medical fa-3x"></i>
                                            <h5>Návšteva lekára s čl. rodiny</h5>
                                        </button>
                                        <button type="submit" name="sluz-odchod"
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <i class="fad fa-briefcase fa-3x"></i>
                                            <h5>Služobný odchod</h5>
                                        </button>
                                        <button type="submit" name="sluz-cesta"
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <i class="fad fa-suitcase-rolling fa-3x"></i>
                                            <h5>Služobná cesta</h5>
                                        </button>
                                    </div>
                                    <div class="btn-group form-group w-100 mb-1">
                                        <button type="submit" name="sc1"
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <i class="fad fa-suitcase-rolling fa-3x"></i>
                                            <h5>Služobná cesta 1 deň</h5>
                                        </button>
                                        <button type="submit" name="sukr-odchod"
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <i class="fa-solid fa-house-person-leave fa-3x"></i>
                                            <h5>Súkromný odchod</h5>
                                        </button>
                                        <button type="submit" name="ocr"
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <i class="fad fa-users-medical fa-3x"></i>
                                            <h5>OČR</h5>
                                        </button>
                                    </div>
                                    <div class="btn-group form-group w-100 mb-1 mb-4">
                                        <button type="submit" name="paragraf"
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <i class="fa-paragraph fa-2x"></i>
                                            <h5>Paragraf</h5>
                                        </button>
                                        <button type="submit" name="fa"
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <i class="fad fa-smoking fa-3x"></i>
                                            <h5>Fajčiarska prestávka</h5>
                                        </button>
                                        <button type="" name=""
                                                class="col-4 btn shadow-sm my-lg-1 mx-lg-1 text-info">
                                            <? if ($_SESSION['is_admin']) { ?>
                                                <i class="fad fa-chevron-double-right fa-3x"></i>
                                                <h5>Automaticky</h5>
                                            <? } ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    <? require_once __DIR__ . '/inc/footer.php'; ?>
    <script>
        $.livetime.options.serverTimeUrl = '/empty.txt';
        $("time.timeago").livetime();
    </script>
    </body>
</html>
