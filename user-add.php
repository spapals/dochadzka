<?
require_once 'functions.php';
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', TRUE);
?>
<!DOCTYPE html>
<html>
<? require_once __DIR__ . '/inc/header.php';
if (!$_SESSION['is_admin']) {
    header("Location: $url");
}?>
<div class="d-flex align-items-stretch">
    <? require_once __DIR__ . '/inc/sidebar.php'; ?>
    <div class="page-content">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Pridať nového zamestnanca</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Domov</a></li>
                <li class="breadcrumb-item active">Pridať nového zamestnanca</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <?
                            if (isset ($_POST['add'])) {
                                $user = add_user($_POST);
                                if ($user) {
                                    echo '<div class="alert alert-success" role="alert">
                                            Úspešne ste pridali nového zamestnanca.<br>Prihl. meno: ' . $user['login'] . ' a heslo: ' . $user['password'] . '
                                          </div>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">
                                            Nastala chyba pri pridávaní nového zamestnanca.
                                          </div>';
                                }
                            }
                            ?>
                            <form method="post" class="form-horizontal mt-4">
                                <div class="form-group row d-print-none">
                                    <label class="col-sm-2" for="poznamka">Meno</label>
                                    <input type="text" class="col-sm-3 form-control" name="meno" required>
                                </div>
                                <div class="form-group row mb-4 d-print-none">
                                    <label class="col-sm-2" for="poznamka">Priezvisko</label>
                                    <input type="text" class="col-sm-3 form-control" name="priezvisko" required>
                                </div>
                                <div class="form-check d-print-none">
                                    <input type="checkbox" class="form-check-input" name="is_admin">
                                    <label class="form-check-label" for="is-admin">Administrátor</label>
                                </div>
                                <div class="form-check d-print-none">
                                    <input type="checkbox" class="form-check-input" name="time_edit">
                                    <label class="form-check-label" for="is-admin">Môže editovať čas</label>
                                </div>
                                <div class="form-check d-print-none">
                                    <input type="checkbox" class="form-check-input" name="force_time">
                                    <label class="form-check-label" for="is-admin">Zaokrúhľovanie času</label>
                                </div>
                                <button type="submit" name="add" class="btn btn-primary d-print-none">Pridať</button>
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
