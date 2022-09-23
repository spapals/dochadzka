<?
require_once 'functions.php';
?>
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
                <h2 class="h5 no-margin-bottom">Pridať záznam</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Domov</a></li>
                <li class="breadcrumb-item active">Pridať záznam</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title"><strong>Pridať záznam</strong></div>
                            <? if (isset ($_POST['add'])) {
                                add_attendance($_POST);
                            }
                            ?>
                            <form method="post" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Vyberte zamestnanca</label>
                                    <div class="col-sm-9">
                                        <select id="worker" name="worker" class="form-control mb-3" required>
                                            <? foreach (get_employees() as $emp) {
                                                {
                                                    echo '<option value="' . $emp['id'] . '">' . $emp['surname'] . ' ' . $emp['name'] . '</option>';
                                                }
                                            } ?>
                                            <option value="all">Všetci</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Vyberte typ</label>
                                    <div class="col-sm-9">
                                        <select id="type" name="type" class="form-control mb-3" required>
                                            <option value="H">Práca</option>
                                            <option value="D">Dovolenka</option>
                                            <option value="PD">Poldňová dovolenka</option>
                                            <option value="L">Návšteva lekára</option>
                                            <option value="LC">Návšteva lekára s čl. rodiny</option>
                                            <option value="CH">Choroba</option>
                                            <option value="SC">Služobná cesta</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Dátum:</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="date" class="form-control" required>
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
                                        <input type="date" name="date_to" class="form-control"">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3" for="poznamka">Poznámka</label>
                                    <textarea class="col-sm-9 form-control" name="poznamka" rows="3"></textarea>
                                </div>
                                <button type="submit" name="add" class="btn btn-primary">Odoslať</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    <? require_once __DIR__ . '/inc/footer.php'; ?>
    <script>
        $('select[name="worker"]').change(function () {
            var selected_worker = $(this).val();
            sessionStorage.setItem('worker', selected_worker);
        });
        $(document).ready(() => {
            var worker = sessionStorage.getItem('worker');
            $("#worker").val(worker);
        });
    </script>
    </body>
</html>
