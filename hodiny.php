<?
ini_set('error_reporting', E_ALL);
ini_set('display_errors', FALSE);
require_once 'functions.php';
$m = $_GET['m']; //(!$m) ? $m = date('m') :
$mo = date('m');
$year = $_GET['y'];
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
                <h2 class="h5 no-margin-bottom">Hodiny</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb d-print-none">
                <li class="breadcrumb-item"><a href="/">Domov</a></li>
                <li class="breadcrumb-item active">Hodiny</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block p-0 m-0">
                            <div class="title pl-2 pt-2"><strong>Prehľad hodín - <?= $m . '/' . $year ?> - <?= name($_SESSION['id']) ?></strong>
                            </div>
                            <div class="row months mb-2 float-right d-print-none pr-4">
                                <? foreach (generate_months_back() as $item) {
                                    $month = $item['month'];
                                    $y = $item['year']; ?>
                                    <a href="<?= $url . 'hodiny/' . $month . '/' . $y ?>" role="button" class="btn btn-primary btn-sm ml-1"><?= short_month($month) ?></a>
                                <? }?>
                            </div>
                            <div class="table-responsive mb-3">
                                <table class="table table-sm">
                                    <thead class="text-center">
                                    <tr>
                                        <th>Deň</th>
                                        <th>Príchod</th>
                                        <th>Odchod</th>
                                        <th>Typ</th>
                                        <th>Hodiny</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?
                                    $table = 'attendance_test';
                                    $wds = [];
                                    foreach (group_by(att_get($table, 'day ASC, start ASC', $_SESSION['id'], $m), array('id', 'day', 'month', 'year')) as $att) {
                                        if ($att[0]['year'] === $year ){
                                            $hideB = can_edit_time($_SESSION['id']) ? 'btn btn-success btn-sm mr-1' : 'd-none';
                                            $hideN = can_edit_time($_SESSION['id']) ? 'd-none d-sm-block mr-1' : 'd-none';
                                            $b = ($count % 2 == 1) ? ('bg-secondary') : ('bg-light');
                                            $date = $att[0]['day'] . '.' . $att[0]['month'] . '.' . $att[0]['year'];
                                            $dt = $att[0]['day'] . '.' . $att[0]['month'] . '<br>' . $att[0]['year'];
                                            echo '<tr>';
                                            echo (!in_array($date, wd($m, $year))) ? '<td rowspan="' . count($att) . '" class="' . $b . ' text-center dashtext-4">' . $dt . '</td>' : '<td rowspan="' . count($att) . '" class="' . $b . ' text-center">' . $dt . '</td>';
                                            foreach ($att as $g) {
                                                $tip = $g['note'] ? ' <i class="fad fa-info-circle" data-toggle="tooltip" data-placement="top" title="' . $g['note'] . '"></i>' : '';
                                                $b = ($count % 2 == 1) ? ('bg-secondary') : ('bg-light');
                                                if ($date != today() && $g['hours'] < 0.15) {
                                                    $b = 'bg-danger';
                                                }
                                                $t = $g['type'];
                                                echo '<td class="' . $b . '">' . $g['start'] . '</td>';
                                                echo '<td class="' . $b . '">' . $g['end'] . '</td>';
                                                echo '<td class="' . $b . '">' . d_type($t) . $tip . '</td>';
                                                echo '<td class="' . $b . '">' . $g['hours_full'] . '</td>';
                                                echo '<td class="' . $b . ' d-print-none">' . '
                                        <a href="https://dochadzka.diago.sk/hodiny-edit/' . $g['uid'] . '" role="button" class="' .$hideB. '" name="delete"><span class="'. $hideN .'">Upraviť</span>
                                        <i class="d-block d-sm-none fas fa-edit"></i></a>
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
                                                ($t == 'H') ? ($wd++) : ('');
                                                ($t == 'H') ? ($wds[] = $g['day']) : ('');
                                            }
                                            $count++;
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <table class="table table-sm mt-2">
                                    <?
                                    $wd = count(array_unique($wds));
                                    $trd = '<tr class="dashtext-3"><td colspan="5">';
                                    $tt = (($h_spolu - wh_month($year, $m)) < 0) ? 'Chýbajúci čas' : 'Nadčas';
                                    $t_wh = wh_month($year, $m) - wh_diff_today($year, $m, $d) - $h_spolu;

                                    echo '<tbody class="table-striped mt-4">';
                                    echo $trd . 'Spolu</td><td colspan="2">' . ht($h_spolu) . '</td></tr>';
                                    echo $trd . 'Mesačný časový fond</td><td colspan="2">' . wh_month(date('Y'), $m) . 'h</td></tr>';
                                    echo $trd . $tt . '</td><td>' . ht(abs(($h_spolu - wh_month($year, $m)))) . '</td></tr>';
                                    echo ($m == $mo) ? ($trd . 'Rozdiel proti časovému fondu k aktuálnemu dňu</td><td colspan="2">' . diff($t_wh) . ht(abs($t_wh)) . '</td></tr>') : ('');
                                    echo $trd . 'Počet pracovných dní</td><td colspan="2">' . wd_month($year, $m) . ' dní</td></tr>';
                                    echo $trd . 'Počet odpracovaných dní</td><td colspan="2">' . $wd . ' dní</td></tr>';
                                    echo '<tr><td colspan="6"></td></tr>';
                                    echo ($o) ? ($trd . 'Obed</td><td colspan="2">' . ht($o) . '</td></tr>') : ('');
                                    echo ($sc) ? ($trd . 'Služobná cesta</td><td colspan="2">' . ht($sc) . '</td></tr>') : ('');
                                    echo ($so) ? ($trd . 'Služobný odchod</td><td colspan="2">' . ht($so) . '</td></tr>') : ('');
                                    echo ($su) ? ($trd . 'Súkromný odchod</td><td colspan="2">' . ht($su) . '</td></tr>') : ('');
                                    echo ($l) ? ($trd . 'Návšteva lekára</td><td colspan="2">' . ht($l) . '</td></tr>') : ('');
                                    echo ($ch) ? ($trd . 'Choroba</td><td colspan="2">' . htd($ch) . ' dní</td></tr>') : ('');
                                    echo ($dov) ? ($trd . 'Dovolenka</td><td colspan="2">' . htd($dov) . '</td></tr>') : ('');
                                    echo ($lc) ? ($trd . 'Návšteva lekára s čl. rodiny</td><td colspan="2">' . ht($lc) . '</td></tr>') : ('');
                                    echo '</tbody>';
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <? require_once __DIR__ . '/inc/footer.php';
        /*echo '<pre>';
        $g = group_by(att_get($table, 'day ASC, type ASC', $_SESSION['id'], $m), array('id', 'day', 'month', 'year'));
        print_r($g);
        echo '</pre>';*/
        ?>
        </body>
</html>
