<?php
$result = $db->prepare("SELECT hc.participar_programa_id, sl.nome AS snch_loteamento, hc.validacao, snch.nome AS snch_apf, hc.snch_apf_id, hp.trab_mesmo_endereco, hc.area_risco_insalubre, hpe.latitude, hpe.longitude, hc.data_cadastro, hc.data_cadastro_anterior, hc.acompanhamento_socio_assistencial, hc.morador_rua, hpe.coabitacao_involuntaria, hpe.aluguel_valor, hpe.aluguel_social, hpe.alugada, blog.nome AS bsc_logradouro, bet.nome AS bsc_endereco_tipo, bdt.nome AS bsc_deficiencia_tipo, hp.casamento_data,
                          hp.lei_maria_penha, hp.data_inicio_residencia_municipio, hp.doenca_cronica, hp.mae_nome, hp.mae_data_nascimento,
                          hp.nis, hp.uniao_estavel, hp.bsc_estado_civil_id AS estado_civil_id, ec.nome AS estado_civil, hcp.hab_subprograma_id, hpro.nome AS programa, hsub.nome AS subprograma,
                          hpes.hab_financia_natureza_id, hpso.nome AS hab_programa_social, hpes.bolsa_percentual, hin.nome AS hab_instituicao_natureza, hpes.instituicao_nome,
                          hpes.serie_periodo, hp.endereco_candidato, hcc.catinic, hcc.catfim, hcc.descricao AS hab_cid10_capitulo, hcg.catinic AS catinicg, hcg.catfim AS catfimg, hcg.descricao AS hab_cid10_grupo, hcca.cat, hcca.descricao AS hab_cid10_categoria, hp.cadastro_unico,
                          hp.deficiencia, hges.nome AS hab_grau_escolar, ho.instituicao, ho.endereco, ho.cargo, ho.data_inicio ,hp.provedor_lar, hp.uniao_estavel,
                          hp.bsc_estado_civil_id, hp.cie_data_validade, hp.cie_data_expedicao, hp.cie_rne, bcc.nome AS bsc_cie_classificacao, hp.bsc_municipio_id_natural,
                          hp.rg_numero, hp.rg_orgao_expedicao_id, best.nome AS rg_uf_expedicao, hp.rg_data_expedicao, hp.cnh_numero, cnhest.nome AS cnh_uf_expedicao, hp.cnh_data_validade,
                          hp.cnh_data_expedicao, hp.id AS pessoa_id, hp.email, hpe.complemento, hpe.lote, hpe.quadra, hpe.bairro, hpe.numero, hpe.logradouro,
                          hpe.cep, hc.id, hp.nome, hp.cpf, bmun.nome AS bsc_municipio, hp.bsc_nacionalidade_id, hp.cadastro_retroativo_ano, bpc.nome AS cor, hp.bsc_pele_cor_id, hp.data_nascimento,
                          hp.bsc_sexo_id, hpes.hab_instituicao_natureza_id
                          FROM hab_candidato hc
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          LEFT JOIN hab_pessoa_endereco AS hpe ON hpe.hab_pessoa_id = hp.id
                          LEFT JOIN hab_candidato_programa AS hcp ON hcp.hab_candidato_id = hc.id
                          LEFT JOIN hab_especificidade AS hsub ON hsub.id = hcp.hab_subprograma_id
                          LEFT JOIN hab_origem_demanda AS hpro ON hpro.id = hsub.hab_programa_id
                          LEFT JOIN hab_ocupacao AS ho ON ho.hab_pessoa_id = hc.hab_pessoa_id
                          LEFT JOIN hab_pessoa_escolar AS hpes ON hpes.hab_pessoa_id = hc.hab_pessoa_id
                          LEFT JOIN hab_instituicao_natureza AS hin ON hin.id = hpes.hab_instituicao_natureza_id
                          LEFT JOIN hab_programa_social AS hpso ON hpso.id = hpes.hab_programa_social_id
                          LEFT JOIN bsc_pele_cor AS bpc ON bpc.id = hp.bsc_pele_cor_id
                          LEFT JOIN bsc_estado_civil AS ec ON ec.id = hp.bsc_estado_civil_id 
                          LEFT JOIN bsc_estado AS best ON best.id = hp.rg_uf_expedicao
                          LEFT JOIN bsc_estado AS cnhest ON cnhest.id = hp.cnh_uf_expedicao
                          LEFT JOIN bsc_cie_classificacao AS bcc ON bcc.id = hp.bsc_cie_classificacao_id
                          LEFT JOIN bsc_endereco_tipo AS bet ON bet.id = hpe.bsc_endereco_tipo_id
                          LEFT JOIN bsc_municipio AS bmun ON bmun.id = hpe.bsc_municipio_id
                          LEFT JOIN bsc_logradouro_tipo AS blog ON blog.id = hpe.bsc_logradouro_id
                          LEFT JOIN hab_grau_escolar AS hges ON hges.id = hp.hab_grau_escolar_id
                          LEFT JOIN bsc_deficiencia_tipo AS bdt ON bdt.id = hp.bsc_deficiencia_tipo_id
                          LEFT JOIN hab_cid10_capitulo AS hcc ON hcc.id = hp.hab_cid10_capitulo_id
                          LEFT JOIN hab_cid10_grupo AS hcg ON hcg.id = hp.hab_cid10_grupo_id
                          LEFT JOIN hab_cid10_categoria AS hcca ON hcca.id = hp.hab_cid10_categoria_id
                          LEFT JOIN snch_apf AS snch ON snch.id = hc.snch_apf_id
                          LEFT JOIN snch_loteamento AS sl ON sl.id = hc.loteamento_id
                          WHERE hc.id = ?");
$result->bindValue(1, $id);
$result->execute();

$dados_candidato = $result->fetch(PDO::FETCH_ASSOC);

// NOME FAMILIARES
$stmt = $db->prepare("SELECT hf.id, hf.hab_candidato_id, hp.nome, hp.cpf
         FROM hab_pessoa AS hp, hab_familiar AS hf
         WHERE hf.hab_pessoa_id = hp.id
         ORDER BY UPPER(hp.nome) ASC");
$stmt->execute();
$rsFamiliarNome = $stmt->fetchAll(PDO::FETCH_ASSOC);

// NOME CONJUGE
$stmt = $db->prepare("SELECT hc.id, hc.hab_candidato_id, hp.nome, hp.cpf
         FROM hab_pessoa AS hp, hab_conjuge AS hc
         WHERE hc.hab_pessoa_id_conjuge = hp.id
         ORDER BY UPPER(hp.nome) ASC");
$stmt->execute();
$rsConjugeNome = $stmt->fetchAll(PDO::FETCH_ASSOC);

// CANDIDATOS
$stmt = $db->prepare("SELECT hc.id, hp.nome, hp.cpf
         FROM hab_pessoa hp
         LEFT JOIN hab_candidato AS hc ON hc.hab_pessoa_id = hp.id
         ORDER BY UPPER(hp.nome) ASC");
$stmt->execute();
$rsPessoasNome = $stmt->fetchAll(PDO::FETCH_ASSOC);

// NOME BAIRROS
$stmt = $db->prepare("SELECT pe.id, pe.bairro, pe.status 
                      FROM hab_candidato AS c 
                      LEFT JOIN hab_pessoa AS p ON p.id = c.hab_pessoa_id 
                      LEFT JOIN hab_pessoa_endereco AS pe ON pe.hab_pessoa_id = p.id 
                      WHERE 1 = 1 AND pe.bairro IS NOT NULL AND pe.bairro NOT LIKE '' 
                      GROUP BY UPPER(pe.bairro) 
                      ORDER BY UPPER(pe.bairro) ASC");
$stmt->execute();
$rsBairrosAcre = $stmt->fetchAll(PDO::FETCH_ASSOC);

$candidato_id = $id;
$pessoa_id = pesquisar_tabela("hab_pessoa_id", "hab_candidato", "id", "=", $candidato_id, "");
$bcp = pesquisar_tabela("id", "hab_pessoa_beneficio", "hab_pessoa_id", "=", $pessoa_id, "AND hab_beneficio_social_id = 2");
$bolsa_familia = pesquisar_tabela("id", "hab_pessoa_beneficio", "hab_pessoa_id", "=", $pessoa_id, "AND hab_beneficio_social_id = 3");

// CONJUGE------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$conjuge_id = pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $id, "AND tipo = 0");

$result = $db->prepare("SELECT hp.trab_mesmo_endereco, hpe.latitude, hpe.longitude, hc.data_cadastro, hpe.coabitacao_involuntaria, hpe.aluguel_valor, hpe.aluguel_social, hpe.alugada, blog.nome AS bsc_logradouro, bet.nome AS bsc_endereco_tipo, bdt.nome AS bsc_deficiencia_tipo, hp.casamento_data,
                          hp.lei_maria_penha, hp.data_inicio_residencia_municipio, hp.doenca_cronica, hp.mae_nome, hp.mae_data_nascimento,
                          hp.nis, hp.uniao_estavel, hp.bsc_estado_civil_id AS estado_civil_id, ec.nome AS estado_civil,
                          hpes.hab_financia_natureza_id, hpso.nome AS hab_programa_social, hpes.bolsa_percentual, hin.nome AS hab_instituicao_natureza, hpes.instituicao_nome,
                          hpes.serie_periodo, hp.endereco_candidato, hcc.catinic, hcc.catfim, hcc.descricao AS hab_cid10_capitulo, hcg.catinic AS catinicg, hcg.catfim AS catfimg, hcg.descricao AS hab_cid10_grupo, hcca.cat, hcca.descricao AS hab_cid10_categoria, hp.cadastro_unico,
                          hp.deficiencia, hges.nome AS hab_grau_escolar, ho.instituicao, ho.endereco, ho.cargo, ho.data_inicio ,hp.provedor_lar, hp.uniao_estavel,
                          hp.bsc_estado_civil_id, hp.cie_data_validade, hp.cie_data_expedicao, hp.cie_rne, bcc.nome AS bsc_cie_classificacao, hp.bsc_municipio_id_natural,
                          hp.rg_numero, hp.rg_orgao_expedicao_id, best.nome AS rg_uf_expedicao, hp.rg_data_expedicao, hp.cnh_numero, cnhest.nome AS cnh_uf_expedicao, hp.cnh_data_validade,
                          hp.cnh_data_expedicao, hp.id AS pessoa_id, hp.email, hpe.complemento, hpe.lote, hpe.quadra, hpe.bairro, hpe.numero, hpe.logradouro,
                          hpe.cep, hc.id, hp.nome, hp.cpf, bmun.nome AS bsc_municipio, hp.bsc_nacionalidade_id, hp.cadastro_retroativo_ano, bpc.nome AS cor, hp.bsc_pele_cor_id, hp.data_nascimento,
                          hp.bsc_sexo_id, hpes.hab_instituicao_natureza_id
                          FROM hab_conjuge hc
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id_conjuge
                          LEFT JOIN hab_pessoa_endereco AS hpe ON hpe.hab_pessoa_id = hp.id
                          LEFT JOIN hab_ocupacao AS ho ON ho.hab_pessoa_id = hc.hab_pessoa_id_conjuge
                          LEFT JOIN hab_pessoa_escolar AS hpes ON hpes.hab_pessoa_id = hc.hab_pessoa_id_conjuge
                          LEFT JOIN hab_instituicao_natureza AS hin ON hin.id = hpes.hab_instituicao_natureza_id
                          LEFT JOIN hab_programa_social AS hpso ON hpso.id = hpes.hab_programa_social_id
                          LEFT JOIN bsc_pele_cor AS bpc ON bpc.id = hp.bsc_pele_cor_id
                          LEFT JOIN bsc_estado_civil AS ec ON ec.id = hp.bsc_estado_civil_id 
                          LEFT JOIN bsc_estado AS best ON best.id = hp.rg_uf_expedicao
                          LEFT JOIN bsc_estado AS cnhest ON cnhest.id = hp.cnh_uf_expedicao
                          LEFT JOIN bsc_cie_classificacao AS bcc ON bcc.id = hp.bsc_cie_classificacao_id
                          LEFT JOIN bsc_endereco_tipo AS bet ON bet.id = hpe.bsc_endereco_tipo_id
                          LEFT JOIN bsc_municipio AS bmun ON bmun.id = hpe.bsc_municipio_id
                          LEFT JOIN bsc_logradouro_tipo AS blog ON blog.id = hpe.bsc_logradouro_id
                          LEFT JOIN hab_grau_escolar AS hges ON hges.id = hp.hab_grau_escolar_id
                          LEFT JOIN bsc_deficiencia_tipo AS bdt ON bdt.id = hp.bsc_deficiencia_tipo_id
                          LEFT JOIN hab_cid10_capitulo AS hcc ON hcc.id = hp.hab_cid10_capitulo_id
                          LEFT JOIN hab_cid10_grupo AS hcg ON hcg.id = hp.hab_cid10_grupo_id
                          LEFT JOIN hab_cid10_categoria AS hcca ON hcca.id = hp.hab_cid10_categoria_id
                          WHERE hc.id = ?");
$result->bindValue(1, $conjuge_id);
$result->execute();

$dados_conjuge = $result->fetch(PDO::FETCH_ASSOC);

$conjuge_pessoa_id = pesquisar_tabela("hab_pessoa_id_conjuge", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "");

$result = $db->prepare("SELECT hco.numero, hp.nome, hp.cpf
                          FROM hab_conjuge hc
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id_conjuge
                          LEFT JOIN hab_pessoa_contato AS hco ON hco.hab_pessoa_id = hc.hab_pessoa_id_conjuge
                          WHERE hc.hab_candidato_id = ? AND hc.tipo = 1");
$result->bindValue(1, $id);
$result->execute();

$dados_conjuge_2 = $result->fetch(PDO::FETCH_ASSOC);

// FAMILIAR------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$result = $db->prepare("SELECT hp.trab_mesmo_endereco, hgp.nome AS parentesco, hp.nis, hgp.nome AS hab_grau_parentesco,
  hc.hab_grau_parentesco_id, hpes.hab_financia_natureza_id, hpso.nome AS hab_programa_social, hpes.hab_programa_social_id, hpes.bolsa_percentual, hin.nome AS hab_instituicao_natureza, hpes.hab_instituicao_natureza_id,
  hpes.instituicao_nome, hpes.serie_periodo, hp.endereco_candidato, hp.hab_cid10_capitulo_id, hp.hab_cid10_grupo_id, hp.hab_cid10_categoria_id,
  hp.cadastro_unico, hp.deficiencia, hges.nome AS hab_grau_escolar, hp.hab_grau_escolar_id, ho.instituicao, ho.endereco, ho.cargo, ho.data_inicio ,hp.provedor_lar, ec.nome AS estado_civil, hp.bsc_estado_civil_id,
  hp.cie_data_validade, hp.cie_data_expedicao, hp.cie_rne, bcc.nome AS bsc_cie_classificacao, hp.bsc_cie_classificacao_id, hp.bsc_municipio_id_natural, hp.rg_numero, hp.rg_orgao_expedicao_id,
  best.nome AS rg_uf_expedicao, hp.rg_data_expedicao, hp.cnh_numero, hp.cnh_uf_expedicao, hp.cnh_data_validade, hp.cnh_data_expedicao, hp.id AS pessoa_id, hp.email,
  hpe.complemento, hpe.lote, hpe.quadra, hpe.bairro, hpe.numero, hpe.logradouro, hpe.cep, hc.id, hp.nome, hp.cpf, bmun.nome AS bsc_municipio, hpe.bsc_municipio_id, hp.bsc_nacionalidade_id,
  hp.cadastro_retroativo_ano, hp.bsc_pele_cor_id, hp.data_nascimento, hp.bsc_sexo_id, bpc.nome AS cor_pele, hp.doenca_cronica, hpes.hab_instituicao_natureza_id,
  hcc.catinic, hcc.catfim, hcc.descricao AS hab_cid10_capitulo, hcg.catinic AS catinicg, hcg.catfim AS catfimg, hcg.descricao AS hab_cid10_grupo, hcca.cat, hcca.descricao AS hab_cid10_categoria
    FROM hab_familiar hc
    LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
    LEFT JOIN hab_pessoa_endereco AS hpe ON hpe.hab_pessoa_id = hp.id
    LEFT JOIN hab_ocupacao AS ho ON ho.hab_pessoa_id = hc.hab_pessoa_id
    LEFT JOIN hab_pessoa_escolar AS hpes ON hpes.hab_pessoa_id = hc.hab_pessoa_id
    LEFT JOIN hab_grau_parentesco AS hgp ON hgp.id = hc.hab_grau_parentesco_id
    LEFT JOIN bsc_pele_cor AS bpc ON bpc.id = hp.bsc_pele_cor_id
    LEFT JOIN bsc_municipio AS bmun ON bmun.id = hpe.bsc_municipio_id
    LEFT JOIN hab_grau_escolar AS hges ON hges.id = hp.hab_grau_escolar_id
    LEFT JOIN hab_instituicao_natureza AS hin ON hin.id = hpes.hab_instituicao_natureza_id
    LEFT JOIN hab_programa_social AS hpso ON hpso.id = hpes.hab_programa_social_id
    LEFT JOIN bsc_estado_civil AS ec ON ec.id = hp.bsc_estado_civil_id 
    LEFT JOIN hab_cid10_capitulo AS hcc ON hcc.id = hp.hab_cid10_capitulo_id
    LEFT JOIN hab_cid10_grupo AS hcg ON hcg.id = hp.hab_cid10_grupo_id
    LEFT JOIN hab_cid10_categoria AS hcca ON hcca.id = hp.hab_cid10_categoria_id
    LEFT JOIN bsc_estado AS best ON best.id = hp.rg_uf_expedicao
    LEFT JOIN bsc_cie_classificacao AS bcc ON bcc.id = hp.bsc_cie_classificacao_id
    WHERE hc.hab_candidato_id = ?");
$result->bindValue(1, $id);
$result->execute();

$dadosFamiliares = $result->fetchAll(PDO::FETCH_ASSOC);

$qtd_familiar = $result->rowCount();
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$stmt = $db->prepare("SELECT * 
                      FROM hab_pessoa_anexo 
                      WHERE hab_pessoa_id = ?
                      ");
$stmt->bindValue(1, $dados_candidato['pessoa_id']);
$stmt->execute();
$candidadoAnexos = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT *
                      FROM hab_pessoa_anexo
                      WHERE hab_pessoa_id = ?
                      ");
$stmt->bindValue(1, $dados_conjuge['pessoa_id']);
$stmt->execute();
$conjugeAnexos = $stmt->fetch(PDO::FETCH_ASSOC);

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
<div id="div_container" class="card icons-demo">
  <div class="card-body card-padding-sm">
    <div class="old-records">
      <div class="">
        <div class="col-md-5 name-program">
          <small>ORIGEM DA DEMANDA</small>
          <span><?= ctexto($dados_candidato['programa'], "mai"); ?></span>
        </div>
        <div class="col-md-5 name-subprogram">
          <small>ESPECIFICIDADE</small>
          <span><?= ctexto($dados_candidato['subprograma'], "mai"); ?></span>
        </div>
        <div class="col-md-2 date">
          <small>DATA DO CADASTRO</small>
          <span><?= is_numeric($dados_candidato['cadastro_retroativo_ano']) ? obterDataBRTimestamp($dados_candidato['data_cadastro_anterior']) : obterDataBRTimestamp($dados_candidato['data_cadastro']); ?></span>
        </div>
      </div>
    </div>

    <?php include_once 'menu_visualiza.php'; ?>

    <section id="section_titular" class="view holder-content">
      <h1 class="spouse">
        <i class="zmdi zmdi-assignment-account zmdi-hc-fw"></i>
        TITULAR
      </h1>
      <div class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="field">
              <small> NOME </small>
              <span class="nome">
                <?= $dados_candidato['nome'] == '' ? '' : $dados_candidato['nome']; ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> CPF </small>
              <span>
                <?= $dados_candidato['cpf'] == '' ? '' : $dados_candidato['cpf']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> COR/RAÇA </small>
              <span>
                <?= $dados_candidato['cor']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> NASCIMENTO </small>
              <span>
                <?= $dados_candidato['data_nascimento'] == "" || $dados_candidato['data_nascimento'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['data_nascimento']); ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> SEXO </small>
              <span>
                <?= $dados_candidato['bsc_sexo_id'] == 1 ? "MASCULINO" : "FEMININO"; ?>
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['rg_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">
              <small> RG </small>
              <span>
                <?= $dados_candidato['rg_numero'] == "" ? "" : $dados_candidato['rg_numero']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> ÓRGÃO EXPEDIDOR </small>
              <span>
                <?= $dados_candidato['rg_orgao_expedicao_id'] == 1 ? "SSP/" : "DETRAN/"; ?><?= $dados_candidato['rg_uf_expedicao']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> UF EXPEDIDOR </small>
              <span>
                <?= $dados_candidato['rg_uf_expedicao']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> DATA EXPEDIÇÃO </small>
              <span>
                <?= $dados_candidato['rg_data_expedicao'] == "" || $dados_candidato['rg_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['rg_data_expedicao']); ?>
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['cnh_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">
              <small> CNH </small>
              <span>
                <?= $dados_candidato['cnh_numero'] == "" ? "" : $dados_candidato['cnh_numero']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> UF EXPEDIDOR </small>
              <span>
                <?= $dados_candidato['cnh_uf_expedicao']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> DATA EXPEDIÇÃO </small>
              <span>
                <?= $dados_candidato['cnh_data_expedicao'] == "" || $dados_candidato['cnh_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cnh_data_expedicao']); ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> DATA DE VALIDADE </small>
              <span>
                <?= $dados_candidato['cnh_data_validade'] == "" || $dados_candidato['cnh_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cnh_data_validade']); ?>
              </span>
            </div>
          </div>
        </div>
        <div <?= is_numeric($dados_candidato['bsc_municipio_id_natural']) ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">
              <small> NACIONALIDADE </small>
              <span> BRASILEIRO </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> NATURALIDADE </small>
              <span>
                <?= nome_estado_municipio(estado_do_municipio($dados_candidato['bsc_municipio_id_natural'])); ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> CIDADE </small>
              <span>
                <?= nome_municipio($dados_candidato['bsc_municipio_id_natural']); ?>
              </span>
            </div>
          </div>
        </div>
        <div <?= is_numeric($dados_candidato['bsc_municipio_id_natural']) ? "style='display: none'" : ""; ?> class="row">
          <div class="col-md-3">
            <div class="field">
              <small> NACIONALIDADE </small>
              <span> ESTRANGEIRO </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> CÓD. RNE </small>
              <span>
                <?= $dados_candidato['cie_rne'] == "" ? "" : $dados_candidato['cie_rne']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> CLASSIFICAÇÃO </small>
              <span>
                <?= $dados_candidato['bsc_cie_classificacao']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> DATA DE EXPEDIÇÃO </small>
              <span>
                <?= $dados_candidato['cie_data_expedicao'] == "" || $dados_candidato['cie_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cie_data_expedicao']); ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> VALIDADE </small>
              <span>
                <?= $dados_candidato['cie_data_validade'] == "" || $dados_candidato['cie_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cie_data_validade']); ?>
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">ENDEREÇO RESIDENCIAL</h2>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> MORADOR DE RUA? </small>
              <span>
                <?= $dados_candidato['morador_rua'] == 1 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> COABITAÇÃO INVOLUNTÁRIA? </small>
              <span>
                <?= $dados_candidato['coabitacao_involuntaria'] == 1 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> ÁREA DE RISCO OU INSALUBRE </small>
              <span>
                <?= $dados_candidato['area_risco_insalubre'] == 1 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> RESINDÊNCIA ALUGADA? </small>
              <span>
                <?= $dados_candidato['alugada'] == 1 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['alugada'] == 1 ? "" : "style='display: none'"; ?> class="col-md-3">
            <div class="field">
              <small> VALOR DO ALUGUEL </small>
              <span>
                <?= fdec($dados_candidato['aluguel_valor']); ?>
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['alugada'] == 1 ? "" : "style='display: none'"; ?> class="col-md-3">
            <div class="field">
              <small> ALUGUEL SOCIAL </small>
              <span>
                <?= $dados_candidato['aluguel_social'] == 1 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> CEP </small>
              <span>
                <?= $dados_candidato['cep'] == "" ? "" : $dados_candidato['cep']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> TIPO DE ENDEREÇO </small>
              <span>
                <?= $dados_candidato['bsc_endereco_tipo']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> MUNICÍPIO </small>
              <span>
                <?= $dados_candidato['bsc_municipio']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> DATA DE INÍCIO NA RESIDÊNCIA </small>
              <span>
                <?= obterDataBRTimestamp($dados_candidato['data_inicio_residencia_municipio']); ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> TIPO DE LOGRADOURO </small>
              <span>
                <?= $dados_candidato['bsc_logradouro']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="field">
              <small> LOGRADOURO </small>
              <span>
                <?= $dados_candidato['logradouro'] == "" ? "" : $dados_candidato['logradouro']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> NÚMERO </small>
              <span>
                <?= $dados_candidato['numero'] == "" ? "" : $dados_candidato['numero']; ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> BAIRRO </small>
              <span>
                <?= $dados_candidato['bairro'] == "" ? "" : $dados_candidato['bairro']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> QUADRA </small>
              <span>
                <?= $dados_candidato['quadra'] == "" ? "" : $dados_candidato['quadra']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> CASA </small>
              <span>
                <?= $dados_candidato['lote'] == "" ? "" : $dados_candidato['lote']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> COMPLEMENTO </small>
              <span>
                <?= $dados_candidato['complemento'] == "" ? "" : $dados_candidato['complemento']; ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> LATITUDE </small>
              <span>
                <?= $dados_candidato['latitude'] == "" ? "" : $dados_candidato['latitude']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> LONGITUDE </small>
              <span>
                <?= $dados_candidato['longitude'] == "" ? "" : $dados_candidato['longitude']; ?>
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">INFORMAÇÕES DE CONTATO</h2>
        <div class="row">
          <div class="col-md-6">
            <div class="field">
              <small> E-MAIL PESSOAL </small>
              <span>
                <?= $dados_candidato['email'] == "" ? "" : $dados_candidato['email']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> RESIDENCIAL </small>
              <span>
                <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 1"); ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> CELULAR </small>
              <span>
                <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 2"); ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> COMERCIAL </small>
              <span>
                <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> RAMAL </small>
              <span>
                <?= pesquisar_tabela("ramal", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> CONTATO TELEFÔNICO </small>
              <span>
                <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 4"); ?>
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="field">
              <small> NOME DO CONTATO </small>
              <span>
                <?= pesquisar_tabela("recado_nome", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 4"); ?>
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">INFORMAÇÕES DE TRABALHO</h2>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> VOCÊ TRABALHA? </small>
              <span>
                <?= $dados_candidato['cargo'] != "" ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['cargo'] != "" ? "" : "style='display: none'"; ?> class="col-md-3">
            <div class="field">
              <small> TRABALHA NO MESMO ENDEREÇO? </small>
              <span>
                <?= $dados_candidato['trab_mesmo_endereco'] == 1 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['cargo'] != "" ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">
              <small> OCUPAÇÃO/CARGO/FUNÇÃO </small>
              <span>
                <?= $dados_candidato['cargo'] == "" ? "" : $dados_candidato['cargo']; ?>
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-3">
            <div class="field">
              <small> LOCAL DE TRABALHO </small>
              <span>
                <?= $dados_candidato['instituicao'] == "" ? "" : $dados_candidato['instituicao']; ?>
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-4">
            <div class="field">
              <small> ENDEREÇO </small>
              <span>
                <?= $dados_candidato['endereco'] == "" ? "" : $dados_candidato['endereco']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-2">
            <div class="field">
              <small> DATA INÍCIO </small>
              <span>
                <?= $dados_candidato['data_inicio'] == "" || $dados_candidato['data_inicio'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['data_inicio']); ?>
              </span>
            </div>
          </div>
        </div>

        <?php
        $result1 = $db->prepare("SELECT hpr.valor, hrt.nome AS renda_tipo
                     FROM hab_pessoa_renda AS hpr
                     LEFT JOIN hab_renda_tipo AS hrt ON hrt.id = hpr.hab_renda_tipo_id
                     WHERE hpr.hab_pessoa_id = ?");
        $result1->bindValue(1, $pessoa_id);
        $result1->execute();
        while ($pessoa_renda = $result1->fetch(PDO::FETCH_ASSOC)) {
          ?>

          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small> TIPO DE RENDA </small>
                <span>
                  <?= $pessoa_renda['renda_tipo']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> VALOR DA RENDA </small>
                <span>
                  <?= fdec($pessoa_renda['valor']); ?>
                </span>
              </div>
            </div>
          </div>

          <?php
        }
        ?>

        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> PROVEDOR DO LAR? </small>
              <span>
                <?= $dados_candidato['provedor_lar'] == 1 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">ESCOLARIDADE</h2>
        <div class="row">
          <div class="col-md-6">
            <div class="field">
              <small> GRAU ESCOLAR </small>
              <span>
                <?= $dados_candidato['hab_grau_escolar']; ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> ESTÁ ESTUDANDO? </small>
              <span>
                <?= $dados_candidato['instituicao_nome'] != "" ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['instituicao_nome'] != "" ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">
              <small> NATUREZA DA INSTITUIÇÃO </small>
              <span>
                <?= $dados_candidato['hab_instituicao_natureza']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="field">
              <small> NOME DA INSTITUIÇÃO </small>
              <span>
                <?= $dados_candidato['instituicao_nome']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> SÉRIE/PERÍODO </small>
              <span>
                <?= $dados_candidato['serie_periodo']; ?>°
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['instituicao_nome'] != "" ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">
              <small> FINANCIADO POR MEIOS PRÓPRIOS? </small>
              <span>
                <?= $dados_candidato['hab_financia_natureza_id'] == 1 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['instituicao_nome'] != "" && $dados_candidato['hab_financia_natureza_id'] == 2 ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-4">
            <div class="field">
              <small> PROGRAMA SOCIAL </small>
              <span>
                <?= $dados_candidato['hab_programa_social']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-4">
            <div class="field">
              <small> PORCENTAGEM </small>
              <span>
                <?= $dados_candidato['bolsa_percentual']; ?>%
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">SITUAÇÃO CONJUGAL</h2>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> ESTADO CIVIL </small>
              <span>
                <?= $dados_candidato['estado_civil']; ?>
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['estado_civil_id'] == 6 || $dados_candidato['estado_civil_id'] == 7 || $dados_candidato['estado_civil_id'] == 8 ? '' : 'style="display: none"'; ?> class="col-md-3">
            <div class="field">
              <small> DATA DE CASAMENTO </small>
              <span>
                <?= obterDataBRTimestamp($dados_candidato['casamento_data']); ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> UNIÃO ESTÁVEL </small>
              <span>
                <?= $dados_candidato['uniao_estavel'] == 1 ? 'SIM' : 'NÃO'; ?>
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">FILIAÇÃO</h2>
        <div class="row">
          <div class="col-md-6">
            <div class="field">
              <small> NOME DA MÃE </small>
              <span>
                <?= $dados_candidato['mae_nome']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> NASCIMENTO </small>
              <span>
                <?= obterDataBRTimestamp($dados_candidato['mae_data_nascimento']); ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> CONTATO </small>
              <span>
                <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 5"); ?>
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">INFORMAÇÕES COMPLEMENTARES</h2>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small> POSSUI ALGUM TIPO DE DEFICIÊNCIA? </small>
              <span>
                <?= $dados_candidato['deficiencia'] == 1 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small> TIPO DE DEFICIÊNCIA </small>
              <span>
                <?= $dados_candidato['bsc_deficiencia_tipo']; ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="field">
              <small> POSSUI ALGUM TIPO DE DOENÇA CRÔNICA INCAPACITANTE? </small>
              <span>
                <?= $dados_candidato['doenca_cronica'] == 1 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">CLASSIFICAÇÃO INTERNACIONAL DE DOENÇA</h2>
        <div class="row">
          <div class="col-md-12">
            <div class="field">
              <small> CID CAPÍTULO </small>
              <span>
                <?= $dados_candidato['catinic'] . " ATÉ " . $dados_candidato['catfim'] . " - " . $dados_candidato['hab_cid10_capitulo']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-12">
            <div class="field">
              <small> CID GRUPO </small>
              <span>
                <?= $dados_candidato['catinicg'] . " ATÉ " . $dados_candidato['catinicg'] . " - " . $dados_candidato['hab_cid10_grupo']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-12">
            <div class="field">
              <small> CID CATEGORIA </small>
              <span>
                <?= $dados_candidato['cat'] . " - " . $dados_candidato['hab_cid10_categoria']; ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="field">
              <small> CAD. ÚNICO </small>
              <span>
                <?= $dados_candidato['cadastro_unico']; ?>
              </span>
            </div>
          </div>
          <div class="col-md-4">
            <div class="field">
              <small> NIS </small>
              <span>
                <?= $dados_candidato['nis']; ?>
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">SOCIAL</h2>
        <div class="row">
          <div class="col-md-4">
            <div class="field">
              <small> OCORRÊNCIA DE MARIA DA PENHA? </small>
              <span>
                <?= $dados_candidato['lei_maria_penha'] == 1 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="field">
              <small> POSSUI ALGUM BENEFÍCIO SOCIAL? </small>
              <span>
                <?= is_numeric($bcp) || is_numeric($bolsa_familia) ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
          <div <?= is_numeric($bcp) ? "" : "style='display:none'"; ?> class="col-md-4">
            <div class="field">
              <small> BENEFÍCIO SOCIAL </small>
              <span>
                <?= "BPC (Benefício de Prestação Continuada)" ?>
              </span>
            </div>
          </div>
          <div <?= is_numeric($bolsa_familia) ? "" : "style='display:none'"; ?> class="col-md-4">
            <div class="field">
              <small> BENEFÍCIO SOCIAL </small>
              <span>
                <?= "BOLSA FAMÍLIA" ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="field">
              <small> JÁ FOI BENEFICIDADO EM ALGUM PROGRAMA HABITACIONAL? </small>
              <span>
                <?= $dados_candidato['snch_apf_id'] > 0 ? "SIM" : "NÃO"; ?>
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['snch_apf_id'] > 0 ? "" : "style='display: none'"; ?> class="col-md-4">
            <div class="field">
              <small> PROGRAMA </small>
              <span>
                <?= $dados_candidato['snch_apf']; ?>
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['snch_apf_id'] > 1 ? "" : "style='display: none'"; ?> class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                LOTEAMENTO
              </small>
              <span>
                <?= $dados_candidato['snch_loteamento']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['snch_apf_id'] > 0 ? "style='display: none'" : ""; ?> class="row">
          <div class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                DESEJA PARTICIPAR DE ALGUM PROGRAMA HABITACIONAL?
              </small>
              <span>
                <?= $dados_candidato['participar_programa_id'] > 0 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['participar_programa_id'] > 0 ? "" : "style='display: none'"; ?> class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                PROGRAMA
              </small>
              <span>
                <?= pesquisar_tabela("nome", "snch_apf", "id", "=", $dados_candidato['participar_programa_id'], ""); ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="section_conjuge" class="view spouse-content">
      <h1>
        <i class="zmdi zmdi-male-female zmdi-hc-fw"></i>
        CÔNJUGE
      </h1>

      <?php
      if ($dados_conjuge['nome'] != "") {
        ?>

        <div class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="field">
                <small> NOME </small>
                <span class="nome">
                  <?= $dados_conjuge['nome'] == '' ? '' : $dados_conjuge['nome']; ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="NOME DO CÔNJUGE" relkey="conjuge_nome" data-toggle="modal" class="info"> </a>
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small> CPF </small>
                <span>
                  <?= $dados_conjuge['cpf'] == '' ? '' : $dados_conjuge['cpf']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> COR/RAÇA </small>
                <span>
                  <?= $dados_conjuge['cor']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> NASCIMENTO </small>
                <span>
                  <?= $dados_conjuge['data_nascimento'] == "" || $dados_conjuge['data_nascimento'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['data_nascimento']); ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> SEXO </small>
                <span>
                  <?= $dados_conjuge['bsc_sexo_id'] == 1 ? "MASCULINO" : "FEMININO"; ?>
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['rg_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small> RG </small>
                <span>
                  <?= $dados_conjuge['rg_numero'] == "" ? "" : $dados_conjuge['rg_numero']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> ÓRGÃO EXPEDIDOR </small>
                <span>
                  <?= $dados_conjuge['rg_orgao_expedicao_id'] == 1 ? "SSP/" : "DETRAN/"; ?><?= $dados_conjuge['rg_uf_expedicao']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> UF EXPEDIDOR </small>
                <span>
                  <?= $dados_conjuge['rg_uf_expedicao']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> DATA EXPEDIÇÃO </small>
                <span>
                  <?= $dados_conjuge['rg_data_expedicao'] == "" || $dados_conjuge['rg_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['rg_data_expedicao']); ?>
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['cnh_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small> CNH </small>
                <span>
                  <?= $dados_conjuge['cnh_numero'] == "" ? "" : $dados_conjuge['cnh_numero']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> UF EXPEDIDOR </small>
                <span>
                  <?= $dados_conjuge['cnh_uf_expedicao']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> DATA EXPEDIÇÃO </small>
                <span>
                  <?= $dados_conjuge['cnh_data_expedicao'] == "" || $dados_conjuge['cnh_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cnh_data_expedicao']); ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> DATA DE VALIDADE </small>
                <span>
                  <?= $dados_conjuge['cnh_data_validade'] == "" || $dados_conjuge['cnh_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cnh_data_validade']); ?>
                </span>
              </div>
            </div>
          </div>
          <div <?= is_numeric($dados_conjuge['bsc_municipio_id_natural']) ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small> NACIONALIDADE </small>
                <span> BRASILEIRO </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> NATURALIDADE </small>
                <span>
                  <?= nome_estado_municipio(estado_do_municipio($dados_conjuge['bsc_municipio_id_natural'])); ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> CIDADE </small>
                <span>
                  <?= nome_municipio($dados_conjuge['bsc_municipio_id_natural']); ?>
                </span>
              </div>
            </div>
          </div>
          <div <?= is_numeric($dados_conjuge['bsc_municipio_id_natural']) ? "style='display: none'" : ""; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small> NACIONALIDADE </small>
                <span> ESTRANGEIRO </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> CÓD. RNE </small>
                <span>
                  <?= $dados_conjuge['cie_rne'] == "" ? "" : $dados_conjuge['cie_rne']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> CLASSIFICAÇÃO </small>
                <span>
                  <?= $dados_conjuge['bsc_cie_classificacao']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> DATA DE EXPEDIÇÃO </small>
                <span>
                  <?= $dados_conjuge['cie_data_expedicao'] == "" || $dados_conjuge['cie_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cie_data_expedicao']); ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> VALIDADE </small>
                <span>
                  <?= $dados_conjuge['cie_data_validade'] == "" || $dados_conjuge['cie_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cie_data_validade']); ?>
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">ENDEREÇO RESIDENCIAL</h2>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small> MORA NO MESMO ENDEREÇO? </small>
                <span>
                  <?= $dados_conjuge['endereco_candidato'] == 1 ? "SIM" : "NÃO"; ?>
                </span>
              </div>
            </div>
            <div <?= $dados_conjuge['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="col-md-3">
              <div class="field">
                <small>
                  <input id="check_conjuge_cep" name="check_CEP" class="checkbox-validacao" rel="CEP DO CÔNJUGE" value="option1" type="checkbox">
                  CEP
                </small>
                <span>
                  <?= $dados_conjuge['cep'] == "" ? "" : $dados_conjuge['cep']; ?>
                </span>
              </div>
            </div>
            <div <?= $dados_conjuge['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="col-md-3">
              <div class="field">
                <small> MUNICÍPIO </small>
                <span>
                  <?= $dados_conjuge['bsc_municipio']; ?>
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="row">
            <div class="col-md-6">
              <div class="field">
                <small> LOGRADOURO </small>
                <span>
                  <?= $dados_conjuge['logradouro'] == "" ? "" : $dados_conjuge['logradouro']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> NÚMERO </small>
                <span>
                  <?= $dados_conjuge['numero'] == "" ? "" : $dados_conjuge['numero']; ?>
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small> BAIRRO </small>
                <span>
                  <?= $dados_conjuge['bairro'] == "" ? "" : $dados_conjuge['bairro']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> QUADRA </small>
                <span>
                  <?= $dados_conjuge['quadra'] == "" ? "" : $dados_conjuge['quadra']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> CASA </small>
                <span>
                  <?= $dados_conjuge['lote'] == "" ? "" : $dados_conjuge['lote']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> COMPLEMENTO </small>
                <span>
                  <?= $dados_conjuge['complemento'] == "" ? "" : $dados_conjuge['complemento']; ?>
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">INFORMAÇÕES DE CONTATO</h2>
          <div class="row">
            <div class="col-md-6">
              <div class="field">
                <small> E-MAIL PESSOAL </small>
                <span>
                  <?= $dados_conjuge['email'] == "" ? "" : $dados_conjuge['email']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> RESIDENCIAL </small>
                <span>
                  <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $conjuge_pessoa_id, "AND hab_contato_tipo_id = 1"); ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> CELULAR </small>
                <span>
                  <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $conjuge_pessoa_id, "AND hab_contato_tipo_id = 2"); ?>
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small> COMERCIAL </small>
                <span>
                  <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $conjuge_pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> RAMAL </small>
                <span>
                  <?= pesquisar_tabela("ramal", "hab_pessoa_contato", "hab_pessoa_id", "=", $conjuge_pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">INFORMAÇÕES DE TRABALHO</h2>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small> VOCÊ TRABALHA? </small>
                <span>
                  <?= $dados_conjuge['cargo'] != "" ? "SIM" : "NÃO"; ?>
                </span>
              </div>
            </div>
            <div <?= $dados_conjuge['cargo'] != "" ? "" : "style='display: none'"; ?> class="col-md-3">
              <div class="field">
                <small> TRABALHA NO MESMO ENDEREÇO? </small>
                <span>
                  <?= $dados_conjuge['trab_mesmo_endereco'] == 1 ? "SIM" : "NÃO"; ?>
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['cargo'] != "" ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small> OCUPAÇÃO/CARGO/FUNÇÃO </small>
                <span>
                  <?= $dados_conjuge['cargo'] == "" ? "" : $dados_conjuge['cargo']; ?>
                </span>
              </div>
            </div>
            <div <?= $dados_conjuge['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-3">
              <div class="field">
                <small> LOCAL DE TRABALHO </small>
                <span>
                  <?= $dados_conjuge['instituicao'] == "" ? "" : $dados_conjuge['instituicao']; ?>
                </span>
              </div>
            </div>
            <div <?= $dados_conjuge['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-4">
              <div class="field">
                <small> ENDEREÇO </small>
                <span>
                  <?= $dados_conjuge['endereco'] == "" ? "" : $dados_conjuge['endereco']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-2">
              <div class="field">
                <small> DATA INÍCIO </small>
                <span>
                  <?= $dados_conjuge['data_inicio'] == "" || $dados_conjuge['data_inicio'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['data_inicio']); ?>
                </span>
              </div>
            </div>
          </div>

          <?php
          $result1 = $db->prepare("SELECT hpr.valor, hrt.id AS renda_tipo_id, hrt.nome AS renda_tipo
                     FROM hab_pessoa_renda AS hpr
                     LEFT JOIN hab_renda_tipo AS hrt ON hrt.id = hpr.hab_renda_tipo_id
                     WHERE hpr.hab_pessoa_id = ?");
          $result1->bindValue(1, $conjuge_pessoa_id);
          $result1->execute();
          while ($pessoa_renda = $result1->fetch(PDO::FETCH_ASSOC)) {
            ?>

            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <?php
                  if (isset($conjugeAnexos['renda_comprovada']) && $pessoa_renda['renda_tipo_id'] == 1) {
                    ?>
                    <small> TIPO DE RENDA </small>
                    <?php
                  } else {
                    ?>
                    <small> TIPO DE RENDA </small>
                    <?php
                  }
                  ?>
                  <span>
                    <?= $pessoa_renda['renda_tipo']; ?>

                    <?php
                    if (isset($conjugeAnexos['renda_comprovada']) && $pessoa_renda['renda_tipo_id'] == 1) {
                      ?>

                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> VALOR DA RENDA </small>
                  <span>
                    <?= fdec($pessoa_renda['valor']); ?>
                  </span>
                </div>
              </div>
            </div>
            <?php
          }
          ?>

          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small> PROVEDOR DO LAR? </small>
                <span>
                  <?= $dados_conjuge['provedor_lar'] == 1 ? "SIM" : "NÃO"; ?>
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">ESCOLARIDADE</h2>
          <div class="row">
            <div class="col-md-6">
              <div class="field">
                <small> GRAU ESCOLAR </small>
                <span>
                  <?= $dados_conjuge['hab_grau_escolar']; ?>
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small> ESTÁ ESTUDANDO? </small>
                <span>
                  <?= $dados_conjuge['instituicao_nome'] != "" ? "SIM" : "NÃO"; ?>
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['instituicao_nome'] != "" ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small> NATUREZA DA INSTITUIÇÃO </small>
                <span>
                  <?= $dados_conjuge['hab_instituicao_natureza']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="field">
                <small> NOME DA INSTITUIÇÃO </small>
                <span>
                  <?= $dados_conjuge['instituicao_nome']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> SÉRIE/PERÍODO </small>
                <span>
                  <?= $dados_conjuge['serie_periodo']; ?>°
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['instituicao_nome'] != "" && $dados_conjuge['hab_instituicao_natureza_id'] == 2 ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small> FINANCIADO POR MEIOS PRÓPRIOS? </small>
                <span>
                  <?= $dados_conjuge['hab_financia_natureza_id'] == 1 ? "SIM" : "NÃO"; ?>
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['instituicao_nome'] != "" && $dados_conjuge['hab_instituicao_natureza_id'] == 2 && $dados_conjuge['hab_financia_natureza_id'] == 2 && $dados_conjuge['hab_programa_social'] != "" ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-4">
              <div class="field">
                <small> PROGRAMA SOCIAL </small>
                <span>
                  <?= $dados_conjuge['hab_programa_social']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="field">
                <small> PORCENTAGEM </small>
                <span>
                  <?= $dados_conjuge['bolsa_percentual']; ?>%
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">SITUAÇÃO CONJUGAL</h2>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small> ESTADO CIVIL </small>
                <span>
                  <?= $dados_conjuge['estado_civil']; ?>
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">INFORMAÇÕES COMPLEMENTARES</h2>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small> POSSUI ALGUM TIPO DE DEFICIÊNCIA? </small>
                <span>
                  <?= $dados_conjuge['deficiencia'] == 1 ? "SIM" : "NÃO"; ?>
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="field">
                <small> POSSUI ALGUM TIPO DE DOENÇA CRÔNICA INCAPACITANTE? </small>
                <span>
                  <?= $dados_conjuge['doenca_cronica'] == 1 ? "SIM" : "NÃO"; ?>
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="field">
                <small> CID CAPÍTULO </small>
                <span>
                  <?= $dados_conjuge['catinic'] . " ATÉ " . $dados_conjuge['catfim'] . " - " . $dados_conjuge['hab_cid10_capitulo']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-12">
              <div class="field">
                <small> CID GRUPO </small>
                <span>
                  <?= $dados_conjuge['catinicg'] . " ATÉ " . $dados_conjuge['catinicg'] . " - " . $dados_conjuge['hab_cid10_grupo']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-12">
              <div class="field">
                <small> CID CATEGORIA </small>
                <span>
                  <?= $dados_conjuge['cat'] . " - " . $dados_conjuge['hab_cid10_categoria']; ?>
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="field">
                <small> CAD. ÚNICO </small>
                <span>
                  <?= $dados_conjuge['cadastro_unico']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="field">
                <small> NIS </small>
                <span>
                  <?= $dados_conjuge['nis']; ?>
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="field">
                <small> POSSUÍ ALGUM OUTRO VÍNCULO JUDICIAL? </small>
                <span>
                  <?= is_numeric(pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 1")) ? "SIM" : "NÃO"; ?>
                </span>
              </div>
            </div>
          </div>
          <h2 <?= is_numeric(pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 1")) ? "" : "style='display: none'"; ?> class="m-b-30">VÍNCULO MATRIMONIAL ANTERIOR PENDENTE</h2>
          <div <?= is_numeric(pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 1")) ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-6">
              <div class="field">
                <small> NOME </small>
                <span>
                  <?= $dados_conjuge_2['nome']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small> CPF </small>
                <span>
                  <?= $dados_conjuge_2['cpf']; ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
                  <small> CONTATO </small>
                  <span>
                    <?= $dados_conjuge_2['numero']; ?>
                  </span>

              </div>
            </div>
          </div>
        </div>
        <?php
      } else {
        ?>
        <div class="content">
          <div class="row">
            <div class="col-md-12">Não existe Cônjuge para esse candidato</div>
          </div>
        </div>
        <?php
      }
      ?>
    </section>
    <section id="section_familiar" class="view family-group-content">
      <h1>
        <i class="zmdi zmdi-accounts zmdi-hc-fw"></i>
        GRUPO FAMILIAR
      </h1>
      <div class="content">

        <?php
        foreach ($dadosFamiliares as $k => $dados_familiar) {

          $stmt = $db->prepare("SELECT *
                      FROM hab_pessoa_anexo
                      WHERE hab_pessoa_id = ?");
          $stmt->bindValue(1, $dados_familiar['pessoa_id']);
          $stmt->execute();
          $familiarAnexos = $stmt->fetch(PDO::FETCH_ASSOC);

          $familiar_id = $dados_familiar['id'];
          $familiar_pessoa_id = $dados_familiar['pessoa_id'];
          ?>

          <fieldset>
            <legend>
              <i class="zmdi zmdi-chevron-right zmdi-hc-fw"></i> <?= $dados_familiar['hab_grau_parentesco']; ?></legend>
            <div class="row">
              <div class="col-md-12">
                <div class="field">
                  <small>
                    <?php
                    if (isset($familiarAnexos['rg']) || isset($familiarAnexos['cpf']) || isset($familiarAnexos['cnh']) || isset($familiarAnexos['cie'])) {
                      ?>
                      <input id="check_familiar_<?= $k; ?>_nome" name="check_NOME" class="checkbox-validacao" rel="NOME DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">

                      <?php
                    }
                    ?>
                    NOME
                  </small>
                  <span class="nome">
                    <?= $dados_familiar['nome'] == '' ? '' : $dados_familiar['nome']; ?>
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> CPF </small>
                  <span>
                    <?= $dados_familiar['cpf'] == '' ? '' : $dados_familiar['cpf']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> COR/RAÇA </small>
                  <span>
                    <?= $dados_familiar['cor_pele']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> NASCIMENTO </small>
                  <span>
                    <?= $dados_familiar['data_nascimento'] == "" || $dados_familiar['data_nascimento'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['data_nascimento']); ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> SEXO </small>
                  <span>
                    <?= $dados_familiar['bsc_sexo_id'] == 1 ? "MASCULINO" : "FEMININO"; ?>
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['rg_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> RG </small>
                  <span>
                    <?= $dados_familiar['rg_numero'] == "" ? "" : $dados_familiar['rg_numero']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> ÓRGÃO EXPEDIDOR </small>
                  <span>
                    <?= $dados_familiar['rg_orgao_expedicao_id'] == 1 ? "SSP/" : "DETRAN/"; ?><?= $dados_familiar['rg_uf_expedicao']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> UF EXPEDIDOR </small>
                  <span>
                    <?= $dados_familiar['rg_uf_expedicao']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> DATA EXPEDIÇÃO </small>
                  <span>
                    <?= $dados_familiar['rg_data_expedicao'] == "" || $dados_familiar['rg_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['rg_data_expedicao']); ?>
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['cnh_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> CNH </small>
                  <span>
                    <?= $dados_familiar['cnh_numero'] == "" ? "" : $dados_familiar['cnh_numero']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> UF EXPEDIDOR </small>
                  <span>
                    <?= $dados_familiar['cnh_uf_expedicao']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> DATA EXPEDIÇÃO </small>
                  <span>
                    <?= $dados_familiar['cnh_data_expedicao'] == "" || $dados_familiar['cnh_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cnh_data_expedicao']); ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> DATA DE VALIDADE </small>
                  <span>
                    <?= $dados_familiar['cnh_data_validade'] == "" || $dados_familiar['cnh_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cnh_data_validade']); ?>
                  </span>
                </div>
              </div>
            </div>
            <div <?= is_numeric($dados_familiar['bsc_municipio_id_natural']) ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> NACIONALIDADE </small>
                  <span> BRASILEIRO </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> NATURALIDADE </small>
                  <span>
                    <?= nome_estado_municipio(estado_do_municipio($dados_familiar['bsc_municipio_id_natural'])); ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> CIDADE </small>
                  <span>
                    <?= nome_municipio($dados_familiar['bsc_municipio_id_natural']); ?>
                  </span>
                </div>
              </div>
            </div>
            <div <?= is_numeric($dados_familiar['bsc_municipio_id_natural']) ? "style='display: none'" : ""; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> NACIONALIDADE </small>
                  <span> ESTRANGEIRO </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> CÓD. RNE </small>
                  <span>
                    <?= $dados_familiar['cie_rne'] == "" ? "" : $dados_familiar['cie_rne']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> CLASSIFICAÇÃO </small>
                  <span>
                    <?= $dados_familiar['bsc_cie_classificacao']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> DATA DE EXPEDIÇÃO </small>
                  <span>
                    <?= $dados_familiar['cie_data_expedicao'] == "" || $dados_familiar['cie_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cie_data_expedicao']); ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> VALIDADE </small>
                  <span>
                    <?= $dados_familiar['cie_data_validade'] == "" || $dados_familiar['cie_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cie_data_validade']); ?>
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">ENDEREÇO RESIDENCIAL</h2>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> MORA NO MESMO ENDEREÇO? </small>
                  <span>
                    <?= $dados_familiar['endereco_candidato'] == 1 ? "SIM" : "NÃO"; ?>
                  </span>
                </div>
              </div>
              <div <?= $dados_familiar['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="col-md-3">
                <div class="field">
                  <small>
                    <input id="check_familiar_<?= $k; ?>_cep" name="check_CEP" class="checkbox-validacao" rel="CEP DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                    CEP
                  </small>
                  <span>
                    <?= $dados_familiar['cep'] == "" ? "" : $dados_familiar['cep']; ?>
                  </span>
                </div>
              </div>
              <div <?= $dados_familiar['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="col-md-3">
                <div class="field">
                  <small> MUNICÍPIO </small>
                  <span>
                    <?= $dados_familiar['bsc_municipio']; ?>
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="row">
              <div class="col-md-6">
                <div class="field">
                  <small> LOGRADOURO </small>
                  <span>
                    <?= $dados_familiar['logradouro'] == "" ? "" : $dados_familiar['logradouro']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> NÚMERO </small>
                  <span>
                    <?= $dados_familiar['numero'] == "" ? "" : $dados_familiar['numero']; ?>
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> BAIRRO </small>
                  <span>
                    <?= $dados_familiar['bairro'] == "" ? "" : $dados_familiar['bairro']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> QUADRA </small>
                  <span>
                    <?= $dados_familiar['quadra'] == "" ? "" : $dados_familiar['quadra']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> CASA </small>
                  <span>
                    <?= $dados_familiar['lote'] == "" ? "" : $dados_familiar['lote']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> COMPLEMENTO </small>
                  <span>
                    <?= $dados_familiar['complemento'] == "" ? "" : $dados_familiar['complemento']; ?>
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">INFORMAÇÕES DE CONTATO</h2>
            <div class="row">
              <div class="col-md-6">
                <div class="field">
                  <small> E-MAIL PESSOAL </small>
                  <span>
                    <?= $dados_familiar['email'] == "" ? "" : $dados_familiar['email']; ?>

                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> RESIDENCIAL </small>
                  <span>
                    <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $familiar_pessoa_id, "AND hab_contato_tipo_id = 1"); ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> CELULAR </small>
                  <span>
                    <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $familiar_pessoa_id, "AND hab_contato_tipo_id = 2"); ?>
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> COMERCIAL </small>
                  <span>
                    <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $familiar_pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> RAMAL </small>
                  <span>
                    <?= pesquisar_tabela("ramal", "hab_pessoa_contato", "hab_pessoa_id", "=", $familiar_pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">INFORMAÇÕES DE TRABALHO</h2>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> VOCÊ TRABALHA? </small>
                  <span>
                    <?= $dados_familiar['cargo'] != "" ? "SIM" : "NÃO"; ?>
                  </span>
                </div>
              </div>
              <div <?= $dados_conjuge['cargo'] != "" ? "" : "style='display: none'"; ?> class="col-md-3">
                <div class="field">
                  <small> TRABALHA NO MESMO ENDEREÇO? </small>
                  <span>
                    <?= $dados_familiar['trab_mesmo_endereco'] == 1 ? "SIM" : "NÃO"; ?>
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['cargo'] != "" ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> OCUPAÇÃO/CARGO/FUNÇÃO </small>
                  <span>
                    <?= $dados_familiar['cargo'] == "" ? "" : $dados_familiar['cargo']; ?>
                  </span>
                </div>
              </div>
              <div <?= $dados_familiar['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-3">
                <div class="field">
                  <small> LOCAL DE TRABALHO </small>
                  <span>
                    <?= $dados_familiar['instituicao'] == "" ? "" : $dados_familiar['instituicao']; ?>
                  </span>
                </div>
              </div>
              <div <?= $dados_familiar['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-4">
                <div class="field">
                  <small> ENDEREÇO </small>
                  <span>
                    <?= $dados_familiar['endereco'] == "" ? "" : $dados_familiar['endereco']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-2">
                <div class="field">
                  <small> DATA INÍCIO </small>
                  <span>
                    <?= $dados_familiar['data_inicio'] == "" || $dados_familiar['data_inicio'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['data_inicio']); ?>
                  </span>
                </div>
              </div>
            </div>

            <?php
            $result1 = $db->prepare("SELECT hpr.valor, hrt.id AS renda_tipo_id, hrt.nome AS renda_tipo
                     FROM hab_pessoa_renda AS hpr
                     LEFT JOIN hab_renda_tipo AS hrt ON hrt.id = hpr.hab_renda_tipo_id
                     WHERE hpr.hab_pessoa_id = ?");
            $result1->bindValue(1, $familiar_pessoa_id);
            $result1->execute();
            while ($pessoa_renda = $result1->fetch(PDO::FETCH_ASSOC)) {
              ?>

              <div class="row">
                <div class="col-md-3">
                  <div class="field">
                    <?php
                    if (isset($familiarAnexos['renda_comprovada']) && $pessoa_renda['renda_tipo_id'] == 1) {
                      ?>
                      <small>
                        <input id="check_familiar_<?= $k; ?>_renda_comprovada" name="check_RENDA" class="checkbox-validacao" rel="RENDA DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                        TIPO DE RENDA
                      </small>
                      <?php
                    } else {
                      ?>
                      <small> TIPO DE RENDA </small>
                      <?php
                    }
                    ?>
                    <span>
                      <?= $pessoa_renda['renda_tipo']; ?>

                      <?php
                      if (isset($familiarAnexos['renda_comprovada']) && $pessoa_renda['renda_tipo_id'] == 1) {
                        ?>
                        <?php
                      }
                      ?>
                    </span>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="field">
                    <small> VALOR DA RENDA </small>
                    <span>
                      <?= fdec($pessoa_renda['valor']); ?>
                    </span>
                  </div>
                </div>
              </div>

              <?php
            }
            ?>

            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> PROVEDOR DO LAR? </small>
                  <span>
                    <?= $dados_familiar['provedor_lar'] == 1 ? "SIM" : "NÃO"; ?>
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">ESCOLARIDADE</h2>
            <div class="row">
              <div class="col-md-6">
                <div class="field">
                  <small> GRAU ESCOLAR </small>
                  <span>
                    <?= $dados_familiar['hab_grau_escolar']; ?>
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> ESTÁ ESTUDANDO? </small>
                  <span>
                    <?= $dados_familiar['instituicao_nome'] != "" ? "SIM" : "NÃO"; ?>
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['instituicao_nome'] != "" ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> NATUREZA DA INSTITUIÇÃO </small>
                  <span>
                    <?= $dados_familiar['hab_instituicao_natureza']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="field">
                  <small> NOME DA INSTITUIÇÃO </small>
                  <span>
                    <?= $dados_familiar['instituicao_nome']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small> SÉRIE/PERÍODO </small>
                  <span>
                    <?= $dados_familiar['serie_periodo']; ?>°
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['instituicao_nome'] != "" && $dados_familiar['hab_instituicao_natureza_id'] == 2 ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> FINANCIADO POR MEIOS PRÓPRIOS? </small>
                  <span>
                    <?= $dados_familiar['hab_financia_natureza_id'] == 1 ? "SIM" : "NÃO"; ?>
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['instituicao_nome'] != "" && $dados_familiar['hab_instituicao_natureza_id'] == 2 && $dados_familiar['hab_financia_natureza_id'] == 2 && $dados_familiar['hab_programa_social'] != "" ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-4">
                <div class="field">
                  <small> PROGRAMA SOCIAL </small>
                  <span>
                    <?= $dados_familiar['hab_programa_social']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="field">
                  <small> PORCENTAGEM </small>
                  <span>
                    <?= $dados_familiar['bolsa_percentual']; ?>%
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">SITUAÇÃO CONJUGAL</h2>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> ESTADO CIVIL </small>
                  <span>
                    <?= $dados_familiar['estado_civil']; ?>
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">INFORMAÇÕES COMPLEMENTARES</h2>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small> POSSUI ALGUM TIPO DE DEFICIÊNCIA? </small>
                  <span>
                    <?= $dados_familiar['deficiencia'] == 1 ? "SIM" : "NÃO"; ?>
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="field">
                  <small> POSSUI ALGUM TIPO DE DOENÇA CRÔNICA INCAPACITANTE? </small>
                  <span>
                    <?= $dados_familiar['doenca_cronica'] == 1 ? "SIM" : "NÃO"; ?>
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">CLASSIFICAÇÃO INTERNACIONAL DE DOENÇA</h2>
            <div class="row">
              <div class="col-md-12">
                <div class="field">
                  <small> CID CAPÍTULO </small>
                  <span>
                    <?= $dados_familiar['catinic'] . " ATÉ " . $dados_familiar['catfim'] . " - " . $dados_familiar['hab_cid10_capitulo']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="field">
                  <small> CID GRUPO </small>
                  <span>
                    <?= $dados_familiar['catinicg'] . " ATÉ " . $dados_familiar['catinicg'] . " - " . $dados_familiar['hab_cid10_grupo']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="field">
                  <small> CID CATEGORIA </small>
                  <span>
                    <?= $dados_familiar['cat'] . " - " . $dados_familiar['hab_cid10_categoria']; ?>
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="field">
                  <small> CAD. ÚNICO </small>
                  <span>
                    <?= $dados_familiar['cadastro_unico']; ?>
                  </span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="field">
                  <small> NIS </small>
                  <span>
                    <?= $dados_familiar['nis']; ?>
                  </span>
                </div>
              </div>
            </div>
          </fieldset>
          <?php
        }
        ?>
      </div>
    </section>
  </div>
</div>