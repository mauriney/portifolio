<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0));
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if (!is_numeric($param)) {
  echo "<script language='javaScript'>window.location.href='lista'</script>";
} else {
  $result = $db->prepare("SELECT hp.trab_mesmo_endereco, hgp.nome AS parentesco, hp.nis, hc.hab_grau_parentesco_id, hpes.hab_financia_natureza_id, hpes.hab_programa_social_id, hpes.bolsa_percentual, hpes.hab_instituicao_natureza_id, hpes.instituicao_nome, hpes.serie_periodo, hp.endereco_candidato, hp.hab_cid10_capitulo_id, hp.hab_cid10_grupo_id, hp.hab_cid10_categoria_id, hp.cadastro_unico, hp.deficiencia, hp.hab_grau_escolar_id, ho.instituicao, ho.endereco, ho.cargo, ho.data_inicio ,hp.provedor_lar, hp.bsc_estado_civil_id, hp.cie_data_validade, hp.cie_data_expedicao, hp.cie_rne, hp.bsc_cie_classificacao_id, hp.bsc_municipio_id_natural, hp.rg_numero, hp.rg_orgao_expedicao_id, hp.rg_uf_expedicao, hp.rg_data_expedicao, hp.cnh_numero, hp.cnh_uf_expedicao, hp.cnh_data_validade, hp.cnh_data_expedicao, hp.id AS pessoa_id, hp.email, hpe.complemento, hpe.lote, hpe.quadra, hpe.bairro, hpe.numero, hpe.logradouro, hpe.cep, hc.id, hp.nome, hp.cpf, hpe.bsc_municipio_id, hp.bsc_nacionalidade_id, hp.cadastro_retroativo_ano, hp.bsc_pele_cor_id, hp.data_nascimento, hp.bsc_sexo_id
    FROM hab_familiar hc
    LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
    LEFT JOIN hab_pessoa_endereco AS hpe ON hpe.hab_pessoa_id = hp.id
    LEFT JOIN hab_ocupacao AS ho ON ho.hab_pessoa_id = hc.hab_pessoa_id
    LEFT JOIN hab_pessoa_escolar AS hpes ON hpes.hab_pessoa_id = hc.hab_pessoa_id
    LEFT JOIN hab_grau_parentesco AS hgp ON hgp.id = hc.hab_grau_parentesco_id
    WHERE hc.hab_candidato_id = ? AND hc.status = 1");
  $result->bindValue(1, $param);
  $result->execute();

  $dadosFamiliares = $result->fetchAll(PDO::FETCH_ASSOC);

  $qtd_familiar = $result->rowCount();

  $pessoa_id = pesquisar_tabela("hab_pessoa_id", "hab_candidato", "id", "=", $param, "");

  // VERIFICA SE O TITULAR POSSUÍ FAMILIAR CADASTRADO
  if ($qtd_familiar > 0) {
    $familia_sim = "checked='true'";
    $familia_nao = "";
    $div_familiar = "";
  } else {
    $familia_sim = "";
    $familia_nao = "checked='true'";
    $div_familiar = "style='display: none'";
  }
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
        <div class="cwh-year">Candidato</div>
        <div class="cwh-day">Cadastro</div>
        <!--         <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a> -->
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
                  <form id="form_candidato_etapa3" name="form_candidato_etapa3" action="#" method="post">
                    <input type="hidden" id="id" name="id" value="<?= $param; ?>" />
                    <input type="hidden" id="qtd_familiar" name="qtd_familiar" value="<?= $qtd_familiar; ?>" />
                    <input type="hidden" id="caminho_pagina" name="caminho_pagina" value="etapa4" />
                    <input type="hidden" id="uniao_estavel_titular" name="uniao_estavel_titular" value="<?= pesquisar_tabela("uniao_estavel", "hab_pessoa", "id", "=", $pessoa_id, ""); ?>" />
                    <input type="hidden" id="estado_civil_titular" name="estado_civil_titular" value="<?= pesquisar_tabela("bsc_estado_civil_id", "hab_pessoa", "id", "=", $pessoa_id, ""); ?>" />
                    <input type="hidden" id="retroativo" name="retroativo" value="<?= vf_retroativo($param); ?>" />
                    <input type="hidden" id='vf_usuario_pagina' name='vf_usuario_pagina' value="<?= $vf_usuario_pagina; ?>"/>
                    <div class="row">
                      <div class="row space-t-20">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <p class="c-black f-500">POSSUI FAMILIAR?</p>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $familia_sim; ?> id="familia_sim" name="familia" type="radio" value="1" name="sample">
                                  <i class="input-helper"></i>
                                  SIM
                                </label>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="radio m-b-15">
                                <label>
                                  <input <?= $familia_nao; ?> id="familia_nao" name="familia" type="radio" value="0" name="sample">
                                  <i class="input-helper"></i>
                                  NÃO
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div id="div_possui" <?= $div_familiar == "style='display: none'" && is_numeric(pesquisar_tabela("id", "hab_familiar", "hab_candidato_id", "=", $param, "")) ? "" : "style='display: none'" ?> class="row space-t-20">
                      <div class="col-md-12">
                        <label class="fg-label">OBSERVAÇÃO<sup>*</sup></label>
                        <div class="form-group fg-float">
                          <div id="div_observacao" class="fg-line">
                            <textarea class="form-control" id="observacao" name="observacao" rows="2" placeholder="Observação"><?= pesquisar_tabela("observacao", "hab_familiar", "hab_candidato_id", "=", $param, ""); ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div <?= $div_familiar; ?> id="div_familiar">
                      <div class="row">
                        <div class="col-md-12">
                          <div role="tabpanel" class="tab">
                            <div class="row">
                              <ul id="ul_menus" class="tab-nav membros" role="tablist">
                                <?php
                                $parentesco_array = Array();
                                $class = "class='active'";
                                foreach ($dadosFamiliares as $k => $dados_familiar) {
                                  $parentesco_array[$k] = $dados_familiar['hab_grau_parentesco_id'];
                                  ?>
                                  <li <?= $class; ?>>
                                    <a error="false" href="#formulario_<?= $dados_familiar['id']; ?>" aria-controls="formulario_<?= $dados_familiar['id']; ?>" role="tab" data-toggle="tab"><?= $dados_familiar['parentesco']; ?></a>
                                    <a id="remover_formulario" rel="formulario_<?= $dados_familiar['id']; ?>" href="#" class="excluir-membro">
                                      <i class="zmdi zmdi-close-circle"></i>
                                    </a>
                                  </li>
                                  <?php
                                  $class = "role='presentation'";
                                }
                                ?>
                                <li id="formulario_add_novo">
                                  <a href="#formulario_novo" aria-controls="formulario_novo" role="tab" data-toggle="tab">
                                    <i class="zmdi zmdi-plus-circle zmdi-hc-fw"></i>
                                  </a>
                                </li>
                              </ul>
                            </div>
                            <div class="tab-content register">
                              <?php
                              $contador = 1;
                              $active = "active";
                              foreach ($dadosFamiliares as $k => $dados_familiar) {

                                $candidato_id = $param;
                                $familiar_id = $dados_familiar['id'];
                                $pessoa_id = $dados_familiar['pessoa_id'];

                                if ($dados_familiar['hab_financia_natureza_id'] == 2) {
                                  $financia_sim = "";
                                  $financia_nao = "checked='true'";
                                  $proprios_meios = "";
                                } else {
                                  $financia_sim = "checked='true'";
                                  $financia_nao = "";
                                  $proprios_meios = "style='display: none'";
                                }

                                if ($dados_familiar['hab_instituicao_natureza_id'] == 2) {
                                  $natureza_instituicao = "";
                                } else {
                                  $natureza_instituicao = "style='display: none'";
                                }

                                if ($dados_familiar['instituicao_nome'] != "") {
                                  $esta_estudando = "";
                                  $estudando_sim = "checked='true'";
                                  $estudando_nao = "";
                                } else {
                                  $esta_estudando = "style='display: none'";
                                  $estudando_sim = "";
                                  $estudando_nao = "checked='true'";
                                }

                                if ($dados_familiar['deficiencia'] == 1) {
                                  $deficiencia_sim = "checked='true'";
                                  $deficiencia_nao = "";
                                } else {
                                  $deficiencia_sim = "";
                                  $deficiencia_nao = "checked='true'";
                                }

                                if ($dados_familiar['provedor_lar'] == 1) {
                                  $provedor_lar_marcado_sim = "checked='true'";
                                  $provedor_lar_marcado_nao = "";
                                } else {
                                  $provedor_lar_marcado_sim = "";
                                  $provedor_lar_marcado_nao = "checked='true'";
                                }

                                if ($dados_familiar['instituicao'] != "") {
                                  $se_trabalha = "";
                                  $trabalha_sim = "checked='true'";
                                  $trabalha_nao = "";
                                } else {
                                  $trabalha_sim = "";
                                  $trabalha_nao = "checked='true'";
                                  $se_trabalha = "style='display: none'";
                                }

                                if ($dados_familiar['endereco_candidato'] == 1) {
                                  $mora_sim = "checked='true'";
                                  $mora_nao = "";
                                  $mesmo_endereco = "style='display: none'";
                                } else {
                                  $mora_sim = "";
                                  $mesmo_endereco = "";
                                  $mora_nao = "checked='true'";
                                }

                                if ($dados_familiar['bsc_sexo_id'] == 2) {
                                  $sexo_masculino = "";
                                  $sexo_feminino = "checked='true'";
                                } else {
                                  $sexo_masculino = "checked='true'";
                                  $sexo_feminino = "";
                                }

                                if (is_numeric($dados_familiar['bsc_municipio_id_natural'])) {
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

                                if ($dados_familiar['rg_numero'] != "") {
                                  $documento_1_1 = "";
                                  $documento_1_2 = "style='display: none'";
                                } else if ($dados_familiar['cnh_numero'] != "") {
                                  $documento_1_1 = "style='display: none'";
                                  $documento_1_2 = "";
                                } else {
                                  $documento_1_1 = "style='display: none'";
                                  $documento_1_2 = "style='display: none'";
                                }
                                ?>

                                <input type="hidden" id="array_familiar_id" name="array_familiar_id[]" value="<?= $familiar_id; ?>" />
                                <div role="tabpanel" class="tab-pane <?= $active; ?> animated fadeInRight" id="formulario_<?= $dados_familiar['id']; ?>">
                                  <?php include('formulario.php'); ?>
                                </div>

                                <?php
                                $active = "";
                                $contador ++;
                              }

                              $dados_familiar = NULL;
                              $familiar_id = 0;
                              $pessoa_id = 0;

                              $financia_sim = "checked='true'";
                              $financia_nao = "";
                              $proprios_meios = "style='display: none'";
                              $natureza_instituicao = "style='display: none'";
                              $esta_estudando = "style='display: none'";
                              $estudando_sim = "";
                              $estudando_nao = "checked='true'";
                              $deficiencia_sim = "";
                              $deficiencia_nao = "checked='true'";
                              $provedor_lar_marcado_sim = "";
                              $provedor_lar_marcado_nao = "checked='true'";
                              $trabalha_sim = "";
                              $trabalha_nao = "checked='true'";
                              $se_trabalha = "style='display: none'";
                              $mora_sim = "";
                              $mesmo_endereco = "";
                              $mora_nao = "checked='true'";
                              $sexo_masculino = "checked='true'";
                              $sexo_feminino = "";
                              $brasileiro = "";
                              $estrangeiro = "style='display: none'";
                              $nacionalidade_brasileiro = "checked='true'";
                              $nacionalidade_estrangeiro = "";
                              $documento_1_1 = "style='display: none'";
                              $documento_1_2 = "style='display: none'";
                              ?>

                              <input type="hidden" id="contador" name="contador" value="<?= $contador; ?>" />
                              <div role="tabpanel" class="tab-pane animated fadeInRight" id="formulario_novo">
                                <?php include('formulario.php'); ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
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
                  </form>
                  <!--/ form-->
                </div>
                <!--/ .form-wizard -->
              </div>
              <!--/ .form-container-->
            </div>
            <!--/ #content-->
          </div>
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
<script src="<?= PORTAL_URL; ?>hab/js/candidato/etapa3.js"></script>