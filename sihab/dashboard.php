<?php include('template/topo.php'); ?>
<?php include('template/sidebar.php'); ?>

<input type="hidden" id="nome_usuario" name="nome_usuario" value="<?= $_SESSION['nome']; ?>" rel="<?= $_SESSION['bemvindo']; ?>"/>

<?php
$_SESSION['bemvindo'] = 0;
$_SESSION['retroativo'] = 0;
?>

<section id="content">
  <div class="container">
    <div class="card container-module">
      <div class="row">
        <?php
        if (vf_modulo_objeto_acao_pagina("hab", "candidato_cadastro", "visualizar")) {
          ?>
          <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="card module">
              <a href="<?= PORTAL_URL; ?>sistema/candidato/etapa1" class="hoverzoom modulo1">
                <h1>NOVO CANDIDATO</h1>
                <img src="assets/img/user.svg">
                <div class="retina">
                  <h2>NOVO CANDIDATO</h2>
                </div>
              </a>
            </div>
          </div>
          <?php
        }
        if (vf_modulo_objeto_acao_pagina("hab", "candidato_lista", "visualizar")) {
          ?>

          <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="card module">
              <a href="<?= PORTAL_URL; ?>sistema/candidato/lista" class="hoverzoom modulo6">
                <h1>LISTA DE CANDIDATOS</h1>
                <img src="assets/img/lista-usuario.svg">
                <div class="retina">
                  <h2>LISTA DE CANDIDATOS</h2>
                </div>
              </a>
            </div>
          </div>
          <?php
        }
        if (vf_modulo_objeto_acao_pagina("hab", "candidato_lista_morto", "visualizar")) {
          ?>
          <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="card module">
              <a href="<?= PORTAL_URL; ?>sistema/candidato/lista_morto" class="hoverzoom modulo7">
                <h1>ARQUIVO MORTO</h1>
                <img src="assets/img/arquivo-morto.svg">
                <div class="retina">
                  <h2>ARQUIVO MORTO</h2>
                </div>
              </a>
            </div>
          </div>
          <?php
        }
        if (vf_modulo_objeto_acao_pagina("hab", "empreendimento_lista", "visualizar") ||
            vf_modulo_objeto_acao_pagina("hab", "acao_lista", "visualizar") ||
            vf_modulo_objeto_acao_pagina("hab", "objeto_lista", "visualizar") ||
            vf_modulo_objeto_acao_pagina("hab", "modulo_lista", "visualizar") ||
            vf_modulo_objeto_acao_pagina("hab", "grupo_lista", "visualizar") ||
            vf_modulo_objeto_acao_pagina("hab", "usuario_lista", "visualizar") ||
            vf_modulo_objeto_acao_pagina("hab", "programa_lista", "visualizar") ||
            vf_modulo_objeto_acao_pagina("hab", "subprograma_lista", "visualizar")) {
          ?>
          <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="card module">
              <a href="<?= PORTAL_URL; ?>dashboard_bsc" class="hoverzoom modulo2">
                <h1>CENTRAL DE CONTROLE</h1>
                <img src="assets/img/settings.svg">
                <div class="retina">
                  <h2>CENTRAL DE CONTROLE</h2>
                </div>
              </a>
            </div>
          </div>
          <?php
        }
        ?>
      </div>

      <div class="row">
        <div class="col-md-4 col-sm-6">

        </div>
        <div class="col-md-4 col-sm-6">

        </div>
        <div class="col-md-4 col-sm-6">

        </div>
      </div>
    </div>
</section>

<?php include('template/rodape.php'); ?>

<!-- JS DO LOGIN -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/dashboard.js"></script>