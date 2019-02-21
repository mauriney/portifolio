<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
if (@is_numeric($_GET['id']) && @antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT *"
            . " FROM tb_bsc_bairro b"
            . " WHERE idbairro = ?");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_bairro = $result->fetch(PDO::FETCH_ASSOC);

    $bairro_id = $dados_bairro['idbairro'];
    $bairro_nome = utf8_encode($dados_bairro['nome']);
    $bairro_regional = $dados_bairro['regional'];
} else {
    $bairro_id = "";
    $bairro_nome = "";
    $bairro_regional = "";
}
?>

<!-- Painel de Demandas -->
<div class="container conteudo">
    <form id="form_bairro" action="#" method="post">
        <input type="hidden" id="id" name="id" value="<?php echo $bairro_id ?>"/>
        <div class="cadastro">
            <h1>Cadastro de Bairro</h1>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <div id="div_nome">
                            <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome" value="<?php echo $bairro_nome; ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="regional">Regional</label>
                        <div id="div_regional">
                            <select name="regional" id="regional" class="form-control">
                                <option value="">Selecione a Regional</option>
                                <?php
                                $sql2 = $db->prepare("SELECT * FROM tb_bsc_regional");
                                $sql2->execute();
                                while ($regiao = $sql2->fetch(PDO::FETCH_ASSOC)) {
                                    if ($bairro_regional == $regiao['idregional']) {
                                        ?>
                                        <option selected="true" value="<?php echo $regiao['idregional']; ?>"><?php echo utf8_encode($regiao['nome']); ?></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="<?php echo $regiao['idregional']; ?>"><?php echo utf8_encode($regiao['nome']); ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/configuracoes/bairro-cadastro.js"></script>

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>