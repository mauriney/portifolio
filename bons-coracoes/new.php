<?php include 'header.php'; ?>
  <section class="login">
    <div class="container">
    </div>
  </section>
  <section class="register">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div id="exTab3">
            <ul  class="nav nav-pills">
              <li class="active"><a  href="#1b" data-toggle="tab">Login</a></li>
              <li><a href="#2b" data-toggle="tab">Nova Conta</a></li>
            </ul>
            <div class="tab-content clearfix">
              <div class="tab-pane active" id="1b">
                <form class="form-horizontal" role="form">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="co-users"></i></span>
                    <input type="text" class="form-control" name="username" value="" placeholder="E-mail, telefone ou ID">
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="co-password"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="Senha">
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <a href="#" class="forgot">Esqueci minha senha</a>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                  <!-- Button -->
                    <div class="col-md-12 text-center">
                      <a href="#" class="btn btn-success btn-login">Entrar</a>
                      <hr>
                      <a href="#" class="btn btn-primary"><i class="co-facebook-logo"></i> Entrar com o Facebook</a>
                    </div>
                  </div>
                </form>
                <form class="form-horizontal" action="" method="post">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="co-new-email-outline"></i></span>
                    <input type="text" class="form-control" name="email" value="" placeholder="Informe seu e-mail">
                  </div>
                  <div class="form-group">
                  <!-- Button -->
                    <div class="col-md-12 text-center">
                      <a id="btn-login" href="#" class="btn btn-success">Enviar</a>
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane" id="2b">
                <form id="signupform" class="form-horizontal" role="form">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="co-users"></i></span>
                    <input type="text" class="form-control" name="username" value="" placeholder="Nome Completo">
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="co-phone-call"></i></span>
                    <input type="text" class="form-control" name="phone" placeholder="Telefone ou E-mail">
                  </div>
                  <div class="sex">
                    <label><strong>Sexo</strong></label><br>
                    <label class="radio-inline"><input type="radio" name="opcao">Masculino</label>
                    <label class="radio-inline"><input type="radio" name="opcao">Feminino</label>
                  </div>
                  <p>Ao clicar em Unir-se você declara que leu e concorda com os <a href="">Termos de Uso</a> e o <a href="">Manual da Oportunidade Bons Corações</a>.</p>

                  <div class="form-group">
                  <!-- Button -->
                    <div class="col-md-12 text-center">
                      <a href="#" class="btn btn-success btn-login">Unir-se</a>
                      <hr>
                      <a href="#" class="btn btn-primary btn-create"><i class="co-facebook-logo"></i> Crie uma conta com o Facebook</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <img src="assets/img/unir-seimg.png" alt="">
        </div>
      </div>
    </div>
  </section>
<?php include 'chat.php'; ?>
<?php include 'footer.php'; ?>
