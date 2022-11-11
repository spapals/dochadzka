<?
//require_once('config.php');
require 'inc/Medoo.php';

use Medoo\Medoo;

// Connect the database.
$database = new Medoo([
    'type' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'dochadzka',
    'username' => 'db@dochadzka.diago.sk',
    'password' => '&ZgV2*RLOsjG',
    'charset' => 'utf8'
]);

$url = 'https://dochadzka.diago.sk/';
$y = date('Y');
$d = date('d');
function db()
{
    $dbc = mysqli_connect('127.0.0.1', 'db@dochadzka.diago.sk', '&ZgV2*RLOsjG', 'dochadzka');
    mysqli_set_charset($dbc, 'utf8');
    if (!$dbc) {
        die('Unable to connect to MySQL: ' . mysqli_error($dbc));
    }
    return $dbc;
}

function allowed_IP()
{
    //BR, BB, PD, KE, ST, SA
    $ip = array('84.16.44.50', '195.80.163.74', '80.87.223.32', '88.212.61.208', '188.121.179.232', '195.80.177.198');
    return $ip;
}

function holidays()
{
    $holidays = array('2019-05-01', '2019-05-08', '2019-07-05', '2019-08-29', '2019-09-01', '2019-09-15', '2019-11-01', '2019-11-17', '2019-12-24', '2019-12-25', '2019-12-26',
        '2020-01-01', '2020-01-06', '2020-04-10', '2020-04-13', '2020-05-01', '2020-05-08', '2020-07-05', '2020-08-29', '2020-09-01', '2020-09-15', '2020-11-01', '2020-11-17', '2020-12-24', '2020-12-25', '2020-12-26',
        '2021-01-01', '2021-01-06', '2021-04-02', '2021-04-05', '2021-05-01', '2021-05-08', '2021-07-05', '2021-08-29', '2021-09-01', '2021-09-15', '2021-11-01', '2021-11-17', '2021-12-24', '2021-12-25', '2021-12-26',
        '2022-01-01', '2022-01-06', '2022-04-15', '2022-04-18', '2022-05-01', '2022-05-08', '2022-07-05', '2022-08-29', '2022-09-01', '2022-09-15', '2022-11-01', '2022-11-17', '2022-12-24', '2022-12-25', '2022-12-26');
    return $holidays;
}

function get_user_requests($user)
{
    global $database;
    $data = $database->select('requests', '*', [
        'uid' => $user
    ]);
    return $data;
}

function db_get($table, $order)
{
    $dbc = db();
    $con = mysqli_query($dbc, "SELECT * FROM $table ORDER BY $order") or die('Error: ' . mysqli_error($dbc));
    $rows = [];
    while ($row = mysqli_fetch_assoc($con)) {
        $rows[] = $row;
    }
    return $rows;
}

function att_get($table, $order, $id, $m)
{
    $dbc = db();
    $con = mysqli_query($dbc, "SELECT * FROM $table WHERE id='$id' AND month='$m' ORDER BY $order") or die('Error: ' . mysqli_error($dbc));
    $rows = [];
    while ($row = mysqli_fetch_assoc($con)) {
        $rows[] = $row;
    }
    return $rows;
}

function att_edit($id)
{
    $dbc = db();
    $con = mysqli_query($dbc, "SELECT * FROM attendance_test WHERE uid=$id") or die('Error: ' . mysqli_error($dbc));
    $rows = [];
    while ($row = mysqli_fetch_assoc($con)) {
        $rows[] = $row;
    }
    return $rows;
}

function name($id)
{
    global $database;
    $name = $database->get('employees', ['name', 'surname'], ['id' => $id]);
    return $name['name'] . ' ' . $name['surname'];
}

function r($n)
{
    return round($n, 2);
}

function dia($t)
{
    return str_replace("'", '', strtolower(iconv('utf-8', 'ASCII//TRANSLIT', $t)));
}

function wh2h($s, $e, $i)
{
    $starttime = $s;
    $stoptime = $e;
    $diff = (strtotime($stoptime) - strtotime($starttime));
    $total = $diff / 60;
    if ($i) {
        return round($total / 60, 2);
    }
    return sprintf('%02dh %02dm', floor($total / 60), $total % 60);
}

function ht($in)
{
    $h = (int)$in;
    $m = round((((($in - $h) / 100.0) * 60.0) * 100), 0);
    if ($m == 60) {
        $h++;
        $m = 0;
    }
    $retval = sprintf('%02dh %02dm', $h, $m);
    return $retval;
}

function htd($in)
{
    $days = $in / 8;
    if ($days == 1) {
        $n = 'deň';
    } elseif ($days > 1 && $days < 5) {
        $n = 'dni';
    } else {
        $n = 'dní';
    }
    return $days . ' ' . $n;
}

