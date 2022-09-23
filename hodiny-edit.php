<?
require_once 'functions.php';
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', TRUE);
$attid = $_GET['id'];
?>
<!DOCTYPE html>
<html>
<? require_once __DIR__ . '/inc/header.php';
?>
<div class="d-flex align-items-stretch">
    <? require_once __DIR__ . '/inc/sidebar.php'; ?>
    <div class="page-content">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Úprava dochádzky</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Domov</a> / <a href="/hodiny/<?=date('m') . '/' . date('Y')?>">Dochádzka</a></li>
                <li class="breadcrumb-item active">Úprava dochádzky</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <?
                            if (isset ($_POST['edit'])) {
                                $uid = $_POST['uid'];
                                $start = $_POST['start'];
                                $end = $_POST['end'];
                                $note = $_POST['note'];
                                $total = ((strtotime($end) - strtotime($start)) / 60);
                                $oh = sprintf("%02dh %02dm", floor($total / 60), $total % 60);
                                $h = r((strtotime($end) - strtotime($start)) / 3600);
                                if ($h < 0) {
                                    $h = '0';
                                    $oh = '00h 00m';
                                }
                                $sql = "UPDATE `attendance_test` SET `start` = '$start', `end` = '$end', `hours` = '$h', `hours_full` = '$oh', `note` = '$note', `edited` = now() WHERE `uid` = '$uid'";
                                mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
                                echo '<div class="alert alert-success" role="alert">Dochádzka bola úspešne upravená.</div>';
                            }
                            ?>

                            <? foreach (att_edit($attid) as $item) {
                                if ($item['id'] == $_SESSION['id'] || $_SESSION['is_admin']) { ?>
                                    <h4><? echo d_type($item['type']) . ' - ' . $item['day'] . '.' . $item['month'] . '.' . $item['year'] . ' (' . $item['hours_full'] . ')' ?> </h4>
                                    <form method="post" class="form-horizontal mt-4">
                                        <div class="form-group row">
                                            <label class="col-sm-2" for="poznamka">Príchod</label>
                                            <input type="time" class="col-sm-3 form-control" name="start" value="<?= $item['start']; ?>">
                                        </div>
                                        <div class="form-group row mb-4">
                                            <label class="col-sm-2" for="poznamka">Odchod</label>
                                            <input type="time" class="col-sm-3 form-control" name="end" value="<?= $item['end']; ?>">
                                        </div>
                                        <div class="form-group row mb-4">
                                            <label class="col-sm-2" for="poznamka">Poznámka</label>
                                            <textarea class="col-sm-3 form-control" rows="3" name="note"><?= $item['note']; ?></textarea>
                                        </div>
                                        <input type="hidden" name="uid" class="hidden" value="<?= $attid; ?>">
                                        <button type="submit" name="edit" class="btn btn-primary">Upraviť</button>
                                    </form>
                                <? } else {
                                    echo 'Co skúšááááš, jak skúšááááš???';
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    <? require_once __DIR__ . '/inc/footer.php'; ?>
    </body>
</html>
