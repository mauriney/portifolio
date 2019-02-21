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
            <form id="form_login" action="#" method="post">
                <div class="login-logo">
                    <a href="<?php PORTAL_URL; ?>login.php" class="logo"></a>
                </div>

                <span class="login100-form-title p-b-59">
                    Faça login para iniciar a sessão
                </span>

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

                <div class="flex-m w-full p-b-33">
                    <div class="contact100-form-checkbox">
                        <span class="txt1">
                            <a href="redefinir.php">
                                Não consegue acessar?
                            </a>
                        </span>
                    </div>
                </div>

                <div class="flex-m w-full p-b-33">
                    <div class="contact100-form-checkbox">
                        <span class="txt1">
                            <a href="registrar.php">Novo usuário ?</a>
                        </span>
                    </div>
                </div>

                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button type="submit" class="login100-form-btn ">
                            Entrar
                        </button>
                    </div>

                </div>

                <!-- <div class="social-auth-links text-center">
                    <p>OU</p>
                    <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Entrar usando o Facebook</a>
                    <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Entrar usando o Google+</a>
                </div> -->

            </form>
        </div>
    </div>
</div>

<?php include('template/rodape_login.php'); ?>

<!-- JS DO LOGIN -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/login.js"></script>