<?
require_once 'functions.php';
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', TRUE);
?>
<!DOCTYPE html>
<html>
<? require_once __DIR__ . '/inc/header.php';
$id = $_GET['id'];
$m = (!$m) ? $m = date('m') : $_GET['m'];
if (!$_SESSION['is_admin']) {
    header("Location: $url");
}
$mo = date('m');
?>
<div class="d-flex align-items-stretch">
    <? require_once __DIR__ . '/inc/sidebar.php'; ?>
    <div class="page-content">
        <div class="page-header no-margin-bottom d-print-none">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Zamestnanci</h2>
            </div>
        </div>
        <div class="container-fluid d-print-none">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Domov</a></li>
                <li class="breadcrumb-item active">Zamestnanci</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <?
                            if (isset ($_POST['delete'])) {
                                $uid = $_POST['uid'];
                                $sql = "DELETE FROM `attendance_test` WHERE `uid`='$uid'";
                                mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
                                echo '<div class="alert alert-danger" role="alert">Záznam bol vymazaný.</div>';
                            }
                            if ($id) { ?>
                            <div class="title"><strong>Prehľad hodín - <?= name($id) ?></strong></div>
                            <div class="row months mb-2 float-right d-print-none pr-4">
                                <? for ($i = -3; $i <= 0; $i++) { ?>
                                    <a href="<?= $url . 'zamestnanci/' . $id . '/'; ?><?= date('m', strtotime("$i month")) ?>"
                                       role="button"
                                       class="btn btn-primary ml-1"><?= svk(date('M', strtotime("$i month"))) ?></a>
                                <? } ?>
                            </div>

                            <p>
                                <a class="btn btn-primary d-print-none" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Zobraziť požiadavky</a>
                            </p>
                            <div class="row">
                                <div class="col">
                                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                                        <table class="table table-striped mb-2 d-print-none">
                                            <thead>
                                            <?
                                            $rq = db_get("requests WHERE uid='$id'", "added");
                                            if ($rq) { ?>
                                            <tr>
                                                <th>Typ</th>
                                                <th>Dátum</th>
                                                <th>Dni</th>
                                                <th>Stav</th>
                                                <th class="d-none d-sm-block">Dátum pridania</th>
                                            </tr>
                                            </thead>
                                            <? foreach ($rq as $req) {
                                                $rm = explode('-', $req['date']);
                                                if ($req['uid'] == $id && $rm[1] == $m) {
                                                    $app_by = $req['approved_by'] ? ' <i class="fad fa-info-circle" data-toggle="tooltip" data-placement="top" title="' . name($req['approved_by']) . '"></i>' : '';
                                                    $tip = $req['note'] ? ' <i class="fad fa-info-circle" data-toggle="tooltip" data-placement="top" title="' . $req['note'] . '"></i>' : '';
                                                    echo '<tr>';
                                                    echo '<td>' . d_type($req['type']) . $tip . '</td>';
                                                    if ($req['type'] == 'H') {
                                                        echo '<td>' . req_date(nd($req['date']), nd($req['date_to'])) . ' - (' . $req['time'] . ' - ' . $req['time_to'] . ')' . '</td>';
                                                    } else {
                                                        echo '<td>' . req_date(nd($req['date']), nd($req['date_to'])) . '</td>';
                                                    }
                                                    echo '<td class="text-break">' . business_days_diff($req['date'], $req['date_to']) . '</td>';
                                                    echo '<td>' . status($req['status']) . $app_by . '</td>';
                                                    echo '<td class="d-none d-sm-block">' . td($req['added']) . '</td>';
                                                    echo '</tr>';
                                                }
                                            }
                                            } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">

                                <table id="dochadzka-zamestnanci" class="table table-striped table-sm">
                                    <thead>
                                    <tr>
                                        <th>Deň</th>
                                        <th>Príchod</th>
                                        <th>Odchod</th>
                                        <th>Typ</th>
                                        <th>Poznámka</th>
                                        <th>Hodiny</th>
                                        <th class="d-none d-sm-inline-block d-print-none">Úprava</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?
                                    $table = 'attendance_test';
                                    $wds = [];
                                    foreach (group_by(att_get($table, 'day ASC, start ASC', $id, $m), array('id', 'day', 'month', 'year')) as $att) {
                                        $b = ($count % 2 == 1) ? ('bg-secondary') : ('bg-light');
                                        $date = $att[0]['day'] . '.' . $att[0]['month'] . '.' . $att[0]['year'];
                                        $dt = $att[0]['day'] . '.' . $att[0]['month'] . '<br>' . $att[0]['year'];
                                        echo '<tr>';
                                        echo (!in_array($date, wd($m, date('Y')))) ? '<td rowspan="' . count($att) . '" class="' . $b . ' text-center dashtext-4">' . $dt . '</td>' : '<td rowspan="' . count($att) . '" class="' . $b . ' text-center">' . $dt . '</td>';
                                        foreach ($att as $g) {
                                            $attid = $g['uid'];
                                            $t = $g['type'];
                                            $b = ($count % 2 == 1) ? ('bg-secondary') : ('bg-light');
                                            if ($date != today() && $g['hours'] < 0.1) {
                                                $b = 'bg-danger';
                                            }
                                            echo '<td class="' . $b . '">' . $g['start'] . '</td>';
                                            echo '<td class="' . $b . '">' . $g['end'] . '</td>';
                                            echo '<td class="' . $b . '">' . d_type($t) . '</td>';
                                            echo '<td class="' . $b . '">' . $g['note'] . '</td>';
                                            echo '<td class="' . $b . '">' . $g['hours_full'] . '</td>';
                                            echo '<td class="' . $b . ' d-print-none">' . '
                                        <a href="https://dochadzka.diago.sk/hodiny-edit/' . $g['uid'] . '" role="button" class="btn btn-success btn-sm float-left" name="edit"><span class="d-none d-sm-block mr-1">Upraviť</span>
                                        <i class="d-block d-sm-none fas fa-edit"></i></a>
                                        <form action="" method="post">
                                        <input type="hidden" name="uid" class="hidden" value="' . $attid . '">
                                        <button name="delete" class="btn btn-danger btn-sm"><span class="d-none d-sm-block ml-1">Zmazať</span>
                                        <i class="d-block d-sm-none fas fa-times"></i></button>
                                        </form>
                                            ' . '</td>';
                                            echo '</tr>';
                                        }
                                        foreach ($att as $g) {
                                            $h = $g['hours'];
                                            $t = $g['type'];
                                            ($t == 'O' || $t == 'OA') ? ($h_spolu -= $h) : ($h_spolu += $h);
                                            ($t == 'SU' || $t == 'SC' || $t == 'SC1' || $t == 'SO') ? ($h_spolu -= $h) : ('');
                                            ($t == 'O' || $t == 'OA') ? ($o += $h) : ('');
                                            ($t == 'L') ? ($l += $h) : ('');
                                            ($t == 'D') ? ($dov += $h) : ('');
                                            ($t == 'CH') ? ($ch += $h) : ('');
                                            ($t == 'LC') ? ($lc += $h) : ('');
                                            ($t == 'SO') ? ($so += $h) : ('');
                                            ($t == 'SU') ? ($su += $h) : ('');
                                            ($t == 'H') ? ($wds[] = $g['day']) : ('');
                                        }
                                        $count++;
                                    }
                                    $wd = count(array_unique($wds));
                                    $trd = '<tr class="dashtext-3"><td colspan="5">';
                                    $tt = (($h_spolu - wh_month($y, $m)) < 0) ? 'Chýbajúci čas' : 'Nadčas';
                                    $t_wh = wh_month($y, $m) - wh_diff_today($y, $m, $d) - $h_spolu;
                                    echo '</tbody><tbody class="table-striped mt-4">';
                                    echo $trd . 'Spolu</td><td colspan="2">' . ht($h_spolu) . '</td></tr>';
                                    echo $trd . 'Mesačný časový fond</td><td colspan="2">' . wh_month($y, $m) . 'h</td></tr>';
                                    echo $trd . $tt . '</td><td colspan="2">' . ht(abs(($h_spolu - wh_month($y, $m)))) . '</td></tr>';
                                    echo ($m == $mo) ? ($trd . 'Rozdiel proti časovému fondu k aktuálnemu dňu</td><td colspan="2">' . diff($t_wh) . ht(abs($t_wh)) . '</td></tr>') : ('');
                                    echo $trd . 'Počet pracovných dní</td><td colspan="2">' . wd_month($y, $m) . ' dní</td></tr>';
                                    echo $trd . 'Počet odpracovaných dní</td><td colspan="2">' . $wd . ' dní</td></tr>';
                                    echo '<tr><td colspan="7"></td></tr>';
                                    echo ($o) ? ($trd . 'Obed</td><td colspan="2">' . ht($o) . '</td></tr>') : ('');
                                    echo ($sc) ? ($trd . 'Služobná cesta</td><td colspan="2">' . ht($sc) . '</td></tr>') : ('');
                                    echo ($so) ? ($trd . 'Služobný odchod</td><td colspan="2">' . ht($so) . '</td></tr>') : ('');
                                    echo ($su) ? ($trd . 'Súkromný odchod</td><td colspan="2">' . ht($su) . '</td></tr>') : ('');
                                    echo ($l) ? ($trd . 'Návšteva lekára</td><td colspan="2">' . ht($l) . '</td></tr>') : ('');
                                    echo ($dov) ? ($trd . 'Dovolenka</td><td colspan="2">' . htd($dov) . '</td></tr>') : ('');
                                    echo ($ch) ? ($trd . 'Choroba</td><td colspan="2">' . htd($ch) . '</td></tr>') : ('');
                                    echo ($lc) ? ($trd . 'Návšteva lekára s čl. rodiny</td><td colspan="2">' . ht($lc) . '</td></tr>') : ('');

                                    } else { ?>
                                    <div class="title"><strong>Prehľad mesiaca</strong></div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm zamestnanci-table">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Meno</th>
                                                <th>Priezvisko</th>
                                                <th>Prihl. meno</th>
                                                <th class="d-none d-sm-block">Posl. prihlásenie</th>
                                            </tr>
                                            </thead>
                                                <?
                                                $table = 'attendance_test';
                                                $wds = [];
                                                foreach (group_by(att_get($table, 'day ASC, start ASC', $id, $m), array('id', 'day', 'month', 'year')) as $att) {
                                                    foreach ($att as $g) {
                                                        $h = $g['hours'];
                                                        $t = $g['type'];
                                                        ($t == 'O' || $t == 'OA') ? ($h_spolu -= $h) : ($h_spolu += $h);
                                                        ($t == 'SU' || $t == 'SC' || $t == 'SC1' || $t == 'SO') ? ($h_spolu -= $h) : ('');
                                                        ($t == 'O' || $t == 'OA') ? ($o += $h) : ('');
                                                        ($t == 'L') ? ($l += $h) : ('');
                                                        ($t == 'D') ? ($dov += $h) : ('');
                                                        ($t == 'CH') ? ($ch += $h) : ('');
                                                        ($t == 'LC') ? ($lc += $h) : ('');
                                                        ($t == 'SO') ? ($so += $h) : ('');
                                                        ($t == 'SU') ? ($su += $h) : ('');
                                                        ($t == 'H') ? ($wds[] = $g['day']) : ('');
                                                    }
                                                }

                                                $i = 0;
                                                foreach (db_get('employees', 'surname') as $emp) {
                                                    echo "<tr data-href='$url" . 'zamestnanci/' . $emp['id'] . '/' . date('m') . "'>";
                                                    echo '<th>' . $emp['id'] . '</th>';
                                                    echo '<td>' . $emp['name'] . '</td>';
                                                    echo '<td>' . $emp['surname'] . '</td>';
                                                    echo '<td>' . '@' . $emp['login'] . ht($so) . '</td>';
                                                    echo '</tr>';
                                                    $i++;
                                                }
                                                }

                                                ?>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-sm">
                                                        <tr>
                                                            <th>Počet zamestnancov: <?= $i ?></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                                </tbody>
                                        </table>
                                        <?
                                        /*foreach (db_get('employees', 'surname') as $emp) {
                                            $sur = strtolower(removeAccents($emp['surname']));
                                            $nam = strtolower(removeAccents($emp['name']));
                                            $pass = generate_password($sur);
                                            $cpass = bcrypt($pass);
                                            $id = $emp['id'];
                                            echo $emp['name'] . ' ' . $emp['surname'] . ' - ' . $nam[0] . $sur . ' - ' . ucfirst($pass) . '<br>';
                                            $sql = "UPDATE `employees` SET `password`='$cpass' WHERE `id`='$id'";
                                            //mysqli_query($dbc, $sql) or die("Error: " . mysqli_error($dbc));
                                        }*/
                                        ?>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <? require_once __DIR__ . '/inc/footer.php'; ?>
        </body>
</html>