function day_type($t, $h, $hf)
{
    if ($t == 'O') {
        return '<td class="dashtext-1">Obed<br>' . $hf . '</td>';
    } elseif ($t == 'OA') {
        return '<td class="dashtext-1">Aut. obed<br>' . $h . 'h</td>';
    } elseif ($t == 'D') {
        return '<td class="dashtext-1">Dovolenka<br>' . $h . 'h</td>';
    } elseif ($t == 'PD') {
        return '<td class="dashtext-1">Poldňová dovolenka<br>' . $h . 'h</td>';
    } elseif ($t == 'L') {
        return '<td class="dashtext-1">Návšteva lekára<br>' . $hf . '</td>';
    } elseif ($t == 'LC') {
        return '<td class="dashtext-1">Lekár s čl. rodiny<br>' . $hf . '</td>';
    } elseif ($t == 'CH') {
        return '<td class="dashtext-1">Choroba</td>';
    } elseif ($t == 'OC') {
        return '<td class="dashtext-1">OČR<br>' . $hf . '</td>';
    } elseif ($t == 'SU') {
        return '<td class="dashtext-1">Súkromný odchod<br>' . $hf . '</td></td>';
    } elseif ($t == 'SO') {
        return '<td class="dashtext-1">Služobný odchod<br>' . $hf . '</td></td>';
    } elseif ($t == 'P') {
        return '<td class="dashtext-1">Paragraf<br>' . $hf . '</td></td>';
    } elseif ($t == 'SC') {
        return '<td class="dashtext-1">Služobná cesta<br>' . $hf . '</td></td>';
    } elseif ($t == 'SC1') {
        return '<td class="dashtext-1">Služobná cesta 1 deň<br>' . $hf . '</td></td>';
    } elseif ($t == 'FA') {
        return '<td class="dashtext-1">Fajčiarska prestávka<br>' . $hf . '</td></td>';
    } elseif ($t == 'H' && $h < 8) {
        return '<td class="dashtext-3">Práca<br>' . $hf . '</td>';
    } elseif ($t == 'NV') {
        return '<td class="dashtext-1">Náhradné voľno<br>' . $hf . '</td>';
    } else {
        return '<td>Práca<br>' . $hf . '</td>';
    }
}

function d_type($t)
{
    if ($t == 'H') {
        return '<i class="fas fa-briefcase"></i> Práca';
    } elseif ($t == 'O') {
        return '<i class="fas fa-utensils-alt"></i> Obed';
    } elseif ($t == 'OA') {
        return '<i class="fas fa-utensils-alt"></i> Aut. obed';
    } elseif ($t == 'D') {
        return '<i class="fas fa-umbrella-beach"></i> Dovolenka';
    } elseif ($t == 'L') {
        return '<i class="fas fa-user-md"></i> Návšteva lekára';
    } elseif ($t == 'LC') {
        return '<i class="fas fa-user-md"></i> Lekár s čl. rodiny';
    } elseif ($t == 'OC') {
        return '<i class="fas fa-user-md"></i> OČR';
    } elseif ($t == 'SU') {
        return '<i class="fas fa-house-leave"></i> Súkromný odchod';
    } elseif ($t == 'SO') {
        return '<i class="fas fa-suitcase-rolling"></i> Služobný odchod';
    } elseif ($t == 'P') {
        return '<i class="fa-solid fa-section"></i> Paragraf';
    } elseif ($t == 'SC') {
        return '<i class="fas fa-suitcase-rolling"></i> Služobná cesta';
    } elseif ($t == 'SC1') {
        return '<i class="fas fa-suitcase-rolling"></i> Služobná cesta 1 deň';
    } elseif ($t == 'PD') {
        return '<i class="fas fa-umbrella-beach"></i> Poldňová dovolenka';
    } elseif ($t == 'CH') {
        return '<i class="fa-solid fa-face-head-bandage"></i> Choroba';
    } elseif ($t == 'FA') {
        return '<i class="fa-solid fa-smoking"></i> Fajčiarska prestávka';
    } elseif ($t == 'NV') {
        return '<i class="fas fa-umbrella-beach"></i> Náhradné voľno';
    }
}

