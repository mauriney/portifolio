<?php

//------------------------------------------------------------------------------
//DATA: 29/07/2016 às 11:37
//NOME: Combo País, Cidade e Estado
//DESCRIÇÃO: Realizada a chamada de paises, cidades relacionadas ao estado escolhido
//------------------------------------------------------------------------------
include_once('../../../../conf/config.php');
$db = Conexao::getInstance();

$pais = $_POST['pais'];

$stmp = $db->prepare("SELECT * FROM bsc_estado WHERE pais_id = ? ORDER BY nome ASC");
$stmp->bindValue(1, $pais);
$stmp->execute();

if ($stmp->rowCount() == 0) {
  echo '<option value="">' . htmlentities('NENHUM ESTADO ENCONTRADO') . '</option>';
} else {
  echo '<option value="">' . htmlentities('ESCOLHA UM ESTADO') . '</option>';
  while ($row = $stmp->fetch(PDO::FETCH_ASSOC)) {
    echo '<option label="' . $row['nome'] . '" value="' . $row['id'] . '">' . $row['nome'] . '</option>';
  }
}
?>

