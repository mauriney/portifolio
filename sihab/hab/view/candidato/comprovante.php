<?php
@session_start();
include_once ('conf/config.php');
include_once ('assets/plugins/phpmailer/class.smtp.php');
include_once ('assets/plugins/phpmailer/class.phpmailer.php');
include_once ('utils/funcoes.php');
include_once ('conf/Url.php');

$db = Conexao::getInstance();

$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0));
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

$result = $db->prepare("SELECT hc.senha_visualiza, hc.validacao, snch.nome AS snch_apf, hc.snch_apf_id, hp.trab_mesmo_endereco, hc.area_risco_insalubre, hpe.latitude, hpe.longitude, hc.data_cadastro, hc.data_cadastro_anterior, hc.acompanhamento_socio_assistencial, hc.morador_rua, hpe.coabitacao_involuntaria, hpe.aluguel_valor, hpe.aluguel_social, hpe.alugada, blog.nome AS bsc_logradouro, bet.nome AS bsc_endereco_tipo, bdt.nome AS bsc_deficiencia_tipo, hp.casamento_data,
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
                          WHERE hc.id = ?");
$result->bindValue(1, $param);
$result->execute();

$dados_candidato = $result->fetch(PDO::FETCH_ASSOC);
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>SISHAB :: SISTEMA DE HABITAÇÃO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="theme-color" content="#0DCCC0">
    <meta name="msapplication-navbutton-color" content="#0DCCC0">
    <meta name="apple-mobile-web-app-status-bar-style" content="#0DCCC0">
    <!-- STYLE CSS -->
    <link href="<?= PORTAL_URL; ?>assets/fontes/stylesheet.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= PORTAL_URL; ?>assets/css/print.css" media="print" />
    <link rel="stylesheet" type="text/css" href="<?= PORTAL_URL; ?>assets/css/print.css" />
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
    <link href="<?= PORTAL_URL; ?>assets/css/style.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/css/cores.css" rel="stylesheet">
  </head>

  <section id="content" class="receipt">
    <div class="c-header">
      <div class="card icons-demo">
        <div class="card-header cw-header palette-Teal-600 bg">
          <div class="cwh-year">Candidato</div>
          <div class="cwh-day">Comprovante</div>
        </div>
        <div class="card-body card-padding-sm">
          <div id="cw-body">
            <div class="card-body card-padding">
              <div class="conteudodatabela">
                <table cellspacing="0">
                  <thead>
                    <tr>
                      <td>
                        <div class="top">
                          <ul>
                            <li>
                              <div class="image">
                                <img src="<?= PORTAL_URL; ?>assets/img/brasao-governo.svg" border="0" />
                              </div>
                            </li>
                            <li>
                              <div class="row">
                                <div class="col-md-12">
                                  <p class="primeiro">Governo do Estado do Acre</p>
                                </div>
                                <div class="col-md-12">
                                  <p>Secretaria de Estado de Habitação de Interesse Social – SEHAB</p>
                                </div>
                                <div class="col-md-12">
                                  <p>Sistema de Habitação</p>
                                </div>
                              </div>
                            </li>
                          </ul>
                        </div>
                      </td>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <td>
                        <div class="foot">
                          <div class="row">
                            <div class="col-md-9 col-xs-8">
                              <div class="row">
                                <div class="col-md-12">
                                  <p>Secretaria de Estado de Habitação de Interesse Social – SEHAB</p>
                                </div>
                                <div class="col-md-12">
                                  <p>Avenida das Acácias, Zona A, Lote 01, Distrito Industrial</p>
                                </div>
                                <div class="col-md-12">
                                  <p>CEP 69917-100 – Rio Branco – ACRE</p>
                                </div>
                                <div class="col-md-12">
                                  <p>Telefone +55 68 0000-0000</p>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-3 col-xs-4">
                              <div class="logo-governo">
                                <img src="<?= PORTAL_URL; ?>assets/img/logo-verde-governo.png" alt="" border="0" width="150px" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tfoot>
                  <tbody>
                    <tr>
                      <td>
                          <div class="text">
                            <h1 class="text-center">COMPROVANTE DE INSCRIÇÃO</h1>
                            <p>Esta inscrição faz parte do Cadastro de Habitação Estadual, criado pelo Decreto nº 8.771, de 16 de dezembro de 2014, e regulamentado pela Portaria/SEHAB nº 41, de 30 de dezembro de 2014.</p>
                            <p>A inscrição foi realizada de acordo com as determinações da Lei que institui o Sistema Estadual de Cadastro Habitacional, em sintonia com os princípios e regras do Programa Minha Casa Minha Vida do Governo Federal, e da Portaria nº 163, de 6 de maio de 2016, do Ministério das Cidades.</p>
                            <p>De acordo com o princípio da transparência da Administração, as informações desta inscrição são públicas, exceto aquelas que dizem respeito à intimidade, vida privada, honra e imagem pessoal, bem como às liberdades e garantias individuais, de acordo com as determinações da Lei Federal nº 12.527, de 18 de novembro de 2011, regulamentada pelo Decreto n° 7.724, de 16 de maio de 2012.</p>
                            <p>O candidato que omitir informações ou as prestar de forma inverídica, sem prejuízo de outras sanções, será excluído, a qualquer tempo, do processo de seleção do empreendimento, podendo concorrer a outro processo de seleção somente após 2 (dois) anos da ocorrência.</p>
                          </div>
                          <div class="data-candidate">
                            <h2><?= $dados_candidato['nome']; ?></h2>
                            <h3>CPF <?= $dados_candidato['cpf']; ?></h3>
                            <h4>SENHA DE ACESSO</h4>
                            <span class="password"><?= $dados_candidato['senha_visualiza']; ?></span>
                            <h5>PARA VISUALIZAR A SUA SITUAÇÃO CADASTRAL ACESSE:</h5>
                            <p>http://sehab.ac.gov.br/cidadao</p>
                          </div>

                          <div class="date-today">Rio Branco – AC, <?= dataExtenso(date("d/m/Y")) ?>.</div>

                          <div class="holder">
                            <span></span>
                            <small>Titular</small>
                          </div>

                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <!--/ #content-->
          </div>
        </div>
      </div>
    </div>
  </section>

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
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/nouislider/distribute/jquery.nouislider.all.min.js"></script>

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

  <script src="<?= PORTAL_URL; ?>hab/js/charts.js"></script>

  <script src="<?= PORTAL_URL; ?>assets/js/functions.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/js/actions.js"></script>

  <script type="text/javascript">
    window.print();
  </script>

</body>
</html>