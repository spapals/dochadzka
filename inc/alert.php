<?
require_once('../functions.php');
$id = $_POST['uid'];
$eid = $_POST['eid'];

$sql = "INSERT INTO `notifications`(`uid`, `eid`) VALUES ('$id','$eid')";

$dbc = db();
$con = mysqli_query($dbc, $sql) or die("Error: " . mysqli_error($dbc));
