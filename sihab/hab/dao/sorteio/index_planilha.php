<?php

$db = Conexao::getInstance();

$msg = array();
$sorteados = "";

$db->beginTransaction();

$casas1 = array();
$candidatos1 = array();

$casas2 = array();
$candidatos2 = array();

$casas3 = array();
$candidatos3 = array();

$casas4 = array();
$candidatos4 = array();

$casas5 = array();
$candidatos5 = array();

//PLANILHA 1-------------------------------------------------------------------------------------------------------------------------------------------------------------------
$cont = 1;

$stmt1 = $db->prepare("SELECT sc.id
                      FROM sort_casa sc
                      WHERE sc.status = 1 AND sc.planilha = 1 
                      ORDER BY sc.nome ASC");
$stmt1->execute();
while ($casa1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
  $casas1[$cont] = $casa1['id'];
  $cont++;
}

$cont2 = 1;

$stmt1 = $db->prepare("SELECT hc.id
                      FROM sort_candidato_apto sca
                      LEFT JOIN hab_candidato AS hc ON hc.id = sca.candidato_id
                      LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                      WHERE sca.status = 1 AND sca.planilha = 1 
                      ORDER BY hp.nome ASC");
$stmt1->execute();
while ($candidato1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
  $candidatos1[$cont2] = $candidato1['id'];
  $cont2++;
}

shuffle($casas1);
shuffle($candidatos1);

//PLANILHA 2-------------------------------------------------------------------------------------------------------------------------------------------------------------------
$cont = 1;
$stmt2 = $db->prepare("SELECT sc.id
                      FROM sort_casa sc
                      WHERE sc.status = 1 AND sc.planilha = 2 
                      ORDER BY sc.nome ASC");
$stmt2->execute();
while ($casa2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
  $casas2[$cont] = $casa2['id'];
  $cont++;
}

$cont2 = 1;

$stmt2 = $db->prepare("SELECT hc.id
                      FROM sort_candidato_apto sca
                      LEFT JOIN hab_candidato AS hc ON hc.id = sca.candidato_id
                      LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                      WHERE sca.status = 1 AND sca.planilha = 2 
                      ORDER BY hp.nome ASC");
$stmt2->execute();
while ($candidato2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
  $candidatos2[$cont2] = $candidato2['id'];
  $cont2++;
}

shuffle($casas2);
shuffle($candidatos2);

//PLANILHA 3-------------------------------------------------------------------------------------------------------------------------------------------------------------------
$cont = 1;
$stmt3 = $db->prepare("SELECT sc.id
                      FROM sort_casa sc
                      WHERE sc.status = 1 AND sc.planilha = 3 
                      ORDER BY sc.nome ASC");
$stmt3->execute();
while ($casa3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
  $casas3[$cont] = $casa3['id'];
  $cont++;
}

$cont2 = 1;

$stmt3 = $db->prepare("SELECT hc.id
                      FROM sort_candidato_apto sca
                      LEFT JOIN hab_candidato AS hc ON hc.id = sca.candidato_id
                      LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                      WHERE sca.status = 1 AND sca.planilha = 3
                      ORDER BY hp.nome ASC");
$stmt3->execute();
while ($candidato3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
  $candidatos3[$cont2] = $candidato3['id'];
  $cont2++;
}

shuffle($casas3);
shuffle($candidatos3);

//PLANILHA 4-------------------------------------------------------------------------------------------------------------------------------------------------------------------
$cont = 1;
$stmt4 = $db->prepare("SELECT sc.id
                      FROM sort_casa sc
                      WHERE sc.status = 1 AND sc.planilha = 4 
                      ORDER BY sc.nome ASC");
$stmt4->execute();
while ($casa4 = $stmt4->fetch(PDO::FETCH_ASSOC)) {
  $casas4[$cont] = $casa4['id'];
  $cont++;
}

$cont2 = 1;

$stmt4 = $db->prepare("SELECT hc.id
                      FROM sort_candidato_apto sca
                      LEFT JOIN hab_candidato AS hc ON hc.id = sca.candidato_id
                      LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                      WHERE sca.status = 1 AND sca.planilha = 4 
                      ORDER BY hp.nome ASC");
$stmt4->execute();
while ($candidato4 = $stmt4->fetch(PDO::FETCH_ASSOC)) {
  $candidatos4[$cont2] = $candidato4['id'];
  $cont2++;
}

shuffle($casas4);
shuffle($candidatos4);

//PLANILHA 5-------------------------------------------------------------------------------------------------------------------------------------------------------------------
$cont = 1;
$stmt5 = $db->prepare("SELECT sc.id
                      FROM sort_casa sc
                      WHERE sc.status = 1 AND sc.planilha = 5 
                      ORDER BY sc.nome ASC");
$stmt5->execute();
while ($casa5 = $stmt5->fetch(PDO::FETCH_ASSOC)) {
  $casas5[$cont] = $casa5['id'];
  $cont++;
}

$cont2 = 1;

$stmt5 = $db->prepare("SELECT hc.id
                      FROM sort_candidato_apto sca
                      LEFT JOIN hab_candidato AS hc ON hc.id = sca.candidato_id
                      LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                      WHERE sca.status = 1 AND sca.planilha = 5 
                      ORDER BY hp.nome ASC");
$stmt5->execute();
while ($candidato5 = $stmt5->fetch(PDO::FETCH_ASSOC)) {
  $candidatos5[$cont2] = $candidato5['id'];
  $cont2++;
}

shuffle($casas5);
shuffle($candidatos5);
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

try {
  //PLANILHA 1
  foreach ($casas1 as $key => $val) {
    if (isset($candidatos1[$key])) {

      $stmt = $db->prepare("INSERT INTO sort_candidato_casa (casa_id, candidato_id, usuario_pai_id, data_update, data_cadastro, status) VALUES (?, ?, ?, NOW(), NOW(), 1)");
      $stmt->bindValue(1, $val);
      $stmt->bindValue(2, $candidatos1[$key]);
      $stmt->bindValue(3, $_SESSION['id']);
      $stmt->execute();

      $stmt = $db->prepare("UPDATE sort_candidato_apto SET status = 2 WHERE candidato_id = ?");
      $stmt->bindValue(1, $candidatos1[$key]);
      $stmt->execute();

      $stmt = $db->prepare("UPDATE sort_casa SET status = 2 WHERE id = ?");
      $stmt->bindValue(1, $val);
      $stmt->execute();
    }
  }
  //PLANILHA 2
  foreach ($casas2 as $key => $val) {
    if (isset($candidatos2[$key])) {

      $stmt = $db->prepare("INSERT INTO sort_candidato_casa (casa_id, candidato_id, usuario_pai_id, data_update, data_cadastro, status) VALUES (?, ?, ?, NOW(), NOW(), 1)");
      $stmt->bindValue(1, $val);
      $stmt->bindValue(2, $candidatos2[$key]);
      $stmt->bindValue(3, $_SESSION['id']);
      $stmt->execute();

      $stmt = $db->prepare("UPDATE sort_candidato_apto SET status = 2 WHERE candidato_id = ?");
      $stmt->bindValue(1, $candidatos2[$key]);
      $stmt->execute();

      $stmt = $db->prepare("UPDATE sort_casa SET status = 2 WHERE id = ?");
      $stmt->bindValue(1, $val);
      $stmt->execute();
    }
  }
  //PLANILHA 3
  foreach ($casas3 as $key => $val) {
    if (isset($candidatos3[$key])) {

      $stmt = $db->prepare("INSERT INTO sort_candidato_casa (casa_id, candidato_id, usuario_pai_id, data_update, data_cadastro, status) VALUES (?, ?, ?, NOW(), NOW(), 1)");
      $stmt->bindValue(1, $val);
      $stmt->bindValue(2, $candidatos3[$key]);
      $stmt->bindValue(3, $_SESSION['id']);
      $stmt->execute();

      $stmt = $db->prepare("UPDATE sort_candidato_apto SET status = 2 WHERE candidato_id = ?");
      $stmt->bindValue(1, $candidatos3[$key]);
      $stmt->execute();

      $stmt = $db->prepare("UPDATE sort_casa SET status = 2 WHERE id = ?");
      $stmt->bindValue(1, $val);
      $stmt->execute();
    }
  }
  //PLANILHA 4
  foreach ($casas4 as $key => $val) {
    if (isset($candidatos4[$key])) {

      $stmt = $db->prepare("INSERT INTO sort_candidato_casa (casa_id, candidato_id, usuario_pai_id, data_update, data_cadastro, status) VALUES (?, ?, ?, NOW(), NOW(), 1)");
      $stmt->bindValue(1, $val);
      $stmt->bindValue(2, $candidatos4[$key]);
      $stmt->bindValue(3, $_SESSION['id']);
      $stmt->execute();

      $stmt = $db->prepare("UPDATE sort_candidato_apto SET status = 2 WHERE candidato_id = ?");
      $stmt->bindValue(1, $candidatos4[$key]);
      $stmt->execute();

      $stmt = $db->prepare("UPDATE sort_casa SET status = 2 WHERE id = ?");
      $stmt->bindValue(1, $val);
      $stmt->execute();
    }
  }
  //PLANILHA 5
  foreach ($casas5 as $key => $val) {
    if (isset($candidatos5[$key])) {

      $stmt = $db->prepare("INSERT INTO sort_candidato_casa (casa_id, candidato_id, usuario_pai_id, data_update, data_cadastro, status) VALUES (?, ?, ?, NOW(), NOW(), 1)");
      $stmt->bindValue(1, $val);
      $stmt->bindValue(2, $candidatos5[$key]);
      $stmt->bindValue(3, $_SESSION['id']);
      $stmt->execute();

      $stmt = $db->prepare("UPDATE sort_candidato_apto SET status = 2 WHERE candidato_id = ?");
      $stmt->bindValue(1, $candidatos5[$key]);
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