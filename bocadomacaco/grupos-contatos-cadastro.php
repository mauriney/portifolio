<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
if (@is_numeric($_GET['id']) && @antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT * FROM tb_bsc_grupo_email WHERE IdGrupoEmail = ?");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_grupo = $result->fetch(PDO::FETCH_ASSOC);

    $grupo_id = $dados_grupo['IdGrupoEmail'];
    $grupo_nome = utf8_encode($dados_grupo['Nome']);
} else {
    $grupo_id = "";
    $grupo_nome = "";
}
?>

<!-- Painel de Demandas -->
<div class="container conteudo">
    <form id="form_grupos" action="#" method="post">
        <input type="hidden" id="id" name="id" value="<?php echo $grupo_id ?>"/>
        <div class="cadastro">
            <h1>Grupos de Contatos</h1>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nome">Nome do Grupo</label>
                        <div id="div_nome">
                            <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome do Grupo" value="<?php echo $grupo_nome; ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/configuracoes/grupos-contatos-cadastro.js"></script>

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>