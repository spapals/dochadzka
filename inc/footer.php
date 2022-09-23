<footer class="footer">
    <div class="footer__block block no-margin-bottom">
        <div class="container-fluid text-center">
            <p class="no-margin-bottom"><?= date('Y') ?> &copy; DIAGO SF s.r.o. </p>
        </div>
    </div>
</footer>
</div>
</div>
<!-- JavaScript files-->
<script src="<?= $url; ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= $url; ?>js/timeago.js?v=6"></script>
<script src="<?= $url; ?>vendor/popper.js/umd/popper.min.js"></script>
<script src="<?= $url; ?>vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= $url; ?>vendor/jquery.cookie/jquery.cookie.js"></script>
<script src="<?= $url; ?>vendor/chart.js/Chart.min.js"></script>
<script src="<?= $url; ?>vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-html5-1.6.1/b-print-1.6.1/r-2.2.3/datatables.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.20/i18n/Slovak.json"></script>
<script src="<?= $url; ?>js/front.js?v=12"></script>

<script>
    jQuery(document).ready(function ($) {
        $('*[data-href]').on('click', function () {
            window.location = $(this).data("href");
        });
        $('.requests-table').DataTable({
            language: {url: 'https://cdn.datatables.net/plug-ins/1.10.20/i18n/Slovak.json'},
            "columnDefs": [
                {"orderable": false, "targets": [2, 4, 5, 6],}
            ]
        });
        ;
        $('.zamestnanci-table').dataTable({
            "pageLength": 100,
            language: {url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/sk.json'},
            "order": []
        });
        /*$('.dochadzka-zamestnanci').dataTable({
            language: {url: 'https://cdn.datatables.net/plug-ins/1.10.20/i18n/Slovak.json'},
            dom: 'Bfrtip',
            buttons: ['print'],
            "columnDefs": [
                {"orderable": false}
            ]
        });*/
    });
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
