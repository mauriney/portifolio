<?php

//------------------------------------------------------------------------------
//DATA: 01/08/2016 às 15:00
//NOME: Cadastro de usuário
//DESCRIÇÃO: Realiza o cadastro de usuário no banco de dados
//------------------------------------------------------------------------------
@session_start();

include_once('../../../assets/plugins/phpmailer/class.smtp.php');
include_once('../../../assets/plugins/phpmailer/class.phpmailer.php');
include_once('../../../utils/funcoes.php');
include_once('../../../conf/config.php');
$db = Conexao::getInstance();

$error = false;
$msg = array();
$mensagem = "";

$db->beginTransaction();

try {

  $usuario_id = $_POST['id'];
  $nome = $_POST['nome'];
  $cpf = $_POST['cpf'];
  $sexo = $_POST['sexo'];
  $nascimento = $_POST['nascimento'];
  $rg = $_POST['rg'];
  $uf_expedicao = $_POST['uf_expedicao'];
  $email_institucional = $_POST['email_institucional'];
  $cnh = $_POST['cnh'];
  $municipio_nascimento = isset($_POST['municipio_nascimento']) && $_POST['municipio_nascimento'] != "" ? $_POST['municipio_nascimento'] : NULL;
  $email_pessoal = $_POST['email_pessoal'];
  $celular = $_POST['celular'];
  $telefone = $_POST['telefone'];
  $cep = $_POST['cep'];
  $logradouro = $_POST['logradouro'];
  $numero = $_POST['numero'];
  $bairro = $_POST['bairro'];
  $complemento = $_POST['complemento'];
  $municipio = isset($_POST['municipio']) && $_POST['municipio'] != "" ? $_POST['municipio'] : NULL;
  $orgao = isset($_POST['orgao']) && $_POST['orgao'] != "" ? $_POST['orgao'] : NULL;
  $telefone_institucional = $_POST['telefone_institucional'];
  $setor = $_POST['setor'];
  $cargo = $_POST['cargo'];
  $data_admissao = $_POST['data_admissao'];

  $usuario_modulos = @$_POST['usuario_modulo'];

  if (isset($_POST['acesso'])) {//Ativo
    $status = 1;
  } else {//Inativo
    $status = 0;
  }

  //VERIFICA SE O CPF DIGITADO É VÁLIDO
  $id_usuario = pesquisar_tabela("id", "seg_usuario", "nome", "=", $nome, "");

  if (is_numeric($id_usuario) && $id_usuario != @$_POST['id']) {
    $error = true;
    $mensagem .= "O nome do usuário informado já existe no sistema.";
    $msg['tipo'] = "nome";
  }

  //VERIFICA SE O E-MAIL INSTITUCIONAL INFORMADO JÁ EXISTE NO SISTEMA
  $id_usuario_email = pesquisar_tabela("id", "seg_usuario", "email_institucional", "=", $email_institucional, "");

  if (is_numeric($id_usuario_email) && $id_usuario_email != @$_POST['id']) {
    $error = true;
    $mensagem .= "\nO e-mail institucional do usuário informado já existe no sistema.";
    $msg['tipo'] = "email";
  }

  //VERIFICA SE O CPF DIGITADO É VÁLIDO
  if (@!valida_cpf($cpf)) {
    $error = true;
    $mensagem .= "\nO CPF do usuário informado é inválido.";
    $msg['tipo'] = "cpf";
  }

  if ($error == false) {
    if (isset($_POST['id']) && $_POST['id'] != 0) {
      $stmt = $db->prepare("UPDATE seg_usuario SET nome = ?, cpf = ?, sexo_id = ?, data_nascimento = ?, rg = ?, uf_expedicao = ?, email_institucional = ?, cnh = ?, nasc_municipio_id = ?, email_pessoal = ?, telefone_celular = ?, telefone_fixo = ?, cep = ?,  logradouro = ?, numero = ?, bairro = ?, complemento = ?, municipio_id = ?,  unidade_organizacional_id = ?, telefone_institucional = ?, setor = ?, cargo = ?, data_admissao = ?, status = ?, login = ?, usuario_pai_id = ? WHERE id = ?");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $cpf);
      $stmt->bindValue(3, $sexo);
      $stmt->bindValue(4, convertDataBR2ISO($nascimento));
      $stmt->bindValue(5, $rg);
      $stmt->bindValue(6, $uf_expedicao);
      $stmt->bindValue(7, $email_institucional);
      $stmt->bindValue(8, $cnh);
      $stmt->bindValue(9, $municipio_nascimento);
      $stmt->bindValue(10, $email_pessoal);
      $stmt->bindValue(11, $celular);
      $stmt->bindValue(12, $telefone);
      $stmt->bindValue(13, $cep);
      $stmt->bindValue(14, $logradouro);
      $stmt->bindValue(15, $numero);
      $stmt->bindValue(16, $bairro);
      $stmt->bindValue(17, $complemento);
      $stmt->bindValue(18, $municipio);
      $stmt->bindValue(19, $orgao);
      $stmt->bindValue(20, $telefone_institucional);
      $stmt->bindValue(21, $setor);
      $stmt->bindValue(22, $cargo);
      $stmt->bindValue(23, convertDataBR2ISO($data_admissao));
      $stmt->bindValue(24, $status);
      $stmt->bindValue(25, gerar_login($email_institucional));
      $stmt->bindValue(26, $_SESSION['id']);
      $stmt->bindValue(27, $usuario_id);
      $stmt->execute();

      $msg['retorno'] = 'Usuário atualizado com sucesso!';

      //DELETANDO OS GRUPOS DOS MODULOS DO USUARIO PARA NOVA INSERCAO
      $stmtUM = $db->prepare("DELETE FROM seg_usuario_modulo_grupo WHERE usuario_id = ?");
      $stmtUM->bindValue(1, $_POST['id']);
      $stmtUM->execute();

      //DELETANDO OS MODULOS DO USUARIO PARA NOVA INSERCAO
      $stmtUM = $db->prepare("DELETE FROM seg_usuario_modulo WHERE usuario_id = ?");
      $stmtUM->bindValue(1, $_POST['id']);
      $stmtUM->execute();

      //SELECIONA TODOS O MODULOS
      $stmtM = $db->prepare("SELECT id FROM seg_modulo");
      $stmtM->execute();
      $rsM = $stmtM->fetchAll(PDO::FETCH_ASSOC);

      //SELECIONA TODOS O GRUPOS
      $stmtG = $db->prepare("SELECT id FROM seg_grupo");
      $stmtG->execute();
      $rsG = $stmtG->fetchAll(PDO::FETCH_ASSOC);

      //SALVANDO MÓDULOS DESSE USUÁRIO
      if (sizeof($usuario_modulos) > 0) {
        foreach ($rsM as $keyM => $valueM) {
          if (is_numeric($valueM['id'])) {
            $stmtIUM = $db->prepare("INSERT INTO seg_usuario_modulo (usuario_id, modulo_id, status, usuario_pai_id, data_cadastro) 
                                                VALUES (:usuario_id, :modulo_id, :status, :usuario_pai_id, NOW())");
            $stmtIUM->bindValue(':usuario_id', $usuario_id);
            $stmtIUM->bindValue(':modulo_id', $valueM['id']);
            $stmtIUM->bindValue(':status', ((in_array($valueM['id'], $usuario_modulos)) ? 1 : 0));
            $stmtIUM->bindValue(':usuario_pai_id', $_SESSION['id']);
            $stmtIUM->execute();
          }
          foreach ($rsG as $keyG => $valueG) {
            if (is_numeric($valueG['id'])) {
              $stmtIUMG = $db->prepare("INSERT INTO seg_usuario_modulo_grupo (usuario_id, modulo_id, grupo_id, status, usuario_pai_id, data_cadastro) 
                                                    VALUES (:usuario_id, :modulo_id, :grupo_id, :status, :usuario_pai_id, NOW())");
              $stmtIUMG->bindValue(':usuario_id', $usuario_id);
              $stmtIUMG->bindValue(':modulo_id', $valueM['id']);
              $stmtIUMG->bindValue(':grupo_id', $valueG['id']);
              if (isset($_POST['grupo_modulo_' . $valueM['id']])) {
                if (sizeof($_POST['grupo_modulo_' . $valueM['id']]) > 0) {
                  $stmtIUMG->bindValue(':status', ((in_array($valueG['id'], $_POST['grupo_modulo_' . $valueM['id']])) ? 1 : 0));
                } else {
                  $stmtIUMG->bindValue(':status', 0);
                }
              } else {
                $stmtIUMG->bindValue(':status', 0);
              }
              $stmtIUMG->bindValue(':usuario_pai_id', $_SESSION['id']);
              $stmtIUMG->execute();
            }
          }
        }
      }

      //DELETANDO OS OBJETOS_AÇÕES ANTERIORES
      $stmt3 = $db->prepare("DELETE FROM seg_usuario_modulo_objeto_acao WHERE usuario_id = ?");
      $stmt3->bindValue(1, $_POST['id']);
      $stmt3->execute();


      //SALVANDO O OBJETO_AÇÃO PECORRENDO PELO ARRAY
      $sql5 = $db->query("select modulo_id, objeto_id, acao_id from seg_modulo_objeto_acao");
      while ($modulo = $sql5->fetch(PDO::FETCH_ASSOC)) {
        $acoes = @$_POST['' . $modulo['modulo_id'] . '_' . $modulo['objeto_id'] . '_' . $modulo['acao_id'] . ''];

        if (isset($acoes) && $acoes != "") {
          $stmt5 = $db->prepare("INSERT INTO seg_usuario_modulo_objeto_acao (modulo_objeto_acao_id, usuario_id, data_cadastro) VALUES (?, ?, now())");
          $stmt5->bindValue(1, $acoes);
          $stmt5->bindValue(2, $usuario_id);
          $stmt5->execute();
        }
      }
    } else {
      $stmt = $db->prepare("INSERT INTO seg_usuario (nome, cpf, sexo_id, data_nascimento, rg, uf_expedicao, email_institucional, cnh, nasc_municipio_id, email_pessoal, telefone_celular, telefone_fixo, cep, logradouro, numero, bairro, complemento, municipio_id, unidade_organizacional_id, telefone_institucional, setor, cargo, data_admissao, login, data_cadastro, status, usuario_pai_id, senha) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $cpf);
      $stmt->bindValue(3, $sexo);
      $stmt->bindValue(4, convertDataBR2ISO($nascimento));
      $stmt->bindValue(5, $rg);
      $stmt->bindValue(6, $uf_expedicao);
      $stmt->bindValue(7, $email_institucional);
      $stmt->bindValue(8, $cnh);
      $stmt->bindValue(9, $municipio_nascimento);
      $stmt->bindValue(10, $email_pessoal);
      $stmt->bindValue(11, $celular);
      $stmt->bindValue(12, $telefone);
      $stmt->bindValue(13, $cep);
      $stmt->bindValue(14, $logradouro);
      $stmt->bindValue(15, $numero);
      $stmt->bindValue(16, $bairro);
      $stmt->bindValue(17, $complemento);
      $stmt->bindValue(18, $municipio);
      $stmt->bindValue(19, $orgao);
      $stmt->bindValue(20, $telefone_institucional);
      $stmt->bindValue(21, $setor);
      $stmt->bindValue(22, $cargo);
      $stmt->bindValue(23, convertDataBR2ISO($data_admissao));
      $stmt->bindValue(24, gerar_login($email_institucional));
      $stmt->bindValue(25, $status);
      $stmt->bindValue(26, $_SESSION['id']);
      $stmt->bindValue(27, sha1("sehab@2018"));
      $stmt->execute();

      //RETORNAR O ID DO USUARIO
      $usuario_id = $db->lastInsertId();

      //INSERINDO SESSÃO
      $useragent = $_SERVER['HTTP_USER_AGENT'];
      if (preg_match('|MSIE ([0-9].[0-9]{1,2})|', $useragent, $matched)) {
        $browser_version = $matched[1];
        $browser = 'IE';
      } elseif (preg_match('|Opera/([0-9].[0-9]{1,2})|', $useragent, $matched)) {
        $browser_version = $matched[1];
        $browser = 'Opera';
      } elseif (preg_match('|Firefox/([0-9\.]+)|', $useragent, $matched)) {
        $browser_version = $matched[1];
        $browser = 'Firefox';
      } elseif (preg_match('|Chrome/([0-9\.]+)|', $useragent, $matched)) {
        $browser_version = $matched[1];
        $browser = 'Chrome';
      } elseif (preg_match('|Safari/([0-9\.]+)|', $useragent, $matched)) {
        $browser_version = $matched[1];
        $browser = 'Safari';
      } else {
        $browser_version = 0;
        $browser = 'Desconhecido';
      }
      $separa = explode(";", $useragent);
      $so = $separa[1];

      $stmt1 = $db->prepare("INSERT INTO seg_sessao 
                     (usuario_id, usuario_pai_id, host, ip, navegador, sistema_operacional, numero_sessao)
                      VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt1->bindValue(1, $usuario_id);
      $stmt1->bindValue(2, $_SESSION['id']);
      $stmt1->bindValue(3, $_SERVER["SERVER_NAME"]);
      $stmt1->bindValue(4, $_SERVER['REMOTE_ADDR']);
      $stmt1->bindValue(5, $browser . " " . $browser_version);
      $stmt1->bindValue(6, $so);
      $stmt1->bindValue(7, session_id());
      $stmt1->execute();

      //ENVIAR E-MAIL COM LINK PARA CADASTRAR A SENHA E ACESSAR O SISTEMA PELA PRIMEIRA VEZ
      $assunto = utf8_decode("Sihab/Primeiro Acesso");
      $codigo = str_replace(" ", "", microtime());

      $stmt = $db->prepare("DELETE FROM seg_recupera_senha WHERE email = ? AND alterada IS NULL");
      $stmt->bindValue(1, $email_institucional);
      $stmt->execute();

      $stmt = $db->prepare("INSERT INTO seg_recupera_senha (email, codigo, datasolicitacao, alterada, expira)
          VALUES (?, ?, NOW(), NULL, DATE_ADD(NOW(), INTERVAL 2 DAY ))");
      $stmt->bindValue(1, $email_institucional);
      $stmt->bindValue(2, $codigo);
      $stmt->execute();

//      $mensagem = '<html>
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
      //envia_email($email_institucional, $assunto, utf8_decode($mensagem), "sihab@ac.gov.br", "sihab");

      $msg['retorno'] = 'Usuário cadastrado com sucesso!';

      //SELECIONA TODOS O MODULOS
      $stmtM = $db->prepare("SELECT id FROM seg_modulo");
      $stmtM->execute();
      $rsM = $stmtM->fetchAll(PDO::FETCH_ASSOC);

      //SELECIONA TODOS O GRUPOS
      $stmtG = $db->prepare("SELECT id FROM seg_grupo");
      $stmtG->execute();
      $rsG = $stmtG->fetchAll(PDO::FETCH_ASSOC);

      //SALVANDO MÓDULOS DESSE USUÁRIO
      if (sizeof($usuario_modulos) > 0) {
        foreach ($rsM as $keyM => $valueM) {
          if (is_numeric($valueM['id'])) {
            $stmtIUM = $db->prepare("INSERT INTO seg_usuario_modulo (usuario_id, modulo_id, status, usuario_pai_id, data_cadastro) 
                                                VALUES (:usuario_id, :modulo_id, :status, :usuario_pai_id, NOW())");
            $stmtIUM->bindValue(':usuario_id', $usuario_id);
            $stmtIUM->bindValue(':modulo_id', $valueM['id']);
            $stmtIUM->bindValue(':status', ((in_array($valueM['id'], $usuario_modulos)) ? 1 : 0));
            $stmtIUM->bindValue(':usuario_pai_id', $_SESSION['id']);
            $stmtIUM->execute();
          }
          foreach ($rsG as $keyG => $valueG) {
            if (is_numeric($valueG['id'])) {
              $stmtIUMG = $db->prepare("INSERT INTO seg_usuario_modulo_grupo (usuario_id, modulo_id, grupo_id, status, usuario_pai_id, data_cadastro) 
                                                    VALUES (:usuario_id, :modulo_id, :grupo_id, :status, :usuario_pai_id, NOW())");
              $stmtIUMG->bindValue(':usuario_id', $usuario_id);
              $stmtIUMG->bindValue(':modulo_id', $valueM['id']);
              $stmtIUMG->bindValue(':grupo_id', $valueG['id']);
              if (isset($_POST['grupo_modulo_' . $valueM['id']])) {
                if (sizeof($_POST['grupo_modulo_' . $valueM['id']]) > 0) {
                  $stmtIUMG->bindValue(':status', ((in_array($valueG['id'], $_POST['grupo_modulo_' . $valueM['id']])) ? 1 : 0));
                } else {
                  $stmtIUMG->bindValue(':status', 0);
                }
              } else {
                $stmtIUMG->bindValue(':status', 0);
              }
              $stmtIUMG->bindValue(':usuario_pai_id', $_SESSION['id']);
              $stmtIUMG->execute();
            }
          }
        }
      }

      //DELETANDO OS OBJETOS_AÇÕES ANTERIORES
      $stmt3 = $db->prepare("DELETE FROM seg_usuario_modulo_objeto_acao WHERE usuario_id = ?");
      $stmt3->bindValue(1, $_POST['id']);
      $stmt3->execute();

      //SALVANDO O OBJETO_AÇÃO PECORRENDO PELO ARRAY
      $sql5 = $db->query("select modulo_id, objeto_id, acao_id from seg_modulo_objeto_acao");
      while ($modulo = $sql5->fetch(PDO::FETCH_ASSOC)) {
        $acoes = @$_POST['' . $modulo['modulo_id'] . '_' . $modulo['objeto_id'] . '_' . $modulo['acao_id'] . ''];

        if (isset($acoes) && $acoes != "") {
          $stmt5 = $db->prepare("INSERT INTO seg_usuario_modulo_objeto_acao (modulo_objeto_acao_id, usuario_id, data_cadastro) VALUES (?, ?, now())");
          $stmt5->bindValue(1, $acoes);
          $stmt5->bindValue(2, $usuario_id);
          $stmt5->execute();
        }
      }
    }

    //ATUALIZANDO O NOME DA FOTO TEMPORARIA
    if (isset($_SESSION['foto_cut']) && isset($_SESSION['foto_origin']) && $_SESSION['foto_cut'] != "" && $_SESSION['foto_origin'] != "") {
      $stmt5 = $db->prepare("UPDATE seg_usuario SET foto = ? where id = ?");
      $stmt5->bindValue(1, PORTAL_URL . "" . $_SESSION['foto_cut']);
      $stmt5->bindValue(2, $usuario_id);
      $stmt5->execute();
      if ($_SESSION['id'] == $usuario_id) {//SE FOR O USUÁRIO LOGADO, ENTÃO ATUALIZAR FOTO NA SESSION
        $_SESSION['foto'] = PORTAL_URL . "" . $_SESSION['foto_cut'];
      }
      $_SESSION['foto_cut'] = "";
    }

    $db->commit();

    //MENSAGEM DE SUCESSO
    $msg['id'] = $usuario_id;
    $msg['msg'] = 'success';

    echo json_encode($msg);
    exit();
  } else {
    $msg['msg'] = 'error';
    $msg['retorno'] = $mensagem;
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar cadastrar o usuário desejado:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>