function last_action_type($id, $u = NULL)
{
    ($u ? $uu = 'uid' : $uu = 'id');
    $dbc = db();
    $con = mysqli_query($dbc, "SELECT * FROM attendance_test WHERE $uu='$id' ORDER BY timestamp DESC LIMIT 1") or die('Error: ' . mysqli_error($dbc));
    $rows = [];
    $typ = array('L', 'SU', 'SO', 'SC', 'OC', 'P', 'FA', 'SC1', 'LC');
    while ($row = mysqli_fetch_assoc($con)) {
        $rows[] = $row;
    }
    foreach ($rows as $row) {
        if ($row['start']) {
            $s = '+';
            if ($row['end']) {
                $s = '-';
            }
        }
        $t = $row['type'];
        if ($t == 'H') {
            if ($s == '-') {
                $s = ' <i class="fas fa-angle-double-up"></i>';
            } else {
                $s = ' <i class="fas fa-angle-double-down"></i>';
            }
        } elseif ($t == 'O') {
            if ($s == '-') {
                $s = ' <i class="fas fa-angle-double-down"></i>';
            } else {
                $s = ' <i class="fas fa-angle-double-up"></i>';
            }
        } elseif (in_array($t, $typ)) {
            if ($s == '-') {
                $s = ' <i class="fas fa-angle-double-down"></i>';
            } else {
                $s = ' <i class="fas fa-angle-double-up"></i>';
            }
        }
        if ($t) {
            if ($t == 'H') {
                return '<i class="fas fa-briefcase"></i>' . $s . ' Práca';
            } elseif ($t == 'O') {
                return '<i class="fas fa-utensils-alt"></i>' . $s . ' Obed';
            } elseif ($t == 'D') {
                return '<i class="fas fa-umbrella-beach"></i>' . $s . ' Dovolenka';
            } elseif ($t == 'CH') {
                return '<i class="fa-solid fa-face-head-bandage"></i>' . $s . ' Choroba';
            } elseif ($t == 'PD') {
                return '<i class="fas fa-umbrella-beach"></i>' . $s . ' Poldňová dovolenka' . $s;
            } elseif ($t == 'OA') {
                return '<i class="fas fa-utensils-alt"></i>' . $s . ' Aut. obed';
            } elseif ($t == 'L') {
                return '<i class="fas fa-user-md"></i>' . $s . ' Návšteva lekára';
            } elseif ($t == 'LC') {
                return '<i class="fas fa-user-md"></i>' . $s . ' Návšteva lekára s čl. rodiny';
            } elseif ($t == 'SU') {
                return '<i class="fas fa-house-leave"></i>' . $s . ' Súkromný odchod';
            } elseif ($t == 'SO') {
                return '<i class="fas fa-suitcase-rolling"></i>' . $s . ' Služobný odchod';
            } elseif ($t == 'SC') {
                return '<i class="fas fa-suitcase-rolling"></i>' . $s . ' Služobná cesta';
            } elseif ($t == 'SC1') {
                return '<i class="fas fa-suitcase-rolling"></i>' . $s . ' Služobná cesta 1 deň';
            } elseif ($t == 'OC') {
                return '<i class="fas fa-user-md"></i>' . $s . ' OČR';
            } elseif ($t == 'P') {
                return 'Paragraf' . $s;
            } elseif ($t == 'FA') {
                return 'Fajčiarska prestávka' . $s;
            } elseif ($t == 'NV') {
                return 'Náhradné voľno' . $s;
            }
        }
    }
}

function wd($m, $y)
{
    $workdays = array();
    $type = CAL_GREGORIAN;
    $day_count = cal_days_in_month($type, $m, $y);
    for ($i = 1; $i <= $day_count; $i++) {
        $date = $y . '/' . $m . '/' . $i;
        $get_name = date('l', strtotime($date));
        $day_name = substr($get_name, 0, 3);
        if ($day_name != 'Sun' && $day_name != 'Sat') {
            $workdays[] = str_pad($i, 2, 0, STR_PAD_LEFT) . '.' . $m . '.' . $y;
        }
    }
    return $workdays;
}

function status($s)
{
    if ($s == 0) {
        return '<span class="float-left text-warning d-none d-sm-block">Čaká na schválenie </span> <i class="d-block d-sm-none far fa-clock text-warning"></i>';
    }
    if ($s == 1) {
        return '<span class="float-left text-success d-none d-sm-block">Schválené </span> <i class="d-block d-sm-none fas fa-check text-success"></i>';
    }
    if ($s == 2) {
        return '<span class="float-left text-danger d-none d-sm-block">Zamietnuté </span> <i class="d-block d-sm-none fas fa-times text-danger"></i>';
    }
}

function td($d)
{
    return date('d.m.Y H:i:s', strtotime($d));
}

function nd($d)
{
    return date("d.m.Y", strtotime($d));
}

function business_days_diff($start_date, $end_date)
{
    $holidays = holidays();
    $business_days = 0;
    $current_date = strtotime($start_date);
    $end_date = strtotime($end_date);
    while ($current_date <= $end_date) {
        if (date('N', $current_date) < 6 && !in_array(date('Y-m-d', $current_date), $holidays)) {
            $business_days++;
        }
        if ($current_date <= $end_date) {
            $current_date = strtotime('+1 day', $current_date);
        }
    }
    if ($business_days == 0) {
        $business_days = 1;
    }
    return $business_days;
}

