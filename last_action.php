<?
ini_set('error_reporting', E_ALL);
ini_set('display_errors', FALSE);
require_once 'functions.php';
$m = (!$m) ? $m = date('m') : $_GET['m'];
$d = date('d');
?>

<!DOCTYPE html>
<html>
<? require_once __DIR__ . '/inc/header.php'; ?>
<div class="d-flex align-items-stretch">
    <? require_once __DIR__ . '/inc/sidebar.php'; ?>
    <div class="page-content">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Posledné udalosti</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Domov</a></li>
                <li class="breadcrumb-item active">Posledné udalosti</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="block">
                    <div class="row">
                        <? foreach (last_action(date('d.m.Y')) as $att) { ?>
                            <div class="card mb-1 col-6 form-group">
                                <div class="row no-gutters">
                                    <span class="col-md-4 h5 mt-lg-4 mt-2">
                                    <a href="<?= $url . 'zamestnanci/' . $att['id'] . '/'.$m . '/' . date('Y') ?>"><?= name($att['id']) ?></a>
                                    </span>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= last_action_type($att['uid'], 1) ?></h5>
                                            <p class="card-text"><small class="text-muted"><?= ($att['end']) ? ($att['start'] . ' - ' . $att['end']) : ($att['start']) ?><?= ($att['hours']>0) ? (' ('.$att['hours_full'].')') : ('') ?></small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <? }?>
                    </div>
                </div>
            </div>
        </section>
        <? require_once __DIR__ . '/inc/footer.php';
        echo '<pre>';
        //$g = group_by(att_get($table, 'day ASC, type ASC', $_SESSION['id'], $m), array('id', 'day', 'month', 'year'));
        //print_r($ids);
        echo '</pre>';
        ?>
        </body>
</html>
