<?php

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: MAIOR ID
// DESCRIÇÃO: RETORNA AO MAIOR ID DA SITUAÇÃO DO CANDIDATO
function maior_id($candidato_id) {
  $db = Conexao::getInstance();
  $result = $db->prepare("SELECT MAX(id) AS maior_id
                                 FROM hab_candidato_situacao
                                 WHERE hab_candidato_id = ?");
  $result->bindValue(1, $candidato_id);
  $result->execute();
  $dados_candidato = $result->fetch(PDO::FETCH_ASSOC);
  return $dados_candidato['maior_id'];
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: CARREGAR CAMPOS COM FUNÇÕES
// DESCRIÇÃO: CARREGA AS FUNÇÕES PARA OS SEUS RESPECTIVOS CAMPOS
function carregar_campos_funcoes($key, $value) {

  $db = Conexao::getInstance();

  if ($key == "data_inicio_residencia_municipio" || $key == "casamento_data" || $key == "municipio_residencia_inicio" || $key == "mae_data_nascimento" || $key == "cie_data_expedicao" || $key == "cie_data_validade" || $key == "data_nascimento" || $key == "cnh_data_expedicao" || $key == "cnh_data_validade" || $key == "rg_data_expedicao") {
    return obterDataBRTimestamp($value);
  } else if ($key == "bsc_pele_cor_id") {
    return pesquisar_tabela("nome", "bsc_pele_cor", "id", "=", $value, "");
  } else if ($key == "bsc_pele_cor_id") {
    return pesquisar_tabela("nome", "bsc_pele_cor", "id", "=", $value, "");
  } else if ($key == "aluguel_valor") {
    return "R$ " . fdec($value);
  } else if ($key == "bsc_pele_cor_id") {
    return pesquisar_tabela("nome", "bsc_pele_cor", "id", "=", $value, "");
  } else if ($key == "morador_rua") {
    return $value == 1 ? "Morador de rua sim" : "Morador de rua não";
  } else if ($key == "area_risco_insalubre") {
    return $value == 1 ? "Área de risco insalubre sim" : "Área de risco insalubre não";
  } else if ($key == "bsc_nacionalidade_id") {
    return pesquisar_tabela("nome", "bsc_nacionalidade", "id", "=", $value, "");
  } else if ($key == "bsc_municipio_id_natural") {
    return pesquisar_tabela("nome", "bsc_municipio", "id", "=", $value, "");
  } else if ($key == "bsc_cie_classificacao_id") {
    return pesquisar_tabela("nome", "bsc_cie_classificacao", "id", "=", $value, "");
  } else if ($key == "bsc_deficiencia_tipo_id") {
    return pesquisar_tabela("nome", "bsc_deficiencia_tipo", "id", "=", $value, "");
  } else if ($key == "hab_cid10_capitulo_id") {
    return pesquisar_tabela("descricao", "hab_cid10_capitulo", "id", "=", $value, "");
  } else if ($key == "hab_cid10_categoria_id") {
    return pesquisar_tabela("descricao", "hab_cid10_categoria", "id", "=", $value, "");
  } else if ($key == "hab_cid10_grupo_id") {
    return pesquisar_tabela("descricao", "hab_cid10_grupo", "id", "=", $value, "");
  } else if ($key == "rg_uf_expedicao") {
    return pesquisar_tabela("nome", "bsc_estado", "id", "=", $value, "");
  } else if ($key == "deficiencia") {
    return $value == 1 ? "Deficiência sim" : "Deficiência não";
  } else if ($key == "doenca_cronica") {
    return $value == 1 ? "Doênça crônica sim" : "Doênça crônica não";
  } else if ($key == "lei_maria_penha") {
    return $value == 1 ? "Lei maria da penha sim" : "Lei maria da penha não";
  } else if ($key == "snch_apf_id") {
    return $value > 0 ? "Preferência por determinado empreendimento sim" : "Preferência por determinado empreendimento não";
  } else if ($key == "bsc_sexo_id") {
    return $value == 1 ? "Masculino" : "Feminino";
  } else if ($key == "hab_grau_escolar_id") {
    return pesquisar_tabela("nome", "hab_grau_escolar", "id", "=", $value, "");
  } else if ($key == "rg_orgao_expedicao") {
    return $value == 1 ? "SSP" : "DETRAN";
  } else if ($key == "cnh_uf_expedicao") {
    return pesquisar_tabela("nome", "bsc_estado", "id", "=", $value, "");
  } else if ($key == "provedor_lar") {
    return $value == 1 ? "Provedor do lar sim" : "Provedor do lar não";
  } else if ($key == "bsc_municipio_id") {
    return pesquisar_tabela("nome", "bsc_municipio", "id", "=", $value, "");
  } else if ($key == "bsc_endereco_tipo_id") {
    return pesquisar_tabela("nome", "bsc_endereco_tipo", "id", "=", $value, "");
  } else if ($key == "coabitacao_involuntaria") {
    return $value == 1 ? "Sim" : "Não";
  } else if ($key == "hab_contato_tipo_id") {
    return "Tipo de Contato " . pesquisar_tabela("nome", "hab_contato_tipo", "id", "=", $value, "");
  } else if ($key == "bsc_estado_civil_id") {
    return pesquisar_tabela("nome", "bsc_estado_civil", "id", "=", $value, "");
  } else if ($key == "situacao") {
    return pesquisar_tabela("nome", "hab_tipo_situacao", "id", "=", $value, "");
  } else {
    return $value;
  }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: OUTRAS ALTERAÇÕES
// DESCRIÇÃO: RETORNA OUTRAS ALTERAÇÕES REALIZADAS
function outras_alteracoes_realizadas($candidato_id, $data_update) {
  $db = Conexao::getInstance();

  $alteracoes = "<br/><b>Atualizações:</b> ";

  if ($data_update != NULL) {
    //DADOS DE HISTÓRICO DO CANDIDATO ----------------------------------------------------------------
    $result = $db->prepare("SELECT hc.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa WHERE id = hc.hab_pessoa_id AND hist_data_cadastro = ?)");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_historico_candidato = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE HISTÓRICO DO CANDIDATO ANTERIOR ----------------------------------------------------------------
    $result = $db->prepare("SELECT hc.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_pessoa WHERE id = hc.hab_pessoa_id AND hist_data_cadastro = ?)
                          GROUP BY hist_id");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_historico_candidato_anterior = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE HISTÓRICO DA PESSOA ----------------------------------------------------------------
    $result = $db->prepare("SELECT hp.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa WHERE id = hc.hab_pessoa_id AND hist_data_cadastro = ?)");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_historico_pessoa = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE HISTÓRICO DO CANDIDATO ANTERIOR ----------------------------------------------------------------
    $result = $db->prepare("SELECT hp.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_pessoa WHERE id = hc.hab_pessoa_id AND hist_data_cadastro = ?)
                          GROUP BY hist_id");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_historico_pessoa_anterior = $result->fetch(PDO::FETCH_ASSOC);

//DADOS DE ENDEREÇO ANTERIOR ----------------------------------------------------------------------------------------------------------------------------------------
    $result = $db->prepare("SELECT hpe.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa_endereco AS hpe ON hpe.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hpe.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa_endereco WHERE hab_pessoa_id = hc.hab_pessoa_id AND hist_data_cadastro = ?)");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_endereco_anterior = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE HISTÓRICO DE ENDEREÇO ANTERIOR ------------------------------------------------
    $result = $db->prepare("SELECT hpe.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa_endereco AS hpe ON hpe.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hpe.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_pessoa_endereco WHERE hab_pessoa_id = hc.hab_pessoa_id AND hist_data_cadastro = ?)");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_historico_endereco_anterior = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE ANEXO ANTERIOR ----------------------------------------------------------------------------------------------------------------------------------------
    $result = $db->prepare("SELECT hpa.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa_anexo AS hpa ON hpa.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hpa.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa_anexo WHERE hab_pessoa_id = hc.hab_pessoa_id AND hist_data_cadastro = ?)
                          GROUP BY hpa.hab_pessoa_id");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_anexo = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE HISTÓRICO DO ANEXO ANTERIOR ----------------------------------------------------------------------------------------------------------------------------------------
    $result = $db->prepare("SELECT hpa.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa_anexo AS hpa ON hpa.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hpa.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_pessoa_anexo WHERE hab_pessoa_id = hc.hab_pessoa_id AND hist_data_cadastro = ?)
                          GROUP BY hpa.hab_pessoa_id");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_historico_anexo = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE CONTATO ----------------------------------------------------------------------------------------------------------------------------------------
    $result = $db->prepare("SELECT hpc.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa_contato AS hpc ON hpc.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hpc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa_contato WHERE hab_pessoa_id = hc.hab_pessoa_id AND hist_data_cadastro = ?)
                          GROUP BY hpc.hab_pessoa_id");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_contato = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE HISTÓRICO DE CONTATO ----------------------------------------------------------------------------------------------------------------------------------------
    $result = $db->prepare("SELECT hpc.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa_contato AS hpc ON hpc.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hpc.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_pessoa_contato WHERE hab_pessoa_id = hc.hab_pessoa_id AND hist_data_cadastro = ?)
                          GROUP BY hpc.hab_pessoa_id");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_historico_contato = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE CÔNJUGE ----------------------------------------------------------------------------------------------------------------------------------------
    $result = $db->prepare("SELECT hp.*
                          FROM hist_hab_conjuge hco
                          LEFT JOIN hist_hab_candidato AS hc ON hc.id = hco.hab_candidato_id
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hco.hab_pessoa_id_conjuge
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_conjuge WHERE id = ? AND hist_data_cadastro = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa WHERE id = hc.hab_pessoa_id AND hist_data_cadastro = ?)");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_conjuge = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE HISTÓRICO DO CÔNJUGE ------------------------------------------------
    $result = $db->prepare("SELECT hp.*
                          FROM hist_hab_conjuge hco
                          LEFT JOIN hist_hab_candidato AS hc ON hc.id = hco.hab_candidato_id
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hco.hab_pessoa_id_conjuge
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_conjuge WHERE id = ? AND hist_data_cadastro = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_pessoa WHERE id = hc.hab_pessoa_id AND hist_data_cadastro = ?)");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_historico_conjuge = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE GRUPO FAMILIAR ----------------------------------------------------------------------------------------------------------------------------------------
    $result = $db->prepare("SELECT hp.*
                          FROM hist_hab_familiar hf
                          LEFT JOIN hist_hab_candidato AS hc ON hc.id = hf.hab_candidato_id
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hf.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_familiar WHERE id = ? AND hist_data_cadastro = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa WHERE id = hc.hab_pessoa_id AND hist_data_cadastro = ?)");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_familiar = $result->fetch(PDO::FETCH_ASSOC);

    //DADOS DE HISTÓRICO DO GRUPO FAMILIAR ------------------------------------------------
    $result = $db->prepare("SELECT hp.*
                          FROM hist_hab_familiar hf
                          LEFT JOIN hist_hab_candidato AS hc ON hc.id = hf.hab_candidato_id
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hf.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_familiar WHERE id = ? AND hist_data_cadastro = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id - 1) as id FROM hist_hab_pessoa WHERE id = hc.hab_pessoa_id AND hist_data_cadastro = ?)");
    $result->bindValue(1, $candidato_id);
    $result->bindValue(2, $candidato_id);
    $result->bindValue(3, $data_update);
    $result->bindValue(4, $data_update);
    $result->execute();

    $dados_historico_familiar = $result->fetch(PDO::FETCH_ASSOC);

//---------------------------------------------------------------------------------------------------------------------------------------------------------    
//VERIFICA ALTERAÇÕES NOS DADOS DO CANDIDATO
    if (isset($dados_historico_candidato['id']) && isset($dados_historico_candidato_anterior['id'])) {
      foreach ($dados_historico_candidato AS $key => $value) {
        if ($value != $dados_historico_candidato_anterior[$key]) {
          if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
            $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_candidato_anterior[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-pessoal'>$key</a>";
          }
        }
      }
    }
//VERIFICA ALTERAÇÕES NOS DADOS DA PESSOA
    if (isset($dados_historico_pessoa['id']) && $dados_historico_pessoa_anterior['id']) {
      foreach ($dados_historico_pessoa AS $key => $value) {
        if ($value != $dados_historico_pessoa_anterior[$key]) {
          if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
            $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_pessoa_anterior[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-pessoal'>$key</a>";
          }
        }
      }
    }
//VERIFICA ALTERAÇÕES NOS DADOS DA PESSOA ENDEREÇO
    if (isset($dados_endereco_anterior['id']) && isset($dados_historico_endereco_anterior['id'])) {
      foreach ($dados_endereco_anterior AS $key => $value) {
        if ($value != $dados_historico_endereco_anterior[$key]) {
          if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
            $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_endereco_anterior[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-endereco'>$key</a>";
          }
        }
      }
    }
  }


  //VERIFICA ALTERAÇÕES NOS DADOS DE CONTATO
  if (isset($dados_contato['id']) && isset($dados_historico_contato['id'])) {
    foreach ($dados_contato AS $key => $value) {
      if ($value != $dados_historico_contato[$key]) {
        if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
          $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_contato[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-contato'>$key</a>";
        }
      }
    }
  }

//VERIFICA ALTERAÇÕES NOS DADOS DO CÔNJUGE
  if (isset($dados_conjuge['id']) && isset($dados_historico_conjuge['id'])) {
    foreach ($dados_conjuge AS $key => $value) {
      if ($value != $dados_historico_conjuge[$key]) {
        if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
          $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_conjuge[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-conjuge'>$key</a>";
        }
      }
    }
  }
//VERIFICA ALTERAÇÕES NOS DADOS DO GRUPO FAMILIAR
  if (isset($dados_familiar['id']) && isset($dados_historico_familiar['id'])) {
    foreach ($dados_familiar AS $key => $value) {
      if ($value != $dados_historico_familiar[$key]) {
        if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
          $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_familiar[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-familiar'>$key</a>";
        }
      }
    }
  }
//VERIFICA ALTERAÇÕES NOS ANEXOS
  if (isset($dados_anexo['id']) && isset($dados_historico_anexo['id'])) {
    foreach ($dados_anexo AS $key => $value) {
      if ($value != $dados_historico_anexo[$key]) {
        if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
          $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_anexo[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-anexo'>$key</a>";
        }
      }
    }
  }

  return $alteracoes;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: ATUALIZAÇÕES REALIZADAS
// DESCRIÇÃO: RETORNA AS ALTERAÇÕES QUE ESTÃO SENDO REALIZADAS NO CADASTRO DE CANDIDATO
function alteracoes_realizadas($candidato_id) {

  $db = Conexao::getInstance();

  $alteracoes = "<br/><b>Atualizações:</b> ";

  //DADOS DO CANDIDATO -----------------------------------------------------------------------------------------------------------------------------------
  $result = $db->prepare("SELECT hc.*
                          FROM hab_candidato hc
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          WHERE hc.id = ?");
  $result->bindValue(1, $candidato_id);
  $result->execute();

  $dados_candidato = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE HISTÓRICO DO CANDIDATO ---------------------------------------------
  $result = $db->prepare("SELECT hc.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa WHERE id = hc.hab_pessoa_id AND hist_data_cadastro = ?)");
  $result->bindValue(1, $candidato_id);
  $result->bindValue(2, $candidato_id);
  $result->bindValue(3, $dados_candidato['data_update']);
  $result->bindValue(4, $dados_candidato['data_update']);
  $result->execute();

  $dados_historico_candidato = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DA PESSOA ----------------------------------------------------------------------------------------------------------------------------------------
  $result = $db->prepare("SELECT hp.*
                          FROM hab_candidato hc
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          WHERE hc.id = ?");
  $result->bindValue(1, $candidato_id);
  $result->execute();

  $dados_pessoa = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE HISTÓRICO DA PESSOA ------------------------------------------------
  $result = $db->prepare("SELECT hp.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa WHERE id = hc.hab_pessoa_id AND hist_data_cadastro = ?)");
  $result->bindValue(1, $candidato_id);
  $result->bindValue(2, $candidato_id);
  $result->bindValue(3, $dados_candidato['data_update']);
  $result->bindValue(4, $dados_candidato['data_update']);
  $result->execute();

  $dados_historico_pessoa = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE ENDEREÇO ----------------------------------------------------------------------------------------------------------------------------------------
  $result = $db->prepare("SELECT hpe.*
                          FROM hab_candidato hc
                          LEFT JOIN hab_pessoa_endereco AS hpe ON hpe.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ?");
  $result->bindValue(1, $candidato_id);
  $result->execute();

  $dados_endereco = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE HISTÓRICO DE ENDEREÇO ------------------------------------------------
  $result = $db->prepare("SELECT hpe.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa_endereco AS hpe ON hpe.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_candidato WHERE id = ? AND hist_data_cadastro = ?)
                          AND hpe.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa_endereco WHERE hab_pessoa_id = hc.hab_pessoa_id AND hist_data_cadastro = ?)");
  $result->bindValue(1, $candidato_id);
  $result->bindValue(2, $candidato_id);
  $result->bindValue(3, $dados_candidato['data_update']);
  $result->bindValue(4, $dados_candidato['data_update']);
  $result->execute();

  $dados_historico_endereco = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE ANEXO ----------------------------------------------------------------------------------------------------------------------------------------
  $result = $db->prepare("SELECT hpa.*
                          FROM hab_candidato hc
                          LEFT JOIN hab_pessoa_anexo AS hpa ON hpa.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ?
                          GROUP BY hpa.hab_pessoa_id");
  $result->bindValue(1, $candidato_id);
  $result->execute();

  $dados_anexo = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE HISTÓRICO DO ANEXO ----------------------------------------------------------------------------------------------------------------------------------------
  $result = $db->prepare("SELECT hpa.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa_anexo AS hpa ON hpa.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_candidato WHERE id = ?)
                          AND hpa.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa_anexo WHERE hab_pessoa_id = hc.hab_pessoa_id)
                          GROUP BY hpa.hab_pessoa_id");
  $result->bindValue(1, $candidato_id);
  $result->bindValue(2, $candidato_id);
  $result->execute();

  $dados_historico_anexo = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE CONTATO ----------------------------------------------------------------------------------------------------------------------------------------
  $result = $db->prepare("SELECT hpc.*
                          FROM hab_candidato hc
                          LEFT JOIN hab_pessoa_contato AS hpc ON hpc.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ?
                          GROUP BY hpc.hab_pessoa_id");
  $result->bindValue(1, $candidato_id);
  $result->execute();

  $dados_contato = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE HISTÓRICO DE CONTATO ----------------------------------------------------------------------------------------------------------------------------------------
  $result = $db->prepare("SELECT hpc.*
                          FROM hist_hab_candidato hc
                          LEFT JOIN hist_hab_pessoa_contato AS hpc ON hpc.hab_pessoa_id = hc.hab_pessoa_id
                          WHERE hc.id = ? AND hc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_candidato WHERE id = ?)
                          AND hpc.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa_contato WHERE hab_pessoa_id = hc.hab_pessoa_id)
                          GROUP BY hpc.hab_pessoa_id");
  $result->bindValue(1, $candidato_id);
  $result->bindValue(2, $candidato_id);
  $result->execute();

  $dados_historico_contato = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE CÔNJUGE ----------------------------------------------------------------------------------------------------------------------------------------
  $result = $db->prepare("SELECT hp.*
                          FROM hab_conjuge hco
                          LEFT JOIN hab_candidato AS hc ON hc.id = hco.hab_candidato_id
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hco.hab_pessoa_id_conjuge
                          WHERE hc.id = ?");
  $result->bindValue(1, $candidato_id);
  $result->execute();

  $dados_conjuge = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE HISTÓRICO DO CÔNJUGE ------------------------------------------------
  $result = $db->prepare("SELECT hp.*
                          FROM hist_hab_conjuge hco
                          LEFT JOIN hist_hab_candidato AS hc ON hc.id = hco.hab_candidato_id
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hco.hab_pessoa_id_conjuge
                          WHERE hc.id = ? AND hco.hist_id = (SELECT MAX(hhc.hist_id) as id FROM hist_hab_conjuge hhc LEFT JOIN hist_hab_candidato AS hca ON hca.id = hhc.hab_candidato_id WHERE hca.id = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa WHERE id = hco.hab_pessoa_id_conjuge)
                          GROUP BY hp.id");
  $result->bindValue(1, $candidato_id);
  $result->bindValue(2, $candidato_id);
  $result->execute();

  $dados_historico_conjuge = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE GRUPO FAMILIAR ----------------------------------------------------------------------------------------------------------------------------------------
  $result = $db->prepare("SELECT hp.*
                          FROM hab_familiar hf
                          LEFT JOIN hab_candidato AS hc ON hc.id = hf.hab_candidato_id
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hf.hab_pessoa_id
                          WHERE hc.id = ?");
  $result->bindValue(1, $candidato_id);
  $result->execute();

  $dados_familiar = $result->fetch(PDO::FETCH_ASSOC);

  //DADOS DE HISTÓRICO DO GRUPO FAMILIAR ------------------------------------------------
  $result = $db->prepare("SELECT hp.*
                          FROM hist_hab_familiar hf
                          LEFT JOIN hist_hab_candidato AS hc ON hc.id = hf.hab_candidato_id
                          LEFT JOIN hist_hab_pessoa AS hp ON hp.id = hf.hab_pessoa_id
                          WHERE hc.id = ? AND hf.hist_id = (SELECT MAX(hhf.hist_id) as id FROM hist_hab_familiar hhf LEFT JOIN hist_hab_candidato AS hca ON hca.id = hhf.hab_candidato_id WHERE hca.id = ?)
                          AND hp.hist_id = (SELECT MAX(hist_id) as id FROM hist_hab_pessoa WHERE id = hf.hab_pessoa_id)
                          GROUP BY hp.id");
  $result->bindValue(1, $candidato_id);
  $result->bindValue(2, $candidato_id);
  $result->execute();

  $dados_historico_familiar = $result->fetch(PDO::FETCH_ASSOC);

//---------------------------------------------------------------------------------------------------------------------------------------------------------
//VERIFICA ALTERAÇÕES NOS DADOS DO CANDIDATO
  if (isset($dados_candidato['id']) && isset($dados_historico_candidato['id'])) {
    foreach ($dados_candidato AS $key => $value) {
      if ($value != $dados_historico_candidato[$key]) {
        if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
          $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_candidato[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-pessoal'>$key</a>";
        }
      }
    }
  }
//VERIFICA ALTERAÇÕES NOS DADOS DA PESSOA
  if (isset($dados_pessoa['id']) && isset($dados_historico_pessoa['id'])) {
    foreach ($dados_pessoa AS $key => $value) {
      if ($value != $dados_historico_pessoa[$key]) {
        if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
          $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_pessoa[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-pessoal'>$key</a>";
        }
      }
    }
  }
//VERIFICA ALTERAÇÕES NOS DADOS DA PESSOA ENDEREÇO
  if (isset($dados_endereco['id']) && isset($dados_historico_endereco['id'])) {
    foreach ($dados_endereco AS $key => $value) {
      if ($value != $dados_historico_endereco[$key]) {
        if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
          $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_endereco[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-endereco'>$key</a>";
        }
      }
    }
  }

  //VERIFICA ALTERAÇÕES NOS DADOS DE CONTATO
  if (isset($dados_contato['id']) && isset($dados_historico_contato['id'])) {
    foreach ($dados_contato AS $key => $value) {
      if ($value != $dados_historico_contato[$key]) {
        if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
          $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_contato[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-contato'>$key</a>";
        }
      }
    }
  }

//VERIFICA ALTERAÇÕES NOS DADOS DO CÔNJUGE
  if (isset($dados_conjuge['id']) && isset($dados_historico_conjuge['id'])) {
    foreach ($dados_conjuge AS $key => $value) {
      if ($value != $dados_historico_conjuge[$key]) {
        if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
          $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_conjuge[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-conjuge'>$key</a>";
        }
      }
    }
  }
//VERIFICA ALTERAÇÕES NOS DADOS DO GRUPO FAMILIAR
  if (isset($dados_familiar['id']) && isset($dados_historico_familiar['id'])) {
    foreach ($dados_familiar AS $key => $value) {
      if ($value != $dados_historico_familiar[$key]) {
        if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
          $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_familiar[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-familiar'>$key</a>";
        }
      }
    }
  }
//VERIFICA ALTERAÇÕES NOS ANEXOS
  if (isset($dados_anexo['id']) && isset($dados_historico_anexo['id'])) {
    foreach ($dados_anexo AS $key => $value) {
      if ($value != $dados_historico_anexo[$key]) {
        if ($key != "data_update" && $key != "data_cadastro" && $key != "hist_data_cadastro" && $key != "hist_id" && $key != "id") {
          $alteracoes .= "<a id='atualizar_modal' novo='" . carregar_campos_funcoes($key, $value) . "' anterior='" . carregar_campos_funcoes($key, $dados_historico_anexo[$key]) . "' href='#modalDefault2' data-toggle='modal' class='bl bl-anexo'>$key</a>";
        }
      }
    }
  }

  return $alteracoes;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICAR PENDÊNCIAS PARA CORREÇÃO
// DESCRIÇÃO: VERIFICA SE O CANDIDATO POSSUÍ ALGUM PENDÊNCIA A SER CORRIGIDA
function vf_pendencias() {

  $db = Conexao::getInstance();

  $qtd = 0;

  $result = $db->prepare("SELECT id, hab_candidato_id, hab_tipo_situacao_id
                          FROM hab_candidato_situacao
			                       WHERE id IN (SELECT MAX(id) FROM hab_candidato_situacao GROUP BY hab_candidato_id)
                          GROUP BY hab_candidato_id");
  $result->execute();

  while ($situacao = $result->fetch(PDO::FETCH_ASSOC)) {
    if ($situacao['hab_tipo_situacao_id'] == 4) {
      if (pesquisar_tabela("seg_usuario_pai_id", "hab_candidato_situacao", "hab_candidato_id", "=", $situacao['hab_candidato_id'], "AND hab_tipo_situacao_id = 2") == $_SESSION['id']) {
        $qtd++;
      }
    }
  }

  return $qtd;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: QUANTIDADE DE ERROS DA VALIDAÇÃO
// DESCRIÇÃO: VERIFICA AS QUANTIDADES DE ERROS PASSADOS PELA VALIDAÇÃO
function qtd_erros_validados($erros) {

  $qtd = 0;

  for ($i = 0; $i < strlen($erros); $i++) {

    if (isset($erros[$i + 1]) && isset($erros[$i + 2])) {
      if ($erros[$i] . $erros[$i + 1] . $erros[$i + 2] == "<b>") {
        $qtd++;
      }
    }
  }
  return $qtd;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: VERIFICAR RETROATIVO
// DESCRIÇÃO: VERIFICA SE O CANDIDATO POSSUÍ UM CADASTRO ANTIGO OU NÃO
function vf_retroativo($candidato_id) {

  $db = Conexao::getInstance();

  $result = $db->prepare("SELECT hp.cadastro_retroativo_ano
                          FROM hab_candidato hc
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          WHERE hc.id = ?");
  $result->bindValue(1, $candidato_id);
  $result->execute();

  $dados_candidato = $result->fetch(PDO::FETCH_ASSOC);

  if (is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
    return true;
  } else {
    return false;
  }
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
// NOME: VERIFICAR LOGIN DE USUÁRIO NO SITE
// DESCRIÇÃO: VERIFICA SE O USUÁRIO ESTÁ LOGADO NO SITE
function vf_usuario_login_site() {
  if (!isset($_SESSION['hab_candidato_id'])) {
    echo "<script>window.location = '" . PORTAL_URL . "hab/view/site/home';</script>";
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
// NOME: STATUS CASA
// DESCRIÇÃO: STATUS ATIVO OU INATIVO OU SORTEADO
function status_casa($id) {
  if ($id == 0) {
    return 'Não';
  } else if ($id == 1) {
    return 'Sim';
  } else if ($id == 2) {
    return 'Sorteado';
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

?>