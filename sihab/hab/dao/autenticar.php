<?php

@session_start();
include_once('../../utils/funcoes.php');
include_once('../../conf/config.php');
$db = Conexao::getInstance();

$msg = array();

$db->beginTransaction();

try {

  //PEGAR DADOS DE LOGIN
  $login = strip_tags($_POST['login']);
  $senha = strip_tags(sha1($_POST['senha']));
  //SQL PARA VERIFICAÇÃO DE LOGIN EXISTENTE
  $result = $db->prepare("SELECT u.senha, u.id, u.nome, u.email_institucional, u.foto, uo.sigla, u.telefone_celular, u.telefone_institucional, u.status
			  FROM seg_usuario u, bsc_unidade_organizacional uo
			  WHERE u.unidade_organizacional_id = uo.id AND u.login = ? OR u.email_institucional = ?");
  $result->bindParam(1, $login);
  $result->bindParam(2, $login);
  $result->execute();
  $num = $result->rowCount();

  if ($num > 0) {
    //PEGA OS DADOS DO USUARIO, CASO TENHA ACESSO
    $dadosUsuario = $result->fetch(PDO::FETCH_ASSOC);

    //VERIFICA SE A SENHA INFORMADA É IGUAL DO USUARIO
    if ($senha == $dadosUsuario['senha']) {

      if ($dadosUsuario['status'] == 1) {

        if ($_POST['senha'] == 'sehab@2018') {//SENHA PADRÃO
          $_SESSION['redefinir_senha'] = 1;
          $_SESSION['redefinir_id'] = $dadosUsuario['id'];
          $msg['msg'] = 'redefinir';
          echo json_encode($msg);
          exit();
        } else {
          $_SESSION['redefinir_senha'] = 0;
        }

        $id = $dadosUsuario['id'];

        $_SESSION['id'] = $id;

        //CRIAR O TIMEOUT DA SESSÃO PARA EXPIRAR
        $_SESSION['timeout'] = time();
        //CRIAR AS SESSÕES DO USUARIO
        $_SESSION['id'] = $id;
        $_SESSION['nome'] = ($dadosUsuario['nome']);
        $_SESSION['email_institucional'] = $dadosUsuario['email_institucional'];
        $_SESSION['foto'] = $dadosUsuario['foto'];
        $_SESSION['sigla'] = ($dadosUsuario['sigla']);
        $_SESSION['telefone_celular'] = $dadosUsuario['telefone_celular'];
        $_SESSION['telefone_institucional'] = $dadosUsuario['telefone_institucional'];
        $_SESSION['login'] = $login;
        //STATUS ONLINE -> 1 - ONLINE e 2 - OFFLINE
        $_SESSION['online'] = 1;
        $_SESSION['foto_cut'] = "";
        $_SESSION['foto_origin'] = "";

        $_SESSION['bemvindo'] = 1;

        //ATUALIZANDO O STATUS ONLINE DO USUARIO
        $result = $db->prepare("UPDATE seg_usuario SET online = '1' WHERE id = ?");
        $result->bindValue(1, $id);
        $result->execute();

        //ATUALIZANDO DADOS DA SESSÃO DO USUÁRIO
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

        $result2 = $db->prepare("UPDATE seg_sessao SET host = ?, ip = ?, navegador = ?, sistema_operacional = ?, numero_sessao = ?, data_login = NOW(), atualizacao = NOW() WHERE usuario_id = ?");
        $result2->bindValue(1, $_SERVER["SERVER_NAME"]);
        $result2->bindValue(2, $_SERVER['REMOTE_ADDR']);
        $result2->bindValue(3, $browser . " " . $browser_version);
        $result2->bindValue(4, $so);
        $result2->bindValue(5, session_id());
        $result2->bindValue(6, $id);
        $result2->execute();

        //SALVANDO DADOS DE PERMISSÕES DO MODULO/OBJETO/AÇÃO
        $stmt = $db->prepare("SELECT m.id, LOWER(m.nome) AS nome
                                      FROM seg_modulo m
                                      LEFT JOIN seg_usuario_modulo AS um ON um.modulo_id = m.id
                                      WHERE um.usuario_id = ? AND um.status = 1");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $rsModulo = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($rsModulo AS $kModulo => $vModulo) {

          $_SESSION['permissao'][$vModulo->nome] = [];

          $stmt = $db->prepare("SELECT o.id, LOWER(o.nome) AS nome
                                        FROM seg_usuario_modulo_objeto_acao AS umoa 
                                        LEFT JOIN seg_modulo_objeto_acao AS moa ON moa.id = umoa.modulo_objeto_acao_id  
                                        LEFT JOIN seg_objeto AS o ON o.id = moa.objeto_id
                                        WHERE moa.modulo_id = ? AND umoa.usuario_id = ?");
          $stmt->bindValue(1, $vModulo->id);
          $stmt->bindValue(2, $id);
          $stmt->execute();
          $rsObjeto = $stmt->fetchAll(PDO::FETCH_OBJ);

          foreach ($rsObjeto AS $kObjeto => $vObjeto) {

            $_SESSION['permissao'][$vModulo->nome][$vObjeto->nome] = [];

            $stmt = $db->prepare("SELECT a.id, LOWER(a.nome) AS nome
                                          FROM seg_usuario_modulo_objeto_acao AS umoa 
                                          LEFT JOIN seg_modulo_objeto_acao AS moa ON moa.id = umoa.modulo_objeto_acao_id  
                                          LEFT JOIN seg_acao AS a ON a.id = moa.acao_id
                                          WHERE moa.modulo_id = ? AND moa.objeto_id = ? AND umoa.usuario_id = ?");
            $stmt->bindValue(1, $vModulo->id);
            $stmt->bindValue(2, $vObjeto->id);
            $stmt->bindValue(3, $id);
            $stmt->execute();
            $rsAcao = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($rsAcao AS $kAcao => $vAcao) {
              $_SESSION['permissao'][$vModulo->nome][$vObjeto->nome][$vAcao->nome] = [];
            }
          }
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        $msg['id'] = $id;
        $msg['msg'] = 'success';
        $msg['retorno'] = 'Login efetuado com sucesso.';
        echo json_encode($msg);
        exit();
      } else {
        $msg['msg'] = 'error';
        $msg['retorno'] = 'Você não tem permissão de acesso ao sistema.';
        echo json_encode($msg);
        exit();
      }
    } else {
      $msg['msg'] = 'error';
      $msg['retorno'] = 'O usuário ou a senha inseridos estão incorretos.';
      echo json_encode($msg);
      exit();
    }
  } else {
    $msg['msg'] = 'error';
    $msg['retorno'] = 'O usuário ou a senha inseridos estão incorretos.';
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar efeturar o login. :" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>