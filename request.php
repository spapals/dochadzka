<?
require_once 'functions.php';
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
                <h2 class="h5 no-margin-bottom">Požiadavka</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Domov</a></li>
                <li class="breadcrumb-item active">Požiadavka</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title"><strong>Požiadavka</strong></div>
                            <?
                            if (isset ($_POST['add'])) {
                                $subject = $_POST['subject'];
                                $from = $_POST['from'];
                                $to = $_POST['to'];
                                $id = $_POST['id'];
                                $t = $_POST['time'];
                                $tt = $_POST['time_to'];
                                $note = $_POST['poznamka'];
                                $sql = "INSERT INTO `requests`(`uid`, `type`, `date`, `date_to`, `time`, `time_to`, `added`, `note`,`status`,`approved_by`) VALUES ('$id','$subject','$from','$to','$t','$tt',now(),'$note',0,0) ";
                                mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
                                echo '<div class="alert alert-success" role="alert">Vaša požiadavka bola odoslaná.</div>';
                            }
                            if (isset ($_POST['submit'])) {
                                $id = $_POST['id'];
                                $sql = "DELETE FROM `requests` WHERE `id`='$id'";
                                mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
                                echo '<div class="alert alert-danger" role="alert">Požiadavka bola vymazaná.</div>';
                            }
                            ?>
                            <form method="post" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Vyberte</label>
                                    <div class="col-sm-9">
                                        <select id="type" name="subject" class="form-control mb-3" required>
                                            <option value="H">Práca</option>
                                            <option value="D">Dovolenka</option>
                                            <option value="PD">Poldňová dovolenka</option>
                                            <option value="L">Návšteva lekára</option>
                                            <option value="LC">Návšteva lekára s čl. rodiny</option>
                                            <option value="CH">Choroba</option>
                                            <option value="SC">Služobná cesta</option>
                                            <option value="NV">Náhradné voľno</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Dátum:</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="from" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row time">
                                    <label class="col-sm-3 form-control-label">Od:</label>
                                    <div class="col-sm-9">
                                        <input type="time" name="time" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row time_to">
                                    <label class="col-sm-3 form-control-label">Do:</label>
                                    <div class="col-sm-9">
                                        <input type="time" name="time_to" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row date_to">
                                    <label class="col-sm-3 form-control-label">Do:</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="to" class="form-control"">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3" for="poznamka">Poznámka</label>
                                    <textarea class="col-sm-9 form-control" name="poznamka" rows="3"></textarea>
                                </div>
                                <input type="hidden" name="id" class="hidden" value="<?= $_SESSION['id']; ?>">
                                <button type="submit" name="add" class="btn btn-primary">Odoslať</button>
                            </form>
                            <div class="mt-4">
                                <div class="title"><strong>Vaše požiadavky</strong></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th>Typ</th>
                                            <th>Dátum</th>
                                            <th>Dni</th>
                                            <th>Stav</th>
                                            <th>Akcia</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?
                                        $id = $_SESSION['id'];
                                        foreach (get_user_requests($id) as $e) {
                                            $type = $e['type'];
                                            echo '<tr>';
                                            echo '<td>' . d_type($e['type']) . '</td>';
                                            if ($type == 'H' || $type == 'SC' || $type == 'L' || $type == 'LC') {
                                                echo '<td>' . req_date(nd($e['date']), nd($e['date_to'])) . ' - (' . $e['time'] . ' - ' . $e['time_to'] . ')' . '</td>';
                                            } else {
                                                echo '<td>' . req_date(nd($e['date']), nd($e['date_to'])) . '</td>';
                                            }
                                            echo '<td>' . business_days_diff($e['date'], $e['date_to']) . '</td>';
                                            echo '<td>' . status($e['status']) . '</td>';
                                            echo '<td>
                                        <form method="post" class="sw-delete" data-flag="0">
                                        <input type="hidden" id="uid" name="id" class="hidden" value="' . $e['id'] . '">
                                        <button type="submit" class="btn btn-danger btn-sm mr-1" name="delete"><span class="d-none d-sm-block mr-1">Vymazať</span>
                                        <i class="d-block d-sm-none fas fa-times"></i></a>
                                        </form>
                                        </td>
                                        </tr>';
                                        }
                                        ?>
                                        </tbody>
                                    </table>
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