function business_days($start_date, $end_date)
{
    $holidays = holidays();
    $business_days = [];
    $current_date = strtotime($start_date);
    $end_date = strtotime($end_date);
    while ($current_date <= $end_date) {
        if (date('N', $current_date) < 6 && !in_array(date('Y-m-d', $current_date), $holidays)) {
            $business_days[] = date('Y-m-d', $current_date);
        }
        if ($current_date <= $end_date) {
            $current_date = strtotime('+1 day', $current_date);
        }
    }
    /*if ($business_days == 0) {
        $business_days = 1;
    }*/
    return $business_days;
}

function alerts($status)
{
    global $database;
    $alerts = $database->count("requests", ["status" => $status]);
    return $alerts;
}

function get_alerts($status)
{
    global $database;
    $alerts = $database->select("requests", '*', ["status" => $status]);
    return $alerts;
}

function requests($status, $order, $page=0)
{
    $limit = 20;
    $offset = ($limit * $page) - $limit;

    global $database;
    $requests = $database->select("requests", "*", ["status" => $status, "ORDER" => ["added" => $order], "LIMIT" => [$offset, $limit]]);
    return $requests;
}

function count_requests($status)
{
    global $database;
    $count = $database->count("requests", ["status" => $status]);
    return ceil($count / 20);
}

function limit($iterable, $limit)
{
    foreach ($iterable as $key => $value) {
        if (!$limit--) break;
        yield $key => $value;
    }
}

function req_date($start, $end)
{
    if ($start && $end == '01.01.1970') {
        return $start;
    }
    if ($start == $end) {
        return $start;
    } else {
        return $start . ' - ' . $end;
    }
}

function wd_month($year, $month)
{
    $holidays = holidays();
    $ignore = array(0, 6);
    $count = 0;
    $hol = 0;
    $counter = mktime(0, 0, 0, $month, 1, $year);
    while (date("n", $counter) == $month) {
        if (!in_array(date("w", $counter), $ignore)) {
            $count++;
        }
        if (!in_array(date("w", $counter), $ignore) && in_array(date("Y-m-d", $counter), $holidays)) {
            $hol++;
        }
        $counter = strtotime("+1 day", $counter);
    }
    return ($count - $hol);
}

function wh_month($year, $month)
{
    $holidays = holidays();
    $ignore = array(0, 6);
    $count = 0;
    $hol = 0;
    $counter = mktime(0, 0, 0, $month, 1, $year);
    while (date("n", $counter) == $month) {
        if (!in_array(date("w", $counter), $ignore)) {
            $count++;
        }
        if (!in_array(date("w", $counter), $ignore) && in_array(date("Y-m-d", $counter), $holidays)) {
            $hol++;
        }
        $counter = strtotime("+1 day", $counter);
    }
    return ($count - $hol) * 8;
}

function wh_diff_today($year, $month, $day)
{
    $holidays = holidays();
    $ignore = array(0, 6);
    $count = 0;
    $hol = 0;
    $counter = mktime(0, 0, 0, $month, $day, $year);
    while (date("n", $counter) == $month) {
        if (!in_array(date("w", $counter), $ignore)) {
            $count++;
        }
        if (!in_array(date("w", $counter), $ignore) && in_array(date("Y-m-d", $counter), $holidays)) {
            $hol++;
        }
        $counter = strtotime("+1 day", $counter);
    }
    return ($count - $hol) * 8;
}

function bcrypt($p)
{
    $options = [
        'cost' => 12,
    ];
    return password_hash($p, PASSWORD_BCRYPT, $options);
}

function verify_password($p, $hash)
{
    if (password_verify($p, $hash)) {
        return true;
    } else {
        return false;
    }
}

function group_by($array, $keys = array())
{
    $return = array();
    foreach ($array as $val) {
        $final_key = "";
        foreach ($keys as $theKey) {
            $final_key .= $val[$theKey] . "_";
        }
        $return[$final_key][] = $val;
    }
    return $return;
}

function noneg($number)
{
    if ($number < 0)
        return '+' . $number;
    return $number;
}

function generate_password($t)
{
    $num = rand(111, 999);
    if (strlen($t) > 4 && strlen($t) < 7) {
        $pass = substr_replace($t, $num, 3, 0);
    } elseif (strlen($t) < 5) {
        $pass = substr_replace($t, $num, 2, 0);
    } else {
        $pass = substr_replace($t, $num, 3, 3);
    }
    return $pass;
}

function removeAccents($str)
{
    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
    $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
    return str_replace($a, $b, $str);
}

function svk($str)
{
    $a = array('May', 'Jun', 'Jul', 'Oct');
    $b = array('Máj', 'Jún', 'Júl', 'Okt');
    return str_replace($a, $b, $str);
}

function is_today($id, $type)
{
    global $database;

    $last_action = $database->get("attendance_test", [
        "start", "end", "day", "month", "year", "timestamp"
    ], [
        "id" => $id,
        "type" => $type,
        "ORDER" => [
            "timestamp" => "DESC"
        ],
    ]);
    $today = strtotime(date('Y-m-d'));
    $date = strtotime($last_action['year'] . '-' . $last_action['month'] . '-' . $last_action['day']);
    return $today == $date;
}

