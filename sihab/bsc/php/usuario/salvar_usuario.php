
<?php

$db = Conexao::getInstance();

//session usuario
$usuario_pai_id = $_SESSION['id']; //Ainda não está pegando o id pela session

$usuario_id = strip_tags($_POST['id']);
$usuario_nome = strip_tags($_POST['nome']);
$usuario_sexo = strip_tags($_POST['sexo']);
$usuario_cpf = strip_tags($_POST['cpf']);
$usuario_nascimento = strip_tags($_POST['data_nascimento']);
$usuario_rg = strip_tags($_POST['rg']);
$usuario_uf_expadicao = strip_tags($_POST['uf_expedicao']);
$usuario_cnh = strip_tags($_POST['cnh']);
$usuario_municipio_nascimento = strip_tags($_POST['municipio_nascimento']);
$usuario_cep = strip_tags($_POST['cep']);
$usuario_numero = strip_tags($_POST['numero']);
$usuario_logradouro = strip_tags($_POST['logradouro']);
$usuario_bairro = strip_tags($_POST['bairro']);
$usuario_complemento = strip_tags($_POST['complemento']);
$usuario_municipio = strip_tags($_POST['municipio']);
$usuario_orgao = strip_tags($_POST['orgao']);
$usuario_setor = strip_tags($_POST['setor']);
$usuario_cargo = strip_tags($_POST['cargo']);
$usuario_data_admissao = strip_tags($_POST['data_admissao']);
$usuario_email_institucional = strip_tags($_POST['email_institucional']);
//$usuario_login = strip_tags(@$_POST['login']);
//$usuario_senha = strip_tags(@$_POST['senha']); //PRIMEIRA SENHA INFORMADA
$usuario_celular = strip_tags($_POST['telefone_celular']);
$usuario_email = strip_tags($_POST['email_pessoal']);
$usuario_telefone_institucional = strip_tags($_POST['telefone_institucional']);
//$data_ultima_atualizacao = @$_POST['data_ultima_atualizacao'];



//$usuario_status = strip_tags(@$_POST['status']);

//$usuario_modulos = @$_POST['usuario_modulo'];


try {


       $stmt = $db->prepare("INSERT INTO seg_usuario (nome, sexo_id, cpf, data_nascimento, rg, uf_expedicao, cnh, cep, numero, logradouro, bairro, complemento, municipio_id, unidade_organizacional_id, setor, cargo, data_admissao, email_institucional, telefone_celular, email_pessoal, telefone_institucional, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())");
      $stmt->bindValue(1, ($usuario_nome));
      $stmt->bindValue(2, $usuario_sexo);
      $stmt->bindValue(3, $usuario_cpf);
      $stmt->bindValue(4, date_format(DateTime:: createFromFormat("d/m/Y", $usuario_nascimento), "Y-m-d"));
      $stmt->bindValue(5, $usuario_rg);
      $stmt->bindValue(6, $usuario_uf_expadicao);
      $stmt->bindValue(7, $usuario_cnh);
      $stmt->bindValue(8, $usuario_cep);
      $stmt->bindValue(9, $usuario_numero);
      $stmt->bindValue(10, ($usuario_logradouro));
      $stmt->bindValue(11, ($usuario_bairro));
      $stmt->bindValue(12, ($usuario_complemento));
      $stmt->bindValue(13, $usuario_municipio);
      $stmt->bindValue(14, $usuario_orgao);
      $stmt->bindValue(15, $usuario_setor);
      $stmt->bindValue(16, ($usuario_cargo));
      $stmt->bindValue(17, $usuario_data_admissao);
      $stmt->bindValue(18, $usuario_email_institucional);
      //$stmt->bindValue(19, $usuario_login);
      //$stmt->bindValue(15, sha1($usuario_senha));
      $stmt->bindValue(19, $usuario_celular);
      $stmt->bindValue(20, $usuario_email);
      $stmt->bindValue(21, $usuario_telefone_institucional);
      //$stmt->bindValue(19, $usuario_status);

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
      $stmt1->bindValue(2, $usuario_pai_id);
      $stmt1->bindValue(3, $_SERVER["SERVER_NAME"]);
      $stmt1->bindValue(4, $_SERVER['REMOTE_ADDR']);
      $stmt1->bindValue(5, $browser . " " . $browser_version);
      $stmt1->bindValue(6, $so);
      $stmt1->bindValue(7, session_id());
      $stmt1->execute();

      

      $db->commit();

      //MENSAGEM DE SUCESSO
        $msg['id'] = $usuario_id;
        $msg['msg'] = 'success';
        $msg['retorno'] = 'Usuário cadastrado com sucesso.';
        echo json_encode($msg);
        exit();

} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar salvar os dados do usuário: " . $e;
  echo json_encode($msg);
  exit();
}
?>

