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

    <div class="cadastro">
        <h1>Usuários Conectados ao Sistema</h1>
        <table class="tabela tb-recorrente">
            <thead>
                <tr>
                    <th>#<th>Login<th>IP<th>Navegador<th>Sistema Opercional<th>
            <tbody>
                <?php
                $cont = 1;
                $sql = $db->prepare("SELECT l.idusuario, l.sistema, l.navegador, l.ipusuario, u.login"
                        . " FROM info_login l, tb_bsc_usuario u "
                        . "WHERE l.idusuario = u.IdUsuario");
                $sql->execute();
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

                    if ($cont < 10) {
                        $cont = "0" . $cont;
                    }
                    ?>

                    <tr>
                        <td data-th="#"><?php echo $cont; ?>
                        <td data-th="Login"><?php echo $linha['login']; ?>
                        <td data-th="IP"><?php echo $linha['ipusuario']; ?>
                        <td data-th="Navegador"><span class="container-lista"><?php echo $linha['navegador']; ?></span>
                        <td data-th="Sistema Opercional"><?php echo $linha['sistema']; ?>
                        <td data-th=""><a href="#" title="Desconectar" class="desconectar" onclick="desconectar(<?php echo $linha['idusuario']; ?>, <?php echo $contador; ?>)">Desconectar</a>
                            <?php
                            $cont++;
                        }
                        ?>
        </table>
    </div>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/configuracoes/conectados-sistema.js"></script>

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

