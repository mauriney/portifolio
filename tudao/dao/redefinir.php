<?php

@session_start();
include_once('../functions/geral.php');
include_once('../assets/plugins/phpmailer/class.smtp.php');
include_once('../assets/plugins/phpmailer/class.phpmailer.php');
include_once('../conf/sistema.php');

$db = Conexao::getInstance();

$msg = "";

$db->beginTransaction();

try {

  $email = $_POST['email'];

//PEGA OS DADOS DO USUÁRIO DE ACORDO COM O SEU E-MAIL
  $sql = $db->prepare("SELECT * FROM pessoas p
          WHERE p.email = ? AND p.status = 1");
  $sql->bindValue(1, $email);
  $sql->execute();

  $id = $db->lastInsertId();

//PEGAR O TOTAL DE DADOS RETORNADOS
  $totalresultado = $sql->rowCount();

  if ($totalresultado > 0) {

    $assunto = utf8_decode("Pedido/Recuperação de senha");
    $codigo = str_replace(" ", "", microtime());

    $stmt = $db->prepare("DELETE FROM recupera_senha WHERE email = ? AND alterada IS NULL");
    $stmt->bindValue(1, $email);
    $stmt->execute();

    $stmt = $db->prepare("INSERT INTO recupera_senha (email, codigo, datasolicitacao, alterada, expira, data_cadastro)
          VALUES (?, ?, NOW(), NULL, DATE_ADD(NOW(), INTERVAL 2 DAY ), NOW())");
    $stmt->bindValue(1, $email);
    $stmt->bindValue(2, $codigo);
    $stmt->execute();

    $db->commit();

    $mensagem = '<html>
				<head>
					<title>SISTEMA :: Nome do Sistema</title>
					<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet"> 
				</head>
				<body>
					<table cellpadding="0" cellspacing="0;" style="width: 100%; border: solid 1px #00BBD3; padding: 5px;">
						<thead>
							<tr>
								<th style="text-align: left; position: relative; background: url(http://sihab.ac.gov.br/sihab/assets/img/background/cidade-povo.jpg) no-repeat center center rgba(0,0,0,0.5); background-size: cover; padding: 50px 10px; font-size: 20px; border-bottom: solid 5px #00BBD3;">
									<div style="display: block; top:0; left:0; position: absolute; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);"></div>
									<img src="http://sihab.ac.gov.br/sihab/assets/img/sihab_color.png" border="0" style="width: 150px; height: 70px;" />
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="padding: 20px 50px; font-family: Ubuntu, sans-serif;">
									<h1 style="font-family: Ubuntu, sans-serif; font-weight: 500; font-size:24px; text-align: center;">
										Seja Bem-Vindo ao</h1>
                                    <h2 style="font-family: Ubuntu, sans-serif; font-weight: 300; font-size:20px; text-align: center;">
                                    	Sistema Integrado de Habitação - SIHAB</h2>
                                    <p style="font-family: Ubuntu, sans-serif; font-weight: 300; font-size:16px; text-align: center">Envio de alteração de senha para acesso ao sistema: <br><br> <a style="color: #00BBD3; text-transform: uppercase;font-family: Ubuntu, sans-serif; font-weight: 700; text-decoration: none;" href="' . PORTAL_URL . 'recuperar_senha.php?id=' . $codigo . '">clique aqui para alterar</a></p>
                                </td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th style="border-top: solid 1px rgba(0, 0, 0, 0.05); padding: 10px 0;">
									<img src="http://sihab.ac.gov.br/sihab/assets/img/logo-verde-governo.png" border="0" style="width: 200px; height: 40px;" />
								</th>
							</tr>
						</tfoot>
                </table>
            </body>

		</html>';


    envia_email($email, $assunto, utf8_decode($mensagem), "sihab@ac.gov.br", "sistema");

    //MENSAGEM DE SUCESSO
    $msg['id'] = $id;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Solicitação enviada com sucesso, por favor acesse seu e-mail para redefinir sua senha.';
    echo json_encode($msg);
    exit();
  } else {
    $msg['msg'] = 'error';
    $msg['retorno'] = 'E-mail não encontrado no sistema, por favor entre em contato com o administrador ou cadastre um novo usuário.';
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar solicitar a redefinição de senha. :" . $e->getMessage();
  echo json_encode($msg);
  exit();
}