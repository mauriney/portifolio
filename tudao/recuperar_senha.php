<?php include('template/topo_login.php'); ?>

<?php
// VERIFICAÇÕES DE SESSÕES
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0));
$param = Url::getURL(2);
$param = $param == '' && $id != '' ? $id : $param;
$codigo = $param;

if (!is_numeric(pesquisar_tabela("id", "recupera_senha", "codigo", "=", $codigo, "AND alterada IS NULL AND expira >= NOW()"))) {
    echo "<script language='javaScript'>window.location.href='" . PORTAL_URL . "index.php'</script>";
}
?>

<div class="transparencia"></div>
<div class="register-box">
    <div class="register-logo">
        <a href="<?php PORTAL_URL; ?>login.php" class="logo"></a>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg">Recuperação de Senha</p>
        <form id="form_recuperar_senha" action="#" method="post">
            <input type="hidden" id="codigo" name="codigo" value="<?= $codigo; ?>"/>
            <div id="div_senha" class="form-group has-feedback">
                <input id="senha" name="senha" type="password" class="form-control" placeholder="Nova Senha"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div id="div_confirmar" class="form-group has-feedback">
                <input id="confirmar" name="confirmar" type="password" class="form-control" placeholder="Confirmar Nova Senha"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Alterar</button>
                </div><!-- /.col -->
                <div class="col-xs-4"></div>
            </div>
        </form>        

        <div class="social-auth-links text-center">
            <p>OU</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Entrar usando o Facebook</a>
            <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Entrar usando o Google+</a>
        </div>

        <a href="login.php" class="text-center">Eu lembrei a senha</a>
    </div><!-- /.form-box -->
</div><!-- /.register-box -->

<?php include('template/rodape_login.php'); ?>

<!-- JS DO REGISTER -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/recuperar_senha.js"></script>