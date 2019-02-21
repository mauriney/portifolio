<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
if (@is_numeric($_GET['id']) && @antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT * FROM tb_bsc_segmento WHERE IdSegmento = ?");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_segmento = $result->fetch(PDO::FETCH_ASSOC);

    $segmento_id = $dados_segmento['IdSegmento'];
    $segmento_nome = utf8_encode($dados_segmento['Descricao']);
} else {
    $segmento_id = "";
    $segmento_nome = "";
}
?>

<!-- Painel de Demandas -->
<div class="container conteudo">
    <form id="form_segmento" action="#" method="post">
        <input type="hidden" id="id" name="id" value="<?php echo $segmento_id ?>"/>
        <div class="cadastro">
            <h1>Cadastro de Segmento</h1>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <div id="div_nome">
                            <input onkeyup="pesquisa()" type="text" name="nome" id="nome" class="form-control" placeholder="Nome" value="<?php echo $segmento_nome; ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/configuracoes/segmento-cadastro.js"></script>

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>