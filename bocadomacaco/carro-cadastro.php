<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
if (@is_numeric($_GET['id']) && @antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT * 
             FROM x_veiculo_saida 
             WHERE id = ?");
    $result->bindValue(1, $id);
    $result->execute();
    $dados = $result->fetch(PDO::FETCH_ASSOC);

    $saida_id = $dados['id'];
    $motorista_id = $dados['motorista_id'];
    $veiculo_id = $dados['veiculo_id'];
    $data_saida = $dados['data_saida'];
    $hora_saida = $dados['hora_saida'];
    $data_prevista = $dados['data_prevista'];
    $hora_prevista = $dados['hora_prevista'];
    $obs = $dados['obs'];
} else {
    $saida_id = "";
    $motorista_id = "";
    $veiculo_id = "";
    $data_saida = obterDataBR();
    $hora_saida = obterHora();
    $data_prevista = "";
    $hora_prevista = "";
    $obs = "";
}
?>

<link rel="stylesheet" type="text/css" href="plugins/relogio/dist/bootstrap-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="plugins/relogio/assets/css/github.min.css">

<div class="container conteudo">
    <form id="form_saida" action="#" method="post">
        <input type="hidden" id="id" name="id" value="<?php echo $id ?>"/>
        <!-- linha -->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="veiculo_id">Veículo</label>
                    <div id="div_veiculo">
                        <select name="veiculo_id" id="veiculo_id" class="ls-select" placeholder="Veículo">
                            <option value="">Escolha o veículo</option>
                            <?php
                            $result = $db->prepare("SELECT id, placa, modelo
                             FROM x_veiculo
                             WHERE id NOT IN (SELECT veiculo_id FROM x_veiculo_saida WHERE status = 1 AND veiculo_id <> ?)
                             ORDER BY modelo ASC");
                            $result->bindValue(1, $veiculo_id);
                            $result->execute();
                            while ($vei = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($veiculo_id == $vei['id']) {
                                    ?>
                                    <option selected="true" value='<?php echo $vei['id']; ?>'><?php echo utf8_encode($vei['modelo']) . " - " . $vei['placa']; ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value='<?php echo $vei['id']; ?>'><?php echo utf8_encode($vei['modelo']) . " - " . $vei['placa']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="motorista_id">Motorista</label>
                    <div id="div_motorista">
                        <select name="motorista_id" id="motorista_id" class="ls-select" placeholder="Motorista">
                            <option value="">Escolha o motorista</option>
                            <?php
                            $result = $db->prepare("SELECT IdUsuario, Nome  
                             FROM tb_bsc_usuario
                             WHERE IdUsuario NOT IN (SELECT motorista_id FROM x_veiculo_saida WHERE status = 1 AND motorista_id <> ?)
                             ORDER BY Nome ASC");
                            $result->bindValue(1, $motorista_id);
                            $result->execute();
                            while ($mot = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($motorista_id == $mot['IdUsuario']) {
                                    ?>
                                    <option selected="true" value='<?php echo $mot['IdUsuario']; ?>'><?php echo utf8_encode($mot['Nome']); ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value='<?php echo $mot['IdUsuario']; ?>'><?php echo utf8_encode($mot['Nome']); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- linha -->
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="data_saida">Data de Saída</label>
                    <div id="div_data_saida">
                        <input type="text" name="data_saida" id="data_saida" class="form-control" value="<?php echo obterDataBRTimestamp($data_saida); ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="horas">Hora de Saída</label>
                    <div id="div_horas" class="input-group clockpicker">
                        <input type="text" name="horas" id="horas" class="form-control hora" value="<?php echo $hora_saida; ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="data_saida">Data Prevista de Chegada</label>
                    <div id="div_data_prevista">
                        <input type="text" name="data_prevista" id="data_prevista" class="form-control" value="<?php echo obterDataBRTimestamp($data_prevista); ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="horap">Hora Prevista de Chegada</label>
                    <div id="div_horap" class="input-group clockpicker">
                        <input type="text" name="horap" id="horap" class="form-control hora" value="<?php echo $hora_prevista; ?>"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="obs">Observação</label>
                    <div id="div_obs">
                        <textarea name="obs" id="obs" class="form-control" placeholder="Observação"><?php echo utf8_encode($obs); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Salvar</button>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/carro/carro-cadastro.js"></script>

<script type="text/javascript">
    $('.clockpicker').clockpicker();
</script>

<script type="text/javascript" src="plugins/relogio/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="plugins/relogio/dist/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">
$('.clockpicker').clockpicker()
.find('input#hora').change(function () {
console.log(this.value);
});
var input = $('#single-input').clockpicker({
placement: 'bottom',
align: 'left',
autoclose: true,
'default': 'now'
});

// Manually toggle to the minutes view
$('#check-minutes').click(function (e) {
// Have to stop propagation here
e.stopPropagation();
input.clockpicker('show')
    .clockpicker('toggleView', 'minutes');
});
if (/mobile/i.test(navigator.userAgent)) {
$('input#hora').prop('readOnly', true);
}
</script>

<script>
    $(function () {
        $('.scroll').perfectScrollbar();
        // with vanilla JS!
        Ps.initialize(document.getElementById('scroll'));
    });
</script>