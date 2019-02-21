<?php
include("topo.php");
include_once('utils/preagenda/funcoes.php');
include("menu-lateral.php");
?>

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
    <div class="botoes-acao">
        <a href="preagenda-cadastro.php" title="Adicionar" class="adicionar"></a>
        <a id="mostrar-busca" href="#" title="Filtrar" class="filtrar"></a>
        <a title="Imprimir" class="imprimir" style="cursor: pointer" onclick="document.getElementById('formulario_imprimir').submit();
                return false;"></a>
    </div>
    <br><br>
    <div id="filtro" class="filtro" style="display: none">
        <form action="" method="post">
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="segmento">Nome</label>
                        <input onkeyup="pesquisa()" type="text" name="nome" id="nome" class="form-control" />
                    </div>
                </div>
            </div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="segmento">Segmento</label>
                        <select onchange="pesquisa()" name="segmento" id="segmento" class="ls-select" placeholder="Segmento">
                            <option value="">Escolha o segmento</option>
                            <?php
                            $result = $db->prepare("SELECT IdSegmento, Descricao 
	                             FROM tb_bsc_segmento
	                             ORDER BY Descricao ASC");
                            $result->execute();
                            while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option label='<?php echo $estado['Descricao']; ?>' value='<?php echo $estado['IdSegmento']; ?>'><?php echo utf8_encode($estado['Descricao']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <form id="formulario_imprimir" action="imprimir-preagenda-painel.php" method="post" target="_blank">
        <?php
        $sql = $db->prepare("SELECT a.*, u.Nome As responsavel_nome, a.IdUsuario AS responsavel, p.Descricao as prioridade, s.Descricao as segmento
                FROM tb_bsc_preagenda a 
 		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade
 		LEFT JOIN tb_bsc_segmento s ON s.IdSegmento = a.idsegmento
                LEFT JOIN tb_bsc_usuario u ON u.IdUsuario  = a.IdUsuario
 		WHERE IdAgenda = 0 ORDER BY prazo ASC, IdPreAgenda DESC");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            ?>

            <table class="tabela">
                <thead>
                    <tr>
                        <th>Prazo<th>Nome<th>Assunto<th>Celular<th>E-mail<th>Segmento<th>Prioridade<th>Registrado<th>
                <tbody id="pagina">

                    <?php
                    $codigo = "SELECT a.*, u.Nome As responsavel_nome, a.IdUsuario AS responsavel, p.Descricao as prioridade, s.Descricao as segmento
                FROM tb_bsc_preagenda a 
 		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade
 		LEFT JOIN tb_bsc_segmento s ON s.IdSegmento = a.idsegmento
                LEFT JOIN tb_bsc_usuario u ON u.IdUsuario  = a.IdUsuario
 		WHERE IdAgenda = 0 ORDER BY prazo ASC, IdPreAgenda DESC";
                    ?>
                <input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>"/>

                <?php
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

                    if ($_SESSION['acesso'] == 1 || $linha['responsavel'] == $_SESSION['id']) {//MOSTRA SÓ AS AGENDAS DO RESPONSÁVEL OU MOSTRA TODAS PARA O ADMINISTRADOR
                        $cores = "";
                        if ($linha['atencao'] == 1) {
                            $cores = 'style="background-color: #FFFFD0"';
                        }
                        ?>

                        <tr>
                            <td <?php echo $cores; ?> data-th="Prazo"><span class="container-lista"><?php echo data_volta($linha['prazo']) ?></span>
                            <td <?php echo $cores; ?> data-th="Nome"><?php echo $linha['Nome'] ?>
                            <td <?php echo $cores; ?> data-th="Assunto"><?php echo $linha['Assunto'] ?>
                            <td <?php echo $cores; ?> data-th="Celular"><span class="container-lista"><?php echo $linha['TelefoneCel'] ?></span>
                            <td <?php echo $cores; ?> data-th="E-mail"><span class="container-lista"><?php echo $linha['Email'] ?></span>
                            <td <?php echo $cores; ?> data-th="Segmento"><?php echo utf8_encode($linha['segmento']) ?>
                            <td <?php echo $cores; ?> data-th="Prioridade"><span class="prioridade-lista <?php echo prioridade_cor($linha['IdPrioridade']); ?>-lista"><?php echo prioridade($linha['IdPrioridade']); ?></span>
                            <td <?php echo $cores; ?> data-th="Registrado"><span class="container-lista"><?php echo data_volta_hora($linha['data_cadastro']) . "<br/>" . utf8_encode($linha['responsavel_nome']); ?></span>
                            <td <?php echo $cores; ?> data-th="Link">
                                <ul class="links">
                                    <li><a href="marcar-agenda-cadastro.php?id=<?php echo $linha['IdPreAgenda']; ?>" title="Agendar" class="add-lista">Adicionar</a></li>
                                    <li><a href="preagenda-cadastro.php?id=<?php echo $linha['IdPreAgenda']; ?>" title="Editar" class="editar-lista">Editar</a></li>
                                    <?php
                                    if ($_SESSION['acesso'] == 1 || $linha['responsavel'] == $_SESSION['id']) {//ADMINISTRADOR OU O USUÁRIO QUE CADASTROU
                                        ?>
                                        <li><a href="#" title="Excluir" class="excluir-lista" onclick="remover(<?php echo $linha['IdPreAgenda']; ?>, <?php echo $contador; ?>)">Excluir</a></li>
                                            <?php
                                        }
                                        ?>
                                </ul>
                                <?php
                            }
                        }
                        ?>
            </table>

            <?php
        } else {
            echo "<div style='diplay:block; clear: both; width: 100%; text-align: center'>Nenhum resultado encontrado</div>";
        }
        ?>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/preagenda/preagenda-painel.js"></script>

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

