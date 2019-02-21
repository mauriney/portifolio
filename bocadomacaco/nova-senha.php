<?php
@session_start();

include_once('conf/config.php');
include_once('utils/funcoes.php');

$db = Conexao::getInstance();

// VERIFICAÇÕES DE SESSÕES
if (isset($_SESSION['usuario'])) {
    echo "<script>window.location = '" . PORTAL_URL . "index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta http-equiv="cache-control"   content="no-cache" />
        <meta http-equiv="pragma" content="no-cache" />

        <title><?php echo TITULOSISTEMA; ?></title>

        <!-- BEGIN CSS TEMPLATE -->
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
        <!-- END CSS TEMPLATE -->

        <!-- FONTES -->
        <link href="fontes/fontes.css" rel="stylesheet" />

        <!-- CSS DA NOTIFICAÇÃO EM IMPROMPT -->
        <link rel="stylesheet" media="all" type="text/css" href="plugins/jQuery-Impromptu-master/dist/jquery-impromptu.css" />


        <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>

        <script type="text/javascript" src="plugins/boostrapv3/js/bootstrap.min.js"></script>

        <script type="text/javascript" src="plugins/datatables-responsive/js/datatables.responsive.min.js"></script>
        <script type="text/javascript" src="plugins/datatables-responsive/js/lodash.min.js"></script>

        <script type="text/javascript" src="plugins/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
        <script type="text/javascript" src="plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>

        <!-- JAVASCRIPT PARA PESQUISA DINÂMICA -->
        <script type="text/javascript" src="js/pesquisa_dinamica.js"></script>

        <!-- Select2 -->
        <link type="text/css" rel="stylesheet" media="all" href="plugins/bootstrap-select2/select2.min.js">
        <script type="text/javascript" src="plugins/locastyle/js/locastyle.js"></script>

        <!-- Máscaras -->
        <script type="text/javascript" src="js/jquery.maskedinput.js"></script>
        <script type="text/javascript" src="js/mascara.js"></script>

        <!-- JS DO DATAPICKER -->
        <script src="plugins/datepicker-personalizado/js/kendo.all.min.js"></script>

        <!-- JAVASCRIPT DA NOTIFICAÇÃO EM IMPROMPT --> 
        <script type="text/javascript" src="plugins/jQuery-Impromptu-master/dist/jquery-impromptu.js"></script>
        <!-- FIM JAVASCRIPT DA NOTIFICAÇÃO EM IMPROMPT -->

        <script type="text/javascript">
            $(document).ready(function() {

                $('#cadastro_form').submit(function() {

                    var cadastro_valido = cadastro_validator();

                    if (cadastro_valido) {

                        $.ajax({
                            type: "POST",
                            url: 'nova-senha-efetivar.php',
                            data: $('#cadastro_form').serialize(),
                            cache: false,
                            success: function(obj) {
                                obj = JSON.parse(obj);
                                if (obj.msg == 'success') {
                                    msg_imprompt("Senha alterada com sucesso!", "index.php");
                                } else if (obj.msg == 'error') {
                                    $('div#div_senha').after('<label id="erro_senha" class="error">' + obj.retorno + '</label>');
                                }
                            },
                            error: function(obj) {
                                $.prompt(obj.retorno);
                            }
                        });
                        return false;
                    } else {
                        return false;
                    }
                });
            });
            //VALIDATOR DO CADASTRO
            function cadastro_validator() {
                var valido = true;
                var confirmasenha = $("#confirmasenha").val();
                var senha = $("#senha").val();

                //LIMPA MENSAGENS DE ERRO
                $('label#erro_confirmasenha').remove();
                $('label#erro_senha').remove();

                //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS
                if (senha == "") {
                    $('div#div_senha').after('<label id="erro_senha" class="error">O campo senha é obrigatório.</label>');
                    valido = false;
                }

                if (confirmasenha == "") {
                    $('div#div_confirmasenha').after('<label id="erro_confirmasenha" class="error">O campo confirmar senha é obrigatório.</label>');
                    valido = false;
                }

                if (senha != "" && confirmasenha != "" && senha != confirmasenha) {
                    $('div#div_senha').after('<label id="erro_senha" class="error">A senha e confirmação de senha não coincidem.</label>');
                    $('div#div_confirmasenha').after('<label id="erro_confirmasenha" class="error">A senha e confirmação de senha não coincidem.</label>');
                    valido = false;
                }

                return valido;
            }

            function msg_imprompt(msg, url) {
                var temp = {
                    state0: {
                        html: '<p>' + msg + '</p>',
                        buttons: {Ok: 1},
                        focus: 2,
                        submit: function(e, v, m, f) {
                            if (v == 1)
                                window.location.href = url;
                            return false;
                        }
                    }
                }
                $.prompt(temp);
            }
        </script>
    </head>
    <body class="esqueceu">

        <div class="topo">
            <div class="logo">Logo</div>
        </div>

        <div class="corpo">

            <a href="index.php" title="Voltar" class="bt-voltar">Voltar</a>
            <section id="login" class="recuperar-senha">

                <form id="cadastro_form" action="#" method="post">
                    <?php
                    //VARIAVEL UNIVERSAL
                    $msg = '';
                    if (isset($_GET['codigo'])) {

                        $codigo = $_GET['codigo'];
                        $decode = base64_decode($codigo);
                        $separa = explode('@@@', $decode);
                        $email = $separa[0];
                        $codigo = $separa[1];

                        //PEGAR OS DADOS DE REDEFINICÃO DA SENHA DE ACORDO O CODIGO INFORMADO
                        $sql = $db->prepare("SELECT ( expira < NOW() ) AS menor FROM x_recuperarsenha WHERE email = ? AND codigo = ? AND alterada IS NULL");
                        $sql->bindValue(1, $email);
                        $sql->bindValue(2, $codigo);
                        $sql->execute();

                        $dadosDefinicao = $sql->fetch(PDO::FETCH_ASSOC);
                        //PEGAR O TOTAL DE DADOS RETORNADOS
                        $totalresultado = $sql->rowCount();
                        if ($totalresultado == 0) {
                            ?>
                            <nav class=''>Esse link já foi utilizado para alteração de senha. Caso seja necessário a alteração novamente da senha <a href="esqueceu-senha.php">clique aqui</a></nav>
                            <?php
                        } else {
                            if ($dadosDefinicao['menor'] == 1) {
                                ?>
                                <nav class=''>Seu código expirou. Caso seja necessário alterar a senha <br><a href="esqueceu-senha.php">clique aqui</a></nav>
                                <?php
                            } else {
                                $sql2 = $db->prepare("SELECT IdUsuario as id, Nome as nome FROM tb_bsc_usuario WHERE Email = ?");
                                $sql2->bindValue(1, $email);
                                $sql2->execute();

                                $dadosUsuario = $sql2->fetch(PDO::FETCH_ASSOC);
                                //SETANDO O ID DO USUARIO
                                $idusuario = $dadosUsuario['id'];
                                ?>
                                <nav class="">Digite sua nova senha de acesso ao sistema</nav>
                                <div class="campos recuperar-senha">

                                    <div id="div_senha" class="input-group senha borda-senha">
                                        <span class="input-group-addon primary">				  
                                            <span class="arrow"></span>
                                            <img src="img/senha-login.svg" border="0" />
                                        </span>
                                        <input type="password" id="senha" name="senha" placeholder="senha">
                                    </div>

                                    <div id="div_confirmasenha" class="input-group senha borda-senha">
                                        <span class="input-group-addon primary">				  
                                            <span class="arrow"></span>
                                            <img src="img/senha-login.svg" border="0" />
                                        </span>
                                        <input type="password" class="margin-top-0-3em" id="confirmasenha" name="confirmasenha" placeholder="confirme sua senha">
                                    </div>

                                    <div class="botao">
                                        <button type="submit" name="enviar" id="botao_entrar" class="btn bt-entrar">SALVAR</button>
                                    </div>
                                    <input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>" />
                                    <input type="hidden" name="codigo" value="<?php echo $codigo; ?>" />
                                    <input type="hidden" name="email" value="<?php echo $email; ?>" />
                                </div>
                                <?php
                            }//END IF
                        }//END IF
                    }//END IF 
                    ?>
                </form>
            </section>

            <!-- RODAPÉ -->
            <footer class="rodape-login">
                <div>
                    <div class="nome-estado">xxx <br /> xxx</div>
                    <div class="logo-estado"><span></span></div>
                    <div class="nome-sistema">AGENDA <br /> 2015 Todos os direitos reservados</div>
                </div>
            </footer>
            <!-- FIM RODAPÉ -->

        </div>
        <!-- FIM CORPO -->

    </body>
</html>