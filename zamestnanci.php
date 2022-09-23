<?
require_once 'functions.php';
?>
<!DOCTYPE html>
<html>
<? require_once __DIR__ . '/inc/header.php';
$id = $_GET['id'];
$year = $_GET['y'];
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
                <li class="breadcrumb-item"><a href="/">Domov</a></li>
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
                            <div class="title"><strong>Prehľad hodín - <?= $m . '/' . $year ?> - <?= name($id) ?> <a href="<?= $url . 'user-edit/' . $id ?>" role="button" class="btn btn-primary btn-sm ml-1 d-print-none" ?>Upraviť</a></strong></div>
                            <div class="row months mb-2 float-right d-print-none pr-4">
                                <? foreach (generate_months_back() as $item) {
                                    $month = $item['month'];
                                    $y = $item['year']; ?>
                                    <a href="<?= $url . 'zamestnanci/' . $id . '/' . $month . '/' . $y ?>" role="button" class="btn btn-primary btn-sm ml-1"><?= short_month($month) ?></a>
                                    <? }?>
                            </div>
                            <? $rq = db_get("requests WHERE uid='$id' AND `date` LIKE '$year-$m%'", "added");
                            if ($rq) { ?>
                                <p>
                                    <a class="btn btn-primary btn-sm d-print-none" data-toggle="collapse" href="#requests" role="button" aria-expanded="false" aria-controls="requests">Zobraziť požiadavky</a>
                                </p>
                            <? } ?>
                            <div class="row">
                                <div class="col">
                                    <div class="collapse multi-collapse" id="requests">
                                        <table class="table table-striped mb-2 d-print-none">
                                            <thead>
                                            <?
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
                                            } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">

                                <table class="table table-striped table-sm dochadzka-zamestnanci">
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
                                        if ($att[0]['year'] === $year) {
                                            $b = ($count % 2 == 1) ? ('bg-secondary') : ('bg-light');
                                            $date = $att[0]['day'] . '.' . $att[0]['month'] . '.' . $att[0]['year'];
                                            $dt = $att[0]['day'] . '.' . $att[0]['month'] . '<br>' . $att[0]['year'];
                                            echo '<tr>';
                                            echo (!in_array($date, wd($m, $year))) ? '<td rowspan="' . count($att) . '" class="' . $b . ' text-center dashtext-4">' . $dt . '</td>' : '<td rowspan="' . count($att) . '" class="' . $b . ' text-center">' . $dt . '</td>';
                                            foreach ($att as $g) {
                                                /*$fhf = substr($g['hours_full'], 0, 2);
                                                $fh = str_replace('.', '', substr($g['hours'], 0, 2));
                                                $fh = str_pad($fh, 2, '0', STR_PAD_LEFT);
                                                $hours = $g['hours'] * 60;
                                                $ggg = sprintf("%02dh %02dm", floor($hours/60), $hours % 60);
                                                if ($g['hours_full'] != $ggg) {
                                                    $b = 'bg-danger';
                                                }*/

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
                                                echo '<td class="' . $b . '">' . $g['hours_full'] . '</td>'; // ' - ' . $g['hours'] .
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
                                    }
                                    $wd = count(array_unique($wds));
                                    $trd = '<tr class="dashtext-3"><td colspan="5">';
                                    $tt = (($h_spolu - wh_month($year, $m)) < 0) ? 'Chýbajúci čas' : 'Nadčas';
                                    $t_wh = wh_month($year, $m) - wh_diff_today($year, $m, $d) - $h_spolu;
                                    echo '</tbody><tbody class="table-striped mt-4">';
                                    echo $trd . 'Spolu</td><td colspan="2">' . ht($h_spolu) . '</td></tr>';
                                    echo $trd . 'Mesačný časový fond</td><td colspan="2">' . wh_month($year, $m) . 'h</td></tr>';
                                    echo $trd . $tt . '</td><td colspan="2">' . ht(abs(($h_spolu - wh_month($year, $m)))) . '</td></tr>';
                                    echo ($m == $mo) ? ($trd . 'Rozdiel proti časovému fondu k aktuálnemu dňu</td><td colspan="2">' . diff($t_wh) . ht(abs($t_wh)) . '</td></tr>') : ('');
                                    echo $trd . 'Počet pracovných dní</td><td colspan="2">' . wd_month($year, $m) . ' dní</td></tr>';
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
                                    <div class="title"><strong>Zamestnanci</strong></div>
                                    <div class="mb-2">
                                        <a href="https://dochadzka.diago.sk/user-add" role="button"
                                           class="btn btn-primary btn-sm">Pridať zamestnanca</a>
                                        <a href="https://dochadzka.diago.sk/add" role="button"
                                           class="btn btn-primary btn-sm">Pridať dochádzku</a>
                                    </div>
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
                                            <div>
                                                <? $i = 0;
                                                foreach (get_employees() as $emp) {
                                                    echo "<tr data-href='$url" . 'zamestnanci/' . $emp['id'] . '/' . date('m') . '/' . $y . "'>";
                                                    echo '<th>' . $emp['id'] . '</th>';
                                                    echo '<td>' . $emp['name'] . '</td>';
                                                    echo '<td>' . $emp['surname'] . '</td>';
                                                    echo '<td>' . '@' . $emp['login'] . '</td>';
                                                    echo (!$emp['last_login']) ? '<td class="d-none d-sm-block"></td>' : '<td class="d-none d-sm-block">' . $emp['last_login'] . '</td>';
                                                    echo '</tr>';
                                                    $i++;
                                                } ?>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-sm">
                                                        <tr>
                                                            <th>Počet zamestnancov: <?= $i ?></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <? } ?>
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
