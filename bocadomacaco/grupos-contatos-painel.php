<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
$contador = @$_POST["carre"];
if ($contador == "") {
    $contador = 1;
}
if (isset($_POST['carre'])) {
    $contador+=1;
    echo "<script>document.getElementById('voltar_pagina').setAttribute('onclick', 'window.history.go(-$contador)');</script>";
}
?>

<!-- Painel de Demandas -->
<div class="container conteudo">

    <?php
    //CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
    if (isset($_POST['mensagem']) && @$_SESSION['mensagem'] == "OK") {
        @$_SESSION['mensagem'] = "NO";
        echo '<div id="div_success" class="alert alert-success" style="display: none;"><button class="close" data-dismiss="alert"></button><b>Sucesso:</b> ' . $_POST['mensagem'] . '</div>';
    }
    ?>

    <form id="formulario_imprimir" action="imprimir.php" method="post" target="_blank">
        <div class="botoes-acao">
            <a id="" href="grupos-contatos-cadastro.php" title="Adicionar" class="adicionar"></a>
            <a id="mostrar-busca" href="#" title="Filtrar" class="filtrar"></a>
        </div>
        <br/>
        <div id="filtro" class="filtro" style="display: none">
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nome">Nome do Grupo</label>
                        <input onkeyup="pesquisa()" type="text" name="nome" id="nome" class="form-control" placeholder="Nome do Grupo" />
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="cadastro">
        <h1>Grupos de Contatos</h1>
        <table class="tabela tb-grupo">
            <thead>
                <tr>
                    <th>#<th>Nome<th>
            <tbody id="pagina">
                <?php
                $vf_vinculacao = true;
                $cont = 1;
                $sql_grupo = $db->prepare("SELECT * FROM tb_bsc_grupo_email");
                $sql_grupo->execute();
                while ($f_grupo = $sql_grupo->fetch(PDO::FETCH_ASSOC)) {

                    if (is_numeric(pesquisar("idcontato", "tb_bsc_contato_grupo", "idgrupo", "=", $f_grupo['IdGrupoEmail'], ""))) {
                        $vf_vinculacao = false;
                    }

                    if ($cont < 10) {
                        $cont = "0" . $cont;
                    }
                    ?>
                    <tr>
                        <td data-th="#"><?php echo $cont; ?>
                        <td data-th="Nome"><?php echo utf8_encode($f_grupo['Nome']); ?>
                        <td data-th="">
                            <ul class="links">
                                <li><a href="grupos-contatos-cadastro.php?id=<?php echo $f_grupo['IdGrupoEmail']; ?>" title="Editar" class="editar-lista">Editar</a></li>
                                <?php
                                if ($vf_vinculacao) {
                                    ?>
                                    <li><a href="#" title="Excluir" class="excluir-lista" onclick="remover(<?php echo $f_grupo['IdGrupoEmail']; ?>, <?php echo $contador; ?>)">Excluir</a></li>
                                        <?php
                                    }
                                    ?>
                            </ul>
                            <?php
                            $vf_vinculacao = true;
                            $cont++;
                        }
                        ?>
        </table>
    </div>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/configuracoes/grupos-contatos-painel.js"></script>

<script>

                                var headertext = [];
                                var headers = document.querySelectorAll(".tabela th"),
                                        tablerows = document.querySelectorAll(".tabela th"),
                                        tablebody = document.querySelector(".tabela tbody");
                                for (var i = 0; i < headers.length; i++) {
                                    var current = headers[i];
                                    headertext.push(current.textContent.replace(/\r?\n|\r/, ""));
                                }
                                for (var i = 0, row; row = tablebody.rows[i]; i++) {
                                    for (var j = 0, col; col = row.cells[j]; j++) {
                                        col.setAttribute("data-th", headertext[j]);
                                    }
                                }
</script>

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>

