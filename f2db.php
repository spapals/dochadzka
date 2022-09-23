<?
ini_set('error_reporting', E_ALL);
ini_set('display_errors', false);

require_once __DIR__ . '/SimpleXLSX.php';
require_once __DIR__ . '/functions.php';


$file = $_GET['file'];
$dt = explode('.', $file);
$year = $dt[0];
$month = $dt[1];
$date = $year . '|' . $month . '|';
$d = '<br>';
$w = ',';

if ($file) {

    if ($xlsx = SimpleXLSX::parse('data/' . $file)) {
        $zamestnanci = count($xlsx->sheetNames());

        //zamestnanci
        /*for ($pocet = 0; $pocet < $zamestnanci - 1; $pocet++) {
            $id = $xlsx->getCell($pocet, 'H1');

                //ID a meno
                $id   = $xlsx->getCell($pocet, 'H1');
                $name = $xlsx->getCell($pocet, 'C2');

                    $name = explode(' ', $name);
                    $sql =  "('" . $id . "','" . $name[1] . "','" . $name[0] . "','" . substr(dia($name[1]), 0, 1) . dia($name[0]) . "','',null, '0'),<br>";
                    echo $sql;

        }*/

        mysqli_query(db(), "DELETE FROM `attendance` WHERE month=$month") or die("Error: " . mysqli_error(db()));
        for ($pocet = 0; $pocet < $zamestnanci - 1; $pocet++) {
            $id = $xlsx->getCell($pocet, 'H1');
            for ($n = 5; $n < 36; $n++) {
                $den = 'A' . $n;
                //Hodiny
                $p = 'C' . $n;
                $o = 'D' . $n;
                $p = $xlsx->getCell($pocet, $p);
                $o = $xlsx->getCell($pocet, $o);
                if ($p || $o) {
                $t = "H";
                if (!$o){
                    $oh = $hh = 0;
                }else{
                    $oh = wh2h($p, $o, 0);
                    $hh = wh2h($p, $o, 1);
                }
                $dd = str_pad($xlsx->getCell($pocet, $den), 2, '0', STR_PAD_LEFT);
                $sql = "INSERT INTO `attendance` (`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`, `hours_full`) VALUES ('$id','$t','$p','$o','$dd','$month','$year','$hh','$oh')";
                mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
                }
                //Dovolenka
                $d = 'G' . $n;
                $d = $xlsx->getCell($pocet, $d);
                if ($d) {
                    $t = "D";
                    $oh = $d;
                    $hh = $d;
                    $dd = str_pad($xlsx->getCell($pocet, $den), 2, '0', STR_PAD_LEFT);
                    $sql = "INSERT INTO `attendance` (`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`, `hours_full`) VALUES ('$id','$t','$p','$o','$dd','$month','$year','$hh','$oh')";
                    mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
                }
                //Návšteva lekára
                $nl = 'H' . $n;
                $nl = $xlsx->getCell($pocet, $nl);
                if ($nl) {
                    $t = "L";
                    $nn = '';
                    $oh = ht($nl);
                    $hh = $nl;
                    $dd = str_pad($xlsx->getCell($pocet, $den), 2, '0', STR_PAD_LEFT);
                    $sql = "INSERT INTO `attendance` (`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`, `hours_full`) VALUES ('$id','$t','$nn','$nn','$dd','$month','$year','$hh','$oh')";
                    mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
                }
                //Obed
                $o = 'E' . $n;
                $o = $xlsx->getCell($pocet, $o);
                if ($o) {
                    $t = "O";
                    $nn = '';
                    $oh = ht($o);
                    $hh = $o;
                    $dd = str_pad($xlsx->getCell($pocet, $den), 2, '0', STR_PAD_LEFT);
                    $sql = "INSERT INTO `attendance` (`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`, `hours_full`) VALUES ('$id','$t','$nn','$nn','$dd','$month','$year','$hh','$oh')";
                    mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
                }
                //OČR
                $lc = 'I' . $n;
                $lc = $xlsx->getCell($pocet, $lc);
                if ($lc) {
                    $t = "LC";
                    $nn = '';
                    $oh = ht($lc);
                    $hh = $lc;
                    $dd = str_pad($xlsx->getCell($pocet, $den), 2, '0', STR_PAD_LEFT);
                    $sql = "INSERT INTO `attendance` (`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`, `hours_full`) VALUES ('$id','$t','$nn','$nn','$dd','$month','$year','$hh','$oh')";
                    mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
                }
                //Choroba
                $ch = 'J' . $n;
                $ch = $xlsx->getCell($pocet, $ch);
                if ($ch) {
                    $t = "CH";
                    $nn = '';
                    $oh = ht($ch);
                    $hh = $ch;
                    $dd = str_pad($xlsx->getCell($pocet, $den), 2, '0', STR_PAD_LEFT);
                    $sql = "INSERT INTO `attendance` (`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`, `hours_full`) VALUES ('$id','$t','$nn','$nn','$dd','$month','$year','$hh','$oh')";
                    mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
                }
            }
        }
    } else {
        echo SimpleXLSX::parseError();
    }
    header('Location: /import');
    exit();
}
