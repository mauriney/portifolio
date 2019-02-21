<?php

//------------------------------------------------------------------------------
//DATA: 12/08/2016 às 15:00
//NOME: Cadastro de candidato
//DESCRIÇÃO: Realiza o cadastro de candidatos no banco de dados
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$error = false;
$msg = array();
$mensagem = "";

$db->beginTransaction();

try {

  $candidato_apto = isset($_POST['apto_sorteio']) == 1 ? 1 : 0;

  $candidato_id = $_POST['id'];
  $pessoa_id = pesquisar_tabela("hab_pessoa_id", "hab_candidato", "id", "=", $candidato_id, "");
  $nome = $_POST['nome'];
  $cpf = $_POST['cpf'];
  $subprograma = isset($_POST['subprograma']) == "" ? NULL : $_POST['subprograma'];
  $cor = $_POST['cor'];
  $data_nascimento = convertDataBR2ISO($_POST['data_nascimento']);
  $sexo = $_POST['sexo'];
  $documento1 = $_POST['documento1'];
  $retroativo_sim = $_POST['retroativo'];
  $data_cadastro_anterior = $_POST['data_cadastro_anterior'];

  $cep = $_POST['cep'];
  $municipio = isset($_POST['municipio']) == "" ? NULL : $_POST['municipio'];
  $logradouro = $_POST['logradouro'];
  $numero = $_POST['numero'];
  $bairro = $_POST['bairro'];
  $quadra = $_POST['quadra'];
  $casa = $_POST['casa'];
  $complemento = $_POST['complemento'];
  $data_inicio_residencia = $_POST['data_inicio_residencia'];
  $tipo_endereco = $_POST['tipo_endereco'];
  $tipo_logradouro = $_POST['tipo_logradouro'];
  $latitude = $_POST['lat'];
  $longitude = $_POST['lng'];

  $beneficio = $_POST['beneficio'];
  $bolsa_familia = isset($_POST['bolsa_familia']) && $beneficio == 1 ? $_POST['bolsa_familia'] : 0;
  $bpc = isset($_POST['bpc']) && $beneficio == 1 ? $_POST['bpc'] : 0;

  //ALUGADO
  $alugada = $_POST['alugada'];
  $valor_aluguel = $_POST['valor_aluguel'];
  $aluguel_social = $_POST['aluguel_social'];
  $coabitacao_involuntaria = $_POST['coabitacao_involuntaria'];
  $area_risco_insalubre = $_POST['area_risco_insalubre'];

  $morador_rua = $_POST['morador_rua'];

  $contato_email = $_POST['contato_email'];
  $residencial = $_POST['residencial'];
  $celular = $_POST['celular'];
  $comercial = $_POST['comercial'];
  $ramal = $_POST['ramal'];

  $contato_nome = $_POST['contato_nome'];
  $contato_telefone = $_POST['contato_telefone'];

  //TRABALHO
  $trabalha = $_POST['trabalha'];
  $local_trabalho = $_POST['local_trabalho'];
  $trab_endereco = $_POST['trab_endereco'];
  $cargo_funcao = $_POST['cargo_funcao'];
  $data_inicio = $_POST['data_inicio'];
  $provedor_lar = $_POST['provedor'];
  $trab_mesmo_endereco = $_POST['trab_mesmo_endereco'];

  $pereferencia_empreendimento = $_POST['pereferencia_empreendimento'];
  $empreendimento = $_POST['empreendimento'];
  $loteamento_id = $_POST['loteamento_id'];
  $participar = $_POST['participar_programa'];
  $participar_id = $_POST['participar_id'];

  $grau_escolar_id = $_POST['grau_escolar'];
  $estado_civil = $_POST['estado_civil'];
  $uniao_estavel = isset($_POST['uniao_estavel']) == 1 ? 1 : 0;
  $deficiencia = $_POST['deficiencia'];
  $tipo_deficiencia = $_POST['tipo_deficiencia'];
  $doenca_cronica = $_POST['doenca_cronica'];

  $cad_unico = $_POST['cad_unico'];
  $nis = $_POST['nis'];

  $tipo_renda = $_POST['tipo_renda'];
  $renda_valor = $_POST['renda_valor'];

  //INFORMAÇÕES DA MÃE DO TITULAR
  $mae_nome = $_POST['mae_nome'];
  $mae_data_nascimento = $_POST['mae_nascimento'];
  $mae_contato = $_POST['mae_contato'];

  $lei_maria_penha = $_POST['lei_maria_penha'];
  $casamento_data = $_POST['casamento_data'];

  //CID10
  $capitulo = $_POST['capitulo'];
  $grupo = isset($_POST['grupo']) == "" ? NULL : $_POST['grupo'];
  $categoria = isset($_POST['categoria']) == "" ? NULL : $_POST['categoria'];

  //SE ESTUDA
  $estudando = $_POST['estudando'];
  $financia = $_POST['financia'];

  if ($estudando == 1) {
    $nome_instituicao = $_POST['nome_instituicao'];
    $serie_periodo = $_POST['serie_periodo'];
    $rede_publica_privada = $_POST['rede_publica_privada'];

    if ($financia == 2) {
      $programa = $_POST['programa_social'];
      $porcentagem = $_POST['porcentagem'];
    } else {
      $programa = NULL;
      $porcentagem = NULL;
    }
  } else {
    $nome_instituicao = NULL;
    $serie_periodo = NULL;
    $rede_publica_privada = NULL;
    $programa = NULL;
    $porcentagem = NULL;
  }

  //SE VINCULO
  $vinculo = $_POST['vinculo'];

  if ($vinculo == 1) {
    $vinculo_id = $_POST['vinculo_id'];
    $vinculo_nome = $_POST['vinculo_nome'];
    $vinculo_cpf = $_POST['vinculo_cpf'];
    $vinculo_contato = $_POST['vinculo_contato'];
  } else {
    $vinculo_id = 0;
    $vinculo_nome = NULL;
    $vinculo_cpf = NULL;
    $vinculo_contato = NULL;
  }

  $nacionalidade = $_POST['nacionalidade'];

  if ($nacionalidade == 1) {
    $naturalidade_municipio = isset($_POST['naturalidade_municipio']) == "" ? NULL : $_POST['naturalidade_municipio'];
    $pais = 30; // 30 = BRASILEIRO
    $cod_rne = NULL;
    $classificacao = NULL;
    $data_expedicao_1 = NULL;
    $validade = NULL;
  } else {
    $naturalidade_municipio = NULL;
    $pais = $_POST['pais'] == NULL ? 30 : $_POST['pais']; // 30 = BRASILEIRO
    $cod_rne = $_POST['cod_rne'] == NULL ? NULL : $_POST['cod_rne'];
    $classificacao = $_POST['classificacao'] == NULL ? NULL : $_POST['classificacao'];
    $data_expedicao_1 = $_POST['data_expedicao_1'] == NULL ? NULL : $_POST['data_expedicao_1'];
    $validade = $_POST['validade'] == NULL ? NULL : $_POST['validade'];
  }

  if ($documento1 == 1) {//RG
    $numero_registro = $_POST['numero_registro'] == "" ? NULL : $_POST['numero_registro'];
    $orgao_expedidor = $_POST['orgao_expedidor'] == "" ? NULL : $_POST['orgao_expedidor'];
    $uf_expedicao = $_POST['uf_expedicao'];
    $data_expedicao = $_POST['data_expedicao'] == "" ? NULL : convertDataBR2ISO($_POST['data_expedicao']);
    $numero_registro_2 = NULL;
    $uf_expedicao_2 = NULL;
    $data_expedicao_2 = NULL;
    $data_validade_2 = NULL;
  } else {//CNH
    $numero_registro = NULL;
    $orgao_expedidor = NULL;
    $uf_expedicao = NULL;
    $data_expedicao = NULL;
    $numero_registro_2 = $_POST['numero_registro_2'];
    $uf_expedicao_2 = $_POST['uf_expedicao_2'];
    $data_expedicao_2 = convertDataBR2ISO($_POST['data_expedicao_2']);
    $data_validade_2 = convertDataBR2ISO($_POST['data_validade_2']);
  }

  if ($retroativo_sim == 0) {

    $cpf_pessoa_cpf = pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $cpf, "");

    if (is_numeric($cpf_pessoa_cpf) && $cpf_pessoa_cpf != $pessoa_id) {
      $error = true;
      $mensagem .= "O CPF do candidato informado já existe no sistema.";
      $msg['tipo'] = "cpf";
    } else {
      if (@!valida_cpf($cpf)) {
        $error = true;
        $mensagem .= "O CPF do candidato informado é inválido.";
        $msg['tipo'] = "cpf";
      }
    }
  }

  if ($vinculo == 1) {

    $cpf_vinculo_cpf = pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $vinculo_cpf, "");

    if (is_numeric($cpf_vinculo_cpf) && $vinculo_id != $cpf_vinculo_cpf) {

      if (is_numeric(pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $vinculo_cpf, "AND id IN (SELECT hab_pessoa_id_conjuge FROM hab_conjuge WHERE hab_pessoa_id_conjuge <> '$vinculo_id')"))) {
        $error = true;
        $mensagem .= "O CPF do outro vínculo informado já existe no sistema com cônjuge.";
        $msg['tipo'] = "cpf";
      } else if (is_numeric(pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $vinculo_cpf, "AND id IN (SELECT hab_pessoa_id FROM hab_familiar)"))) {
        $error = true;
        $mensagem .= "O CPF do outro vínculo informado já existe no sistema como membro de um grupo familiar.";
        $msg['tipo'] = "cpf";
      } else if (is_numeric(pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $vinculo_cpf, "AND id IN (SELECT hab_pessoa_id FROM hab_candidato)"))) {
        $error = true;
        $mensagem .= "O CPF do outro vínculo informado já existe no sistema como candidato.";
        $msg['tipo'] = "cpf";
      }
    } else if (@!valida_cpf($vinculo_cpf)) {
      $error = true;
      $mensagem .= "O CPF do outro vínculo informado é inválido.";
      $msg['tipo'] = "cpf";
    }
  }

  if ($retroativo_sim == 0) {
    if (calcular_idade($data_nascimento) < 18) {//SE A IDADE DO CANDIDATO FOR MENOR QUE 18
      $error = true;
      $mensagem .= "O candidato não pode ser menor de idade.";
      $msg['tipo'] = "idade";
    }
  }

  if ($error == false) {

    if (isset($_POST['id']) && $_POST['id'] != 0) {
//------------------------------------------------ATUALIZAÇÃO------------------------------------------------
      //ATUALIZAÇÃO DE PESSOA
      $stmt = $db->prepare("UPDATE hab_pessoa SET bsc_cie_classificacao_id = ?, cie_rne = ?, cie_data_validade = ?, cie_data_expedicao = ?, bsc_municipio_id_natural = ?, bsc_nacionalidade_id = ?, nome = ?, cpf = ?, bsc_pele_cor_id = ?, data_nascimento = ?, rg_numero = ?, rg_uf_expedicao = ?, rg_data_expedicao = ?, rg_orgao_expedicao_id = ?, cnh_numero = ?, cnh_uf_expedicao = ?, cnh_data_validade = ?, cnh_data_expedicao = ?, bsc_sexo_id = ?, email = ?, uniao_estavel = ?, deficiencia = ?, doenca_cronica = ?, hab_grau_escolar_id = ?, cadastro_unico = ?, nis = ?, hab_cid10_capitulo_id = ?, hab_cid10_grupo_id = ?, hab_cid10_categoria_id = ?, bsc_estado_civil_id = ?, mae_nome = ?, mae_data_nascimento = ?, data_inicio_residencia_municipio = ?, lei_maria_penha = ?, casamento_data = ?, bsc_deficiencia_tipo_id = ?, cadastro_retroativo_ano = ?, provedor_lar = ?, trab_mesmo_endereco = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE id = ?");
      $stmt->bindValue(1, $classificacao);
      $stmt->bindValue(2, $cod_rne);
      $stmt->bindValue(3, convertDataBR2ISO($validade));
      $stmt->bindValue(4, convertDataBR2ISO($data_expedicao_1));
      $stmt->bindValue(5, $naturalidade_municipio == "" ? NULL : $naturalidade_municipio);
      $stmt->bindValue(6, $pais);
      $stmt->bindValue(7, $nome);
      $stmt->bindValue(8, $cpf);
      $stmt->bindValue(9, $cor == "" ? NULL : $cor);
      $stmt->bindValue(10, $data_nascimento);
      $stmt->bindValue(11, $numero_registro);
      $stmt->bindValue(12, $uf_expedicao);
      $stmt->bindValue(13, $data_expedicao);
      $stmt->bindValue(14, $orgao_expedidor);
      $stmt->bindValue(15, $numero_registro_2);
      $stmt->bindValue(16, $uf_expedicao_2);
      $stmt->bindValue(17, $data_validade_2);
      $stmt->bindValue(18, $data_expedicao_2);
      $stmt->bindValue(19, $sexo);
      $stmt->bindValue(20, $contato_email);
      $stmt->bindValue(21, $uniao_estavel);
      $stmt->bindValue(22, $deficiencia);
      $stmt->bindValue(23, $doenca_cronica);
      $stmt->bindValue(24, $grau_escolar_id == "" ? NULL : $grau_escolar_id);
      $stmt->bindValue(25, $cad_unico);
      $stmt->bindValue(26, $nis);
      $stmt->bindValue(27, $capitulo == "" ? NULL : $capitulo);
      $stmt->bindValue(28, $grupo == "" ? NULL : $grupo);
      $stmt->bindValue(29, $categoria == "" ? NULL : $categoria);
      $stmt->bindValue(30, $estado_civil == "" ? NULL : $estado_civil);
      $stmt->bindValue(31, $mae_nome);
      $stmt->bindValue(32, convertDataBR2ISO($mae_data_nascimento));
      $stmt->bindValue(33, convertDataBR2ISO($data_inicio_residencia));
      $stmt->bindValue(34, $lei_maria_penha);
      $stmt->bindValue(35, $estado_civil == 6 || $estado_civil == 7 || $estado_civil == 8 ? convertDataBR2ISO($casamento_data) : NULL);
      $stmt->bindValue(36, $deficiencia == 1 ? $tipo_deficiencia : NULL);
      $stmt->bindValue(37, $retroativo_sim == 1 ? obterAnoTimestamp(convertDataBR2ISO($data_cadastro_anterior)) : NULL);
      $stmt->bindValue(38, $provedor_lar);
      $stmt->bindValue(39, $trab_mesmo_endereco);
      $stmt->bindValue(40, $_SESSION['id']);
      $stmt->bindValue(41, $pessoa_id);
      $stmt->execute();

      //SALVANDO BENEFÍCIO SOCIAL
      if ($bolsa_familia == 3) {
        $stmt = $db->prepare("INSERT INTO hab_pessoa_beneficio (hab_pessoa_id, hab_beneficio_social_id, data_cadastro, seg_usuario_pai_id, status) VALUES (?, ?, NOW(), ?, 1)");
        $stmt->bindValue(1, $pessoa_id);
        $stmt->bindValue(2, 3);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      } else {
        $stmt = $db->prepare("DELETE FROM hab_pessoa_beneficio WHERE hab_pessoa_id = ? AND hab_beneficio_social_id = ?");
        $stmt->bindValue(1, $pessoa_id);
        $stmt->bindValue(2, 3);
        $stmt->execute();
      }

      if ($bpc == 2) {
        $stmt = $db->prepare("INSERT INTO hab_pessoa_beneficio (hab_pessoa_id, hab_beneficio_social_id, data_cadastro, seg_usuario_pai_id, status) VALUES (?, ?, NOW(), ?, 1)");
        $stmt->bindValue(1, $pessoa_id);
        $stmt->bindValue(2, 2);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      } else {
        $stmt = $db->prepare("DELETE FROM hab_pessoa_beneficio WHERE hab_pessoa_id = ? AND hab_beneficio_social_id = ?");
        $stmt->bindValue(1, $pessoa_id);
        $stmt->bindValue(2, 2);
        $stmt->execute();
      }

      if (is_numeric(pesquisar_tabela("id", "hab_pessoa_escolar", "hab_pessoa_id", "=", $pessoa_id, ""))) {
        //ATUALIZAR DADOS ESCOLARES
        $stmt = $db->prepare("UPDATE hab_pessoa_escolar SET instituicao_nome = ?, serie_periodo = ?, hab_instituicao_natureza_id = ?, hab_financia_natureza_id = ?, hab_programa_social_id = ?, bolsa_percentual = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE hab_pessoa_id = ?");
        $stmt->bindValue(1, $nome_instituicao);
        $stmt->bindValue(2, $serie_periodo);
        $stmt->bindValue(3, $rede_publica_privada == "" ? NULL : $rede_publica_privada);
        $stmt->bindValue(4, $financia);
        $stmt->bindValue(5, $programa == "" ? NULL : $programa);
        $stmt->bindValue(6, $porcentagem);
        $stmt->bindValue(7, $_SESSION['id']);
        $stmt->bindValue(8, $pessoa_id);
        $stmt->execute();
      } else {
        //CADASTRAR DADOS ESCOLARES
        $stmt = $db->prepare("INSERT INTO hab_pessoa_escolar (instituicao_nome, serie_periodo, hab_instituicao_natureza_id, hab_financia_natureza_id, hab_programa_social_id, bolsa_percentual, status, data_cadastro, hab_pessoa_id, seg_usuario_pai_id) VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), ?, ?)");
        $stmt->bindValue(1, $nome_instituicao);
        $stmt->bindValue(2, $serie_periodo);
        $stmt->bindValue(3, $rede_publica_privada == "" ? NULL : $rede_publica_privada);
        $stmt->bindValue(4, $financia);
        $stmt->bindValue(5, $programa == "" ? NULL : $programa);
        $stmt->bindValue(6, $porcentagem);
        $stmt->bindValue(7, $pessoa_id);
        $stmt->bindValue(8, $_SESSION['id']);
        $stmt->execute();
      }

      if (is_numeric(pesquisar_tabela("id", "hab_pessoa_endereco", "hab_pessoa_id", "=", $pessoa_id, ""))) {
        //ATUALIZAÇÃO DE PESSOA ENDEREÇO
        $stmt = $db->prepare("UPDATE hab_pessoa_endereco SET bsc_municipio_id = ?, logradouro = ?, numero = ?, bairro = ?, quadra = ?, lote = ?, complemento = ?, cep = ?, bsc_endereco_tipo_id = ?, bsc_logradouro_id = ?, alugada = ?, aluguel_valor = ?, aluguel_social = ?, coabitacao_involuntaria = ?, latitude = ?, longitude = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE hab_pessoa_id = ?");
        $stmt->bindValue(1, $municipio == "" ? NULL : $municipio);
        $stmt->bindValue(2, $logradouro);
        $stmt->bindValue(3, $numero);
        $stmt->bindValue(4, $bairro);
        $stmt->bindValue(5, $quadra);
        $stmt->bindValue(6, $casa);
        $stmt->bindValue(7, $complemento);
        $stmt->bindValue(8, $cep);
        $stmt->bindValue(9, $tipo_endereco == "" ? NULL : $tipo_endereco);
        $stmt->bindValue(10, $tipo_logradouro == "" ? NULL : $tipo_logradouro);
        $stmt->bindValue(11, $alugada);
        $stmt->bindValue(12, $alugada == 1 ? valorfloat($valor_aluguel) : NULL);
        $stmt->bindValue(13, $alugada == 1 ? $aluguel_social : 0);
        $stmt->bindValue(14, $coabitacao_involuntaria == 1 ? $coabitacao_involuntaria : 0);
        $stmt->bindValue(15, $latitude);
        $stmt->bindValue(16, $longitude);
        $stmt->bindValue(17, $_SESSION['id']);
        $stmt->bindValue(18, $pessoa_id);
        $stmt->execute();
      } else {
        //CADASTRO DE ENDEREÇO DA PESSOA
        $stmt = $db->prepare("INSERT INTO hab_pessoa_endereco (bsc_municipio_id, logradouro, numero, bairro, quadra, lote, complemento, cep, bsc_endereco_tipo_id, bsc_logradouro_id, alugada, aluguel_valor, aluguel_social, coabitacao_involuntaria, latitude, longitude, status, data_cadastro, hab_pessoa_id, seg_usuario_pai_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), ?, ?)");
        $stmt->bindValue(1, $municipio == "" ? NULL : $municipio);
        $stmt->bindValue(2, $logradouro);
        $stmt->bindValue(3, $numero);
        $stmt->bindValue(4, $bairro);
        $stmt->bindValue(5, $quadra);
        $stmt->bindValue(6, $casa);
        $stmt->bindValue(7, $complemento);
        $stmt->bindValue(8, $cep);
        $stmt->bindValue(9, $tipo_endereco == "" ? NULL : $tipo_endereco);
        $stmt->bindValue(10, $tipo_logradouro == "" ? NULL : $tipo_logradouro);
        $stmt->bindValue(11, $alugada);
        $stmt->bindValue(12, $alugada == 1 ? valorfloat($valor_aluguel) : NULL);
        $stmt->bindValue(13, $alugada == 1 ? $aluguel_social : 0);
        $stmt->bindValue(14, $coabitacao_involuntaria == 1 ? $coabitacao_involuntaria : 0);
        $stmt->bindValue(15, $latitude);
        $stmt->bindValue(16, $longitude);
        $stmt->bindValue(17, $pessoa_id);
        $stmt->bindValue(18, $_SESSION['id']);
        $stmt->execute();
      }

      //TIPO RESIDENCIAL
      if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, " AND hab_contato_tipo_id = 1"))) {
        $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_contato_tipo_id = 1 AND hab_pessoa_id = ?");
        $stmt->bindValue(1, $residencial);
        $stmt->bindValue(2, $_SESSION['id']);
        $stmt->bindValue(3, $pessoa_id);
        $stmt->execute();
      } else {
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (1, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $residencial);
        $stmt->bindValue(2, $pessoa_id);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      }

      //TIPO CELULAR
      if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, " AND hab_contato_tipo_id = 2"))) {
        $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_contato_tipo_id = 2 AND hab_pessoa_id = ?");
        $stmt->bindValue(1, $celular);
        $stmt->bindValue(2, $_SESSION['id']);
        $stmt->bindValue(3, $pessoa_id);
        $stmt->execute();
      } else {
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (2, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $celular);
        $stmt->bindValue(2, $pessoa_id);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      }

      //TIPO COMERCIAL
      if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, " AND hab_contato_tipo_id = 3"))) {
        $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, ramal = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_contato_tipo_id = 3 AND hab_pessoa_id = ?");
        $stmt->bindValue(1, $comercial);
        $stmt->bindValue(2, $ramal);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->bindValue(4, $pessoa_id);
        $stmt->execute();
      } else {
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, ramal, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (3, ?, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $comercial);
        $stmt->bindValue(2, $ramal);
        $stmt->bindValue(3, $pessoa_id);
        $stmt->bindValue(4, $_SESSION['id']);
        $stmt->execute();
      }

      //TIPO RECADO
      if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, " AND hab_contato_tipo_id = 4"))) {
        $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, recado_nome = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_contato_tipo_id = 4 AND hab_pessoa_id = ?");
        $stmt->bindValue(1, $contato_telefone);
        $stmt->bindValue(2, $contato_nome);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->bindValue(4, $pessoa_id);
        $stmt->execute();
      } else {
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, recado_nome, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (4, ?, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $contato_telefone);
        $stmt->bindValue(2, $contato_nome);
        $stmt->bindValue(3, $pessoa_id);
        $stmt->bindValue(4, $_SESSION['id']);
        $stmt->execute();
      }

      //TIPO MÃE
      if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, " AND hab_contato_tipo_id = 5"))) {
        $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_contato_tipo_id = 5 AND hab_pessoa_id = ?");
        $stmt->bindValue(1, $mae_contato);
        $stmt->bindValue(2, $_SESSION['id']);
        $stmt->bindValue(3, $pessoa_id);
        $stmt->execute();
      } else {
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (5, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $mae_contato);
        $stmt->bindValue(2, $pessoa_id);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      }

      //REMOVER OCUPAÇÃO ANTERIOR
      $stmt = $db->prepare("DELETE FROM hab_ocupacao WHERE hab_pessoa_id = ?");
      $stmt->bindValue(1, $pessoa_id);
      $stmt->execute();

      if ($trabalha == 1) {
        //CADASTRO DA OCUPAÇÃO DA PESSOA
        $stmt = $db->prepare("INSERT INTO hab_ocupacao (instituicao, endereco, cargo, data_inicio, seg_usuario_pai_id, hab_pessoa_id, status, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())");
        $stmt->bindValue(1, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $local_trabalho);
        $stmt->bindValue(2, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $trab_endereco);
        $stmt->bindValue(3, $trabalha == 1 ? $cargo_funcao : NULL);
        $stmt->bindValue(4, $trabalha == 1 ? convertDataBR2ISO($data_inicio) : NULL);
        $stmt->bindValue(5, $_SESSION['id']);
        $stmt->bindValue(6, $pessoa_id);
        $stmt->execute();
      }

      //ATUALIZAÇÃO DE CANDIDATO
      $stmt = $db->prepare("UPDATE hab_candidato SET situacao = 1, area_risco_insalubre = ?, morador_rua = ?, data_cadastro_anterior = ?, snch_apf_id = ?, loteamento_id = ?, participar_programa_id = ?, data_update = NOW() WHERE hab_pessoa_id = ?");
      $stmt->bindValue(1, $area_risco_insalubre);
      $stmt->bindValue(2, $morador_rua);
      $stmt->bindValue(3, $retroativo_sim == 1 ? convertDataBR2ISO($data_cadastro_anterior) : NULL);
      $stmt->bindValue(4, $pereferencia_empreendimento == 1 ? $empreendimento : NULL);
      $stmt->bindValue(5, $empreendimento > 1 ? $loteamento_id : NULL);
      $stmt->bindValue(6, $participar == 1 ? $participar_id : NULL);
      $stmt->bindValue(7, $pessoa_id);
      $stmt->execute();

      $hab_candidato_id = pesquisar_tabela("id", "hab_candidato", "hab_pessoa_id", "=", $pessoa_id, "");

      $stmt = $db->prepare("INSERT INTO hab_candidato_situacao (hab_candidato_id, seg_usuario_pai_id, hab_tipo_situacao_id, data_cadastro, status, data_update) VALUES (?, ?, 1, NOW(), 1, NOW())");
      $stmt->bindValue(1, $hab_candidato_id);
      $stmt->bindValue(2, $_SESSION['id']);
      $stmt->execute();

      if ($subprograma != "") {
        //ATUALIZAÇÃO DE CANDIDATO PROGRAMA
        $stmt = $db->prepare("UPDATE hab_candidato_programa SET hab_subprograma_id = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_candidato_id = ?");
        $stmt->bindValue(1, $subprograma);
        $stmt->bindValue(2, $_SESSION['id']);
        $stmt->bindValue(3, $hab_candidato_id);
        $stmt->execute();
      }

      $conjuge_id = pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 2");

      $pessoa2_id = pesquisar_tabela("hab_pessoa_id_conjuge", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 2");

      //ATUALIZAÇÃO DE CÔNJUGE
      $stmt = $db->prepare("UPDATE hab_conjuge SET data_update = NOW() WHERE id = ?");
      $stmt->bindValue(1, $conjuge_id);
      $stmt->execute();

      $hab_conjuge_id = $conjuge_id;

      if ($vinculo == 1) {

        if (is_numeric($pessoa2_id)) {
          //ATUALIZAÇÃO DE OUTRO CÔNJUGE JUDICIAL
          $stmt = $db->prepare("UPDATE hab_pessoa SET nome = ?, cpf = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE id IN (SELECT hab_pessoa_id_conjuge FROM hab_conjuge WHERE hab_candidato_id = ? AND tipo = 2)");
          $stmt->bindValue(1, $vinculo_nome);
          $stmt->bindValue(2, $vinculo_cpf);
          $stmt->bindValue(3, $_SESSION['id']);
          $stmt->bindValue(4, $candidato_id);
          $stmt->execute();

          $stmt = $db->prepare("UPDATE hab_conjuge SET data_update = NOW() WHERE id = ?");
          $stmt->bindValue(1, $conjuge_id);
          $stmt->execute();
        } else {
          //CADASTRO DE OUTRO CÔNJUGE JUDICIAL
          $stmt = $db->prepare("INSERT INTO hab_pessoa (nome, cpf, data_cadastro, seg_usuario_pai_id) VALUES (?, ?, NOW(), ?)");
          $stmt->bindValue(1, $vinculo_nome);
          $stmt->bindValue(2, $vinculo_cpf);
          $stmt->bindValue(3, $_SESSION['id']);
          $stmt->execute();

          $pessoa2_id = $db->lastInsertId();

          $stmt = $db->prepare("INSERT INTO hab_conjuge (hab_pessoa_id_conjuge, hab_candidato_id, data_cadastro, status, seg_usuario_pai_id, tipo) VALUES (?, ?, NOW(), 1, ?, 2)");
          $stmt->bindValue(1, $pessoa2_id);
          $stmt->bindValue(2, $candidato_id);
          $stmt->bindValue(3, $_SESSION['id']);
          $stmt->execute();
        }

        if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa2_id, "AND hab_contato_tipo_id = 2"))) {
          //ATUALIZAR TIPO CELULAR
          $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_pessoa_id = ? AND hab_contato_tipo_id = 2");
          $stmt->bindValue(1, $vinculo_contato);
          $stmt->bindValue(2, $_SESSION['id']);
          $stmt->bindValue(3, $pessoa2_id);
          $stmt->execute();
        } else {
          //CADASTRAR TIPO CELULAR
          $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (2, ?, ?, NOW(), 1, ?)");
          $stmt->bindValue(1, $vinculo_contato);
          $stmt->bindValue(2, $pessoa2_id);
          $stmt->bindValue(3, $_SESSION['id']);
          $stmt->execute();
        }
      } else {
        //REMOVENDO DADOS CONTATO
        $stmt = $db->prepare("DELETE FROM hab_pessoa_contato WHERE hab_pessoa_id = ?");
        $stmt->bindValue(1, $pessoa2_id);
        $stmt->execute();

        //REMOVENDO DADOS CONJUGE
        $stmt = $db->prepare("DELETE FROM hab_conjuge WHERE hab_pessoa_id_conjuge = ?");
        $stmt->bindValue(1, $pessoa2_id);
        $stmt->execute();

        //REMOVENDO DADOS DA PESSOA
        $stmt = $db->prepare("DELETE FROM hab_pessoa WHERE id = ?");
        $stmt->bindValue(1, $pessoa2_id);
        $stmt->execute();
      }

      //RENDA
      $stmt = $db->prepare("DELETE FROM hab_pessoa_renda WHERE hab_pessoa_id = ?");
      $stmt->bindValue(1, $pessoa_id);
      $stmt->execute();

      foreach ($tipo_renda AS $key => $val) {
        if ($val != "" && $renda_valor[$key] != "" && $val != 0) {
          $stmt = $db->prepare("INSERT INTO hab_pessoa_renda (valor, hab_pessoa_id, hab_renda_tipo_id, data_cadastro, seg_usuario_pai_id, status) VALUES (?, ?, ?, NOW(), ?, 1)");
          $stmt->bindValue(1, valorfloat($renda_valor[$key]));
          $stmt->bindValue(2, $pessoa_id);
          $stmt->bindValue(3, $val);
          $stmt->bindValue(4, $_SESSION['id']);
          $stmt->execute();
        }
      }

      $stmt = $db->prepare("SELECT senha_visualiza FROM hab_candidato WHERE hab_pessoa_id = ?");
      $stmt->bindValue(1, $pessoa_id);
      $stmt->execute();
      $senhaVisualiza = $stmt->fetch(PDO::FETCH_ASSOC)['senha_visualiza'];
      if ($senhaVisualiza == '') {

        $stmt = $db->prepare("UPDATE hab_candidato SET senha_visualiza = ? WHERE hab_pessoa_id = ?");
        $stmt->bindValue(1, gera_senha(4));
        $stmt->bindValue(2, $pessoa_id);
        $stmt->execute();
      }


      $msg['retorno'] = 'Primeira etapa do cadastro atualizado com sucesso!';
    } else {
//------------------------------------------------CADASTRO------------------------------------------------
      //CADASTRO DE PESSOA RETROATIVA
      $stmt = $db->prepare("INSERT INTO hab_pessoa (bsc_cie_classificacao_id, cie_rne, cie_data_validade, cie_data_expedicao, bsc_municipio_id_natural, bsc_nacionalidade_id,
                 nome, cpf, bsc_pele_cor_id, data_nascimento, rg_numero, rg_uf_expedicao, rg_data_expedicao, rg_orgao_expedicao_id, cnh_numero, cnh_uf_expedicao, cnh_data_validade,
                 cnh_data_expedicao, bsc_sexo_id, email, uniao_estavel, deficiencia, doenca_cronica, hab_grau_escolar_id, cadastro_unico, nis, hab_cid10_capitulo_id,
                 hab_cid10_grupo_id, hab_cid10_categoria_id, bsc_estado_civil_id, mae_nome, mae_data_nascimento, data_inicio_residencia_municipio, lei_maria_penha, 
                 casamento_data, bsc_deficiencia_tipo_id, data_cadastro, status, cadastro_retroativo_ano, provedor_lar, trab_mesmo_endereco, seg_usuario_pai_id)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 1, ?, ?, ?, ?)");
      $stmt->bindValue(1, $classificacao);
      $stmt->bindValue(2, $cod_rne);
      $stmt->bindValue(3, convertDataBR2ISO($validade));
      $stmt->bindValue(4, convertDataBR2ISO($data_expedicao_1));
      $stmt->bindValue(5, $naturalidade_municipio == "" ? NULL : $naturalidade_municipio);
      $stmt->bindValue(6, $pais);
      $stmt->bindValue(7, $nome);
      $stmt->bindValue(8, $cpf);
      $stmt->bindValue(9, $cor == "" ? NULL : $cor);
      $stmt->bindValue(10, $data_nascimento);
      $stmt->bindValue(11, $numero_registro);
      $stmt->bindValue(12, $uf_expedicao);
      $stmt->bindValue(13, $data_expedicao);
      $stmt->bindValue(14, $orgao_expedidor);
      $stmt->bindValue(15, $numero_registro_2);
      $stmt->bindValue(16, $uf_expedicao_2);
      $stmt->bindValue(17, $data_validade_2);
      $stmt->bindValue(18, $data_expedicao_2);
      $stmt->bindValue(19, $sexo);
      $stmt->bindValue(20, $contato_email);
      $stmt->bindValue(21, $uniao_estavel);
      $stmt->bindValue(22, $deficiencia);
      $stmt->bindValue(23, $doenca_cronica);
      $stmt->bindValue(24, $grau_escolar_id == "" ? NULL : $grau_escolar_id);
      $stmt->bindValue(25, $cad_unico);
      $stmt->bindValue(26, $nis);
      $stmt->bindValue(27, $capitulo == "" ? NULL : $capitulo);
      $stmt->bindValue(28, $grupo == "" ? NULL : $grupo);
      $stmt->bindValue(29, $categoria == "" ? NULL : $categoria);
      $stmt->bindValue(30, $estado_civil == "" ? NULL : $estado_civil);
      $stmt->bindValue(31, $mae_nome);
      $stmt->bindValue(32, convertDataBR2ISO($mae_data_nascimento));
      $stmt->bindValue(33, convertDataBR2ISO($data_inicio_residencia));
      $stmt->bindValue(34, $lei_maria_penha);
      $stmt->bindValue(35, $estado_civil == 6 || $estado_civil == 7 || $estado_civil == 8 ? convertDataBR2ISO($casamento_data) : NULL);
      $stmt->bindValue(36, $deficiencia == 1 ? $tipo_deficiencia : NULL);
      $stmt->bindValue(37, $retroativo_sim == 1 ? obterAnoTimestamp(convertDataBR2ISO($data_cadastro_anterior)) : NULL);
      $stmt->bindValue(38, $provedor_lar);
      $stmt->bindValue(39, $trab_mesmo_endereco);
      $stmt->bindValue(40, $_SESSION['id']);
      $stmt->execute();

      $pessoa_id = $db->lastInsertId();

      //SALVANDO BENEFÍCIO SOCIAL
      if ($bolsa_familia == 3) {
        $stmt = $db->prepare("INSERT INTO hab_pessoa_beneficio (hab_pessoa_id, hab_beneficio_social_id, data_cadastro, seg_usuario_pai_id, status) VALUES (?, ?, NOW(), ?, 1)");
        $stmt->bindValue(1, $pessoa_id);
        $stmt->bindValue(2, 3);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      }

      if ($bpc == 2) {
        $stmt = $db->prepare("INSERT INTO hab_pessoa_beneficio (hab_pessoa_id, hab_beneficio_social_id, data_cadastro, seg_usuario_pai_id, status) VALUES (?, ?, NOW(), ?, 1)");
        $stmt->bindValue(1, $pessoa_id);
        $stmt->bindValue(2, 2);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      }

      //CADASTRAR DADOS ESCOLARES
      $stmt = $db->prepare("INSERT INTO hab_pessoa_escolar (instituicao_nome, serie_periodo, hab_instituicao_natureza_id, hab_financia_natureza_id, hab_programa_social_id, bolsa_percentual, status, data_cadastro, hab_pessoa_id, seg_usuario_pai_id) VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), ?, ?)");
      $stmt->bindValue(1, $nome_instituicao);
      $stmt->bindValue(2, $serie_periodo);
      $stmt->bindValue(3, $rede_publica_privada == "" ? NULL : $rede_publica_privada);
      $stmt->bindValue(4, $financia);
      $stmt->bindValue(5, $programa == "" ? NULL : $programa);
      $stmt->bindValue(6, $porcentagem);
      $stmt->bindValue(7, $pessoa_id);
      $stmt->bindValue(8, $_SESSION['id']);
      $stmt->execute();

      //CADASTRO DE ENDEREÇO DA PESSOA
      $stmt = $db->prepare("INSERT INTO hab_pessoa_endereco (bsc_municipio_id, logradouro, numero, bairro, quadra, lote, complemento, cep, bsc_endereco_tipo_id, bsc_logradouro_id, alugada, aluguel_valor, aluguel_social, coabitacao_involuntaria, latitude, longitude, status, data_cadastro, hab_pessoa_id, seg_usuario_pai_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), ?, ?)");
      $stmt->bindValue(1, $municipio == "" ? NULL : $municipio);
      $stmt->bindValue(2, $logradouro);
      $stmt->bindValue(3, $numero);
      $stmt->bindValue(4, $bairro);
      $stmt->bindValue(5, $quadra);
      $stmt->bindValue(6, $casa);
      $stmt->bindValue(7, $complemento);
      $stmt->bindValue(8, $cep);
      $stmt->bindValue(9, $tipo_endereco == "" ? NULL : $tipo_endereco);
      $stmt->bindValue(10, $tipo_logradouro == "" ? NULL : $tipo_logradouro);
      $stmt->bindValue(11, $alugada);
      $stmt->bindValue(12, $alugada == 1 ? valorfloat($valor_aluguel) : NULL);
      $stmt->bindValue(13, $alugada == 1 ? $aluguel_social : 0);
      $stmt->bindValue(14, $coabitacao_involuntaria == 1 ? $coabitacao_involuntaria : 0);
      $stmt->bindValue(15, $latitude);
      $stmt->bindValue(16, $longitude);
      $stmt->bindValue(17, $pessoa_id);
      $stmt->bindValue(18, $_SESSION['id']);
      $stmt->execute();

      if ($residencial != "") {
        //TIPO RESIDENCIAL
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (1, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $residencial);
        $stmt->bindValue(2, $pessoa_id);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      }

      if ($celular != "") {
        //TIPO CELULAR
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (2, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $celular);
        $stmt->bindValue(2, $pessoa_id);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      }

      if ($comercial != "" && $ramal != "") {
        //TIPO COMERCIAL
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, ramal, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (3, ?, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $comercial);
        $stmt->bindValue(2, $ramal);
        $stmt->bindValue(3, $pessoa_id);
        $stmt->bindValue(4, $_SESSION['id']);
        $stmt->execute();
      }

      if ($contato_telefone != "" && $contato_nome != "") {
        //TIPO RECADO
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, recado_nome, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (4, ?, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $contato_telefone);
        $stmt->bindValue(2, $contato_nome);
        $stmt->bindValue(3, $pessoa_id);
        $stmt->bindValue(4, $_SESSION['id']);
        $stmt->execute();
      }

      if ($mae_contato != "") {
        //TIPO MÃE
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (5, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $mae_contato);
        $stmt->bindValue(2, $pessoa_id);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      }

      //CADASTRO DA OCUPAÇÃO DA PESSOA
      $stmt = $db->prepare("INSERT INTO hab_ocupacao (instituicao, endereco, cargo, data_inicio, seg_usuario_pai_id, hab_pessoa_id, status, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())");
      $stmt->bindValue(1, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $local_trabalho);
      $stmt->bindValue(2, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $trab_endereco);
      $stmt->bindValue(3, $trabalha == 1 ? $cargo_funcao : NULL);
      $stmt->bindValue(4, $trabalha == 1 ? convertDataBR2ISO($data_inicio) : NULL);
      $stmt->bindValue(5, $_SESSION['id']);
      $stmt->bindValue(6, $pessoa_id);
      $stmt->execute();

      //CADASTRO DE CANDIDATO
      gera_senha(4);

      $stmt = $db->prepare("INSERT INTO hab_candidato (situacao, area_risco_insalubre, morador_rua, data_cadastro_anterior, snch_apf_id, senha_visualiza, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (1, ?, ?, ?, ?, ?, ?, NOW(), 1, ?)");
      $stmt->bindValue(1, $area_risco_insalubre);
      $stmt->bindValue(2, $morador_rua);
      $stmt->bindValue(3, $retroativo_sim == 1 ? convertDataBR2ISO($data_cadastro_anterior) : NULL);
      $stmt->bindValue(4, $pereferencia_empreendimento == 1 ? $empreendimento : NULL);
      $stmt->bindValue(5, gera_senha(4));
      $stmt->bindValue(6, $pessoa_id);
      $stmt->bindValue(7, $_SESSION['id']);
      $stmt->execute();

      $candidato_id = $db->lastInsertId();

      $stmt = $db->prepare("INSERT INTO hab_candidato_situacao (hab_candidato_id, seg_usuario_pai_id, hab_tipo_situacao_id, data_cadastro, status, data_update) VALUES (?, ?, 1, NOW(), 1, NOW())");
      $stmt->bindValue(1, $candidato_id);
      $stmt->bindValue(2, $_SESSION['id']);
      $stmt->execute();

      if ($subprograma != "") {
        $stmt = $db->prepare("INSERT INTO hab_candidato_programa (hab_candidato_id, hab_subprograma_id, data_cadastro, status, seg_usuario_pai_id) VALUES (?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $candidato_id);
        $stmt->bindValue(2, $subprograma);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      }

      if ($vinculo == 1) {
        //CADASTRO DE OUTRO CÔNJUGE JUDICIAL
        $stmt = $db->prepare("INSERT INTO hab_pessoa (nome, cpf, data_cadastro, seg_usuario_pai_id) VALUES (?, ?, NOW(), ?)");
        $stmt->bindValue(1, $vinculo_nome);
        $stmt->bindValue(2, $vinculo_cpf);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();

        $pessoa2_id = $db->lastInsertId();

        //TIPO CELULAR
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (2, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $vinculo_contato);
        $stmt->bindValue(2, $pessoa2_id);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();

        $stmt = $db->prepare("INSERT INTO hab_conjuge (hab_pessoa_id_conjuge, hab_candidato_id, data_cadastro, status, seg_usuario_pai_id, tipo) VALUES (?, ?, NOW(), 1, ?, 2)");
        $stmt->bindValue(1, $pessoa2_id);
        $stmt->bindValue(2, $candidato_id);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
      }

      //RENDA
      $stmt = $db->prepare("DELETE FROM hab_pessoa_renda WHERE hab_pessoa_id = ?");
      $stmt->bindValue(1, $pessoa_id);
      $stmt->execute();

      foreach ($tipo_renda AS $key => $val) {
        if ($val != "" && $renda_valor[$key] != "" && $val != 0) {
          $stmt = $db->prepare("INSERT INTO hab_pessoa_renda (valor, hab_pessoa_id, hab_renda_tipo_id, data_cadastro, seg_usuario_pai_id, status) VALUES (?, ?, ?, NOW(), ?, 1)");
          $stmt->bindValue(1, valorfloat($renda_valor[$key]));
          $stmt->bindValue(2, $pessoa_id);
          $stmt->bindValue(3, $val);
          $stmt->bindValue(4, $_SESSION['id']);
          $stmt->execute();
        }
      }

      $msg['retorno'] = 'Primeira etapa cadastrada com sucesso!';
    }

