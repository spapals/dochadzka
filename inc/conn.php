<?
if (isset ($_POST['notif_id'])) {
    $uid = htmlspecialchars($_POST['uid'], ENT_QUOTES, 'UTF-8');
    $eid = htmlspecialchars($_POST['eid'], ENT_QUOTES, 'UTF-8');
    $sql = "INSERT INTO `notifications`(`uid`, `eid`) VALUES ('$uid','$eid') ";
    mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
}
?>