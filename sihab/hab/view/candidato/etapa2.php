<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0));
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

$conjuge_id = pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $param, "AND tipo = 0");

if (is_numeric($param)) {

  $result = $db->prepare("SELECT hc.observacao, hp.trab_mesmo_endereco, hp.nis, hpes.hab_financia_natureza_id, hpes.hab_programa_social_id, hpes.bolsa_percentual, hpes.hab_instituicao_natureza_id, hpes.instituicao_nome, 
                          hpes.serie_periodo, hp.endereco_candidato, hp.hab_cid10_capitulo_id, hp.hab_cid10_grupo_id, hp.hab_cid10_categoria_id, hp.cadastro_unico, hp.deficiencia, 
                          hp.hab_grau_escolar_id, ho.instituicao, ho.endereco, ho.cargo, ho.data_inicio ,hp.provedor_lar, hp.bsc_estado_civil_id, hp.cie_data_validade, hp.cie_data_expedicao, 
                          hp.cie_rne, hp.bsc_cie_classificacao_id, hp.bsc_municipio_id_natural, hp.id AS pessoa_id, hp.email, hpe.complemento, hpe.lote, hpe.quadra, hpe.bairro, hpe.numero, 
          hp.rg_numero, hp.rg_orgao_expedicao_id, hp.rg_uf_expedicao, hp.rg_data_expedicao, hp.cnh_numero, 
          hp.cnh_uf_expedicao, hp.cnh_data_validade, hp.cnh_data_expedicao, 
                          hpe.logradouro, hpe.cep, hc.id, hp.nome, hp.cpf, hpe.bsc_municipio_id, hp.bsc_nacionalidade_id, hp.cadastro_retroativo_ano, hp.bsc_pele_cor_id, hp.data_nascimento, 
                          hp.bsc_sexo_id
                          FROM hab_conjuge hc
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id_conjuge
                          LEFT JOIN hab_pessoa_endereco AS hpe ON hpe.hab_pessoa_id = hp.id
                          LEFT JOIN hab_ocupacao AS ho ON ho.hab_pessoa_id = hc.hab_pessoa_id_conjuge
                          LEFT JOIN hab_pessoa_escolar AS hpes ON hpes.hab_pessoa_id = hc.hab_pessoa_id_conjuge
                          WHERE hc.id = ? AND hc.status = 1");
  $result->bindValue(1, $conjuge_id);
  $result->execute();

  $dados_conjuge = $result->fetch(PDO::FETCH_ASSOC);

  $qtd_conjuge = $result->rowCount();

  $candidato_id = $param;
  $pessoa_id = pesquisar_tabela("hab_pessoa_id_conjuge", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 0");

  $result = $db->prepare("SELECT hco.numero, hp.nome, hp.cpf
                          FROM hab_conjuge hc
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id_conjuge
                          LEFT JOIN hab_pessoa_contato AS hco ON hco.hab_pessoa_id = hc.hab_pessoa_id_conjuge
                          WHERE hc.hab_candidato_id = ? AND hc.tipo = 1 AND hc.status = 1");
  $result->bindValue(1, $param);
  $result->execute();

  $dados_conjuge_2 = $result->fetch(PDO::FETCH_ASSOC);
} else {
  echo "<script language='javaScript'>window.location.href='lista'</script>";
}

if ($dados_conjuge['hab_financia_natureza_id'] == 1) {
  $financia_sim = "checked='true'";
  $financia_nao = "";
  $proprios_meios = "style='display: none'";
} else {
  $financia_sim = "";
  $financia_nao = "checked='true'";
  $proprios_meios = "";
}

if ($dados_conjuge['hab_instituicao_natureza_id'] == 2) {
  $natureza_instituicao = "";
} else {
  $natureza_instituicao = "style='display: none'";
}

if ($dados_conjuge['instituicao_nome'] != "") {
  $esta_estudando = "";
  $estudando_sim = "checked='true'";
  $estudando_nao = "";
} else {
  $esta_estudando = "style='display: none'";
  $estudando_sim = "";
  $estudando_nao = "checked='true'";
}

if ($dados_conjuge['deficiencia'] == 1) {
  $deficiencia_sim = "checked='true'";
  $deficiencia_nao = "";
} else {
  $deficiencia_sim = "";
  $deficiencia_nao = "checked='true'";
}

if (is_numeric(pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, "AND tipo = 1"))) {
  $vinculo_sim = "checked='true'";
  $vinculo_nao = "";
  $possui_vinculo = "style='display: block'";
} else {
  $vinculo_sim = "";
  $vinculo_nao = "checked='true'";
  $possui_vinculo = "style='display: none'";
}

if ($dados_conjuge['provedor_lar'] == 1) {
  $provedor_lar_marcado_sim = "checked='true'";
  $provedor_lar_marcado_nao = "";
} else {
  $provedor_lar_marcado_sim = "";
  $provedor_lar_marcado_nao = "checked='true'";
}

if ($dados_conjuge['instituicao'] != "") {
  $se_trabalha = "";
  $trabalha_sim = "checked='true'";
  $trabalha_nao = "";
} else {
  $trabalha_sim = "";
  $trabalha_nao = "checked='true'";
  $se_trabalha = "style='display: none'";
}

if ($dados_conjuge['endereco_candidato'] == 1) {
  $mora_sim = "checked='true'";
  $mora_nao = "";
  $mesmo_endereco = "style='display: none'";
} else {
  $mora_sim = "";
  $mesmo_endereco = "";
  $mora_nao = "checked='true'";
}

if ($dados_conjuge['bsc_sexo_id'] == 2) {
  $sexo_masculino = "";
  $sexo_feminino = "checked='true'";
} else {
  $sexo_masculino = "checked='true'";
  $sexo_feminino = "";
}

if (is_numeric($dados_conjuge['bsc_municipio_id_natural'])) {
  $brasileiro = "";
  $estrangeiro = "style='display: none'";
  $nacionalidade_brasileiro = "checked='true'";
  $nacionalidade_estrangeiro = "";
} else {
  $brasileiro = "style='display: none'";
  $estrangeiro = "";
  $nacionalidade_brasileiro = "";
  $nacionalidade_estrangeiro = "checked='true'";
}

if ($dados_conjuge['rg_numero'] != "") {
  $documento_1_1 = "";
  $documento_1_2 = "style='display: none'";
} else if ($dados_conjuge['cnh_numero'] != "") {
  $documento_1_1 = "style='display: none'";
  $documento_1_2 = "";
} else {
  $documento_1_1 = "style='display: none'";
  $documento_1_2 = "style='display: none'";
}

// VERIFICA SE O TITULAR POSSUÍ FAMILIAR CADASTRADO
if ($qtd_conjuge > 0) {
  $conjuge_sim = "checked='true'";
  $conjuge_nao = "";
  $div_conjuge = "";
} else {
  $conjuge_sim = "";
  $conjuge_nao = "checked='true'";
  $div_conjuge = "style='display: none'";
}

//IF ABAIXO PARA VERIFICAR USUÁRIOS NA PÁGINA
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
<section id="content">
  <div class="c-header">
    <div class="card icons-demo">
      <div class="card-header cw-header <?= vf_retroativo($param) ? 'palette-Black' : 'palette-Teal-600' ?> bg">
        <div class="cwh-year">Cônjuge</div>
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
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="form-header">
                        <div class="form-title">
                          <i class="zmdi zmdi-account topo-icons-etapas"></i>
                          <b>INFORMAÇÕES DO CÔNJUGE</b>
                        </div>
                      </div>
                      <!--/ .form-header-->
                    </div>
                  </div>
                  <!--/ .row-->
                  <form id="form_candidato_etapa2" name="form_candidato_etapa2" action="#" method="post">
                    <input type="hidden" id="conjuge_id" name="conjuge_id" value="<?= $conjuge_id; ?>" />
                    <input type="hidden" id="id" name="id" value="<?= $candidato_id; ?>" />
                    <input type="hidden" id="caminho_pagina" name="caminho_pagina" value="etapa3" />
                    <input type="hidden" id="retroativo" name="retroativo" value="<?= vf_retroativo($param); ?>" />
                    <input type="hidden" id='vf_usuario_pagina' name='vf_usuario_pagina' value="<?= $vf_usuario_pagina; ?>"/>

                    <div class="row">
                      <div class="col-md-4">
                        <div class="row">
                          <div class="col-md-12">
                            <p class="c-black f-500">POSSUI CÔNJUGE?</p>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $conjuge_sim; ?> id="conjuge_sim" name="conjuge" type="radio" value="1">
                                <i class="input-helper"></i>
                                SIM
                              </label>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="radio m-b-15">
                              <label>
                                <input <?= $conjuge_nao; ?> id="conjuge_nao" name="conjuge" type="radio" value="0">
                                <i class="input-helper"></i>
                                NÃO
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div id="div_possui" <?= $div_conjuge == "style='display: none'" && is_numeric(pesquisar_tabela("id", "hab_conjuge", "id", "=", $conjuge_id, "")) ? "" : "style='display: none'" ?> class="row space-t-20">
                      <div class="col-md-12">
                        <label class="fg-label">OBSERVAÇÃO<sup>*</sup></label>
                        <div class="form-group fg-float">
                          <div id="div_observacao" class="fg-line">
                            <textarea class="form-control" id="observacao" name="observacao" rows="2" placeholder="Observação"><?= pesquisar_tabela("observacao", "hab_conjuge", "id", "=", $conjuge_id, ""); ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div <?= $div_conjuge; ?> id="div_conjuge">
                      <div class="form-wizard space-t-25">
                        <div class="row">
                          <div class="col-md-3 item-form">
                            <div class="has-feedback form-group fg-float">
                              <span class="zmdi zmdi-card form-control-icons"></span>
                              <div id="div_cpf" class="fg-line">
                                <input id="cpf" name="cpf" type="text" class="input-sm form-control fg-input" data-mask="000.000.000-00" value="<?= $dados_conjuge['cpf'] == '' ? '' : $dados_conjuge['cpf']; ?>">
                                <label class="fg-label">CPF<sup>*</sup></label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-5 item-form">
                            <div class="form-group fg-float">
                              <div id="div_nome" class="fg-line">
                                <input onKeyUp="busca_autocomplete()" id="nome" name="nome" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['nome'] == '' ? '' : $dados_conjuge['nome']; ?>">
                                <label class="fg-label">NOME<sup>*</sup></label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-2 sel-form">
                            <div class="input-groud fg-float">
                              <label for="cor" class="">COR/RAÇA<sup>*</sup></label>
                              <select id="cor" name="cor" class="selectpicker" data-live-search="true">
                                <option value="">ESCOLHA A COR/RAÇA</option>
                                <?php
                                $result = $db->prepare("SELECT id, nome FROM bsc_pele_cor WHERE status = 1 ORDER BY nome ASC");
                                $result->execute();
                                while ($cor = $result->fetch(PDO::FETCH_ASSOC)) {
                                  if ($dados_conjuge['bsc_pele_cor_id'] == $cor['id']) {
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
                                <input id="data_nascimento" name="data_nascimento" type='text' class="form-control date-picker" value="<?= $dados_conjuge['data_nascimento'] == "" || $dados_conjuge['data_nascimento'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['data_nascimento']); ?>" data-mask="00/00/0000">
                                <label class="fg-label">DATA DE NASCIMENTO<sup>*</sup></label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row space-t-25">
                          <div class="col-md-3 sel-form">
                            <label for="documento1" class="">TIPO DE DOCUMENTO<sup>*</sup></label>
                            <select id="documento1" name="documento1" class="selectpicker" data-live-search="true">
                              <option value="">ESCOLHA O TIPO DE DOCUMENTO</option>
                              <?php
                              if ($dados_conjuge['rg_numero'] != "") {
                                ?>
                                <option selected="true" value="1">REGISTRO GERAL - RG</option>
                                <option value="2">CARTEIRA NACIONAL DE HABILITAÇÃO - CNH</option>
                                <?php
                              } else if ($dados_conjuge['cnh_numero'] != "") {
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
                          <div id="documento_1_1" <?= $documento_1_1; ?>>
                            <div class="col-md-3 item-form">
                              <div class="form-group fg-float">
                                <div id="div_numero_registro" class="fg-line">
                                  <input id="numero_registro" name="numero_registro" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['rg_numero'] == "" ? "" : $dados_conjuge['rg_numero']; ?>">
                                  <label class="fg-label">Nº DE REGISTRO<sup>*</sup></label>
                                </div>
                              </div>
                            </div>
                            <div id="div_orgao_expedidor" class="col-md-2 item-form">
                              <label for="orgao_expedidor">ÓRGÃO EXPEDIDOR<sup>*</sup></label>
                              <select id="orgao_expedidor" name="orgao_expedidor" class="selectpicker" data-live-search="true">
                                <option value="">ESCOLHA O ÓRGÃO EXPEDIDOR</option>
                                <?php
                                $result = $db->prepare("SELECT id, nome FROM bsc_orgao_expedidor WHERE status = 1 ORDER BY id ASC");
                                $result->execute();
                                while ($orgao = $result->fetch(PDO::FETCH_ASSOC)) {
                                  if ($dados_conjuge['rg_orgao_expedicao_id'] == $orgao['id']) {
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
                              <label for="uf_expedicao">UF EXPEDIÇÃO<sup>*</sup></label>
                              <select id="uf_expedicao" name="uf_expedicao" class="selectpicker" data-live-search="true">
                                <option value="">ESCOLHA A UF DE EXPEDIÇÃO</option>
                                <?php
                                $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                                $result->execute();
                                while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                                  if ($dados_conjuge['rg_uf_expedicao'] == $estado['id']) {
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
                                <div id="div_data_expedicao" class="fg-line">
                                  <input id="data_expedicao" name="data_expedicao" type='text' class="form-control date-picker" value="<?= $dados_conjuge['rg_data_expedicao'] == "" || $dados_conjuge['rg_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['rg_data_expedicao']); ?>" data-mask="00/00/0000">
                                  <label class="fg-label">DATA DE EXPEDIÇÃO<sup>*</sup></label>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div id="documento_1_2" <?= $documento_1_2; ?>>
                            <div class="col-md-2 item-form">
                              <div class="form-group fg-float">
                                <div id="div_numero_registro_2" class="fg-line">
                                  <input id="numero_registro_2" name="numero_registro_2" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['cnh_numero'] == "" ? "" : $dados_conjuge['cnh_numero']; ?>">
                                  <label class="fg-label">Nº DE REGISTRO<sup>*</sup></label>
                                </div>
                              </div>
                            </div>
                            <div id="div_uf_expedicao_2" class="col-md-3 sel-form">
                              <label for="documento1" class="">UF EXPEDIÇÃO<sup>*</sup></label>
                              <select id="uf_expedicao_2" name="uf_expedicao_2" class="selectpicker" data-live-search="true">
                                <option value="">ESCOLHA A UF DE EXPEDIÇÃO</option>
                                <?php
                                $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                                $result->execute();
                                while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                                  if ($dados_conjuge['cnh_uf_expedicao'] == $estado['id']) {
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
                                <div id="div_data_expedicao_2" class="fg-line">
                                  <input id="data_expedicao_2" name="data_expedicao_2" type='text' class="form-control date-picker" value="<?= $dados_conjuge['cnh_data_expedicao'] == "" || $dados_conjuge['cnh_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cnh_data_expedicao']); ?>" data-mask="00/00/0000">
                                  <label class="fg-label">DATA DE EXPEDIÇÃO<sup>*</sup></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-2 item-form">
                              <div class="has-feedback form-group fg-float">
                                <span class="zmdi zmdi-calendar form-control-icons"></span>
                                <div id="div_data_expedicao_2" class="fg-line">
                                  <input id="data_validade_2" name="data_validade_2" type='text' class="form-control date-picker" value="<?= $dados_conjuge['cnh_data_validade'] == "" || $dados_conjuge['cnh_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cnh_data_validade']); ?>" data-mask="00/00/0000">
                                  <label class="fg-label">DATA DE VALIDADE<sup>*</sup></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row space-t-10">
                          <div class="row">
                            <div class="col-md-4">
                              <div class="col-md-12">
                                <p class="c-black f-500">SEXO</p>
                              </div>
                              <div class="col-lg-4">
                                <div class="radio m-b-15">
                                  <label>
                                    <input <?= $sexo_masculino; ?> id="masculino" name="sexo" type="radio" value="1" name="sample">
                                    <i class="input-helper"></i>
                                    MASCULINO
                                  </label>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="radio m-b-15">
                                  <label>
                                    <input <?= $sexo_feminino; ?> id="feminino" name="sexo" type="radio" value="2" name="sample">
                                    <i class="input-helper"></i>
                                    FEMININO
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
                                <p class="c-black f-500">NACIONALIDADE</p>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-4">
                                <div class="radio m-b-15">
                                  <label>
                                    <input <?= $nacionalidade_brasileiro; ?> checked="true" id="nacionalidade_brasileiro" name="nacionalidade" type="radio" value="1">
                                    <i class="input-helper"></i>
                                    BRASILEIRO
                                  </label>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="radio m-b-15">
                                  <label>
                                    <input <?= $nacionalidade_estrangeiro; ?> id="nacionalidade_estrangeiro" name="nacionalidade" type="radio" value="0">
                                    <i class="input-helper"></i>
                                    ESTRANGEIRO
                                  </label>
                                </div>
                              </div>
                            </div>  
                          </div>
                        </div>
                        <div <?= $estrangeiro; ?> id="estrangeiro" class="row space-t-25">
                          <div id="div_pais" class="col-md-3 item-form">
                            <label for="pais" class="">PAÍS<sup>*</sup></label>
                            <select id="pais" name="pais" placeholder="PAÍS" class="selectpicker" data-live-search="true">
                              <option value="">ESCOLHA A NACIONALIDADE</option>
                              <?php
                              $result = $db->prepare("SELECT id, nome FROM bsc_nacionalidade WHERE id <> 30 ORDER BY nome ASC");
                              $result->execute();
                              while ($nacionalidade = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_conjuge['bsc_nacionalidade_id'] == $nacionalidade['id']) {
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
                                <input id="cod_rne" name="cod_rne" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['cie_rne'] == "" ? "" : $dados_conjuge['cie_rne']; ?>">
                                <label class="fg-label">CÓD. RNE (letras e números)<sup>*</sup></label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3 sel-form">
                            <label for="pais" class="">CLASSIFICAÇÃO<sup>*</sup></label>
                            <div class="fg-float">
                              <div id="div_classificacao" class="fg-line">
                                <select id="classificacao" name="classificacao" class="selectpicker" data-live-search="true" data-actions-box="true">
                                  <option value="">ESCOLHA A CLASSIFICAÇÃO</option>
                                  <?php
                                  $result = $db->prepare("SELECT id, nome FROM bsc_cie_classificacao WHERE status = 1 ORDER BY nome ASC");
                                  $result->execute();
                                  while ($classificacao = $result->fetch(PDO::FETCH_ASSOC)) {
                                    if ($dados_conjuge['bsc_cie_classificacao_id'] == $classificacao['id']) {
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
                                <input id="data_expedicao_1" name="data_expedicao_1" type='text' class="form-control date-picker" value="<?= $dados_conjuge['cie_data_expedicao'] == "" || $dados_conjuge['cie_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cie_data_expedicao']); ?>" data-mask="00/00/0000">
                                <label class="fg-label">DATA EXPEDIÇÃO<sup>*</sup></label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-2 item-form">
                            <div class="has-feedback form-group fg-float">
                              <span class="zmdi zmdi-calendar form-control-icons"></span>
                              <div id="div_validade" class="dtp-container fg-line">
                                <input id="validade" name="validade" type='text' class="form-control date-picker" value="<?= $dados_conjuge['cie_data_validade'] == "" || $dados_conjuge['cie_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['cie_data_validade']); ?>" data-mask="00/00/0000">
                                <label class="fg-label">VALIDADE<sup>*</sup></label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div <?= $brasileiro; ?> id="brasileiro" class="row space-t-10 space-b-20">
                          <div class="col-xs-12">
                            <p class="c-black f-500">NATURALIDADE</p>
                          </div>
                          <br style="clear: both; margin-bottom: 30px" />
                          <div id="div_naturalidade_estado" class="col-md-4 item-form">
                            <label for="naturalidade_estado">ESTADO<sup>*</sup></label>
                            <select id="naturalidade_estado" name="naturalidade_estado" placeholder="estado" class="selectpicker" data-live-search="true">
                              <option value="">ESCOLHA O ESTADO</option>
                              <?php
                              $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                              $result->execute();
                              while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                                if (estado_do_municipio($dados_conjuge['bsc_municipio_id_natural']) == $estado['id'] || $estado['id'] == 1 && $conjuge_id == "") {
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
                            <label for="naturalidade_municipio">CIDADE<sup>*</sup></label>
                            <select id="naturalidade_municipio" name="naturalidade_municipio" class="selectpicker" data-live-search="true">
                              <?php
                              if ($conjuge_id == "" || $dados_conjuge['bsc_municipio_id_natural'] == "") {
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
                                $result2->bindValue(1, estado_do_municipio($dados_conjuge['bsc_municipio_id_natural']));
                                $result2->execute();
                                while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                                  if ($dados_conjuge['bsc_municipio_id_natural'] == $municipio['id']) {
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

                        <div class="row space-t-10">
                          <div id="div_estado_civil" class="col-md-4 item-form">
                            <label for="">ESTADO CIVIL<sup>*</sup></label>
                            <select id="estado_civil" name="estado_civil" class="selectpicker" data-live-search="true">
                              <option value="">ESCOLHA SEU ESTADO CIVIL</option>
                              <?php
                              $result = $db->prepare("SELECT id, nome FROM bsc_estado_civil WHERE status = 1 ORDER BY nome ASC");
                              $result->execute();
                              while ($civil = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_conjuge['bsc_estado_civil_id'] == $civil['id']) {
                                  ?>
                                  <option selected='true' value='<?= $civil['id']; ?>'><?= $civil['nome']; ?></option>
                                  <?php
                                } else {
                                  ?>
                                  <option value='<?= $civil['id']; ?>'><?= $civil['nome']; ?></option>
                                  <?php
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
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
                      <div class="row space-t-20">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="col-md-12">
                              <p class="c-black f-500">MORA NO MESMO ENDEREÇO?</p>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $mora_sim; ?> id="mora_sim" name="mora" type="radio" value="1" name="sample">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $mora_nao; ?> id="mora_nao" name="mora" type="radio" value="0" name="sample">
                                  <i class="input-helper"></i>
                                  NÃO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div <?= $mesmo_endereco; ?> id="mesmo_endereco">
                        <div class="row space-t-25">
                          <div class="col-md-4 item-form">
                            <div class="has-feedback form-group fg-float">
                              <span class="zmdi zmdi-map form-control-icons"></span>
                              <div id="div_cep" class="fg-line">
<!--                                <input onKeyUp="consultacep()" id="cep" name="cep" type="text" class="input-sm form-control fg-input" maxlength="10" data-mask="00.000-000" value="<?= $dados_conjuge['cep'] == "" ? "" : $dados_conjuge['cep']; ?>">-->
                                <input id="cep" name="cep" type="text" class="input-sm form-control fg-input" maxlength="10" data-mask="00.000-000" value="<?= $dados_conjuge['cep'] == "" ? "" : $dados_conjuge['cep']; ?>">
                                <label class="fg-label">CEP<sup>*</sup></label>
                              </div>
                            </div>
                          </div>
                          <div id="div_estado" class="col-md-4 sel-form">
                            <label for="estado">ESTADO<sup>*</sup></label>
                            <select id="estado" name="estado" placeholder="ESTADO" class="selectpicker" data-live-search="true">
                              <option value="">ESCOLHA O ESTADO</option>
                              <?php
                              $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                              $result->execute();
                              while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                                if (estado_do_municipio($dados_conjuge['bsc_municipio_id']) == $estado['id']) {
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
                          <div id="div_municipio" class="col-md-4 sel-form">
                            <label for="municipio">CIDADE<sup>*</sup></label>
                            <select id="municipio" name="municipio" class="selectpicker" data-live-search="true">
                              <?php
                              if ($conjuge_id == "") {
                                ?>
                                <option value="">ESCOLHA PRIMEIRO O ESTADO</option>
                                <?php
                              } else {
                                $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio ORDER BY nome ASC");
                                $result2->execute();
                                while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                                  if ($dados_conjuge['bsc_municipio_id'] == $municipio['id']) {
                                    ?>
                                    <option label='<?= $municipio['nome']; ?>' selected='true' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                                    <?php
                                  }
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="row space-t-25">
                          <div class="col-md-10 item-form">
                            <div class="form-group fg-float">
                              <div id="div_logradouro" class="fg-line">
                                <input id="logradouro" name="logradouro" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['logradouro'] == "" ? "" : $dados_conjuge['logradouro']; ?>">
                                <label class="fg-label">LOGRADOURO<sup>*</sup></label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-2 item-form">
                            <div class="has-feedback form-group fg-float">
                              <span class="zmdi zmdi-keyboard form-control-icons"></span>
                              <div id="div_numero" class="fg-line">
                                <input id="numero" name="numero" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['numero'] == "" ? "" : $dados_conjuge['numero']; ?>" data-mask="#####">
                                <label class="fg-label">NÚMERO<sup>*</sup></label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row space-t-25">
                          <div class="col-md-3 item-form">
                            <div class="form-group fg-float">
                              <div id="div_bairro" class="fg-line">
                                <input onKeyUp="bairro_autocomplete()" id="bairro" name="bairro" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['bairro'] == "" ? "" : $dados_conjuge['bairro']; ?>">
                                <label class="fg-label">BAIRRO<sup>*</sup></label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3 item-form">
                            <div class="form-group fg-float">
                              <div id="div_quadra" class="fg-line">
                                <input id="quadra" name="quadra" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['quadra'] == "" ? "" : $dados_conjuge['quadra']; ?>">
                                <label class="fg-label">QUADRA</label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3 item-form">
                            <div class="form-group fg-float">
                              <div id="div_casa" class="fg-line">
                                <input id="casa" name="casa" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['lote'] == "" ? "" : $dados_conjuge['lote']; ?>">
                                <label class="fg-label">CASA</label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3 item-form">
                            <div class="form-group fg-float">
                              <div id="div_complemento" class="fg-line">
                                <input id="complemento" name="complemento" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['complemento'] == "" ? "" : $dados_conjuge['complemento']; ?>">
                                <label class="fg-label">COMPLEMENTO</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
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
                              <input type="text" id="contato_email" name="contato_email" class="form-control" value="<?= $dados_conjuge['email'] == "" ? "" : $dados_conjuge['email']; ?>">
                              <label class="fg-label">E-MAIL PESSOAL</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-25">
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-phone form-control-icons"></span>
                            <div id="div_residencial" class="fg-line">
                              <input type="text" id="residencial" name="residencial" class="form-control" maxlength="14" data-mask="(00) 00000-0000" value="<?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 1"); ?>">
                              <label class="fg-label">RESIDENCIAL</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-smartphone-iphone form-control-icons"></span>
                            <div id="div_celular" class="fg-line">
                              <input type="text" id="celular" name="celular" class="form-control" maxlength="14" data-mask="(00) 00000-0000" value="<?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 2"); ?>">
                              <label class="fg-label">CELULAR</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-phone form-control-icons"></span>
                            <div id="div_comercial" class="fg-line">
                              <input type="text" id="comercial" name="comercial" class="form-control" maxlength="14" data-mask="(00) 00000-0000" value="<?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 3"); ?>">
                              <label class="fg-label">COMERCIAL</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-phone-forwarded form-control-icons"></span>
                            <div id="div_ramal" class="fg-line">
                              <input type="text" id="ramal" name="ramal" class="form-control" maxlength="6" data-mask="000000" value="<?= pesquisar_tabela("ramal", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 3"); ?>">
                              <label class="fg-label">RAMAL</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
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
                      <div class="row space-t-20">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">VOCÊ TRABALHA?</p>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_conjuge['cargo'] != "" ? "checked='true'" : ""; ?> id="trabalha_sim" name="trabalha" type="radio" value="1">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_conjuge['cargo'] != "" ? "" : "checked='true'"; ?> id="trabalha_nao" name="trabalha" type="radio" value="0">
                                  <i class="input-helper"></i>
                                  NÃO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="trabalha_sim_nao_mesmo_endereco" <?= $dados_conjuge['cargo'] != "" ? "" : "style='display: none'"; ?> class="row space-t-10">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">TRABALHA NO MESMO ENDEREÇO EM QUE RESIDE?</p>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_conjuge['trab_mesmo_endereco'] == 1 ? "checked='true'" : ""; ?> id="trab_mesmo_endereco_sim" name="trab_mesmo_endereco" type="radio" value="1">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $dados_conjuge['trab_mesmo_endereco'] == 0 ? "checked='true'" : ""; ?> id="trab_mesmo_endereco_nao" name="trab_mesmo_endereco" type="radio" value="0">
                                  <i class="input-helper"></i>
                                  NÃO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div <?= $dados_conjuge['cargo'] != "" ? "" : "style='display: none'"; ?> id="trabalha_sim_nao" class="row space-t-25">
                        <div class="col-md-3 item-form">
                          <div class="form-group fg-float">
                            <div id="div_cargo_funcao" class="fg-line">
                              <input id="cargo_funcao" name="cargo_funcao" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['cargo'] == "" ? "" : $dados_conjuge['cargo']; ?>">
                              <label class="fg-label">OCUPAÇÃO/CARGO/FUNÇÃO</label>
                            </div>
                          </div>
                        </div>
                        <div id="trab_mesmo_endereco_sim_nao" <?= $dados_conjuge['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?>>
                          <div class="col-md-3 item-form">
                            <div class="form-group fg-float">
                              <div id="div_local_trabalho" class="fg-line">
                                <input id="local_trabalho" name="local_trabalho" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['instituicao'] == "" ? "" : $dados_conjuge['instituicao']; ?>">
                                <label class="fg-label">LOCAL DE TRABALHO</label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4 item-form">
                            <div class="form-group fg-float">
                              <div id="div_trab_endereco" class="fg-line">
                                <input id="trab_endereco" name="trab_endereco" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['endereco'] == "" ? "" : $dados_conjuge['endereco']; ?>">
                                <label class="fg-label">ENDEREÇO</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2 item-form">
                          <div class="has-feedback form-group fg-float">
                            <span class="zmdi zmdi-calendar form-control-icons"></span>
                            <div id="div_data_inicio" class="fg-line">
                              <input id="data_inicio" name="data_inicio" type="text" class="form-control date-picker" value="<?= $dados_conjuge['data_inicio'] == "" || $dados_conjuge['data_inicio'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_conjuge['data_inicio']); ?>" data-mask="00/00/0000">
                              <label class="fg-label">DATA INÍCIO</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
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
                                  <input <?= $provedor_lar_marcado_sim; ?> id="provedor_sim" name="provedor" type="radio" value="1" name="sample">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $provedor_lar_marcado_nao; ?> id="provedor_nao" name="provedor" type="radio" value="0" name="sample">
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
                          <label for="">GRAU ESCOLAR<sup>*</sup></label>
                          <select id="grau_escolar" name="grau_escolar" class="selectpicker" data-live-search="true">
                            <option value="">ESCOLHA O SEU GRAU ESCOLAR</option>
                            <?php
                            $result = $db->prepare("SELECT id, nome FROM hab_grau_escolar WHERE status = 1 ORDER BY nome ASC");
                            $result->execute();
                            while ($grau = $result->fetch(PDO::FETCH_ASSOC)) {
                              if ($dados_conjuge['hab_grau_escolar_id'] == $grau['id']) {
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
                                  <input <?= $estudando_sim; ?> id="estudando_sim" name="estudando" type="radio" value="1" name="sample">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $estudando_nao; ?> id="estudando_nao" name="estudando" type="radio" value="0" name="sample">
                                  <i class="input-helper"></i>
                                  NÃO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div <?= $esta_estudando; ?> id="estiver_estudando">
                        <div class="row space-t-25">
                          <div class="col-md-6 item-form">
                            <div class="form-group fg-float">
                              <div id="div_nome_instituicao" class="fg-line">
                                <input id="nome_instituicao" name="nome_instituicao" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['instituicao_nome']; ?>">
                                <label class="fg-label">NOME DA INSTITUIÇÃO</label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3 item-form">
                            <div class="form-group fg-float">
                              <div id="div_serie_periodo" class="fg-line">
                                <input id="serie_periodo" name="serie_periodo" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['serie_periodo']; ?>">
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
                                if ($dados_conjuge['hab_instituicao_natureza_id'] == $natureza['id']) {
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
                        <div <?= $natureza_instituicao; ?> id="natureza_instituicao">
                          <div class="row space-t-10">
                            <div class="col-md-4">
                              <div class="row">
                                <div class="col-md-12">
                                  <p class="c-black f-500">FINANCIADO POR MEIOS PRÓPRIOS?</p>
                                </div>
                                <div class="col-lg-4">
                                  <div class="radio m-b-15">
                                    <label>
                                      <input <?= $financia_sim; ?> id="financia_sim" name="financia" type="radio" value="1" name="sample">
                                      <i class="input-helper"></i>
                                      SIM
                                    </label>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="radio m-b-15">
                                    <label>
                                      <input <?= $financia_nao; ?> id="financia_nao" name="financia" type="radio" value="2" name="sample">
                                      <i class="input-helper"></i>
                                      NÃO
                                    </label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div <?= $proprios_meios; ?> id="proprios_meios">
                            <div class="row space-t-25">
                              <div class="col-md-6 sel-form">
                                <div class="form-group fg-float">
                                  <div id="div_programa_social" class="fg-line">
                                    <p>PROGRAMA SOCIAL</p>
                                    <select id="programa_social" name="programa_social" class="selectpicker" data-live-search="true">
                                      <option value="">ESCOLHA O PROGRAMA SOCIAL</option>
                                      <?php
                                      $result = $db->prepare("SELECT id, nome FROM hab_programa_social WHERE status = 1 ORDER BY nome ASC");
                                      $result->execute();
                                      while ($social = $result->fetch(PDO::FETCH_ASSOC)) {
                                        if ($dados_conjuge['hab_programa_social_id'] == $social['id']) {
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
                                    <input id="porcentagem" name="porcentagem" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['bolsa_percentual']; ?>" data-mask="###" max="3">
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
                              <i class="zmdi zmdi-hospital topo-icons-etapas"></i>
                              <b>INFORMAÇÕES COMPLEMENTARES</b>
                            </div>
                          </div>
                          <!--/ .form-header-->
                        </div>
                      </div>
                      <div class="row space-t-10">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">POSSUI ALGUM TIPO DE DEFICIÊNCIA?</p>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $deficiencia_sim; ?> id="deficiencia_sim" name="deficiencia" type="radio" value="1" name="sample">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $deficiencia_nao; ?> id="deficiencia_nao" name="deficiencia" type="radio" value="0" name="sample">
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
                            <option value="">Escolha o capítulo</option>
                            <?php
                            $result = $db->prepare("SELECT id, catinic, catfim, descricao
                         FROM hab_cid10_capitulo
                         ORDER BY catinic ASC");
                            $result->execute();
                            while ($capitulo = $result->fetch(PDO::FETCH_ASSOC)) {
                              if ($dados_conjuge['hab_cid10_capitulo_id'] == $capitulo['id']) {
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
                            if ($conjuge_id == "" || $dados_conjuge['hab_cid10_grupo_id'] == "") {
                              ?>
                              <option value="">ESCOLHA PRIMEIRO O CAPÍTULO</option>
                              <?php
                            } else {
                              $result2 = $db->prepare("SELECT * FROM hab_cid10_grupo ORDER BY catinic ASC");
                              $result2->execute();
                              while ($grupo = $result2->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_conjuge['hab_cid10_grupo_id'] == $grupo['id']) {
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
                            if ($conjuge_id == "" || $dados_conjuge['hab_cid10_categoria_id'] == "") {
                              ?>
                              <option value="">ESCOLHA PRIMEIRO O GRUPO</option>
                              <?php
                            } else {
                              $result3 = $db->prepare("SELECT * FROM hab_cid10_categoria ORDER BY cat ASC");
                              $result3->execute();
                              while ($categoria = $result3->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_conjuge['hab_cid10_categoria_id'] == $categoria['id']) {
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
                      <div class="row space-t-40">
                        <div class="col-md-4 item-form">
                          <div class="form-group fg-float">
                            <div id="div_cad_unico" class="fg-line">
                              <input id="cad_unico" name="cad_unico" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['cadastro_unico']; ?>">
                              <label class="fg-label">CAD. ÚNICO<sup>*</sup></label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4 item-form">
                          <div class="form-group fg-float">
                            <div id="div_nis" class="fg-line">
                              <input id="nis" name="nis" type="text" class="input-sm form-control fg-input" value="<?= $dados_conjuge['nis']; ?>" data-mask="###########" maxlength="11">
                              <label class="fg-label">NIS<sup>*</sup></label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row space-t-10">
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
                      <div <?= $possui_vinculo; ?> id="possuir_vinculo">
                        <div class="row space-t-25">
                          <div class="col-md-6 item-form">
                            <div class="form-group fg-float">
                              <div id="div_vinculo_nome" class="fg-line">
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
                    </div>
                    <div class="row space-t-10">
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
                  </form>
                  <!--/ form-->
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
<script src="<?= PORTAL_URL; ?>hab/js/candidato/etapa_wizard.js"></script>
<script src="<?= PORTAL_URL; ?>hab/js/candidato/etapa2.js"></script>