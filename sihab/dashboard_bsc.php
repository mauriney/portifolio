<?php include('template/topo.php'); ?>
<?php include('template/sidebar.php'); ?>

<input type="hidden" id="nome_usuario" name="nome_usuario" value="<?= $_SESSION['nome']; ?>" rel="<?= $_SESSION['bemvindo']; ?>"/>

<?php $_SESSION['bemvindo'] = 0; ?>

<section id="content">
  <div class="container">
    <div class="card container-module">
      <div class="row">
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/empreendimento/lista" class="hoverzoom modulo2">
              <h1>EMPREENDIMENTO</h1>
              <img src="assets/img/home.svg">
              <div class="retina">
                <h2>EMPREENDIMENTO</h2>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/acao/lista" class="hoverzoom modulo2">
              <h1>AÇÃO</h1>
              <img src="assets/img/push-screen.svg">
              <div class="retina">
                <h2>AÇÃO</h2>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/objeto/lista" class="hoverzoom modulo2">
              <h1>OBJETO</h1>
              <img src="assets/img/archive.svg">
              <div class="retina">
                <h2>OBJETO</h2>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/modulo/lista" class="hoverzoom modulo2">
              <h1>MÓDULO</h1>
              <img src="assets/img/puzzle.svg">
              <div class="retina">
                <h2>MÓDULO</h2>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/grupo/lista" class="hoverzoom modulo2">
              <h1>GRUPO DE ACESSO</h1>
              <img src="assets/img/team.svg">
              <div class="retina">
                <h2>GRUPO DE ACESSO</h2>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/usuario/lista" class="hoverzoom modulo2">
              <h1>MÓDULO DE USUÁRIOS</h1>
              <img src="assets/img/users.svg">
              <div class="retina">
                <h2>MÓDULO DE USUÁRIOS</h2>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/origem_demanda/lista" class="hoverzoom modulo2">
              <h1>ORIGEM DA DEMANDA</h1>
              <img src="assets/img/office-material.svg">
              <div class="retina">
                <h2>ORIGEM DA DEMANDA</h2>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/especificidade/lista" class="hoverzoom modulo2">
              <h1>ESPECIFICIDADE</h1>
              <img src="assets/img/checklist.svg">
              <div class="retina">
                <h2>ESPECIFICIDADE</h2>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/loteamento/lista" class="hoverzoom modulo2">
              <h1>LOTEAMENTO</h1>
              <img src="assets/img/office.svg">
              <div class="retina">
                <h2>LOTEAMENTO</h2>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/casa/lista" class="hoverzoom modulo2">
              <h1>CASAS</h1>
              <img src="assets/img/home.svg">
              <div class="retina">
                <h2>CASAS</h2>
              </div>
            </a>
          </div>
        </div> 
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/casa/apto" class="hoverzoom modulo2">
              <h1>APTO</h1>
              <img src="assets/img/list.svg">
              <div class="retina">
                <h2>APTO</h2>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/sorteio/index" class="hoverzoom modulo2">
              <h1>SORTEIO</h1>
              <img src="assets/img/lottery.svg">
              <div class="retina">
                <h2>SORTEIO</h2>
              </div>
            </a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 col-sm-6">
          <div class="card module">
            <a href="<?= PORTAL_URL; ?>sistema/sorteio/resultado" class="hoverzoom modulo2">
              <h1>RESULTADO DO SORTEIO</h1>
              <img src="assets/img/lottery.svg">
              <div class="retina">
                <h2>RESULTADO DO SORTEIO</h2>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">

        </div>
        <div class="col-md-4 col-sm-6">

        </div>
      </div>
    </div>
  </div>
</section>

<?php include('template/rodape.php'); ?>

<!-- JS DO LOGIN -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/dashboard.js"></script>