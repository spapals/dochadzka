<!DOCTYPE html>
<html>
<? require_once __DIR__ . '/inc/header.php';
if (!$_SESSION['is_admin']) {
    header("Location: $url");
} ?>
<div class="d-flex align-items-stretch">
    <? require_once __DIR__ . '/inc/sidebar.php'; ?>
    <div class="page-content">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Import</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Domov</a></li>
                <li class="breadcrumb-item active">Import</li>
                <?
                print_r(alerts('0'));
                ?>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="block">
                    <div class="row">
                        <div class="form-group row">
                            <label class="col-sm-3 form-control-label">Vyberte súbor</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button data-toggle="dropdown" type="button"
                                                class="btn btn-outline-secondary">Vyberte <i class="fad fa-angle-down"></i></button>
                                        <div class="dropdown-menu">
                                            <?
                                            foreach (scandir('data/') as $filename) {
                                                if (substr(strrchr($filename, "."), 1) === 'xlsx') {
                                                    echo "<a href='f2db?file=" . $filename . "' class='" . "class='dropdown-item'>" . $filename . "</option>";
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control">
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