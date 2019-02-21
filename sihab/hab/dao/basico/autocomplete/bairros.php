<?php

//------------------------------------------------------------------------------
//DATA: 29/07/2016 às 11:37
//NOME: Autocomplete Bairro
//DESCRIÇÃO: Realizada a chamada de cidades relacionadas ao estado escolhido
//------------------------------------------------------------------------------
include_once('../../../conf/config.php');
$db = Conexao::getInstance();

$busca = $_GET["q"];

$stmp = $db->prepare("SELECT * FROM hab_pessoa_endereco WHERE bairro like ? ORDER BY bairro ASC");
$stmp->bindValue(1, "%$busca%");
$stmp->execute();

if ($stmp->rowCount() == 0) {
  echo "";
} else {
  while ($row = $stmp->fetch(PDO::FETCH_ASSOC)) {
    echo $row['bairro'] . "\n";
  }
}
?>

