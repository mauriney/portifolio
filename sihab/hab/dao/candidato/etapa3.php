<?php

//------------------------------------------------------------------------------
//DATA: 12/08/2016 às 15:00
//NOME: Cadastro de candidato
//DESCRIÇÃO: Realiza o cadastro de familiares no banco de dados
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$error = false;
$msg = array();
$mensagem = "";

$db->beginTransaction();

try {

  $familia = $_POST['familia'];
  $candidato_id = $_POST['id'];
  $familiarIds = $_POST['familiar_id'];

  //REMOVENDO FAMILIAR DELETADO
  if (isset($_POST['array_familiar_id'])) {
    $array_familiar_id = $_POST['array_familiar_id'];
    foreach ($array_familiar_id AS $key => $id) {
      if ($id != "" && $id != NULL && $id != 0) {
        if (!in_array("$id", $familiarIds)) {
//          //REMOVENDO DADOS ESCOLARES
          $stmt = $db->prepare("DELETE FROM hab_pessoa_escolar WHERE hab_pessoa_id IN (SELECT hab_pessoa_id FROM hab_familiar WHERE id = ?)");
          $stmt->bindValue(1, $id);
          $stmt->execute();
          //REMOVENDO DADOS DE ENDEREÇO
          $stmt = $db->prepare("DELETE FROM hab_pessoa_endereco WHERE hab_pessoa_id IN (SELECT hab_pessoa_id FROM hab_familiar WHERE id = ?)");
          $stmt->bindValue(1, $id);
          $stmt->execute();
          //REMOVENDO DADOS DE CONTATO
          $stmt = $db->prepare("DELETE FROM hab_pessoa_contato WHERE hab_pessoa_id IN (SELECT hab_pessoa_id FROM hab_familiar WHERE id = ?)");
          $stmt->bindValue(1, $id);
          $stmt->execute();
          //REMOVENDO DADOS DE OCUPAÇÃO
          $stmt = $db->prepare("DELETE FROM hab_ocupacao WHERE hab_pessoa_id IN (SELECT hab_pessoa_id FROM hab_familiar WHERE id = ?)");
          $stmt->bindValue(1, $id);
          $stmt->execute();
          //REMOVENDO A PESSOA RENDA
          $stmt = $db->prepare("DELETE FROM hab_pessoa_renda WHERE hab_pessoa_id IN (SELECT hab_pessoa_id FROM hab_familiar WHERE id = ?)");
          $stmt->bindValue(1, $id);
          $stmt->execute();

          $familiar_pessoa_id = pesquisar_tabela("hab_pessoa_id", "hab_familiar", "id", "=", $id, "");
          //REMOVENDO DADOS FAMILIAR
          $stmt = $db->prepare("DELETE FROM hab_familiar WHERE id  = ?");
          $stmt->bindValue(1, $id);
          $stmt->execute();
          //REMOVENDO DADOS FAMILIAR
//          $stmt = $db->prepare("UPDATE hab_familiar SET status = 2 WHERE id  = ?");
//          $stmt->bindValue(1, $id);
//          $stmt->execute();
          //REMOVENDO DADOS PESSOA FAMILIAR
          $stmt = $db->prepare("DELETE FROM hab_pessoa WHERE id = ?");
          $stmt->bindValue(1, $familiar_pessoa_id);
          $stmt->execute();
        }
      }
    }
  }

  //SE POSSUÍ FAMILIAR
  $qtd_familiar = $_POST['qtd_familiar'];
  $qtd_familiar++;
  $qtd_novo = "$qtd_familiar" . "1";

  if ($familia == 1) {
    if (isset($familiarIds)) {
      foreach ($familiarIds AS $FamiliarKey => $familiarId) {

        if ($_POST['nome'][$FamiliarKey] != "") {

          $familiar_pessoa_id = pesquisar_tabela("hab_pessoa_id", "hab_familiar", "id", "=", $familiarId, "");

          $nome = $_POST['nome'][$FamiliarKey];
          $cpf = $_POST['cpf'][$FamiliarKey];
          $cor = $_POST['cor'][$FamiliarKey];
          $data_nascimento = convertDataBR2ISO($_POST['data_nascimento'][$FamiliarKey]);
          $documento1 = $_POST['documento1'][$FamiliarKey];

          //RENDA
          $array_contador = $_POST['array_familiar_contador'][$FamiliarKey];
          $tipo_renda = $_POST['tipo_renda_' . $array_contador];
          $renda_valor = $_POST['renda_valor_' . $array_contador];

          //RADIO
          $sexo = $_POST['sexo_' . ($familiarId == 0 ? $qtd_novo : $familiarId)];
          $nacionalidade = $_POST['nacionalidade_' . ($familiarId == 0 ? $qtd_novo : $familiarId)];
          $deficiencia = $_POST['deficiencia_' . ($familiarId == 0 ? $qtd_novo : $familiarId)];
          $estudando = $_POST['estudando_' . ($familiarId == 0 ? $qtd_novo : $familiarId)];
          $financia = $_POST['financia_' . ($familiarId == 0 ? $qtd_novo : $familiarId)];
          $mesmo_endereco = $_POST['mora_' . ($familiarId == 0 ? $qtd_novo : $familiarId)];

          //CONTATO
          $contato_email = $_POST['contato_email'][$FamiliarKey];
          $residencial = $_POST['residencial'][$FamiliarKey];
          $celular = $_POST['celular'][$FamiliarKey];
          $comercial = $_POST['comercial'][$FamiliarKey];
          $ramal = $_POST['ramal'][$FamiliarKey];

          //TRABALHO
          $trabalha = $_POST['trabalha_' . ($familiarId == 0 ? $qtd_novo : $familiarId)];
          $local_trabalho = $_POST['local_trabalho'][$FamiliarKey];
          $trab_endereco = $_POST['trab_endereco'][$FamiliarKey];
          $cargo_funcao = $_POST['cargo_funcao'][$FamiliarKey];
          $data_inicio = $_POST['data_inicio'][$FamiliarKey];

          $trab_mesmo_endereco = $_POST['trab_mesmo_endereco_' . ($familiarId == 0 ? $qtd_novo : $familiarId)];

          $grau_escolar_id = $_POST['grau_escolar'][$FamiliarKey];
          $estado_civil = $_POST['estado_civil'][$FamiliarKey];

          $cad_unico = $_POST['cad_unico'][$FamiliarKey];
          $nis = $_POST['nis'][$FamiliarKey];

          //CID10
          $capitulo = $_POST['capitulo'][$FamiliarKey];
          $grupo = $_POST['grupo'][$FamiliarKey];
          $categoria = $_POST['categoria'][$FamiliarKey];

          //SE ESTUDA
          $grau_parentesco = $_POST['grau_parentesco'][$FamiliarKey];

          if ($estudando == 1) {
            $nome_instituicao = $_POST['nome_instituicao'][$FamiliarKey];
            $serie_periodo = $_POST['serie_periodo'][$FamiliarKey];
            $rede_publica_privada = $_POST['rede_publica_privada'][$FamiliarKey];

            if ($financia == 2) {
              $programa = $_POST['programa_social'][$FamiliarKey];
              $porcentagem = $_POST['porcentagem'][$FamiliarKey];
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

          //ENDEREÇO
          if ($mesmo_endereco == 1) {//MORA SIM
            $cep = NULL;
            $municipio = NULL;
            $logradouro = NULL;
            $numero = NULL;
            $bairro = NULL;
            $quadra = NULL;
            $casa = NULL;
            $complemento = NULL;
          } else {//MORA NÃO
            $cep = $_POST['cep'][$FamiliarKey];
            $municipio = $_POST['municipio'][$FamiliarKey];
            $logradouro = $_POST['logradouro'][$FamiliarKey];
            $numero = $_POST['numero'][$FamiliarKey];
            $bairro = $_POST['bairro'][$FamiliarKey];
            $quadra = $_POST['quadra'][$FamiliarKey];
            $casa = $_POST['casa'][$FamiliarKey];
            $complemento = $_POST['complemento'][$FamiliarKey];
          }

          if ($nacionalidade == 1) {
            $naturalidade_municipio = $_POST['naturalidade_municipio'][$FamiliarKey];
            $pais = 35; // 35 = BRASILEIRO
            $cod_rne = NULL;
            $classificacao = NULL;
            $data_expedicao_1 = NULL;
            $validade = NULL;
          } else {
            $naturalidade_municipio = NULL;
            $pais = $_POST['pais'][$FamiliarKey] == NULL ? 35 : $_POST['pais'][$FamiliarKey]; // 35 = BRASILEIRO
            $cod_rne = $_POST['cod_rne'][$FamiliarKey] == NULL ? NULL : $_POST['cod_rne'][$FamiliarKey];
            $classificacao = $_POST['classificacao'][$FamiliarKey] == NULL ? NULL : $_POST['classificacao'][$FamiliarKey];
            $data_expedicao_1 = $_POST['data_expedicao_1'][$FamiliarKey] == NULL ? NULL : $_POST['data_expedicao_1'][$FamiliarKey];
            $validade = $_POST['validade'][$FamiliarKey] == NULL ? NULL : $_POST['validade'][$FamiliarKey];
          }

          if ($documento1 == 1) {//RG
            $numero_registro = $_POST['numero_registro'][$FamiliarKey];
            $orgao_expedidor = $_POST['orgao_expedidor'][$FamiliarKey];
            $uf_expedicao = $_POST['uf_expedicao'][$FamiliarKey];
            $data_expedicao = convertDataBR2ISO($_POST['data_expedicao'][$FamiliarKey]);
            $numero_registro_2 = "";
            $uf_expedicao_2 = "";
            $data_expedicao_2 = "";
            $data_validade_2 = "";
          } else {//CNH
            $numero_registro = "";
            $orgao_expedidor = "";
            $uf_expedicao = "";
            $data_expedicao = "";
            $numero_registro_2 = $_POST['numero_registro_2'][$FamiliarKey];
            $uf_expedicao_2 = $_POST['uf_expedicao_2'][$FamiliarKey];
            $data_expedicao_2 = convertDataBR2ISO($_POST['data_expedicao_2'][$FamiliarKey]);
            $data_validade_2 = convertDataBR2ISO($_POST['data_validade_2'][$FamiliarKey]);
          }

          $cpf_pessoa_cpf = pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $cpf, "");

          if (is_numeric($cpf_pessoa_cpf) && $cpf_pessoa_cpf != $familiar_pessoa_id) {
            if (is_numeric(pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $cpf, "AND id IN (SELECT hab_pessoa_id_conjuge FROM hab_conjuge)"))) {
              $error = true;
              $mensagem .= "O CPF do familiar informado já existe no sistema com cônjuge.";
              $msg['tipo'] = "cpf";
            } else if (is_numeric(pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $cpf, "AND id IN (SELECT hab_pessoa_id FROM hab_familiar)"))) {
              $error = true;
              $mensagem .= "O CPF do familiar informado já existe no sistema como outro membro de um grupo familiar.";
              $msg['tipo'] = "cpf";
            } else if (is_numeric(pesquisar_tabela("id", "hab_pessoa", "cpf", "=", $cpf, "AND id IN (SELECT hab_pessoa_id FROM hab_candidato)"))) {
              $error = true;
              $mensagem .= "O CPF do familiar informado já existe no sistema como candidato.";
              $msg['tipo'] = "cpf";
            }
          } else {
            if (@!valida_cpf($cpf)) {
              $error = true;
              $mensagem .= "O CPF do familiar informado é inválido.";
              $msg['tipo'] = "cpf";
            }
          }

          if ($grau_parentesco == "") {
            $error = true;
            $mensagem .= "É necessário informar o grau parentesco.";
            $msg['tipo'] = "grau_parentesco";
          }

          if ($error == false) {

            if ($familiarId == 0) {

              $qtd_novo = "$qtd_novo" . "1";

              //------------------------------------------------CADASTRO------------------------------------------------
              $stmt = $db->prepare("INSERT INTO hab_pessoa (bsc_estado_civil_id, bsc_cie_classificacao_id, cie_rne, cie_data_validade, cie_data_expedicao, bsc_municipio_id_natural, bsc_nacionalidade_id, nome, cpf, bsc_pele_cor_id, data_nascimento, rg_numero, rg_uf_expedicao, rg_data_expedicao, rg_orgao_expedicao_id, cnh_numero, cnh_uf_expedicao, cnh_data_validade, cnh_data_expedicao, bsc_sexo_id, email, hab_grau_escolar_id, deficiencia, cadastro_unico, nis, hab_cid10_capitulo_id, hab_cid10_grupo_id, hab_cid10_categoria_id, endereco_candidato, trab_mesmo_endereco, data_cadastro, status, seg_usuario_pai_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 1, ?)");
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
              $stmt->bindValue(22, $grau_escolar_id == "" ? NULL : $grau_escolar_id);
              $stmt->bindValue(23, $deficiencia);
              $stmt->bindValue(24, $cad_unico);
              $stmt->bindValue(25, $nis);
              $stmt->bindValue(26, $capitulo == "" ? NULL : $capitulo);
              $stmt->bindValue(27, $grupo == "" ? NULL : $grupo);
              $stmt->bindValue(28, $categoria == "" ? NULL : $categoria);
              $stmt->bindValue(29, $mesmo_endereco);
              $stmt->bindValue(30, $trab_mesmo_endereco);
              $stmt->bindValue(31, $_SESSION['id']);
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
              $stmt = $db->prepare("INSERT INTO hab_familiar (hab_pessoa_id, hab_candidato_id, hab_grau_parentesco_id, data_cadastro, status, seg_usuario_pai_id) VALUES (?, ?, ?, NOW(), 1, ?)");
              $stmt->bindValue(1, $pessoa_id);
              $stmt->bindValue(2, $candidato_id);
              $stmt->bindValue(3, $grau_parentesco);
              $stmt->bindValue(4, $_SESSION['id']);
              $stmt->execute();

              $hab_familiar_id = $db->lastInsertId();

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

              $msg['retorno'] = 'Terceira etapa cadastrada com sucesso!';
            } else {
              //UPDATE
              //------------------------------------------------ATUALIZAÇÃO------------------------------------------------
              //ATUALIZAÇÃO DE PESSOA
              $stmt = $db->prepare("UPDATE hab_pessoa SET bsc_estado_civil_id = ?, bsc_cie_classificacao_id = ?, cie_rne = ?, cie_data_validade = ?, cie_data_expedicao = ?, bsc_municipio_id_natural = ?, bsc_nacionalidade_id = ?, nome = ?, cpf = ?, bsc_pele_cor_id = ?, data_nascimento = ?, rg_numero = ?, rg_uf_expedicao = ?, rg_data_expedicao = ?, rg_orgao_expedicao_id = ?, cnh_numero = ?, cnh_uf_expedicao = ?, cnh_data_validade = ?, cnh_data_expedicao = ?, bsc_sexo_id = ?, email = ?, hab_grau_escolar_id = ?, deficiencia = ?, cadastro_unico = ?, nis = ?, hab_cid10_capitulo_id = ?, hab_cid10_grupo_id = ?, hab_cid10_categoria_id = ?, endereco_candidato = ?, trab_mesmo_endereco = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE id = ?");
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
              $stmt->bindValue(22, $grau_escolar_id == "" ? NULL : $grau_escolar_id);
              $stmt->bindValue(23, $deficiencia);
              $stmt->bindValue(24, $cad_unico);
              $stmt->bindValue(25, $nis);
              $stmt->bindValue(26, $capitulo == "" ? NULL : $capitulo);
              $stmt->bindValue(27, $grupo == "" ? NULL : $grupo);
              $stmt->bindValue(28, $categoria == "" ? NULL : $categoria);
              $stmt->bindValue(29, $mesmo_endereco);
              $stmt->bindValue(30, $trab_mesmo_endereco);
              $stmt->bindValue(31, $_SESSION['id']);
              $stmt->bindValue(32, $familiar_pessoa_id);
              $stmt->execute();

              if (is_numeric(pesquisar_tabela("id", "hab_pessoa_escolar", "hab_pessoa_id", "=", $familiar_pessoa_id, ""))) {

                //ATUALIZAR DADOS ESCOLARES
                $stmt = $db->prepare("UPDATE hab_pessoa_escolar SET instituicao_nome = ?, serie_periodo = ?, hab_instituicao_natureza_id = ?, hab_financia_natureza_id = ?, hab_programa_social_id = ?, bolsa_percentual = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE hab_pessoa_id = ?");
                $stmt->bindValue(1, $nome_instituicao);
                $stmt->bindValue(2, $serie_periodo);
                $stmt->bindValue(3, $rede_publica_privada == "" ? NULL : $rede_publica_privada);
                $stmt->bindValue(4, $financia);
                $stmt->bindValue(5, $programa == "" ? NULL : $programa);
                $stmt->bindValue(6, $porcentagem);
                $stmt->bindValue(7, $_SESSION['id']);
                $stmt->bindValue(8, $familiar_pessoa_id);
                $stmt->execute();
              } else {
                //CADASTRAR DADOS ESCOLARES
                $stmt = $db->prepare("INSERT INTO hab_pessoa_escolar (instituicao_nome, serie_periodo, hab_instituicao_natureza_id, hab_financia_natureza_id, hab_programa_social_id, bolsa_percentual, status, data_cadastro, hab_pessoa_id, seg_usuario_pai_id) VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), ?, ?)");
                $stmt->bindValue(1, $nome_instituicao);
                $stmt->bindValue(2, $serie_periodo);
                $stmt->bindValue(3, $rede_publica_privada);
                $stmt->bindValue(4, $financia);
                $stmt->bindValue(5, $programa);
                $stmt->bindValue(6, $porcentagem);
                $stmt->bindValue(7, $familiar_pessoa_id);
                $stmt->bindValue(8, $_SESSION['id']);
                $stmt->execute();
              }

              if (is_numeric(pesquisar_tabela("id", "hab_pessoa_endereco", "hab_pessoa_id", "=", $familiar_pessoa_id, ""))) {
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
                $stmt->bindValue(10, $familiar_pessoa_id);
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
                $stmt->bindValue(9, $familiar_pessoa_id);
                $stmt->bindValue(10, $_SESSION['id']);
                $stmt->execute();
              }

              if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $familiar_pessoa_id, "AND hab_contato_tipo_id = 1"))) {
                //ATUALIZAR TIPO RESIDENCIAL
                $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_pessoa_id = ? AND hab_contato_tipo_id = 1");
                $stmt->bindValue(1, $residencial);
                $stmt->bindValue(2, $_SESSION['id']);
                $stmt->bindValue(3, $familiar_pessoa_id);
                $stmt->execute();
              } else {
                //CADASTRAR TIPO RESIDENCIAL
                $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (1, ?, ?, NOW(), 1, ?)");
                $stmt->bindValue(1, $residencial);
                $stmt->bindValue(2, $familiar_pessoa_id);
                $stmt->bindValue(3, $_SESSION['id']);
                $stmt->execute();
              }

              if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $familiar_pessoa_id, "AND hab_contato_tipo_id = 2"))) {
                //ATUALIZAR TIPO CELULAR
                $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_pessoa_id = ? AND hab_contato_tipo_id = 2");
                $stmt->bindValue(1, $celular);
                $stmt->bindValue(2, $_SESSION['id']);
                $stmt->bindValue(3, $familiar_pessoa_id);
                $stmt->execute();
              } else {
                //CADASTRAR TIPO CELULAR
                $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (2, ?, ?, NOW(), 1, ?)");
                $stmt->bindValue(1, $celular);
                $stmt->bindValue(2, $familiar_pessoa_id);
                $stmt->bindValue(3, $_SESSION['id']);
                $stmt->execute();
              }

              if (is_numeric(pesquisar_tabela("id", "hab_pessoa_contato", "hab_pessoa_id", "=", $familiar_pessoa_id, "AND hab_contato_tipo_id = 3"))) {
                //ATUALIZAR TIPO COMERCIAL
                $stmt = $db->prepare("UPDATE hab_pessoa_contato SET numero = ?, ramal = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_pessoa_id = ? AND hab_contato_tipo_id = 3");
                $stmt->bindValue(1, $comercial);
                $stmt->bindValue(2, $ramal);
                $stmt->bindValue(3, $_SESSION['id']);
                $stmt->bindValue(4, $familiar_pessoa_id);
                $stmt->execute();
              } else {
                //CADASTRAR TIPO COMERCIAL
                $stmt = $db->prepare("INSERT INTO hab_pessoa_contato (hab_contato_tipo_id, numero, ramal, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (3, ?, ?, ?, NOW(), 1, ?)");
                $stmt->bindValue(1, $comercial);
                $stmt->bindValue(2, $ramal);
                $stmt->bindValue(3, $familiar_pessoa_id);
                $stmt->bindValue(4, $_SESSION['id']);
                $stmt->execute();
              }

              if (is_numeric(pesquisar_tabela("id", "hab_ocupacao", "hab_pessoa_id", "=", $familiar_pessoa_id, ""))) {
                //ATUALIZAÇÃO DA OCUPAÇÃO DA PESSOA
                $stmt = $db->prepare("UPDATE hab_ocupacao SET instituicao = ?, endereco = ?, cargo = ?, data_inicio = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE hab_pessoa_id = ?");
                $stmt->bindValue(1, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $local_trabalho);
                $stmt->bindValue(2, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $trab_endereco);
                $stmt->bindValue(3, $trabalha == 1 ? $cargo_funcao : NULL);
                $stmt->bindValue(4, $trabalha == 1 ? convertDataBR2ISO($data_inicio) : NULL);
                $stmt->bindValue(5, $_SESSION['id']);
                $stmt->bindValue(6, $familiar_pessoa_id);
                $stmt->execute();
              } else {
                //CADASTRO DA OCUPAÇÃO DA PESSOA
                $stmt = $db->prepare("INSERT INTO hab_ocupacao (instituicao, endereco, cargo, data_inicio, seg_usuario_pai_id, hab_pessoa_id, status, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())");
                $stmt->bindValue(1, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $local_trabalho);
                $stmt->bindValue(2, $trab_mesmo_endereco == 1 || $trabalha == 0 ? NULL : $trab_endereco);
                $stmt->bindValue(3, $trabalha == 1 ? $cargo_funcao : NULL);
                $stmt->bindValue(4, $trabalha == 1 ? convertDataBR2ISO($data_inicio) : NULL);
                $stmt->bindValue(5, $_SESSION['id']);
                $stmt->bindValue(6, $familiar_pessoa_id);
                $stmt->execute();
              }

              //ATUALIZAÇÃO DE CÔNJUGE
              $stmt = $db->prepare("UPDATE hab_familiar SET hab_grau_parentesco_id = ?, data_update = NOW() WHERE id = ?");
              $stmt->bindValue(1, $grau_parentesco);
              $stmt->bindValue(2, $familiarId);
              $stmt->execute();

              //RENDA
              $stmt = $db->prepare("DELETE FROM hab_pessoa_renda WHERE hab_pessoa_id = ?");
              $stmt->bindValue(1, $familiar_pessoa_id);
              $stmt->execute();

              foreach ($tipo_renda AS $key => $val) {
                if ($val != "" && $renda_valor[$key] != "" && $val != 0) {
                  $stmt = $db->prepare("INSERT INTO hab_pessoa_renda (valor, hab_pessoa_id, hab_renda_tipo_id, data_cadastro, seg_usuario_pai_id, status) VALUES (?, ?, ?, NOW(), ?, 1)");
                  $stmt->bindValue(1, valorfloat($renda_valor[$key]));
                  $stmt->bindValue(2, $familiar_pessoa_id);
                  $stmt->bindValue(3, $val);
                  $stmt->bindValue(4, $_SESSION['id']);
                  $stmt->execute();
                }
              }

              $msg['retorno'] = 'Terceira etapa do cadastro atualizado com sucesso!';
            }
          } else {
            $msg['msg'] = 'error';
            $msg['retorno'] = $mensagem;
            echo json_encode($msg);
            exit();
          }
        }
      }
      //SE NÃO HOUVER NENHUM ERRO, ENTÃO ELE DAR UM COMMIT E ATRIBUÍ AS MENSAGENS DE SUCESSO
      if ($error == false) {
        $db->commit();
        //MENSAGEM DE SUCESSO
        $msg['id'] = $candidato_id;
        $msg['msg'] = 'success';
        echo json_encode($msg);
        exit();
      }
    }
  } else {

    $observacao = $_POST['observacao'];

    $result = $db->prepare("SELECT hab_pessoa_id, hab_candidato_id FROM hab_familiar WHERE hab_candidato_id = ?");
    $result->bindValue(1, $candidato_id);
    $result->execute();

    if ($result->rowCount() > 0) {
      while ($familiar = $result->fetch(PDO::FETCH_ASSOC)) {

        //REMOVENDO DADOS FAMILIAR
//        $stmt = $db->prepare("UPDATE hab_familiar SET status = 2, observacao = ? WHERE hab_pessoa_id  = ?");
//        $stmt->bindValue(1, $observacao);
//        $stmt->bindValue(2, $familiar['hab_pessoa_id']);
//        $stmt->execute();
        //REMOVENDO DADOS ESCOLARES
        $stmt = $db->prepare("DELETE FROM hab_pessoa_escolar WHERE hab_pessoa_id = ?");
        $stmt->bindValue(1, $familiar['hab_pessoa_id']);
        $stmt->execute();
        //REMOVENDO DADOS DE ENDEREÇO
        $stmt = $db->prepare("DELETE FROM hab_pessoa_endereco WHERE hab_pessoa_id = ?");
        $stmt->bindValue(1, $familiar['hab_pessoa_id']);
        $stmt->execute();
        //REMOVENDO DADOS DE CONTATO
        $stmt = $db->prepare("DELETE FROM hab_pessoa_contato WHERE hab_pessoa_id = ?");
        $stmt->bindValue(1, $familiar['hab_pessoa_id']);
        $stmt->execute();
        //REMOVENDO DADOS DE OCUPAÇÃO
        $stmt = $db->prepare("DELETE FROM hab_ocupacao WHERE hab_pessoa_id = ?");
        $stmt->bindValue(1, $familiar['hab_pessoa_id']);
        $stmt->execute();
        //REMOVENDO A PESSOA RENDA
        $stmt = $db->prepare("DELETE FROM hab_pessoa_renda WHERE hab_pessoa_id = ?");
        $stmt->bindValue(1, $familiar['hab_pessoa_id']);
        $stmt->execute();
        //REMOVENDO DADOS FAMILIAR
        $stmt = $db->prepare("DELETE FROM hab_familiar WHERE hab_candidato_id = ?");
        $stmt->bindValue(1, $familiar['hab_candidato_id']);
        $stmt->execute();
        //REMOVENDO DADOS PESSOA FAMILIAR
        $stmt = $db->prepare("DELETE FROM hab_pessoa WHERE id = ?");
        $stmt->bindValue(1, $familiar['hab_pessoa_id']);
        $stmt->execute();
      }
    }

    $db->commit();

    $msg['retorno'] = 'Terceira etapa do cadastro atualizado com sucesso!';

    //MENSAGEM DE SUCESSO
    $msg['id'] = $candidato_id;
    $msg['msg'] = 'success';
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar cadastrar ou atualizar a terceira etapa do formulário de candidato:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>