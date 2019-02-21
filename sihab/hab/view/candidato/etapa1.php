<?php include('template/topo.php'); ?>

<?php
$internet = @fsockopen("www.google.com", 80, $errno, $errstr, 30);

$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0));
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

$_SESSION['retroativo'] = is_numeric(Url::getURL(4)) ? Url::getURL(4) : $_SESSION['retroativo'];

$result = $db->prepare("SELECT hc.loteamento_id, hc.participar_programa_id, hc.snch_apf_id, hp.trab_mesmo_endereco, hc.area_risco_insalubre, hpe.latitude, hpe.longitude ,hc.data_cadastro_anterior, hc.acompanhamento_socio_assistencial, hc.morador_rua, hpe.coabitacao_involuntaria, hpe.aluguel_valor, hpe.aluguel_social, hpe.alugada, hpe.bsc_logradouro_id, hpe.bsc_endereco_tipo_id, hp.bsc_deficiencia_tipo_id, hp.casamento_data,
                          hp.lei_maria_penha, hp.data_inicio_residencia_municipio, hp.doenca_cronica, hp.mae_nome, hp.mae_data_nascimento,
                          hp.nis, hp.uniao_estavel, hp.bsc_estado_civil_id AS estado_civil_id, ec.nome AS estado_civil, hcp.hab_subprograma_id,
                          hpes.hab_financia_natureza_id, hpes.hab_programa_social_id, hpes.bolsa_percentual, hpes.hab_instituicao_natureza_id, hpes.instituicao_nome,
                          hpes.serie_periodo, hp.endereco_candidato, hp.hab_cid10_capitulo_id, hp.hab_cid10_grupo_id, hp.hab_cid10_categoria_id, hp.cadastro_unico,
                          hp.deficiencia, hp.hab_grau_escolar_id, ho.instituicao, ho.endereco, ho.cargo, ho.data_inicio ,hp.provedor_lar, hp.uniao_estavel,
                          hp.bsc_estado_civil_id, hp.cie_data_validade, hp.cie_data_expedicao, hp.cie_rne, hp.bsc_cie_classificacao_id, hp.bsc_municipio_id_natural,
                          hp.rg_numero, hp.rg_orgao_expedicao_id, hp.rg_uf_expedicao, hp.rg_data_expedicao, hp.cnh_numero, hp.cnh_uf_expedicao, hp.cnh_data_validade,
                          hp.cnh_data_expedicao, hp.id AS pessoa_id, hp.email, hpe.complemento, hpe.lote, hpe.quadra, hpe.bairro, hpe.numero, hpe.logradouro,
                          hpe.cep, hc.id, hp.nome, hp.cpf, hpe.bsc_municipio_id, hp.bsc_nacionalidade_id, hp.cadastro_retroativo_ano, hp.bsc_pele_cor_id, hp.data_nascimento,
                          hp.bsc_sexo_id
                          FROM hab_candidato hc
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          LEFT JOIN hab_pessoa_endereco AS hpe ON hpe.hab_pessoa_id = hp.id
                          LEFT JOIN hab_candidato_programa AS hcp ON hcp.hab_candidato_id = hc.id
                          LEFT JOIN hab_ocupacao AS ho ON ho.hab_pessoa_id = hc.hab_pessoa_id
                          LEFT JOIN hab_pessoa_escolar AS hpes ON hpes.hab_pessoa_id = hc.hab_pessoa_id
                          LEFT JOIN bsc_estado_civil AS ec ON ec.id = hp.bsc_estado_civil_id 
                          WHERE hc.id = ?");
$result->bindValue(1, $param);
$result->execute();

$dados_candidato = $result->fetch(PDO::FETCH_ASSOC);

$candidato_id = $param;

$candidato_apto = pesquisar_tabela("status", "sort_candidato_apto", "candidato_id", "=", $candidato_id, "");

// OUTRO CONJUGE

$result = $db->prepare("SELECT hp.id, hco.numero, hp.nome, hp.cpf
                          FROM hab_conjuge hc
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id_conjuge
                          LEFT JOIN hab_pessoa_contato AS hco ON hco.hab_pessoa_id = hc.hab_pessoa_id_conjuge
                          WHERE hc.hab_candidato_id = ? AND hc.tipo = 2");
$result->bindValue(1, $param);
$result->execute();

$dados_conjuge_2 = $result->fetch(PDO::FETCH_ASSOC);

// NOME FAMILIARES
$stmt = $db->prepare("SELECT hf.id, hf.hab_candidato_id, hp.nome, hp.cpf
         FROM hab_pessoa AS hp, hab_familiar AS hf
         WHERE hf.hab_pessoa_id = hp.id AND hf.status = 1
         ORDER BY UPPER(hp.nome) ASC");
$stmt->execute();
$rsFamiliarNome = $stmt->fetchAll(PDO::FETCH_ASSOC);

// NOME CONJUGE
$stmt = $db->prepare("SELECT hc.id, hc.hab_candidato_id, hp.nome, hp.cpf
         FROM hab_pessoa AS hp, hab_conjuge AS hc
         WHERE hc.hab_pessoa_id_conjuge = hp.id AND hc.status = 1
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

$pessoa_id = pesquisar_tabela("hab_pessoa_id", "hab_candidato", "id", "=", $candidato_id, "");
$bcp = pesquisar_tabela("id", "hab_pessoa_beneficio", "hab_pessoa_id", "=", $pessoa_id, "AND hab_beneficio_social_id = 2");
$bolsa_familia = pesquisar_tabela("id", "hab_pessoa_beneficio", "hab_pessoa_id", "=", $pessoa_id, "AND hab_beneficio_social_id = 3");

if (is_numeric(pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 2"))) {
  $vinculo_sim = "checked='true'";
  $vinculo_nao = "";
  $possui_vinculo = "style='display: block'";
} else {
  $vinculo_sim = "";
  $vinculo_nao = "checked='true'";
  $possui_vinculo = "style='display: none'";
}

// IF ABAIXO PARA VERIFICAR USUÁRIOS NA PÁGINA
if ($param != null && $param != '' && $param != NULL && $param != 0) {
  $vf_usuario_pagina = vf_usuario_pagina($GLOBALS['urlModulo'] . "/" . $GLOBALS['urlPasta'] . "/" . $GLOBALS['urlArquivo'] . "/" . $GLOBALS['urlParametro']);
  if ($vf_usuario_pagina > 0) {
    $nome_usuario = info_usuario($vf_usuario_pagina);
  } else {
    $nome_usuario = 0;
  }
} else {
  $vf_usuario_pagina = 0;
  $nome_usuario = 0;
}
?>
<script>
  var pessoasId = <?= json_encode(array_column($rsPessoasNome, 'id')); ?>;
  var pessoasNome = <?= json_encode(array_column($rsPessoasNome, 'nome')); ?>;
  var pessoasCpf = <?= json_encode(array_column($rsPessoasNome, 'cpf')); ?>;

  var conjugeId = <?= json_encode(array_column($rsConjugeNome, 'id')); ?>;
  var conjugeNome = <?= json_encode(array_column($rsConjugeNome, 'nome')); ?>;
  var conjugeCpf = <?= json_encode(array_column($rsConjugeNome, 'cpf')); ?>;
  var conjugeCandidato = <?= json_encode(array_column($rsConjugeNome, 'hab_candidato_id')); ?>;

  var familiarId = <?= json_encode(array_column($rsFamiliarNome, 'id')); ?>;
  var familiarNome = <?= json_encode(array_column($rsFamiliarNome, 'nome')); ?>;
  var familiarCpf = <?= json_encode(array_column($rsFamiliarNome, 'cpf')); ?>;
  var familiarCandidato = <?= json_encode(array_column($rsFamiliarNome, 'hab_candidato_id')); ?>;

  var bairrosAcre = <?= json_encode(array_column($rsBairrosAcre, 'bairro')); ?>;
</script>
<?php include('template/sidebar.php'); ?>
<section id="content">
  <div class="c-header">
    <div class="card icons-demo">
      <div class="card-header cw-header <?= $_SESSION['retroativo'] == 1 ? 'palette-Black' : 'palette-Teal-600' ?> bg">
        <div class="cwh-year">Candidato</div>
        <div class="cwh-day">Cadastro</div>
        <!--        <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a>-->
      </div>
      <div class="card-body card-padding-sm">
        <div id="cw-body">
          <div class="card-body card-padding">
            <div id="content">
              <div class="form-container">
                <div id="tmm-form-wizard" class="substrate">
                  <?php
                  include ('etapa_wizard.php');
                  ?>
                  <!--/ .row-->
                  <form id="form_candidato" name="form_candidato" action="#" method="post">
                    <input type="hidden" id='vf_usuario_pagina' name='vf_usuario_pagina' value="<?= $vf_usuario_pagina; ?>" />
                    <input type="hidden" id='nome_usuario' name='nome_usuario' value="<?= $nome_usuario; ?>" />
                    <input type="hidden" id="id" name="id" value="<?= $candidato_id; ?>" />
                    <input type="hidden" id="caminho_pagina" name="caminho_pagina" value="etapa2" />
                    <input type="hidden" id="retroativo" name="retroativo" value="<?= $_SESSION['retroativo']; ?>" />
                    <div class="form-wizard">
                      <div class="row space-t-20">
                        <div class="col-md-2">
                          <div <?= $candidato_apto == 2 ? 'style="display: blank"' : ''; ?> class="row">
                            <div class="col-md-6">
                              <label class="fg-label">
                                APTO A SORTEIO ?
                              </label>
                            </div>
                            <div class="col-md-2">
                              <div class="checkbox"><label><input <?= $candidato_apto == 1 ? 'checked="true"' : ''; ?> class="select-box" id="apto_sorteio" name="apto_sorteio" value="1" type="checkbox"><i class="input-helper"></i></label></div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="retroativo_sim_nao" <?= $_SESSION['retroativo'] == 1 ? "" : "style='display: none'" ?> class="row space-t-20">
                        <div class="col-md-2">
                          <div class="form-group fg-float">
                            <div id="div_data_cadastro_anterior" class="fg-line">
                              <label class="fg-label">
                                DATA CADASTRO ANTERIOR
                                <sup>*</sup>
                              </label>
                              <input id="data_cadastro_anterior" name="data_cadastro_anterior" type='text' class="form-control date-picker" data-mask="00/00/0000" value="<?= obterDataBRTimestamp($dados_candidato['data_cadastro_anterior']); ?>">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12">
                          <div class="row">
                            <div class="col-xs-12">
                              <div class="form-header">
                                <div class="form-title">
                                  <i class="zmdi zmdi-accounts-list topo-icons-etapas"></i>
                                  <b>MOTIVO DO CADASTRO</b>
                                </div>
                              </div>
                              <!--/ .form-header-->
                            </div>
                          </div>
                        </div>
                      </div>
                      <!--/ .row-->
                      <div class="row space-t-25">
                        <div id="div_programa" class="col-sm-6 sel-form m-b-25">
                          <label for="">
                            ORIGEM DA DEMANDA
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </label>
                          <select id="programa" name="programa" class="selectpicker" data-live-search="true">
                            <option value="">ESCOLHA A ORIGEM DA DEMANDA</option>
                            <?php
                            $result = $db->prepare("SELECT id, nome FROM hab_origem_demanda WHERE status = 1 ORDER BY nome ASC");
                            $result->execute();
                            while ($programa = $result->fetch(PDO::FETCH_ASSOC)) {
                              if (pesquisar_tabela("hab_programa_id", "hab_especificidade", "id", "=", $dados_candidato['hab_subprograma_id'], "") == $programa['id']) {
                                ?>
                                <option selected='true' value='<?= $programa['id']; ?>'><?= $programa['nome']; ?></option>
                                <?php
                              } else {
                                ?>
                                <option value='<?= $programa['id']; ?>'><?= $programa['nome']; ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                        <div id="div_subprograma" class="col-sm-6 sel-form m-b-25">
                          <label for="">
                            ESPECIFICIDADE
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </label>
                          <select id="subprograma" name="subprograma" class="selectpicker" data-live-search="true">
                            <?php
                            if ($candidato_id == "") {
                              ?>
                              <option value="">ESCOLHA PRIMEIRO A ORIGEM DA DEMANDA</option>
                              <?php
                            } else {
                              $result2 = $db->prepare("SELECT id, nome FROM hab_especificidade WHERE status = 1 ORDER BY nome ASC");
                              $result2->execute();
                              while ($subprograma = $result2->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_candidato['hab_subprograma_id'] == $subprograma['id']) {
                                  ?>
                                  <option selected='true' value='<?= $subprograma['id']; ?>'><?= $subprograma['nome']; ?></option>
                                  <?php
                                }
                              }
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12">
                          <div class="form-header">
                            <div class="form-title">
                              <i class="zmdi zmdi-account topo-icons-etapas"></i>
                              <b>INFORMAÇÕES DO TITULAR</b>
                            </div>
                          </div>
                          <!--/ .form-header-->
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-card form-control-icons"></span>
                            <div id="div_cpf" class="fg-line">
                              <input id="cpf" name="cpf" type="text" class="input-sm form-control fg-input" data-mask="000.000.000-00" value="<?= $dados_candidato['cpf'] == '' ? '' : $dados_candidato['cpf']; ?>">
                              <label class="fg-label">
                                CPF
                                <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-5 item-form">
                          <div class="form-group fg-float">
                            <div id="div_nome" class="fg-line">
                              <input id="nome" name="nome" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['nome'] == '' ? '' : $dados_candidato['nome']; ?>">
                              <label class="fg-label">
                                NOME
                                <sup>*</sup>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2 sel-form">
                          <div class="input-groud fg-float">
                            <label for="cor" class="">
                              COR/RAÇA
                              <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                            </label>
                            <select id="cor" name="cor" placeholder="Responsável" class="selectpicker" data-live-search="true">
                              <p class="f-500 m-b-15 c-black">ESCOLHA A COR/RAÇA</p>
                              <option value="">ESCOLHA A COR/RAÇA</option>
                              <?php
                              $result = $db->prepare("SELECT id, nome FROM bsc_pele_cor WHERE status = 1 ORDER BY nome ASC");
                              $result->execute();
                              while ($cor = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_candidato['bsc_pele_cor_id'] == $cor['id']) {
                                  ?>
                                  <option selected='true' value='<?= $cor['id']; ?>'><?= $cor['nome']; ?></option>
                                  <?php
                                } else {
                                  ?>
                                  <option value='<?= $cor['id']; ?>'><?= $cor['nome']; ?></option>
                                  <?php
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-2 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-calendar form-control-icons"></span>
                            <div id="div_data_nascimento" class="fg-line">
                              <label class="fg-label">
                                DATA DE NASCIMENTO
                                <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                              </label>
                              <input id="data_nascimento" name="data_nascimento" type='text' class="form-control date-picker" value="<?= $dados_candidato['data_nascimento'] == "" || $dados_candidato['data_nascimento'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['data_nascimento']); ?>" data-mask="00/00/0000">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div class="col-md-3 sel-form">
                          <label for="documento1" class="">
                            TIPO DE DOCUMENTO
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </label>
                          <select id="documento1" name="documento1" class="selectpicker" data-live-search="true">
                            <option value="">ESCOLHA O TIPO DE DOCUMENTO</option>
                            <?php
                            if ($dados_candidato['rg_numero'] != "") {
                              ?>
                              <option selected="true" value="1">REGISTRO GERAL - RG</option>
                              <option value="2">CARTEIRA NACIONAL DE HABILITAÇÃO - CNH</option>
                              <?php
                            } else if ($dados_candidato['cnh_numero'] != "") {
                              ?>
                              <option value="1">REGISTRO GERAL - RG</option>
                              <option selected="true" value="2">CARTEIRA NACIONAL DE HABILITAÇÃO - CNH</option>
                              <?php
                            } else {
                              ?>
                              <option value="1">REGISTRO GERAL - RG</option>
                              <option value="2">CARTEIRA NACIONAL DE HABILITAÇÃO - CNH</option>
                              <?php
                            }
                            ?>

                          </select>
                        </div>
                        <div id="documento_1_1" <?= $dados_candidato['rg_numero'] != "" ? "" : "style='display: none'"; ?>>
                          <div class="col-md-3 item-form">
                            <div class="form-group fg-float">
                              <div id="div_numero_registro" class="fg-line">
                                <input id="numero_registro" name="numero_registro" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['rg_numero'] == "" ? "" : $dados_candidato['rg_numero']; ?>">
                                <label class="fg-label">
                                  Nº DE REGISTRO
                                  <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div id="div_orgao_expedidor" class="col-md-2 item-form">
                            <label for="orgao_expedidor">
                              ÓRGÃO EXPEDIDOR
                              <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                            </label>
                            <select id="orgao_expedidor" name="orgao_expedidor" class="selectpicker" data-live-search="true">
                              <option value="">ESCOLHA O ÓRGÃO EXPEDIDOR</option>
                              <?php
                              $result = $db->prepare("SELECT id, nome FROM bsc_orgao_expedidor WHERE status = 1 ORDER BY id ASC");
                              $result->execute();
                              while ($orgao = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_candidato['rg_orgao_expedicao_id'] == $orgao['id']) {
                                  ?>
                                  <option selected="true" value="<?= $orgao['id']; ?>"><?= $orgao['nome']; ?></option>
                                  <?php
                                } else {
                                  ?>
                                  <option value="<?= $orgao['id']; ?>"><?= $orgao['nome']; ?></option>
                                  <?php
                                }
                              }
                              ?>

                            </select>
                          </div>
                          <div id="div_uf_expedicao" class="col-md-2 item-form">
                            <label for="uf_expedicao">
                              UF EXPEDIÇÃO
                              <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                            </label>
                            <select id="uf_expedicao" name="uf_expedicao" class="selectpicker" data-live-search="true">
                              <option value="">ESCOLHA A UF DE EXPEDIÇÃO</option>
                              <?php
                              $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                              $result->execute();
                              while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_candidato['rg_uf_expedicao'] == $estado['id']) {
                                  ?>
                                  <option label='<?= $estado['sigla']; ?>' selected='true' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                                  <?php
                                } else {
                                  ?>
                                  <option label='<?= $estado['sigla']; ?>' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                                  <?php
                                }
                              }
                              ?>
                            </select>
                          </div>
                          <div class="col-md-2 item-form">
                            <div class="has-feedback form-group fg-float">
                              <span class="zmdi zmdi-calendar form-control-icons"></span>
                              <div id="div_data_expedicao" class="dtp-container fg-line">
                                <input id="data_expedicao" name="data_expedicao" type='text' class="form-control date-picker" value="<?= $dados_candidato['rg_data_expedicao'] == "" || $dados_candidato['rg_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['rg_data_expedicao']); ?>" data-mask="00/00/0000">
                                <label class="fg-label">
                                  DATA DE EXPEDIÇÃO
                                  <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div id="documento_1_2" <?= $dados_candidato['cnh_numero'] != "" ? "" : "style='display: none'"; ?>>
                          <div class="col-md-2 item-form">
                            <div class="form-group fg-float">
                              <div id="div_numero_registro_2" class="fg-line">
                                <input id="numero_registro_2" name="numero_registro_2" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['cnh_numero'] == "" ? "" : $dados_candidato['cnh_numero']; ?>">
                                <label class="fg-label">
                                  Nº DE REGISTRO
                                  <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div id="div_uf_expedicao_2" class="col-md-3 sel-form">
                            <label for="documento1" class="">
                              UF EXPEDIÇÃO
                              <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                            </label>
                            <select id="uf_expedicao_2" name="uf_expedicao_2" class="selectpicker" data-live-search="true">
                              <option value="">ESCOLHA A UF DE EXPEDIÇÃO</option>
                              <?php
                              $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                              $result->execute();
                              while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_candidato['cnh_uf_expedicao'] == $estado['id']) {
                                  ?>
                                  <option label='<?= $estado['sigla']; ?>' selected='true' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                                  <?php
                                } else {
                                  ?>
                                  <option label='<?= $estado['sigla']; ?>' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                                  <?php
                                }
                              }
                              ?>
                            </select>
                          </div>
                          <div class="col-md-2 item-form">
                            <div class="has-feedback form-group fg-float">
                              <span class="zmdi zmdi-calendar form-control-icons"></span>
                              <div id="div_data_expedicao_2" class="dtp-container fg-line">
                                <input id="data_expedicao_2" name="data_expedicao_2" type='text' class="form-control date-picker" value="<?= $dados_candidato['cnh_data_expedicao'] == "" || $dados_candidato['cnh_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cnh_data_expedicao']); ?>" data-mask="00/00/0000">
                                <label class="fg-label">
                                  DATA DE EXPEDIÇÃO
                                  <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-2 item-form">
                            <div class="has-feedback form-group fg-float">
                              <span class="zmdi zmdi-calendar form-control-icons"></span>
                              <div id="div_data_validade_2" class="dtp-container fg-line">
                                <input id="data_validade_2" name="data_validade_2" type='text' class="form-control date-picker" value="<?= $dados_candidato['cnh_data_validade'] == "" || $dados_candidato['cnh_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cnh_data_validade']); ?>" data-mask="00/00/0000">
                                <label class="fg-label">
                                  DATA DE VALIDADE
                                  <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">SEXO</p>
                            </div>
                            <div class="col-lg-6">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['bsc_sexo_id'] == 2 ? "" : "checked='true'"; ?> id="masculino" name="sexo" type="radio" value="1">
                                  <i class="input-helper"></i>
                                  MASCULINO
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['bsc_sexo_id'] == 2 ? "checked='true'" : ""; ?> id="feminino" name="sexo" type="radio" value="2">
                                  <i class="input-helper"></i>
                                  FEMININO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-10">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">NACIONALIDADE</p>
                            </div>
                            <div class="col-lg-6">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= is_numeric($dados_candidato['bsc_municipio_id_natural']) || $param == "" ? "checked='true'" : ""; ?> id="nacionalidade_brasileiro" name="nacionalidade" type="radio" value="1">
                                  <i class="input-helper"></i>
                                  BRASILEIRO
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= is_numeric($dados_candidato['bsc_municipio_id_natural']) || $param == "" ? "" : "checked='true'"; ?> id="nacionalidade_estrangeiro" name="nacionalidade" type="radio" value="0">
                                  <i class="input-helper"></i>
                                  ESTRANGEIRO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div <?= is_numeric($dados_candidato['bsc_municipio_id_natural']) || $param == "" ? "style='display: none'" : ""; ?> id="estrangeiro" class="row space-t-40">
                        <div id="div_pais" class="col-md-3 item-form">
                          <label for="pais" class="">
                            PAÍS
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </label>
                          <select id="pais" name="pais" placeholder="PAÍS" class="selectpicker" data-live-search="true">
                            <option value="">ESCOLHA A NACIONALIDADE</option>
                            <?php
                            $result = $db->prepare("SELECT id, nome FROM bsc_nacionalidade WHERE id <> 30 ORDER BY nome ASC");
                            $result->execute();
                            while ($nacionalidade = $result->fetch(PDO::FETCH_ASSOC)) {
                              if ($dados_candidato['bsc_nacionalidade_id'] == $nacionalidade['id']) {
                                ?>
                                <option selected='true' value='<?= $nacionalidade['id']; ?>'><?= ctexto($nacionalidade['nome'], "mai"); ?></option>
                                <?php
                              } else {
                                ?>
                                <option value='<?= $nacionalidade['id']; ?>'><?= ctexto($nacionalidade['nome'], "mai"); ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                        <div class="col-md-2 item-form">
                          <div class="form-group fg-float">
                            <div id="div_cod_rne" class="fg-line">
                              <input id="cod_rne" name="cod_rne" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['cie_rne'] == "" ? "" : $dados_candidato['cie_rne']; ?>">
                              <label class="fg-label">
                                CÓD. RNE (letras e números)
                                <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 sel-form">
                          <label for="pais" class="">
                            CLASSIFICAÇÃO
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </label>
                          <div class="fg-float">
                            <div id="div_classificacao" class="fg-line">
                              <select id="classificacao" name="classificacao" class="selectpicker" data-live-search="true" data-actions-box="true">
                                <option value="">ESCOLHA A CLASSIFICAÇÃO</option>
                                <?php
                                $result = $db->prepare("SELECT id, nome FROM bsc_cie_classificacao WHERE status = 1 ORDER BY nome ASC");
                                $result->execute();
                                while ($classificacao = $result->fetch(PDO::FETCH_ASSOC)) {
                                  if ($dados_candidato['bsc_cie_classificacao_id'] == $classificacao['id']) {
                                    ?>
                                    <option selected='true' value='<?= $classificacao['id']; ?>'><?= $classificacao['nome']; ?></option>
                                    <?php
                                  } else {
                                    ?>
                                    <option value='<?= $classificacao['id']; ?>'><?= $classificacao['nome']; ?></option>
                                    <?php
                                  }
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-calendar form-control-icons"></span>
                            <div id="div_data_expedicao_1" class="dtp-container fg-line">
                              <input id="data_expedicao_1" name="data_expedicao_1" type='text' class="form-control date-picker" value="<?= $dados_candidato['cie_data_expedicao'] == "" || $dados_candidato['cie_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cie_data_expedicao']); ?>" data-mask="00/00/0000">
                              <label class="fg-label">
                                DATA EXPEDIÇÃO
                                <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-calendar form-control-icons"></span>
                            <div id="div_validade" class="dtp-container fg-line">
                              <input id="validade" name="validade" type='text' class="form-control date-picker" value="<?= $dados_candidato['cie_data_validade'] == "" || $dados_candidato['cie_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['cie_data_validade']); ?>" data-mask="00/00/0000">
                              <label class="fg-label">
                                VALIDADE
                                <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div <?= is_numeric($dados_candidato['bsc_municipio_id_natural']) || $param == "" ? "" : "style='display: none'"; ?> class="row space-t-10">
                        <div class="col-xs-12">
                          <p class="c-black f-500">
                            NATURALIDADE
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </p>
                        </div>
                      </div>
                      <div <?= is_numeric($dados_candidato['bsc_municipio_id_natural']) || $param == "" ? "" : "style='display: none'"; ?> id="brasileiro" class="row space-t-25">
                        <div id="div_naturalidade_estado" class="col-md-4 item-form">
                          <label for="naturalidade_estado">
                            ESTADO
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </label>
                          <select id="naturalidade_estado" name="naturalidade_estado" placeholder="estado" class="selectpicker" data-live-search="true">
                            <?php
                            $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                            $result->execute();
                            while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                              if (estado_do_municipio($dados_candidato['bsc_municipio_id_natural']) == $estado['id'] || $estado['id'] == 1 && $candidato_id == "") {
                                ?>
                                <option label='<?= $estado['sigla']; ?>' selected='true' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                                <?php
                              } else {
                                ?>
                                <option label='<?= $estado['sigla']; ?>' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                        <div id="div_naturalidade_municipio" class="col-md-4 item-form">
                          <label for="naturalidade_municipio">
                            CIDADE
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </label>
                          <select id="naturalidade_municipio" name="naturalidade_municipio" class="selectpicker" data-live-search="true">
                            <?php
                            if ($candidato_id == "" || $dados_candidato['bsc_municipio_id_natural'] == "") {
                              ?>
                              <option value="">ESCOLHA UM MUNICÍPIO</option>
                              <?php
                              $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio WHERE estado_id = 1 ORDER BY nome ASC");
                              $result2->execute();
                              while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option label='<?= $municipio['nome']; ?>' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                                <?php
                              }
                            } else {
                              $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio WHERE estado_id = ? ORDER BY nome ASC");
                              $result2->bindValue(1, estado_do_municipio($dados_candidato['bsc_municipio_id_natural']));
                              $result2->execute();
                              while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_candidato['bsc_municipio_id_natural'] == $municipio['id']) {
                                  ?>
                                  <option label='<?= $municipio['nome']; ?>' selected='true' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                                  <?php
                                } else {
                                  ?>
                                  <option label='<?= $municipio['nome']; ?>' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                                  <?php
                                }
                              }
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div class="col-md-6 sel-form">
                          <label for="">
                            ESTADO CIVIL
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </label>
                          <select id="estado_civil" name="estado_civil" class="selectpicker" data-live-search="true">
                            <option value="">ESCOLHA SEU ESTADO CIVIL</option>
                            <?php
                            $result = $db->prepare("SELECT id, nome FROM bsc_estado_civil WHERE id NOT IN (9) AND status = 1 ORDER BY id ASC");
                            $result->execute();
                            while ($estado_civil = $result->fetch(PDO::FETCH_ASSOC)) {
                              if ($dados_candidato['estado_civil_id'] == $estado_civil['id']) {
                                ?>
                                <option selected='true' value="<?= $estado_civil['id'] ?>"><?= $estado_civil['nome']; ?></option>
                                <?php
                              } else {
                                ?>
                                <option value="<?= $estado_civil['id']; ?>"><?= $estado_civil['nome']; ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                        <div id="div_data_do_casamento" <?= $dados_candidato['estado_civil_id'] == 6 || $dados_candidato['estado_civil_id'] == 7 || $dados_candidato['estado_civil_id'] == 8 ? '' : 'style="display: none"'; ?> class="col-md-3 item-form">
                          <div class="form-group fg-float">
                            <div id="div_casamento_data" class="fg-line">
                              <label class="fg-label">DATA DE CASAMENTO</label>
                              <input id="casamento_data" name="casamento_data" type='text' class="form-control date-picker" value="<?= obterDataBRTimestamp($dados_candidato['casamento_data']); ?>" data-mask="00/00/0000">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" id="uniao_estavel" name="uniao_estavel" value="1" <?= $dados_candidato['uniao_estavel'] == 1 ? 'checked="true"' : ''; ?>>
                              <i class="input-helper"></i>
                              UNIÃO ESTÁVEL
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-10">
                        <div class="col-xs-12">
                          <div class="form-header">
                            <div class="form-title">
                              <i class="zmdi zmdi-home topo-icons-etapas"></i>
                              <b>ENDEREÇO RESIDENCIAL</b>
                            </div>
                          </div>
                          <!--/ .form-header-->
                        </div>
                      </div>
                      <div class="row space-t-10">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">MORADOR DE RUA?</p>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['morador_rua'] == 1 ? "checked='true'" : ""; ?> id="morador_rua_sim" name="morador_rua" type="radio" value="1">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['morador_rua'] == 1 ? "" : "checked='true'"; ?> id="morador_rua_nao" name="morador_rua" type="radio" value="0">
                                  <i class="input-helper"></i>
                                  NÃO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-10">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">COABITAÇÃO INVOLUNTÁRIA?</p>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['coabitacao_involuntaria'] == 1 ? "checked='true'" : ""; ?> id="coabitacao_involuntaria_sim" name="coabitacao_involuntaria" type="radio" value="1">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['coabitacao_involuntaria'] == 1 ? "" : "checked='true'"; ?> id="coabitacao_involuntaria_nao" name="coabitacao_involuntaria" type="radio" value="0">
                                  <i class="input-helper"></i>
                                  NÃO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-10">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">RESIDÊNCIA ALUGADA?</p>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['alugada'] == 1 ? "checked='true'" : ""; ?> id="alugada_sim" name="alugada" type="radio" value="1">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['alugada'] == 1 ? "" : "checked='true'"; ?> id="alugada_nao" name="alugada" type="radio" value="0">
                                  <i class="input-helper"></i>
                                  NÃO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div <?= $dados_candidato['alugada'] == 1 ? "" : "style='display: none'"; ?> id="alugado_sim_nao">
                        <div class="row space-t-25">
                          <div class="col-md-3 item-form">
                            <div class="has-feedback form-group fg-float">
                              <span class="zmdi zmdi-money-box form-control-icons"></span>
                              <div id="div_valor_aluguel" class="fg-line">
                                <input id="valor_aluguel" name="valor_aluguel" type="text" class="input-sm form-control fg-input" value="<?= fdec($dados_candidato['aluguel_valor']); ?>">
                                <label class="fg-label">
                                  VALOR DO ALUGUEL
                                  <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4">
                            <div class="row">
                              <div class="col-md-12">
                                <p class="c-black f-500">ALUGUEL SOCIAL?</p>
                              </div>
                              <div class="col-lg-4">
                                <div class="radio m-b-15">
                                  <label>
                                    <input <?= $dados_candidato['aluguel_social'] == 1 ? "checked='true'" : ""; ?> id="aluguel_social_sim" name="aluguel_social" type="radio" value="1">
                                    <i class="input-helper"></i>
                                    SIM
                                  </label>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="radio m-b-15">
                                  <label>
                                    <input <?= $dados_candidato['aluguel_social'] == 1 ? "" : "checked='true'"; ?> id="aluguel_social_nao" name="aluguel_social" type="radio" value="0">
                                    <i class="input-helper"></i>
                                    NÃO
                                  </label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-10">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">ÁREA DE RISCO OU INSALUBRE?</p>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['area_risco_insalubre'] == 1 ? "checked='true'" : ""; ?> id="area_risco_insalubre" name="area_risco_insalubre" type="radio" value="1">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['area_risco_insalubre'] == 1 ? "" : "checked='true'"; ?> id="area_risco_insalubre" name="area_risco_insalubre" type="radio" value="0">
                                  <i class="input-helper"></i>
                                  NÃO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-map form-control-icons"></span>
                            <div id="div_cep" class="fg-line">
<!--                              <input onKeyUp="consultacep()" id="cep" name="cep" type="text" class="input-sm form-control fg-input" maxlength="10" data-mask="00.000-000" value="<?= $dados_candidato['cep'] == "" ? "" : $dados_candidato['cep']; ?>">-->
                              <input id="cep" name="cep" type="text" class="input-sm form-control fg-input" maxlength="10" data-mask="00.000-000" value="<?= $dados_candidato['cep'] == "" ? "" : $dados_candidato['cep']; ?>">
                              <label class="fg-label">
                                CEP
                                <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div id="div_tipo_endereco" class="col-md-3 sel-form">
                          <label for="">
                            TIPO DE ENDEREÇO
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </label>
                          <select id="tipo_endereco" name="tipo_endereco" class="selectpicker" data-live-search="true">
                            <option value="">ESCOLHA O TIPO DE ENDEREÇO</option>
                            <?php
                            $result = $db->prepare("SELECT id, nome FROM bsc_endereco_tipo ORDER BY nome ASC");
                            $result->execute();
                            while ($endereco_tipo = $result->fetch(PDO::FETCH_ASSOC)) {
                              if ($dados_candidato['bsc_endereco_tipo_id'] == $endereco_tipo['id']) {
                                ?>
                                <option selected='true' value='<?= $endereco_tipo['id']; ?>'><?= $endereco_tipo['nome']; ?></option>
                                <?php
                              } else {
                                ?>
                                <option value='<?= $endereco_tipo['id']; ?>'><?= $endereco_tipo['nome']; ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                        <div id="div_municipio" class="col-md-4 sel-form">
                          <label for="">
                            MUNICÍPIO
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </label>
                          <select id="municipio" name="municipio" class="selectpicker" data-live-search="true">
                            <option value="">ESCOLHA O MUNICÍPIO</option>
                            <?php
                            $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio WHERE estado_id = 1 ORDER BY nome ASC");
                            $result2->execute();
                            while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                              if ($dados_candidato['bsc_municipio_id'] == $municipio['id']) {
                                ?>
                                <option label='<?= $municipio['nome']; ?>' selected='true' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                                <?php
                              } else {
                                ?>
                                <option label='<?= $municipio['nome']; ?>' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                        <div class="col-md-2 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-calendar form-control-icons"></span>
                            <div id="div_data_inicio_residencia" class="dtp-container fg-line">
                              <input id="data_inicio_residencia" name="data_inicio_residencia" type='text' class="form-control date-picker" value="<?= obterDataBRTimestamp($dados_candidato['data_inicio_residencia_municipio']); ?>" data-mask="00/00/0000">
                              <label class="fg-label">
                                INÍCIO NA RESIDÊNCIA
                                <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div id="div_tipo_logradouro" class="col-md-5 sel-form">
                          <label for="">
                            TIPO DE LOGRADOURO
                            <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                          </label>
                          <select id="tipo_logradouro" name="tipo_logradouro" class="selectpicker" data-live-search="true">
                            <option value="">ESCOLHA O TIPO DE LOGRADOURO</option>
                            <?php
                            $result = $db->prepare("SELECT id, nome FROM bsc_logradouro_tipo ORDER BY nome ASC");
                            $result->execute();
                            while ($tipo_logradouro = $result->fetch(PDO::FETCH_ASSOC)) {
                              if ($dados_candidato['bsc_logradouro_id'] == $tipo_logradouro['id']) {
                                ?>
                                <option selected='true' value='<?= $tipo_logradouro['id']; ?>'><?= $tipo_logradouro['nome']; ?></option>
                                <?php
                              } else {
                                ?>
                                <option value='<?= $tipo_logradouro['id']; ?>'><?= $tipo_logradouro['nome']; ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                        <div class="col-md-5 item-form">
                          <div class="form-group form-group fg-float">
                            <div id="div_logradouro" class="fg-line">
                              <input id="logradouro" name="logradouro" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['logradouro'] == "" ? "" : $dados_candidato['logradouro']; ?>">
                              <label class="fg-label">
                                LOGRADOURO
                                <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-keyboard form-control-icons"></span>
                            <div id="div_numero" class="fg-line">
                              <input id="numero" name="numero" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['numero'] == "" ? "" : $dados_candidato['numero']; ?>" data-mask="#####">
                              <label class="fg-label">
                                NÚMERO
                                <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div class="col-md-3 item-form">
                          <div class="form-group fg-float">
                            <div id="div_bairro" class="fg-line">
                              <input onKeyUp="bairro_autocomplete()" id="bairro" name="bairro" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['bairro'] == "" ? "" : $dados_candidato['bairro']; ?>">
                              <label class="fg-label">
                                BAIRRO
                                <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="form-group fg-float">
                            <div id="div_quadra" class="fg-line">
                              <input id="quadra" name="quadra" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['quadra'] == "" ? "" : $dados_candidato['quadra']; ?>">
                              <label class="fg-label">QUADRA</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="form-group fg-float">
                            <div id="div_casa" class="fg-line">
                              <input id="casa" name="casa" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['lote'] == "" ? "" : $dados_candidato['lote']; ?>">
                              <label class="fg-label">CASA</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="form-group fg-float">
                            <div id="div_complemento" class="fg-line">
                              <input id="complemento" name="complemento" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['complemento'] == "" ? "" : $dados_candidato['complemento']; ?>">
                              <label class="fg-label">COMPLEMENTO</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-pin-drop form-control-icons"></span>
                            <div id="div_latitude" class="fg-line">
                              <input id="lat" name="lat" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['latitude'] == "" ? "" : $dados_candidato['latitude']; ?>">
                              <label class="fg-label">LATITUDE</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-pin-drop form-control-icons"></span>
                            <div id="div_longitude" class="fg-line">
                              <input id="lng" name="lng" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['longitude'] == "" ? "" : $dados_candidato['longitude']; ?>">
                              <label class="fg-label">LONGITUDE</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <a <?= $internet ? '' : 'style="display: none"' ?> id="carregando_modal" data-toggle="modal" class="btn palette-Orange-700 bg btn-float waves-effect waves-circle waves-float filtro">
                            <i class="zmdi zmdi-search"></i>
                          </a>
                          <button type="button" style="display: none" id="carregando_modal_button" class="btn btn-danger bg btn-float waves-effect waves-circle waves-float ">
                            <i id="mudar_check" class="zmdi zmdi-check zmdi-hc-fw"></i>
                          </button>
                        </div>
                      </div>
                      <div id="div_mapa" class="row space-t-25" style="height: 500px; display: none">
                        <div class="col-md-12 item-form">
                          <div id="map-canvas"></div>
                        </div>
                      </div>
                      <div class="row space-t-10">
                        <div class="col-xs-12">
                          <div class="form-header">
                            <div class="form-title">
                              <i class="zmdi zmdi-assignment-account topo-icons-etapas"></i>
                              <b>INFORMAÇÕES DE CONTATO</b>
                            </div>
                          </div>
                          <!--/ .form-header-->
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div class="col-md-6 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-email form-control-icons"></span>
                            <div id="div_contato_email" class="fg-line">
                              <input type="text" id="contato_email" name="contato_email" class="input-sm form-control fg-input" value="<?= $dados_candidato['email'] == "" ? "" : $dados_candidato['email']; ?>">
                              <label class="fg-label">E-MAIL PESSOAL</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-phone form-control-icons"></span>
                            <div id="div_residencial" class="fg-line">
                              <input type="text" id="residencial" name="residencial" class="input-sm form-control fg-input" maxlength="14" data-mask="(00) 00000-0000" value="<?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 1"); ?>">
                              <label class="fg-label">RESIDENCIAL</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-smartphone-iphone form-control-icons"></span>
                            <div id="div_celular" class="fg-line">
                              <input type="text" id="celular" name="celular" class="input-sm form-control fg-input" maxlength="14" data-mask="(00) 00000-0000" value="<?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 2"); ?>">
                              <label class="fg-label">CELULAR</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-phone form-control-icons"></span>
                            <div id="div_comercial" class="fg-line">
                              <input type="text" id="comercial" name="comercial" class="input-sm form-control fg-input" maxlength="14" data-mask="(00) 00000-0000" value="<?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 3"); ?>">
                              <label class="fg-label">COMERCIAL</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-phone-forwarded form-control-icons"></span>
                            <div id="div_ramal" class="fg-line">
                              <input type="text" id="ramal" name="ramal" class="input-sm form-control fg-input" maxlength="6" data-mask="000000" value="<?= pesquisar_tabela("ramal", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 3"); ?>">
                              <label class="fg-label">RAMAL</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-account-box-phone form-control-icons"></span>
                            <div id="div_contato_telefone" class="fg-line">
                              <input type="text" id="contato_telefone" name="contato_telefone" class="input-sm form-control fg-input" maxlength="14" data-mask="(00) 00000-0000" value="<?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 4"); ?>">
                              <label class="fg-label">CONTATO TELEFONE</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-9 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-account-box form-control-icons"></span>
                            <div id="div_contato_nome" class="fg-line">
                              <input type="text" id="contato_nome" name="contato_nome" class="input-sm form-control fg-input" maxlength="100" value="<?= pesquisar_tabela("recado_nome", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 4"); ?>">
                              <label class="fg-label">CONTATO NOME</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-10">
                        <div class="col-xs-12">
                          <div class="form-header">
                            <div class="form-title">
                              <i class="zmdi zmdi-city topo-icons-etapas"></i>
                              <b>INFORMAÇÕES DE TRABALHO</b>
                            </div>
                          </div>
                          <!--/ .form-header-->
                        </div>
                      </div>
                      <div class="row space-t-10">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">VOCÊ TRABALHA?</p>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['cargo'] != "" ? "checked='true'" : ""; ?> id="trabalha_sim" name="trabalha" type="radio" value="1">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['cargo'] != "" ? "" : "checked='true'"; ?> id="trabalha_nao" name="trabalha" type="radio" value="0">
                                  <i class="input-helper"></i>
                                  NÃO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="trabalha_sim_nao_mesmo_endereco" <?= $dados_candidato['cargo'] != "" ? "" : "style='display: none'"; ?> class="row space-t-10">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">TRABALHA NO MESMO ENDEREÇO EM QUE RESIDE?</p>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['trab_mesmo_endereco'] == 1 ? "checked='true'" : ""; ?> id="trab_mesmo_endereco_sim" name="trab_mesmo_endereco" type="radio" value="1">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_candidato['trab_mesmo_endereco'] == 0 ? "checked='true'" : ""; ?> id="trab_mesmo_endereco_nao" name="trab_mesmo_endereco" type="radio" value="0">
                                  <i class="input-helper"></i>
                                  NÃO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div <?= $dados_candidato['cargo'] != "" ? "" : "style='display: none'"; ?> id="trabalha_sim_nao" class="row space-t-25">
                      <div class="col-md-3 item-form">
                        <div class="form-group fg-float">
                          <div id="div_cargo_funcao" class="fg-line">
                            <input id="cargo_funcao" name="cargo_funcao" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['cargo'] == "" ? "" : $dados_candidato['cargo']; ?>">
                            <label class="fg-label">OCUPAÇÃO/CARGO/FUNÇÃO</label>
                          </div>
                        </div>
                      </div>
                      <div id="trab_mesmo_endereco_sim_nao" <?= $dados_candidato['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?>>
                        <div class="col-md-3 item-form">
                          <div class="form-group fg-float">
                            <div id="div_local_trabalho" class="fg-line">
                              <input id="local_trabalho" name="local_trabalho" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['instituicao'] == "" ? "" : $dados_candidato['instituicao']; ?>">
                              <label class="fg-label">LOCAL DE TRABALHO</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4 item-form">
                          <div class="form-group fg-float">
                            <div id="div_trab_endereco" class="fg-line">
                              <input id="trab_endereco" name="trab_endereco" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['endereco'] == "" ? "" : $dados_candidato['endereco']; ?>">
                              <label class="fg-label">ENDEREÇO</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2 item-form">
                        <div class="has-feedback form-group fg-float">
                          <span class="zmdi zmdi-calendar form-control-icons"></span>
                          <div id="div_data_inicio" class="fg-line">
                            <input id="data_inicio" name="data_inicio" type="text" class="form-control date-picker" value="<?= $dados_candidato['data_inicio'] == "" || $dados_candidato['data_inicio'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_candidato['data_inicio']); ?>" data-mask="00/00/0000">
                            <label class="fg-label">DATA INÍCIO</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row space-t-10">
                      <div class="col-xs-12">
                        <div class="form-header">
                          <div class="form-title">
                            <i class="zmdi zmdi-money topo-icons-etapas"></i>
                            <b>INFORMAÇÕES DE RENDA</b>
                          </div>
                        </div>
                        <!--/ .form-header-->
                      </div>
                    </div>
                    <div id="clonar" class="row space-t-25">
                      <div id="div_tipo_renda" class="col-md-4 sel-form">
                        <label for="">TIPO DE RENDA</label>
                        <select id="tipo_renda" name="tipo_renda[]" class="selectpicker" data-live-search="true">
                          <option value="">ESCOLHA O TIPO DE RENDA</option>
                          <?php
                          $renda_tipo = 0;
                          $result = $db->prepare("SELECT id, nome FROM hab_renda_tipo WHERE status = 1 ORDER BY nome ASC");
                          $result->execute();
                          while ($tipo_renda = $result->fetch(PDO::FETCH_ASSOC)) {
                            if ($renda_tipo == $tipo_renda['id']) {
                              ?>
                              <option selected='true' value='<?= $tipo_renda['id']; ?>'><?= $tipo_renda['nome']; ?></option>
                              <?php
                            } else {
                              ?>
                              <option value='<?= $tipo_renda['id']; ?>'><?= $tipo_renda['nome']; ?></option>
                              <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-3 item-form">
                        <div class="has-feedback form-group fg-float">
                          <span class="zmdi zmdi-money-box form-control-icons"></span>
                          <div id="div_renda_valor" class="fg-line">
                            <input id="renda_valor" name="renda_valor[]" type="text" class="input-sm form-control fg-input" value="">
                            <label class="fg-label">VALOR DA RENDA</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group fg-float">
                          <div class="fg-line">
                            <a id="add_renda" class="btn palette-Light-Green-200 bg btn-success">
                              <i class="zmdi zmdi-plus"></i>
                            </a>
                            &nbsp;
                            <a id="remover_renda" class="btn palette-Red-200 bg btn-danger" style="display: none">
                              <i class="zmdi zmdi-close"></i>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>

                    <?php
                    $result1 = $db->prepare("SELECT valor, hab_renda_tipo_id FROM hab_pessoa_renda WHERE hab_pessoa_id = ?");
                    $result1->bindValue(1, $pessoa_id);
                    $result1->execute();
                    while ($pessoa_renda = $result1->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <div id="clonar" class="row space-t-25">
                        <div id="div_tipo_renda" class="col-md-4 sel-form">
                          <label for="">TIPO DE RENDA</label>
                          <select id="tipo_renda" name="tipo_renda[]" class="selectpicker" data-live-search="true">
                            <option value="">ESCOLHA O TIPO DE RENDA</option>
                            <?php
                            $renda_tipo = 0;
                            $result = $db->prepare("SELECT id, nome FROM hab_renda_tipo WHERE status = 1 ORDER BY nome ASC");
                            $result->execute();
                            while ($tipo_renda = $result->fetch(PDO::FETCH_ASSOC)) {
                              if ($pessoa_renda['hab_renda_tipo_id'] == $tipo_renda['id']) {
                                ?>
                                <option selected='true' value='<?= $tipo_renda['id']; ?>'><?= $tipo_renda['nome']; ?></option>
                                <?php
                              } else {
                                ?>
                                <option value='<?= $tipo_renda['id']; ?>'><?= $tipo_renda['nome']; ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-money-box form-control-icons"></span>
                            <div id="div_renda_valor" class="fg-line">
                              <input id="renda_valor" name="renda_valor[]" type="text" class="input-sm form-control fg-input" value="<?= fdec($pessoa_renda['valor']); ?>">
                              <label class="fg-label">VALOR DA RENDA</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group fg-float">
                            <div class="fg-line">
                              <a id="add_renda" class="btn palette-Light-Green-200 bg btn-success">
                                <i class="zmdi zmdi-plus"></i>
                              </a>
                              &nbsp;
                              <a id="remover_renda" class="btn palette-Red-200 bg btn-danger">
                                <i class="zmdi zmdi-close"></i>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php
                    }
                    ?>

                    <div class="row space-t-10">
                      <div class="col-md-4">
                        <div class="row">
                          <div class="col-md-12">
                            <p class="c-black f-500">PROVEDOR DO LAR?</p>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $dados_candidato['provedor_lar'] == 1 ? "checked='true'" : ""; ?> id="provedor_sim" name="provedor" type="radio" value="1">
                                <i class="input-helper"></i>
                                SIM
                              </label>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $dados_candidato['provedor_lar'] == 1 ? "" : "checked='true'"; ?> id="provedor_nao" name="provedor" type="radio" value="0">
                                <i class="input-helper"></i>
                                NÃO
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row space-t-10">
                      <div class="col-xs-12">
                        <div class="form-header">
                          <div class="form-title">
                            <i class="zmdi zmdi-graduation-cap topo-icons-etapas"></i>
                            <b>ESCOLARIDADE</b>
                          </div>
                        </div>
                        <!--/ .form-header-->
                      </div>
                    </div>
                    <div class="row space-t-25 space-b-25">
                      <div id="div_grau_escolar" class="col-md-6 sel-form">
                        <label for="">
                          GRAU ESCOLAR
                          <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                        </label>
                        <select id="grau_escolar" name="grau_escolar" class="selectpicker" data-live-search="true">
                          <option value="">ESCOLHA O SEU GRAU ESCOLAR</option>
                          <?php
                          $result = $db->prepare("SELECT id, nome FROM hab_grau_escolar WHERE status = 1 ORDER BY nome ASC");
                          $result->execute();
                          while ($grau = $result->fetch(PDO::FETCH_ASSOC)) {
                            if ($dados_candidato['hab_grau_escolar_id'] == $grau['id']) {
                              ?>
                              <option selected='true' value='<?= $grau['id']; ?>'><?= $grau['nome']; ?></option>
                              <?php
                            } else {
                              ?>
                              <option value='<?= $grau['id']; ?>'><?= $grau['nome']; ?></option>
                              <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="row space-t-20">
                      <div class="col-md-4">
                        <div class="row">
                          <div class="col-md-12">
                            <p class="c-black f-500">ESTÁ ESTUDANDO?</p>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $dados_candidato['instituicao_nome'] != "" ? "checked='true'" : ""; ?> id="estudando_sim" name="estudando" type="radio" value="1">
                                <i class="input-helper"></i>
                                SIM
                              </label>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $dados_candidato['instituicao_nome'] != "" ? "" : "checked='true'"; ?> id="estudando_nao" name="estudando" type="radio" value="0">
                                <i class="input-helper"></i>
                                NÃO
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div <?= $dados_candidato['instituicao_nome'] != "" ? "" : "style='display: none'"; ?> id="estiver_estudando">
                      <div class="row space-t-25">
                        <div class="col-md-6 item-form">
                          <div class="form-group fg-float">
                            <div id="div_nome_instituicao" class="fg-line">
                              <input id="nome_instituicao" name="nome_instituicao" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['instituicao_nome']; ?>">
                              <label class="fg-label">NOME DA INSTITUIÇÃO</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="form-group fg-float">
                            <div id="div_serie_periodo" class="fg-line">
                              <input id="serie_periodo" name="serie_periodo" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['serie_periodo']; ?>">
                              <label class="fg-label">SÉRIE/PERÍODO</label>
                            </div>
                          </div>
                        </div>
                        <div id="div_rede_publica_privada" class="col-md-3 sel-form">
                          <label for="rede_publica_privada">TIPO</label>
                          <select id="rede_publica_privada" name="rede_publica_privada" class="selectpicker" data-live-search="true">
                            <option value="">ESCOLHA A NATUREZA DA INSTITUIÇÃO</option>
                            <?php
                            $result = $db->prepare("SELECT id, nome FROM hab_instituicao_natureza WHERE status = 1 ORDER BY nome ASC");
                            $result->execute();
                            while ($natureza = $result->fetch(PDO::FETCH_ASSOC)) {
                              if ($dados_candidato['hab_instituicao_natureza_id'] == $natureza['id']) {
                                ?>
                                <option selected='true' value='<?= $natureza['id']; ?>'><?= $natureza['nome']; ?></option>
                                <?php
                              } else {
                                ?>
                                <option value='<?= $natureza['id']; ?>'><?= $natureza['nome']; ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div <?= $dados_candidato['hab_instituicao_natureza_id'] == 2 ? "" : "style='display: none'"; ?> id="natureza_instituicao">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="row">
                              <div class="col-md-12">
                                <p class="c-black f-500">FINANCIADO POR MEIOS PRÓPRIOS?</p>
                              </div>
                              <div class="col-lg-4">
                                <div class="radio m-b-15">
                                  <label>
                                    <input <?= $dados_candidato['hab_financia_natureza_id'] == 2 ? "" : "checked='true'"; ?> id="financia_sim" name="financia" type="radio" value="1">
                                    <i class="input-helper"></i>
                                    SIM
                                  </label>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="radio m-b-15">
                                  <label>
                                    <input <?= $dados_candidato['hab_financia_natureza_id'] == 2 ? "checked='true'" : ""; ?> id="financia_nao" name="financia" type="radio" value="2">
                                    <i class="input-helper"></i>
                                    NÃO
                                  </label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div <?= $dados_candidato['hab_financia_natureza_id'] == 2 ? "" : "style='display: none'"; ?> id="proprios_meios">
                          <div class="row space-t-20">
                            <div class="col-md-6 sel-form">
                              <div class="form-group fg-float">
                                <p>PROGRAMA SOCIAL</p>
                                <div id="div_programa_social" class="fg-line">
                                  <select id="programa_social" name="programa_social" class="selectpicker" data-live-search="true">
                                    <option value="">ESCOLHA O PROGRAMA SOCIAL</option>
                                    <?php
                                    $result = $db->prepare("SELECT id, nome FROM hab_programa_social WHERE status = 1 ORDER BY nome ASC");
                                    $result->execute();
                                    while ($social = $result->fetch(PDO::FETCH_ASSOC)) {
                                      if ($dados_candidato['hab_programa_social_id'] == $social['id']) {
                                        ?>
                                        <option selected='true' value='<?= $social['id']; ?>'><?= $social['nome']; ?></option>
                                        <?php
                                      } else {
                                        ?>
                                        <option value='<?= $social['id']; ?>'><?= $social['nome']; ?></option>
                                        <?php
                                      }
                                    }
                                    ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-3 item-form">
                              <div class="form-group fg-float">
                                <div id="div_porcentagem" class="fg-line">
                                  <input id="porcentagem" name="porcentagem" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['bolsa_percentual']; ?>" data-mask="###" max="3">
                                  <label class="fg-label">PORCENTAGEM</label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12">
                        <div class="form-header">
                          <div class="form-title">
                            <i class="zmdi zmdi-face topo-icons-etapas"></i>
                            <b>FILIAÇÃO</b>
                          </div>
                        </div>
                        <!--/ .form-header-->
                      </div>
                    </div>
                    <div class="row space-t-25">
                      <div class="col-md-6 item-form">
                        <div class="form-group fg-float">
                          <div id="div_mae_nome" class="fg-line">
                            <label class="fg-label">
                              NOME DA MÃE
                              <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                            </label>
                            <input id="mae_nome" name="mae_nome" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['mae_nome']; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 item-form">
                        <div class="has-feedback form-group fg-float">
                          <span class="zmdi zmdi-calendar form-control-icons"></span>
                          <div id="div_mae_nascimento" class="fg-line">
                            <label class="fg-label">
                              DATA DE NASCIMENTO
                              <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                            </label>
                            <input id="mae_nascimento" name="mae_nascimento" type='text' class="form-control date-picker" value="<?= obterDataBRTimestamp($dados_candidato['mae_data_nascimento']); ?>" data-mask="00/00/0000">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 item-form">
                        <div class="has-feedback form-group fg-float">
                          <span class="zmdi zmdi-phone form-control-icons"></span>
                          <div id="div_mae_contato" class="fg-line">
                            <label class="fg-label">
                              CONTATO
                              <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                            </label>
                            <input type="text" id="mae_contato" name="mae_contato" class="input-sm form-control fg-input" maxlength="14" data-mask="(00) 00000-0000" value="<?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 5"); ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12">
                        <div class="form-header">
                          <div class="form-title">
                            <i class="zmdi zmdi-hospital topo-icons-etapas"></i>
                            <b>INFORMAÇÕES COMPLEMENTARES</b>
                          </div>
                        </div>
                        <!--/ .form-header-->
                      </div>
                    </div>
                    <!--                    <div class="row space-t-10">
                                          <div class="col-md-4">
                                            <div class="row">
                                              <div class="col-md-12">
                                                <p class="c-black f-500">MORADOR DE RUA?</p>
                                              </div>
                                              <div class="col-lg-4">
                                                <div class="radio m-b-15">
                                                  <label>
                                                    <input <?= $dados_candidato['morador'] == 1 ? "checked='true'" : ""; ?> id="morador_sim" name="morador" type="radio" value="1">
                                                    <i class="input-helper"></i>
                                                    SIM
                                                  </label>
                                                </div>
                                              </div>
                                              <div class="col-lg-4">
                                                <div class="radio m-b-15">
                                                  <label>
                                                    <input <?= $dados_candidato['morador'] == 1 ? "" : "checked='true'"; ?> id="morador_nao" name="morador" type="radio" value="0">
                                                    <i class="input-helper"></i>
                                                    NÃO
                                                  </label>
                                                </div>
                                              </div>
                                            </div>    
                                          </div>
                                        </div>-->
                    <div class="row space-t-10">
                      <div class="col-md-4">
                        <div class="row">
                          <div class="col-md-12">
                            <p class="c-black f-500">POSSUI ALGUM TIPO DE DEFICIÊNCIA?</p>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $dados_candidato['deficiencia'] == 1 ? "checked='true'" : ""; ?> id="deficiencia_sim" name="deficiencia" type="radio" value="1">
                                <i class="input-helper"></i>
                                SIM
                              </label>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $dados_candidato['deficiencia'] == 1 ? "" : "checked='true'"; ?> id="deficiencia_nao" name="deficiencia" type="radio" value="0">
                                <i class="input-helper"></i>
                                NÃO
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="tipo_da_deficiencia" <?= $dados_candidato['deficiencia'] == 1 ? "" : "style='display:none'"; ?> class="row space-t-20 space-b-20">
                      <div id="div_tipo_deficiencia" class="col-md-4 sel-form">
                        <label for="">TIPO DE DEFICIÊNCIA</label>
                        <select id="tipo_deficiencia" name="tipo_deficiencia" class="selectpicker" data-live-search="true">
                          <option value="">ESCOLHA O TIPO DE DEFICIÊNCIA</option>
                          <?php
                          $result = $db->prepare("SELECT id, nome
                         FROM bsc_deficiencia_tipo
                         ORDER BY nome ASC");
                          $result->execute();
                          while ($tipo_deficiencia = $result->fetch(PDO::FETCH_ASSOC)) {
                            if ($dados_candidato['bsc_deficiencia_tipo_id'] == $tipo_deficiencia['id']) {
                              ?>
                              <option selected="true" value="<?= $tipo_deficiencia['id']; ?>"><?= $tipo_deficiencia['nome']; ?></option>
                              <?php
                            } else {
                              ?>
                              <option value="<?= $tipo_deficiencia['id']; ?>"><?= $tipo_deficiencia['nome']; ?></option>
                              <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="row space-t-10">
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-md-12">
                            <p class="c-black f-500">POSSUI ALGUM TIPO DE DOENÇA CRÔNICA INCAPACITANTE?</p>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $dados_candidato['doenca_cronica'] == 1 ? "checked='true'" : ""; ?> id="doenca_cronica_sim" name="doenca_cronica" type="radio" value="1">
                                <i class="input-helper"></i>
                                SIM
                              </label>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $dados_candidato['doenca_cronica'] == 1 ? "" : "checked='true'"; ?> id="doenca_cronica_nao" name="doenca_cronica" type="radio" value="0">
                                <i class="input-helper"></i>
                                NÃO
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-12">
                        <p class="c-black f-500">CLASSIFICAÇÃO INTERNACIONAL DE DOENÇA</p>
                      </div>
                    </div>
                    <div class="row space-t-25">
                      <div id="div_capitulo" class="col-md-4 sel-form">
                        <label for="">CAPÍTULO</label>
                        <select id="capitulo" name="capitulo" placeholder="Capítulo" class="selectpicker" data-live-search="true">
                          <option value="">ESCOLHA O CAPÍTULO</option>
                          <?php
                          $result = $db->prepare("SELECT id, catinic, catfim, descricao
                         FROM hab_cid10_capitulo
                         ORDER BY catinic ASC");
                          $result->execute();
                          while ($capitulo = $result->fetch(PDO::FETCH_ASSOC)) {
                            if ($dados_candidato['hab_cid10_capitulo_id'] == $capitulo['id']) {
                              ?>
                              <option selected="true" value="<?= $capitulo['id']; ?>" catinic1="<?= $capitulo['catinic']; ?>" catfim1="<?= $capitulo['catfim']; ?>"><?= ($capitulo['catinic'] . " até " . $capitulo['catfim'] . " - " . $capitulo['descricao']); ?></option>
                              <?php
                            } else {
                              ?>
                              <option value="<?= $capitulo['id']; ?>" catinic1="<?= $capitulo['catinic']; ?>" catfim1="<?= $capitulo['catfim']; ?>"><?= ($capitulo['catinic'] . " até " . $capitulo['catfim'] . " - " . $capitulo['descricao']); ?></option>
                              <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                      <div id="div_grupo" class="col-md-4 sel-form">
                        <label>GRUPO</label>
                        <select id="grupo" name="grupo" placeholder="Grupo" class="selectpicker" data-live-search="true">
                          <?php
                          if ($candidato_id == "" || $dados_candidato['hab_cid10_grupo_id'] == "") {
                            ?>
                            <option value="">ESCOLHA PRIMEIRO O CAPÍTULO</option>
                            <?php
                          } else {
                            $result2 = $db->prepare("SELECT * FROM hab_cid10_grupo ORDER BY catinic ASC");
                            $result2->execute();
                            while ($grupo = $result2->fetch(PDO::FETCH_ASSOC)) {
                              if ($dados_candidato['hab_cid10_grupo_id'] == $grupo['id']) {
                                ?>
                                <option selected="true" value="<?= $grupo['id']; ?>" catinic2="<?= $grupo['catinic']; ?>" catfim2="<?= $grupo['catfim']; ?>"><?= $grupo['catinic'] . " até " . $grupo['catfim'] . " - " . $grupo['descricao']; ?></option>
                                <?php
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                      <div id="div_categoria" class="col-md-4 sel-form">
                        <label for="">CATEGORIA</label>
                        <select id="categoria" name="categoria" placeholder="Categoria" class="selectpicker" data-live-search="true">
                          <?php
                          if ($candidato_id == "" || $dados_candidato['hab_cid10_categoria_id'] == "") {
                            ?>
                            <option value="">ESCOLHA PRIMEIRO O GRUPO</option>
                            <?php
                          } else {
                            $result3 = $db->prepare("SELECT * FROM hab_cid10_categoria ORDER BY cat ASC");
                            $result3->execute();
                            while ($categoria = $result3->fetch(PDO::FETCH_ASSOC)) {
                              if ($dados_candidato['hab_cid10_categoria_id'] == $categoria['id']) {
                                ?>
                                <option value="<?= $categoria['id']; ?>"><?= $categoria['cat'] . " - " . $categoria['descricao']; ?></option>
                                <?php
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="row space-t-50">
                      <div class="col-md-4 item-form">
                        <div class="form-group fg-float">
                          <div id="div_cad_unico" class="fg-line">
                            <input id="cad_unico" name="cad_unico" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['cadastro_unico']; ?>">
                            <label class="fg-label">
                              CAD. ÚNICO
                              <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4 item-form">
                        <div class="form-group fg-float">
                          <div id="div_nis" class="fg-line">
                            <input id="nis" name="nis" type="text" class="input-sm form-control fg-input" value="<?= $dados_candidato['nis']; ?>" data-mask="###########" maxlength="11">
                            <label class="fg-label">
                              NIS
                              <sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row space-t-10">
                      <div class="col-md-4">
                        <div class="row">
                          <div class="col-md-12">
                            <p class="c-black f-500">OCORRÊNCIA DE MARIA DA PENHA?</p>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $dados_candidato['lei_maria_penha'] == 1 ? "checked='true'" : ""; ?> id="lei_maria_penha" name="lei_maria_penha" type="radio" value="1">
                                <i class="input-helper"></i>
                                SIM
                              </label>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $dados_candidato['lei_maria_penha'] == 1 ? "" : "checked='true'"; ?> id="lei_maria_penha" name="lei_maria_penha" type="radio" value="0">
                                <i class="input-helper"></i>
                                NÃO
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row space-t-10">
                      <div class="col-md-4">
                        <div class="row">
                          <div class="col-md-12">
                            <p class="c-black f-500">POSSUI ALGUM BENEFÍCIO SOCIAL?</p>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input id="beneficio_sim" name="beneficio" type="radio" <?= is_numeric($bcp) || is_numeric($bolsa_familia) ? "checked='true'" : ""; ?> value="1">
                                <i class="input-helper"></i>
                                SIM
                              </label>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input id="beneficio_nao" name="beneficio" type="radio" <?= is_numeric($bcp) || is_numeric($bolsa_familia) ? "" : "checked='true'"; ?> value="0">
                                <i class="input-helper"></i>
                                NÃO
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="beneficio_social_sim_nao" <?= is_numeric($bcp) || is_numeric($bolsa_familia) ? "" : "style='display:none'"; ?> class="row space-t-10">
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="checkbox m-b-15">
                              <label>
                                <input id="bolsa_familia" name="bolsa_familia" type="checkbox" <?= is_numeric($bolsa_familia) ? "checked='true'" : "" ?> value="3">
                                <i class="input-helper"></i>
                                Bolsa Família
                              </label>
                            </div>
                          </div>
                          <div class="col-lg-8">
                            <div class="checkbox m-b-15">
                              <label>
                                <input id="bpc" name="bpc" type="checkbox" <?= is_numeric($bcp) ? "checked='true'" : "" ?> value="2">
                                <i class="input-helper"></i>
                                BPC (Benefício de Prestação Continuada)
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row space-t-10">
                      <div class="col-md-4">
                        <div class="row">
                          <div class="col-md-12">
                            <p class="c-black f-500">JÁ FOI BENEFICIDADO EM ALGUM PROGRAMA HABITACIONAL?</p>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input id="pereferencia_empreendimento_sim" name="pereferencia_empreendimento" type="radio" <?= $dados_candidato['snch_apf_id'] > 0 ? "checked='true'" : ""; ?> value="1">
                                <i class="input-helper"></i>
                                SIM
                              </label>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input id="pereferencia_empreendimento_nao" name="pereferencia_empreendimento" type="radio" <?= $dados_candidato['snch_apf_id'] > 0 ? "" : "checked='true'"; ?> value="0">
                                <i class="input-helper"></i>
                                NÃO
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="pereferencia_empreendimento_sim_nao" <?= $dados_candidato['snch_apf_id'] > 0 ? "" : "style='display:none'"; ?> class="row space-t-25">
                      <div id="div_empreendimento" class="col-md-4 sel-form">
                        <label>PROGRAMA</label>
                        <select id="empreendimento" name="empreendimento" class="selectpicker" data-live-search="true">
                          <option value="">ESCOLHA O PROGRAMA</option>
                          <?php
                          $result2 = $db->prepare("SELECT * FROM snch_apf ORDER BY nome ASC");
                          $result2->execute();
                          while ($empreendimento = $result2->fetch(PDO::FETCH_ASSOC)) {
                            if ($dados_candidato['snch_apf_id'] == $empreendimento['id']) {
                              ?>
                              <option selected="true" value="<?= $empreendimento['id']; ?>"><?= $empreendimento['nome']; ?></option>
                              <?php
                            } else {
                              ?>
                              <option value="<?= $empreendimento['id']; ?>"><?= $empreendimento['nome']; ?></option>
                              <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                      <div id="div_loteamento" <?= $dados_candidato['snch_apf_id'] > 1 ? "" : "style='display:none'"; ?> class="col-md-4 sel-form">
                        <label>LOTEAMENTO</label>
                        <select id="loteamento_id" name="loteamento_id" class="selectpicker" data-live-search="true">
                          <option value="">ESCOLHA O LOTEAMENTO</option>
                          <?php
                          $result4 = $db->prepare("SELECT * FROM snch_loteamento ORDER BY nome ASC");
                          $result4->execute();
                          while ($loteamento = $result4->fetch(PDO::FETCH_ASSOC)) {
                            if ($dados_candidato['loteamento_id'] == $loteamento['id']) {
                              ?>
                              <option selected="true" value="<?= $loteamento['id']; ?>"><?= $loteamento['nome']; ?></option>
                              <?php
                            } else {
                              ?>
                              <option value="<?= $loteamento['id']; ?>"><?= $loteamento['nome']; ?></option>
                              <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div id="div_deseja_participar" <?= $dados_candidato['snch_apf_id'] > 0 ? "style='display: none'" : ""; ?> class="row space-t-10">
                      <div class="col-md-4">
                        <div class="row">
                          <div class="col-md-12">
                            <p class="c-black f-500">DESEJA PARTICIPAR DE ALGUM PROGRAMA HABITACIONAL?</p>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input id="participar_sim" name="participar_programa" type="radio" <?= $dados_candidato['participar_programa_id'] > 0 ? "checked='true'" : ""; ?> value="1">
                                <i class="input-helper"></i>
                                SIM
                              </label>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input id="participar_nao" name="participar_programa" type="radio" <?= $dados_candidato['participar_programa_id'] > 0 ? "" : "checked='true'"; ?> value="0">
                                <i class="input-helper"></i>
                                NÃO
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="participar_sim_nao" <?= $dados_candidato['participar_programa_id'] > 0 ? "" : "style='display:none'"; ?> class="row space-t-25">
                      <div id="div_participar" class="col-md-4 sel-form">
                        <label>PROGRAMA</label>
                        <select id="participar_id" name="participar_id" class="selectpicker" data-live-search="true">
                          <option value="">ESCOLHA O PROGRAMA</option>
                          <?php
                          $result3 = $db->prepare("SELECT * FROM snch_apf WHERE status = 1 AND id <> 1 ORDER BY nome ASC");
                          $result3->execute();
                          while ($participar = $result3->fetch(PDO::FETCH_ASSOC)) {
                            if ($participar['id'] == $dados_candidato['participar_programa_id']) {
                              ?>
                              <option selected="true" value="<?= $participar['id']; ?>"><?= $participar['nome']; ?></option>
                              <?php
                            } else {
                              ?>
                              <option value="<?= $participar['id']; ?>"><?= $participar['nome']; ?></option>
                              <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="row space-t-20">
                      <div class="col-md-4">
                        <div class="row">
                          <div class="col-md-12">
                            <p class="c-black f-500">POSSUÍ ALGUM OUTRO VÍNCULO JUDICIAL PENDENTE?</p>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $vinculo_sim; ?> id="vinculo_sim" name="vinculo" type="radio" value="1">
                                <i class="input-helper"></i>
                                SIM
                              </label>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $vinculo_nao; ?> id="vinculo_nao" name="vinculo" type="radio" value="0">
                                <i class="input-helper"></i>
                                NÃO
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br />
                    <div <?= $possui_vinculo; ?> id="possuir_vinculo">
                      <div class="row space-t-30">
                        <div class="col-md-6 item-form">
                          <div class="form-group fg-float">
                            <div id="div_vinculo_nome" class="fg-line">
                              <input id="vinculo_id" name="vinculo_id" type="hidden" class="input-sm form-control fg-input" value="<?= $dados_conjuge_2['id']; ?>">
                              <input id="vinculo_nome" name="vinculo_nome" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge_2['nome']; ?>">
                              <label class="fg-label">NOME</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-card form-control-icons"></span>
                            <div id="div_vinculo_cpf" class="fg-line">
                              <input id="vinculo_cpf" name="vinculo_cpf" type="text" class="input-sm form-control fg-input" data-mask="000.000.000-00" value="<?= $dados_conjuge_2['cpf']; ?>">
                              <label class="fg-label">CPF</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-phone form-control-icons"></span>
                            <div id="div_vinculo_contato" class="fg-line">
                              <input type="text" id="vinculo_contato" name="vinculo_contato" class="input-sm form-control fg-input" maxlength="14" data-mask="(00) 00000-0000" value="<?= $dados_conjuge_2['numero']; ?>">
                              <label class="fg-label">CONTATO</label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--                    <div class="row">
                                          <div class="col-xs-12">
                                            <div class="form-header">
                                              <div class="form-title">
                                                <i class="zmdi zmdi-format-list-bulleted zmdi-hc-fw topo-icons-etapas"></i>
                                                <b>LISTA DE CRITÉRIOS<sup <?= $_SESSION['retroativo'] == 1 ? "style='display: none'" : "" ?> id="obrigatorio">*</sup></b>
                                              </div>
                                            </div>
                                            / .form-header
                                          </div>
                                        </div>
                    <?php
                    $result = $db->prepare("SELECT id, nome FROM hab_criterios WHERE status = 1 ORDER BY nome ASC");
                    $result->execute();
                    ?>
                                        <div class="row space-50">
                    <?php
                    while ($criterio = $result->fetch(PDO::FETCH_ASSOC)) {

                      if (is_numeric($param)) {
                        $resultado = pesquisar_tabela("id", "hab_candidato_criterios", "hab_criterios_id", "=", $criterio['id'], "AND hab_candidato_id = " . $param);
                      } else {
                        $resultado = "";
                      }
                      ?>
                                                                                                                                                                                                              <div class="col-md-4 space-t-10">
                                                                                                                                                                                                              <div class="input-group fg-float">
                                                                                                                                                                                                                <label class="checkbox checkbox-inline m-r-20">
                                                                                                                                                                                                                  <input id="criterio_<?= $criterio['id']; ?>" name="criterio_<?= $criterio['id']; ?>" <?= is_numeric($resultado) ? "checked='true'" : "" ?> type="checkbox" value="<?= $criterio['id']; ?>">
                                                                                                                                                                                                                  <i class="input-helper"></i>
                      <?= $criterio['nome']; ?>
                                                                                                                                                                                                                  </label>
                                                                                                                                                                                                              </div>
                                                                                                                                                                                                            </div>
                      <?php
                    }
                    ?>
                                        </div>
                                        <hr size="1" style="width: 100%" />-->

                    <?php
                    if ($vf_usuario_pagina == 0) {
                      ?>

                      <div class="row space-t-20">
                        <div class="col-md-4">
                          <div class="prev">
                            <?php
                            if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
                              ?>                        
                              <button id="anterior" name="anterior" class="btn btn-primary btn-lg">
                                <i class="zmdi zmdi-arrow-back"></i>
                                Etapa Anterior
                              </button>                      
                              <?php
                            }
                            ?>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="finalizar text-center">
                            <?php
                            if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
                              ?>
                              <button id="finalizar" name="finalizar" class="btn btn-success bg btn-lg">
                                <i class="zmdi zmdi-check"></i>
                                Salvar
                              </button>
                              <?php
                            }
                            ?>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="next">
                            <?php
                            if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
                              ?>
                              <button id="proxima" name="proxima" class="btn btn-primary btn-lg">
                                Próxima Etapa
                                <i class="zmdi zmdi-arrow-forward"></i>
                              </button>
                              <?php
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      <?php
                    }
                    ?>
                  </form>
                  <!--/ .form-->
                </div>
                <!--/ .form-wizard-->
              </div>
              <!--/ .container-->
            </div>
            <!--/ .form-container-->
          </div>
          <!--/ #content-->
        </div>
      </div>
    </div>
  </div>
</section>
<?php include('template/rodape.php'); ?>
<!--[if lt IE 9]>
<script src="<?//= PORTAL_URL; ?>assets/plugins/js/respond.min.js"></script>
<![endif]-->
<!--<script type="text/javascript" src="<?//= PORTAL_URL; ?>assets/plugins/autocomplete/js/jquery-1.4.2.js"></script>
<script type='text/javascript' src="<?//= PORTAL_URL; ?>assets/plugins/autocomplete/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="<?//= PORTAL_URL; ?>assets/plugins/autocomplete/css/jquery.autocomplete.css" />-->
<link rel="stylesheet" type="text/css" href="<?= PLUGINS_FOLDER; ?>pesquisa_coordenadas/css/style.css" />
<?php
if ($internet) {
  echo '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCM9YHjXr2QOnnyLXFukEPalhmDdC8NCkw&sensor=true" type="text/javascript"></script>';
}
?>
<script type="text/javascript" src="<?= PLUGINS_FOLDER; ?>pesquisa_coordenadas/js/map.js"></script>
<script src="<?= PORTAL_URL; ?>hab/js/candidato/etapa_wizard.js"></script>
<script src="<?= PORTAL_URL; ?>hab/js/candidato/etapa1.js"></script>
