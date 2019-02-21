<?php

$db = Conexao::getInstance();

$msg = array();
$sorteados = "";

$db->beginTransaction();

$cont = 1;
$casas = array();

$stmt = $db->prepare("SELECT sc.id
                      FROM sort_casa sc
                      WHERE sc.status = 1
                      ORDER BY sc.nome ASC");
$stmt->execute();
while ($casa = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $casas[$cont] = $casa['id'];
  $cont++;
}

$cont2 = 1;
$candidatos = array();

$stmt = $db->prepare("SELECT hc.id
                      FROM sort_candidato_apto sca
                      LEFT JOIN hab_candidato AS hc ON hc.id = sca.candidato_id
                      LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                      WHERE sca.status = 1
                      ORDER BY hp.nome ASC");
$stmt->execute();
$qtd_candidatos = $stmt->rowCount();
while ($candidato = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $candidatos[$cont2] = $candidato['id'];
  $cont2++;
}

shuffle($casas);
shuffle($candidatos);

try {
  foreach ($casas as $key => $val) {
    if (isset($candidatos[$key])) {

      $stmt = $db->prepare("INSERT INTO sort_candidato_casa (casa_id, candidato_id, usuario_pai_id, data_update, data_cadastro, status) VALUES (?, ?, ?, NOW(), NOW(), 1)");
      $stmt->bindValue(1, $val);
      $stmt->bindValue(2, $candidatos[$key]);
      $stmt->bindValue(3, $_SESSION['id']);
      $stmt->execute();

      $stmt = $db->prepare("UPDATE sort_candidato_apto SET status = 2 WHERE candidato_id = ?");
      $stmt->bindValue(1, $candidatos[$key]);
      $stmt->execute();

      $stmt = $db->prepare("UPDATE sort_casa SET status = 2 WHERE id = ?");
      $stmt->bindValue(1, $val);
      $stmt->execute();

    }
  }

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = 1;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Sorteio realizado com sucesso!';
  $msg['sorteados'] = $sorteados;
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar realizar o sorteio desejado:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>