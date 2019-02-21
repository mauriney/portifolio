<?php include 'header.php'; ?>
  <section class="cover-action">
    <div class="container">
      <div class="change-cover">
        <a href="#"><i class="co-photo-camera-o"></i> <p>Alterar Capa</p></a>
      </div>
      <div class="photo-profile">
        <img src="http://pdi.sk/Pictures/Icons/CircleIcons/Circle%20Icons%20Pack%20add-on%202/512x512/wwe.png" alt="">
        <a href="#" class="change-photo"><i class="co-photo-camera-o"></i> Alterar Imagem</a>
      </div>
      <img src="http://covertimeline.com//app/template/587.jpg" alt="">
      <h1 class="desktop">Plataforma Crowdsourced</h1>
      <a href="#" class="desktop">Bons Corações</a>
    </div>
  </section>
  <section class="cover-action-mobile mobile">
    <div class="container">
      <h1>Plataforma Crowdsourced</h1>
      <a href="#">Bons Corações</a>
    </div>
  </section>
  <section class="data-action">
    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <iframe width="100%" height="400" src="https://www.youtube.com/embed/eofvvnJ6_RE" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
        <div class="col-md-4">
          <ul class="elements">
            <li>
              <div class="card">
                <div class="img">
                  <img src="assets/img/status/busca-captacao-0.png" alt="">
                </div>
                <div class="data">
                  <h3>Valor Investido</h3>
                  <span><small>R$</small> 0,00</span>
                </div>
              </div>
            </li>
            <li>
              <div class="card">
                <div class="img">
                  <img src="assets/img/status/projeto-icone-02-00.png" alt="">
                </div>
                <div class="data">
                  <h3>Meta de Investimento</h3>
                  <span>0%</span>
                </div>
              </div>
            </li>
            <li>
              <div class="card">
                <div class="img">
                  <img src="assets/img/status/projeto-icone-03-0.png" alt="">
                </div>
                <div class="data">
                  <h3>Corações Unidos</h3>
                  <span>0</span>
                </div>
              </div>
            </li>
            <li>
              <div class="card">
                <div class="img">
                  <img src="assets/img/status/projeto-icone-04-0.png" alt="">
                </div>
                <div class="data">
                  <h3>Você tem até</h3>
                  <span class="hour">00/00/2017 às 00h00</span>
                  <small>para investir nesta Ação</small>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <section class="info-action">
    <div class="container">
      <div class="tab" role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#pitch" aria-controls="pitch" role="tab" data-toggle="tab"><i class="co-hourglass"></i>Pitch</a></li>
          <li role="presentation"><a href="#brainstorm" aria-controls="brainstorm" role="tab" data-toggle="tab"><i class="co-care"></i>Brainstorm</a></li>
          <li role="presentation"><a href="#demonstration" aria-controls="demonstration" role="tab" data-toggle="tab"><i class="co-megaphone"></i>Demonstração</a></li>
          <li role="presentation"><a href="#network" aria-controls="network" role="tab" data-toggle="tab"><i class="co-network"></i>Network</a></li>
          <li role="presentation"><a href="#timeline" aria-controls="timeline" role="tab" data-toggle="tab"><i class="co-timeline"></i>Timeline</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane fade in active" id="pitch">
            <section class="action-pitch">
              <?php include 'action_pitch.php'; ?>
            </section>
          </div>

          <div role="tabpanel" class="tab-pane fade in" id="brainstorm">
            <section class="brainstorm">
              <?php include 'action_brainstorm.php'; ?>
            </section>
          </div>

          <div role="tabpanel" class="tab-pane fade in" id="demonstration">
            <section class="demonstration">
              <?php include 'demonstration.php'; ?>
            </section>
          </div>

          <div role="tabpanel" class="tab-pane fade in" id="network">
            <section class="action-network">
              <?php include 'action_network.php'; ?>
            </section>
          </div>

          <div role="tabpanel" class="tab-pane fade in" id="timeline">
            <?php include 'profile_timeline.php'; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php include 'invest.php'; ?>
<?php include 'chat.php'; ?>
<?php include 'footer.php'; ?>
