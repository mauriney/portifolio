<?php include 'template/top.php' ?>
<?php include 'template/menu_topo.php' ?>

<link rel="stylesheet" href="docs/style.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<!-- Início do Corpo do Site -->
<div class="content">
    <div class="container">

        <div id="app">
            <div class="sidebar">
                <div class="card">
                    <section class="cameras">
                        <h2>Selecione a Câmeras</h2>
                        <ul>
                            <li v-if="cameras.length === 0" class="empty">Nenhuma câmera encontrada</li>
                            <li v-for="camera in cameras">
                                <span v-if="camera.id == activeCameraId" :title="formatName(camera.name)" class="active">{{ formatName(camera.name) }}</span>
                                <span v-if="camera.id != activeCameraId" :title="formatName(camera.name)">
                                    <a @click.stop="selectCamera(camera)">{{ formatName(camera.name) }}</a>
                                </span>
                            </li>
                        </ul>
                    </section>
                    <div class="preview-container">
                        <video id="preview"></video>
                    </div>
                </div>

                <section id='enviar_section' class="scans">
                    <form id="formulario" name="formulario" action="#" method="POST"> 
                        <div class="card card-nav-tabs">
                            <h4 class="card-title text-center">Boletim de Urna</h4>
                            <div class="card-body">
                                <section id='enviar_section' class="scans">
                                    <!--<transition-group name="scans" tag="ul">
                                        <li v-for="scan in scans" :key="scan.date" :title="scan.content">{{scan.content}}</li>
                                    </transition-group>-->
                                    <input type='hidden' id='yourInputFieldId' name='yourInputFieldId' />
                                </section>
                                <div class="progress">
									<div id="barra_progesso" class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="info-bu">
									<div class="row">
										<div class="col-md-4"><span>Zona Eleitoral <strong id="zona">0000</strong></span></div>
										<div class="col-md-4"><span>Local de Votação <strong id="local">0000</strong></span></div>
										<div class="col-md-4"><span>Seção Eleitoral <strong id="secao">0000</strong></span></div>
									</div>
									<hr>
									<div class="row">
										<div class="col-md-4"><span>Eleitores Aptos <strong id="aptos">0000</strong></span></div>
										<div class="col-md-4"><span>Comparecimentos <strong id="comparecimentos">0000</strong></span></div>
										<div class="col-md-4"><span>Eleitores Faltosos <strong id="faltosos">0000</strong></span></div>
									</div>
								</div>
                                <h3 class="text-center">GOVERNADOR</h3>
								<form action="">
									<div class="candidates">
										<div class="image"><img src="assets/img/marcus.jpg" alt=""></div>
										<div class="name">Marcus Alexandre <strong class="treze">13</strong></div>
										<div id="gov_13" class="wishes">0</div>
									</div>
									<div class="candidates">
										<div class="image"><img src="assets/img/ullysses.jpg" alt=""></div>
										<div class="name">Coronel Ullysses <strong>17</strong></div>
										<div id="gov_17" class="wishes">0</div>
									</div>
									<div class="candidates">
										<div class="image"><img src="assets/img/janaina.jpg" alt=""></div>
										<div class="name">Janaína Furtado <strong>18</strong></div>
										<div id="gov_18" class="wishes">0</div>
									</div>
									<div class="candidates">
										<div class="image"><img src="assets/img/gladson.jpg" alt=""></div>
										<div class="name">Gladson Cameli <strong>11</strong></div>
										<div id="gov_11" class="wishes">0</div>
									</div>
									<div class="candidates">
										<div class="image"><img src="assets/img/david.jpg" alt=""></div>
										<div class="name">David Hall <strong>70</strong></div>
										<div id="gov_70" class="wishes">0</div>
									</div>
									<hr>
									<div class="info-bu">
										<div class="row">
											<div class="col-md-6"><span>Brancos <strong id="brancos">0000</strong></span></div>
											<div class="col-md-6"><span>Nulos <strong id="nulos">0000</strong></span></div>
										</div>
									</div>
								</form>
                            </div>
                        </div>
                        <div class="text-center">
                            <input type="hidden" id="hash" name="hash" value=""/>
                        	<button id="botao_confirmar" type="submit" disabled="disabled" class="btn btn-default btn-lg"><i class="fas fa-check"></i> CONFIRMAR</button>
                        </div>
                    </form>
                </section>
            </div>
        </div> 
    </div>
</div>
<!-- Fim do Corpo do Site -->

<?php include 'template/footer.php' ?>

<script type="text/javascript" src="docs/app.js"></script>
