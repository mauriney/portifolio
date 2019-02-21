<?php

//------------------------------------------------------------------------------
//DATA: 29/07/2016 às 11:37
//NOME: Combo Categoria
//DESCRIÇÃO: Realizada a chamada de cidades relacionadas ao estado escolhido
//------------------------------------------------------------------------------
include_once('../../../../conf/config.php');
$db = Conexao::getInstance();

$catinic2 = $_POST['catinic2'];
$catfim2 = $_POST['catfim2'];

$stmp = $db->prepare("SELECT * FROM hab_cid10_categoria WHERE cat BETWEEN ? AND ? ORDER BY cat ASC");
$stmp->bindValue(1, $catinic2);
$stmp->bindValue(2, $catfim2);

$stmp->execute();

if ($stmp->rowCount() == 0) {
  echo '<option value="">' . htmlentities('NENHUMA CATEGORIA ENCONTRADA') . '</option>';
} else {
  echo '<option value="">' . htmlentities('ESCOLHA UMA CATEGORIA') . '</option>';
  while ($row = $stmp->fetch(PDO::FETCH_ASSOC)) {
    echo '<option  value="' . $row['id'] . '">' . ($row['cat'] . " - " . $row['descricao']) . '</option>';
  }
}
?>

