<?php include('template/topo.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0));
$param = $GLOBALS['urlParametro'] == '' ? 0 : $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

// SELECT PARA PESSOA
$stmt = $db->prepare("SELECT r.id AS renda, e.cep, c.id, p.id AS pessoa_id, p.cpf, p.rg_numero, p.cnh_numero, p.cie_rne, p.deficiencia, p.cadastro_unico, p.nis, p.cert_nasc_livro, p.uniao_estavel
                      FROM hab_candidato AS c
                      LEFT JOIN hab_pessoa AS p ON p.id = c.hab_pessoa_id  
                      LEFT JOIN hab_pessoa_endereco AS e ON e.id = p.endereco_candidato
                      LEFT JOIN hab_pessoa_renda AS r ON r.hab_pessoa_id = p.id
                      WHERE c.id = ?");

$stmt->bindValue(1, $param);
$stmt->execute();
$dadosCandidato = $stmt->fetch(PDO::FETCH_ASSOC);

// SELECT PARA PESSOA->CONJUGE
$stmt = $db->prepare("SELECT r.id AS renda, e.cep, cj.hab_candidato_id AS candidato_id, p.id AS pessoa_id, cj.id, cj.hab_pessoa_id_conjuge, p.cpf, p.rg_numero, p.cnh_numero, p.cie_rne, p.deficiencia, p.cadastro_unico, p.nis, p.cert_nasc_livro, p.uniao_estavel
                      FROM hab_conjuge AS cj 
                      LEFT JOIN hab_pessoa AS p ON p.id = cj.hab_pessoa_id_conjuge
                      LEFT JOIN hab_pessoa_endereco AS e ON e.id = p.endereco_candidato
                      LEFT JOIN hab_pessoa_renda AS r ON r.hab_pessoa_id = p.id
                      WHERE cj.hab_candidato_id = ? AND cj.tipo = 0 AND cj.status = 1");
$stmt->bindValue(1, $param);
$stmt->execute();
$dadosConjuge = $stmt->fetch(PDO::FETCH_ASSOC);

// SELECT PESSOA->FAMILIAR
$stmt = $db->prepare("SELECT r.id AS renda, e.cep, f.hab_candidato_id AS candidato_id, p.id AS pessoa_id, f.id, f.hab_pessoa_id, p.nome AS pessoa_nome, f.hab_grau_parentesco_id, UPPER(gp.nome) AS grau_parentesco_nome, p.cpf, p.rg_numero, p.cnh_numero, p.cie_rne, p.deficiencia, p.cadastro_unico, p.nis, p.cert_nasc_livro, p.uniao_estavel
                      FROM hab_familiar as f
                      LEFT JOIN hab_pessoa AS p ON p.id = f.hab_pessoa_id
                      LEFT JOIN hab_grau_parentesco AS gp ON gp.id = f.hab_grau_parentesco_id
                      LEFT JOIN hab_pessoa_endereco AS e ON e.id = p.endereco_candidato
                      LEFT JOIN hab_pessoa_renda AS r ON r.hab_pessoa_id = p.id
                      WHERE f.hab_candidato_id = ?");
$stmt->bindValue(1, $param);
$stmt->execute();
$dadosFamiliares = $stmt->fetchAll(PDO::FETCH_ASSOC);

$docsUpload = array(
    'cpf' => array(
        'titulo' => 'CPF',
        'campo' => 'cpf'
    ),
    'rg_numero' => array(
        'titulo' => 'RG',
        'campo' => 'rg'
    ),
    'cnh_numero' => array(
        'titulo' => 'CNH',
        'campo' => 'cnh'
    ),
    'cie_rne' => array(
        'titulo' => 'Cédula de Identidade de Estrangeiro',
        'campo' => 'cie'
    ),
    'deficiencia' => array(
        'titulo' => 'Deficiência',
        'campo' => 'deficiencia'
    ),
    'cadastro_unico' => array(
        'titulo' => 'Cadastro Único',
        'campo' => 'cadastro_unico'
    ),
    'nis' => array(
        'titulo' => 'NIS',
        'campo' => 'nis'
    ),
    'cert_nasc_livro' => array(
        'titulo' => 'Certidao de Nascimento Livro',
        'campo' => 'certidao_nascimento'
    ),
    'uniao_estavel' => array(
        'titulo' => 'União Estavel',
        'campo' => 'uniao_estavel'
    ),
    'cep' => array(
        'titulo' => 'Endereço',
        'campo' => 'endereco'
    ),
    'renda' => array(
        'titulo' => 'Renda Comprovada',
        'campo' => 'renda_comprovada'
    ),
    'id' => array(
        'titulo' => 'Comprovante de Cadastro',
        'campo' => 'comprovante'
    )
);

$docsUploadConjuge = array(
    'cpf' => array(
        'titulo' => 'CPF',
        'campo' => 'cpf'
    ),
    'rg_numero' => array(
        'titulo' => 'RG',
        'campo' => 'rg'
    ),
    'cnh_numero' => array(
        'titulo' => 'CNH',
        'campo' => 'cnh'
    ),
    'cie_rne' => array(
        'titulo' => 'Cédula de Identidade de Estrangeiro',
        'campo' => 'cie'
    ),
    'deficiencia' => array(
        'titulo' => 'Deficiência',
        'campo' => 'deficiencia'
    ),
    'cadastro_unico' => array(
        'titulo' => 'Cadastro Único',
        'campo' => 'cadastro_unico'
    ),
    'nis' => array(
        'titulo' => 'NIS',
        'campo' => 'nis'
    ),
    'cert_nasc_livro' => array(
        'titulo' => 'Certidao de Nascimento Livro',
        'campo' => 'certidao_nascimento'
    ),
    'uniao_estavel' => array(
        'titulo' => 'União Estavel',
        'campo' => 'uniao_estavel'
    ),
    'cep' => array(
        'titulo' => 'Endereço',
        'campo' => 'endereco'
    ),
    'renda' => array(
        'titulo' => 'Renda Comprovada',
        'campo' => 'renda_comprovada'
    )
);

$docsUploadFamiliar = array(
    'cpf' => array(
        'titulo' => 'CPF',
        'campo' => 'cpf'
    ),
    'rg_numero' => array(
        'titulo' => 'RG',
        'campo' => 'rg'
    ),
    'cnh_numero' => array(
        'titulo' => 'CNH',
        'campo' => 'cnh'
    ),
    'cie_rne' => array(
        'titulo' => 'Cédula de Identidade de Estrangeiro',
        'campo' => 'cie'
    ),
    'deficiencia' => array(
        'titulo' => 'Deficiência',
        'campo' => 'deficiencia'
    ),
    'cadastro_unico' => array(
        'titulo' => 'Cadastro Único',
        'campo' => 'cadastro_unico'
    ),
    'nis' => array(
        'titulo' => 'NIS',
        'campo' => 'nis'
    ),
    'cert_nasc_livro' => array(
        'titulo' => 'Certidao de Nascimento Livro',
        'campo' => 'certidao_nascimento'
    ),
    'uniao_estavel' => array(
        'titulo' => 'União Estavel',
        'campo' => 'uniao_estavel'
    ),
    'cep' => array(
        'titulo' => 'Endereço',
        'campo' => 'endereco'
    ),
    'renda' => array(
        'titulo' => 'Renda Comprovada',
        'campo' => 'renda_comprovada'
    )
);

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


<?php include('template/sidebar.php'); ?>
<section id="content">
  <div class="c-header">
    <div class="card icons-demo">
      <div class="card-header cw-header <?= vf_retroativo($param) ? 'palette-Black' : 'palette-Teal-600' ?> bg">
        <div class="cwh-year">Candidato</div>
        <div class="cwh-day">Cadastro</div>
        <!--        <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a>-->
      </div>
      <div class="card-body card-padding-sm">
        <div id="cw-body">
          <div class="card-body">
            <div id="content">
              <div class="form-container">
                <div id="tmm-form-wizard" class="substrate">
                  <?php
                  include ('etapa_wizard.php');
                  ?>
                  <div class="form-header">
                    <div class="form-title">
                      <i class="zmdi zmdi-developer-board topo-icons-etapas"></i>
                      <b>DOCUMENTAÇÃO DO TITULAR</b>
                    </div>
                  </div>
                  <!--/ .form-header-->
                  <form id="form_candidato_etapa4" name="form_candidato_etapa4" action="<?= PORTAL_URL; ?>hab/dao/pessoa/salvar_anexo" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="candidato_id" name="candidato_id" value="<?= $dadosCandidato['id']; ?>" />
                    <input type="hidden" id="index_anexo" name="index_anexo" value="11">
                    <input type="hidden" id="proxima_etapa" name="proxima_etapa" value="" />
                    <input type="hidden" id="retroativo" name="retroativo" value="<?= vf_retroativo($param); ?>" />
                    <input type="hidden" id='vf_usuario_pagina' name='vf_usuario_pagina' value="<?= $vf_usuario_pagina; ?>"/>
                    <div id="form_titular" class="form-wizard">
                      <div class="card-body">
                        <div class="row space-t-10">
                          <?php
                          $qtd = 0;
                          if (isset($dadosCandidato['id'])) {
                            foreach ($dadosCandidato as $key => $value) {
                              if (isset($docsUpload[$key])) {
                                $foto_caminho = pesquisar_tabela("" . $docsUpload[$key]['campo'] . "", "hab_pessoa_anexo", "hab_pessoa_id", "=", $dadosCandidato['pessoa_id'], "");

                                if ($foto_caminho != "") {
                                  $qtd ++;
                                }
                                ?>
                                <div id="div_anexo" class="col-md-3 col-sm-4 col-xs-12 space-t-34">
                                  <input type="hidden" id="anexo_qtd" name="anexo_qtd[]" value="<?= $qtd; ?>">
                                  <input type="hidden" id="anexo_id" name="candidato_anexo_id">
                                  <input type="hidden" id="anexo_nome" name="candidato_anexo_nome">
                                  <input type="hidden" id="anexo_endereco" name="candidato_anexo_endereco">
                                  <input type="hidden" id="tabela_campo" name="tabela_campo[]" value="<?= $docsUpload[$key]['campo']; ?>">
                                  <input type="hidden" id="foto_caminho" name="foto_caminho[]" value="<?= $foto_caminho != "" && $foto_caminho != NULL ? $foto_caminho : '' ?>">
                                  <input type="hidden" id="pessoa_id" name="pessoa_id[]" value="<?= $dadosCandidato['pessoa_id']; ?>" />
                                  <p class="f-500 c-black m-b-20"><?= $docsUpload[$key]['titulo']; ?></p>
                                  <div class="fileinput <?= $foto_caminho != "" && $foto_caminho != NULL ? 'fileinput-exists' : 'fileinput-new' ?>" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"><?php if ($foto_caminho != "" && $foto_caminho != NULL) { ?><img src="../../../../../<?= $foto_caminho; ?>" /><?php } ?></div>
                                    <div class="arquivo_cpf">
                                      <span class="btn btn-info btn-file">
                                        <span class="fileinput-new">Selecionar imagem</span>
                                        <span class="fileinput-exists">Alterar imagem</span>
                                        <input type="file" id="anexo" name="anexo[]">
                                      </span>
                                      <a id="remover_foto" href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                    </div>
                                  </div>
                                </div>
                                <?php
                              }
                            }
                          }
                          ?>
                        </div>
                      </div>
                    </div>

                    <?php
                    if (isset($dadosConjuge['id'])) {
                      ?>

                      <hr size="1" style="width: 100%" />
                      <div class="row">
                        <div class="col-xs-12">
                          <div class="form-header">
                            <div class="form-title">
                              <i class="zmdi zmdi-developer-board topo-icons-etapas"></i>
                              <b>DOCUMENTAÇÃO DO CONJUGE</b>
                            </div>
                          </div>
                          <!--/ .form-header-->
                        </div>
                      </div>
                      <!--/ .row-->


                      <?php
                      $contador = 9;
                      $dadosConjugeQtd = $dadosConjuge;
                      if (isset($dadosConjugeQtd['id'])) {
                        foreach ($dadosConjugeQtd as $key => $value) {
                          if (isset($docsUploadConjuge[$key])) {
                            $foto_caminho = pesquisar_tabela("" . $docsUploadConjuge[$key]['campo'] . "", "hab_pessoa_anexo", "hab_pessoa_id", "=", $dadosConjugeQtd['pessoa_id'], "");
                            if ($foto_caminho != "" && $foto_caminho != NULL) {
                              $contador ++;
                            }
                          }
                        }
                      }
                      ?>

                      <input type="hidden" id="id" name="id" value="<?= $dadosConjuge['candidato_id']; ?>" />
                      <input type="hidden" id="index_anexo_conjuge" name="index_anexo_conjuge" value="22">
                      <div id="form_conjuge" class="form-wizard">
                        <div class="card-body card-padding">
                          <div class="row space-t-10">
                            <?php
                            foreach ($dadosConjuge as $key => $value) {
                              if (isset($docsUploadConjuge[$key])) {
                                $foto_caminho = pesquisar_tabela("" . $docsUploadConjuge[$key]['campo'] . "", "hab_pessoa_anexo", "hab_pessoa_id", "=", $dadosConjuge['pessoa_id'], "");
                                ?>
                                <div id="div_anexo" class="col-sm-3 space-t-34">
                                  <input type="hidden" id="candidato_anexo_id" name="candidato_anexo_id">
                                  <input type="hidden" id="candidato_anexo_nome" name="candidato_anexo_nome">
                                  <input type="hidden" id="candidato_anexo_endereco" name="candidato_anexo_endereco">
                                  <input type="hidden" id="tabela_campo" name="tabela_campo[]" value="<?= $docsUploadConjuge[$key]['campo']; ?>">
                                  <input type="hidden" id="foto_caminho" name="foto_caminho[]" value="<?= $foto_caminho != "" && $foto_caminho != NULL ? $foto_caminho : '' ?>">
                                  <input type="hidden" id="pessoa_id" name="pessoa_id[]" value="<?= $dadosConjuge['pessoa_id']; ?>" />
                                  <p class="f-500 c-black m-b-20"><?= $docsUploadConjuge[$key]['titulo']; ?></p>
                                  <div class="fileinput <?= $foto_caminho != "" && $foto_caminho != NULL ? 'fileinput-exists' : 'fileinput-new' ?>" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"><?php if ($foto_caminho != "" && $foto_caminho != NULL) { ?><img src="../../../../../<?= $foto_caminho; ?>" /><?php } ?></div>
                                    <div class="arquivo_conjuge">
                                      <span class="btn btn-info btn-file">
                                        <span class="fileinput-new">Selecionar imagem</span>
                                        <span class="fileinput-exists">Alterar imagem</span>
                                        <input type="file" id="anexo" name="anexo[]">
                                      </span>
                                      <a id="remover_foto" href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                    </div>
                                  </div>
                                </div>
                                <?php
                              }
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      <?php
                    }
                    ?>
                    <!-- <div class="row">
                      <div class="col-xs-12">
                        <div class="form-header">
                          <div class="form-title form-icon title-icon-payment">
                            <b>DOCUMENTO DO CONJUGE</b>
                          </div>
                        </div>/ .form-header
                      </div>
                    </div>/ .row

                    <div class="form-wizard">
                      <div class="card-body card-padding">
                        <div class="row space-t-10">
                    <?php
                    if (isset($dadosConjuge['id'])) {
                      foreach ($dadosConjuge as $key => $value) {
                        if (isset($docsUpload[$key])) {
                          ?>
                                                                                                                                                  <div class="col-sm-3">
                                                                                                                                                    <input type="hidden" id="anexo_id" name="conjuge_anexo_id">
                                                                                                                                                    <input type="hidden" id="anexo_nome" name="conjuge_anexo_nome">
                                                                                                                                                    <input type="hidden" id="anexo_endereco" name="conjuge_anexo_endereco">
                                                                                                                                                    <input type="hidden" id="tabela_campo" name="conjuge_tabela_campo">
                                                                                                                                                    <p class="f-500 c-black m-b-20"><?= $docsUpload[$key]['titulo']; ?></p>
                                                                                                                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                                                                                      <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                                                                                                                                      <div class="arquivo_cpf">
                                                                                                                                                        <span class="btn btn-info btn-file">
                                                                                                                                                          <span class="fileinput-new">Selecionar imagem</span>
                                                                                                                                                          <span class="fileinput-exists">Alterar imagem</span>
                                                                                                                                                          <input type="file" id="anexo" name="anexo[]">
                                                                                                                                                        </span>
                                                                                                                                                        <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                                                                                                                                      </div>
                                                                                                                                                    </div>
                                                                                                                                                  </div>
                          <?php
                        }
                      }
                    } else {
                      ?>
                                                              <div class="col-sm-12">
                                                                Não existe conjuge para o candidato
                                                              </div>
                      <?php
                    }
                    ?>
                        </div>
                      </div>
                    </div>-->

                    <?php
                    if (isset($dadosFamiliares[0]['id'])) {
                      ?>
                      <hr size="1" style="width: 100%" />
                      <div class="row">
                        <div class="col-xs-12">
                          <div class="form-header">
                            <div class="form-title">
                              <i class="zmdi zmdi-developer-board topo-icons-etapas"></i>
                              <b>DOCUMENTAÇÃO DOS FAMILIARES</b>
                            </div>
                          </div>
                          <!--/ .form-header-->
                        </div>
                      </div>
                      <!--/ .row-->
                      <?php
                    }
                    ?>

                    <?php
                    $qtd = 0;
                    $contador = 22;
                    $dadosFamiliarQtd = $dadosFamiliares;
                    if (isset($dadosFamiliarQtd[0]['id'])) {
                      foreach ($dadosFamiliarQtd as $key => $value) {
                        $qtd ++;
                      }
                    }

                    $qtd *= 11;

                    $contador += $qtd;
                    ?>

                    <input type="hidden" id="id" name="id" value="<?= $dadosConjuge['candidato_id']; ?>" />
                    <input type="hidden" id="index_anexo_familiar" name="index_anexo_familiar" value="<?= $contador; ?>">
                    <div id="form_familiar" class="form-wizard">
                      <div class="card-body">
                        <div class="row space-t-10">
                          <?php
                          if (isset($dadosFamiliares[0]['id'])) {
                            foreach ($dadosFamiliares as $keyDadosFamiliar => $dadosFamiliar) {
                              ?>

                              <hr size="1" style="width: 100%" />

                              <div class="row">
                                <div class="col-xs-12">
                                  <div class="form-header">
                                    <div class="form-title">
                                      <i class="zmdi zmdi-developer-board topo-icons-etapas"></i>
                                      <b><?= $dadosFamiliar['grau_parentesco_nome'] . ' - ' . $dadosFamiliar['pessoa_nome']; ?></b>
                                    </div>
                                  </div>
                                  <!--/ .form-header-->
                                </div>
                              </div>
                              <!--/ .row-->


                              <?php
                              foreach ($dadosFamiliar as $key => $value) {

                                if (isset($docsUploadFamiliar[$key])) {
                                  $foto_caminho = pesquisar_tabela("" . $docsUploadFamiliar[$key]['campo'] . "", "hab_pessoa_anexo", "hab_pessoa_id", "=", $dadosFamiliar['pessoa_id'], "");
                                  ?>

                                  <div id="div_anexo" class="col-sm-3 space-t-34">
                                    <input type="hidden" id="candidato_anexo_id" name="candidato_anexo_id">
                                    <input type="hidden" id="candidato_anexo_nome" name="candidato_anexo_nome">
                                    <input type="hidden" id="candidato_anexo_endereco" name="candidato_anexo_endereco">
                                    <input type="hidden" id="tabela_campo" name="tabela_campo[]" value="<?= $docsUploadFamiliar[$key]['campo']; ?>">
                                    <input type="hidden" id="foto_caminho" name="foto_caminho[]" value="<?= $foto_caminho != "" && $foto_caminho != NULL ? $foto_caminho : '' ?>">
                                    <input type="hidden" id="pessoa_id" name="pessoa_id[]" value="<?= $dadosFamiliar['pessoa_id']; ?>" />
                                    <p class="f-500 c-black m-b-20"><?= $docsUploadFamiliar[$key]['titulo']; ?></p>
                                    <div class="fileinput <?= $foto_caminho != "" && $foto_caminho != NULL ? 'fileinput-exists' : 'fileinput-new' ?>" data-provides="fileinput">
                                      <div class="fileinput-preview thumbnail" data-trigger="fileinput"><?php if ($foto_caminho != "" && $foto_caminho != NULL) { ?><img src="../../../../../<?= $foto_caminho; ?>" /><?php } ?></div>
                                      <div class="arquivo_conjuge">
                                        <span class="btn btn-info btn-file">
                                          <span class="fileinput-new">Selecionar imagem</span>
                                          <span class="fileinput-exists">Alterar imagem</span>
                                          <input type="file" id="anexo" name="anexo[]">
                                        </span>
                                        <a id="remover_foto" href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                      </div>
                                    </div>
                                  </div>

                                  <?php
                                }
                              }
                            }
                          }
                          ?>
                        </div>
                      </div>
                    </div>

                    <hr size="1" style="width: 100%" />

                    <!-- TÍTULO -->
                    <div class="form-header">
                      <div class="form-title">
                        <i class="zmdi zmdi-developer-board topo-icons-etapas"></i>
                        <b>OUTROS DOCUMENTOS</b>
                      </div>
                    </div>
                    <!-- FIM TÍTULO -->

                    <!--/ .form-header-->
                    <div class="row space-50">
                      <div class="col-md-12">
                        <div id="div_outros_documentos">
                          <div id="clone_principal">
                            <div id="clonado" class="row-element space-50">
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <span id="arquivo_enviar" class="btn btn-primary btn-file m-r-10">
                                      <span class="fileinput-new">Selecione o arquivo</span>
                                      <span class="fileinput-exists">Trocar</span>
                                      <input id="outro_anexo" type="file" name="outro_anexo[]">
                                    </span>
                                    <span class="fileinput-filename"></span>
                                    <a href="#" class="close fileinput-exists" data-dismiss="fileinput"><i class="zmdi zmdi-minus-circle text-danger"></i></a>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group fg-float">
                                    <div class="fg-line">
                                      <input type="text" id="nome_digitado" name="nome_digitado[]" value="" class="form-control fg-input" maxlength="50"/>
                                      <label class="fg-label">Nome do arquivo</label>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <div id="botoes" class="new-button">
                                    <a style="display: none; cursor: pointer" id="anexo_foto_remove"><i class="zmdi zmdi-minus-circle text-danger"></i></a>
                                    <a style="cursor: pointer" id="anexo_foto_add"><i class="zmdi zmdi-plus-circle text-success"></i></a>
                                    <input type="hidden" id="anexo_id" name="anexo_id[]" value=""/>
                                    <input type="hidden" id="anexo_nome" name="anexo_nome[]" value=""/>
                                    <input type="hidden" id="index_anexo_outro" name="index_anexo_outro[]" value=""/>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- ATUALIZADO -->
                            <div id="clones">
                              <?php
                              $result_anexo = $db->prepare("SELECT * 
                                              FROM hab_pessoa_outro_anexo
                                              WHERE hab_candidato_id = ?
                                              ORDER BY nomeAnexo ASC");
                              $result_anexo->bindValue(1, $param);
                              $result_anexo->execute();
                              if ($result_anexo->rowCount() > 0) {
                                while ($anexo = $result_anexo->fetch(PDO::FETCH_ASSOC)) {
                                  ?>
                                  <div id="clonado" class="row-element space-50">
                                    <div class="row">
                                      <div class="col-md-4">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                          <span id="arquivo_enviar" class="btn btn-primary btn-file m-r-10">
                                            <span class="fileinput-new">Selecione o arquivo</span>
                                            <span class="fileinput-exists">Trocar</span>
                                            <input id="outro_anexo" type="file" name="outro_anexo[]" value="<?= $anexo['link']; ?>">
                                          </span>
                                          <span class="fileinput-filename"><?= resume($anexo['nomeAnexo'], 16); ?></span>
                                          <a href="#" class="close fileinput-exists" data-dismiss="fileinput"><i class="zmdi zmdi-minus-circle text-danger"></i></a>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group fg-float">
                                          <div class="fg-line">
                                            <input type="text" id="nome_digitado" name="nome_digitado[]" value="<?= $anexo['nome_digitado']; ?>" class="form-control fg-input" maxlength="50"/>
                                            <label class="fg-label">Nome do arquivo</label>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-2">
                                        <div id="botoes" class="new-button">
                                          <a style="cursor: pointer" id="anexo_foto_remove"><i class="zmdi zmdi-minus-circle text-danger"></i></a>
                                          <input type="hidden" id="anexo_id" name="anexo_id[]" value="<?= $anexo['id']; ?>"/>
                                          <input type="hidden" id="anexo_nome" name="anexo_nome[]" value="<?= $anexo['nomeAnexo']; ?>"/>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <?php
                                }
                              }
                              ?>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>

                    <hr size="1" style="width: 100%" />
                    <!-- TÍTULO -->
                    <div class="form-header">
                      <div class="form-title">
                        <i class="zmdi zmdi-developer-board topo-icons-etapas"></i>
                        <b>OBSERVAÇÃO DE CADASTRO</b>
                      </div>
                    </div>
                    <!-- FIM TÍTULO -->

                    <div id="clone_principal_obs">
                      <div id="clonado_obs" class="row space-30">
                        <div class="col-md-12">
                          <div id="div_outras_obs">
                            <div class="row-element space-50">
                              <div class="row">
                                <div class="col-md-10 clone-obs">
                                  <!-- <label for="observacao">Observação</label> -->
                                  <div class="form-group">
                                    <div class="fg-line" id="div_observacao">
                                      <textarea id="observacao" class="form-control" name="observacao[]" rows="5" placeholder="Informe a observação"></textarea>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <div id="botoes_obs" class="new-button">
                                    <a style="display: none; cursor: pointer" id="obs_remove"><i class="zmdi zmdi-minus-circle text-danger"></i></a>
                                    <a style="cursor: pointer" id="obs_add"><i class="zmdi zmdi-plus-circle text-success"></i></a>
                                    <input type="hidden" id="observacao_id" name="observacao_id[]" value=""/>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- ATUALIZADO -->
                      <div id="clones_obs">
                        <?php
                        $result_obs = $db->prepare("SELECT hco.id, hco.observacao, hco.data_cadastro, hco.data_update, u.nome 
                                              FROM hab_candidato_observacao hco
                                              LEFT JOIN seg_usuario AS u ON u.id = hco.seg_usuario_pai_id
                                              WHERE hco.hab_candidato_id = ?
                                              ORDER BY hco.id ASC");
                        $result_obs->bindValue(1, $param);
                        $result_obs->execute();
                        if ($result_obs->rowCount() > 0) {
                          while ($dadosObs = $result_obs->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <div id="clonado_obs" class="row space-30">
                              <div class="col-md-12">
                                <div id="div_outras_obs">
                                  <div class="row-element space-50">
                                    <div class="row">
                                      <div id="div_observacao" class="col-md-6">
                                        <textarea id="observacao" class="form-control" name="observacao[]" rows="5" placeholder="Observação"><?= $dadosObs['observacao']; ?></textarea>
                                      </div>
                                      <div class="col-md-2">
                                        <?= $dadosObs['nome']; ?>
                                      </div>
                                      <div class="col-md-2">
                                        <?= $dadosObs['data_update'] == NULL ? obterDataBRTimestamp($dadosObs['data_cadastro']) . " às " . obterHoraCompletaTimestamp($dadosObs['data_cadastro']) : obterDataBRTimestamp($dadosObs['data_update']) . " às " . obterHoraCompletaTimestamp($dadosObs['data_update']); ?>
                                      </div>
                                      <div class="col-md-2">
                                        <div id="botoes_obs" class="new-button">
                                          <a style="cursor: pointer" id="obs_remove"><i class="zmdi zmdi-minus-circle text-danger"></i></a>
                                          <input type="hidden" id="observacao_id" name="observacao_id[]" value="<?= $dadosObs['id']; ?>"/>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                              </div>
                            </div>
                            <?php
                          }
                        }
                        ?>
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
                            <button id="salvar" name="salvar" class="btn btn-success bg btn-lg">
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
                          if (vf_objeto_acao("cadastrar") && vf_retroativo($param) == false || (vf_objeto_acao("editar")) && vf_retroativo($param) == false) {
                            ?>
                            <button id="finalizar" name="finalizar" class="btn btn-danger btn-lg">Finalizar</button>                    
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
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
<script src="<?= PORTAL_URL; ?>hab/js/candidato/etapa4.js"></script>