<?php
include_once('conf/config.php');
include_once('conf/funcoes.php');

$db = Conexao::getInstance();

$error = false;
$msg = "";
$mensagem = "";

$db->beginTransaction();

try {

  $zona = $_POST['zona'];
  $secao = $_POST['secao'];
  $local = $_POST['local'];
  $aptos = $_POST['aptos'];
  $comparecimentos = $_POST['comparecimentos'];
  $faltosos = $_POST['faltosos'];
  $brancos = $_POST['brancos'];
  $nulos = $_POST['nulos'];

  $gov_13 = $_POST['gov_13'];
  $gov_17 = $_POST['gov_17'];
  $gov_18 = $_POST['gov_18'];
  $gov_11 = $_POST['gov_11'];
  $gov_70 = $_POST['gov_70'];

  $hash = $_POST['hash'];
  
  $hash2 = explode(", ", $hash);
  $qrbu = explode("QRBU:", $hash);
  
  foreach($hash2 AS $key => $val){
      if($qrbu[$key] != "" && $val != ""){
      if (is_numeric(pesquisar_tabela("id", "resultado", "hash", "LIKE", $val, ""))) {
         $qrbu_quebrado = explode(" ", $qrbu[$key]);
         $error = true;
         $mensagem .= "- O QR Code QRBU ".$qrbu_quebrado[0]." informado já está existe no sistema.\n";
        }  
      }
  }
  
  if ($error == false) {

    $stmt = $db->prepare("INSERT INTO resultado (secao, zona, comparecimento, faltosos, aptos, hash, brancos, nulos, data_cadastro)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bindValue(1, $secao);
    $stmt->bindValue(2, $zona);
    $stmt->bindValue(3, $comparecimentos);
    $stmt->bindValue(4, $faltosos);
    $stmt->bindValue(5, $aptos);
    $stmt->bindValue(6, $hash);
    $stmt->bindValue(7, $brancos);
    $stmt->bindValue(8, $nulos);
    $stmt->execute();

    $resultado_id = $db->lastInsertId();

    //GOV - 13
    $stmt = $db->prepare("INSERT INTO resultado_candidato (candidato_id, votos, resultado_id, data_cadastro)
                          VALUES (?, ?, ?, NOW())");
    $stmt->bindValue(1, 13);
    $stmt->bindValue(2, $gov_13);
    $stmt->bindValue(3, $resultado_id);
    $stmt->execute();
    //GOV - 17
    $stmt = $db->prepare("INSERT INTO resultado_candidato (candidato_id, votos, resultado_id, data_cadastro)
                          VALUES (?, ?, ?, NOW())");
    $stmt->bindValue(1, 17);
    $stmt->bindValue(2, $gov_17);
    $stmt->bindValue(3, $resultado_id);
    $stmt->execute();
    //GOV - 18
    $stmt = $db->prepare("INSERT INTO resultado_candidato (candidato_id, votos, resultado_id, data_cadastro)
                          VALUES (?, ?, ?, NOW())");
    $stmt->bindValue(1, 18);
    $stmt->bindValue(2, $gov_18);
    $stmt->bindValue(3, $resultado_id);
    $stmt->execute();
    //GOV - 11
    $stmt = $db->prepare("INSERT INTO resultado_candidato (candidato_id, votos, resultado_id, data_cadastro)
                          VALUES (?, ?, ?, NOW())");
    $stmt->bindValue(1, 11);
    $stmt->bindValue(2, $gov_11);
    $stmt->bindValue(3, $resultado_id);
    $stmt->execute();
    //GOV - 70
    $stmt = $db->prepare("INSERT INTO resultado_candidato (candidato_id, votos, resultado_id, data_cadastro)
                          VALUES (?, ?, ?, NOW())");
    $stmt->bindValue(1, 70);
    $stmt->bindValue(2, $gov_70);
    $stmt->bindValue(3, $resultado_id);
    $stmt->execute();

    $db->commit();

    //MENSAGEM DE SUCESSO
    $msg['retorno'] = 'Informações salvas com sucesso!';
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
  $msg['retorno'] = "Erro ao tentar salvar os dados enviados:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>