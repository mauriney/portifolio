<?php
@session_start();
include_once ('conf/config.php');
include_once ('utils/funcoes.php');
include_once ('conf/Url.php');

$db = Conexao::getInstance();

$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0));
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

// NOME BAIRROS
$stmt = $db->prepare("SELECT pe.id, pe.bairro, pe.status 
                      FROM hab_candidato AS c 
                      LEFT JOIN hab_pessoa AS p ON p.id = c.hab_pessoa_id 
                      LEFT JOIN hab_pessoa_endereco AS pe ON pe.hab_pessoa_id = p.id 
                      WHERE 1 AND pe.bairro IS NOT NULL AND pe.bairro NOT LIKE '' 
                      GROUP BY UPPER(pe.bairro) 
                      ORDER BY UPPER(pe.bairro) ASC");
$stmt->execute();
$rsBairrosAcre = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<script>
  var bairrosAcre = <?= json_encode(array_column($rsBairrosAcre, 'bairro')); ?>;
</script>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CADASTRO .::. SECRETARIA DE ESTADO DE HABITAÇÃO - SEHAB</title>
    <!-- STYLE CSS -->
    <link href="<?= PORTAL_URL; ?>assets/fontes/stylesheet.css" rel="stylesheet">
    <!-- Vendor CSS -->
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/nouislider/distribute/jquery.nouislider.min.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <!-- CSS -->
    <link href="<?= PORTAL_URL; ?>assets/css/app.min.1.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/css/app.min.2.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/css/formulario.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/css/cores.css" rel="stylesheet">
    <!-- Notificação -->
    <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/location-sel.css" rel="stylesheet" type="text/css" media="screen"/>
  </head>
  <body>
    <div class="transparencia"></div>
    <div id="div_geral" class="content">
      <div class="container">
        <div class="row m-t-20">
          <div class="col-md-6">
	        <div class="logo-governo-top">
	        	<img src="<?= PORTAL_URL; ?>assets/img/brasao-governo.svg" class="logo" alt="">
	        	<div class="title">
	        		<h2 class="gov">Governo do Estado do Acre</h2>
					<h3 class="sec">Secretaria de Habitação</h3>
	        	</div>
	        </div>
<!--
            <div class="row">
              <div class="col-sm-3 col-xs-4">
                <img src="<?= PORTAL_URL; ?>assets/img/brasao-governo.svg" class="logo" alt="">
              </div>
              <div class="col-sm-9 col-xs-8">
                <h2 class="gov">Governo do Estado do Acre</h2>
                <h3 class="sec">Secretaria de Habitação</h3>
              </div>
            </div>
-->
          </div>
          <div class="col-md-6">
            <div class="chamamento">
              <p>Edital de Chamamento nº 01/2017</p>
              <p>Manifestação de Interesse</p>
              <p>Programa Habitacional do Servidor Público do Estado do Acre - PHSPAC</p>
            </div>
          </div>
        </div>

        <form action="#" id="form_servidor" name="form_servidor" method="post">
          <input type="hidden" id="id" name="id" value="<?= $param; ?>"/>
          <div class="card m-t-20">
            <div class="card-header color-block bgm-cyan">
              <h2>DADOS PESSOAIS</h2>
            </div>
            <div class="card-body card-padding">
              <div class="row m-t-30">
                <div class="col-md-8">
                  <div class="form-group fg-float">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="nome" id="nome">
                      <label class="fg-label">NOME COMPLETO</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group fg-float">
                    <div class="fg-line">
                      <input id="cpf" name="cpf" type="text" class="input-sm form-control fg-input" data-mask="000.000.000-00" value="">
                      <label class="fg-label">CPF</label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="identidade" id="identidade">
                      <label class="fg-label">DOC. DE IDENTIDADE</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-5">
                  <label class="f-500 c-black">ÓRGÃO EXPEDIDOR</label>
                  <select id="orgao_expedidor" name="orgao_expedidor" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA O ÓRGÃO EXPEDIDOR</option>
                    <?php
                    $result = $db->prepare("SELECT id, nome FROM bsc_orgao_expedidor WHERE status = 1 ORDER BY id ASC");
                    $result->execute();
                    while ($orgao = $result->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option value="<?= $orgao['id']; ?>"><?= $orgao['nome']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <label class="c-black f-500">DATA EXP.</label>
                  <div class="input-group form-group">
                      <!-- <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span> -->
                    <div class="dtp-container">
                      <input  id="data_expedicao" name="data_expedicao" type='text' class="form-control date-picker" placeholder="Clique...">
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <label class="c-black f-500">DATA NASC.</label>
                  <div class="input-group form-group">
                      <!-- <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span> -->
                    <div class="dtp-container">
                      <input id="data_nascimento" name="data_nascimento" type='text' class="form-control date-picker" placeholder="Clique...">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label class="f-500 c-black">ESTADO CIVIL</label>
                  <select id="estado_civil" name="estado_civil" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA O ESTADO CIVIL</option>
                    <?php
                    $result = $db->prepare("SELECT id, nome FROM bsc_estado_civil WHERE id NOT IN (5,6,7,8) AND status = 1 ORDER BY id ASC");
                    $result->execute();
                    while ($estado_civil = $result->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option value="<?= $estado_civil['id']; ?>"><?= $estado_civil['nome']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
                <div id="div_uniao_estavel" class="col-md-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="uniao_estavel" name="uniao_estavel" value="1">
                      <i class="input-helper"></i>
                      UNIÃO ESTÁVEL
                    </label>
                  </div>
                </div>
              </div>                

              <div class="row m-t-20">
                <div class="col-md-8">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="residencial" id="residencial">
                      <label class="fg-label">ENDEREÇO RESIDENCIAL</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="bairro" id="bairro">
                      <label class="fg-label">BAIRRO</label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-2">
                  <label class="f-500 c-black">ESTADO</label>
                  <select id="servidor_estado" name="servidor_estado" class="selectpicker" data-live-search="true">
                    <?php
                    $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                    $result->execute();
                    while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option label='<?= $estado['sigla']; ?>' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="f-500 c-black">CIDADE</label>
                  <select id="servidor_cidade" name="servidor_cidade" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA UM MUNICÍPIO</option>
                    <?php
                    $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio WHERE estado_id = 1 ORDER BY nome ASC");
                    $result2->execute();
                    while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option label='<?= $municipio['nome']; ?>' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="tel_residencial" id="tel_residencial" maxlength="14" data-mask="(00) 0000-0000">
                      <label class="fg-label">TEL. RESIDENCIAL</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="celular" id="celular" maxlength="14" data-mask="(00) 00000-0000">
                      <label class="fg-label">TEL. CELULAR</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="email" class="form-control fg-input" name="email" id="email">
                      <label class="fg-label">E-MAIL</label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <label class="f-500 c-black">NACIONALIDADE</label>
                </div>
              </div>

              <div class="row">
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="nacionalidade_brasileiro" name="nacionalidade" type="radio" value="1" checked="true">
                      <i class="input-helper"></i>
                      BRASILEIRO
                    </label>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="nacionalidade_estrangeiro" name="nacionalidade" type="radio" value="0">
                      <i class="input-helper"></i>
                      ESTRANGEIRO
                    </label>
                  </div>
                </div>
              </div>

              <div id="estrangeiro" class="row space-t-20" style="display: none">
                <div id="div_servidor_pais" class="col-md-3 item-form">
                  <label for="servidor_pais" class="">PAÍS</label>
                  <select id="servidor_pais" name="servidor_pais" placeholder="PAÍS" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA A NACIONALIDADE</option>
                    <?php
                    $result = $db->prepare("SELECT id, nome FROM bsc_nacionalidade WHERE id <> 35 ORDER BY nome ASC");
                    $result->execute();
                    while ($nacionalidade = $result->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option value='<?= $nacionalidade['id']; ?>'><?= ctexto($nacionalidade['nome'], "mai"); ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div id="div_naturalidade" class="row space-t-20">
                <div class="col-md-4 item-form">
                  <label class="f-500 c-black">NATURALIDADE</label>
                </div>
              </div>

              <div id="brasileiro" class="row">
                <div id="div_naturalidade_estado" class="col-md-4 item-form">
                  <label for="naturalidade_estado">ESTADO</label>
                  <select id="naturalidade_estado" name="naturalidade_estado" placeholder="estado" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA O ESTADO</option>
                    <?php
                    $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                    $result->execute();
                    while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option label='<?= $estado['sigla']; ?>' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
                <div id="div_naturalidade_municipio" class="col-md-4 item-form">
                  <label for="naturalidade_municipio">CIDADE</label>
                  <select id="naturalidade_municipio" name="naturalidade_municipio" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA UM MUNICÍPIO</option>
                    <?php
                    $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio WHERE estado_id = 1 ORDER BY nome ASC");
                    $result2->execute();
                    while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option label='<?= $municipio['nome']; ?>' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="row m-t-10">
                <div class="col-md-5">
                  <div class="form-group fg-float">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="pai" id="pai">
                      <label class="fg-label">NOME DO PAI</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group fg-float">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="mae" id="mae">
                      <label class="fg-label">NOME DA MÃE</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group fg-float">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="dependentes" id="dependentes" data-mask="###">
                      <label class="fg-label">Nº DE DEPENDENTES</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row m-t-25">
                <div class="col-md-3 item-form">
                  <label class="f-500 c-black">QUANTO ANOS RESIDE NESTA CIDADE</label>
                  <select id="ano_reside_cidade" name="ano_reside_cidade" placeholder="ANOS" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA O ANO</option>
                    <?php
                    for ($cont = 1; $cont <= 100; $cont++) {
                      ?>
                      <option value='<?= $cont; ?>'><?= $cont; ?> <?= $cont == 1 ? 'Ano' : 'Anos'; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>  
                <div class="col-md-3 item-form">
                  <label class="f-500 c-black">QUANTO MESES RESIDE NESTA CIDADE</label>
                  <select id="mes_reside_cidade" name="mes_reside_cidade" placeholder="MESES" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA O MÊS</option>
                    <?php
                    for ($cont = 1; $cont <= 12; $cont++) {
                      ?>
                      <option value='<?= $cont; ?>'><?= $cont; ?> <?= $cont == 1 ? 'Mês' : 'Meses'; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div> 
              </div>              
              <div class="row">
                <div class="col-md-4">
                  <label class="f-500 c-black">MORA COM PARENTES?</label>
                </div>
              </div>

              <div class="row">
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="mora_sim" name="mora_parentes" type="radio" value="1" checked="true">
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="mora_nao" name="mora_parentes" type="radio" value="0">
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label class="f-500 c-black">POSSUI CASA PRÓPRIA?</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="casa_propria_sim" name="casa_propria" type="radio" value="1" checked="true">
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="casa_propria_nao" name="casa_propria" type="radio" value="0">
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <label class="f-500 c-black">PAGA ALUGUEL?</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="aluguel_sim" name="aluguel" type="radio" value="1" checked="true">
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="aluguel_nao" name="aluguel" type="radio" value="0">
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>            
              <div id="div_aluguel" class="row">
                <div class="col-md-3">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="valor_aluguel" id="valor_aluguel">
                      <label class="fg-label">VALOR</label>
                    </div>
                  </div>
                </div>              
              </div>
            </div>
          </div>

          <div id="card_conjuge" class="card" style="display: none">
            <div class="card-header color-block bgm-cyan">
              <h2>DADOS CÔNJUGE</h2>
            </div>
            <div class="card-body card-padding">
              <div class="row m-t-30">
                <div class="col-md-8">

                  <div class="form-group fg-float">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="conjuge_nome" id="conjuge_nome">
                      <label class="fg-label">NOME COMPLETO</label>
                    </div>
                  </div>

                </div>                
                <div class="col-md-4">
                  <div class="form-group fg-float">
                    <div class="fg-line">
                      <input id="conjuge_cpf" name="conjuge_cpf" type="text" class="input-sm form-control fg-input" data-mask="000.000.000-00" value="">
                      <label class="fg-label">CPF</label>
                    </div>
                  </div>
                </div> 
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label class="c-black f-500">DATA NASC.</label>
                  <div class="input-group form-group">                     
                    <div class="dtp-container">
                      <input id="conjuge_data_nasc" name="conjuge_data_nasc" type='text' class="form-control date-picker" placeholder="Clique...">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="conjuge_rg" id="conjuge_rg">
                      <label class="fg-label">DOC. DE IDENTIDADE</label>
                    </div>
                  </div>
                </div> 
                <div class="col-md-3">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="conjuge_profissao" id="conjuge_profissao">
                      <label class="fg-label">PROFISSÃO</label>
                    </div>
                  </div>
                </div>                               
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label class="f-500 c-black">NACIONALIDADE</label>
                </div>
              </div>

              <div class="row">
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="conjuge_nacionalidade_brasileiro" name="conjuge_nacionalidade2" type="radio" value="1" checked="true">
                      <i class="input-helper"></i>
                      BRASILEIRO
                    </label>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="conjuge_nacionalidade_estrangeiro" name="conjuge_nacionalidade2" type="radio" value="0">
                      <i class="input-helper"></i>
                      ESTRANGEIRO
                    </label>
                  </div>
                </div>
              </div>

              <div id="conjuge_estrangeiro" class="row space-t-40" style="display: none">
                <div id="div_conjuge_servidor_pais" class="col-md-3 item-form">
                  <label for="conjuge_servidor_pais" class="">PAÍS</label>
                  <select id="conjuge_servidor_pais" name="conjuge_servidor_pais" placeholder="PAÍS" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA A NACIONALIDADE</option>
                    <?php
                    $result = $db->prepare("SELECT id, nome FROM bsc_nacionalidade WHERE id <> 35 ORDER BY nome ASC");
                    $result->execute();
                    while ($nacionalidade = $result->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option value='<?= $nacionalidade['id']; ?>'><?= ctexto($nacionalidade['nome'], "mai"); ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div id="div_conjuge_naturalidade" class="row space-t-40">
                <div class="col-md-4 item-form">
                  <label class="f-500 c-black">NATURALIDADE</label>
                </div>
              </div>

              <div id="conjuge_brasileiro" class="row">
                <div id="div_conjuge_naturalidade_estado" class="col-md-4 item-form">
                  <label for="conjuge_naturalidade_estado">ESTADO</label>
                  <select id="conjuge_naturalidade_estado" name="conjuge_naturalidade_estado" placeholder="estado" class="selectpicker" data-live-search="true">
                    <?php
                    $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                    $result->execute();
                    while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option label='<?= $estado['sigla']; ?>' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
                <div id="div_conjuge_naturalidade_municipio" class="col-md-4 item-form">
                  <label for="conjuge_naturalidade_municipio">CIDADE</label>
                  <select id="conjuge_naturalidade_municipio" name="conjuge_naturalidade_municipio" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA UM MUNICÍPIO</option>
                    <?php
                    $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio WHERE estado_id = 1 ORDER BY nome ASC");
                    $result2->execute();
                    while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option label='<?= $municipio['nome']; ?>' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
              </div>

            </div>
          </div><!-- FIM DADOS CÔNJUGE-->
          <div class="card">
            <div class="card-header color-block bgm-cyan">
              <h2>DADOS FUNCIONAIS</h2>
            </div>
            <div class="card-body card-padding">
              <div class="row m-t-30">  
                <div id="div_funcional_entidade" class="col-md-6 item-form">
                  <label for="funcional_entidade">ENTIDADE PÚBLICA</label>
                  <select id="funcional_entidade" name="funcional_entidade" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA UMA ENTIDADE</option>
                    <?php
                    $result2 = $db->prepare("SELECT id, nome, sigla FROM bsc_unidade_organizacional WHERE status = 1 ORDER BY nome ASC");
                    $result2->execute();
                    while ($unidade = $result2->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option value='<?= $unidade['id']; ?>'><?= $unidade['nome']; ?> - <?= $unidade['sigla']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <div class="form-group fg-float">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="lotacao" id="lotacao">
                      <label class="fg-label">LOTAÇÃO</label>
                    </div>
                  </div>                  
                </div>                 
              </div>
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="funcional_endereco" id="funcional_endereco">
                      <label class="fg-label">ENDEREÇO (RUA, N, COMPLEMENTO, ETC.)</label>
                    </div>
                  </div>
                </div>              
                <div class="col-md-4">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="funcional_bairro" id="funcional_bairro">
                      <label class="fg-label">BAIRRO</label>
                    </div>
                  </div>
                </div>

              </div>
              <div class="row">
                <div class="col-md-4">
                  <label class="f-500 c-black">ESTADO</label>
                  <select id="funcional_estado" name="funcional_estado" class="selectpicker" data-live-search="true">
                    <?php
                    $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                    $result->execute();
                    while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option label='<?= $estado['sigla']; ?>' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="f-500 c-black">CIDADE</label>
                  <select id="funcional_cidade" name="funcional_cidade" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA UM MUNICÍPIO</option>
                    <?php
                    $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio WHERE estado_id = 1 ORDER BY nome ASC");
                    $result2->execute();
                    while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option label='<?= $municipio['nome']; ?>' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="row m-t-20">
                <div class="col-md-5">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="funcional_cargo" id="funcional_cargo">
                      <label class="fg-label">CARGO</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="funcional_matricula" id="funcional_matricula">
                      <label class="fg-label">MÁTRICULA</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="funcional_telefone_comercial" id="funcional_telefone_comercial" maxlength="14" data-mask="(00) 0000-0000">
                      <label class="fg-label">TEL. COMERCIAL</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group fg-float m-t-20">
                    <div class="fg-line">
                      <input type="text" class="form-control fg-input" name="funcional_ramal" id="funcional_ramal" data-mask="#####">
                      <label class="fg-label">RAMAL</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <label class="c-black f-500">DATA ADMISSÃO</label>
                  <div class="input-group form-group">
                      <!-- <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span> -->
                    <div class="dtp-container">
                      <input id="funcional_data_admissao" name="funcional_data_admissao" type='text' class="form-control date-picker" placeholder="Clique...">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">                  
                  <label class="f-500 c-black">VINCULAÇÃO</label>
                  <select id="funcional_vinculacao" name="funcional_vinculacao" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA UMA VINCULAÇÃO</option>
                    <?php
                    $result2 = $db->prepare("SELECT id, nome_vinculacao FROM svd_vinculacao WHERE status = 1 ORDER BY nome_vinculacao ASC");
                    $result2->execute();
                    while ($vinculacao = $result2->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option value='<?= $vinculacao['id']; ?>'><?= $vinculacao['nome_vinculacao']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-5">
                  <label class="f-500 c-black">REMUNERAÇÃO</label>
                  <select id="funcional_renumeracao" name="funcional_renumeracao" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA A REMUNERAÇÃO</option>
                    <?php
                    $result = $db->prepare("SELECT id, tipo FROM svd_remuneracao WHERE status = 1 ORDER BY id ASC");
                    $result->execute();
                    while ($renumeracao = $result->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option value='<?= $renumeracao['id']; ?>'><?= $renumeracao['tipo']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
          </div><!-- FIM DADOS FUNCIONAIS-->


          <div class="card">
            <div class="card-header color-block bgm-cyan">
              <h2>DADOS DE IMÓVEIS</h2>
            </div>
            <div class="card-body card-padding">
              <div class="row m-t-25">
                <div class="col-md-4">
                  <label class="f-500 c-black">POSSUI IMÓVEL URBANO EM SEU NOME?</label>
                </div>
              </div>

              <div class="row">
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="imovel_sim" name="imovel" type="radio" value="1" checked="true">
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="imovel_nao" name="imovel" type="radio" value="0">
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 m-t-20">
                  <label class="f-500 c-black">POSSUI IMÓVEL URBANO EM NOME DO CÔNJUGE?</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="conjuge_imovel_sim" name="conjuge_imovel" type="radio" value="1" checked="true">
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="conjuge_imovel_nao" name="conjuge_imovel" type="radio" value="0">
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 m-t-20">
                  <label class="f-500 c-black">JÁ FOI BENEFICIADO EM OUTRO PROGRAMA HABITACIONAL?</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="benficiado_imovel_sim" name="benficiado_imovel" type="radio" value="1" checked="true">
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="radio m-b-15">
                    <label>
                      <input id="benficiado_imovel_nao" name="benficiado_imovel" type="radio" value="0">
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- FIM DADOS DE IMÓVEIS-->  
          <div class="card">
            <div class="card-header color-block bgm-cyan">
              <h2>TIPOLOGIA DO IMÓVEL DE INTERESSE</h2>
            </div>
            <div class="card-body card-padding">
              <div class="row m-t-25">
                <div class="col-md-5">
                  <label class="f-500 c-black">INTERESSE PELO TIPO DE EDIFICAÇÃO:</label>
                  <select id="tipo_edificacao" name="tipo_edificacao" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA O TIPO DE EDIFICAÇÃO</option>
                    <?php
                    $result = $db->prepare("SELECT id, interesse_tipo FROM svd_tipo_edificacao WHERE status = 1 ORDER BY interesse_tipo ASC");
                    $result->execute();
                    while ($interesse_tipo = $result->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <option value='<?= $interesse_tipo['id']; ?>'><?= $interesse_tipo['interesse_tipo']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
          </div><!-- FIM TIMPOLOGIA DO IMÓVEL DE INTERESSE -->

          <div class="card">
            <div class="card-header color-block bgm-cyan">
              <h2>DECLARAÇÃO</h2>
            </div>
            <div class="card-body card-padding">
              <div class="row m-t-25">
                <div class="col-md-12">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="declaracao" name="declaracao" value="1">
                      <i class="input-helper"></i>
                      DECLARO SOB AS PENAS DA LEI, SEREM VERDADEIRAS TODAS AS INFORMAÇÕES ACIMA PRESTADAS.
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" id="salvar" name="salvar" class="btn btn-success" style="display: none">
            Salvar
          </button>
        </form>
        <br><br><br>

        <div class="footer">
          <div class="logo-governo"></div>
          <div class="logo-sihab"></div>
        </div>
      </div>
    </div>

    <!-- Javascript Libraries -->
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/Waves/dist/waves.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/moment/min/moment.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/salvattore/dist/salvattore.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/nouislider/distribute/jquery.nouislider.all.js"></script>

    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/input-mask/input-mask.min.js"></script>

    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/flot/jquery.flot.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/flot/jquery.flot.resize.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/flot.curvedlines/curvedLines.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/sparklines/jquery.sparkline.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/js/flot-charts/curved-line-chart.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/js/flot-charts/line-chart.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/js/jquery.form.js"></script>

    <script src="<?= PORTAL_URL; ?>assets/plugins/form-wizard/js/tmm_form_wizard_custom.js"></script>

    <script type="text/javascript" src="<?= JS_FOLDER ?>livequery.js"></script>

    <!-- JS UTIL -->
    <script src="<?= PORTAL_URL ?>utils/utils.js" type="text/javascript"></script>
    <script src="<?= PORTAL_URL ?>utils/projeto.utils.js" type="text/javascript"></script>

    <!-- JAVASCRIPT PARA PESQUISA DINÂMICA -->
    <script src="<?= JS_FOLDER ?>pesquisa_dinamica.js"></script>

    <!-- Placeholder for IE9 -->
    <!--[if IE 9 ]>
    <script src="vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
    <![endif]-->

    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/chosen/chosen.jquery.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/fileinput/fileinput.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/input-mask/input-mask.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/farbtastic/farbtastic.min.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>

    <script src="<?= PORTAL_URL; ?>assets/js/functions.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/js/actions.js"></script>

    <!-- Notificação -->
    <script src="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/js/messenger.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/js/demo/location-sel.js"></script>
    <script type="text/javascript" src="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/js/demo/theme-sel.js"></script>
    <script src="<?= PORTAL_URL; ?>hab/js/servidor/formulario.js"></script>

  </body>
</html>