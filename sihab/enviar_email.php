<?php

include_once ('conf/config.php');
include_once ('assets/plugins/phpmailer/class.smtp.php');
include_once ('assets/plugins/phpmailer/class.phpmailer.php');
include_once ('utils/funcoes.php');

$assunto = utf8_decode("Sihab/Primeiro Acesso");
$codigo = str_replace(" ", "", microtime());

$mensagem = '<html>
				<head>
					<title>SIHAB :: Sistema Integrado de Habitação</title>
					<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet"> 
				</head>
				<body>
					<table cellpadding="0" cellspacing="0;" style="width: 100%; border: solid 1px #00BBD3; padding: 5px;">
						<thead>
							<tr>
								<th style="text-align: left; position: relative; background: url() no-repeat center center rgba(0,0,0,0.5); background-size: cover; padding: 50px 10px; font-size: 20px; border-bottom: solid 5px #00BBD3;">
									<div style="display: block; top:0; left:0; position: absolute; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);"></div>
									<img src="" border="0" style="width: 150px; height: 70px;" />
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
                                    <p style="font-family: Ubuntu, sans-serif; font-weight: 300; font-size:16px; text-align: center;">No primeiro acesso você deve cadastrar sua senha.</p> <br>
                                    <p style="font-family: Ubuntu, sans-serif; font-weight: 300; font-size:16px; text-align: center">Você deve cadastrar sua senha no link: <br><br> <a style="color: #00BBD3; text-transform: uppercase;font-family: Ubuntu, sans-serif; font-weight: 700; text-decoration: none;" href="' . PORTAL_URL . 'recuperar_senha.php?id=' . $codigo . '">clique aqui para cadastrar</a></p>
                                </td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th style="border-top: solid 1px rgba(0, 0, 0, 0.05); padding: 10px 0;">
									<img src="" border="0" style="width: 200px; height: 40px;" />
								</th>
							</tr>
						</tfoot>
                </table>
            </body>

		</html>';

//$mensagem = '<html>
//				<head>
//					<title>SIHAB :: Sistema Integrado de Habitação</title>
//					<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet"> 
//				</head>
//				<body>
//					<table cellpadding="0" cellspacing="0;" style="width: 100%; border: solid 1px #00BBD3; padding: 5px;">
//						<thead>
//							<tr>
//								<th style="text-align: left; position: relative; background: url(http://sihab.ac.gov.br/sihab/assets/img/background/cidade-povo.jpg) no-repeat center center rgba(0,0,0,0.5); background-size: cover; padding: 50px 10px; font-size: 20px; border-bottom: solid 5px #00BBD3;">
//									<div style="display: block; top:0; left:0; position: absolute; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);"></div>
//									<img src="http://sihab.ac.gov.br/sihab/assets/img/sihab_color.png" border="0" style="width: 150px; height: 70px;" />
//								</th>
//							</tr>
//						</thead>
//						<tbody>
//							<tr>
//								<td style="padding: 20px 50px; font-family: Ubuntu, sans-serif;">
//									<h1 style="font-family: Ubuntu, sans-serif; font-weight: 500; font-size:24px; text-align: center;">
//										Seja Bem-Vindo ao</h1>
//                                    <h2 style="font-family: Ubuntu, sans-serif; font-weight: 300; font-size:20px; text-align: center;">
//                                    	Sistema Integrado de Habitação - SIHAB</h2>
//                                    <p style="font-family: Ubuntu, sans-serif; font-weight: 300; font-size:16px; text-align: center;">No primeiro acesso você deve cadastrar sua senha.</p> <br>
//                                    <p style="font-family: Ubuntu, sans-serif; font-weight: 300; font-size:16px; text-align: center">Você deve cadastrar sua senha no link: <br><br> <a style="color: #00BBD3; text-transform: uppercase;font-family: Ubuntu, sans-serif; font-weight: 700; text-decoration: none;" href="' . PORTAL_URL . 'recuperar_senha.php?id=' . $codigo . '">clique aqui para cadastrar</a></p>
//                                </td>
//							</tr>
//						</tbody>
//						<tfoot>
//							<tr>
//								<th style="border-top: solid 1px rgba(0, 0, 0, 0.05); padding: 10px 0;">
//									<img src="http://sihab.ac.gov.br/sihab/assets/img/logo-verde-governo.png" border="0" style="width: 200px; height: 40px;" />
//								</th>
//							</tr>
//						</tfoot>
//                </table>
//            </body>
//
//		</html>';

if (envia_email("orinac@hotmail.com", $assunto, utf8_decode($mensagem), "sihab@ac.gov.br", "sihab")) {
  echo "Enviou o e-mail.";
} else {
  echo "Não enviou o e-mail.";
}
?>

