<?php

//------------------------------------------------------------------------------
//DATA: 12/08/2016 às 15:00
//NOME: Cadastro de candidato
//DESCRIÇÃO: Realiza o cadastro de cônjuge no banco de dados
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$error = false;
$msg = array();
$mensagem = "";

$db->beginTransaction();

try {

  $possui_conjuge = $_POST['conjuge'];
  $candidato_id = $_POST['id'];
  $conjuge_id = pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 0");
  $conjuge_pessoa_id = pesquisar_tabela("hab_pessoa_id_conjuge", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 0");

  if ($possui_conjuge == 1) {

    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $cor = $_POST['cor'];
    $data_nascimento = convertDataBR2ISO($_POST['data_nascimento']);
    $sexo = $_POST['sexo'];
    $documento1 = $_POST['documento1'];

    //CONTATO
    $contato_email = $_POST['contato_email'];
    $residencial = $_POST['residencial'];
    $celular = $_POST['celular'];
    $comercial = $_POST['comercial'];
    $ramal = $_POST['ramal'];

    $tipo_renda = $_POST['tipo_renda'];
    $renda_valor = $_POST['renda_valor'];

    //TRABALHO
    $trabalha = $_POST['trabalha'];
    $local_trabalho = $_POST['local_trabalho'];
    $trab_endereco = $_POST['trab_endereco'];
    $cargo_funcao = $_POST['cargo_funcao'];
    $data_inicio = $_POST['data_inicio'];
    $provedor_lar = $_POST['provedor'];

    $trab_mesmo_endereco = $_POST['trab_mesmo_endereco'];

    $grau_escolar_id = $_POST['grau_escolar'];
    $estado_civil = $_POST['estado_civil'];
    $deficiencia = $_POST['deficiencia'];
    $cad_unico = $_POST['cad_unico'];
    $nis = $_POST['nis'];

    //CID10
    $capitulo = $_POST['capitulo'];
    $grupo = $_POST['grupo'];
    $categoria = $_POST['categoria'];

    $nacionalidade = $_POST['nacionalidade'];

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
      $vinculo_nome = $_POST['vinculo_nome'];
      $vinculo_cpf = $_POST['vinculo_cpf'];
      $vinculo_contato = $_POST['vinculo_contato'];
    } else {
      $vinculo_nome = NULL;
      $vinculo_cpf = NULL;
      $vinculo_contato = NULL;
    }

    //ENDEREÇO
    $mesmo_endereco = $_POST['mora'];

    if ($mesmo_endereco == 1) {//MORA SIM
      //ENDEREÇO
      $cep = NULL;
      $municipio = NULL;
      $logradouro = NULL;
      $numero = NULL;
      $bairro = NULL;
      $quadra = NULL;
      $casa = NULL;
      $complemento = NULL;
    } else {//MORA NÃO
      $cep = $_POST['cep'];
      $municipio = $_POST['municipio'];
      $logradouro = $_POST['logradouro'];
      $numero = $_POST['numero'];
      $bairro = $_POST['bairro'];
      $quadra = $_POST['quadra'];
      $casa = $_POST['casa'];
      $complemento = $_POST['complemento'];
    }

    if ($nacionalidade == 1) {
      $naturalidade_municipio = $_POST['naturalidade_municipio'];
      $pais = 35; // 35 = BRASILEIRO
      $cod_rne = NULL;
      $classificacao = NULL;
      $data_expedicao_1 = NULL;
      $validade = NULL;
    } else {
      $naturalidade_municipio = NULL;
      $pais = $_POST['pais'] == NULL ? 35 : $_POST['pais']; // 35 = BRASILEIRO
      $cod_rne = $_POST['cod_rne'] == NULL ? NULL : $_POST['cod_rne'];
      $classificacao = $_POST['classificacao'] == NULL ? NULL : $_POST['classificacao'];
      $data_expedicao_1 = $_POST['data_expedicao_1'] == NULL ? NULL : $_POST['data_expedicao_1'];
      $validade = $_POST['validade'] == NULL ? NULL : $_POST['validade'];
    }

    if ($documento1 == 1) {//RG
      $numero_registro = $_POST['numero_registro'];
      $orgao_expedidor = $_POST['orgao_expedidor'];
      $uf_expedicao = $_POST['uf_expedicao'];
      $data_expedicao = convertDataBR2ISO($_POST['data_expedicao']);
      $numero_registro_2 = "";
      $uf_expedicao_2 = "";
      $data_expedicao_2 = "";
      $data_validade_2 = "";
    } else {//CNH
      $numero_registro = "";
      $orgao_expedidor = "";
      $uf_expedicao = "";
      $data_expedicao = "";
      $numero_registro_2 = $_POST['numero_registro_2'];
      $uf_expedicao_2 = $_POST['uf_expedicao_2'];
      $data_expedicao_2 = convertDataBR2ISO($_POST['data_expedicao_2']);
      $data_validade_2 = convertDataBR2ISO($_POST['data_validade_2']);
    }

    $cpf_pessoa_cpf = pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $cpf, "");

    if (is_numeric($cpf_pessoa_cpf) && $cpf_pessoa_cpf != $conjuge_pessoa_id) {

      if (is_numeric(pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $cpf, "AND id IN (SELECT hab_pessoa_id_conjuge FROM hab_conjuge WHERE tipo = 0)"))) {
        $error = true;
        $mensagem .= "O CPF do cônjuge informado já existe no sistema com cônjuge.";
        $msg['tipo'] = "cpf";
      } else if (is_numeric(pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $cpf, "AND id IN (SELECT hab_pessoa_id FROM hab_familiar)"))) {
        $error = true;
        $mensagem .= "O CPF do cônjuge informado já existe no sistema como membro de um grupo familiar.";
        $msg['tipo'] = "cpf";
      } else if (is_numeric(pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $cpf, "AND id IN (SELECT hab_pessoa_id FROM hab_candidato)"))) {
        $error = true;
        $mensagem .= "O CPF do cônjuge informado já existe no sistema como candidato.";
        $msg['tipo'] = "cpf";
      }
    } else {
      if (@!valida_cpf($cpf)) {
        $error = true;
        $mensagem .= "O CPF do cônjuge informado é inválido.";
        $msg['tipo'] = "cpf";
      }
    }

    if ($error == false) {

      if (is_numeric($conjuge_id)) {
//------------------------------------------------ATUALIZAÇÃO------------------------------------------------
        //ATUALIZAÇÃO DE PESSOA
        $stmt = $db->prepare("UPDATE hab_pessoa SET bsc_estado_civil_id = ?, bsc_cie_classificacao_id = ?, cie_rne = ?, cie_data_validade = ?, cie_data_expedicao = ?, bsc_municipio_id_natural = ?, bsc_nacionalidade_id = ?, nome = ?, cpf = ?, bsc_pele_cor_id = ?, data_nascimento = ?, rg_numero = ?, rg_uf_expedicao = ?, rg_data_expedicao = ?, rg_orgao_expedicao_id = ?, cnh_numero = ?, cnh_uf_expedicao = ?, cnh_data_validade = ?, cnh_data_expedicao = ?, bsc_sexo_id = ?, email = ?, provedor_lar = ?, hab_grau_escolar_id = ?, deficiencia = ?, cadastro_unico = ?, nis = ?, hab_cid10_capitulo_id = ?, hab_cid10_grupo_id = ?, hab_cid10_categoria_id = ?, endereco_candidato = ?, trab_mesmo_endereco = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE id = ?");
        $stmt->bindValue(1, $estado_civil == "" ? NULL : $estado_civil);
        $stmt->bindValue(2, $classificacao);
        $stmt->bindValue(3, $cod_rne);
        $stmt->bindValue(4, convertDataBR2ISO($validade));
        $stmt->bindValue(5, convertDataBR2ISO($data_expedicao_1));
        $stmt->bindValue(6, $naturalidade_municipio == "" ? NULL : $naturalidade_municipio);
        $stmt->bindValue(7, $pais);
        $stmt->bindValue(8, $nome);
        $stmt->bindValue(9, $cpf);
        $stmt->bindValue(10, $cor == "" ? NULL : $cor);
        $stmt->bindValue(11, $data_nascimento);
        $stmt->bindValue(12, $numero_registro);
        $stmt->bindValue(13, $uf_expedicao);
        $stmt->bindValue(14, $data_expedicao);
        $stmt->bindValue(15, $orgao_expedidor);
        $stmt->bindValue(16, $numero_registro_2);
        $stmt->bindValue(17, $uf_expedicao_2);
        $stmt->bindValue(18, $data_validade_2);
        $stmt->bindValue(19, $data_expedicao_2);
        $stmt->bindValue(20, $sexo);
        $stmt->bindValue(21, $contato_email);
        $stmt->bindValue(22, $provedor_lar);
        $stmt->bindValue(23, $grau_escolar_id == "" ? NULL : $grau_escolar_id);
        $stmt->bindValue(24, $deficiencia);
        $stmt->bindValue(25, $cad_unico);
        $stmt->bindValue(26, $nis);
        $stmt->bindValue(27, $capitulo == "" ? NULL : $capitulo);
        $stmt->bindValue(28, $grupo == "" ? NULL : $grupo);
        $stmt->bindValue(29, $categoria == "" ? NULL : $categoria);
        $stmt->bindValue(30, $mesmo_endereco);
        $stmt->bindValue(31, $trab_mesmo_endereco);
        $stmt->bindValue(32, $_SESSION['id']);
        $stmt->bindValue(33, $conjuge_pessoa_id);
        $stmt->execute();

        if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $conjuge_pessoa_id, "AND hab_contato_tipo_id = 1"))) {
          //ATUALIZAR TIPO RESIDENCIAL
          $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_pessoa_id = ? AND hab_contato_tipo_id = 1");
          $stmt->bindValue(1, $residencial);
          $stmt->bindValue(2, $_SESSION['id']);
          $stmt->bindValue(3, $conjuge_pessoa_id);
          $stmt->execute();
        } else {
          //CADASTRAR TIPO RESIDENCIAL
          $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (1, ?, ?, NOW(), 1, ?)");
          $stmt->bindValue(1, $residencial);
          $stmt->bindValue(2, $conjuge_pessoa_id);
          $stmt->bindValue(3, $_SESSION['id']);
          $stmt->execute();
        }

        if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $conjuge_pessoa_id, "AND hab_contato_tipo_id = 2"))) {
          //ATUALIZAR TIPO CELULAR
          $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_pessoa_id = ? AND hab_contato_tipo_id = 2");
          $stmt->bindValue(1, $celular);
          $stmt->bindValue(2, $_SESSION['id']);
          $stmt->bindValue(3, $conjuge_pessoa_id);
          $stmt->execute();
        } else {
          //CADASTRAR TIPO CELULAR
          $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (2, ?, ?, NOW(), 1, ?)");
          $stmt->bindValue(1, $celular);
          $stmt->bindValue(2, $conjuge_pessoa_id);
          $stmt->bindValue(3, $_SESSION['id']);
          $stmt->execute();
        }

        if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $conjuge_pessoa_id, "AND hab_contato_tipo_id = 3"))) {
          //ATUALIZAR TIPO COMERCIAL
          $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, ramal = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_pessoa_id = ? AND hab_contato_tipo_id = 3");
          $stmt->bindValue(1, $comercial);
          $stmt->bindValue(2, $ramal);
          $stmt->bindValue(3, $_SESSION['id']);
          $stmt->bindValue(4, $conjuge_pessoa_id);
          $stmt->execute();
        } else {
          //CADASTRAR TIPO COMERCIAL
          $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, ramal, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (3, ?, ?, ?, NOW(), 1, ?)");
          $stmt->bindValue(1, $comercial);
          $stmt->bindValue(2, $ramal);
          $stmt->bindValue(3, $conjuge_pessoa_id);
          $stmt->bindValue(4, $_SESSION['id']);
          $stmt->execute();
        }

        if (is_numeric(pesquisar_tabela("id", "hab_pessoa_escolar", "hab_pessoa_id", "=", $conjuge_pessoa_id, ""))) {
          //ATUALIZAR DADOS ESCOLARES
          $stmt = $db->prepare("UPDATE hab_pessoa_escolar SET instituicao_nome = ?, serie_periodo = ?, hab_instituicao_natureza_id = ?, hab_financia_natureza_id = ?, hab_programa_social_id = ?, bolsa_percentual = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE hab_pessoa_id = ?");
          $stmt->bindValue(1, $nome_instituicao);
          $stmt->bindValue(2, $serie_periodo);
          $stmt->bindValue(3, $rede_publica_privada == "" ? NULL : $rede_publica_privada);
          $stmt->bindValue(4, $financia);
          $stmt->bindValue(5, $programa == "" ? NULL : $programa);
          $stmt->bindValue(6, $porcentagem);
          $stmt->bindValue(7, $_SESSION['id']);
          $stmt->bindValue(8, $conjuge_pessoa_id);
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
          $stmt->bindValue(7, $conjuge_pessoa_id);
          $stmt->bindValue(8, $_SESSION['id']);
          $stmt->execute();
        }

        if (is_numeric(pesquisar_tabela("id", "hab_pessoa_endereco", "hab_pessoa_id", "=", $conjuge_pessoa_id, ""))) {
          //ATUALIZAÇÃO DE PESSOA ENDEREÇO
          $stmt = $db->prepare("UPDATE hab_pessoa_endereco SET bsc_municipio_id = ?, logradouro = ?, numero = ?, bairro = ?, quadra = ?, lote = ?, complemento = ?, cep = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE hab_pessoa_id = ?");
          $stmt->bindValue(1, $municipio == "" ? NULL : $municipio);
          $stmt->bindValue(2, $logradouro);
          $stmt->bindValue(3, $numero);
          $stmt->bindValue(4, $bairro);
          $stmt->bindValue(5, $quadra);
          $stmt->bindValue(6, $casa);
          $stmt->bindValue(7, $complemento);
          $stmt->bindValue(8, $cep);
          $stmt->bindValue(9, $_SESSION['id']);
          $stmt->bindValue(10, $conjuge_pessoa_id);
          $stmt->execute();
        } else {
          //CADASTRO DE ENDEREÇO DA PESSOA
          $stmt = $db->prepare("INSERT INTO hab_pessoa_endereco (bsc_municipio_id, logradouro, numero, bairro, quadra, lote, complemento, cep, status, data_cadastro, hab_pessoa_id, seg_usuario_pai_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), ?, ?)");
          $stmt->bindValue(1, $municipio == "" ? NULL : $municipio);
          $stmt->bindValue(2, $logradouro);
          $stmt->bindValue(3, $numero);
          $stmt->bindValue(4, $bairro);
          $stmt->bindValue(5, $quadra);
          $stmt->bindValue(6, $casa);
          $stmt->bindValue(7, $complemento);
          $stmt->bindValue(8, $cep);
          $stmt->bindValue(9, $conjuge_pessoa_id);
          $stmt->bindValue(10, $_SESSION['id']);
          $stmt->execute();
        }

        if (is_numeric(pesquisar_tabela("id", "hab_ocupacao", "hab_pessoa_id", "=", $conjuge_pessoa_id, ""))) {
          //ATUALIZAÇÃO DA OCUPAÇÃO DA PESSOA
          $stmt = $db->prepare("UPDATE hab_ocupacao SET instituicao = ?, endereco = ?, cargo = ?, data_inicio = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE hab_pessoa_id = ?");
          $stmt->bindValue(1, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $local_trabalho);
          $stmt->bindValue(2, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $trab_endereco);
          $stmt->bindValue(3, $trabalha == 1 ? $cargo_funcao : NULL);
          $stmt->bindValue(4, $trabalha == 1 ? convertDataBR2ISO($data_inicio) : NULL);
          $stmt->bindValue(5, $_SESSION['id']);
          $stmt->bindValue(6, $conjuge_pessoa_id);
          $stmt->execute();
        } else {
          //CADASTRO DA OCUPAÇÃO DA PESSOA
          $stmt = $db->prepare("INSERT INTO hab_ocupacao (instituicao, endereco, cargo, data_inicio, seg_usuario_pai_id, hab_pessoa_id, status, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())");
          $stmt->bindValue(1, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $local_trabalho);
          $stmt->bindValue(2, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $trab_endereco);
          $stmt->bindValue(3, $trabalha == 1 ? $cargo_funcao : NULL);
          $stmt->bindValue(4, $trabalha == 1 ? convertDataBR2ISO($data_inicio) : NULL);
          $stmt->bindValue(5, $_SESSION['id']);
          $stmt->bindValue(6, $conjuge_pessoa_id);
          $stmt->execute();
        }

        //ATUALIZAÇÃO DE CÔNJUGE
        $stmt = $db->prepare("UPDATE hab_conjuge SET data_update = NOW() WHERE id = ?");
        $stmt->bindValue(1, $conjuge_id);
        $stmt->execute();

        $hab_conjuge_id = $conjuge_id;

        $pessoa2_id = pesquisar_tabela("hab_pessoa_id_conjuge", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 1");

        if ($vinculo == 1) {

          if (is_numeric($pessoa2_id)) {
            //ATUALIZAÇÃO DE OUTRO CÔNJUGE JUDICIAL
            $stmt = $db->prepare("UPDATE hab_pessoa SET nome = ?, cpf = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE id IN (SELECT hab_pessoa_id_conjuge FROM hab_conjuge WHERE hab_candidato_id = ? AND tipo = 1)");
            $stmt->bindValue(1, $vinculo_nome);
            $stmt->bindValue(2, $vinculo_cpf);
            $stmt->bindValue(3, $_SESSION['id']);
            $stmt->bindValue(4, $candidato_id);
            $stmt->execute();

            $stmt = $db->prepare("UPDATE hab_conjuge SET data_update = NOW() WHERE id = ?");
            $stmt->bindValue(1, $pessoa2_id);
            $stmt->execute();
          } else {
            //CADASTRO DE OUTRO CÔNJUGE JUDICIAL
            $stmt = $db->prepare("INSERT INTO hab_pessoa (nome, cpf, data_cadastro, seg_usuario_pai_id) VALUES (?, ?, NOW(), ?)");
            $stmt->bindValue(1, $vinculo_nome);
            $stmt->bindValue(2, $vinculo_cpf);
            $stmt->bindValue(3, $_SESSION['id']);
            $stmt->execute();

            $pessoa2_id = $db->lastInsertId();

            $stmt = $db->prepare("INSERT INTO hab_conjuge (hab_pessoa_id_conjuge, hab_candidato_id, data_cadastro, status, seg_usuario_pai_id, tipo) VALUES (?, ?, NOW(), 1, ?, 1)");
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
        if (is_numeric($conjuge_pessoa_id)) {
          $stmt = $db->prepare("DELETE FROM hab_pessoa_renda WHERE hab_pessoa_id = ?");
          $stmt->bindValue(1, $conjuge_pessoa_id);
          $stmt->execute();

          foreach ($tipo_renda AS $key => $val) {
            if ($val != "" && $renda_valor[$key] != "" && $val != 0) {
              $stmt = $db->prepare("INSERT INTO hab_pessoa_renda (valor, hab_pessoa_id, hab_renda_tipo_id, data_cadastro, seg_usuario_pai_id, status) VALUES (?, ?, ?, NOW(), ?, 1)");
              $stmt->bindValue(1, valorfloat($renda_valor[$key]));
              $stmt->bindValue(2, $conjuge_pessoa_id);
              $stmt->bindValue(3, $val);
              $stmt->bindValue(4, $_SESSION['id']);
              $stmt->execute();
            }
          }
        }

        $msg['retorno'] = 'Segunda etapa do cadastro atualizado com sucesso!';
      } else {
//------------------------------------------------CADASTRO------------------------------------------------
        $stmt = $db->prepare("INSERT INTO hab_pessoa (bsc_estado_civil_id, bsc_cie_classificacao_id, cie_rne, cie_data_validade, cie_data_expedicao, bsc_municipio_id_natural, bsc_nacionalidade_id, nome, cpf, bsc_pele_cor_id, data_nascimento, rg_numero, rg_uf_expedicao, rg_data_expedicao, rg_orgao_expedicao_id, cnh_numero, cnh_uf_expedicao, cnh_data_validade, cnh_data_expedicao, bsc_sexo_id, email, provedor_lar, hab_grau_escolar_id, deficiencia, cadastro_unico, nis, hab_cid10_capitulo_id, hab_cid10_grupo_id, hab_cid10_categoria_id, endereco_candidato, trab_mesmo_endereco, data_cadastro, status, seg_usuario_pai_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $estado_civil == "" ? NULL : $estado_civil);
        $stmt->bindValue(2, $classificacao);
        $stmt->bindValue(3, $cod_rne);
        $stmt->bindValue(4, convertDataBR2ISO($validade));
        $stmt->bindValue(5, convertDataBR2ISO($data_expedicao_1));
        $stmt->bindValue(6, $naturalidade_municipio == "" ? NULL : $naturalidade_municipio);
        $stmt->bindValue(7, $pais);
        $stmt->bindValue(8, $nome);
        $stmt->bindValue(9, $cpf);
        $stmt->bindValue(10, $cor == "" ? NULL : $cor);
        $stmt->bindValue(11, $data_nascimento);
        $stmt->bindValue(12, $numero_registro);
        $stmt->bindValue(13, $uf_expedicao);
        $stmt->bindValue(14, $data_expedicao);
        $stmt->bindValue(15, $orgao_expedidor);
        $stmt->bindValue(16, $numero_registro_2);
        $stmt->bindValue(17, $uf_expedicao_2);
        $stmt->bindValue(18, $data_expedicao_2);
        $stmt->bindValue(19, $data_validade_2);
        $stmt->bindValue(20, $sexo);
        $stmt->bindValue(21, $contato_email);
        $stmt->bindValue(22, $provedor_lar);
        $stmt->bindValue(23, $grau_escolar_id == "" ? NULL : $grau_escolar_id);
        $stmt->bindValue(24, $deficiencia);
        $stmt->bindValue(25, $cad_unico);
        $stmt->bindValue(26, $nis);
        $stmt->bindValue(27, $capitulo == "" ? NULL : $capitulo);
        $stmt->bindValue(28, $grupo == "" ? NULL : $grupo);
        $stmt->bindValue(29, $categoria == "" ? NULL : $categoria);
        $stmt->bindValue(30, $mesmo_endereco);
        $stmt->bindValue(31, $trab_mesmo_endereco);
        $stmt->bindValue(32, $_SESSION['id']);
        $stmt->execute();

        $pessoa_id = $db->lastInsertId();

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
        $stmt = $db->prepare("INSERT INTO hab_pessoa_endereco (bsc_municipio_id, logradouro, numero, bairro, quadra, lote, complemento, cep, status, data_cadastro, hab_pessoa_id, seg_usuario_pai_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), ?, ?)");
        $stmt->bindValue(1, $municipio == "" ? NULL : $municipio);
        $stmt->bindValue(2, $logradouro);
        $stmt->bindValue(3, $numero);
        $stmt->bindValue(4, $bairro);
        $stmt->bindValue(5, $quadra);
        $stmt->bindValue(6, $casa);
        $stmt->bindValue(7, $complemento);
        $stmt->bindValue(8, $cep);
        $stmt->bindValue(9, $pessoa_id);
        $stmt->bindValue(10, $_SESSION['id']);
        $stmt->execute();

        //TIPO RESIDENCIAL
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (1, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $residencial);
        $stmt->bindValue(2, $pessoa_id);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
        //TIPO CELULAR
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (2, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $celular);
        $stmt->bindValue(2, $pessoa_id);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();
        //TIPO COMERCIAL
        $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, ramal, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (3, ?, ?, ?, NOW(), 1, ?)");
        $stmt->bindValue(1, $comercial);
        $stmt->bindValue(2, $ramal);
        $stmt->bindValue(3, $pessoa_id);
        $stmt->bindValue(4, $_SESSION['id']);
        $stmt->execute();

        //CADASTRO DA OCUPAÇÃO DA PESSOA
        $stmt = $db->prepare("INSERT INTO hab_ocupacao (instituicao, endereco, cargo, data_inicio, seg_usuario_pai_id, hab_pessoa_id, status, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())");
        $stmt->bindValue(1, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $local_trabalho);
        $stmt->bindValue(2, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $trab_endereco);
        $stmt->bindValue(3, $trabalha == 1 ? $cargo_funcao : NULL);
        $stmt->bindValue(4, $trabalha == 1 ? convertDataBR2ISO($data_inicio) : NULL);
        $stmt->bindValue(5, $_SESSION['id']);
        $stmt->bindValue(6, $pessoa_id);
        $stmt->execute();

        //CADASTRO DE CÔNJUGE
        $stmt = $db->prepare("INSERT INTO hab_conjuge (hab_pessoa_id_conjuge, hab_candidato_id, data_cadastro, status, seg_usuario_pai_id, tipo) VALUES (?, ?, NOW(), 1, ?, 0)");
        $stmt->bindValue(1, $pessoa_id);
        $stmt->bindValue(2, $candidato_id);
        $stmt->bindValue(3, $_SESSION['id']);
        $stmt->execute();

        $hab_conjuge_id = $db->lastInsertId();

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

          $stmt = $db->prepare("INSERT INTO hab_conjuge (hab_pessoa_id_conjuge, hab_candidato_id, data_cadastro, status, seg_usuario_pai_id, tipo) VALUES (?, ?, NOW(), 1, ?, 1)");
          $stmt->bindValue(1, $pessoa2_id);
          $stmt->bindValue(2, $candidato_id);
          $stmt->bindValue(3, $_SESSION['id']);
          $stmt->execute();
        }

        //RENDA
        if (is_numeric($pessoa_id)) {
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
        }

        $msg['retorno'] = 'Segunda etapa cadastrada com sucesso!';
      }

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
  } else {

    $observacao = $_POST['observacao'];

    //ATUALIZAÇÃO DE CÔNJUGE
    $stmt = $db->prepare("UPDATE hab_conjuge SET observacao = ?, status = 2, data_update = NOW() WHERE id = ?");
    $stmt->bindValue(1, $observacao);
    $stmt->bindValue(2, $conjuge_id);
    $stmt->execute();

    $db->commit();

    //MENSAGEM DE SUCESSO
    $msg['retorno'] = 'Segunda etapa concluída com sucesso!';
    $msg['id'] = $candidato_id;
    $msg['msg'] = 'success';
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar cadastrar ou atualizar a segunda etapa do formulário de candidato:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>