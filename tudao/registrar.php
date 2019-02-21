<?php include('template/topo_login.php'); ?>
<?php
if (isset($_SESSION['id'])) {
  echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "view/geral/modulos.php';</script>";
}
?>

<div class="limiter">
    <div class="container-login100">
        <div class="login100-more" style="background-image: url('assets/img/bg-20.jpg');"></div>

        <div class="wrap-login100 p-l-50 p-r-50 p-t-72 p-b-50">
            <form id="form_register" action="#" method="post">
                <div class="login-logo">
                    <a href="<?php PORTAL_URL; ?>login.php" class="logo"></a>
                </div>

                <span class="login100-form-title p-b-59">
                    Registre um novo usuário
                </span>

                <div id="div_nome" class="wrap-input100 validate-input" data-validate="Nome é obrigatório">
                    <span class="label-input100">Nome Completo</span>
                    <input class="input100" type="text" id="nome" name="nome" >
                    <span class="focus-input100"></span>
                </div>

                <div id="div_cpf" class="wrap-input100 validate-input" data-validate="CPF é obrigatório">
                    <span class="label-input100">CPF</span>
                    <input class="input100" type="text" id="cpf" name="cpf" data-inputmask='"mask": "999.999.999-99"' data-mask>
                    <span class="focus-input100"></span>
                </div>

                <div id="div_contato" class="wrap-input100 validate-input" data-validate="Celular é obrigatório">
                    <span class="label-input100">Celular</span>
                    <input class="input100" type="text" id="contato" name="contato" data-inputmask='"mask": "(99) 9999-9999"' data-mask>
                    <span class="focus-input100"></span>
                </div>

                <div id="div_email" class="wrap-input100 validate-input" data-validate="E-mail é obrigatório">
                    <span class="label-input100">E-mail</span>
                    <input class="input100" type="text" id="email" name="email" >
                    <span class="focus-input100"></span>
                </div>

                <div id="div_senha" class="wrap-input100 validate-input" data-validate="Senha é obrigatório">
                    <span class="label-input100">Senha</span>
                    <input class="input100" type="password" id="senha" name="senha">
                    <span class="focus-input100"></span>
                </div>

                <div id="div_confirmar" class="wrap-input100 validate-input" data-validate="Confirmação de Senha é obrigatório">
                    <span class="label-input100">Confirmar Senha</span>
                    <input class="input100" type="password" id="confirmar" name="confirmar">
                    <span class="focus-input100"></span>
                </div>

                <div class="flex-m w-full p-b-33">
                    <div id="div_termo" class="checkbox icheck">
                        <label>
                            <input id="termo" name="termo" type="checkbox"> Concordo e aceito os <a href="#">termos</a>
                        </label>
                    </div> 
                </div>
                
                <div class="flex-m w-full p-b-33">
                    <div class="contact100-form-checkbox">
                        <span class="txt1">
                            <a href="login.php">Já tenho um usuário</a>
                        </span>
                    </div>
                </div>

                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button type="submit" class="login100-form-btn ">
                            Registrar
                        </button>
                    </div>

                </div>

                <!-- <div class="social-auth-links text-center">
                    <p>OU</p>
                    <div class="row">
                        <div class="col-xs-6">
                            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Facebook</a>
                        </div>
                        <div class="col-xs-6">
                            <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Google+</a>
                        </div>
                    </div>
                </div> -->

            </form>
        </div>
    </div>
</div>

<?php include('template/rodape_login.php'); ?>

<!-- JS DO REGISTER -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/registrar.js"></script>