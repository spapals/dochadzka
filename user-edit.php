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
                <h2 class="h5 no-margin-bottom">Upraviť zamestnanca</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Domov</a> / <a href="/zamestnanci">Zamestnanci</a></li>
                <li class="breadcrumb-item active">Upraviť zamestnanca - <?= name($_GET['id'])?></li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <?
                            if (isset ($_POST['add'])) {
                                update_user_data($_POST);
                            }
                            $user = get_user_data($_GET['id']);
                            ?>
                            <form method="post" class="form-horizontal mt-4">
                                <div class="form-group row">
                                    <label class="col-sm-2" for="poznamka">Meno</label>
                                    <input type="text" class="col-sm-3 form-control" name="name" value="<?= $user['name'] ?>" required>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-sm-2" for="poznamka">Priezvisko</label>
                                    <input type="text" class="col-sm-3 form-control" name="surname" value="<?= $user['surname'] ?>" required>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-sm-2" for="poznamka">Heslo</label>
                                    <input type="text" class="col-sm-3 form-control" name="password" placeholder="">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_admin" <?= $user['is_admin'] ? 'checked': '';?>>
                                    <label class="form-check-label" for="is-admin">Administrátor</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="time_edit" <?= $user['time_edit'] ? 'checked': '';?>>
                                    <label class="form-check-label" for="is-admin">Môže editovať čas</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="force_time" <?= $user['force_time'] ? 'checked': '';?>>
                                    <label class="form-check-label" for="is-admin">Zaokrúhľovanie času</label>
                                </div>
                                <div class="form-check pb-3">
                                    <input type="checkbox" class="form-check-input" name="active" <?= $user['active'] ? 'checked': '';?>>
                                    <label class="form-check-label" for="is-admin">Aktívny</label>
                                </div>
                                <input type="hidden" name="id" class="hidden" value="<?= $_GET['id'];?>">
                                <button type="submit" name="add" class="btn btn-primary">Upraviť</button>
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
