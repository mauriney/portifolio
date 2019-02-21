<?php

//------------------------------------------------------------------------------
//DATA: 29/07/2016 às 11:37
//NOME: Combo Cidade e Estado
//DESCRIÇÃO: Realizada a chamada de cidades relacionadas ao estado escolhido
//------------------------------------------------------------------------------
include_once('../../../../conf/config.php');
$db = Conexao::getInstance();

$estado = $_POST['estado'];

$stmp = $db->prepare("SELECT * FROM bsc_municipio WHERE estado_id = ? ORDER BY nome ASC");
$stmp->bindValue(1, $estado);
$stmp->execute();

if ($stmp->rowCount() == 0) {
  echo '<option value="">' . htmlentities('Nenhum município encontrado') . '</option>';
} else {
  echo '<option value="">' . htmlentities('Escolha um município') . '</option>';
  while ($row = $stmp->fetch(PDO::FETCH_ASSOC)) {
    echo '<option label="' . $row['nome'] . '" value="' . $row['id'] . '">' . $row['nome'] . '</option>';
  }
}
?>

