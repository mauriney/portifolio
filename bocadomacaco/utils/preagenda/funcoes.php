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

?>