<?php

//------------------------------------------------------------------------------
//DATA: 29/07/2016 às 11:37
//NOME: Combo Cidade e Estado
//DESCRIÇÃO: Realizada a chamada de cidades relacionadas ao estado escolhido
//------------------------------------------------------------------------------
include_once('../../../../conf/config.php');
$db = Conexao::getInstance();

$programa = $_POST['programa'];

$stmp = $db->prepare("SELECT * FROM hab_especificidade WHERE status = 1 AND hab_programa_id = ? ORDER BY nome ASC");
$stmp->bindValue(1, $programa);
$stmp->execute();

if ($stmp->rowCount() == 0) {
  echo '<option value="">' . htmlentities('NENHUMA ESPECIFICIDADE ENCONTRADA') . '</option>';
} else {
  echo '<option value="">' . htmlentities('ESCOLHA UMA ESPECIFICIDADE') . '</option>';
  while ($row = $stmp->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $row['id'] . '">' . $row['nome'] . '</option>';
  }
}
?>

