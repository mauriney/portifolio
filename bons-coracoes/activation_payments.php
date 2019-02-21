<div class="card payments">
  <div class="row">
    <div class="col-xs-10"><h1>Dados de Pagamentos</h1></div>
    <div class="col-xs-2"><a href="#" class="back" title="Voltar"><i class="co-reply"></i></a></div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <label>
        <span class="default"><i class="co-check-mark"></i></span>
        <img src="assets/img/activation/mastercard.svg" alt="" />
        <input type="radio" name="opcao" value="">
        <span>Cartão final: <strong>8345</strong></span>
        <ul>
          <li><a href="#"><i class="co-edit"></i></a></li>
          <li><a href="#"><i class="co-garbage1"></i></a></li>
          <!-- <li><a href="#"><i class="co-check"></i></a></li> -->
        </ul>
      </label>
    </div>
    <div class="col-md-3">
      <label>
        <img src="assets/img/activation/visa.svg" alt="" />
        <input type="radio" name="opcao" value="">
        <span>Cartão final: <strong>8345</strong></span>
        <ul>
          <li><a href="#"><i class="co-edit"></i></a></li>
          <li><a href="#"><i class="co-garbage1"></i></a></li>
          <li><a href="#"><i class="co-check"></i></a></li>
        </ul>
      </label>
    </div>
    <div class="col-md-3">
      <label>
        <img src="assets/img/activation/elo.svg" alt="" />
        <input type="radio" name="opcao" value="">
        <span>Cartão final: <strong>8345</strong></span>
        <ul>
          <li><a href="#"><i class="co-edit"></i></a></li>
          <li><a href="#"><i class="co-garbage1"></i></a></li>
          <li><a href="#"><i class="co-check"></i></a></li>
        </ul>
      </label>
    </div>
    <div class="col-md-3">
      <label>
        <img src="assets/img/activation/paypal1.svg" alt="" />
        <input type="radio" name="opcao" value="">
        <span>seuemail@gmail.com</span>
        <ul>
          <li><a href="#"><i class="co-edit"></i></a></li>
          <li><a href="#"><i class="co-garbage1"></i></a></li>
          <li><a href="#"><i class="co-check"></i></a></li>
        </ul>
      </label>
    </div>
    <div class="col-md-3">
      <label>
        <img src="assets/img/activation/boleto.svg" alt="" />
        <input type="radio" name="opcao" value="">
        <span>Desconto de <strong>5%</strong></span>
        <ul>
          <li><a href="#"><i class="co-edit"></i></a></li>
          <li><a href="#"><i class="co-garbage1"></i></a></li>
          <li><a href="#"><i class="co-check"></i></a></li>
        </ul>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <a href="#" class="btn btn-default btn-lg" data-toggle="modal" data-target="#new-method">Adicionar novo método</a>
    </div>
  </div>
</div>
<?php include 'modal_new_payment.php'; ?>
