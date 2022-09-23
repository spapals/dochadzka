<?
require_once 'functions.php';
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', TRUE);
?>
<!DOCTYPE html>
<html>
<? require_once __DIR__ . '/inc/header.php';
?>
<div class="d-flex align-items-stretch">
    <? require_once __DIR__ . '/inc/sidebar.php'; ?>
    <div class="page-content">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Nastavenia</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Domov</a></li>
                <li class="breadcrumb-item active">Nastavenia</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <?
                            if (isset ($_POST['add'])) {
                                $id = $_POST['id'];
                                $p = $_POST['password'];
                                $pc = $_POST['password-conf'];
                                $pass = bcrypt($p);
                                if ($p == $pc) {
                                    $sql = "UPDATE `employees` SET `password` = '$pass' WHERE `id` = '$id'";
                                    mysqli_query(db(), $sql) or die("Error: " . mysqli_error(db()));
                                    echo '<div class="alert alert-success" role="alert">Vaše heslo bolo úspešne zmenené.</div>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">Heslá sa nezhodujú, skúste to prosím znova.</div>';
                                }
                            }
                            ?>
                            <div class="title"><strong>Nastavenie</strong></div>
                            <h5>Meno: <?= name($_SESSION['id']); ?></h5>
                            <form method="post" class="form-horizontal mt-4">
                                <div class="form-group row">
                                    <label class="col-sm-2" for="poznamka">Nové heslo</label>
                                    <input type="password" class="col-sm-3 form-control" name="password">
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-sm-2" for="poznamka">Potvrďte heslo</label>
                                    <input type="password" class="col-sm-3 form-control" name="password-conf">
                                </div>
                                <input type="hidden" name="id" class="hidden" value="<?= $_SESSION['id']; ?>">
                                <button type="submit" name="add" class="btn btn-primary">Odoslať</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    <? require_once __DIR__ . '/inc/footer.php'; ?>
    </body>
</html>
