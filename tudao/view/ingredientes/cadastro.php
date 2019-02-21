<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo.php');

$id = antiSQL(@$_GET['id']);

if (is_numeric($id)) {//VERIFICA SE O ID PASSADO POR PARÂMETRO É UM NÚMERO
    $sql = $db->prepare("SELECT id, nome, add_valor, add_pontos
                FROM ingredientes
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
                <small>Ingredientes</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo PORTAL_URL; ?>view/geral/modulos.php"><i class="fa fa-dashboard"></i> <b>Módulos</b></a></li>
                <li><a href="<?php echo PORTAL_URL; ?>view/ingredientes/index.php"> <b>Ingredientes</b></a></li>
                <li class="active">Cadastro</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="box box-danger">
                <form id="form_ingrediente" action="#" method="post">
                    <div class="box-header">
                        <h3 class="box-title">Novo Ingrediente</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <input type="hidden" id="codigo" name="codigo" value="<?php echo $reg['id']; ?>" />
                        <div class="row">
                            <div class="col-md-12">
                                <div id="div_nome" class="form-group has-feedback">
                                    <label for="nome">NOME</label>
                                    <input id="nome" name="nome" type="text" class="form-control" placeholder="Nome completo" value="<?php echo $reg['nome']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div id="div_valor" class="form-group has-feedback">
                                    <label for="valor">VALOR EM R$</label>
                                    <input id="valor" name="valor" type="text" class="form-control" placeholder="Valor em R$" value="<?php echo fdec($reg['add_valor']); ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="div_valor_pontos" class="form-group has-feedback">
                                    <label for="valor_pontos">VALOR EM PONTOS</label>
                                    <input id="valor_pontos" name="valor_pontos" type="text" class="form-control" placeholder="Valor em Pontos" value="<?php echo $reg['add_pontos']; ?>"/>
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

<!-- MÁSCARA PARA DINHEIRO -->
<script type="text/javascript" src="<?= ASSETS_FOLDER; ?>js/jquery.price_format.1.3.js"></script>

<script>
    $(function() {
        $("input#valor").priceFormat({
            prefix: '',
            centsSeparator: ',',
            thousandsSeparator: '.'
        });
    });
</script>

<!-- JS DO REGISTER -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/ingredientes/cadastro.js"></script>