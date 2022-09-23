<footer class="footer">
    <div class="footer__block block no-margin-bottom">
        <div class="container-fluid text-center">
            <p class="no-margin-bottom">2019 &copy; DIAGO SF s.r.o. </p>
        </div>
    </div>
</footer>
</div>
</div>
<!-- JavaScript files-->

<script src="<?= $url; ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= $url; ?>vendor/popper.js/umd/popper.min.js"></script>
<script src="<?= $url; ?>vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= $url; ?>vendor/jquery.cookie/jquery.cookie.js"></script>
<script src="<?= $url; ?>vendor/chart.js/Chart.min.js"></script>
<script src="<?= $url; ?>vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="<?= $url; ?>js/front.js"></script>
<script>
    jQuery(document).ready(function($) {
        $('*[data-href]').on('click', function() {
            window.location = $(this).data("href");
        });
    });
</script>
