<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo.php');

$id = antiSQL(@$_GET['id']);

if (is_numeric($id)) {//VERIFICA SE O ID PASSADO POR PARÂMETRO É UM NÚMERO
    $sql = $db->prepare("SELECT id, nome
                FROM categorias
                WHERE id = ?");
    $sql->bindParam(1, $id);
    $sql->execute();
    $reg = $sql->fetch(PDO::FETCH_BOTH);
} else {
    $reg = 0;
    $id = "";
}
?>
<div class="wrapper">
    <?php include($_SESSION['NOME_SISTEMA'] . 'template/header.php'); ?>
    <?php include($_SESSION['NOME_SISTEMA'] . 'template/sidebar.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Módulo
                <small>Categorias</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo PORTAL_URL; ?>view/geral/modulos.php"><i class="fa fa-dashboard"></i> <b>Módulos</b></a></li>
                <li><a href="<?php echo PORTAL_URL; ?>view/categorias/index.php"> <b>Categorias</b></a></li>
                <li class="active">Cadastro</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Formulário de Categoria</h3>
                </div><!-- /.box-header -->
                <form id="form_categoria" action="#" method="post">
                    <div class="box-body">
                        <input type="hidden" id="codigo" name="codigo" value="<?php echo $reg['id']; ?>" />
                        <div class="row">
                            <div class="col-md-12">
                                <div id="div_nome" class="form-group has-feedback">
                                    <label for="nome">NOME</label>
                                    <input id="nome" name="nome" type="text" class="form-control" placeholder="Nome completo" value="<?php echo $reg['nome']; ?>"/>
                                </div>  
                            </div>
                        </div>     
                    </div><!-- /.box-body -->
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-success"><?php echo is_numeric($id) ? 'Atualizar' : 'Cadastrar' ?></button>
                    </div>
                </form>     
            </div><!-- /.box -->

        </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class='control-sidebar-bg'></div>
</div><!-- ./wrapper -->

<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape.php'); ?>

<!-- JS DO REGISTER -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/categorias/cadastro.js"></script>