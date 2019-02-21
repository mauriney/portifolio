<?php
include("topo.php");
include("menu-lateral.php");
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


    <div class="relatorio">
        <h1>Lista de Agentes Para Emissão de Relatório</h1>
        <table class="tabela tb-usuarios">
            <thead>
                <tr>
                    <th style="text-align:left">Nome<th>
            <tbody id="pagina">
                <?php
                $sql_grupo = $db->prepare("SELECT * FROM tb_bsc_usuario WHERE demanda = 1 OR quemvai = 1");
                $sql_grupo->execute();
                while ($usuario = $sql_grupo->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td data-th="Nome"><?php echo utf8_encode($usuario['Nome']); ?>
                        <td data-th="" width="50"><a href="relatorio-desempenho-agentes.php?id=<?php echo $usuario['IdUsuario']; ?>" title="Visualizar Relatório" class="visualizar">Editar</a>
                            <?php
                        }
                        ?>
        </table>
    </div>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/configuracoes/usuarios-demanda.js"></script>

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

