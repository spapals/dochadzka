<?
require_once 'functions.php';
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', FALSE);
$s = $_GET['s'];
$page = $_GET['p'];
if (!$s) {
    $s = '0';
}
if (!$p) {
    $p = '0';
}
$prev = $page - 1;
$next = $page + 1;
$totalPages = count_requests($s);
?>
<!DOCTYPE html>
<html>
<? require_once __DIR__ . '/inc/header.php';
if (!$_SESSION['is_admin']) {
    header("Location: $url");
} ?>
<div class="d-flex align-items-stretch">
    <? require_once __DIR__ . '/inc/sidebar.php'; ?>
    <div class="page-content">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Požiadavky</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Domov</a></li>
                <li class="breadcrumb-item active">Požiadavky</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row px-0">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title"><strong>Na schválenie</strong></div>
                            <?
                            $uid = $_POST['uid'];
                            $id = $_POST['id'];
                            if (isset ($_POST['approve'])) {
                                req_status_update($id, 1);
                                sendMessage($uid, 'ok');
                            }
                            if (isset ($_POST['deny'])) {
                                req_status_update($id, 2);
                                sendMessage($uid, 'no');
                            }
                            if (isset ($_POST['delete'])) {
                                req_status_update($id, 3);
                            }
                            ?>
                            <div class="mb-2">
                                <a href="<?= $url ?>requests/0/1" role="button" class="btn btn-warning btn-sm">Čakajúce <span class="badge badge-light"><?= alerts(0) ?></span></a>
                                <a href="<?= $url; ?>requests/1/1" role="button" class="btn btn-success btn-sm">Schválené <span class="badge badge-light"><?= alerts(1) ?></span></a>
                                <a href="<?= $url; ?>requests/2/1"/ role="button" class="btn btn-danger btn-sm">Neschválené <span class="badge badge-light"><?= alerts(2) ?></span></a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm requests-table">
                                    <thead>
                                    <tr>
                                        <th>Meno</th>
                                        <th>Typ</th>
                                        <th>Dátum</th>
                                        <th>Dni</th>
                                        <th>Stav</th>
                                        <th class="d-none d-sm-block">Dátum pridania</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?
                                    $ord = $s == '0' ? 'ASC' : 'DESC';
                                    foreach (requests($s, $ord, $page) as $req) {
                                        $id = $req['uid'];
                                        $type = $req['type'];
                                        $tip = $req['note'] ? ' <i class="fad fa-info-circle" data-toggle="tooltip" data-placement="top" title="' . $req['note'] . '"></i>' : '';
                                        $app_by = $req['approved_by'] ? ' <i class="fad fa-info-circle" data-toggle="tooltip" data-placement="top" title="' . name($req['approved_by']) . '"></i>' : '';
                                        echo '<tr>';
                                        echo '<td><a href="' . $url . 'zamestnanci/' . $id . '/' . date('m') . '/' . date('Y') . '">' . name($req['uid']) . '</a></td>';
                                        echo '<td>' . d_type($type) . $tip . '</td>';
                                        if ($type == 'H' || $type == 'SC' || $type == 'L' || $type == 'LC' || $type == 'PD') {
                                            echo '<td>' . req_date(nd($req['date']), nd($req['date_to'])) . ' - (' . $req['time'] . ' - ' . $req['time_to'] . ')' . '</td>';
                                        } else {
                                            echo '<td>' . req_date(nd($req['date']), nd($req['date_to'])) . '</td>';
                                        }
                                        echo '<td class="text-break">' . business_days_diff($req['date'], $req['date_to']) . '</td>';
                                        echo '<td>' . status($req['status']) . $app_by . '</td>';
                                        echo '<td class="d-none d-sm-block">' . td($req['added']) . '</td>';
                                        echo '<td class="text-center">
                                        <form method="post" class="form-horizontal">
                                        <input type="hidden" name="id" class="hidden" value="' . $req['id'] . '">                               
                                        <input type="hidden" name="uid" class="hidden" value="' . $req['uid'] . '">                               
                                        <button type="submit" class="btn btn-success btn-sm" name="approve"><span class="d-none d-sm-block">Schváliť</span><i class="d-block d-sm-none fad fa-check"></i></a>
                                        <button type="submit" class="btn btn-danger btn-sm ml-lg-1" name="deny"><span class="d-none d-sm-block">Zamietnuť</span><i class="d-block d-sm-none fad fa-times"></i></a>
                                        <button type="submit" class="btn btn-warning btn-sm ml-lg-1" name="delete"><span class="d-none d-sm-block">Zmazať</span><i class="d-block d-sm-none fad fa-minus"></i></a>
                                        </form>
                                        </td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation example mt-5">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                                            <a class="page-link"
                                               href="<?php if($page <= 1){ echo '#'; } else { echo $prev; } ?>">Predch.</a>
                                        </li>
                                        <?php for($i = 1; $i <= $totalPages; $i++ ): ?>
                                            <?php if($page < $i+4 && $page > $i-4) { ?>
                                                <li class="page-item <?php if($page == $i) {echo 'active'; } ?>">
                                                    <a class="page-link" href="<?= $i; ?>"> <?= $i; ?> </a>
                                                </li>
                                            <? } ?>

                                        <?php endfor; ?>
                                        <li class="page-item <?php if($page >= $totalPages) { echo 'disabled'; } ?>">
                                            <a class="page-link"
                                               href="<?php if($page >= $totalPages){ echo '#'; } else {echo $next; } ?>">Ďalšie</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
</section>
<? require_once __DIR__ . '/inc/footer.php'; ?>
</body>
</html>
