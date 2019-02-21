<?php

//FUNÇÃO QUE VARRE UMA PALAVRA E RETORNA SEU VALOR REAL SEM CRAQUELAMENTO
function anti_utf8($texto) {

    $palavra = $texto;

    $simbolo = false;
    $tam = strlen($texto);

    $palavra2 = preg_replace("/[^A-Za-z0-9-áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ-\\s-]/", "", $texto);

    $tam2 = strlen($palavra2);

    if ($tam != $tam2) {
        $simbolo = true;
    }

    if ($simbolo) {

        $palavra = utf8_encode($texto);
        $tam = strlen($palavra);
        $simbolo = false;

        $palavra2 = preg_replace("/[^A-Za-z0-9-áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ-\\s-]/", "", $palavra);
        $tam2 = strlen($palavra2);

        if ($tam != $tam2) {
            $simbolo = true;
        } else {
            $palavra = utf8_encode($texto);
        }

        if ($simbolo) {

            $palavra = utf8_decode($texto);
            $tam = strlen($palavra);
            $simbolo = false;

            $palavra2 = preg_replace("/[^A-Za-z0-9-áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ-\\s-]/", "", $palavra);
            $tam2 = strlen($palavra2);

            if ($tam != $tam2) {
                $simbolo = true;
            } else {
                $palavra = utf8_decode($texto);
            }
        }
    }

    return $palavra;
}

//----------------------------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA REMOVER SQL DOS PARÂMETROS PASSADOS POR URL
function antiSQL($campo, $adicionaBarras = false) {
// remove palavras que contenham sintaxe sql
    $campo = preg_replace("/(from| or | and |alter table|select|insert|delete|update|were|drop table|show tables|#|\*|--|\\\\)/i", "Error", $campo);
    $campo = trim($campo); //limpa espaços vazio
    $campo = strip_tags($campo); //tira tags html e php
    if ($adicionaBarras || !get_magic_quotes_gpc())
        $campo = addslashes($campo);
    return $campo;
}

function vf_usuario_login() {
    @session_start();

    if (!isset($_SESSION['id']) && !is_numeric(pesquisar("idinfo", "info_login", "idusuario", "=", @$_SESSION['id'], "")) || pesquisar("idinfo", "info_login", "idusuario", "=", @$_SESSION['id'], "") == "") {
        echo "<script>window.location = 'index.php';</script>";
        exit();
    }
}

//FUNÇÃO QUE RECEBE UM ARRAY E ARRUMA TODOS OS DADOS PARA SEREM PEGOS
function retorna_campos($post) {
    $fields = explode("&", $post);
    foreach ($fields as $field) {
        $field_key_value = explode("=", $field);
        $key = ($field_key_value[0]);
        $value = ($field_key_value[1]);
        if ($value != '')
            $data[$key] = utf8_decode(urldecode($value));
    }
    return $data;
}

//MÉTOD PARA VALIDAR O CPF
function valida_cpf($cpfx) {

    $cpf = "";
    $guard = "";

    for ($i = 0; ($i < 14); $i++) {
        if ($cpfx[$i] != '.' && $cpfx[$i] != '-') {
            $cpf+= $cpfx[$i];
            $guard = "$guard$cpfx[$i]";
        }
    }

    $cpf = $guard;

//Verifica se o cpf possui números      
    if (!is_numeric($cpf)) {
        $status = false;
    } else {
//VERIFICA
        if (($cpf == '11111111111') || ($cpf == '22222222222') ||
                ($cpf == '33333333333') || ($cpf == '44444444444') ||
                ($cpf == '55555555555') || ($cpf == '66666666666') ||
                ($cpf == '77777777777') || ($cpf == '88888888888') ||
                ($cpf == '99999999999') || ($cpf == '00000000000')) {
            $status = false;
        } else {
//PEGA O DIGITO VERIFIACADOR
            $dv_informado = substr($cpf, 9, 2);

            for ($i = 0; $i <= 8; $i++) {
                $digito[$i] = substr($cpf, $i, 1);
            }

//CALCULA O VALOR DO 10º DIGITO DE VERIFICAÇÂO
            $posicao = 10;
            $soma = 0;

            for ($i = 0; $i <= 8; $i++) {
                $soma = $soma + $digito[$i] * $posicao;
                $posicao = $posicao - 1;
            }

            $digito[9] = $soma % 11;

            if ($digito[9] < 2) {
                $digito[9] = 0;
            } else {
                $digito[9] = 11 - $digito[9];
            }

//CALCULA O VALOR DO 11º DIGITO DE VERIFICAÇÃO
            $posicao = 11;
            $soma = 0;

            for ($i = 0; $i <= 9; $i++) {
                $soma = $soma + $digito[$i] * $posicao;
                $posicao = $posicao - 1;
            }

            $digito[10] = $soma % 11;

            if ($digito[10] < 2) {
                $digito[10] = 0;
            } else {
                $digito[10] = 11 - $digito[10];
            }

//VERIFICA SE O DV CALCULADO É IGUAL AO INFORMADO
            $dv = $digito[9] * 10 + $digito[10];
            if ($dv != $dv_informado) {
                $status = false;
            } else
                $status = true;
        }//FECHA ELSE
    }//FECHA ELSE(is_numeric)    
    return $status;
}

//MÉTODO PARA REALIZAR UMA PESQUISA DE COMPARAÇÃO NO BANCO DE DADOS
function pesquisar($retorno, $tabela, $campo, $cond, $variavel, $add) {

    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "", "" . DB_USER . "", "" . DB_PASS . "");

    $rs = $db->prepare("SELECT $retorno FROM $tabela WHERE $campo $cond ? $add");
    $rs->bindValue(1, $variavel);
    $rs->execute();
    $dados = $rs->fetch(PDO::FETCH_ASSOC);

    return $dados[$retorno];
}

