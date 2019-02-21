<?php

$db = Conexao::getInstance();

$param = $_POST['candidato_id'];
$indiceAux = $_POST['index_anexo'];

$anexoId = $_POST['anexo_id'];
$anexoNome = $_POST['anexo_nome'];
$anexoEndereco = $_POST['anexo_endereco'];
$tabelaCampo = $_POST['tabela_campo'];



//INFO ARQUIVO
$arquivo = $_FILES['anexo'];

//LISTA DE TIPOS DE ARQUIVOS PERMITIDOS
$tiposPermitidos = array('application/pdf', 'application/msword', 'application/msexcel', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png'); //ARQUIVO: .gif, .jpeg, .pjpeg, .png
//TAMANHO MÁXIMO DO ARQUIVO(em bytes)
$tamanhoPermitido = 20971520; //20MB

$arquivo_name = '';
$arquivo_error = 0;
$arquivo_type = '';
$arquivo_size = '';
$arquivo_tmp = '';
$valor_temp = 0;

try {
    if (is_numeric($indiceAux) && $indiceAux >= 0) {
        $arquivo_name = $_FILES['arquivos']['name'][$indiceAux]; //$arquivo["name"][$i];
        $arquivo_error = $arquivo["error"][$indiceAux];
        $arquivo_type = $arquivo["type"][$indiceAux];
        $arquivo_size = $_FILES['arquivos']['size'][$indiceAux]; //$arquivo["size"][$i];
        $arquivo_tmp = $arquivo["tmp_name"][$indiceAux];
    }

    //VERIFICAR
    if ($arquivo_error > 0) {
        $msg[] = "Error ao tentar fazer o upload do arquivo: " . $errorMsg[$arquivo["error"][$i]];
    } else {
        //VERIFICA SE A EXTENSAO DO ARQUIVO ESTÁ CORRETO
        if (!in_array($arquivo_type, $tiposPermitidos)) {
            $msg[] = "Por favor, selecione um arquivo de formato válido.";
            $msg['msg'] = 'error';
            $msg['retorno'] = 'Arquivo inválido: ';
            echo json_encode($msg);
            die();
        } else if ($arquivo_size > $tamanhoPermitido) {
            $msg[] = "Por favor, selecione um arquivo com tamanho máximo de 20MB.";
            $msg['msg'] = 'error';
            $msg['retorno'] = 'Tamanho do arquivo inválido.';
            echo json_encode($msg);
            die();
        } else {
            // Pega a extensão do arquivo enviado
            $tmp = explode('.', $arquivo_name);
            $extensao = strtolower(end($tmp));
            // Gera um nome único para o arquivo
            $nomearquivo = md5(uniqid(time())) . "." . $extensao;
            //Move arquivo para a pasta informada
            move_uploaded_file($arquivo_tmp, "../anexos/sishab/pessoa/" . $nomearquivo);

            $msg['msg'] = "success";
            $msg['retorno'] = '' . $nomearquivo;
            echo json_encode($msg);
            die();
        } //END IF
    }//END IF
} catch (PDOException $e) {
    $msg['msg'] = 'error';
    $msg['retorno'] = 'Erro: ' . $e;
    echo json_encode($msg);
    die();
}
?>

<!--<script src="--><?//= PORTAL_URL; ?><!--utils/jquery.form.js"></script>-->
