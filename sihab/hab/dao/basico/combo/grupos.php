<?php

//------------------------------------------------------------------------------
//DATA: 29/07/2016 às 11:37
//NOME: Combo Grupo
//DESCRIÇÃO: Realizada a chamada de cidades relacionadas ao estado escolhido
//------------------------------------------------------------------------------
include_once('../../../../conf/config.php');
$db = Conexao::getInstance();

$catinic1 = $_POST['catinic1'];
$catfim1 = $_POST['catfim1'];

$stmp = $db->prepare("SELECT * FROM hab_cid10_grupo WHERE catinic BETWEEN ? AND ? AND catfim BETWEEN ? AND ? ORDER BY catinic ASC");
$stmp->bindValue(1, $catinic1);
$stmp->bindValue(2, $catfim1);
$stmp->bindValue(3, $catinic1);
$stmp->bindValue(4, $catfim1);

$stmp->execute();

if ($stmp->rowCount() == 0) {
  echo '<option value="">' . htmlentities('NENHUM GRUPO ENCONTRADO') . '</option>';
} else {
  echo '<option value="">' . htmlentities('ESCOLHA UM GRUPO') . '</option>';
  while ($row = $stmp->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $row['id'] . '" catinic2="' . $row['catinic'] . '" catfim2="' . $row['catfim'] . '">' . ($row['catinic'] . " até " . $row['catfim'] . " - " . $row['descricao']) . '</option>';
  }
}
?>