// MUDAR AQUI NIRO !!!
//Método para verificar se o email informado é do ac.gov.br
function VfEmailAc($email) {
    $emailDigitado = ""; //vai guardar tudo que vem depois do @ para depois comparar	
    $vf = false;
    $emailvf = false;

    for ($k = 0; ($k < strlen($email)); $k++) {
        if ($email[$k] == '@' || $vf == true) {
            $emailDigitado = "$emailDigitado$email[$k]";
            $vf = true;
        }
    }
    if ($emailDigitado == "@ac.gov.br") {
        $emailvf = true;
        return true;
    } else if ($emailvf == false) {
        return false;
    }
}

//NÍVEL DE ACESSO DO USUÁRIO
function nivel($codigo) {

    $con = Conexao::getInstance();

    $rs = $con->prepare("SELECT Descricao FROM tb_bsc_acesso WHERE IdAcesso = ?");
    $rs->bindValue(1, $codigo);
    $rs->execute();

    $dados = $rs->fetch(PDO::FETCH_ASSOC);

    return $dados['Descricao'];
}

//FUNÇÃO PARA RETORNAR O STATUS
function status($codigo) {
    if ($codigo == 1)
        return "Ativo";
    else
        return "Inativo";
}

//FUNÇÃO PARA RETORNAR O STATUS
function status_inverso($codigo) {
    if (strtoupper($codigo) == "ATIVO")
        return 1;
    else if (strtoupper($codigo) == "INATIVO")
        return 0;
    else
        return 2;
}

//FUNÇÃO PARA RETORNAR O NOME DO ESTADO PELO ID
function nome_estado_id($estado) {
    if (is_numeric($estado)) {
        $con = Conexao::getInstance();

        $rs = $con->prepare("SELECT nome FROM estado WHERE idestado = ?");
        $rs->bindValue(1, $estado);
        $rs->execute();
        $dados = $rs->fetch(PDO::FETCH_ASSOC);
        return $dados['nome'];
    } else {
        return "";
    }
}

//FUNÇÃO PARA RETORNAR O NOME DA CIDADE PELO ID
function nome_cidade_id($cidade) {
    if (is_numeric($cidade)) {
        $con = Conexao::getInstance();

        $rs = $con->prepare("SELECT nome FROM cidade WHERE idcidade = ?");
        $rs->bindValue(1, $cidade);
        $rs->execute();
        $dados = $rs->fetch(PDO::FETCH_ASSOC);
        return $dados['nome'];
    } else {
        return "";
    }
}

//FUNÇÃO PARA RETORNAR O ESTADO DE UM MUNICÍPIO
function estado_municipio($municipio) {
    if (is_numeric($municipio)) {
        $con = Conexao::getInstance();

        $rs = $con->prepare("SELECT idestado FROM cidade WHERE idcidade = ?");
        $rs->bindValue(1, $municipio);
        $rs->execute();
        $dados = $rs->fetch(PDO::FETCH_ASSOC);

        return $dados['idestado'];
    } else {
        return "";
    }
}

function formata_data($data) {
    if ($data == '')
        return '';
    $d = explode('/', $data);
    return $d[2] . '-' . $d[1] . '-' . $d[0];
}

function data_volta($data) {
    if ($data == '' || $data == '0000-00-00')
        return '';
    $d = explode('-', $data);
    return $d[2] . '/' . $d[1] . '/' . $d[0];
}

function hora($hora) { //Deixa a hora 20:00
    $h = explode(':', $hora);
    return $h[0] . ':' . $h[1];
}

function getSemana($dia, $completo = 0) {
    switch ($dia) {
        case 1:
            $r = 'SEG';
            $comp = 'Segunda-feira';
            break;
        case 2:
            $r = 'TER';
            $comp = 'Terça-feira';
            break;
        case 3:
            $r = 'QUA';
            $comp = 'Quarta-feira';
            break;
        case 4:
            $r = 'QUI';
            $comp = 'Quinta-feira';
            break;
        case 5:
            $r = 'SEX';
            $comp = 'Sexta-feira';
            break;
        case 6:
            $r = 'SAB';
            $comp = 'Sábado';
            break;
        case 7:
            $r = 'DOM';
            $comp = 'Domingo';
            break;
    }
    if ($completo == 1)
        return $comp;
    else
        return $r;
}

function getSemana2($dia, $completo = 0) {
    switch ($dia) {
        case 1:
            $r = 'Seg';
            $comp = 'Segunda-feira';
            break;
        case 2:
            $r = 'Ter';
            $comp = 'Terça-feira';
            break;
        case 3:
            $r = 'Qua';
            $comp = 'Quarta-feira';
            break;
        case 4:
            $r = 'Qui';
            $comp = 'Quinta-feira';
            break;
        case 5:
            $r = 'Sex';
            $comp = 'Sexta-feira';
            break;
        case 6:
            $r = 'Sab';
            $comp = 'Sábado';
            break;
        case 7:
            $r = 'Dom';
            $comp = 'Domingo';
            break;
    }
    if ($completo == 1)
        return $comp;
    else
        return $r;
}

function getDiaSemana($dia, $completo = 0) {
    switch ($dia) {
        case 1:
            $r = 'Dom';
            $comp = 'Domingo';
            break;
        case 2:
            $r = 'Seg';
            $comp = 'Segunda-feira';
            break;
        case 3:
            $r = 'Ter';
            $comp = 'Terça-feira';
            break;
        case 4:
            $r = 'Qua';
            $comp = 'Quarta-feira';
            break;
        case 5:
            $r = 'Qui';
            $comp = 'Quinta-feira';
            break;
        case 6:
            $r = 'Sex';
            $comp = 'Sexta-feira';
            break;
        case 7:
            $r = 'Sab';
            $comp = 'Sábado';
            break;
    }
    if ($completo == 1)
        return $comp;
    else
        return $r;
}

function hoje($data) {
    $dt = explode('/', $data);
    return getSemana(date("N", mktime(0, 0, 0, $dt[1], $dt[0], intval($dt[2]))), 1);
}

function hoje2($data) {
    $dt = explode('/', $data);
    return getSemana2(date("N", mktime(0, 0, 0, $dt[1], $dt[0], intval($dt[2]))), 0);
}

