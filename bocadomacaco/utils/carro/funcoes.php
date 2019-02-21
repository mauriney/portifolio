<?php

//VERIFICANDO SE A DATA DE SAÍDA E HORA DE SAÍDA SÃO MAIORES QUE A DATA ATUAL E HORA ATUAL
function vf_data_saida($data_saida, $hora_saida) {
    $rs = true;

    if (convertDataBR2ISO(obterDataBR()) < convertDataBR2ISO($data_saida)) {
        $rs = false;
        $mensagem .= "<span>A data de chegada não pode ser menor que a data de saída.</span>";
        $msg['tipo'] = "data_chegada";
    }

    if ($rs == true && strtotime(obterHora()) < strtotime($hora_saida) && convertDataBR2ISO(obterDataBR()) == convertDataBR2ISO($data_saida)) {
        $rs = false;
        $mensagem .= "<span>A hora de chegada não pode ser menor que a hora de saída.</span>";
        $msg['tipo'] = "hora_chegada";
    }

    return $rs;
}

?>