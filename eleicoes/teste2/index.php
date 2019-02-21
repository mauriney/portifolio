<?php include 'template/top.php' ?>
<?php include 'template/menu_topo.php' ?>

<link rel="stylesheet" href="docs/style.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<!-- Início do Corpo do Site -->
<div class="content">
    <div class="container">
        <div class="card">
            <div id="app">
                <div class="sidebar">
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
                </div>
                <div class="preview-container">
                    <video id="preview"></video>
                </div>
            </div>
        </div>

        <section id='enviar_section' class="scans">
            <form id="formulario" name="formulario" action="http://acreideias.com.br/eleicoes/index2.php" method="POST"> 
                <div class="card card-nav-tabs">
                    <h4 class="card-header card-header-info text-center">Boletim de Urna</h4>
                    <div class="card-body">
                        <section id='enviar_section' class="scans">
                            <input type='hidden' id='yourInputFieldId' name='yourInputFieldId' />
                        </section>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-check"></i> CONFIRMAR</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
<!-- Fim do Corpo do Site -->

<?php include 'template/footer.php' ?>

<script type="text/javascript" src="docs/app.js"></script>
