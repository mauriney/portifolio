<?php
include("topo.php");
include("menu-lateral.php");
?>

<div class="conteudo container">
    <div class="view configuracao">
        <h1>PAINEL DE CONFIGURAÇÕES</h1>
        <ul class="menu-configuracoes">
            <li class="col-md-3"><a href="mudar-senha.php" title="Mudar Senha" class="config mudar-senha">Mudar Senha</a></li>
            <?php
            if ($_SESSION['acesso'] == 1) {
                ?>
                <li class="col-md-3"><a href="recorrentes-painel.php" title="Agendas Recorrentes" class="config recorrentes">Agendas Recorrentes</a></li>
                <li class="col-md-3"><a href="conectados-sistema-painel.php" title="Conectados ao Sistema" class="config conectados">Conectados ao Sistema</a></li>
                <!--<li class="col-md-3"><a href="grupos-contatos-painel.php" title="Grupos de Contato" class="config grupos-contato">Grupos de Contato</a></li>-->
                <li class="col-md-3"><a href="segmento-painel.php" title="Segmentos" class="config segmento">Segmentos</a></li>
                <li class="col-md-3"><a href="usuarios-painel.php" title="Lista de Usuários" class="config lista-usuarios">Lista de Usuários</a></li>
                <li class="col-md-3"><a href="bairro-painel.php" title="Lista de Bairros" class="config lista-bairro">Lista de Bairros</a></li>
                <li class="col-md-3"><a href="veiculo-painel.php" title="Lista de Veículos" class="config lista-carro">Lista de Veículos</a></li>
                    <?php
                }
                ?>
        </ul>
    </div>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/configuracoes/configuracao-painel.js"></script>