</body>
<!-- jQuery 2.1.4 -->
<script src="<?= PLUGINS_FOLDER; ?>jQuery/jQuery-2.1.4.min.js"></script>
<!-- jQuery UI 1.11.2 -->
<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?= ASSETS_FOLDER; ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>    
<!-- Morris.js charts -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<?= PLUGINS_FOLDER; ?>morris/morris.min.js" type="text/javascript"></script>
<!-- Sparkline -->
<script src="<?= PLUGINS_FOLDER; ?>sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- jvectormap -->
<script src="<?= PLUGINS_FOLDER; ?>jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
<script src="<?= PLUGINS_FOLDER; ?>jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
<!-- jQuery Knob Chart -->
<script src="<?= PLUGINS_FOLDER; ?>knob/jquery.knob.js" type="text/javascript"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>
<script src="<?= PLUGINS_FOLDER; ?>daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- datepicker -->
<script src="<?= PLUGINS_FOLDER; ?>datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?= PLUGINS_FOLDER; ?>bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<!-- Slimscroll -->
<script src="<?= PLUGINS_FOLDER; ?>slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src='<?= PLUGINS_FOLDER; ?>fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="<?= JS_FOLDER; ?>app.min.js" type="text/javascript"></script>    
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--<script src="<?= JS_FOLDER; ?>pages/dashboard.js" type="text/javascript"></script>-->   
<!-- AdminLTE for demo purposes -->
<script src="<?= JS_FOLDER; ?>demo.js" type="text/javascript"></script>
<!-- JAVASCRIPT DO SELECT2 DO LOCAWEB -->
<script src="<?= PLUGINS_FOLDER ?>bootstrap-select2/select2.js"></script>
<!-- InputMask -->
<script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<!-- Sweetalert -->
<script src="<?= PLUGINS_FOLDER; ?>sweetalert/dist/sweetalert.min.js"></script>
<!-- Livequery -->
<script src="<?= JS_FOLDER ?>livequery.js" type="text/javascript"></script>
<!-- JS UTIL -->
<script src="<?= JS_FOLDER ?>utils.js" type="text/javascript"></script>
<!-- Projeto.Utils -->
<script src="<?= JS_FOLDER ?>projeto.utils.js" type="text/javascript"></script>

<!-- MÁSCARA PARA DINHEIRO -->
<script type="text/javascript" src="<?= ASSETS_FOLDER; ?>js/jquery.price_format.1.3.js"></script>

<script>

    $('select').each(function() {
        $(this).select2();
    });

    $('.datepicker').each(function() {
        $(this).datepicker({
            format: 'mm/dd/yyyy',
            startDate: '-3d'
        });
    });

</script>

</html>