  <footer>
    <div class="container">
      <ul class="menu-footer">
        <li><a href="#">Bons Corações</a></li>
        <li><a href="#">Funcionamento</a></li>
        <li><a href="#">Comunidade</a></li>
        <li><a href="#">Unir-se</a></li>
      </ul>
      <hr>
      <a href="#" class="logo-footer"><img src="assets/img/logo-footer.png" alt=""></a>
      <p class="copyright">Copyright © 2018 Bons Corações :: Somos o legado do Mundo bem melhor</p>
      <p class="developed">Desenvolvido por <a href="#">Netxs Idéias</a></p>
    </div>
  </footer>
  <script src="assets/js/jquery-1.10.2.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/pushy.min.js" type="text/javascript"></script>
  <!-- PLUGINS -->
  <script src="assets/plugins/datapicker/js/bootstrap-datepicker.js"></script>
  <script src="assets/plugins/datapicker/locales/bootstrap-datepicker.pt-BR.min.js"></script>
	<script src="assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
  <script type="text/javascript">
  $('.modal-calendar-new input').datepicker({
    format: "dd/mm/yyyy",
    todayBtn: "linked",
    language: "pt-BR",
    forceParse: false,
    calendarWeeks: true,
    autoclose: true,
    todayHighlight: true
  });
  $('#sandbox-container div').datepicker({
    format: "dd/mm/yyyy",
    todayBtn: "linked",
    language: "pt-BR",
    forceParse: false,
    calendarWeeks: true,
    autoclose: true,
    todayHighlight: true
  });
  $('.form').find('input, textarea').on('keyup blur focus', function (e) {
    var $this = $(this),
        label = $this.prev('label');
      if (e.type === 'keyup') {
        if ($this.val() === '') {
            label.removeClass('active highlight');
          } else {
            label.addClass('active highlight');
          }
      } else if (e.type === 'blur') {
        if( $this.val() === '' ) {
          label.removeClass('active highlight');
        } else {
          label.removeClass('highlight');
        }
      } else if (e.type === 'focus') {

        if( $this.val() === '' ) {
          label.removeClass('highlight');
        }
        else if( $this.val() !== '' ) {
          label.addClass('highlight');
        }
      }
    });
    $('.tab a').on('click', function (e) {
    e.preventDefault();
    $(this).parent().addClass('active');
    $(this).parent().siblings().removeClass('active');
    target = $(this).attr('href');
    $('.tab-content > div').not(target).hide();
    $(target).fadeIn(600);
    });

    $('.carousel').carousel();
    
  </script>
</body>

</html>
