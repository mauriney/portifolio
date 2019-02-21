</body>

<!-- Sweetalert -->
<script src="<?= PLUGINS_FOLDER; ?>sweetalert/dist/sweetalert.min.js"></script>
<!-- jQuery 2.1.4 -->
<script src="<?= PLUGINS_FOLDER; ?>jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?= ASSETS_FOLDER; ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- iCheck -->
<script src="<?= PLUGINS_FOLDER; ?>iCheck/icheck.min.js" type="text/javascript"></script>
<!-- Livequery -->
<script src="<?= JS_FOLDER ?>livequery.js" type="text/javascript"></script>
<!-- JS UTIL -->
<script src="<?= JS_FOLDER ?>utils.js" type="text/javascript"></script>
<!-- Projeto.Utils -->
<script src="<?= JS_FOLDER ?>projeto.utils.js" type="text/javascript"></script>
<!-- InputMask -->
<script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>

<!--===============================================================================================-->
<script src="<?= PLUGINS_FOLDER; ?>login/js/main.js"></script>
<!--===============================================================================================-->

<script>
  $(function () {
      $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
      });

      //Money Euro
      $("[data-mask]").inputmask();

  });
</script>
</html>