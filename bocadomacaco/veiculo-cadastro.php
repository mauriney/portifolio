<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
if (@is_numeric($_GET['id']) && @antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT modelo, placa, cor
				FROM x_veiculo
				WHERE id = ?");
    $result->bindValue(1, $id);
    $result->execute();
    $dados = $result->fetch(PDO::FETCH_ASSOC);

    $veiculo_id = $dados['id'];
    $modelo = $dados['modelo'];
    $placa = $dados['placa'];
    $cor = $dados['cor'];
} else {
    $veiculo_id = "";
    $modelo = "";
    $placa = "";
    $cor = "";
}
?>

<div class="container conteudo">
    <form id="form_veiculo" action="#" method="post">
        <input type="hidden" id="id" name="id" value="<?php echo $id ?>"/>
        <!-- linha -->
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="placa">Placa</label>
                    <div id="div_placa">
                        <input type="text" name="placa" id="placa" class="form-control" value="<?php echo utf8_encode($placa); ?>"/>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="modelo">Modelo</label>
                    <div id="div_modelo">
                        <input type="text" name="modelo" id="modelo" class="form-control" value="<?php echo utf8_encode($modelo); ?>"/>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="cor">Cor</label>
                    <div id="div_cor">
                        <input type="text" name="cor" id="cor" class="form-control" value="<?php echo utf8_encode($cor); ?>"/>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Salvar</button>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/carro/veiculo-cadastro.js"></script>
<script>
    $(function () {
        $('.scroll').perfectScrollbar();
        // with vanilla JS!
        Ps.initialize(document.getElementById('scroll'));
    });
</script>