function timeDiff($firstTime, $lastTime) {
    $firstTime = strtotime($firstTime);
    $lastTime = strtotime($lastTime);
    $timeDiff = $lastTime - $firstTime;
    return $timeDiff;
}

function separa_hora($hora, $op) { //$op = minutos = 1; hora = 0
    $hr = explode(':', $hora);
    return $hr[$op];
}

function dataExtensoTimeline($dt) {
    $da = explode('/', $dt);
    $diasemana = date("w", mktime(0, 0, 0, $da[1], $da[0], $da[2]));
    return getSemana2($diasemana, 0) . '  ' . getMes2($da[1]) . '  ' . $da[0] . ' ' . $da[2];
}

function getMes($m) {
    switch ($m) {
        case 1: $mes = "Janeiro";
            break;
        case 2: $mes = "Fevereiro";
            break;
        case 3: $mes = "Março";
            break;
        case 4: $mes = "Abril";
            break;
        case 5: $mes = "Maio";
            break;
        case 6: $mes = "Junho";
            break;
        case 7: $mes = "Julho";
            break;
        case 8: $mes = "Agosto";
            break;
        case 9: $mes = "Setembro";
            break;
        case 10: $mes = "Outubro";
            break;
        case 11: $mes = "Novembro";
            break;
        case 12: $mes = "Dezembro";
            break;
    }
    return $mes;
}

function getMes2($m) {
    switch ($m) {
        case 1: $mes = "Jan";
            break;
        case 2: $mes = "Fev";
            break;
        case 3: $mes = "Mar";
            break;
        case 4: $mes = "Abr";
            break;
        case 5: $mes = "Mai";
            break;
        case 6: $mes = "Jun";
            break;
        case 7: $mes = "Jul";
            break;
        case 8: $mes = "Ago";
            break;
        case 9: $mes = "Set";
            break;
        case 10: $mes = "Out";
            break;
        case 11: $mes = "Nov";
            break;
        case 12: $mes = "Dez";
            break;
    }
    return $mes;
}

function ctexto($texto, $frase = 'pal') {
    switch ($frase) {
        case 'fra': //Apenas a a primeira letra em maiusculo
            $texto = ucfirst(strtolower($texto));
            break;
        case 'min':
            $texto = strtolower(colocaAcentoMinusculo($texto));
            break;
        case 'mai':
            $texto = colocaAcentoMaiusculo(utf8_encode(strtoupper($texto)));
            break;
        case 'pal': //Todas as palavras com a primeira em maiusculo
            $texto = titleCase(ucwords(strtolower(colocaAcentoMinusculo($texto))));
            break;
        case 'pri'://Todos os primeiros caracteres de cada palavra em maiusuclo, menos as junções
            $texto = titleCase($texto);
            break;
    }
    return $texto;
}

function titleCase($string, $delimiters = array(" ", "-", ".", "'", "O'", "Mc"), $exceptions = array("mas", "nem", "também", "porém", "contudo", "todavia", "entretanto", "ora", "já", "quer", "pois", "portanto", "porque", "quer", "logo", "se", "caso", "em", "aos", "na", "para", "a", "e", "o", "dias", "no", "às", "os", "de", "da", "dos", "das", "do", "I", "II", "III", "IV", "V", "VI")) {
    /*
     * Exceptions in lower case are words you don't want converted
     * Exceptions all in upper case are any words you don't want converted to title case
     *   but should be converted to upper case, e.g.:
     *   king henry viii or king henry Viii should be King Henry VIII
     */
    $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
    foreach ($delimiters as $dlnr => $delimiter) {
        $words = explode($delimiter, $string);
        $newwords = array();
        foreach ($words as $wordnr => $word) {
            if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                // check exceptions list for any words that should be in upper case
                $word = mb_strtoupper($word, "UTF-8");
            } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                // check exceptions list for any words that should be in upper case
                $word = mb_strtolower($word, "UTF-8");
            } elseif (!in_array($word, $exceptions)) {
                // convert to uppercase (non-utf8 only)
                $word = ucfirst($word);
            }
            array_push($newwords, $word);
        }
        $string = join($delimiter, $newwords);
    }//foreach
    return $string;
}