function sendMessage($id, $status)
{
    $dbc = db();
    $con = mysqli_query($dbc, "SELECT eid FROM notifications WHERE uid='$id'") or die('Error: ' . mysqli_error($dbc));
    $ids = array();
    while ($row = mysqli_fetch_array($con)) {
        $ids[] = $row['eid'];
    }
    if ($ids) {
        if ($status == 'ok') {
            $heading = array(
                'en' => 'Požiadavka schválená',
                'sk' => 'Požiadavka schválená'
            );
            $content = array(
                'en' => 'Vaša požiadavka bola schválená.',
                'sk' => 'Vaša požiadavka bola schválená.'
            );
        }
        if ($status == 'no') {
            $heading = array(
                'en' => 'Požiadavka zamietnutá',
                'sk' => 'Požiadavka zamietnutá'
            );
            $content = array(
                'en' => 'Vaša požiadavka bola zamietnutá.',
                'sk' => 'Vaša požiadavka bola zamietnutá.'
            );
        }

        $fields = array(
            'app_id' => "335dbc82-ddd3-41b4-9fcb-e5d5ad832d44",
            'include_player_ids' => $ids,
            'data' => array("foo" => "bar"),
            'headings' => $heading,
            'contents' => $content
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

function req_status_update($id, $status)
{
    $dbc = db();
    $sid = $_SESSION['id'];
    $sql = "UPDATE `requests` SET status='$status', approved_by='$sid' WHERE `id`='$id'";
    if ($status == 3) {
        $sql = "DELETE FROM `requests` WHERE `id`='$id'";
    }
    mysqli_query($dbc, $sql) or die('Error: ' . mysqli_error($dbc));
    if ($status == '1') {
        echo '<div class="alert alert-success" role="alert">Požiadavka bola schválená.</div>';
    } elseif ($status == '2') {
        echo '<div class="alert alert-danger" role="alert">Požiadavka bola zamietnutá.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Požiadavka bola vymazaná.</div>';
    }

    if ($status == '1') {
        $con = mysqli_query($dbc, "SELECT * FROM `requests` WHERE id='$id'") or die('Error: ' . mysqli_error($dbc));
        $rows = [];
        while ($row = mysqli_fetch_assoc($con)) {
            $rows[] = $row;
        }
        if ($rows) {
            foreach ($rows as $row) {
                $iid = $row['uid'];
                $type = $row['type'];
                $start = $row['time'];
                $end = $row['time_to'];
                $date = $row['date'];
                $note = $row['note'];
                $date_to = $row['date_to'];
                $d = explode('-', $row['date']);
                $total = ((strtotime($end) - strtotime($start)) / 60);
                $oh = sprintf("%02dh %02dm", floor($total / 60), $total % 60);
                $h = r((strtotime($end) - strtotime($start)) / 3600);

                if ($type == 'D' || $type == 'CH') {
                    $h = '8';
                    $oh = '08h 00m';
                    foreach (business_days($date, $date_to) as $bd) {
                        $d = explode('-', $bd);
                        $sql = "INSERT INTO `attendance_test`(`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`, `hours_full`, `note`, `timestamp`) 
                VALUES ('$iid','$type','$start','$end','$d[2]','$d[1]','$d[0]','$h','$oh','$note',now()) ";
                        mysqli_query($dbc, $sql) or die('Error: ' . mysqli_error($dbc));
                    }
                } else {
                    $sql = "INSERT INTO `attendance_test`(`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`, `hours_full`, `note`, `timestamp`) 
                VALUES ('$iid','$type','$start','$end','$d[2]','$d[1]','$d[0]','$h','$oh','$note',now()) ";
                    mysqli_query($dbc, $sql) or die('Error: ' . mysqli_error($dbc));
                }
            }
        }
    }
}

function last_action($d)
{
    $dbc = db();
    $d = explode('.', $d);
    $con = mysqli_query($dbc, "SELECT * FROM attendance_test WHERE day='$d[0]' AND month='$d[1]' AND year='$d[2]' ORDER BY timestamp DESC") or die('Error: ' . mysqli_error($dbc));
    $rows = [];
    while ($row = mysqli_fetch_assoc($con)) {
        $rows[] = $row;
    }
    return $rows;
}

function last_action_time($id)
{
    $dbc = db();
    $con = mysqli_query($dbc, "SELECT * FROM attendance_test WHERE id='$id' AND type='H' ORDER BY timestamp DESC LIMIT 1") or die('Error: ' . mysqli_error($dbc));
    $rows = [];
    while ($row = mysqli_fetch_assoc($con)) {
        $rows[] = $row;
    }
    foreach ($rows as $row) {
        $add = date('I') == 1 ? 7200 : 3600;
        $t = strtotime($row['start']) - $add;
        return date('Y-m-d H:i:s', $t);
    }
}

function work($id)
{
    $dbc = db();
    $con = mysqli_query($dbc, "SELECT * FROM attendance_test WHERE id='$id' AND type='H' ORDER BY timestamp DESC LIMIT 1") or die('Error: ' . mysqli_error($dbc));
    $rows = [];
    while ($row = mysqli_fetch_assoc($con)) {
        $rows[] = $row;
    }
    if ($rows) {
        foreach ($rows as $row) {
            $add = date('I') == 1 ? 7200 : 3600;
            $t = strtotime($row['start']) - $add;
            $td = date('Y-m-d');
            $today = explode(' ', $row['timestamp']);
            if ($row['start'] && $today[0] == $td && !$row['end']) {
                return '<span class="mb-2 font-weight-bold">V práci od<br><i class="fal fa-clock"></i> ' . $row['start'] . '<br>
                               <time class="timeago" datetime="' . date('Y-m-d H:i:s', $t) . '" data-time-tooltip="">
                                 <span data-time-label="#_in" class=""></span>
                                 <span data-time-label="td_h" class="number">0</span>h :
                                 <span data-time-label="d_mm" class="number">0</span>m :
                                 <span data-time-label="d_ss" class="number timer-tick">0</span>s
                               </time>
                           </span>';
            }
        }
    }
}

function lat($id)
{
    $dbc = db();
    $con = mysqli_query($dbc, "SELECT * FROM attendance_test WHERE id='$id' ORDER BY timestamp DESC LIMIT 1") or die('Error: ' . mysqli_error($dbc));
    $rows = [];
    while ($row = mysqli_fetch_assoc($con)) {
        $rows[] = $row;
    }
    foreach ($rows as $row) {
        $t = strtotime($row['timestamp']);
        return date('H:i - d.m.Y', $t);
    }
}

function att_alert($id, $type)
{
    global $database;
    $profile = $database->get("employees", [
        "time_edit",
        "force_time"
    ], [
        "id" => $id
    ]);

    $dbc = db();
    $con = mysqli_query($dbc, "SELECT * FROM attendance_test WHERE id='$id' AND type='$type' ORDER BY timestamp DESC LIMIT 1") or die('Error: ' . mysqli_error($dbc));
    $rows = [];
    while ($row = mysqli_fetch_assoc($con)) {
        $rows[] = $row;
    }
    if (!$rows) {
        $s = '-';
    }
    foreach ($rows as $row) {
        if ($row['start']) {
            $s = '+';
            if ($row['end']) {
                $s = '-';
            }
        }
    }
    $today = strtotime(date('Y-m-d'));
    $action_time = strtotime($rows[0]['year'] . '-' . $rows[0]['month'] . '-' . $rows[0]['day']);
    $is_today = $today == $action_time ? true : false;
    if ($type) {
        $t = $type;
        if ($t == 'H') {
            if ($s == '-' || !$is_today) {
                $title = 'Vitajte!';
            } else {
                $title = 'Dovidenia!';
            }
        } elseif ($t == 'O') {
            if ($s == '-') {
                $title = 'Dobrú chuť!';
            } else {
                $title = 'Vitajte!';
            }
        } elseif ($t == 'L' || $t == 'SU' || $t == 'SO' || $t == 'SC' || $t == 'OC' || $t == 'P' || $t == 'FA' || $t == 'SC1' || $t == 'LC') {
            if ($s == '-') {
                $title = 'Dovidenia!';
            } else {
                $title = 'Vitajte!';
            }
        }
    }
    $disable = $profile['time_edit'] ? '' : 'swal.disableInput()';
    $time = date('H:i');
    if ($profile['force_time']) {
        $time = force_time($time);
    }
    return "Swal.fire({
    type: 'success',
    title: '$title',
    showConfirmButton: true,
    showCancelButton: true,
    showCloseButton: true,
    cancelButtonColor: '#d33',
    cancelButtonText: 'Zrušiť',
    input: 'text',
    inputValue: '$time',
    customClass: { input: 'text-center' },
    onBeforeOpen: () => {
      $disable},
}).then((result) => {
    if (result.value) {
        $.ajax({
            type: 'POST',
            url: '',
            data: {id: '$id', type: '$type', time: result.value },
            cache: false,
        }).then(function() {
        location.reload();
    })
    }
})";
}

function add_att($id, $type, $now)
{
    $day = date('Y-m-d');
    $now = r_time($now);
    $d = explode('-', $day);
    $dbc = db();
    $con = mysqli_query($dbc, "SELECT * FROM attendance_test WHERE id='$id' AND type='$type' ORDER BY timestamp DESC LIMIT 1") or die('Error: ' . mysqli_error($dbc));
    $rows = [];
    while ($row = mysqli_fetch_assoc($con)) {
        $rows[] = $row;
    }
    if (!$rows) {
        $sql = "INSERT INTO `attendance_test`(`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`,`hours_full`,`timestamp`) 
                VALUES ('$id','$type','$now','','$d[2]','$d[1]','$d[0]','0','00h 00m',now()) ";
    }
    foreach ($rows as $row) {
        $td = date('Y-m-d');
        $today = explode(' ', $row['timestamp']);
        $total = ((strtotime($now) - strtotime($row['start'])) / 60);
        $oh = sprintf('%02dh %02dm', floor($total / 60), $total % 60);
        $uid = $row['uid'];
        $typ = $row['type'];
        $h = r((strtotime($now) - strtotime($row['start'])) / 3600);
        if ($today[0] == $td && $typ == $type && !$row['end']) {
            $sql = "UPDATE `attendance_test` SET `end` = '$now', `hours` = '$h', `hours_full` = '$oh',`timestamp` = now() WHERE `uid` = $uid AND `type` = '$typ'";
        } else {
            $sql = "INSERT INTO `attendance_test`(`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`, `hours_full`, `timestamp`) 
                VALUES ('$id','$type','$now','','$d[2]','$d[1]','$d[0]','0','00h 00m',now()) ";
        }
    }
    mysqli_query($dbc, $sql) or die('Error: ' . mysqli_error($dbc));
}

function work_inout($id)
{
    $dbc = db();
    $con = mysqli_query($dbc, "SELECT * FROM attendance_test WHERE id='$id' AND type='H' ORDER BY timestamp DESC LIMIT 1") or die('Error: ' . mysqli_error($dbc));
    $rows = [];
    while ($row = mysqli_fetch_assoc($con)) {
        $rows[] = $row;
    }
    $x = '<i class="fad fa-angle-right fa-3x"></i><br>Príchod do práce';
    foreach ($rows as $row) {
        $td = date('Y-m-d');
        $today = explode(' ', $row['timestamp']);
        if ($today[0] == $td && $row['start'] && !$row['end']) {
            $x = '<i class="fad fa-angle-left fa-3x"></i><br>Odchod z práce';
        }
    }
    return $x;
}


function diff($n)
{
    if ($n == 0) {
        return abs($n);
    } elseif ($n <= 0) {
        return '+ ';
    } else {
        return '- ';
    }
}

function r_time($t)
{
    return str_replace(array(',', '.'), array(':', ':'), $t);
}

function count_hours($i)
{
    echo $i['type'];
}

function in_array_r($needle, $haystack, $strict = false)
{
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}

function today()
{
    return date('d.m.Y');
}

function getUserIP()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

function allow_login()
{
    if (in_array(getUserIP(), allowed_IP())) {
        echo "GOT IP";
    } else {
        echo 'Scap';
    }
}

function oh($start, $end)
{
    $t = ((strtotime($end) - strtotime($start)) / 60);
    return sprintf("%02dh %02dm", floor($t / 60), $t % 60);
}

function h($start, $end)
{
    return r((strtotime($end) - strtotime($start)) / 3600);
}

function add_attendance($post)
{
    //print_r($post);
    //die();
    $date = explode('-', $post['date']);//
    $day = $date[2];
    $month = $date[1];
    $year = $date[0];
    $type = $post['type'];
    $from = $post['time'];
    $to = $post['time_to'];
    $id = $post['worker'];
    $t = $post['time'];
    $tt = $post['time_to'];
    $note = $post['poznamka'];
    $h = h($t, $tt);
    $oh = oh($t, $tt);

    if ($id === 'all') {
        foreach (db_get('employees', 'surname') as $emp) {
            $eid = $emp['id'];
            if ($type === 'D' || $type === 'CH') {
                $h = '8';
                $oh = '08h 00m';
            }
            $sql = "INSERT INTO `attendance_test`(`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`,`hours_full`, `note`, `timestamp`) VALUES ('$eid','$type','$from','$to','$day','$month','$year','$h','$oh','$note',now()) ";
            mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
        }
    } else {
        if ($type === 'D' || $type === 'CH') {
            $h = '8';
            $oh = '08h 00m';
        }
        $sql = "INSERT INTO `attendance_test`(`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`,`hours_full`, `note`, `timestamp`) VALUES ('$id','$type','$from','$to','$day','$month','$year','$h','$oh','$note',now()) ";
        mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
    }
    echo '<div class="alert alert-success" role="alert">Záznam bol pridaný.</div>';
}

function force_time($time, $minute = 30, $timeto = 12)
{
    $time = strtotime($time);
    $m = date("i", $time) * 1;
    $h = date("H", $time) * 1;

    if ($h > $timeto && $m < $minute) {
        $min = 0;
    } elseif ($h < $timeto && $m > $minute) {
        $h = $h + 1;
        $min = 0;
    } else {
        $min = $minute;
    }
    return date("H:i", strtotime($h . ":" . $min));
}

function can_edit_time($id)
{
    global $database;
    $profile = $database->get("employees", [
        "time_edit"
    ], [
        "id" => $id
    ]);
    return $profile['time_edit'];
}

function login($post)
{
    global $database;
    $user = $database->get("employees", [
        "id",
        "name",
        "surname",
        "login",
        "password",
        "last_login",
        "is_admin"
    ], [
        "login" => $post['user'],
    ]);
    if ($user) {
        if (password_verify($post['pass'], $user['password'])) {
            session_start();
            $_SESSION['sess_user'] = $user['login'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'] . ' ' . $user['surname'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $last_login = $database->update("employees", [
                "last_login" => date("Y-m-d H:i:s")
            ], [
                "id" => $user['id']
            ]);
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function get_user_data($id)
{
    global $database;
    $user = $database->get("employees", [
        "id",
        "name",
        "surname",
        "is_admin",
        "time_edit",
        "force_time",
        "active"
    ], [
        "id" => $id
    ]);
    return $user;
}

function get_employees()
{
    global $database;
    $employees = $database->select("employees", [
        "id",
        "name",
        "surname",
        "login",
        "password",
        "last_login",
        "is_admin",
        "time_edit",
        "force_time",
        "active"
    ], [
        "active" => 1,
        "ORDER" => [
            "surname" => "ASC",
        ]
    ]);
    return $employees;
}

function update_user_data($post)
{
    global $database;
    $id = $post['id'];
    $name = $post['name'];
    $surname = $post['surname'];
    $password = $post['password'] ? $post['password'] : '';
    $is_admin = $post['is_admin'] == 'on' ? 1 : 0;
    $time_edit = $post['time_edit'] == 'on' ? 1 : 0;
    $force_time = $post['force_time'] == 'on' ? 1 : 0;
    $active = $post['active'] == 'on' ? 1 : 0;
    if ($password) {
        $sql = $database->update("employees", [
            "name" => $name,
            "surname" => $surname,
            "password" => bcrypt($password),
            "is_admin" => $is_admin,
            "time_edit" => $time_edit,
            "force_time" => $force_time,
            "active" => $active
        ], [
            "id" => $id
        ]);
        return true;
    } else {
        $sql = $database->update("employees", [
            "name" => $name,
            "surname" => $surname,
            "is_admin" => $is_admin,
            "time_edit" => $time_edit,
            "force_time" => $force_time,
            "active" => $active
        ], [
            "id" => $id
        ]);
        return true;
    }
}

function get_unique_id()
{
    global $database;
    $ids = $database->select("employees", [
        "id"
    ]);
    $ids_array = array();
    foreach ($ids as $id) {
        $ids_array[] = $id['id'];
    }
    $unique_id = rand(111, 999);
    while (in_array($unique_id, $ids_array)) {
        $unique_id = rand(1111, 999);
    }
    return $unique_id;
}

function add_user($post)
{
    global $database;
    $id = get_unique_id();
    $name = $post['meno'];
    $surname = $post['priezvisko'];
    $sur = strtolower(removeAccents($surname));
    $nam = strtolower(removeAccents($name));
    $login = $nam[0] . $sur;
    $password = generate_password($sur);
    $is_admin = $post['is_admin'] == 'on' ? 1 : 0;
    $time_edit = $post['time_edit'] == 'on' ? 1 : 0;
    $force_time = $post['force_time'] == 'on' ? 1 : 0;
    $sql = $database->insert("employees", [
        "id" => $id,
        "name" => $name,
        "surname" => $surname,
        "login" => $login,
        "password" => bcrypt($password),
        "is_admin" => $is_admin,
        "time_edit" => $time_edit,
        "force_time" => $force_time,
        "active" => 1
    ]);
    $added['login'] = $login;
    $added['password'] = $password;
    return $added;
}

function generate_months_back()
{
    $months_back = array();
    $month = date('m', strtotime('+1 month'));
    $year = date('Y');
    for ($i = 0; $i < 4; $i++) {
        $month--;
        if ($month == 0) {
            $month = 12;
            $year--;
        }
        $month = $month < 10 ? '0' . $month : $month;
        $months_back[$i]['month'] = $month;
        $months_back[$i]['year'] = $year;
    }
    return array_reverse($months_back);
}

function short_month($month)
{
    $months = array(
        '01' => 'Jan',
        '02' => 'Feb',
        '03' => 'Mar',
        '04' => 'Apr',
        '05' => 'Máj',
        '06' => 'Jún',
        '07' => 'Júl',
        '08' => 'Aug',
        '09' => 'Sep',
        '10' => 'Okt',
        '11' => 'Nov',
        '12' => 'Dec'
    );
    return $months[$month];
}



