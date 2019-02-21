<?php include('template/topo.php'); ?>
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/summernote/dist/summernote.css" rel="stylesheet">
<?php include('template/sidebar.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0));
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

$result = $db->prepare("SELECT hc.participar_programa_id, sl.nome AS snch_loteamento, hc.seg_usuario_pai_id, hc.situacao, hc.validacao, snch.nome AS snch_apf, hc.snch_apf_id, hp.trab_mesmo_endereco, hc.area_risco_insalubre, hpe.latitude, hpe.longitude, hc.data_cadastro, hc.data_cadastro_anterior, hc.acompanhamento_socio_assistencial, hc.morador_rua, hpe.coabitacao_involuntaria, hpe.aluguel_valor, hpe.aluguel_social, hpe.alugada, blog.nome AS bsc_logradouro, bet.nome AS bsc_endereco_tipo, bdt.nome AS bsc_deficiencia_tipo, hp.casamento_data,
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
$result->bindValue(1, $param);
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

$candidato_id = $param;
$pessoa_id = pesquisar_tabela("hab_pessoa_id", "hab_candidato", "id", "=", $candidato_id, "");
$bcp = pesquisar_tabela("id", "hab_pessoa_beneficio", "hab_pessoa_id", "=", $pessoa_id, "AND hab_beneficio_social_id = 2");
$bolsa_familia = pesquisar_tabela("id", "hab_pessoa_beneficio", "hab_pessoa_id", "=", $pessoa_id, "AND hab_beneficio_social_id = 3");

// CONJUGE------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$conjuge_id = pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $param, "AND tipo = 0");

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
$result->bindValue(1, $param);
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
$result->bindValue(1, $param);
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

// SITUAÇÃO------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$result = $db->prepare("SELECT c.data_update, c.data_cadastro, cs.hab_tipo_situacao_id
                        FROM hab_candidato_situacao cs
                        LEFT JOIN hab_candidato AS c ON c.id = cs.hab_candidato_id
			WHERE cs.id IN (SELECT MAX(id) FROM hab_candidato_situacao WHERE hab_candidato_id = ? GROUP BY hab_candidato_id)
                        GROUP BY cs.hab_candidato_id");
$result->bindValue(1, $param);
$result->execute();

$dados_situacao = $result->fetch(PDO::FETCH_ASSOC);
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//IF ABAIXO PARA VERIFICAR USUÁRIOS NA PÁGINA
if ($param != null && $param != '' && $param != NULL && $param != 0) {
  $vf_usuario_pagina = vf_usuario_pagina($GLOBALS['urlPasta'] . "/" . $GLOBALS['urlArquivo']);
  if ($vf_usuario_pagina > 0) {
    $nome_usuario = info_usuario($vf_usuario_pagina);
  } else {
    $nome_usuario = 0;
  }
} else {
  $vf_usuario_pagina = 0;
  $nome_usuario = 0;
}
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
?>

<input type="hidden" id='vf_usuario_pagina' name='vf_usuario_pagina' value="<?= $vf_usuario_pagina; ?>"/>
<input type="hidden" id='nome_usuario' name='nome_usuario' value="<?= $nome_usuario; ?>"/>

<div id="div_container" class="card icons-demo">
  <div class="card-header cw-header <?= is_numeric($dados_candidato['cadastro_retroativo_ano']) ? 'palette-Black' : 'palette-Teal-600' ?> bg">
    <div class="cwh-year">Candidato</div>
    <div class="cwh-day">Visualização</div>
    <?php
    if (vf_objeto_acao("imprimir")) {
      ?>
      <a target="_blank" href="<?= PORTAL_URL; ?>sistema/candidato/result_impressao/<?= $param; ?>" class="btn palette-Blue-Grey-600 bg print btn-float waves-effect waves-float waves-float">
        <i class="zmdi zmdi-print"></i>
      </a>
      <?php
    }
    if (vf_objeto_acao("editar") && $dados_candidato['seg_usuario_pai_id'] == $_SESSION['id']) {
      ?>
      <a href="<?= PORTAL_URL; ?>sistema/candidato/etapa1/<?= $param; ?>" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float">
        <i class="zmdi zmdi-edit"></i>
      </a>
      <?php
    }
    ?>

  </div>

  <?php
  if (isset($dados_situacao['hab_tipo_situacao_id'])) {
    if ($dados_situacao['hab_tipo_situacao_id'] == 3) {
      $data_atualizacao = $dados_situacao['data_update'] == NULL || $dados_situacao['data_update'] == '' || $dados_situacao['data_update'] == '0000-00-00 00:00:00' ? $dados_situacao['data_cadastro'] : $dados_situacao['data_update'];

      $qtd_dias = diff_data_dias2(date('Y-m-d'), date('Y-m-d', strtotime("+730 days", strtotime($data_atualizacao))));
      ?>

      <div class="row">
        <div class="col-md-4 col-md-offset-4">
          <div class="validade <?= $qtd_dias > 460 ? 'st-valido' : ($qtd_dias > 365 ? 'st-vencendo' : ($qtd_dias >= 0 ? 'st-vencido' : 'st-nulo')) ?>">
            <small><?= $qtd_dias >= 0 ? 'CADASTRO VÁLIDO' : 'CADASTRO COM PRAZO DE VALIDADE VENCIDO'; ?></small>
            <span><?= ctexto(diff_data_dias3(date('Y-m-d'), date('Y-m-d', strtotime("+730 days", strtotime($data_atualizacao)))), "mai"); ?></span>
          </div>
        </div>
      </div>

      <?php
    }
  }
  ?>

  <div class="card-body card-padding-sm">
    <div class="old-records">
      <div class="">
        <div class="col-md-5 name-program">
          <small>PROGRAMA</small>
          <span><?= ctexto($dados_candidato['programa'], "mai"); ?></span>
        </div>
        <div class="col-md-5 name-subprogram">
          <small>SUBPROGRAMA</small>
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

              <?php
              if (isset($candidadoAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($candidadoAnexos['cpf']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($candidadoAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($candidadoAnexos['cie']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_nome" name="check_NOME" class="checkbox-validacao" rel="NOME DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  NOME
                </label>
                <?php
              } else {
                ?>
                <small>
                  NOME
                </small>
                <?php
              }
              ?>

              <span class="nome">
                <?= $dados_candidato['nome'] == '' ? '' : $dados_candidato['nome']; ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>

                  <a id="modalDefaultClick" href="#modalDefault" rel="NOME DO TITULAR" relkey="titular_nome" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>

                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['rg']) || isset($candidadoAnexos['cpf']) || isset($candidadoAnexos['cnh']) || isset($candidadoAnexos['cie'])) {
                  ?>
                                                                                                                                                                                                                                    <!--                  <a href="../../../../../<?= isset($candidadoAnexos['rg']) ? $candidadoAnexos['rg'] : isset($candidadoAnexos['cpf']) ? $candidadoAnexos['cpf'] : isset($candidadoAnexos['cnh']) ? $candidadoAnexos['cnh'] : $candidadoAnexos['cie']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                      </a>-->
                  <?php
                }
                ?>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['cpf']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_cpf" name="check_CPF" class="checkbox-validacao" rel="CPF DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  CPF
                </label>
                <?php
              } else {
                ?>
                <small>
                  CPF
                </small>
                <?php
              }
              ?>

              <span>

                <?= $dados_candidato['cpf'] == '' ? '' : $dados_candidato['cpf']; ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>

                  <a id="modalDefaultClick" href="#modalDefault" rel="CPF DO TITULAR" relkey="titular_cpf" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>

                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['cpf'])) {
                  ?>
                  <a href="../../../../../<?= $candidadoAnexos['cpf']; ?>" class="attachment" target="_blank">
                    <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                COR/RAÇA
              </small>
              <span>
                <?= $dados_candidato['cor']; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($candidadoAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_data_nascimento" name="check_NASCIMENTO" class="checkbox-validacao" rel="DATA DE NASCIMENTO DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  NASCIMENTO
                </label>
                <?php
              } else {
                ?>
                <small>
                  NASCIMENTO
                </small>
                <?php
              }
              ?>

              <span>
                <?= $dados_candidato['data_nascimento'] == "" || $dados_candidato['data_nascimento'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['data_nascimento']); ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE NASCIMENTO DO TITULAR" relkey="titular_data_nascimento" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['rg']) || isset($candidadoAnexos['cnh'])) {
                  ?>
                                                                                                                                                                                                                                                    <!--                  <a href="../../../../../<?= isset($candidadoAnexos['rg']) ? $candidadoAnexos['rg'] : $candidadoAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                      </a>-->
                  <?php
                }
                ?>

              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['certidao_nascimento']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_bsc_sexo_id" name="check_SEXO" class="checkbox-validacao" rel="SEXO DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  SEXO
                </label>
                <?php
              } else {
                ?>
                <small>
                  SEXO
                </small>
                <?php
              }
              ?>

              <span>
                <?= $dados_candidato['bsc_sexo_id'] == 1 ? "MASCULINO" : "FEMININO"; ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="SEXO DO TITULAR" relkey="titular_bsc_sexo_id" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['certidao_nascimento'])) {
                  ?>
                                                                                                                                                                                                                                                    <!--                  <a href="../../../../../<?= $candidadoAnexos['certidao_nascimento']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                      </a>-->
                  <?php
                }
                ?>
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['rg_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_rg_numero" name="check_RG" class="checkbox-validacao" rel="RG DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  RG
                </label>
                <?php
              } else {
                ?>
                <small>
                  RG
                </small>
                <?php
              }
              ?>

              <span>
                <?= $dados_candidato['rg_numero'] == "" ? "" : $dados_candidato['rg_numero']; ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="RG DO TITULAR" relkey="titular_rg_numero" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['rg'])) {
                  ?>
                  <a href="../../../../../<?= $candidadoAnexos['rg']; ?>" class="attachment" target="_blank">
                    <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_rg_orgao_expedicao" name="check_ORGAO_EXPEDIDOR" class="checkbox-validacao" rel="ÓRGÃO EXPEDIDOR DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  ÓRGÃO EXPEDIDOR
                </label>
                <?php
              } else {
                ?>
                <small>
                  ÓRGÃO EXPEDIDOR
                </small>
                <?php
              }
              ?>

              <span>
                <?= $dados_candidato['rg_orgao_expedicao_id'] == 1 ? "SSP/" : "DETRAN/"; ?><?= $dados_candidato['rg_uf_expedicao']; ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="ÓRGÃO EXPEDIDOR DO TITULAR" relkey="titular_rg_orgao_expedicao" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  </a>-->
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['rg'])) {
                  ?>
                                                                                                                                                                                                                                                    <!--                  <a href="../../../../../<?= $candidadoAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                      </a>-->
                  <?php
                }
                ?>

              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_rg_uf_expedicao" name="check_UF_EXPEDIDOR" class="checkbox-validacao" rel="UF DE EXPEDIÇÃO DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  UF EXPEDIDOR
                </label>
                <?php
              } else {
                ?>
                <small>
                  UF EXPEDIDOR
                </small>
                <?php
              }
              ?>

              <span>
                <?= $dados_candidato['rg_uf_expedicao']; ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="UF DE EXPEDIÇÃO DO TITULAR" relkey="titular_rg_uf_expedicao" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['rg'])) {
                  ?>
                                                                                                                                                                                                                                                    <!--                  <a href="../../../../../<?= $candidadoAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                      </a>-->
                  <?php
                }
                ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_rg_data_expedicao" name="check_DATA_EXPEDICAO" class="checkbox-validacao" rel="DATA DE EXPEDIÇÃO DO TITULAR" value="option1" type="checkbox"/>
                  <i class="input-close"></i>
                  DATA EXPEDIÇÃO
                </label>
                <?php
              } else {
                ?>
                <small>
                  DATA EXPEDIÇÃO
                </small>
                <?php
              }
              ?>

              <span>
                <?= $dados_candidato['rg_data_expedicao'] == "" || $dados_candidato['rg_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['rg_data_expedicao']); ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE EXPEDIÇÃO DO TITULAR" relkey="titular_rg_data_expedicao" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['rg'])) {
                  ?>
                                                                                                                                                                                                                                                    <!--                  <a href="../../../../../<?= $candidadoAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                      </a>-->
                  <?php
                }
                ?>
              </span>
            </div>
          </div>
        </div>

        <div <?= $dados_candidato['cnh_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_cnh_numero" name="check_CNH" class="checkbox-validacao" rel="CNH DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  CNH
                </label>
                <?php
              } else {
                ?>
                <small>
                  CNH
                </small>
                <?php
              }
              ?>

              <span>
                <?= $dados_candidato['cnh_numero'] == "" ? "" : $dados_candidato['cnh_numero']; ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="CNH DO TITULAR" relkey="titular_cnh_numero" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['cnh'])) {
                  ?>
                  <a href="../../../../../<?= $candidadoAnexos['cnh']; ?>" class="attachment" target="_blank">
                    <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_cnh_uf_expedicao" name="check_UF_EXPEDIDOR2" class="checkbox-validacao" rel="UF DE EXPEDIÇÃO DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  UF EXPEDIDOR
                </label>
                <?php
              } else {
                ?>
                <small>
                  UF EXPEDIDOR
                </small>
                <?php
              }
              ?>

              <span>
                <?= $dados_candidato['cnh_uf_expedicao']; ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="UF DE EXPEDIÇÃO DO TITULAR" relkey="titular_cnh_uf_expedicao" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['cnh'])) {
                  ?>
                                                                                                                                                                                                                                                    <!--                  <a href="../../../../../<?= $candidadoAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                      </a>-->
                  <?php
                }
                ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_cnh_data_expedicao" name="check_UF_EXPEDICAO2" class="checkbox-validacao" rel="UF DE EXPEDIÇÃO DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  DATA EXPEDIÇÃO
                </label>
                <?php
              } else {
                ?>
                <small>
                  DATA EXPEDIÇÃO
                </small>
                <?php
              }
              ?>

              <span>
                <?= $dados_candidato['cnh_data_expedicao'] == "" || $dados_candidato['cnh_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cnh_data_expedicao']); ?>
                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="UF DE EXPEDIÇÃO DO TITULAR" relkey="titular_cnh_data_expedicao" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['cnh'])) {
                  ?>
                                                                                                                                                                                                                                                    <!--                  <a href="../../../../../<?= $candidadoAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                      </a>-->
                  <?php
                }
                ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_cnh_data_validade" name="check_DATA_VALIDADE" class="checkbox-validacao" rel="DATA DE VALIDADE DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  DATA DE VALIDADE
                </label>
                <?php
              } else {
                ?>
                <small>
                  DATA DE VALIDADE
                </small>
                <?php
              }
              ?>

              <span>
                <?= $dados_candidato['cnh_data_validade'] == "" || $dados_candidato['cnh_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cnh_data_validade']); ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE VALIDADE DO TITULAR" relkey="titular_cnh_data_validade" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['cnh'])) {
                  ?>
                                                                                                                                                                                                                                                    <!--                  <a href="../../../../../<?= $candidadoAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                      </a>-->
                  <?php
                }
                ?>
              </span>
            </div>
          </div>
        </div>

        <div <?= is_numeric($dados_candidato['bsc_municipio_id_natural']) ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
           <i class="input-close"></i>-->
                NACIONALIDADE
              </small>
              <span>
                BRASILEIRO
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>

          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_bsc_municipio_id_natural" name="check_NATURALIDADE" class="checkbox-validacao" rel="NATURALIDADE DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  NATURALIDADE
                </label>
                <?php
              } else {
                ?>
                <small>
                  NATURALIDADE
                </small>
                <?php
              }
              ?>

              <span>
                <?= nome_estado_municipio(estado_do_municipio($dados_candidato['bsc_municipio_id_natural'])); ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="NATURALIDADE DO TITULAR" relkey="titular_bsc_municipio_id_natural" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['rg'])) {
                  ?>
                                                                                                                                                                                                                              <!--                                        <a href="../../../../../<?= $candidadoAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                      </a>-->
                  <?php
                }
                ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_bsc_municipio_id_natural" name="check_CIDADE" class="checkbox-validacao" rel="CIDADE DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  CIDADE
                </label>
                <?php
              } else {
                ?>
                <small>
                  CIDADE
                </small>
                <?php
              }
              ?>

              <span>
                <?= nome_municipio($dados_candidato['bsc_municipio_id_natural']); ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="CIDADE DO TITULAR" relkey="titular_bsc_municipio_id_natural" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['rg'])) {
                  ?>
                                                                                                                                                                                                                                                    <!--                  <a href="../../../../../<?= $candidadoAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                      </a>-->
                  <?php
                }
                ?>
              </span>
            </div>
          </div>
        </div>

        <div <?= is_numeric($dados_candidato['bsc_municipio_id_natural']) ? "style='display: none'" : ""; ?> class="row">
          <div class="col-md-3">
            <div class="field">

              <?php
              if (isset($candidadoAnexos['cie']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_cie" name="check_CIE" class="checkbox-validacao" rel="NACIONALIDADE DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  NACIONALIDADE
                </label>
                <?php
              } else {
                ?>
                <small>
                  NACIONALIDADE
                </small>
                <?php
              }
              ?>

              <span>
                ESTRANGEIRO
                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="NACIONALIDADE DO TITULAR" relkey="titular_cie" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['cie'])) {
                  ?>
                  <a href="../../../../../<?= $candidadoAnexos['cie']; ?>" class="attachment" target="_blank">
                    <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
           <i class="input-close"></i>-->
                CÓD. RNE
              </small>
              <span>
                <?= $dados_candidato['cie_rne'] == "" ? "" : $dados_candidato['cie_rne']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
           <i class="input-close"></i>-->
                CLASSIFICAÇÃO
              </small>
              <span>
                <?= $dados_candidato['bsc_cie_classificacao']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
           <i class="input-close"></i>-->
                DATA DE EXPEDIÇÃO
              </small>
              <span>
                <?= $dados_candidato['cie_data_expedicao'] == "" || $dados_candidato['cie_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cie_data_expedicao']); ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
           <i class="input-close"></i>-->
                VALIDADE
              </small>
              <span>
                <?= $dados_candidato['cie_data_validade'] == "" || $dados_candidato['cie_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cie_data_validade']); ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">ENDEREÇO RESIDENCIAL</h2>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
           <i class="input-close"></i>-->
                MORADOR DE RUA?
              </small>
              <span>
                <?= $dados_candidato['morador_rua'] == 1 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
           <i class="input-close"></i>-->
                COABITAÇÃO INVOLUNTÁRIA?
              </small>
              <span>
                <?= $dados_candidato['coabitacao_involuntaria'] == 1 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
           <i class="input-close"></i>-->
                ÁREA DE RISCO OU INSALUBRE
              </small>
              <span>
                <?= $dados_candidato['area_risco_insalubre'] == 1 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
           <i class="input-close"></i>-->
                RESINDÊNCIA ALUGADA?
              </small>
              <span>
                <?= $dados_candidato['alugada'] == 1 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['alugada'] == 1 ? "" : "style='display: none'"; ?> class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
           <i class="input-close"></i>-->
                VALOR DO ALUGUEL
              </small>
              <span>
                <?= fdec($dados_candidato['aluguel_valor']); ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['alugada'] == 1 ? "" : "style='display: none'"; ?> class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                ALUGUEL SOCIAL
              </small>
              <span>
                <?= $dados_candidato['aluguel_social'] == 1 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <?php
              if (isset($candidadoAnexos['endereco']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                ?>
                <label class="checkbox checkbox-inline m-r-20">
                  <input id="check_titular_cep" name="check_CEP" class="checkbox-validacao" rel="CEP DO TITULAR" value="option1" type="checkbox">
                  <i class="input-close"></i>
                  CEP
                </label>
                <?php
              } else {
                ?>
                <small>
                  CEP
                </small>
                <?php
              }
              ?>

              <span>
                <?= $dados_candidato['cep'] == "" ? "" : $dados_candidato['cep']; ?>

                <?php
                if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <a id="modalDefaultClick" href="#modalDefault" rel="CEP DO TITULAR" relkey="titular_cep" data-toggle="modal" class="info">
                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

                <?php
                if (isset($candidadoAnexos['endereco'])) {
                  ?>
                  <a href="../../../../../<?= $candidadoAnexos['endereco']; ?>" class="attachment" target="_blank">
                    <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                  </a>
                  <?php
                }
                ?>

              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                TIPO DE ENDEREÇO
              </small>
              <span>
                <?= $dados_candidato['bsc_endereco_tipo']; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                MUNICÍPIO
              </small>
              <span>
                <?= $dados_candidato['bsc_municipio']; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                DATA DE INÍCIO NA RESIDÊNCIA
              </small>
              <span>
                <?= obterDataBRTimestamp($dados_candidato['data_inicio_residencia_municipio']); ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                TIPO DE LOGRADOURO
              </small>
              <span>
                <?= $dados_candidato['bsc_logradouro']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                LOGRADOURO
              </small>
              <span>
                <?= $dados_candidato['logradouro'] == "" ? "" : $dados_candidato['logradouro']; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                NÚMERO
              </small>
              <span>
                <?= $dados_candidato['numero'] == "" ? "" : $dados_candidato['numero']; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                BAIRRO
              </small>
              <span>
                <?= $dados_candidato['bairro'] == "" ? "" : $dados_candidato['bairro']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                QUADRA
              </small>
              <span>
                <?= $dados_candidato['quadra'] == "" ? "" : $dados_candidato['quadra']; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                CASA
              </small>
              <span>
                <?= $dados_candidato['lote'] == "" ? "" : $dados_candidato['lote']; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                COMPLEMENTO
              </small>
              <span>
                <?= $dados_candidato['complemento'] == "" ? "" : $dados_candidato['complemento']; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                LATITUDE
              </small>
              <span>
                <?= $dados_candidato['latitude'] == "" ? "" : $dados_candidato['latitude']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                LONGITUDE
              </small>
              <span>
                <?= $dados_candidato['longitude'] == "" ? "" : $dados_candidato['longitude']; ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">INFORMAÇÕES DE CONTATO</h2>
        <div class="row">
          <div class="col-md-6">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
           <i class="input-close"></i>-->
                E-MAIL PESSOAL
              </small>
              <span>
                <?= $dados_candidato['email'] == "" ? "" : $dados_candidato['email']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                RESIDENCIAL
              </small>
              <span>
                <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 1"); ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                CELULAR
              </small>
              <span>
                <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 2"); ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                COMERCIAL
              </small>
              <span>
                <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                RAMAL
              </small>
              <span>
                <?= pesquisar_tabela("ramal", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                CONTATO TELEFÔNICO
              </small>
              <span>
                <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 4"); ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                NOME DO CONTATO
              </small>
              <span>
                <?= pesquisar_tabela("recado_nome", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 4"); ?>
                <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">INFORMAÇÕES DE TRABALHO</h2>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                VOCÊ TRABALHA?
              </small>
              <span>
                <?= $dados_candidato['cargo'] != "" ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>
                
                                <a href="../../../../../<?= $candidadoAnexos['endereco']; ?>" class="attachment" target="_blank">
                                  <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['cargo'] != "" ? "" : "style='display: none'"; ?> class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                TRABALHA NO MESMO ENDEREÇO?
              </small>
              <span>
                <?= $dados_candidato['trab_mesmo_endereco'] == 1 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['cargo'] != "" ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                OCUPAÇÃO/CARGO/FUNÇÃO
              </small>
              <span>
                <?= $dados_candidato['cargo'] == "" ? "" : $dados_candidato['cargo']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                LOCAL DE TRABALHO
              </small>
              <span>
                <?= $dados_candidato['instituicao'] == "" ? "" : $dados_candidato['instituicao']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-4">
            <div class="field">
              <small>
  <!--                <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                ENDEREÇO
              </small>
              <span>
                <?= $dados_candidato['endereco'] == "" ? "" : $dados_candidato['endereco']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-2">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                DATA INÍCIO
              </small>
              <span>
                <?= $dados_candidato['data_inicio'] == "" || $dados_candidato['data_inicio'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['data_inicio']); ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
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
                <small>
  <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                  TIPO DE RENDA
                </small>
                <span>
                  <?= $pessoa_renda['renda_tipo']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                  VALOR DA RENDA
                </small>
                <span>
                  <?= fdec($pessoa_renda['valor']); ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
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
              <small>
<!--                <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                PROVEDOR DO LAR?
              </small>
              <span>
                <?= $dados_candidato['provedor_lar'] == 1 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">ESCOLARIDADE</h2>
        <div class="row">
          <div class="col-md-6">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                GRAU ESCOLAR
              </small>
              <span>
                <?= $dados_candidato['hab_grau_escolar']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
 <!--                <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                ESTÁ ESTUDANDO?
              </small>
              <span>
                <?= $dados_candidato['instituicao_nome'] != "" ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['instituicao_nome'] != "" ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
      <!--                <input value="option1" type="checkbox">
                      <i class="input-close"></i>-->
                NATUREZA DA INSTITUIÇÃO
              </small>
              <span>
                <?= $dados_candidato['hab_instituicao_natureza']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="field">
              <small>
  <!--                <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                NOME DA INSTITUIÇÃO
              </small>
              <span>
                <?= $dados_candidato['instituicao_nome']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                SÉRIE/PERÍODO
              </small>
              <span>
                <?= $dados_candidato['serie_periodo']; ?>°
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['instituicao_nome'] != "" ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
  <!--                <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                FINANCIADO POR MEIOS PRÓPRIOS?
              </small>
              <span>
                <?= $dados_candidato['hab_financia_natureza_id'] == 1 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div <?= $dados_candidato['instituicao_nome'] != "" && $dados_candidato['hab_financia_natureza_id'] == 2 ? "" : "style='display: none'"; ?> class="row">
          <div class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                PROGRAMA SOCIAL
              </small>
              <span>
                <?= $dados_candidato['hab_programa_social']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-4">
            <div class="field">
              <small>
   <!--                <input value="option1" type="checkbox">
                   <i class="input-close"></i>-->
                PORCENTAGEM
              </small>
              <span>
                <?= $dados_candidato['bolsa_percentual']; ?>%
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">SITUAÇÃO CONJUGAL</h2>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                ESTADO CIVIL
              </small>
              <span>
                <?= $dados_candidato['estado_civil']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['estado_civil_id'] == 6 || $dados_candidato['estado_civil_id'] == 7 || $dados_candidato['estado_civil_id'] == 8 ? '' : 'style="display: none"'; ?> class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                DATA DE CASAMENTO
              </small>
              <span>
                <?= obterDataBRTimestamp($dados_candidato['casamento_data']); ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                UNIÃO ESTÁVEL
              </small>
              <span>
                <?= $dados_candidato['uniao_estavel'] == 1 ? 'SIM' : 'NÃO'; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">FILIAÇÃO</h2>
        <div class="row">
          <div class="col-md-6">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                NOME DA MÃE
              </small>
              <span>
                <?= $dados_candidato['mae_nome']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                NASCIMENTO
              </small>
              <span>
                <?= obterDataBRTimestamp($dados_candidato['mae_data_nascimento']); ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                CONTATO
              </small>
              <span>
                <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 5"); ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">INFORMAÇÕES COMPLEMENTARES</h2>
        <div class="row">
          <div class="col-md-3">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                POSSUI ALGUM TIPO DE DEFICIÊNCIA?
              </small>
              <span>
                <?= $dados_candidato['deficiencia'] == 1 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="field">
              <small>
  <!--                <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                TIPO DE DEFICIÊNCIA
              </small>
              <span>
                <?= $dados_candidato['bsc_deficiencia_tipo']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                POSSUI ALGUM TIPO DE DOENÇA CRÔNICA INCAPACITANTE?
              </small>
              <span>
                <?= $dados_candidato['doenca_cronica'] == 1 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">CLASSIFICAÇÃO INTERNACIONAL DE DOENÇA</h2>
        <div class="row">
          <div class="col-md-12">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                CID CAPÍTULO
              </small>
              <span>
                <?= $dados_candidato['catinic'] . " ATÉ " . $dados_candidato['catfim'] . " - " . $dados_candidato['hab_cid10_capitulo']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-12">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                CID GRUPO
              </small>
              <span>
                <?= $dados_candidato['catinicg'] . " ATÉ " . $dados_candidato['catinicg'] . " - " . $dados_candidato['hab_cid10_grupo']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-12">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                CID CATEGORIA
              </small>
              <span>
                <?= $dados_candidato['cat'] . " - " . $dados_candidato['hab_cid10_categoria']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                CAD. ÚNICO
              </small>
              <span>
                <?= $dados_candidato['cadastro_unico']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                NIS
              </small>
              <span>
                <?= $dados_candidato['nis']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <h2 class="m-b-30">SOCIAL</h2>
        <div class="row">
          <div class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                OCORRÊNCIA DE MARIA DA PENHA?
              </small>
              <span>
                <?= $dados_candidato['lei_maria_penha'] == 1 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                POSSUI ALGUM BENEFÍCIO SOCIAL?
              </small>
              <span>
                <?= is_numeric($bcp) || is_numeric($bolsa_familia) ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div <?= is_numeric($bcp) ? "" : "style='display:none'"; ?> class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                BENEFÍCIO SOCIAL
              </small>
              <span>
                <?= "BPC (Benefício de Prestação Continuada)" ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div <?= is_numeric($bolsa_familia) ? "" : "style='display:none'"; ?> class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                BENEFÍCIO SOCIAL?
              </small>
              <span>
                <?= "BOLSA FAMÍLIA" ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                JÁ FOI BENEFICIDADO EM ALGUM PROGRAMA HABITACIONAL?
              </small>
              <span>
                <?= $dados_candidato['snch_apf_id'] > 0 ? "SIM" : "NÃO"; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
              </span>
            </div>
          </div>
          <div <?= $dados_candidato['snch_apf_id'] > 0 ? "" : "style='display: none'"; ?> class="col-md-4">
            <div class="field">
              <small>
<!--                <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                PROGRAMA
              </small>
              <span>
                <?= $dados_candidato['snch_apf']; ?>
                <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                  <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                </a>-->
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

                <?php
                if (isset($conjugeAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($conjugeAnexos['cpf']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($conjugeAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($conjugeAnexos['cie']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_nome" name="check_NOME" class="checkbox-validacao" rel="NOME DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    NOME
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    NOME
                  </small>
                  <?php
                }
                ?>

                <span class="nome">
                  <?= $dados_conjuge['nome'] == '' ? '' : $dados_conjuge['nome']; ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="NOME DO CÔNJUGE" relkey="conjuge_nome" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="field">


                <?php
                if (isset($conjugeAnexos['cpf']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_cpf" name="check_CPF" class="checkbox-validacao" rel="CPF DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    CPF
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    CPF
                  </small>
                  <?php
                }
                ?>
                <span>

                  <?= $dados_conjuge['cpf'] == '' ? '' : $dados_conjuge['cpf']; ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>

                    <a id="modalDefaultClick" href="#modalDefault" rel="CPF DO CÔNJUGE" relkey="conjuge_cpf" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>

                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['cpf'])) {
                    ?>
                    <a href="../../../../../<?= $conjugeAnexos['cpf']; ?>" class="attachment" target="_blank">
                      <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                  COR/RAÇA
                </small>
                <span>
                  <?= $dados_conjuge['cor']; ?>
                  <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                  </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($conjugeAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_data_nascimento" name="check_NASCIMENTO" class="checkbox-validacao" rel="DATA DE NASCIMENTO DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    NASCIMENTO
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    NASCIMENTO
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= $dados_conjuge['data_nascimento'] == "" || $dados_conjuge['data_nascimento'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['data_nascimento']); ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE NASCIMENTO DO CÔNJUGE" relkey="conjuge_data_nascimento" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['rg']) || isset($conjugeAnexos['cnh'])) {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= isset($conjugeAnexos['rg']) ? $conjugeAnexos['rg'] : $conjugeAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                    <?php
                  }
                  ?>

                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['certidao_nascimento']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_bsc_sexo_id" name="check_SEXO" class="checkbox-validacao" rel="SEXO DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    SEXO
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    SEXO
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= $dados_conjuge['bsc_sexo_id'] == 1 ? "MASCULINO" : "FEMININO"; ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="SEXO DO CÔNJUGE" relkey="conjuge_bsc_sexo_id" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['certidao_nascimento'])) {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $conjugeAnexos['certidao_nascimento']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['rg_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_rg_numero" name="check_RG" class="checkbox-validacao" rel="RG DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    RG
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    RG
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= $dados_conjuge['rg_numero'] == "" ? "" : $dados_conjuge['rg_numero']; ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="RG DO CÔNJUGE" relkey="conjuge_rg_numero" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['rg'])) {
                    ?>
                    <a href="../../../../../<?= $conjugeAnexos['rg']; ?>" class="attachment" target="_blank">
                      <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_rg_orgao_expedicao" name="check_ORGAO_EXPEDIDOR" class="checkbox-validacao" rel="ÓRGÃO EXPEDIDOR DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    ÓRGÃO EXPEDIDOR
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    ÓRGÃO EXPEDIDOR
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= $dados_conjuge['rg_orgao_expedicao_id'] == 1 ? "SSP/" : "DETRAN/"; ?><?= $dados_conjuge['rg_uf_expedicao']; ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="ÓRGÃO EXPEDIDOR DO CÔNJUGE" relkey="conjuge_rg_orgao_expedicao" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['rg'])) {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $conjugeAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                    <?php
                  }
                  ?>

                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_rg_uf_expedicao" name="check_UF_EXPEDIDOR" class="checkbox-validacao" rel="UF DE EXPEDIÇÃO DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    UF EXPEDIDOR
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    UF EXPEDIDOR
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= $dados_conjuge['rg_uf_expedicao']; ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="UF DE EXPEDIÇÃO DO CÔNJUGE" relkey="conjuge_rg_uf_expedicao" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['rg'])) {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $conjugeAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_rg_data_expedicao" name="check_DATA_EXPEDICAO" class="checkbox-validacao" rel="DATA DE EXPEDIÇÃO DO CÔNJUGE" value="option1" type="checkbox"/>
                    <i class="input-close"></i>
                    DATA EXPEDIÇÃO
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    DATA EXPEDIÇÃO
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= $dados_conjuge['rg_data_expedicao'] == "" || $dados_conjuge['rg_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['rg_data_expedicao']); ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE EXPEDIÇÃO DO CÔNJUGE" relkey="conjuge_rg_data_expedicao" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['rg'])) {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $conjugeAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
          </div>

          <div <?= $dados_conjuge['cnh_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_cnh_numero" name="check_CNH" class="checkbox-validacao" rel="CNH DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    CNH
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    CNH
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= $dados_conjuge['cnh_numero'] == "" ? "" : $dados_conjuge['cnh_numero']; ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="CNH DO CÔNJUGE" relkey="cnh_numero" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['cnh'])) {
                    ?>
                    <a href="../../../../../<?= $conjugeAnexos['cnh']; ?>" class="attachment" target="_blank">
                      <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_cnh_uf_expedicao" name="check_UF_EXPEDIDOR2" class="checkbox-validacao" rel="UF DE EXPEDIÇÃO DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    UF EXPEDIDOR
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    UF EXPEDIDOR
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= $dados_conjuge['cnh_uf_expedicao']; ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="UF DE EXPEDIÇÃO DO CÔNJUGE" relkey="conjuge_cnh_uf_expedicao" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['cnh'])) {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $conjugeAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_cnh_data_expedicao" name="check_UF_EXPEDICAO2" class="checkbox-validacao" rel="UF DE EXPEDIÇÃO DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    DATA EXPEDIÇÃO
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    DATA EXPEDIÇÃO
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= $dados_conjuge['cnh_data_expedicao'] == "" || $dados_conjuge['cnh_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cnh_data_expedicao']); ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="UF DE EXPEDIÇÃO DO CÔNJUGE" relkey="conjuge_cnh_data_expedicao" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['cnh'])) {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $conjugeAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_cnh_data_validade" name="check_DATA_VALIDADE" class="checkbox-validacao" rel="DATA DE VALIDADE DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    DATA DE VALIDADE
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    DATA DE VALIDADE
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= $dados_conjuge['cnh_data_validade'] == "" || $dados_conjuge['cnh_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cnh_data_validade']); ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE VALIDADE DO CÔNJUGE" relkey="conjuge_cnh_data_validade" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['cnh'])) {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $conjugeAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
          </div>

          <div <?= is_numeric($dados_conjuge['bsc_municipio_id_natural']) ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                  NACIONALIDADE
                </small>
                <span>
                  BRASILEIRO
                  <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                    <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                  </a>-->
                </span>
              </div>
            </div>

            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_bsc_municipio_id_natural" name="check_NATURALIDADE" class="checkbox-validacao" rel="NATURALIDADE DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    NATURALIDADE
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    NATURALIDADE
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= nome_estado_municipio(estado_do_municipio($dados_conjuge['bsc_municipio_id_natural'])); ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="NATURALIDADE DO CÔNJUGE" relkey="conjuge_bsc_municipio_id_natural" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['rg'])) {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $conjugeAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_bsc_municipio_id_natural" name="check_CIDADE" class="checkbox-validacao" rel="CIDADE DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    CIDADE
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    CIDADE
                  </small>
                  <?php
                }
                ?>

                <span>
                  <?= nome_municipio($dados_conjuge['bsc_municipio_id_natural']); ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="CIDADE DO CÔNJUGE" relkey="conjuge_bsc_municipio_id_natural" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['rg'])) {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $conjugeAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
          </div>

          <div <?= is_numeric($dados_conjuge['bsc_municipio_id_natural']) ? "style='display: none'" : ""; ?> class="row">
            <div class="col-md-3">
              <div class="field">

                <?php
                if (isset($conjugeAnexos['cie']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_cie" name="check_NACIONALIDADE" class="checkbox-validacao" rel="NACIONALIDADE DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    NACIONALIDADE
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    NACIONALIDADE
                  </small>
                  <?php
                }
                ?>

                <span>
                  ESTRANGEIRO

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="NACIONALIDADE DO CÔNJUGE" relkey="conjuge_cie" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['cie'])) {
                    ?>
                    <a href="../../../../../<?= $conjugeAnexos['cie']; ?>" class="attachment" target="_blank">
                      <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                  CÓD. RNE
                </small>
                <span>
                  <?= $dados_conjuge['cie_rne'] == "" ? "" : $dados_conjuge['cie_rne']; ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="CÓD. RNE DO CÔNJUGE" relkey="conjuge_cie_rne" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                  CLASSIFICAÇÃO
                </small>
                <span>
                  <?= $dados_conjuge['bsc_cie_classificacao']; ?>
                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="CLASSIFICAÇÃO DO CÔNJUGE" relkey="conjuge_bsc_cie_classificacao" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                  DATA DE EXPEDIÇÃO
                </small>
                <span>
                  <?= $dados_conjuge['cie_data_expedicao'] == "" || $dados_conjuge['cie_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cie_data_expedicao']); ?>
                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE EXPEDIÇÃO DO CÔNJUGE" relkey="conjuge_cie_data_expedicao" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                <input value="option1" type="checkbox">
             <i class="input-close"></i>-->
                  VALIDADE
                </small>
                <span>
                  <?= $dados_conjuge['cie_data_validade'] == "" || $dados_conjuge['cie_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cie_data_validade']); ?>
                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="VALIDADE DO CÔNJUGE" relkey="conjuge_cie_data_validade" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">ENDEREÇO RESIDENCIAL</h2>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                  MORA NO MESMO ENDEREÇO?
                </small>
                <span>
                  <?= $dados_conjuge['endereco_candidato'] == 1 ? "SIM" : "NÃO"; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div <?= $dados_conjuge['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="col-md-3">
              <div class="field">
                <?php
                if (isset($conjugeAnexos['endereco']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                  ?>
                  <label class="checkbox checkbox-inline m-r-20">
                    <input id="check_conjuge_cep" name="check_CEP" class="checkbox-validacao" rel="CEP DO CÔNJUGE" value="option1" type="checkbox">
                    <i class="input-close"></i>
                    CEP
                  </label>
                  <?php
                } else {
                  ?>
                  <small>
                    CEP
                  </small>
                  <?php
                }
                ?>
                <span>
                  <?= $dados_conjuge['cep'] == "" ? "" : $dados_conjuge['cep']; ?>

                  <?php
                  if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <a id="modalDefaultClick" href="#modalDefault" rel="CEP DO CÔNJUGE" relkey="conjuge_cep" data-toggle="modal" class="info">
                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>

                  <?php
                  if (isset($conjugeAnexos['endereco'])) {
                    ?>
                    <a href="../../../../../<?= $conjugeAnexos['endereco']; ?>" class="attachment" target="_blank">
                      <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                    </a>
                    <?php
                  }
                  ?>
                </span>
              </div>
            </div>
            <div <?= $dados_conjuge['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                  MUNICÍPIO
                </small>
                <span>
                  <?= $dados_conjuge['bsc_municipio']; ?>
                  <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="row">
            <div class="col-md-6">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                  LOGRADOURO
                </small>
                <span>
                  <?= $dados_conjuge['logradouro'] == "" ? "" : $dados_conjuge['logradouro']; ?>
                  <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                  NÚMERO
                </small>
                <span>
                  <?= $dados_conjuge['numero'] == "" ? "" : $dados_conjuge['numero']; ?>
                  <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                  BAIRRO
                </small>
                <span>
                  <?= $dados_conjuge['bairro'] == "" ? "" : $dados_conjuge['bairro']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                  QUADRA
                </small>
                <span>
                  <?= $dados_conjuge['quadra'] == "" ? "" : $dados_conjuge['quadra']; ?>
                  <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                  CASA
                </small>
                <span>
                  <?= $dados_conjuge['lote'] == "" ? "" : $dados_conjuge['lote']; ?>
                  <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                  COMPLEMENTO
                </small>
                <span>
                  <?= $dados_conjuge['complemento'] == "" ? "" : $dados_conjuge['complemento']; ?>
                  <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">INFORMAÇÕES DE CONTATO</h2>
          <div class="row">
            <div class="col-md-6">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                  E-MAIL PESSOAL
                </small>
                <span>
                  <?= $dados_conjuge['email'] == "" ? "" : $dados_conjuge['email']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                  RESIDENCIAL
                </small>
                <span>
                  <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $conjuge_pessoa_id, "AND hab_contato_tipo_id = 1"); ?>
                  <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                  CELULAR
                </small>
                <span>
                  <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $conjuge_pessoa_id, "AND hab_contato_tipo_id = 2"); ?>
                  <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
            <i class="input-close"></i>-->
                  COMERCIAL
                </small>
                <span>
                  <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $conjuge_pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
                  <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                  RAMAL
                </small>
                <span>
                  <?= pesquisar_tabela("ramal", "hab_pessoa_contato", "hab_pessoa_id", "=", $conjuge_pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
                  <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">INFORMAÇÕES DE TRABALHO</h2>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                  VOCÊ TRABALHA?
                </small>
                <span>
                  <?= $dados_conjuge['cargo'] != "" ? "SIM" : "NÃO"; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>
                  
                                    <a href="../../../../../<?= $conjugeAnexos['endereco']; ?>" class="attachment" target="_blank">
                                      <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div <?= $dados_conjuge['cargo'] != "" ? "" : "style='display: none'"; ?> class="col-md-3">
              <div class="field">
                <small>
     <!--                  <input value="option1" type="checkbox">
                       <i class="input-close"></i>-->
                  TRABALHA NO MESMO ENDEREÇO?
                </small>
                <span>
                  <?= $dados_conjuge['trab_mesmo_endereco'] == 1 ? "SIM" : "NÃO"; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['cargo'] != "" ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                  OCUPAÇÃO/CARGO/FUNÇÃO
                </small>
                <span>
                  <?= $dados_conjuge['cargo'] == "" ? "" : $dados_conjuge['cargo']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div <?= $dados_conjuge['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                  LOCAL DE TRABALHO
                </small>
                <span>
                  <?= $dados_conjuge['instituicao'] == "" ? "" : $dados_conjuge['instituicao']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div <?= $dados_conjuge['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-4">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                  ENDEREÇO
                </small>
                <span>
                  <?= $dados_conjuge['endereco'] == "" ? "" : $dados_conjuge['endereco']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-2">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                  DATA INÍCIO
                </small>
                <span>
                  <?= $dados_conjuge['data_inicio'] == "" || $dados_conjuge['data_inicio'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['data_inicio']); ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
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
                  if (isset($conjugeAnexos['renda_comprovada']) && $pessoa_renda['renda_tipo_id'] == 1 && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_conjuge_renda_tipo_id" name="check_RENDA" class="checkbox-validacao" rel="RENDA DO CÔNJUGE" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      TIPO DE RENDA
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      TIPO DE RENDA
                    </small>
                    <?php
                  }
                  ?>
                  <span>
                    <?= $pessoa_renda['renda_tipo']; ?>

                    <?php
                    if (isset($conjugeAnexos['renda_comprovada']) && $pessoa_renda['renda_tipo_id'] == 1) {

                      if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                        ?>

                        <a id="modalDefaultClick" href="#modalDefault" rel="RENDA DO CÔNJUGE" relkey="conjuge_renda_tipo_id" data-toggle="modal" class="info">
                          <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                        </a>

                        <?php
                      }
                      ?>


                      <a href="../../../../../<?= $conjugeAnexos['renda_comprovada']; ?>" class="attachment" target="_blank">
                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                    <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                    VALOR DA RENDA
                  </small>
                  <span>
                    <?= fdec($pessoa_renda['valor']); ?>
                    <!--                    <a href="#modalDefault" data-toggle="modal" class="info">
                                          <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                        </a>-->
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
                <small>
  <!--                  <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                  PROVEDOR DO LAR?
                </small>
                <span>
                  <?= $dados_conjuge['provedor_lar'] == 1 ? "SIM" : "NÃO"; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">ESCOLARIDADE</h2>
          <div class="row">
            <div class="col-md-6">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                  GRAU ESCOLAR
                </small>
                <span>
                  <?= $dados_conjuge['hab_grau_escolar']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                  ESTÁ ESTUDANDO?
                </small>
                <span>
                  <?= $dados_conjuge['instituicao_nome'] != "" ? "SIM" : "NÃO"; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['instituicao_nome'] != "" ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                  NATUREZA DA INSTITUIÇÃO
                </small>
                <span>
                  <?= $dados_conjuge['hab_instituicao_natureza']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                  NOME DA INSTITUIÇÃO
                </small>
                <span>
                  <?= $dados_conjuge['instituicao_nome']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                  SÉRIE/PERÍODO
                </small>
                <span>
                  <?= $dados_conjuge['serie_periodo']; ?>°
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['instituicao_nome'] != "" && $dados_conjuge['hab_instituicao_natureza_id'] == 2 ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                  FINANCIADO POR MEIOS PRÓPRIOS?
                </small>
                <span>
                  <?= $dados_conjuge['hab_financia_natureza_id'] == 1 ? "SIM" : "NÃO"; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div <?= $dados_conjuge['instituicao_nome'] != "" && $dados_conjuge['hab_instituicao_natureza_id'] == 2 && $dados_conjuge['hab_financia_natureza_id'] == 2 && $dados_conjuge['hab_programa_social'] != "" ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-4">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                  PROGRAMA SOCIAL
                </small>
                <span>
                  <?= $dados_conjuge['hab_programa_social']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                  PORCENTAGEM
                </small>
                <span>
                  <?= $dados_conjuge['bolsa_percentual']; ?>%
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">SITUAÇÃO CONJUGAL</h2>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                  ESTADO CIVIL
                </small>
                <span>
                  <?= $dados_conjuge['estado_civil']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <h2 class="m-b-30">INFORMAÇÕES COMPLEMENTARES</h2>
          <div class="row">
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                  POSSUI ALGUM TIPO DE DEFICIÊNCIA?
                </small>
                <span>
                  <?= $dados_conjuge['deficiencia'] == 1 ? "SIM" : "NÃO"; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                  POSSUI ALGUM TIPO DE DOENÇA CRÔNICA INCAPACITANTE?
                </small>
                <span>
                  <?= $dados_conjuge['doenca_cronica'] == 1 ? "SIM" : "NÃO"; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                  CID CAPÍTULO
                </small>
                <span>
                  <?= $dados_conjuge['catinic'] . " ATÉ " . $dados_conjuge['catfim'] . " - " . $dados_conjuge['hab_cid10_capitulo']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-12">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                  CID GRUPO
                </small>
                <span>
                  <?= $dados_conjuge['catinicg'] . " ATÉ " . $dados_conjuge['catinicg'] . " - " . $dados_conjuge['hab_cid10_grupo']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-12">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                  CID CATEGORIA
                </small>
                <span>
                  <?= $dados_conjuge['cat'] . " - " . $dados_conjuge['hab_cid10_categoria']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                  CAD. ÚNICO
                </small>
                <span>
                  <?= $dados_conjuge['cadastro_unico']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                  NIS
                </small>
                <span>
                  <?= $dados_conjuge['nis']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                  POSSUÍ ALGUM OUTRO VÍNCULO JUDICIAL?
                </small>
                <span>
                  <?= is_numeric(pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 1")) ? "SIM" : "NÃO"; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
          </div>
          <h2 <?= is_numeric(pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 1")) ? "" : "style='display: none'"; ?> class="m-b-30">VÍNCULO MATRIMONIAL ANTERIOR PENDENTE</h2>
          <div <?= is_numeric(pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 1")) ? "" : "style='display: none'"; ?> class="row">
            <div class="col-md-6">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                  NOME
                </small>
                <span>
                  <?= $dados_conjuge_2['nome']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
  <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                  CPF
                </small>
                <span>
                  <?= $dados_conjuge_2['cpf']; ?>
                  <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="field">
                <small>
                  <small>
                    CONTATO
                  </small>
                  <span>
                    <?= $dados_conjuge_2['numero']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
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
            <div class="col-md-12">
              Não existe cônjuge para esse candidato
            </div>
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

                  <?php
                  if (isset($familiarAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($familiarAnexos['cpf']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($familiarAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($familiarAnexos['cie']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_nome" name="check_NOME" class="checkbox-validacao" rel="NOME DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      NOME
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      NOME
                    </small>
                    <?php
                  }
                  ?>

                  <span class="nome">
                    <?= $dados_familiar['nome'] == '' ? '' : $dados_familiar['nome']; ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="NOME DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_nome" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="field">


                  <?php
                  if (isset($familiarAnexos['cpf']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_cpf" name="check_CPF" class="checkbox-validacao" rel="CPF DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      CPF
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      CPF
                    </small>
                    <?php
                  }
                  ?>


                  <span>

                    <?= $dados_familiar['cpf'] == '' ? '' : $dados_familiar['cpf']; ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="CPF DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_cpf" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['cpf'])) {
                      ?>
                      <a href="../../../../../<?= $familiarAnexos['cpf']; ?>" class="attachment" target="_blank">
                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                    COR/RAÇA
                  </small>
                  <span>
                    <?= $dados_familiar['cor_pele']; ?>
                    <!--                <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) || isset($familiarAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_data_nascimento" name="check_NASCIMENTO" class="checkbox-validacao" rel="DATA DE NASCIMENTO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      NASCIMENTO
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      NASCIMENTO
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= $dados_familiar['data_nascimento'] == "" || $dados_familiar['data_nascimento'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['data_nascimento']); ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE NASCIMENTO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_data_nascimento" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['rg']) || isset($familiarAnexos['cnh'])) {
                      ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= isset($familiarAnexos['rg']) ? $familiarAnexos['rg'] : $familiarAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                      <?php
                    }
                    ?>

                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['certidao_nascimento']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_bsc_sexo_id" name="check_SEXO" class="checkbox-validacao" rel="SEXO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      SEXO
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      SEXO
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= $dados_familiar['bsc_sexo_id'] == 1 ? "MASCULINO" : "FEMININO"; ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="SEXO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_bsc_sexo_id" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['certidao_nascimento'])) {
                      ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $familiarAnexos['certidao_nascimento']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['rg_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_rg_numero" name="check_RG" class="checkbox-validacao" rel="RG DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      RG
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      RG
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= $dados_familiar['rg_numero'] == "" ? "" : $dados_familiar['rg_numero']; ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="RG DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_rg_numero" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['rg'])) {
                      ?>
                      <a href="../../../../../<?= $familiarAnexos['rg']; ?>" class="attachment" target="_blank">
                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_rg_uf_expedicao" name="check_ORGAO_EXPEDIDOR" class="checkbox-validacao" rel="ÓRGÃO EXPEDIDOR DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      ÓRGÃO EXPEDIDOR
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      ÓRGÃO EXPEDIDOR
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= $dados_familiar['rg_orgao_expedicao_id'] == 1 ? "SSP/" : "DETRAN/"; ?><?= $dados_familiar['rg_uf_expedicao']; ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="ÓRGÃO EXPEDIDOR DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_rg_uf_expedicao" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['rg'])) {
                      ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $familiarAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                      <?php
                    }
                    ?>

                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_rg_uf_expedicao" name="check_UF_EXPEDIDOR" class="checkbox-validacao" rel="UF DE EXPEDIÇÃO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      UF EXPEDIDOR
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      UF EXPEDIDOR
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= $dados_familiar['rg_uf_expedicao']; ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="UF DE EXPEDIÇÃO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_rg_uf_expedicao" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['rg'])) {
                      ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $familiarAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_rg_data_expedicao" name="check_DATA_EXPEDICAO" class="checkbox-validacao" rel="DATA DE EXPEDIÇÃO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox"/>
                      <i class="input-close"></i>
                      DATA EXPEDIÇÃO
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      DATA EXPEDIÇÃO
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= $dados_familiar['rg_data_expedicao'] == "" || $dados_familiar['rg_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['rg_data_expedicao']); ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE EXPEDIÇÃO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_rg_data_expedicao" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['rg'])) {
                      ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $familiarAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
            </div>

            <div <?= $dados_familiar['cnh_numero'] != "" ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_cnh_numero" name="check_CNH" class="checkbox-validacao" rel="CNH DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      CNH
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      CNH
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= $dados_familiar['cnh_numero'] == "" ? "" : $dados_familiar['cnh_numero']; ?>
                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="CNH DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_cnh_numero" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['cnh'])) {
                      ?>
                      <a href="../../../../../<?= $familiarAnexos['cnh']; ?>" class="attachment" target="_blank">
                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_cnh_uf_expedicao" name="check_UF_EXPEDIDOR2" class="checkbox-validacao" rel="UF DE EXPEDIÇÃO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      UF EXPEDIDOR
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      UF EXPEDIDOR
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= $dados_familiar['cnh_uf_expedicao']; ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="UF DE EXPEDIÇÃO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_cnh_uf_expedicao" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['cnh'])) {
                      ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $familiarAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_cnh_data_expedicao" name="check_UF_EXPEDICAO2" class="checkbox-validacao" rel="DATA DE EXPEDIÇÃO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      DATA EXPEDIÇÃO
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      DATA EXPEDIÇÃO
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= $dados_familiar['cnh_data_expedicao'] == "" || $dados_familiar['cnh_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cnh_data_expedicao']); ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE EXPEDIÇÃO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_cnh_data_expedicao" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['cnh'])) {
                      ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $familiarAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['cnh']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_cnh_data_validade" name="check_DATA_VALIDADE" class="checkbox-validacao" rel="DATA DE VALIDADE DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      DATA DE VALIDADE
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      DATA DE VALIDADE
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= $dados_familiar['cnh_data_validade'] == "" || $dados_familiar['cnh_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cnh_data_validade']); ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE VALIDADE DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_cnh_data_validade" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['cnh'])) {
                      ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $familiarAnexos['cnh']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
            </div>

            <div <?= is_numeric($dados_familiar['bsc_municipio_id_natural']) ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                    NACIONALIDADE
                  </small>
                  <span>
                    BRASILEIRO
                    <!--                <a href="#modalDefault" data-toggle="modal" class="info">
                                      <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                    </a>-->
                  </span>
                </div>
              </div>

              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_bsc_municipio_id_natural" name="check_NATURALIDADE" class="checkbox-validacao" rel="NATURALIDADE DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      NATURALIDADE
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      NATURALIDADE
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= nome_estado_municipio(estado_do_municipio($dados_familiar['bsc_municipio_id_natural'])); ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="NATURALIDADE DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_bsc_municipio_id_natural" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>

                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['rg'])) {
                      ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $familiarAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['rg']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_bsc_municipio_id_natural" name="check_CIDADE" class="checkbox-validacao" rel="CIDADE DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      CIDADE
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      CIDADE
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    <?= nome_municipio($dados_familiar['bsc_municipio_id_natural']); ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="CIDADE DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_bsc_municipio_id_natural" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['rg'])) {
                      ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                  <a href="../../../../../<?= $familiarAnexos['rg']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </a>-->
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
            </div>

            <div <?= is_numeric($dados_familiar['bsc_municipio_id_natural']) ? "style='display: none'" : ""; ?> class="row">
              <div class="col-md-3">
                <div class="field">

                  <?php
                  if (isset($familiarAnexos['cie']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_cie" name="check_NACIONALIDADE" class="checkbox-validacao" rel="NACIONALIDADE DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      NACIONALIDADE
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      NACIONALIDADE
                    </small>
                    <?php
                  }
                  ?>

                  <span>
                    ESTRANGEIRO

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="NACIONALIDADE DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_cie" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['cie'])) {
                      ?>
                      <a href="../../../../../<?= $familiarAnexos['cie']; ?>" class="attachment" target="_blank">
                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                    CÓD. RNE
                  </small>
                  <span>
                    <?= $dados_familiar['cie_rne'] == "" ? "" : $dados_familiar['cie_rne']; ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="CÓD. RNE DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_cie_rne" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                    CLASSIFICAÇÃO
                  </small>
                  <span>
                    <?= $dados_familiar['bsc_cie_classificacao']; ?>
                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="CLASSIFICAÇÃO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_bsc_cie_classificacao" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                    DATA DE EXPEDIÇÃO
                  </small>
                  <span>
                    <?= $dados_familiar['cie_data_expedicao'] == "" || $dados_familiar['cie_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cie_data_expedicao']); ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="DATA DE EXPEDIÇÃO DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_cie_data_expedicao" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                <input value="option1" type="checkbox">
               <i class="input-close"></i>-->
                    VALIDADE
                  </small>
                  <span>
                    <?= $dados_familiar['cie_data_validade'] == "" || $dados_familiar['cie_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cie_data_validade']); ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="VALIDADE DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_cie_data_validade" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">ENDEREÇO RESIDENCIAL</h2>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                    <i class="input-close"></i>-->
                    MORA NO MESMO ENDEREÇO?
                  </small>
                  <span>
                    <?= $dados_familiar['endereco_candidato'] == 1 ? "SIM" : "NÃO"; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div <?= $dados_familiar['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="col-md-3">
                <div class="field">
                  <?php
                  if (isset($familiarAnexos['endereco']) && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                    ?>
                    <label class="checkbox checkbox-inline m-r-20">
                      <input id="check_familiar_<?= $k; ?>_cep" name="check_CEP" class="checkbox-validacao" rel="CEP DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                      <i class="input-close"></i>
                      CEP
                    </label>
                    <?php
                  } else {
                    ?>
                    <small>
                      CEP
                    </small>
                    <?php
                  }
                  ?>
                  <span>
                    <?= $dados_familiar['cep'] == "" ? "" : $dados_familiar['cep']; ?>

                    <?php
                    if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <a id="modalDefaultClick" href="#modalDefault" rel="CEP DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_cep" data-toggle="modal" class="info">
                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                      </a>

                      <?php
                    }
                    ?>

                    <?php
                    if (isset($familiarAnexos['endereco'])) {
                      ?>
                      <a href="../../../../../<?= $familiarAnexos['endereco']; ?>" class="attachment" target="_blank">
                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </span>
                </div>
              </div>
              <div <?= $dados_familiar['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                    MUNICÍPIO
                  </small>
                  <span>
                    <?= $dados_familiar['bsc_municipio']; ?>
                    <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="row">
              <div class="col-md-6">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                    LOGRADOURO
                  </small>
                  <span>
                    <?= $dados_familiar['logradouro'] == "" ? "" : $dados_familiar['logradouro']; ?>
                    <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                    NÚMERO
                  </small>
                  <span>
                    <?= $dados_familiar['numero'] == "" ? "" : $dados_familiar['numero']; ?>
                    <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['endereco_candidato'] == 1 ? "style='display: none'" : ""; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                    BAIRRO
                  </small>
                  <span>
                    <?= $dados_familiar['bairro'] == "" ? "" : $dados_familiar['bairro']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                    QUADRA
                  </small>
                  <span>
                    <?= $dados_familiar['quadra'] == "" ? "" : $dados_familiar['quadra']; ?>
                    <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                    CASA
                  </small>
                  <span>
                    <?= $dados_familiar['lote'] == "" ? "" : $dados_familiar['lote']; ?>
                    <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                    COMPLEMENTO
                  </small>
                  <span>
                    <?= $dados_familiar['complemento'] == "" ? "" : $dados_familiar['complemento']; ?>
                    <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">INFORMAÇÕES DE CONTATO</h2>
            <div class="row">
              <div class="col-md-6">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                    E-MAIL PESSOAL
                  </small>
                  <span>
                    <?= $dados_familiar['email'] == "" ? "" : $dados_familiar['email']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                    RESIDENCIAL
                  </small>
                  <span>
                    <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $familiar_pessoa_id, "AND hab_contato_tipo_id = 1"); ?>
                    <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                    CELULAR
                  </small>
                  <span>
                    <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $familiar_pessoa_id, "AND hab_contato_tipo_id = 2"); ?>
                    <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
              <i class="input-close"></i>-->
                    COMERCIAL
                  </small>
                  <span>
                    <?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $familiar_pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
                    <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                    <i class="input-close"></i>-->
                    RAMAL
                  </small>
                  <span>
                    <?= pesquisar_tabela("ramal", "hab_pessoa_contato", "hab_pessoa_id", "=", $familiar_pessoa_id, "AND hab_contato_tipo_id = 3"); ?>
                    <!--                  <a href="#" class="info" data-toggle="tooltip" data-placement="bottom" data-original-title="observação">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">INFORMAÇÕES DE TRABALHO</h2>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small>
  <!--                    <input value="option1" type="checkbox">
                    <i class="input-close"></i>-->
                    VOCÊ TRABALHA?
                  </small>
                  <span>
                    <?= $dados_familiar['cargo'] != "" ? "SIM" : "NÃO"; ?>
                    <!--                    <a href="#modalDefault" data-toggle="modal" class="info">
                                          <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                        </a>-->

                                                                                                                                                                                                                                                  <!--                    <a href="../../../../../<?= $familiarAnexos['endereco']; ?>" class="attachment" target="_blank">
                                                                                                                                                                                                                                                                        <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                                                                                                                                                                                                                                                                      </a>-->
                  </span>
                </div>
              </div>
              <div <?= $dados_conjuge['cargo'] != "" ? "" : "style='display: none'"; ?> class="col-md-3">
                <div class="field">
                  <small>
       <!--                  <input value="option1" type="checkbox">
                         <i class="input-close"></i>-->
                    TRABALHA NO MESMO ENDEREÇO?
                  </small>
                  <span>
                    <?= $dados_familiar['trab_mesmo_endereco'] == 1 ? "SIM" : "NÃO"; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['cargo'] != "" ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                   <i class="input-close"></i>-->
                    OCUPAÇÃO/CARGO/FUNÇÃO
                  </small>
                  <span>
                    <?= $dados_familiar['cargo'] == "" ? "" : $dados_familiar['cargo']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div <?= $dados_familiar['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                   <i class="input-close"></i>-->
                    LOCAL DE TRABALHO
                  </small>
                  <span>
                    <?= $dados_familiar['instituicao'] == "" ? "" : $dados_familiar['instituicao']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div <?= $dados_familiar['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?> class="col-md-4">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                   <i class="input-close"></i>-->
                    ENDEREÇO
                  </small>
                  <span>
                    <?= $dados_familiar['endereco'] == "" ? "" : $dados_familiar['endereco']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-2">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                    <i class="input-close"></i>-->
                    DATA INÍCIO
                  </small>
                  <span>
                    <?= $dados_familiar['data_inicio'] == "" || $dados_familiar['data_inicio'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['data_inicio']); ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
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
                    if (isset($familiarAnexos['renda_comprovada']) && $pessoa_renda['renda_tipo_id'] == 1 && $dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                      ?>
                      <label class="checkbox checkbox-inline m-r-20">
                        <input id="check_familiar_<?= $k; ?>_renda_comprovada" name="check_RENDA" class="checkbox-validacao" rel="RENDA DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" value="option1" type="checkbox">
                        <i class="input-close"></i>
                        TIPO DE RENDA
                      </label>
                      <?php
                    } else {
                      ?>
                      <small>
                        TIPO DE RENDA
                      </small>
                      <?php
                    }
                    ?>
                    <span>
                      <?= $pessoa_renda['renda_tipo']; ?>

                      <?php
                      if (isset($familiarAnexos['renda_comprovada']) && $pessoa_renda['renda_tipo_id'] == 1) {
                        ?>

                        <?php
                        if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano'])) {
                          ?>
                          <a id="modalDefaultClick" href="#modalDefault" rel="RENDA DO FAMILIAR <?= ctexto($dados_familiar['nome'], "mai"); ?>" relkey="familiar_<?= $k; ?>_renda_comprovada" data-toggle="modal" class="info">
                            <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                          </a>
                          <?php
                        }
                        ?>

                        <a href="../../../../../<?= $familiarAnexos['renda_comprovada']; ?>" class="attachment" target="_blank">
                          <i class="zmdi zmdi-attachment-alt zmdi-hc-fw"></i>
                        </a>
                        <?php
                      }
                      ?>
                    </span>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="field">
                    <small>
      <!--                    <input value="option1" type="checkbox">
                    <i class="input-close"></i>-->
                      VALOR DA RENDA
                    </small>
                    <span>
                      <?= fdec($pessoa_renda['valor']); ?>
                      <!--                    <a href="#modalDefault" data-toggle="modal" class="info">
                                            <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                          </a>-->
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
                  <small>
    <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                    PROVEDOR DO LAR?
                  </small>
                  <span>
                    <?= $dados_familiar['provedor_lar'] == 1 ? "SIM" : "NÃO"; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">ESCOLARIDADE</h2>
            <div class="row">
              <div class="col-md-6">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                    GRAU ESCOLAR
                  </small>
                  <span>
                    <?= $dados_familiar['hab_grau_escolar']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                    ESTÁ ESTUDANDO?
                  </small>
                  <span>
                    <?= $dados_familiar['instituicao_nome'] != "" ? "SIM" : "NÃO"; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['instituicao_nome'] != "" ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                    NATUREZA DA INSTITUIÇÃO
                  </small>
                  <span>
                    <?= $dados_familiar['hab_instituicao_natureza']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                    NOME DA INSTITUIÇÃO
                  </small>
                  <span>
                    <?= $dados_familiar['instituicao_nome']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                    SÉRIE/PERÍODO
                  </small>
                  <span>
                    <?= $dados_familiar['serie_periodo']; ?>°
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['instituicao_nome'] != "" && $dados_familiar['hab_instituicao_natureza_id'] == 2 ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                    FINANCIADO POR MEIOS PRÓPRIOS?
                  </small>
                  <span>
                    <?= $dados_familiar['hab_financia_natureza_id'] == 1 ? "SIM" : "NÃO"; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <div <?= $dados_familiar['instituicao_nome'] != "" && $dados_familiar['hab_instituicao_natureza_id'] == 2 && $dados_familiar['hab_financia_natureza_id'] == 2 && $dados_familiar['hab_programa_social'] != "" ? "" : "style='display: none'"; ?> class="row">
              <div class="col-md-4">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                 <i class="input-close"></i>-->
                    PROGRAMA SOCIAL
                  </small>
                  <span>
                    <?= $dados_familiar['hab_programa_social']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                    <i class="input-close"></i>-->
                    PORCENTAGEM
                  </small>
                  <span>
                    <?= $dados_familiar['bolsa_percentual']; ?>%
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">SITUAÇÃO CONJUGAL</h2>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                    <i class="input-close"></i>-->
                    ESTADO CIVIL
                  </small>
                  <span>
                    <?= $dados_familiar['estado_civil']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <h2 class="m-b-30">INFORMAÇÕES COMPLEMENTARES</h2>
            <div class="row">
              <div class="col-md-3">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                    POSSUI ALGUM TIPO DE DEFICIÊNCIA?
                  </small>
                  <span>
                    <?= $dados_familiar['deficiencia'] == 1 ? "SIM" : "NÃO"; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                    POSSUI ALGUM TIPO DE DOENÇA CRÔNICA INCAPACITANTE?
                  </small>
                  <span>
                    <?= $dados_familiar['doenca_cronica'] == 1 ? "SIM" : "NÃO"; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                    CID CAPÍTULO
                  </small>
                  <span>
                    <?= $dados_familiar['catinic'] . " ATÉ " . $dados_familiar['catfim'] . " - " . $dados_familiar['hab_cid10_capitulo']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                    CID GRUPO
                  </small>
                  <span>
                    <?= $dados_familiar['catinicg'] . " ATÉ " . $dados_familiar['catinicg'] . " - " . $dados_familiar['hab_cid10_grupo']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                   <i class="input-close"></i>-->
                    CID CATEGORIA
                  </small>
                  <span>
                    <?= $dados_familiar['cat'] . " - " . $dados_familiar['hab_cid10_categoria']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                  <i class="input-close"></i>-->
                    CAD. ÚNICO
                  </small>
                  <span>
                    <?= $dados_familiar['cadastro_unico']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
                  </span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="field">
                  <small>
    <!--                  <input value="option1" type="checkbox">
                <i class="input-close"></i>-->
                    NIS
                  </small>
                  <span>
                    <?= $dados_familiar['nis']; ?>
                    <!--                  <a href="#modalDefault" data-toggle="modal" class="info">
                                        <i class="zmdi zmdi-alert-circle zmdi-hc-fw"></i>
                                      </a>-->
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

    <form id="form_validacao" name="form_validacao" action="#" method="post">

      <section id="section_observacao" class="view note-content">
        <h1>
          <i class="zmdi zmdi-format-list-bulleted zmdi-hc-fw"></i>
          OBSERVAÇÃO
        </h1>
        <div class="content">
          <div id="text_validacao" class="html-editor"><?= $dados_candidato['validacao']; ?></div>
        </div>
      </section>

      <section id="section_historico" class="view history-content">
        <h1>
          <i class="zmdi zmdi-archive zmdi-hc-fw"></i>
          HISTÓRICO
        </h1>
        <div class="content">
          <div class="row">
            <?php
            $result = $db->prepare("SELECT cs.data_update, cs.id AS id, u.nome AS responsavel, cs.data_cadastro, ts.id AS situacao_id, ts.descricao AS situacao
                                 FROM hab_candidato_situacao cs
                                 LEFT JOIN seg_usuario AS u ON u.id = cs.seg_usuario_pai_id
                                 LEFT JOIN hab_tipo_situacao AS ts ON ts.id = cs.hab_tipo_situacao_id
                                 WHERE cs.hab_candidato_id = ?
                                 ORDER BY cs.data_cadastro DESC");
            $result->bindValue(1, $candidato_id);
            $result->execute();
            while ($historico = $result->fetch(PDO::FETCH_ASSOC)) {
              ?>

              <div class="col-md-12">
                <div class="field">
                  <small>
                    <?= obterDataBRTimestamp($historico['data_cadastro']) . " ÀS " . obterHoraCompletaTimestamp($historico['data_cadastro']); ?>
                  </small>
                  <span>
                    <b>Responsável:</b> <?= $historico['responsavel']; ?>
                    <br />
                    <b>Situação:</b> <?= $historico['situacao']; ?>
                    <br/>
                    <?= $historico['situacao_id'] == 1 && maior_id($candidato_id) == $historico['id'] ? alteracoes_realizadas($candidato_id) : ($historico['situacao_id'] == 1 && maior_id($candidato_id) != $historico['id'] ? outras_alteracoes_realizadas($candidato_id, $historico['data_update']) : ''); ?>
                  </span>
                </div>
              </div>

              <?php
            }
            ?>
          </div>
          <?php
          if ($result->rowCount() == 0) {
            ?>
            <div class="row">
              <div class="col-md-4">
                <div class="field">
                  <small>
                    Nenhuma atualização realizada
                  </small>
                </div>
              </div>
            </div>
            <?php
          }
          ?>

        </div>
      </section>

      <div class="row">
        <div class="col-md-12 text-center">

          <?php
          if ($dados_candidato['situacao'] == 2 && !is_numeric($dados_candidato['cadastro_retroativo_ano']) && $vf_usuario_pagina == 0) {
            if (vf_permissao_pagina("validador")) {
              ?>
              <button id="confirmar_validacao" class="btn btn-success btn-lg waves-effect">
                <i class="zmdi zmdi-check"></i>
                CONFIRMAR VALIDAÇÃO
              </button>

              <button id="retornar_validacao" class="btn btn-warning btn-lg waves-effect" style="display: none">
                <i class="zmdi zmdi-arrow-back"></i>
                DEVOLVER PARA CORREÇÃO
              </button>

              <?php
            }
          }
          ?>

        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Default -->
<div class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="motivo_titulo" class="modal-title">Motivo da não Validação</h4>
      </div>
      <div class="modal-body">
        <textarea id="motivo_validacao" class="form-control obs" placeholder="Adicionar Motivo"><?= $dados_candidato['validacao']; ?></textarea>
      </div>
      <div class="modal-footer">
        <input type="hidden" value="<?= $param; ?>" id="candidato_id" name="candidato_id"/>
        <input type="hidden" id="start_option" name="start_option" value=""/>
        <input type="hidden" id="titulo_campo" name="titulo_campo" relkey="" value=""/>
        <button id="salvar_motivo" type="button" class="btn palette-Light-Green bg waves-effect" data-dismiss="modal">Salvar</button>
        <button id="limpar_motivo" type="button" class="btn palette-Light-Blue bg waves-effect" data-dismiss="modal">Limpar</button>
        <button id="cancelar_motivo" type="button" class="btn palette-Red bg waves-effect" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Default 2 -->
<div class="modal fade" id="modalDefault2" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="motivo_titulo" class="modal-title">Alterações Realizadas</h4>
      </div>
      <div class="modal-body">
        <br />
        <div class="row">
          <div class="col-md-6">
            <h5>INFORMAÇÃO ANTERIOR</h5>
            <div id="informacao_anterior"></div>
          </div>
          <div class="col-md-6">
            <h5>INFORMAÇÃO ATUALIZADA</h5>
            <div id="informacao_nova"></div> 
          </div>
        </div> 
      </div>
      <div class="modal-footer">
        <button id="cancelar_motivo" type="button" class="btn palette-Red bg waves-effect" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<?php include('template/rodape.php'); ?>
<script src="<?= PORTAL_URL; ?>hab/js/candidato/menu_visualiza.js"></script>
<script src="<?= PORTAL_URL; ?>hab/js/candidato/visualiza.js"></script>
<script src="<?= PORTAL_URL; ?>assets/plugins/vendors/summernote/dist/summernote-updated.min.js"></script>
<script src="<?= PORTAL_URL; ?>assets/js/functions.js"></script>