<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<!-- Painel de Demandas -->
<div class="container conteudo">

    <div class="botoes-acao">
        <a href="#" title="Imprimir" class="imprimir"></a>
    </div>

    <table class="tabela agentes">
        <thead>
            <tr>
                <th>#<th>Agente | Responsável<th>Demandas Cadastradas<th>Demandas Concluídas<th>Demandas Ativas<th>Quantidade de Agendas<th>
        <tbody>
            <?php
            $cont = 1;
            $sql = $db->prepare("SELECT u.nome as usuario_nome"
                    . " FROM x_agente a, tb_bsc_usuario u WHERE IdUsuario = a.usuario_id");
            $sql->execute();
            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td data-th="#">
                        <?php
                        if ($cont < 10) {
                            echo "0" . $cont;
                        } else {
                            echo $cont;
                        }
                        ?>
                    <td data-th="Agente/Responsável"><?php echo $linha['usuario_nome']; ?>
                    <td data-th="Demandas Cadastradas">000
                    <td data-th="Demandas Concluídas">000 (00%)
                    <td data-th="Demandas Ativas">000 (00%)
                    <td data-th="Quantidade de Agendas">000
                    <td data-th="Link"><a href="#" title="Visuzalizar" class="visualizar">Visualizar</a>
                        <?php
                        $cont++;
                    }
                    ?>
<!--<tr>
    <td data-th="#">02
    <td data-th="Agente/Responsável">Fulano de Tal dos Anzóis
    <td data-th="Demandas Cadastradas">000
    <td data-th="Demandas Concluídas">000 (00%)
    <td data-th="Demandas Ativas">000 (00%)
    <td data-th="Quantidade de Agendas">000
    <td data-th="Link"><a href="#" title="Visuzalizar" class="visualizar">Visualizar</a>
<tr>
    <td data-th="#">03
    <td data-th="Agente/Responsável">Fulano de Tal dos Anzóis
    <td data-th="Demandas Cadastradas">000
    <td data-th="Demandas Concluídas">000 (00%)
    <td data-th="Demandas Ativas">000 (00%)
    <td data-th="Quantidade de Agendas">000
    <td data-th="Link"><a href="#" title="Visuzalizar" class="visualizar">Visualizar</a>
<tr>
    <td data-th="#">04
    <td data-th="Agente/Responsável">Fulano de Tal dos Anzóis
    <td data-th="Demandas Cadastradas">000
    <td data-th="Demandas Concluídas">000 (00%)
    <td data-th="Demandas Ativas">000 (00%)
    <td data-th="Quantidade de Agendas">000
    <td data-th="Link"><a href="#" title="Visuzalizar" class="visualizar">Visualizar</a>
<tr>
    <td data-th="#">05
    <td data-th="Agente/Responsável">Fulano de Tal dos Anzóis
    <td data-th="Demandas Cadastradas">000
    <td data-th="Demandas Concluídas">000 (00%)
    <td data-th="Demandas Ativas">000 (00%)
    <td data-th="Quantidade de Agendas">000
    <td data-th="Link"><a href="#" title="Visuzalizar" class="visualizar">Visualizar</a>-->
    </table>
</div>

<?php include("rodape.php") ?>



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

