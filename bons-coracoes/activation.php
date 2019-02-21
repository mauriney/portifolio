<?php include 'header.php'; ?>
<section class="activation">
  <div class="container">
    <a href="#" class="back"><i class="co-left-arrow"></i> Voltar ao Perfil</a>
    <hr>
    <div class="row">
      <div class="col-md-3">
        <a href="#" class="stage active">
          <img src="assets/img/activation/ativacao-01.svg" alt="">
          Dados Pessoais
          <div class="status st-success">
            <i class="co-check-mark"></i>
          </div>
        </a>
        <a href="#" class="stage">
          <img src="assets/img/activation/ativacao-02.svg" alt="">
          Dados de Pagamento
          <div class="status st-success">
            <i class="co-check-mark"></i>
          </div>
        </a>
        <a href="#" class="stage">
          <img src="assets/img/activation/ativacao-03.png" alt="">
          Indicação do Consultor Raiz
          <div class="status st-danger">
            <i class="co-close"></i>
          </div>
        </a>
        <a href="#" class="stage">
          <img src="assets/img/activation/ativacao-04.png" alt="">
          Plano de Investimento
          <div class="status st-danger">
            <i class="co-close"></i>
          </div>
        </a>
      </div>
      <div class="col-md-9">
        <?php include 'activation_register.php'; ?>
      </div>
    </div>
  </div>
</section>
<?php include 'chat.php'; ?>
<?php include 'footer.php'; ?>
