<?php
require ('conf/phpmailer/class.smtp.php');
require ('conf/phpmailer/class.phpmailer.php');
include_once('conf/config.php');
include_once('utils/funcoes.php');
$db = Conexao::getInstance();

// VERIFICAÇÕES DE SESSÕES
if (isset($_SESSION['login'])) {
    echo "<script>window.location = '" . PORTAL_URL . "index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>:: AGENDA | Login ::</title>

        <!-- BEGIN CSS TEMPLATE -->
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
        <!-- END CSS TEMPLATE -->

        <!-- FONTES -->
        <link href="fontes/fontes.css" rel="stylesheet" />

        <!-- CSS DA NOTIFICAÇÃO EM IMPROMPT -->
        <link rel="stylesheet" media="all" type="text/css" href="plugins/jQuery-Impromptu-master/dist/jquery-impromptu.css" />

    </head>
    <body class="esqueceu">

        <div class="topo">
            <div class="logo">Logo</div>
        </div>

        <div class="corpo">

            <a href="index.php" title="Voltar" class="bt-voltar">Voltar</a>

            <!-- FORMULÁRIO DE RECUPERAÇÃO DE SENHA -->
            <div class="form-email">
                <form method="post" action="">
                    <div class="input-group usuario">
                        <span class="input-group-addon primary">				  
                            <span class="arrow"></span>
                            <img src="img/basic_mail_open_text.svg" width="20px" border="0" />
                        </span>
                        <input type="text" id="email_institucional" name="email_institucional" class="form-control" placeholder="Informe seu email" />
                    </div>
                    <button id="recuperar" name="recuperar" class="btn bt-recuperar" title="Recuperar Senha" type="submit"></button>
                </form>
            </div>
            <!-- FIM FORMULÁRIO DE RECUPERAÇÃO DE SENHA -->

            <!-- RODAPÉ -->
            <footer class="rodape-login">
                <div>
                    <div class="nome-estado">Gabinete Deputado Federal Sibá Machado <br /> Desenvolvido por JR Design &
                        Tecnologia</div>
                    <div class="logo-estado"><span></span></div>
                    <div class="nome-sistema"><br /> 2015 Todos os direitos reservados</div>
                </div>
            </footer>
            <!-- FIM RODAPÉ -->
        </div>
        <!-- FIM CORPO -->
    </body>
</html>

<!-- JAVASCRIPT DA NOTIFICAÇÃO EM IMPROMPT --> 
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="plugins/jQuery-Impromptu-master/dist/jquery-impromptu.js"></script>
<!-- FIM JAVASCRIPT DA NOTIFICAÇÃO EM IMPROMPT -->
<script type="text/javascript">
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
<?php
//VARIÁVEL UNIVERSAL
$msg = '';
if (isset($_POST['email_institucional']) && @$_POST['email_institucional'] != '') {

    $email_institucional = $_POST['email_institucional'];
    //PEGA OS DADOS DO USUÁRIO DE ACORDO COM O SEU E-MAIL
    $sql = $db->prepare("SELECT * FROM tb_bsc_usuario WHERE Email = ? AND Status = 1 ");

    $sql->bindValue(1, $email_institucional);
    $sql->execute();

    //PEGAR O TOTAL DE DADOS RETORNADOS
    $totalresultado = $sql->rowCount();

    if ($totalresultado == 0) {
        echo "<script>$.prompt('Email não encontrado! por favor mande-nos um email para acre2013@gmail.com informando o assunto.');</script>";
    } else {

        $assunto = utf8_decode("Agenda / Recuperação de senha");
        $codigo = microtime();

        $stmt = $db->prepare("INSERT INTO x_recuperarsenha (email, codigo, datasolicitacao, alterada, expira)
          VALUES (?, ?, NOW(), NULL, DATE_ADD(NOW(), INTERVAL 2 DAY ))");
        $stmt->bindValue(1, $email_institucional);
        $stmt->bindValue(2, $codigo);
        $stmt->execute();

        $encode = base64_encode($email_institucional . "@@@" . $codigo);

        $msg = '<table cellpadding="0" cellspacing="0;" style="width: 100%; font-family: Arial, sans-serif; font-size: 12px;">
		<tbody>
			<tr>
				<td style="padding: 20px 50px;">
                                     Envio de recuperação de senha para acesso ao Sistema. <br><br> Você deve alterar sua senha no link: <a style="color: #00BBD3; text-transform: uppercase;" href="' . PORTAL_URL . 'nova-senha.php?codigo=' . $encode . '">clique aqui para alterar</a>
                                </td>
			</tr>
		</tbody>
                </table>';

        $envio = envia_email($email_institucional, $assunto, utf8_decode($msg), EMAIL_ENDERECO, TITULOSISTEMA) or die("ERRO GRAVE AO TENTAR RECUPERAR A SENHA");

        echo "<script>msg_imprompt('Você receberá um e-mail com instruções para alterar sua senha!','index.php');</script>";
    }//END IF
}//END IF
else if (isset($_POST['recuperar'])) {
    echo "<script>$.prompt('É necessário informar o seu e-mail cadastrado no sistema para recuperar sua senha.');</script>";
}
?>


