<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo.php');

$id = antiSQL(@$_GET['id']);

if (is_numeric($id)) {//VERIFICA SE O ID PASSADO POR PARÂMETRO É UM NÚMERO
    $sql = $db->prepare("SELECT id, valor, pontos, codigo
                FROM cupons
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
                <small>Cupons</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo PORTAL_URL; ?>view/geral/modulos.php"><i class="fa fa-dashboard"></i> <b>Módulos</b></a></li>
                <li><a href="<?php echo PORTAL_URL; ?>view/cupons/index.php"> <b>Cupons</b></a></li>
                <li class="active">Cadastro</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Formulário de Cupom</h3>
                </div><!-- /.box-header -->
                <form id="form_cupom" action="#" method="post">
                    <div class="box-body">
                        <input type="hidden" id="id" name="id" value="<?php echo $reg['id']; ?>" />
                        <div class="row">
                            <div class="col-md-4">
                                <div id="div_codigo" class="form-group has-feedback">
                                    <label for="codigo">Código</label>
                                    <input id="codigo" name="codigo" type="text" class="form-control" placeholder="Código do Cupom" value="<?php echo $reg['codigo']; ?>"/>
                                </div>  
                            </div>
                            <div class="col-md-4">
                                <div id="div_pontos" class="form-group has-feedback">
                                    <label for="pontos">Pontos</label>
                                    <input id="pontos" name="pontos" type="text" class="form-control" placeholder="Pontuação do Cupom" value="<?php echo $reg['pontos']; ?>" onkeypress="return SomenteNumero(event);"/>
                                </div>  
                            </div>
                            <div class="col-md-4">
                                <div id="div_valor" class="form-group has-feedback">
                                    <label for="valor">Valor</label>
                                    <input id="valor" name="valor" type="text" class="form-control" placeholder="Valor do Cupom" value="<?php echo fdec($reg['valor']); ?>"/>
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

<!-- JS DO REGISTER -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/cupons/cadastro.js"></script>

<script>
    $(function() {
        $("input#valor").priceFormat({
            prefix: '',
            centsSeparator: ',',
            thousandsSeparator: '.'
        });
    });
</script>