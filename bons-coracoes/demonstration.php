<div class="row">
  <div class="col-md-4">
    <div class="card">
      <div class="col-xs-4">
        <img src="assets/img/demonstration/money-bag.svg" alt="">
      </div>
      <div class="col-xs-8">
        <h1>Caixa</h1>
        <strong>R$ 0,00</strong>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="col-xs-4">
        <img src="assets/img/demonstration/transaction.svg" alt="">
      </div>
      <div class="col-xs-8">
        <h1>Investimentos</h1>
        <strong>R$ 0,00</strong>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="col-xs-4">
        <img src="assets/img/demonstration/profits.svg" alt="">
      </div>
      <div class="col-xs-8">
        <h1>Rendimentos</h1>
        <strong>R$ 0,00</strong>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card grafic"></div>
  </div>
</div>
<hr>
<div class="btn-group btn-group-toggle" data-toggle="buttons">
  <label class="btn btn-danger btn-lg active">
    <input type="radio" name="options" id="option1" autocomplete="off" checked> Investimentos
  </label>
  <label class="btn btn-danger btn-lg">
    <input type="radio" name="options" id="option2" autocomplete="off"> Rendimentos
  </label>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <?php include 'demonstration_investiment.php'; ?>
    </div>
  </div>
</div>
