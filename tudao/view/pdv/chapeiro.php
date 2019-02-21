<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo.php');
?>

<?php include('header_pdv.php'); ?>

<main>

    <div class="row">

        <?php include('menu_lateral.php'); ?>

        <div class="col-md-8">

        </div>

    </div>

</main>

<?php include('footer_pdv.php'); ?>

<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape_pdv.php' ); ?>