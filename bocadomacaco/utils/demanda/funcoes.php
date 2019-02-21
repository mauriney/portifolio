<?php

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

function situacao_demanda($prazo, $status) {
    if ($status == 3) {
        return "CONCLUÍDO";
    } else if ($prazo >= obterDataISO()) {
        return "NO PRAZO";
    } else if ($prazo < obterDataISO()) {
        return "ATRASADO";
    }
}

function situacao_demanda2($prazo, $status) {

    if ($status == 3) {
        return "concluido";
    } else if ($prazo >= obterDataISO()) {
        return "no-prazo";
    } else if ($prazo < obterDataISO()) {
        return "atrasado";
    }
}

//FUNÇÃO PARA PESQUISAR TODOS OS RESPONSÁVEIS DA DEMANDA
function responsavel_demanda($demanda_id) {

    $db = Conexao::getInstance();

    $result = "";

    $sql = $db->prepare("SELECT u.nome AS responsavel FROM"
            . " x_demanda_responsavel r, tb_bsc_usuario u"
            . " WHERE r.responsavel_id = u.IdUsuario AND r.demanda_id = ? GROUP BY u.IdUsuario");

    $sql->bindValue(1, $demanda_id);
    $sql->execute();
    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
        $result .= "" . utf8_encode($linha['responsavel']) . "; ";
    }
    return $result;
}

//FUNÇÃO PARA PESQUISAR SE O RESPONSÁVEL FAZ PARTE DA DEMANDA
function responsavel_demanda_id($demanda_id, $usuario_id) {

    $result = false;

    $db = Conexao::getInstance();

    $sql = $db->prepare("SELECT * FROM"
            . " x_demanda_responsavel r, tb_bsc_usuario u"
            . " WHERE u.IdUsuario = ? AND r.responsavel_id = u.IdUsuario AND r.demanda_id = ? GROUP BY u.IdUsuario");

    $sql->bindValue(1, $usuario_id);
    $sql->bindValue(2, $demanda_id);
    $sql->execute();
    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
        $result = true;
    }

    //OU VERIFICAR ABAIXO SE O USUÁRIO CADASTROU A DEMANDA
    /*if ($result == false) {
        $sql2 = $db->prepare("SELECT id FROM x_demanda WHERE IdUsuario = ? AND id = ?");
        $sql2->bindValue(1, $usuario_id);
        $sql2->bindValue(2, $demanda_id);
        $sql2->execute();
        while ($linha2 = $sql2->fetch(PDO::FETCH_ASSOC)) {
            $result = true;
        }
    }*/

    return $result;
}

//STATUS DA DEMANDA
function status_demanda($op) {
    if ($op == 0) {
        return "EM ANDAMENTO";
    } else if ($op == 1) {
        return "CANCELADA";
    } else if ($op == 3) {
        return "CONCLUÍDO";
    } else if ($op == 2) {
        return "NÃO INICIADO";
    } else if ($op == 4) {
        return "SEM SOLUÇÃO";
    }
}

//STATUS DA DEMANDA
function status_demanda2($op) {
    if ($op == 0) {
        return "Em Andamenta";
    } else if ($op == 1) {
        return "Cancelada";
    } else if ($op == 3) {
        return "Concluída";
    } else if ($op == 2) {
        return "Não Inicada";
    } else if ($op == 4) {
        return "Sem Solução";
    }
}

?>