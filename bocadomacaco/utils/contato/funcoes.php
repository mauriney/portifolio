<?php

function tipo_contato($op) {
    if ($op == 1) {
        return "Prioritário";
    } else if ($op == 2) {
        return "Normal";
    } else {
        return "";
    }
}

function tipo_endereco($op) {
    if ($op == 1) {
        return "Residencial";
    } else if ($op == 2) {
        return "Comercial";
    } else {
        return "";
    }
}

?>