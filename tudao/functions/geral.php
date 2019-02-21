<?PHP
//----------------------------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA CARREGAR PONTOS GANHOS DO CLIENTE PELA COMPRA
function carregar_pontos_ganhos_cliente($pedido_id) {

    $db = Conexao::getInstance();

    $total_pontos = 0;

    $sql = $db->prepare("SELECT pr.pontuacao_recebida, pi.quantidade
                             FROM pedidos_itens pi
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             LEFT JOIN produtos AS pr ON pr.id = pi.produto_id
                             WHERE pe.id = ?
                             GROUP BY pi.id
                             ORDER BY pr.nome ASC");
    $sql->bindValue(1, $pedido_id);
    $sql->execute();
    while ($pedidos = $sql->fetch(PDO::FETCH_ASSOC)) {
        $total_pontos += ($pedidos['pontuacao_recebida'] * $pedidos['quantidade']);
    }

    return $total_pontos;
}
//----------------------------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA CARREGAR PONTOS GANHOS PELA COMPRA
function carregar_pontos_ganhos($numero_sessao) {

    $db = Conexao::getInstance();

    $total_pontos = 0;

    $sql = $db->prepare("SELECT pr.pontuacao_recebida, pi.quantidade
                             FROM pedidos_itens pi
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             LEFT JOIN produtos AS pr ON pr.id = pi.produto_id
                             WHERE pe.status = 0 AND pe.numero_sessao = ?
                             GROUP BY pi.id
                             ORDER BY pr.nome ASC");
    $sql->bindValue(1, $numero_sessao);
    $sql->execute();
    while ($pedidos = $sql->fetch(PDO::FETCH_ASSOC)) {
        $total_pontos += ($pedidos['pontuacao_recebida'] * $pedidos['quantidade']);
    }

    return $total_pontos;
}

//----------------------------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA RETORNAR SOMENTE O PRIMEIRO NOME DA PESSOA
function primeiro_nome($nome) {
    $primeiro_nome = "";
    $vf = true;

    for ($x = 0; ($x < strlen($nome)); $x++) {
        if ($nome[$x] != " " && $vf) {
            $primeiro_nome .= $nome[$x];
        } else {
            $vf = false;
        }
    }

    return $primeiro_nome;
}

//----------------------------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA RETORNAR O STATUS DO PEDIDO
function pedido_status($status) {
    if ($status == 0) {
        return "Escolhendo Pedido";
    } else if ($status == 1) {
        return "Em Atendimento";
    } else if ($status == 2) {
        return "Pronto para Envio";
    } else if ($status == 3) {
        return "Enviado para Entrega";
    } else if ($status == 4) {
        return "Concluído";
    } else if ($status == 5) {
        return "Cancelado";
    }
}

//----------------------------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA CARREGAR VALORES DO CARRINHO
function valores_carirnho() {

    $numero_sessao = session_id();

    $db = Conexao::getInstance();

    $subtotalgeral = 0;
    $subpontosgeral = 0;
    $subtotal = 0;
    $subpontos = 0;
    $carrinho_qtd = 0;
    $sql = $db->prepare("SELECT pi.observacao, pi.id AS pedidos_itens_id, pr.pontuacao_cobrada, pr.id AS produto_id, pi.quantidade, pe.id, pr.nome AS produto, pr.foto_cortada, pr.valor
                             FROM pedidos_itens pi
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             LEFT JOIN produtos AS pr ON pr.id = pi.produto_id
                             WHERE pe.status = 0 AND pe.numero_sessao = ?
                             GROUP BY pi.id
                             ORDER BY pr.nome ASC");
    $sql->bindValue(1, $numero_sessao);
    $sql->execute();
    while ($pedidos = $sql->fetch(PDO::FETCH_ASSOC)) {
        $subtotal = 0;
        $subpontos = 0;
        $ingredientes = "";
        $sql2 = $db->prepare("SELECT i.nome, i.add_valor, i.add_pontos
                                         FROM ingredientes i
                                         LEFT JOIN produtos_ingredientes AS pi ON pi.ingrediente_id = i.id
                                         WHERE pi.produto_id = ?
                                         GROUP BY i.id
                                         ORDER BY i.nome ASC");
        $sql2->bindValue(1, $pedidos['produto_id']);
        $sql2->execute();
        while ($itens = $sql2->fetch(PDO::FETCH_ASSOC)) {
            $ingredientes .= $itens['nome'] . ", ";
        }

        $sql3 = $db->prepare("SELECT i.nome, i.add_valor, i.add_pontos
                                         FROM ingredientes i
                                         LEFT JOIN pedidos_itens_ingredientes AS pii ON pii.ingrediente_id = i.id
                                         LEFT JOIN pedidos_itens AS pi ON pi.id = pii.pedidos_itens_id
                                         WHERE pi.produto_id = ? AND pi.pedido_id = ?
                                         GROUP BY i.id
                                         ORDER BY i.nome ASC");
        $sql3->bindValue(1, $pedidos['produto_id']);
        $sql3->bindValue(2, $pedidos['id']);
        $sql3->execute();
        while ($pedidos_itens = $sql3->fetch(PDO::FETCH_ASSOC)) {
            $ingredientes .= $pedidos_itens['nome'] . ", ";
            $subtotal += $pedidos['quantidade'] == 1 ? $pedidos_itens['add_valor'] : ($pedidos_itens['add_valor'] * $pedidos['quantidade']);
            $subpontos += $pedidos['quantidade'] == 1 ? $pedidos_itens['add_pontos'] : ($pedidos_itens['add_pontos'] * $pedidos['quantidade']);
        }

        $subtotal += ($pedidos['valor'] * $pedidos['quantidade']);
        $subpontos += ($pedidos['pontuacao_cobrada'] * $pedidos['quantidade']);

        $subtotalgeral += $subtotal;
        $subpontosgeral += $subpontos;

        $carrinho_qtd += $pedidos['quantidade'];
    }
    return $carrinho_qtd . " item(s) - R$ " . fdec($subtotalgeral);
}

//----------------------------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA INSERIR INGREDIENTES NO CARRINHO
function inserir_ingredientes($produto_id, $obs, $quantidade, $ingredientes, $numero_sessao) {

    $db = Conexao::getInstance();

    $pedido_id = pesquisar_tabela("id", "pedidos", "numero_sessao", "=", $numero_sessao, "AND status = 0");

    if (!is_numeric($pedido_id)) {
        $rs = $db->prepare("INSERT INTO pedidos (numero_sessao, cadastro, status) VALUES (?, NOW(), 0)");
        $rs->bindValue(1, $numero_sessao);
        $rs->execute();

        $pedido_id = $db->lastInsertId();

        $rs2 = $db->prepare("INSERT INTO pedidos_itens (pedido_id, produto_id, observacao, quantidade, cadastro, status)
                 VALUES (?, ?, ?, ?, NOW(),1)");
        $rs2->bindValue(1, $pedido_id);
        $rs2->bindValue(2, $produto_id);
        $rs2->bindValue(3, $obs != "" ? $obs : NULL);
        $rs2->bindValue(4, $quantidade);
        $rs2->execute();

        $pedidos_itens_id = $db->lastInsertId();

        if (isset($ingredientes)) {
            foreach ($ingredientes AS $key => $value) {
                if ($value != "" && $value != NULL) {
                    $rs3 = $db->prepare("INSERT INTO pedidos_itens_ingredientes (pedidos_itens_id, ingrediente_id, cadastro, status)
                 VALUES (?, ?, NOW(),1)");
                    $rs3->bindValue(1, $pedidos_itens_id);
                    $rs3->bindValue(2, $value);
                    $rs3->execute();
                }
            }
        }
    } else {
        if (!is_numeric(pesquisar_tabela("id", "pedidos_itens", "pedido_id", "=", $pedido_id, "AND produto_id = '$produto_id' AND observacao = '$obs' AND quantidade = '$quantidade'"))) {
            $rs2 = $db->prepare("INSERT INTO pedidos_itens (pedido_id, produto_id, observacao, quantidade, cadastro, status)
                 VALUES (?, ?, ?, ?, NOW(),1)");
            $rs2->bindValue(1, $pedido_id);
            $rs2->bindValue(2, $produto_id);
            $rs2->bindValue(3, $obs != "" ? $obs : NULL);
            $rs2->bindValue(4, $quantidade);
            $rs2->execute();

            $pedidos_itens_id = $db->lastInsertId();

            if (isset($ingredientes)) {
                foreach ($ingredientes AS $key => $value) {
                    if ($value != "" && $value != NULL) {
                        $rs3 = $db->prepare("INSERT INTO pedidos_itens_ingredientes (pedidos_itens_id, ingrediente_id, cadastro, status)
                 VALUES (?, ?, NOW(), 1)");
                        $rs3->bindValue(1, $pedidos_itens_id);
                        $rs3->bindValue(2, $value);
                        $rs3->execute();
                    }
                }
            }
        }
    }
}

//----------------------------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA RETORNAR A QUANTIDADE DE PRODUTOS PELA CATEGORIA
function qtd_produtos_categoria($id) {
    $db = Conexao::getInstance();

    $rs = $db->prepare("SELECT id 
             FROM produtos
             WHERE categoria_id = ? AND status = 1");
    $rs->bindValue(1, $id);
    $rs->execute();

    return $rs->rowCount();
}

//----------------------------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA REMOVER SQL DOS PARÂMETROS PASSADOS POR URL
function antiSQL($campo, $adicionaBarras = false) {
// remove palavras que contenham sintaxe sql
    $campo = preg_replace("/(from| or | and |alter table|select|insert|delete|update|were|drop table|show tables|#|\*|--|\\\\)/i", "Anti Sql-Injection - bjus Mãe !", $campo);
    $campo = trim($campo); //limpa espaços vazio
    $campo = strip_tags($campo); //tira tags html e php
    if ($adicionaBarras || !get_magic_quotes_gpc())
        $campo = addslashes($campo);
    return $campo;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICAR NÍVEL
// DESCRIÇÃO: VERIFICA NÍVEL DE ACESSO AO SISTEMA
function ver_nivel($sistema, $nivel, $redir = '') {

    if (isset($_SESSION['id'])) {
        $erro = false;
        if (($sistema == '') or ( $nivel == ''))
            $erro = false;

        if (pesquisar_tabela('user_id', 'permissoes', 'user_id', '=', $_SESSION['id'], ' AND nivel = ' . $nivel . ' AND sistema = ' . $sistema)) {
            return true;
        } else {
            if ($redir == '')
                return false;
            else {
                msg('Você não possui permissão para acessar está área.');
                url('index.php');
            }
        }
    } else {
        msg('Você não possui permissão para acessar está área.');
        url("" . PORTAL_URL . "logout.php");
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: CARREGAR URL
// DESCRIÇÃO: CARREGA A URL INFORMADA
function url($end) {
    echo "<script language='javaScript'>window.location.href='$end'</script>";
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: ENVIAR ALERT
// DESCRIÇÃO: ENVIAR ALERT INFORMADO
function msg($msg) {
    echo "<script language='javaScript'>window.alert('$msg')</script>";
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: CALCULAR IDADE
// DESCRIÇÃO: CALCULA A IDADE ATRAVÉS DE UMA DATA PASSADA
function calcular_idade($data_nascimento) {
// Separa em ano, mês e dia
    list ( $ano, $mes, $dia ) = explode('-', $data_nascimento);

// Descobre que dia é hoje e retorna a unix timestamp
    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
// Descobre a unix timestamp da data de nascimento do fulano
    $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

// Depois apenas fazemos o cálculo já citado :)
    $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
    return $idade;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: ADICIONAR MÁSCARAS
// DESCRIÇÃO: PARA ADICIONAR MÁSCARAS
function adicionar_mascara($campo, $tipo) {

// REMOVENDO MÁSCARAS
    $campo = remover_mascara($campo);

// MÁSCARA CPF
    if (ctexto($tipo, "min") == "cpf") {
        return mask($campo, "###.###.###-##");
    }
// MÁSCARA CNPJ
    if (ctexto($tipo, "min") == "cnpj") {
        return mask($campo, "##.###.###/####-##");
    }
// MÁSCARA CONTATO
    if (ctexto($tipo, "min") == "contato") {
        if (strlen($campo) == 10) {
            return mask($campo, "(##)####-####");
        } else {
            return mask($campo, "(##)#####-####");
        }
    }
// MÁSCARA DATA BR
    if (ctexto($tipo, "min") == "data_br") {
        return mask($campo, '##/##/####');
    }
// MÁSCARA DATA ISO
    if (ctexto($tipo, "min") == "data_iso") {
        return convertDataBR2ISO(mask($campo, '##/##/####'));
    }
// MÁSCARA CEP
    if (ctexto($tipo, "min") == "cep") {
        return mask($campo, '#####-###');
    }
// MÁSCARA VALOR
    if (ctexto($tipo, "min") == "valor") {
        return fdec($campo);
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: RETIRAR MÁSCARAS
// DESCRIÇÃO: PARA RETIRAR MÁSCARAS
function remover_mascara($campo) {
    return preg_replace('/[^0-9]/', '', $campo);
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: ATRIBUIR MÁSCARAS AOS CAMPOS
// DESCRIÇÃO: COLOCAR MÁSCARAS NOS CAMPOS
function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i ++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k ++];
        } else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICAR SESSION
// DESCRIÇÃO: PARA VERIFICAR SE A SESSÃO EXPIROU
function vf_session() {
    if (!isset($_SESSION['id'])) {
        echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "logout';</script>";
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: GERADOR DE LOGIN
// DESCRIÇÃO: GERADOR LOGIN ATRAVÉS DO E-MAIL INSTITUCIONAL
function gerar_login($email_institucional) {
    $login = "";
    $vf = false;

    for ($k = 0; ($k < strlen($email_institucional)); $k ++) {
        if ($email_institucional[$k] != '@' && $vf == false) {
            $login .= "$email_institucional[$k]";
        } else {
            $vf = true;
        }
    }

    return $login;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: RESUMIR TEXTO
// DESCRIÇÃO: SE O TEXTO FOR MAIOR O QUE LIMITE INFORMADO, ENTÃO A FUNÇÃO CORTA O TEXTO E ADD 3 PONTINHOS.
function resume($var, $limite) {
    if (strlen($var) > $limite) {
        $var = substr($var, 0, $limite);
        $var = trim($var) . "...";
    }
    return $var;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICAR SE USUÁRIO ESTÁ ONLINE
// DESCRIÇÃO: VERIFICA SE O USUÁRIO REALIZOU ALGUMA AÇÃO EM 30 MINUTOS NO SISTEMA, ENTÃO SE NÃO TIVER REALIZADO ELE É CONSIDERADO OFFLINE
function vf_online($id) {
    $db = Conexao::getInstance();

    $rs = $db->prepare(" SELECT id 
             FROM seg_sessao
             WHERE usuario_id = ? AND DATE(atualizacao) = DATE(NOW()) AND HOUR(atualizacao) = HOUR(NOW())
             AND MINUTE(atualizacao) >= (MINUTE(NOW())-30)");
    $rs->bindValue(1, $id);
    $rs->execute();

    if (is_numeric($rs->rowCount()) && $rs->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: STATUS INVERSO
// DESCRIÇÃO: FUNÇÃO PARA RETORNAR O STATUS
function status_inverso($codigo) {
    if (strtoupper($codigo) == "ATIVO")
        return 1;
    else if (strtoupper($codigo) == "INATIVO")
        return 0;
    else
        return 2;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: INFORMAÇÕES DO USUÁRIO
// DESCRIÇÃO: RETORNA INFORMAÇÕES SOBRE O USUÁRIO PELO ID
function info_usuario($id_usuario) {
    $db = Conexao::getInstance();

    $rs = $db->prepare("SELECT u.sexo_id, u.nome, o.sigla 
            FROM seg_usuario AS u 
            LEFT JOIN bsc_unidade_organizacional AS o ON o.id = u.unidade_organizacional_id
            WHERE u.id = ?");
    $rs->bindValue(1, $id_usuario);
    $rs->execute();
    $usuario = $rs->fetch(PDO::FETCH_ASSOC);

    return $usuario['sexo_id'] == 1 ? "O usuário " . $usuario['nome'] . " (" . $usuario['sigla'] . ")" : "A usuária " . $usuario['nome'] . " (" . $usuario['sigla'] . ")";
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICA USUÁRIOS NA PÁGINA
// DESCRIÇÃO: VERIFICA SE JÁ EXISTE MAIS DE UM USUÁRIO NA MESMA PÁGINA, PARA EVITAR DOIS OU MAIS USUÁRIOS ATUALIZANDO A MESMA PÁGINA
function vf_usuario_pagina($pagina) {
    $vf = 0;

    @session_start();

    $db = Conexao::getInstance();

    $rs = $db->prepare("SELECT usuario_id FROM seg_sessao WHERE usuario_id <> ? AND pagina = ? ORDER BY atualizacao DESC");
    $rs->bindValue(1, $_SESSION['id']);
    $rs->bindValue(2, $pagina);
    $rs->execute();

    while ($sessao = $rs->fetch(PDO::FETCH_ASSOC)) {
        if (vf_on_usuario($sessao['usuario_id'])) {
            $vf = $sessao['usuario_id'];
        }
    }
    return $vf;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: INSERIR PÁGINA NA SESSÃO
// DESCRIÇÃO: INSERINDO PÁGINA QUE O USUÁRIO ESTÁ NA TABELA SESSÃO
function inserir_sessao() {
    @session_start();
    $db = Conexao::getInstance();
    if ($GLOBALS['urlArquivo'] != 'css' && $GLOBALS['urlPasta'] != 'media' && $GLOBALS['urlArquivo'] != 'js') {
        $rs = $db->prepare("UPDATE seg_sessao SET atualizacao = NOW(), pagina = ? WHERE usuario_id = ?");
        $rs->bindValue(1, $GLOBALS['urlModulo'] . "/" . $GLOBALS['urlPasta'] . "/" . $GLOBALS['urlArquivo'] . "/" . $GLOBALS['urlParametro']);
        $rs->bindValue(2, $_SESSION['id']);
        $rs->execute();
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICAR LOGIN DE USUÁRIO
// DESCRIÇÃO: VERIFICA SE O USUÁRIO ESTÁ ONLINE E INSERI SEUS DADOS NA TABELA SESSAO
function vf_usuario_login() {
    if (isset($_SESSION['id'])) {
        inserir_sessao(); // INSERI NA SESSÃO A DATA E HORA DA ÚLTIMA AÇÃO DO USUÁRIO
    } else if (Url::getURL(0) != 'login' && Url::getURL(0) != 'logout') {
        echo "<script>window.location = '" . PORTAL_URL . "logout';</script>";
        exit();
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICA SE O USUÁRIO ESTÁ ONLINE
// DESCRIÇÃO: VERIFICA ATRAVÉS DO ID DO USUÁRIO SE O MESMO ENCONTRA-SE ONLINE
function vf_on_usuario($id) {
    $db = Conexao::getInstance();
    $result = $db->prepare("SELECT id, online, status from seg_usuario WHERE id = ?");
    $result->bindValue(1, $id);
    $result->execute();
    while ($usuario = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($usuario['online'] == 1 && vf_online($usuario['id']))
            return true;
        if ($usuario['status'] == 0)
            return false;
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: RETORNA CAMPOS EM ARRAY
// DESCRIÇÃO: RECEBE UM ARRAY E ARRUMA TODOS OS DADOS PARA SEREM PEGOS
function retorna_campos($post) {
    $fields = explode("&", $post);
    foreach ($fields as $field) {
        $field_key_value = explode("=", $field);
        $key = ($field_key_value[0]);
        $value = ($field_key_value[1]);
        if ($value != '')
            $data[$key] = (urldecode($value));
    }
    return $data;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VALIDA CPF
// DESCRIÇÃO: VALIDA O CPF DO USUÁRIO
function valida_cpf($cpfx) {
    $cpf = "";
    $guard = "";

    for ($i = 0; ($i < 14); $i ++) {
        if ($cpfx[$i] != '.' && $cpfx[$i] != '-') {
            $cpf += $cpfx[$i];
            $guard = "$guard$cpfx[$i]";
        }
    }

    $cpf = $guard; // CPF SOMENTE COM OS NÚMEROS
// VERIFICA SE O CPF POSSUÍ NÚMEROS
    if (!is_numeric($cpf)) {
        $status = false;
    } else {
        if (($cpf == '11111111111') || ($cpf == '22222222222') || ($cpf == '33333333333') || ($cpf == '44444444444') || ($cpf == '55555555555') || ($cpf == '66666666666') || ($cpf == '77777777777') || ($cpf == '88888888888') || ($cpf == '99999999999') || ($cpf == '00000000000')) {
            $status = false;
        } else {
// PEGA O DIGITO VERIFIACADOR
            $dv_informado = substr($cpf, 9, 2);

            for ($i = 0; $i <= 8; $i ++) {
                $digito[$i] = substr($cpf, $i, 1);
            }

// CALCULA O VALOR DO 10º DIGITO DE VERIFICAÇÂO
            $posicao = 10;
            $soma = 0;

            for ($i = 0; $i <= 8; $i ++) {
                $soma = $soma + $digito[$i] * $posicao;
                $posicao = $posicao - 1;
            }

            $digito[9] = $soma % 11;

            if ($digito[9] < 2) {
                $digito[9] = 0;
            } else {
                $digito[9] = 11 - $digito[9];
            }

// CALCULA O VALOR DO 11º DIGITO DE VERIFICAÇÃO
            $posicao = 11;
            $soma = 0;

            for ($i = 0; $i <= 9; $i ++) {
                $soma = $soma + $digito[$i] * $posicao;
                $posicao = $posicao - 1;
            }

            $digito[10] = $soma % 11;

            if ($digito[10] < 2) {
                $digito[10] = 0;
            } else {
                $digito[10] = 11 - $digito[10];
            }

// VERIFICA SE O DV CALCULADO É IGUAL AO INFORMADO
            $dv = $digito[9] * 10 + $digito[10];
            if ($dv != $dv_informado) {
                $status = false;
            } else
                $status = true;
        }
    }
    return $status;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: PESQUISAR TABELA
// DESCRIÇÃO: PESQUISAR NO BANCO DE DADOS POR ALGUMA INFORMAÇÃO
function pesquisar_tabela($retorno, $tabela, $campo, $cond, $variavel, $add) {
    $db = Conexao::getInstance();

    $rs = $db->prepare("SELECT $retorno FROM $tabela WHERE $campo $cond ? $add");
    $rs->bindValue(1, $variavel);
    $rs->execute();
    $dados = $rs->fetch(PDO::FETCH_ASSOC);

    return $dados[$retorno];
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICA E-MAIL AC
// DESCRIÇÃO: VERIFICA SE O E-MAIL PASSADO É DO AC.GOV.BR
function vf_email_ac($email) {
    $emailDigitado = "";
    $vf = false;
    $emailvf = false;

    for ($k = 0; ($k < strlen($email)); $k ++) {
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

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: PAÍS DO MUNICÍPIO
// DESCRIÇÃO: RETORNA O PAÍS DE UM MUNICÍPIO PELO ID
function pais_do_municipio($municipio) {
    $estado_id = estado_do_municipio($municipio);

    if (is_numeric($estado_id)) {
        $con = Conexao::getInstance();

        $rs = $con->prepare("SELECT pais_id FROM bsc_estado WHERE id = ?");
        $rs->bindValue(1, $estado_id);
        $rs->execute();
        $dados = $rs->fetch(PDO::FETCH_ASSOC);

        return $dados['pais_id'];
    } else {
        return "";
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: PROGRAMA DO SUBPROGRAMA
// DESCRIÇÃO: RETORNA O PROGRAMA DE UM SUBPROGRAMA PELO ID
function pegar_programa_id($subprograma) {
    if (is_numeric($subprograma)) {
        $con = Conexao::getInstance();

        $rs = $con->prepare("SELECT hab_programa_id FROM hab_subprograma WHERE id = ?");
        $rs->bindValue(1, $subprograma);
        $rs->execute();
        $dados = $rs->fetch(PDO::FETCH_ASSOC);

        return $dados['hab_programa_id'];
    } else {
        return "";
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: ESTADO DO MUNICÍPIO
// DESCRIÇÃO: RETORNA O ESTADO DE UM MUNICÍPIO PELO ID
function estado_do_municipio($municipio) {
    if (is_numeric($municipio)) {
        $con = Conexao::getInstance();

        $rs = $con->prepare("SELECT estado_id FROM bsc_municipio WHERE id = ?");
        $rs->bindValue(1, $municipio);
        $rs->execute();
        $dados = $rs->fetch(PDO::FETCH_ASSOC);

        return $dados['estado_id'];
    } else {
        return "";
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: NOME DO ESTADO DE UM MUNICÍPIO
// DESCRIÇÃO: RETORNAR O NOME DO ESTADO DE UM MUNICÍPIO
function nome_estado_municipio($municipio) {
    if (is_numeric($municipio)) {
        $con = Conexao::getInstance();

        $rs = $con->prepare("SELECT nome FROM bsc_estado WHERE id = ?");
        $rs->bindValue(1, $municipio);
        $rs->execute();
        $dados = $rs->fetch(PDO::FETCH_ASSOC);

        return $dados['nome'];
    } else {
        return "";
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: NOME DO MUNICÍPIO
// DESCRIÇÃO: RETORNAR O NOME DE UM MUNICÍPIO
function nome_municipio($municipio_id) {
    if (is_numeric($municipio_id)) {
        $con = Conexao::getInstance();

        $rs = $con->prepare("SELECT nome FROM bsc_municipio WHERE id = ?");
        $rs->bindValue(1, $municipio_id);
        $rs->execute();
        $dados = $rs->fetch(PDO::FETCH_ASSOC);

        return $dados['nome'];
    } else {
        return "";
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICAR OBJETOS E AÇÃO MARCADOS
// DESCRIÇÃO: VERIFICAR QUAIS OBJETOS E AÇÕES FORAM MARCADOS NO GRUPO
function grupo_modulo_objeto_acao($id, $grupo) {
    $con = Conexao::getInstance();

    $rs = $con->prepare("SELECT id FROM seg_grupo_modulo_objeto_acao WHERE modulo_objeto_acao_id = ? and grupo_id = ?");
    $rs->bindValue(1, $id);
    $rs->bindValue(2, $grupo);

    $rs->execute();
    $rowCount = $rs->rowCount();

    if ($rowCount > 0)
        return true;
    else
        return false;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICA OS OBJETOS E AÇÕES MARCADOS EM USUÁRIOS
// DESCRIÇÃO: VERIFICAR QUAIS OBJETOS E AÇÕES FORAM MARCADOS NO FORMULÁRIO DE USUÁRIO
function usuario_modulo_objeto_acao($id, $usuario) {
    $con = Conexao::getInstance();

    $rs = $con->prepare("SELECT id FROM seg_usuario_modulo_objeto_acao WHERE modulo_objeto_acao_id = ? and usuario_id = ?");
    $rs->bindValue(1, $id);
    $rs->bindValue(2, $usuario);

    $rs->execute();
    $rowCount = $rs->rowCount();

    if ($rowCount > 0)
        return true;
    else
        return false;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICA QUAIS OBJETOS E AÇÕES FORAM MARCADOS
// DESCRIÇÃO: VERIFICAR QUAIS OBJETOS E AÇÕES FORAM MARCADOS
function check_objeto_acao($modulo, $objeto, $acao) {
    $con = Conexao::getInstance();

    $rs = $con->prepare("SELECT id FROM seg_modulo_objeto_acao WHERE objeto_id = ? AND acao_id = ? AND modulo_id = ?");
    $rs->bindValue(1, $objeto);
    $rs->bindValue(2, $acao);
    $rs->bindValue(3, $modulo);

    $rs->execute();
    $rowCount = $rs->rowCount();
    $dados = $rs->fetch(PDO::FETCH_ASSOC);

    if ($rowCount > 0)
        return $dados['id'];
    else
        return 0;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: DIFERENÇA ENTRE MESES
// DESCRIÇÃO: CALCULA A DIFERENÇA ENTRE MESES ENTRE DUAS DATAS
function diff_data_meses($inicio, $fim) {
    if ($inicio != "00/00/0000 00:00:00" && $fim != "00/00/0000 00:00:00" && $inicio != "0000-00-00 00:00:00" && $fim != "0000-00-00 00:00:00") {
// CONVERTE AS DATAS PARA O FORMATO AMERICANO
        $inicio = explode('/', $inicio);
        $inicio = "{$inicio[2]}-{$inicio[1]}-{$inicio[0]}";

        $fim = explode('/', $fim);
        $fim = "{$fim[2]}-{$fim[1]}-{$fim[0]}";

// AGORA CONVERTEMOS A DATA PARA UM INTEIRO
// QUE REPRESENTA A DATA E É PASSÍVEL DE OPERAÇÕES SIMPLES
// COMO SUBITRAÇÃO E ADIÇÃO
        $inicio = strtotime($inicio);
        $fim = strtotime($fim);

// CALCULA A DIFERENÇA ENTRE AS DATAS
        $intervalo = $fim - $inicio;

        $meses = floor(($intervalo / (30 * 60 * 60 * 24)));

        if ($meses > 1) {
            return "$meses meses";
        } else if ($meses == 1) {
            return "$meses mês";
        } else if ($meses == 0) {
            return "Esse mês";
        } else if ($meses < 0) {
            if ($meses < - 1) {
                return "Atrasado " . abs($meses) . " meses";
            } else {
                return "Atrasado " . abs($meses) . " mês";
            }
        }
    } else {
        return "";
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: DIFERENÇA ENTRE DIAS COM TEXTO
// DESCRIÇÃO: CALCULA A DIFERENÇA ENTRE DIAS ENTRE DUAS DATAS COM TEXTO CONCATENADO
function diff_data_dias($data_inicial, $data_final) {
    if ($data_inicial != "00/00/0000 00:00:00" && $data_final != "00/00/0000 00:00:00" && $data_inicial != "0000-00-00 00:00:00" && $data_final != "0000-00-00 00:00:00") {

        $diferenca = strtotime($data_final) - strtotime($data_inicial);

        $dias = floor($diferenca / (60 * 60 * 24));

        if ($dias > 1) {
            return "$dias Dias";
        } else if ($dias == 1) {
            return "$dias Dia";
        } else if ($dias == 0) {
            return "Hoje";
        } else if ($dias < 0) {
            if ($dias < - 1) {
                return "" . abs($dias) . " Dias";
            } else {
                return "" . abs($dias) . " Dia";
            }
        }
    } else {
        return "";
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: CALCULA DIFERENÇA ENTRE DIAS SEM TEXTO
// DESCRIÇÃO: CALCULA A DIFERENÇA ENTRE DIAS DE DUAS DATAS SEM CONCATENAR TEXTO
function diff_data_dias2($data_inicial, $data_final) {
    if ($data_inicial != "00/00/0000 00:00:00" && $data_final != "00/00/0000 00:00:00" && $data_inicial != "0000-00-00 00:00:00" && $data_final != "0000-00-00 00:00:00") {

        $diferenca = strtotime($data_final) - strtotime($data_inicial);

        $dias = floor($diferenca / (60 * 60 * 24));

        if ($dias > 1) {
            return $dias;
        } else if ($dias == 1) {
            return $dias;
        } else if ($dias == 0) {
            return $dias;
        } else if ($dias < 0) {
            return $dias;
        }
    } else {
        return "";
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: DIFERENÇA ENTRE DIAS COM TEXTO E ACEITANDO NEGATIVOS
// DESCRIÇÃO: CALCULA A DIFERENÇA ENTRE DIAS ENTRE DUAS DATAS COM TEXTO CONCATENADO E ACEITANDO NEGATIVOS
function diff_data_dias3($data_inicial, $data_final) {
    if ($data_inicial != "00/00/0000 00:00:00" && $data_final != "00/00/0000 00:00:00" && $data_inicial != "0000-00-00 00:00:00" && $data_final != "0000-00-00 00:00:00") {

        $diferenca = strtotime($data_final) - strtotime($data_inicial);

        $dias = floor($diferenca / (60 * 60 * 24));

        if ($dias > 1) {
            return "$dias Dias Restantes";
        } else if ($dias == 1) {
            return "$dias Dia Restantes";
        } else if ($dias == 0) {
            return "Até Hoje";
        } else if ($dias < 0) {
            if (abs($dias) > 1) {
                return abs($dias) . " Dias Vencidos";
            } else {
                return abs($dias) . " Dia Vencido";
            }
        }
    } else {
        return "";
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: SEMANA
// DESCRIÇÃO: RETORNA A SEMANA EM TEXTO
function getSemana($dia, $completo = 0) {
    switch ($dia) {
        case 1 :
            $r = 'SEG';
            $comp = 'Segunda-feira';
            break;
        case 2 :
            $r = 'TER';
            $comp = 'Terça-feira';
            break;
        case 3 :
            $r = 'QUA';
            $comp = 'Quarta-feira';
            break;
        case 4 :
            $r = 'QUI';
            $comp = 'Quinta-feira';
            break;
        case 5 :
            $r = 'SEX';
            $comp = 'Sexta-feira';
            break;
        case 6 :
            $r = 'SAB';
            $comp = 'Sábado';
            break;
        case 7 :
            $r = 'DOM';
            $comp = 'Domingo';
            break;
    }
    if ($completo == 1)
        return $comp;
    else
        return $r;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: DATA DE HOJE
// DESCRIÇÃO: RETORNA A DATA DE HOJE
function hoje($data) {
    $dt = explode('/', $data);
    return getSemana(date("N", mktime(0, 0, 0, $dt[1], $dt[0], intval($dt[2]))), 1);
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: DIFERENÇA ENTRE HORAS
// DESCRIÇÃO: RETORNA A DIFERENÇA DE HORAS
function timeDiff($firstTime, $lastTime) {
    $firstTime = strtotime($firstTime);
    $lastTime = strtotime($lastTime);
    $timeDiff = $lastTime - $firstTime;
    return $timeDiff;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: DATA POR EXTENSO
// DESCRIÇÃO: RETORNA UMA DATA POR EXTENSO
function dataExtenso($dt) {
    $da = explode('/', $dt);
    return $da[0] . ' de ' . getMes($da[1]) . ' de ' . $da[2];
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: RETORNA MÊS
// DESCRIÇÃO: RETORNA O MÊS POR EXTENSO
function getMes($m) {
    $mes = "";
    switch ($m) {
        case 1 :
            $mes = "Janeiro";
            break;
        case 2 :
            $mes = "Fevereiro";
            break;
        case 3 :
            $mes = "Março";
            break;
        case 4 :
            $mes = "Abril";
            break;
        case 5 :
            $mes = "Maio";
            break;
        case 6 :
            $mes = "Junho";
            break;
        case 7 :
            $mes = "Julho";
            break;
        case 8 :
            $mes = "Agosto";
            break;
        case 9 :
            $mes = "Setembro";
            break;
        case 10 :
            $mes = "Outubro";
            break;
        case 11 :
            $mes = "Novembro";
            break;
        case 12 :
            $mes = "Dezembro";
            break;
    }
    return $mes;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: MÊS ABREVIADO
// DESCRIÇÃO: RETORNA O MÊS ABREVIADO
function getMes2($m) {
    switch ($m) {
        case 1 :
            $mes = "Jan";
            break;
        case 2 :
            $mes = "Fev";
            break;
        case 3 :
            $mes = "Mar";
            break;
        case 4 :
            $mes = "Abr";
            break;
        case 5 :
            $mes = "Mai";
            break;
        case 6 :
            $mes = "Jun";
            break;
        case 7 :
            $mes = "Jul";
            break;
        case 8 :
            $mes = "Ago";
            break;
        case 9 :
            $mes = "Set";
            break;
        case 10 :
            $mes = "Out";
            break;
        case 11 :
            $mes = "Nov";
            break;
        case 12 :
            $mes = "Dez";
            break;
    }
    return $mes;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: TRANSOFRMAR TEXTO
// DESCRIÇÃO: TRANSFORMAR TEXTO EM MAIÚSCULO, MINÚSCULO, ETC
function ctexto($texto, $frase = 'pal') {
    switch ($frase) {
        case 'fra' : // Apenas a a primeira letra em maiusculo
            $texto = ucfirst(mb_strtolower($texto));
            break;
        case 'min' :
            $texto = mb_strtolower($texto);
            break;
        case 'mai' :
            $texto = colocaAcentoMaiusculo((mb_strtoupper($texto)));
            break;
        case 'pal' : // Todas as palavras com a primeira em maiusculo
            $texto = ucwords(mb_strtolower($texto));
            break;
        case 'pri' : // Todos os primeiros caracteres de cada palavra em maiusuclo, menos as junções
            $texto = titleCase($texto);
            break;
    }
    return $texto;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: STATUS
// DESCRIÇÃO: STATUS ATIVO OU INATIVO
function status($id) {
    if ($id == 0) {
        return 'Inativo';
    } else if ($id == 1) {
        return 'Ativo';
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: ACENTO EM LETRAS MAIÚSCULAS
// DESCRIÇÃO: COLOCA ACENTO EM LETRAS MAIÚSCULAS
function colocaAcentoMaiusculo($texto) {
    $array1 = array(
        "á",
        "à",
        "â",
        "ã",
        "ä",
        "é",
        "è",
        "ê",
        "ë",
        "í",
        "ì",
        "î",
        "ï",
        "ó",
        "ò",
        "ô",
        "õ",
        "ö",
        "ú",
        "ù",
        "û",
        "ü",
        "ç"
    );
    $array2 = array(
        "Á",
        "À",
        "Â",
        "Ã",
        "Ä",
        "É",
        "È",
        "Ê",
        "Ë",
        "Í",
        "Ì",
        "Î",
        "Ï",
        "Ó",
        "Ò",
        "Ô",
        "Õ",
        "Ö",
        "Ú",
        "Ù",
        "Û",
        "Ü",
        "Ç"
    );
    return str_replace($array1, $array2, $texto);
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: RETIRA ACENTOS
// DESCRIÇÃO: RETIRA TODOS OS ACENTOS DE UM TEXTO PASSADO POR PARÂMETRO
function retira_acentos($texto) {
    $array1 = array(
        "á",
        "à",
        "â",
        "ã",
        "ä",
        "é",
        "è",
        "ê",
        "ë",
        "í",
        "ì",
        "î",
        "ï",
        "ó",
        "ò",
        "ô",
        "õ",
        "ö",
        "ú",
        "ù",
        "û",
        "ü",
        "ç",
        "Á",
        "À",
        "Â",
        "Ã",
        "Ä",
        "É",
        "È",
        "Ê",
        "Ë",
        "Í",
        "Ì",
        "Î",
        "Ï",
        "Ó",
        "Ò",
        "Ô",
        "Õ",
        "Ö",
        "Ú",
        "Ù",
        "Û",
        "Ü",
        "Ç"
    );
    $array2 = array(
        "a",
        "a",
        "a",
        "a",
        "a",
        "e",
        "e",
        "e",
        "e",
        "i",
        "i",
        "i",
        "i",
        "o",
        "o",
        "o",
        "o",
        "o",
        "u",
        "u",
        "u",
        "u",
        "c",
        "A",
        "A",
        "A",
        "A",
        "A",
        "E",
        "E",
        "E",
        "E",
        "I",
        "I",
        "I",
        "I",
        "O",
        "O",
        "O",
        "O",
        "O",
        "U",
        "U",
        "U",
        "U",
        "C"
    );
    return str_replace($array1, $array2, $texto);
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: OBTER DATA BR DE UM TIMESTAMP
// DESCRIÇÃO: OBTÉM UMA DATA BR DE UM TIMESTAMP
function obterDataBRTimestamp($data) {
    if ($data != '') {
        $data = substr($data, 0, 10);
        $explodida = explode("-", $data);
        $dataIso = $explodida[2] . "/" . $explodida[1] . "/" . $explodida[0];
        return $dataIso;
    }
    return NULL;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: CONVERTE DATA BR EM TIMESTAMP
// DESCRIÇÃO: CONVERTE UMA DATA BR EM TIMESTAMP
function convertDataBR2ISO($data) {
    if ($data == '')
        return false;
    $explodida = explode("/", $data);
    $dataIso = $explodida[2] . "-" . $explodida[1] . "-" . $explodida[0];
    return $dataIso;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: HORA DE UM TIMESTAMP
// DESCRIÇÃO: OBTÉM A HORA DE UM TIMESTAMP
function obterHoraTimestamp($data) {
    return substr($data, 11, 5);
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: HORA DE UM TIMESTAMP COMPLETA
// DESCRIÇÃO: OBTÉM A HORA DE UM TIMESTAMP COMPLETA COM SEGUNDOS
function obterHoraCompletaTimestamp($data) {
    return substr($data, 11, 8);
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: DIA DE UM TIMESTAMP
// DESCRIÇÃO: OBTÉM O DIA DE UM TIMESTAMP
function obterDiaTimestamp($data) {
    return substr($data, 8, 2);
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: MÊS DE UM TIMESTAMP
// DESCRIÇÃO: OBTÉM O MÊS DE UM TIMESTAMP
function obterMesTimestamp($data) {
    return substr($data, 5, 2);
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: ANO DE UM TIMESTMAP
// DESCRIÇÃO: OBTÉM O ANO DE UM TIMESTAMP
function obterAnoTimestamp($data) {
    return substr($data, 0, 4);
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: DIFERENÇA ENTRE DIAS DE DATAS
// DESCRIÇÃO: CALCULA A DIFERENÇA ENTRE DIAS DE DUAS DATAS
function calculaDiferencaDatas($data_inicial, $data_final) {
// Usa a função criada e pega o timestamp das duas datas:
    $time_inicial = geraTimestamp($data_inicial);
    $time_final = geraTimestamp($data_final);

// Calcula a diferença de segundos entre as duas datas:
    $diferenca = $time_final - $time_inicial; // 19522800 segundos
// Calcula a diferença de dias
    $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias
// Exibe uma mensagem de resultado:
// echo "A diferença entre as datas ".$data_inicial." e ".$data_final." é de <strong>".$dias."</strong> dias";
    return $dias;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: TIPOS DE ARQUIVOS PERMITIDOS
// DESCRIÇÃO: VERIFICA SE O ARQUIVO PASSADO É PERMITIDO NO ARRAY
function uploadArquivoPermitido($arquivo) {
    $tiposPermitidos = array(
        'image/gif',
        'image/jpeg',
        'image/jpg',
        'image/pjpeg',
        'image/png',
        'video/webm',
        'video/mp4',
        'video/ogv',
        'audio/mp3',
        'audio/mp4',
        'audio/mpeg',
        'audio/ogg'
    );
    if (array_search($arquivo, $tiposPermitidos) === false) {
        return false;
    } else {
        return true;
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VALOR EM FLOAT
// DESCRIÇÃO: CONVERTE O VALOR PASSADO EM FLOAT
function valorfloat($num) {
    $num = str_replace(".", "", $num);
    $num = str_replace(",", ".", $num);
    return $num;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VALOR EM REAL
// DESCRIÇÃO: CONVERTE O VALOR EM FORMATO DE DINHEIRO
function fdec($numero, $formato = NULL, $tmp = NULL) {
    switch ($formato) {
        case null :
            if ($numero != 0)
                $numero = number_format($numero, 2, ',', '.');
            else
                $numero = '0,00';
            break;
        case '%' :
            if ($numero > 0)
                $numero = number_format((($numero / $tmp) * 100), 2, ',', '.') . '%';
            else
                $numero = '0%';
            break;
        case '-' :
            $numero = "<font color='red'>" . fdec($numero) . "</font>";
            break;
    }
    return $numero;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICA DUPLICIDADE DE LOGIN
// DESCRIÇÃO: VERIFICA SE O LOGIN JÁ ESTÁ LOGADO MAIS DE UMA VEZ NO SISTEMA
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

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: ENVIAR E-MAIL
// DESCRIÇÃO: PARA ENVIAR E-MAIL
function envia_email($para, $assunto, $mensagem, $emaile, $nome_email) {

// Inicia a classe PHPMailer
    $mail = new PHPMailer();

// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->Host = "mail.ac.gov.br"; // Endereço do servidor SMTP (caso queira utilizar a autenticação, utilize o host smtp.seudomínio.com.br)
    $mail->SMTPAuth = true; // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
    $mail->Username = 'sihab'; // Usuário do servidor SMTP (endereço de email)
    $mail->Password = 'Z62Sw3zu'; // Senha do servidor SMTP (senha do email usado)
// Define o remetente
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->From = $emaile;
    $mail->Sender = $emaile;
    $mail->FromName = $nome_email;
// Adicionando copia da mensagem

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
    if ($mail->Send()) {
        return true;
    } else {
        return false;
    }

// Limpa os destinatários e os anexos
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: PEGA VALOR ARRAY
// DESCRIÇÃO: PEGA SOMENTE OS VALORES PREENCHIDOS DE UM ARRAY E IGNORA OS VAZIOS, ZERADOS E NULOS
function pegar_valor_array($valor_array) {
    $rs = "";

    foreach ($valor_array as $key => $value) {
        if ($value != "" && $value != NULL && $value != "0" && $value != "undefined") {
            $rs = $value;
        }
    }
    return $rs;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: ALGARISMOS ROMANOS
// DESCRIÇÃO: RETORNA O VALOR PASSADO EM ALGARISMO ROMANO
function romano($N) {
    $N1 = $N;
    $Y = "";
    while ($N / 1000 >= 1) {
        $Y .= "M";
        $N = $N - 1000;
    }
    if ($N / 900 >= 1) {
        $Y .= "CM";
        $N = $N - 900;
    }
    if ($N / 500 >= 1) {
        $Y .= "D";
        $N = $N - 500;
    }
    if ($N / 400 >= 1) {
        $Y .= "CD";
        $N = $N - 400;
    }
    while ($N / 100 >= 1) {
        $Y .= "C";
        $N = $N - 100;
    }
    if ($N / 90 >= 1) {
        $Y .= "XC";
        $N = $N - 90;
    }
    if ($N / 50 >= 1) {
        $Y .= "L";
        $N = $N - 50;
    }
    if ($N / 40 >= 1) {
        $Y .= "XL";
        $N = $N - 40;
    }
    while ($N / 10 >= 1) {
        $Y .= "X";
        $N = $N - 10;
    }
    if ($N / 9 >= 1) {
        $Y .= "IX";
        $N = $N - 9;
    }
    if ($N / 5 >= 1) {
        $Y .= "V";
        $N = $N - 5;
    }
    if ($N / 4 >= 1) {
        $Y .= "IV";
        $N = $N - 4;
    }
    while ($N >= 1) {
        $Y .= "I";
        $N = $N - 1;
    }
    return $Y;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// VALOR POR EXTENSO
// DESCRIÇÃO: RETORNA O VALOR PASSADO EM VALOR POR EXTENSO
function valorPorExtenso($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false) {
    $singular = null;
    $plural = null;

    if ($bolExibirMoeda) {
        $singular = array(
            "centavo",
            "real",
            "mil",
            "milhão",
            "bilhão",
            "trilhão",
            "quatrilhão"
        );
        $plural = array(
            "centavos",
            "reais",
            "mil",
            "milhões",
            "bilhões",
            "trilhões",
            "quatrilhões"
        );
    } else {
        $singular = array(
            "",
            "",
            "mil",
            "milhão",
            "bilhão",
            "trilhão",
            "quatrilhão"
        );
        $plural = array(
            "",
            "",
            "mil",
            "milhões",
            "bilhões",
            "trilhões",
            "quatrilhões"
        );
    }

    $c = array(
        "",
        "cem",
        "duzentos",
        "trezentos",
        "quatrocentos",
        "quinhentos",
        "seiscentos",
        "setecentos",
        "oitocentos",
        "novecentos"
    );
    $d = array(
        "",
        "dez",
        "vinte",
        "trinta",
        "quarenta",
        "cinquenta",
        "sessenta",
        "setenta",
        "oitenta",
        "noventa"
    );
    $d10 = array(
        "dez",
        "onze",
        "doze",
        "treze",
        "quatorze",
        "quinze",
        "dezesseis",
        "dezesete",
        "dezoito",
        "dezenove"
    );
    $u = array(
        "",
        "um",
        "dois",
        "três",
        "quatro",
        "cinco",
        "seis",
        "sete",
        "oito",
        "nove"
    );

    if ($bolPalavraFeminina) {

        if ($valor == 1) {
            $u = array(
                "",
                "uma",
                "duas",
                "três",
                "quatro",
                "cinco",
                "seis",
                "sete",
                "oito",
                "nove"
            );
        } else {
            $u = array(
                "",
                "um",
                "duas",
                "três",
                "quatro",
                "cinco",
                "seis",
                "sete",
                "oito",
                "nove"
            );
        }

        $c = array(
            "",
            "cem",
            "duzentas",
            "trezentas",
            "quatrocentas",
            "quinhentas",
            "seiscentas",
            "setecentas",
            "oitocentas",
            "novecentas"
        );
    }

    $z = 0;

    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);

    for ($i = 0; $i < count($inteiro); $i ++) {
        for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii ++) {
            $inteiro[$i] = "0" . $inteiro[$i];
        }
    }

// $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
    $rt = null;
    $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
    for ($i = 0; $i < count($inteiro); $i ++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
        $t = count($inteiro) - 1 - $i;
        $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($valor == "000")
            $z ++;
        elseif ($z > 0)
            $z --;

        if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
            $r .= (($z > 1) ? " de " : "") . $plural[$t];

        if ($r)
            $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    $rt = mb_substr($rt, 1);

    return ($rt ? trim($rt) : "zero");
}

//----------------------------------------------------------------------------------------------------------------------------
?>