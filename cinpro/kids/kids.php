<?php include '../top.php' ?>
<!-- content -->
<div class="content">

    <nav class="menu-tabs">
        <a class="pg1 active" id="pg1" href="#">ESPAÇO KIDS</a>
        <a class="pg2" id="pg2" href="#">EQUIPE</a>
        <a class="pg3" id="pg3" href="#">DIFERENCIAIS</a>
        <a class="pg4" id="pg4" href="#">ATENDIMENTOS</a>
    </nav>

    <div class="aba" id="tab1"><?php include('espaco-kids.php'); ?></div>
    <div style="display: none" class="aba" id="tab2"><?php include('equipe.php'); ?></div>
    <div style="display: none" class="aba" id="tab3"><?php include('diferenciais.php'); ?></div>
    <div style="display: none" class="aba" id="tab4"><?php include('atendimentos.php'); ?></div>

</div>   
<!-- fim content -->
<?php include '../footer.php' ?>
<script type="text/javascript" src="<?= PORTAL_URL; ?>assets/js/kids.js"></script>