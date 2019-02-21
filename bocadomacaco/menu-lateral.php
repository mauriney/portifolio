<?php
@session_start();
include_once('utils/funcoes.php');
$dia = @dataExtenso(date('d/m/Y'));
?>
<nav class="menu">
    <ul>
        <li><a href="agenda-dia.php" class="menu1"> <i class="icone"><b><?php echo @$dia; ?></b></i> <span>AGENDAS DO DIA</span> </a></li>
        <li><a href="agendacompleta-painel.php" class="menu2"> <i class="icone"></i> <span>AGENDA COMPLETA</span> </a></li>
        <li><a href="preagenda-painel.php" class="menu3"> <i class="icone"></i> <span>PRÉ-AGENDA</span> </a></li>
        <?php
        if($_SESSION['demanda'] == 1){
        ?>
        <li><a href="demanda-painel.php" class="menu4"> <i class="icone"></i> <span>PAINEL DE DEMANDA</span> </a></li>
        <?php
        }
        ?>
        <li><a href="contato-painel.php" class="menu5"> <i class="icone"></i> <span>CONTATO</span> </a></li>
        <li><a href="aniversario-painel.php" class="menu6"> <i class="icone"></i> <span>ANIVERSARIANTE</span> </a></li>
        <li><a href="carro-painel.php" class="menu9"> <i class="icone"></i> <span>CARROS</span> </a></li>
        <li><a href="relatorio-painel.php" class="menu7"> <i class="icone"></i> <span>RELATÓRIOS</span> </a></li>
        <li><a href="configuracoes.php" class="menu8"> <i class="icone"></i> <span>CONFIGURAÇÕES</span> </a></li>
    </ul>
</nav>