//----------------------------------------------------------------------------------------------------------------------------
//SALVANDO OS CRITÉRIOS
//    $stmt = $db->prepare("DELETE FROM hab_candidato_criterios WHERE hab_candidato_id = ?");
//    $stmt->bindValue(1, $candidato_id);
//    $stmt->execute();
//
//    $result = $db->prepare("SELECT id, nome FROM hab_criterios WHERE status = 1 ORDER BY nome ASC");
//    $result->execute();
//    while ($criterio = $result->fetch(PDO::FETCH_ASSOC)) {
//      if (isset($_POST['criterio_' . $criterio['id'] . ''])) {
//        $stmt = $db->prepare("INSERT INTO hab_candidato_criterios (hab_candidato_id, hab_criterios_id, data_cadastro, seg_usuario_pai_id, status) VALUES (?, ?, NOW(), ?, 1)");
//        $stmt->bindValue(1, $candidato_id);
//        $stmt->bindValue(2, $_POST['criterio_' . $criterio['id'] . '']);
//        $stmt->bindValue(3, $_SESSION['id']);
//        $stmt->execute();
//      }
//    }
//    
//------------------------------------------------------------------------------------------------------------------------------------
//TORNANDO CANDIDATO APTO AO SORTEIO OU NÃO
    if ($candidato_apto == 1) {
      //CADASTRO DE CANDIDATO APTO
      $stmt = $db->prepare("INSERT INTO sort_candidato_apto (candidato_id, seg_usuario_pai, data_cadastro, data_update, status)
                 VALUES (?, ?, NOW(), NOW(), 1)");
      $stmt->bindValue(1, $candidato_id);
      $stmt->bindValue(2, $_SESSION['id']);
      $stmt->execute();
    } else {
      //DELETEANDO CANDIDATO APTO
      $stmt = $db->prepare("DELETE FROM sort_candidato_apto WHERE candidato_id = ?");
      $stmt->bindValue(1, $candidato_id);
      $stmt->execute();
    }
//------------------------------------------------------------------------------------------------------------------------------------

    $db->commit();

    //MENSAGEM DE SUCESSO
    $msg['id'] = $candidato_id;
    $msg['msg'] = 'success';
    echo json_encode($msg);
    exit();
  } else {
    $msg['msg'] = 'error';
    $msg['retorno'] = $mensagem;
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar cadastrar ou atualizar a primeira etapa do formulário de candidato:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>