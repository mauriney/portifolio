<nav class="navbar navbar-bottom fixed-bottom navbar-expand-lg bg-default">
    <div class="container">
        <nav class="menu_footer flex flex-wrap">
            <a href="" class="nav-link"><i class="fas fa-qrcode"></i></a>
            <a href="" class="nav-link"></a>
            <a href="" class="nav-link"></a>
            <a href="" class="nav-link"></a>
        </nav>
    </div>
</nav>
<!--   Core JS Files   -->
<script src="<?= PORTAL_URL; ?>assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="<?= PORTAL_URL; ?>assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="<?= PORTAL_URL; ?>assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="<?= PORTAL_URL; ?>assets/js/plugins/moment.min.js"></script>
<!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
<script src="<?= PORTAL_URL; ?>assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="<?= PORTAL_URL; ?>assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
<!-- Sweetalert -->
<script src="<?= PORTAL_URL; ?>assets/plugins/sweetalert/dist/sweetalert.min.js"></script>
<!--	Plugin for Sharrre btn -->
<script src="<?= PORTAL_URL; ?>assets/js/plugins/jquery.sharrre.js" type="text/javascript"></script>
<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
<script src="<?= PORTAL_URL; ?>assets/js/material-kit.js?v=2.0.4" type="text/javascript"></script>
<script>
        $(document).ready(function () {
            //init DateTimePickers
            materialKit.initFormExtendedDatetimepickers();

            // Sliders Init
            materialKit.initSliders();
        });

        function scrollToDownload() {
            if ($('.section-download').length != 0) {
                $("html, body").animate({
                    scrollTop: $('.section-download').offset().top
                }, 1000);
            }
        }

        $(document).ready(function () {

            $('#facebook').sharrre({
                share: {
                    facebook: true
                },
                enableHover: false,
                enableTracking: false,
                enableCounter: false,
                click: function (api, options) {
                    api.simulateClick();
                    api.openPopup('facebook');
                },
                template: '<i class="fab fa-facebook-f"></i> Facebook',
                url: 'https://demos.creative-tim.com/material-kit/index.html'
            });

            $('#googlePlus').sharrre({
                share: {
                    googlePlus: true
                },
                enableCounter: false,
                enableHover: false,
                enableTracking: true,
                click: function (api, options) {
                    api.simulateClick();
                    api.openPopup('googlePlus');
                },
                template: '<i class="fab fa-google-plus"></i> Google',
                url: 'https://demos.creative-tim.com/material-kit/index.html'
            });

            $('#twitter').sharrre({
                share: {
                    twitter: true
                },
                enableHover: false,
                enableTracking: false,
                enableCounter: false,
                buttons: {
                    twitter: {
                        via: 'CreativeTim'
                    }
                },
                click: function (api, options) {
                    api.simulateClick();
                    api.openPopup('twitter');
                },
                template: '<i class="fab fa-twitter"></i> Twitter',
                url: 'https://demos.creative-tim.com/material-kit/index.html'
            });

        });
</script>
</body>

</html>