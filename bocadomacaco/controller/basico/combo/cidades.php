<?php

@session_start();

include_once('../../../conf/config.php');
$db = Conexao::getInstance();

$estado = $_POST['estado'];

$stmp = $db->prepare("SELECT * FROM cidade WHERE idestado = ? ORDER BY nome ASC");
$stmp->bindValue(1, $estado);
$stmp->execute();

if ($stmp->rowCount() == 0) {
    echo '<option value="">' . htmlentities('Escolha primeiro o estado') . '</option>';
} else {
    echo '<option value="">' . htmlentities(utf8_decode('Escolha um município')) . '</option>';
    while ($row = $stmp->fetch(PDO::FETCH_ASSOC)) {
        echo '<option label="' . $row['nome'] . '" value="' . $row['idcidade'] . '">' . utf8_encode($row['nome']) . '</option>';
    }
}
?>

