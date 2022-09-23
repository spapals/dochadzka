<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', TRUE);
require_once 'functions.php';

if ($_GET['s'] == 'AFwZQ22rGQ6krHTe') {
    $i = 0;
    $at = group_by(db_get('attendance_test', 'timestamp ASC'), array('id', 'day', 'month', 'year'));
    $sql = "INSERT INTO `attendance_test` (`id`, `type`, `start`, `end`, `day`, `month`, `year`, `hours`,`hours_full`,`timestamp`) VALUES ";
    $val = [];
    foreach ($at as $att) {
        if (!in_array_r("O", $att) && !in_array_r("OA", $att)) {
            foreach ($att as $g) {
                if ($g['type'] == 'H' && $g['hours'] >= '8') {
                    $id = $g['id'];
                    $day = $g['day'];
                    $month = $g['month'];
                    $year = $g['year'];
                    $n = $year . '-' . $month . '-' . $day . ' 11:30:00';
                    $val[] = "('$id','OA','11:30','12:00','$day','$month','$year','0.5','00h 30m','$n')";
                    echo name($g['id']) . ' - ' . $g['day'] . '.' . $g['month'] . '.' . $g['year'] . '<br>';
                    $i++;
                }
            }
        }
    }
    $o = 1;

    foreach ($val as $vals) {
        $d = ($o < $i) ? ',' : ';';
        $sql .= $vals . $d;
        $o++;
    }
    if ($i){mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));}
    //echo $sql;
    echo 'Pridaných ' . $i . ' záznamov.';
    echo '<pre>';
    //print_r($val);
    echo '</pre>';
}
