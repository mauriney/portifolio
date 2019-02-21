<?php
$etapaPage = $GLOBALS['urlArquivo'];
$stageTitular = '';
$stageConjuge = '';
$stageFamiliar = '';
$stageDocumentacao = '';

$stmt = $db->prepare('SELECT hc.id, hp.id AS hab_pessoa_id, hcj.id AS hab_conjugue_id, COUNT(hf.id) AS qtd_familiar 
                    FROM hab_candidato AS hc 
                    LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id 
                    LEFT JOIN hab_conjuge AS hcj ON hcj.hab_candidato_id = hc.id 
                    LEFT JOIN hab_familiar AS hf ON hf.hab_candidato_id = hc.id 
                    WHERE hc.id = ?');
$stmt->bindValue(1, $param);
$stmt->execute();
$dataWizard = $stmt->fetch(PDO::FETCH_ASSOC);

if ($etapaPage == 'etapa1') {
  $stageTitular = 'tmm-current';
} else if ($etapaPage == 'etapa2') {
  $stageTitular = 'tmm-success';
  $stageConjuge = 'tmm-current';
} else if ($etapaPage == 'etapa3') {
  $stageTitular = 'tmm-success';
  $stageConjuge = $dataWizard['hab_conjugue_id'] > 0 ? 'tmm-success' : '';
  $stageFamiliar = 'tmm-current';
} else if ($etapaPage == 'etapa4') {
  $stageTitular = 'tmm-success';
  $stageConjuge = $dataWizard['hab_conjugue_id'] > 0 ? 'tmm-success' : '';
  $stageFamiliar = $dataWizard['qtd_familiar'] > 0 ? 'tmm-success' : '';
  $stageDocumentacao = 'tmm-current';
}
?>
<!-- CSS WIZARD
================================================== -->
<link rel="stylesheet" href="<?= PORTAL_URL; ?>assets/plugins/form-wizard/css/tmm_form_wizard_style_demo.css" />
<link rel="stylesheet" href="<?= PORTAL_URL; ?>assets/plugins/form-wizard/css/grid.css" />
<link rel="stylesheet" href="<?= PORTAL_URL; ?>assets/plugins/form-wizard/css/tmm_form_wizard_layout.css" />
<link rel="stylesheet" href="<?= PORTAL_URL; ?>assets/plugins/form-wizard/css/fontello.css" />
<div class="row stage-container">
  <div class="stage <?= $stageTitular;?> titular col-md-3 col-sm-3">
    <div id="clicar_titular" style="cursor: pointer" class="stage-header">
      <i class="zmdi zmdi-assignment-account zmdi-hc-fw topo-icons"></i>
    </div>
    <div class="stage-content">
      <h3 class="stage-title">TITULAR</h3>
      <div class="stage-info"></div>
    </div>
  </div>
  <!--/ .stage-->
  <div class="stage <?= $stageConjuge;?> conjuge col-md-3 col-sm-3 col-xs-12">
    <div id="clicar_conjuge" style="cursor: pointer" class="stage-header">
      <i class="zmdi zmdi-male-female zmdi-hc-fw topo-icons"></i>
    </div>
    <div class="stage-content">
      <h3 class="stage-title">CÔNJUGE</h3>
      <div class="stage-info"></div>
    </div>
  </div>
  <!--/ .stage-->
  <div class="stage <?= $stageFamiliar;?> grupo-familiar col-md-3 col-sm-3 col-xs-12">
    <div id="clicar_familiar" style="cursor: pointer" class="stage-header">
      <i class="zmdi zmdi-accounts zmdi-hc-fw topo-icons"></i>
    </div>
    <div class="stage-content">
      <h3 class="stage-title">GRUPO FAMILIAR</h3>
      <div class="stage-info"></div>
    </div>
  </div>
  <!--/ .stage-->
  <div class="stage <?= $stageDocumentacao;?> documentacao col-md-3 col-sm-3 col-xs-12">
    <div id="clicar_documentacao" style="cursor: pointer" class="stage-header">
      <i class="zmdi zmdi-format-list-bulleted zmdi-hc-fw topo-icons"></i>
    </div>
    <div class="stage-content">
      <h3 class="stage-title">DOCUMENTAÇÃO</h3>
      <div class="stage-info"></div>
    </div>
  </div>
  <!--/ .stage-->
</div>