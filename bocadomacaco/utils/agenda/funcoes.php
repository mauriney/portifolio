<?php

//FUNÇÃO PARA VERIFICAR SE USUÁRIO É UM PARTICIPANTE DA AGENDA
function vf_participante($id_agenda, $id_participante) {
    $db = Conexao::getInstance();

    $result = false;

    $sql = $db->prepare("SELECT u.Nome as participante FROM 
             x_agenda_participante p, tb_bsc_usuario u 
             WHERE p.IdContato = u.IdUsuario AND p.IdAgenda = ? AND u.IdUsuario = ?");

    $sql->bindValue(1, $id_agenda);
    $sql->bindValue(2, $id_participante);
    $sql->execute();
    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
        $result = true;
    }
    return $result;
}

//FUNÇÃO PARA PESQUISAR TODOS OS PARTICIPANTES DA AGENDA
function agenda_participantes($id_agenda) {

    $db = Conexao::getInstance();

    $result = "";

    $sql = $db->prepare("SELECT u.Nome as participante FROM"
            . " x_agenda_participante p, tb_bsc_usuario u"
            . " WHERE p.IdContato = u.IdUsuario AND p.IdAgenda = ?");

    $sql->bindValue(1, $id_agenda);
    $sql->execute();
    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
        $result .= "" . $linha['participante'] . "; ";
    }
    return $result;
}

function prioridade($op) {
    if ($op == 1) {
        return "Baixa";
    } else if ($op == 2) {
        return "Média";
    } else if ($op == 3) {
        return "Alta";
    }
}

function prioridade_cor($op) {
    if ($op == 1) {
        return "baixa";
    } else if ($op == 2) {
        return "media";
    } else if ($op == 3) {
        return "alta";
    }
}

?>