<?php
@session_start();
include_once ('../../../assets/plugins/phpmailer/class.smtp.php');
include_once ('../../../assets/plugins/phpmailer/class.phpmailer.php');
include_once ('../../../utils/funcoes.php');
include_once ('../../../conf/config.php');
$db = Conexao::getInstance();

$msg = array();

$db->beginTransaction();

try {
  
  // PEGAR DADOS DE LOGIN
  $login = strip_tags($_POST['cpf']);
  $senha = strtoupper(strip_tags($_POST['senha']));
  // SQL PARA VERIFICAÇÃO DE LOGIN EXISTENTE
  $stmt = $db->prepare("SELECT c.id, UPPER(c.senha_visualiza) AS senha_visualiza
                        FROM hab_candidato AS c 
                        LEFT JOIN hab_pessoa AS p ON p.id = c.hab_pessoa_id 
                        WHERE p.cpf = ?");
  $stmt->bindValue(1, $login);
  $stmt->execute();
  $num = $stmt->rowCount();
  
  if ($num > 0) {
    // PEGA OS DADOS DO USUARIO, CASO TENHA ACESSO
    $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // VERIFICA SE A SENHA INFORMADA É IGUAL DO USUARIO
    if ($senha == $dadosUsuario['senha_visualiza']) {
      
      $id = $dadosUsuario['id'];
      
      $_SESSION['hab_candidato_id'] = $id;
      
      // CRIAR O TIMEOUT DA SESSÃO PARA EXPIRAR
      $_SESSION['hab_candidato_timeout'] = time()+180;
      
      // MENSAGEM DE SUCESSO
      $msg['id'] = $id;
      $msg['msg'] = 'success';
      $msg['retorno'] = 'Login efetuado com sucesso.';
      echo json_encode($msg);
      exit();
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
} catch ( PDOException $e ) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar efeturar o login. :" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>