function colocaAcentoMinusculo($texto) {

    $array2 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç");

    $array1 = array("Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
    return str_replace($array1, $array2, $texto);
}

function colocaAcentoMaiusculo($texto) {

    $array1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç");

    $array2 = array("Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
    return str_replace($array1, $array2, $texto);
}

function retira_acentos($texto) {
    $array1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
        , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
    $array2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
        , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C");
    return str_replace($array1, $array2, $texto);
}

// Cria uma função que retorna o timestamp de uma data no formato DD/MM/AAAA
function obterTimestamp($data) {
    $partes = explode('/', $data);
    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}

function obterDataISO() {
    $hoje = date('Y-m-d');
    return $hoje;
}

function obterHora() {
    $hoje = date('H:i');
    return $hoje;
}

function obterDataBR() {
    $hoje = date('d/m/Y');
    return $hoje;
}

function obterMesBRTimestamp($data) {
    if ($data != '') {
        $data = substr($data, 0, 10);
        $explodida = explode("-", $data);
        $dataIso = $explodida[1];
        return $dataIso;
    }
    return NULL;
}

function obterDiaBRTimestamp($data) {
    if ($data != '') {
        $data = substr($data, 0, 10);
        $explodida = explode("-", $data);
        $dataIso = $explodida[2];
        return $dataIso;
    }
    return NULL;
}

function obterAnoBRTimestamp($data) {
    if ($data != '') {
        $data = substr($data, 0, 10);
        $explodida = explode("-", $data);
        $dataIso = $explodida[0];
        return $dataIso;
    }
    return NULL;
}

function obterDataBRTimestamp($data) {
    if ($data != '') {
        $data = substr($data, 0, 10);
        $explodida = explode("-", $data);
        $dataIso = $explodida[2] . "/" . $explodida[1] . "/" . $explodida[0];
        return $dataIso;
    }
    return NULL;
}

function convertDataBR2ISO($data) {
    if ($data == '')
        return false;
    $explodida = explode("/", $data);
    $dataIso = $explodida[2] . "-" . $explodida[1] . "-" . $explodida[0];
    return $dataIso;
}

function obterHoraTimestamp($data) {
    return substr($data, 11, 5);
}

function calculaDiferencaDatas($data_inicial, $data_final) {
// Usa a função criada e pega o timestamp das duas datas:
    $time_inicial = geraTimestamp($data_inicial);
    $time_final = geraTimestamp($data_final);

// Calcula a diferença de segundos entre as duas datas:
    $diferenca = $time_final - $time_inicial; // 19522800 segundos
// Calcula a diferença de dias
    $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias
// Exibe uma mensagem de resultado:
//echo "A diferença entre as datas ".$data_inicial." e ".$data_final." é de <strong>".$dias."</strong> dias";
    return $dias;
}

function apelidometadatos($variavel) {
    /* $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ ,;:./';
      $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr______';
      //$string = utf8_decode($string);
      $string = strtr($string, utf8_decode($a), $b); //substitui letras acentuadas por "normais"
      $string = str_replace(" ","",$string); // retira espaco
      $string = strtolower($string); // passa tudo para minusculo */
    $string = strtolower(ereg_replace("[^a-zA-Z0-9-]", "-", strtr(utf8_decode(trim($variavel)), utf8_decode("áàãâéêíóôõúüñçÁÀÃÂÉÊÍÓÔÕÚÜÑÇ"), "aaaaeeiooouuncAAAAEEIOOOUUNC-")));
    return utf8_encode($string); //finaliza, gerando uma saída para a funcao
}

function getExtensaoArquivo($extensao) {
    switch ($extensao) {
        case 'image/jpeg': $ext = ".jpeg";
            break;
        case 'image/jpg': $ext = ".jpg";
            break;
        case 'image/pjpeg': $ext = ".pjpg";
            break;
        case 'image/JPEG': $ext = ".JPEG";
            break;
        case 'image/gif': $ext = ".gif";
            break;
        case 'image/png': $ext = ".png";
            break;
        case 'video/webm': $ext = ".webm";
            break;
        case 'video/mp4': $ext = ".mp4";
            break;
        case 'video/flv': $ext = ".flv";
            break;
        case 'video/webm': $ext = ".webm";
            break;
        case 'audio/mp4': $ext = ".acc";
            break;
        case 'audio/mpeg': $ext = ".mp3";
            break;
        case 'audio/ogg': $ext = ".ogg";
            break;
    }
    return $ext;
}

function uploadArquivoPermitido($arquivo) {
    $tiposPermitidos = array('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'video/webm', 'video/mp4', 'video/ogv', 'audio/mp3', 'audio/mp4', 'audio/mpeg', 'audio/ogg');
    if (array_search($arquivo, $tiposPermitidos) === false) {
        return false;
    } else {
        return true;
    }//end if
}

function converteValorMonetario($valor) {
    $valor = str_replace('.', '', $valor);
    $valor = str_replace('.', '', $valor);
    $valor = str_replace('.', '', $valor);
    $valor = str_replace(',', '.', $valor);
    return $valor;
}

function valorMonetario($valor) {
    $valor = number_format($valor, 2, ',', '.');
    return $valor;
}

function verificarloginduplicado($usuario, $idsessao, $query) {
    $oConexao = Conexao::getInstance();
    $retorno = true;
    $querysessao = $oConexao->query($query);
    $qtdsessao = $querysessao->rowCount();
    if ($qtdsessao == 0) {
        $retorno = false;
    }
    return $retorno;
}

function historicoacesso($pagina, $apelido, $operacao, $usuario, $ip) {
    $oConn = Conexao::getInstance();
    $retorno = true;
    if ($pagina != '' && $operacao != '') {
        $rsUsuarioHist = $oConn->prepare("SELECT count(id) total FROM usuario_hist WHERE datacadastro = now() AND ip = ? AND apelido = ? AND operacao = ?");
        $rsUsuarioHist->bindValue(1, $ip);
        $rsUsuarioHist->bindValue(2, $apelido);
        $rsUsuarioHist->bindValue(3, $operacao);
        $rsUsuarioHist->execute();
        $countUsuarioHist = $rsUsuarioHist->fetch(PDO::FETCH_OBJ)->total;
        if ($countUsuarioHist <= 0) {
            $usuarioHist = $oConn->prepare("INSERT INTO usuario_hist(pagina, apelido, operacao, datacadastro, idusuario, ip) VALUES(?, ?, ?, now(), ?, ?)");
            $usuarioHist->bindValue(1, $pagina);
            $usuarioHist->bindValue(2, $apelido);
            $usuarioHist->bindValue(3, $operacao);
            $usuarioHist->bindValue(4, $usuario);
            $usuarioHist->bindValue(5, $ip);
            // $usuarioHist->bindValue(6, $ip);
            if (!$usuarioHist->execute()) {
                $retorno = false;
            }
        }
    }
    return $retorno;
}

function envia_email($para, $assunto, $mensagem, $nome_email) {
    unset($mail);
// Inicia a classe PHPMailer
    $mail = new PHPMailer();

// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->Host = "mail.sibamachado13.com"; // Endereço do servidor SMTP (caso queira utilizar a autenticação, utilize o host smtp.seudomínio.com.br)
    $mail->SMTPAuth = true; // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
    $mail->Username = 'sibamachado13@sibamachado13.com'; // Usuário do servidor SMTP (endereço de email)
    $mail->Password = 'Pynm55@#$'; // Senha do servidor SMTP (senha do email usado)
    // Activo condificacción utf-8
    $mail->CharSet = 'UTF-8';
// Define o remetente
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->From = "sibamachado13@sibamachado13.com";
    $mail->Sender = "sibamachado13@sibamachado13.com";
    $mail->FromName = $nome_email;
    //Adicionando copia da mensagem

    $mail->AddAddress($para); // E-mail do destinatário
    // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->Subject = $assunto; // Assunto da mensagem
    $mail->Body = $mensagem;
    $mail->AltBody = $mensagem;

    // Envia o e-mail
    $enviado = $mail->Send();

// Limpa os destinatários e os anexos
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();

    if (!$mail->Send()) {
        return true;
    } else {
        return false;
    }
}

function data_volta_hora($data) {
    if ($data == '' || $data == '0000-00-00')
        return '';
    $dd = explode(' ', $data);
    $d = explode('-', $dd[0]);
    return $d[2] . '/' . $d[1] . '/' . $d[0];
}

function getParticipantes($id) {
    switch ($id) {
        case 1:
            $r = 'Acima do Esperado';
            break;
        case 2:
            $r = 'Esperado';
            break;
        case 3:
            $r = 'Abaixo do Esperado';
            break;
        case 4:
            $r = 'Muito Abaixo do Esperado';
            break;
    }
    return $r;
}

function getTipo($id) {
    switch ($id) {
        case 1:
            $r = 'Residencial';
            break;
        case 2:
            $r = 'Comercial';
            break;
        case 3:
            $r = 'Celular';
            break;
        case 4:
            $r = 'Assistente';
            break;
        case 5:
            $r = 'Outro';
            break;
    }
    return $r;
}

function getCorParticipantes($id) {
    switch ($id) {
        case 1:
            $r = 'FF0000';
            break;
        case 2:
            $r = 'FFE900';
            break;
        case 3:
            $r = '00CF43';
            break;
        case 4:
            $r = '0300CF';
            break;
    }
    return $r;
}

function getObjetivo($id, $sem = 0) {
    switch ($id) {
        case 0:
            $r = 'Não';
            $r1 = 'Nao';
            break;
        case 1:
            $r = 'Sim';
            $r1 = 'Sim';
            break;
        case 2:
            $r = 'Parcialmente';
            $r1 = 'Parcialmente';
            break;
    }
    if ($sem == 0) {
        return $r;
    } else {
        return $r1;
    }
}

function getCorObjetivo($id) {
    switch ($id) {
        case 1:
            $r = 'FF0000';
            break;
        case 2:
            $r = 'FFE900';
            break;
        case 0:
            $r = '0300CF';
            break;
    }
    return $r;
}

function getAvaliacaoResultados($id, $sem = 0) {
    switch ($id) {
        case 1:
            $r = 'Ótimo';
            $r1 = 'Otimo';
            break;
        case 2:
            $r = 'Bom';
            $r1 = 'Bom';
            break;
        case 3:
            $r = 'Regular';
            $r1 = 'Regular';
            break;
        case 4:
            $r = 'Ruim';
            $r1 = 'Ruim';
            break;
    }
    if ($sem == 0) {
        return $r;
    } else {
        return $r1;
    }
}

function getCorAvaliacao($id) {
    switch ($id) {
        case 1:
            $r = 'FF0000';
            break;
        case 2:
            $r = 'FFE900';
            break;
        case 3:
            $r = '00CF43';
            break;
        case 4:
            $r = '0300CF';
            break;
    }
    return $r;
}

function getCabecalho($titulo, $especial = 0) {
    $data = date('d/m/Y');
    if ($especial == 0)
        return "<h1><span class = 'logo'>$titulo</span><span class = 'data'>$data</span></h1>";
    else
        return "<h1><span class = 'logo'>$titulo</span><span class = 'data'>$especial</span></h1>";
}

function fill($string, $valor, $qtd) {
    $total = strlen($string);
    for ($i = $total; $i < $qtd; $i++) {
        $string = $valor . $string;
    }
    return $string;
}

function dataExtensoComAno($dt) {
    $da = explode('/', $dt);
    return $da[0] . ' de ' . getMes($da[1]) . ' de ' . $da[2];
}

function dataExtensoSemAno($dt) {
    $da = explode('/', $dt);
    return $da[0] . ' de ' . getMes($da[1]);
}

function dataExtenso($dt) {
    $da = explode('/', $dt);
    return $da[0];
}

function verificaData($dia, $mes, $ano, $acesso) {
    $eventos = array();
    $sql = "SELECT *, DATE_FORMAT(HoraAgenda, '%H:%i') as hora FROM tb_bsc_agenda a" .
            " LEFT JOIN tb_bsc_acesso_agenda aa ON aa.IdAgenda = a.IdAgenda" .
            " WHERE aa.IdAcesso = $acesso AND Confirmado = 1 AND DAY(DataAgenda) = " . $dia . " AND MONTH(DataAgenda) = " . $mes .
            " AND YEAR(DataAgenda) = " . $ano .
            " ORDER BY DataAgenda";
    $q = mysql_query($sql);
    $num = mysql_num_rows($q);

    if ($num > 0) {
        while ($f = mysql_fetch_array($q)) {
            $pos = count($eventos);
            $hora = $f["hora"];
            $eventos[$pos][0] = $f["IdAgenda"];
            $eventos[$pos][1] = $hora . " - " . $f["Pauta"];
        }
    }
    return $eventos;
}

function verificaAcesso($acesso, $pg) {
    $verif = "SELECT * FROM tb_bsc_controle WHERE Pagina = '$pg' AND IdAcesso = $acesso";
    $qverif = mysql_query($verif);
    $numverif = mysql_num_rows($qverif);
    if ($numverif == 0) {
        echo "<script>alert('Você não tem acesso a esta área!');
window.location = 'index.php';</script>;";
        exit();
    }
}

function enviaEmail($agenda, $st) {
    /*
      $selagenda = "SELECT *, c.Nome as NomeCandidato, s.Descricao as NomeSegmento, a.Numero as num, bai.nome as nomeBairro FROM tb_bsc_agenda a
      LEFT JOIN tb_bsc_municipios m ON m.IdMunicipio = a.IdMunicipio
      LEFT JOIN tb_bsc_candidato c ON a.IdCandidato = c.IdCandidato
      LEFT JOIN tb_bsc_segmento s ON a.IdSegmento = s.IdSegmento
      LEFT JOIN tb_bsc_prioridade p ON a.IdPrioridade = p.IdPrioridade
      LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.bairro
      WHERE IdAgenda = $agenda";
     */
    $selagenda = "SELECT *, p.Descricao as descPri, s.Descricao as descSeg, u.Nome as NomeUsuario, cont.nome as participante, bai.nome as nomeBairro, reg.nome as nomeRegional, m.NomeMunicipio, a.IdMunicipio FROM tb_bsc_agenda a 
LEFT JOIN tb_bsc_municipios m ON m.IdMunicipio = a.IdMunicipio
LEFT JOIN tb_bsc_tipoagenda ta ON ta.IdTipoAgenda = a.IdTipoAgenda
LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade
LEFT JOIN tb_bsc_segmento s ON s.IdSegmento = a.IdSegmento
LEFT JOIN tb_bsc_usuario u ON a.IdUsuario = u.IdUsuario
LEFT JOIN tb_bsc_contato cont ON cont.idcontato = a.contato_participante
LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.bairro
LEFT JOIN tb_bsc_regional reg ON bai.regional = reg.idregional
WHERE a.IdAgenda = $agenda";
    $qselagenda = mysql_query($selagenda);
    $f = mysql_fetch_array($qselagenda);
    $dt = data_volta($f['DataAgenda']);
    $hr = hora($f['HoraAgenda']);

    switch ($st) {
        case 1:
            $assunto = "Agenda Confirmada dia $dt às $hr hs";
            $aviso = '<span style="font-family: Calibri, Arial, Helvetica; font-size: 25px; width: 320px; display: block; background-color: #489500; color: white; font-weight: normal; text-align: right; padding: 8px 30px 0 0px; height: 40px; margin-top: 22px; margin-left: -20px;">AGENDA CONFIRMADA</span>';
            break;
        case 2:
            $assunto = "Agenda Confirmada dia $dt às $hr hs";
            $aviso = '<span style="font-family: Calibri, Arial, Helvetica; font-size: 25px; width: 320px; display: block; background-color: #489500; color: white; font-weight: normal; text-align: right; padding: 8px 30px 0 0px; height: 40px; margin-top: 30px; margin-left: -20px;">AGENDA CONFIRMADA</span>';
            break;
        case 3:
            $assunto = "Agenda do dia $dt às $hr foi CANCELADA";
            $aviso = '<span style="font-family: Calibri, Arial, Helvetica; font-size: 25px; width: 320px; display: block; background-color: #EB2428; color: white; font-weight: normal; text-align: right; padding: 8px 30px 0 0px; height: 40px; margin-top: 30px; margin-left: -20px;">AGENDA CANCELADA</span>';
            break;
    }
    switch ($f['IdPrioridade']) {
        case 1: $cor = "#39B54A; color: white;";
            break;
        case 2: $cor = "#FBB03B; color: #333333;";
            break;
        case 3: $cor = "#EB2428; color: white";
            break;
    }
    switch ($f['IdPrioridade']) {
        case 1: $prio = "Baixa";
            break;
        case 2: $prio = "Média";
            break;
        case 3: $prio = "Alta";
            break;
    }
    $cor_conf = $f['Confirmado'] == 1 ? 'verde' : 'vermelha';
    $municipio_nome = utf8_encode($f['nomeBairro']);

    $msg = '
<div style="background-color: #F7F7F7; width: 96%; display: block; padding: 0 0 20px 0; margin-left: -0.1%; margin-top: 10px; position: relative; left: 2%;">
    <span align="center" style="font-family: Calibri, Helvetica, Arial; font-weight: normal; text-transform:uppercase; font-size: 20px; text-align: center; color: #FFF;padding: 2% 0; background-color: #666666; display: block; width: 100%;">' . TITULO . '</span>
    <img src="' . ENDERECO . '/img/logo_email.svg" align="left" alt="' . TITULO . '" style="display: block; width: 25%; background-size: cover;overflow: hidden; margin: 3% 0 1% 3%;"></img>
    <img src="' . ENDERECO . '/img/pmrb_logo.png" align="right" alt="' . TITULO . '" style="display: block; width: 25%; background-size: cover;overflow: hidden; margin: 3% 3% 1% 0;"></img>
    <hr size=1 style="width: 94%; color: #E6E6E6; display: block; margin: 15% 3% 0 2%;">
    ' . $aviso . '
    <h1 style="font-family: Calibri, Helvetica, Arial; margin-top: 50px; font-weight:normal; font-size: 22px; text-align: left; color: #000; padding-left: 10%;">' . dataExtenso(data_volta($f['DataAgenda'])) . ' (' . hoje(data_volta($f['DataAgenda'])) . ')</h1>
    <h2 style="font-family: Calibri, Helvetica, Arial; font-weight: bold; font-size: 24px; text-align: left; color: #000; padding-left: 10%; margin: -5px 0 30px 0;">' . hora($f['HoraAgenda']) . '</h2>
    <span style="font-family: Calibri, Helvetica, Arial; font-size: 18px; font-weight: normal; ftext-align: left; color: #808080; padding-left: 10%;">Pauta</span>
    <hr size=1 style="width: 85%; color: #E6E6E6; display: block; margin: -17px 5% 0 10%;">
    <span style="font-family: Calibri, Helvetica, Arial; font-weight: normal; font-size: 30px; text-align: left; color: #333; padding-left: 10%; margin-top: 6px; margin-right: 6.5%; display: block;">' . $f['Pauta'] . '</span>
    <span style="font-family: Calibri, Helvetica, Arial; font-size: 18px; font-weight: normal; ftext-align: left; color: #808080; padding-left: 10%; display: block; margin-top: 3%;">Demandante</span>
    <span style="font-family: Calibri, Helvetica, Arial; font-weight: normal; font-size: 24px; text-align: left; color: #333; padding-left: 10%; margin: -25px 0 0 0; display: block;">' . $f['Demandante'] . '</span>
    <span style="font-family: Calibri, Helvetica, Arial; font-size: 18px; font-weight: normal; ftext-align: left; color: #808080; padding-left: 10%; display: block;  margin-top: 3%;">Local</span>
    <span style="font-family: Calibri, Helvetica, Arial; font-weight: normal; font-size: 24px; text-align: left; color: #333; padding-left: 10%; margin-top: -5%; display: block;">' . $f['LocalEvento'] . '</span>
    <span style="font-family: Calibri, Helvetica, Arial; font-size: 18px; font-weight: normal; ftext-align: left; color: #808080; padding-left: 10%; display: block; margin-top: 3%;">Bairro</span>
    <span style="font-family: Calibri, Helvetica, Arial; font-weight: normal; font-size: 24px; text-align: left; color: #333; padding-left: 10%; margin: -25px 0 0 0; display: block;">' . $municipio_nome . '</span>
    <span style="font-family: Calibri, Helvetica, Arial; font-size: 18px; font-weight: normal; ftext-align: left; color: #808080; padding-left: 10%; display: block; margin-top: 3%;">Quem vai</span>
    <span style="font-family: Calibri, Helvetica, Arial; font-weight: normal; font-size: 24px; text-align: left; color: #333; padding-left: 10%; margin: -25px 0 0 0; display: block;">' . utf8_encode($f['participante']) . '</span>
    <h3 style="width: 280px; display: block; background-color: ' . $cor . ' ; height: 38px; font-family: Calibri, Helvetica, Arial; font-weight: normal; font-size: 20px; text-align: left; padding: 6px 0 9px 60px; margin: 20px 0 0 10%;">Prioridade<span style="font-size: 30px; padding: 0 0 0 30px;">' . $prio . '</span></h3>
    <hr size=1 style="width: 91%; color: #c1c1c1; display: block; margin: 30px 5% 0 4%; border-top: 1px dotted;">
    <a href="' . ENDERECO . '" style="margin-top: 40px; display: block; font-family: Calibri, Helvetica, Arial; text-align: center; color: #0060BC;">[ CLIQUE AQUI PARA ACESSAR O SISTEMA DE AGENDA ]</a>
</div>
<h2 style=" font-family: Calibri, Helvetica, Arial; margin-top: 20px; font-weight:normal; font-size: 12px; text-align: center; color: #CCCCCC;">Sistema de ' . TITULO . '  ©  ' . $ano . '</h2>
';

    $selemail = "SELECT Email, RecebeEmail FROM tb_bsc_usuario u 
LEFT JOIN tb_bsc_acesso_agenda aa ON aa.IdAcesso = u.IdAcesso WHERE aa.IdAgenda = $agenda AND Status = 1";
    $qselmail = mysql_query($selemail);

    $selgrupo = "SELECT email as Email FROM tb_bsc_contato c
LEFT JOIN tb_bsc_contato_grupo cg ON c.idcontato = cg.idcontato
LEFT JOIN tb_bsc_agenda_email ae ON ae.IdGrupoEmail = cg.idgrupo
WHERE ae.IdAgenda = $agenda";
    $qselgrupo = mysql_query($selgrupo);

// O remetente deve ser um e-mail do seu domínio conforme determina a RFC 822.
// O return-path deve ser ser o mesmo e-mail do remetente.
//JUNIOR AQUI
    $headers = "MIME-Version: 1.1\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: Gabinete do Prefeito Marcus Alexandre <prefeito@rtvac.com>\n"; // remetente
    $headers .= "Return-Path: Gabinete do Prefeito Marcus Alexandre <prefeito@rtvac.com>\n"; // return-path

    if ($fselagenda['EmailDemandante'] != '') {
        $envio = mail($fselagenda['EmailDemandante'], $assunto, $msg, $headers);
    }
    if ($fselagenda['EmailDemandante'] != '') {
        if ($fselagenda['EmailDemandante'] != $fselagenda['EmailCon'])
            $envio = mail($fselagenda['EmailCon'], $assunto, $msg, $headers);
    }
    while ($fselmail = mysql_fetch_array($qselmail)) {
        if ($fselmail['RecebeEmail'] == 1)
            $envio = mail($fselmail['Email'], $assunto, $msg, $headers);
    }
    while ($fselgrupo = mysql_fetch_array($qselgrupo)) {
        $envio = mail($fselgrupo['Email'], $assunto, $msg, $headers);
    }
}

function verifica_login($usuario, $idsessao) {
    $ret = true;
    $sel_sessao = "SELECT * FROM info_login WHERE idusuario = $usuario AND idsessao = '$idsessao'";
    $qsel_sessao = mysql_query($sel_sessao);
    $num_sessao = mysql_num_rows($qsel_sessao);
    if ($num_sessao == 0) {
        $ret = false;
    }
    return $ret;
}

function br2nl($string) {
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

//----------------------------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA REALIZAR QUALQUER TIPO DE PESQUISA NO BANCO E RETORNAR UM VALOR DESEJADO - PODE FAZER ATÉ 4 CONDIÇÕES
function pesquisa2($retorno, $tabela, $campo1, $valor1, $campo2, $valor2, $campo3, $valor3, $campo4, $valor4, $operacao) {

    $db = Conexao::getInstance();

    $sql = $db->prepare("SELECT $retorno FROM $tabela WHERE $campo1 $campo2 $campo3 $campo4 $operacao");

    $sql->bindParam(1, $valor1);
    if ($valor2 != "")
        $sql->bindParam(2, $valor2);
    if ($valor3 != "")
        $sql->bindParam(3, $valor3);
    if ($valor4 != "")
        $sql->bindParam(4, $valor4);

    $sql->execute();

    if ($sql->rowCount() > 0) {
        $l = $sql->fetch(PDO::FETCH_BOTH);
        return $l[$retorno];
    }
    return "vazio"; //Retorna vazio caso não tenha encontrado o resultado desejado
}

//FUNÇÃO PARA CRIAR O EMAIL DA DEMANDA
function criar_email_demanda($demanda_descricao, $data_prazo, $respo) {
    $resultado = '';

    $prazo = hoje2($data_prazo) . " " . obterDiaBRTimestamp($data_prazo) . " " . getMes2(obterMesBRTimestamp($data_prazo)) . " " . obterAnoBRTimestamp($data_prazo);

    $resultado.= '
        <div style="width: 600px; background-color: white; margin: 15px auto;-webkit-box-shadow: 0px 3px 19px -2px rgba(0,0,0,0.53); -moz-box-shadow: 0px 3px 19px -2px rgba(0,0,0,0.53); box-shadow: 0px 3px 19px -2px rgba(0,0,0,0.53); padding-bottom: 6px;">

            <div style="height: 120px;">
                <img src="http://sibamachado13.com/agenda/img/logo_login.png" style="margin: 20px 71px;">
            </div>
            <div style="width: 100%; height: 92px; background-color: #7BC10C; display: block;">
                <img style="margin: 13px 143px 6px;" src="http://sibamachado13.com/agenda/img/email-demanda.png">
            </div>

            <div style="width: 93%; margin: auto;">
                <div style="margin: 35px 0 4px 0; font-family: Helvetica, Arial, sans-serif;color:darkgreen;">DEMANDA</div>

                <hr size="2" width="100%" align="center" noshade color="darkgreen">

                <div style="font-family: Helvetica, Arial, sans-serif; font-size: 24px;">
                    <b>
                    ' . $demanda_descricao . '
                    </b>
                </div>

                <div style="margin: 20px 0 4px 0; font-family: Helvetica, Arial, sans-serif; color:darkgreen;">PRAZO</div>

                <hr size="2" width="100%" align="center" noshade color="darkgreen">

                <div style="font-family: Helvetica, Arial, sans-serif; font-size: 18px;">
                ' . $prazo . '    
                </div>

                <div style="margin: 20px 0 4px 0; font-family: Helvetica, Arial, sans-serif; color:darkgreen;">RESPONSÁVEL(EIS)</div>

                <hr size="2" width="100%" align="center" noshade color="darkgreen" style="margin-bottom: -5px;">

                <ul style="font-family: Helvetica, Arial, sans-serif; font-size: 15px;list-style-type: none;margin-left: -38px;">
                    ';
    if (isset($respo) && $respo != NULL && $respo != "") {
        foreach ($respo as $valu) {
            $resultado.= '<li s tyle="margin: 0 0 4px 0;">' . utf8_encode(pesquisa2("Nome", "tb_bsc_usuario", "IdUsuario = ?", $valu, "", "", "", "", "", "", "")) . '</li>';
        }
    }

    $resultado .= '</ul>
            </div>
            <a href="http://sibamachado13.com" target="_blank" style="margin: 30px auto 20px auto; display: block; width: 148px; "><img src="http://sibamachado13.com/agenda/img/email-mais-detalhes.png"></a>
        </div>';

    return $resultado;
}

//FUNÇÃO PARA CRIAR O EMAIL DA AGENDA
function criar_email_agenda($pauta, $local, $bairro, $dt_agenda, $hora_agenda, $confirmado) {


    $resultado = '';

    $db = Conexao::getInstance();
    $resultado.= '<html>
            <head>
                <meta charset="utf-8" lang="pt-br">
            </head>
        <body style="background-color: #fcfcfc;">
            <div style="width: 600px; background-color: white; margin: 15px auto;-webkit-box-shadow: 0px 3px 19px -2px rgba(0,0,0,0.53); -moz-box-shadow: 0px 3px 19px -2px rgba(0,0,0,0.53); box-shadow: 0px 3px 19px -2px rgba(0,0,0,0.53); padding-bottom: 6px;">

                <div style="height: 120px;">
                    <img src="http://sibamachado13.com/agenda/img/logo_login.png" style="margin: 20px 71px;">
                </div>
                <div style="width: 100%; height: 92px; background-color: red; display: block;">
                    <img style="margin: 8px 176px 8px;" src="http://sibamachado13.com/agenda/img/email-agenda.png">
                </div>';

    $data_agenda = hoje2($dt_agenda) . " " . obterDiaBRTimestamp($dt_agenda) . " " . getMes2(obterMesBRTimestamp($dt_agenda)) . " " . obterAnoBRTimestamp($dt_agenda) . " - " . hora($hora_agenda);

    if ($confirmado == 1) {
        $resultado .= " <div style='background-color: #1dc116; color: white; font-family: Helvetica, Arial, sans-serif; font-size: 25px; text-align: center; padding: 8px 0 4px 0;'>CONFIRMADA</div> ";
    } else {
        $resultado.= " <div style='background-color: #CC0000; color: white; font-family: Helvetica, Arial, sans-serif; font-size: 25px; text-align: center; padding: 8px 0 4px 0;'>CANCELADA</div> ";
    }


    $resultado.= '<div style="font-family: Helvetica, Arial, sans-serif; font-size: 31px; text-align: center; margin: 25px 0 35px 0;">' . $data_agenda . '</div>

                <div style="width: 93%; margin: auto;">
                    <div style="margin: 35px 0 4px 0; font-family: Helvetica, Arial, sans-serif;color:red;">PAUTA</div>

                    <hr size="2" width="100%" align="center" noshade color="red">

                    <div style="font-family: Helvetica, Arial, sans-serif; font-size: 24px;">
                        <b>
                        ' . $pauta . '
                        </b>
                    </div>

                    <div style="margin: 20px 0 4px 0; font-family: Helvetica, Arial, sans-serif; color:red;">LOCAL</div>

                    <hr size="2" width="100%" align="center" noshade color="red">

                    <div style="font-family: Helvetica, Arial, sans-serif; font-size: 18px;">
                    ' . $local . '    
                    </div>

                    <div style="font-family: Helvetica, Arial, sans-serif; font-size: 18px; margin-top: 6px;">
                        Bairro <b>' . $bairro . '</b> - <b>Rio Branco</b> - <b>AC</b>
                    </div>
                </div>
                <a href="http://sibamachado13.com" target=”_blank” style="margin: 30px auto 20px auto; display: block; width: 148px; "><img src="http://sibamachado13.com/agenda/img/email-mais-detalhes.png"></a>
            </div>
        </body>
    </html>
';
    return $resultado;
}

?>