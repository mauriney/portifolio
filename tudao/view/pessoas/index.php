<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo.php');
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
                <small>Pessoa</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo PORTAL_URL; ?>view/geral/modulos.php"><i class="fa fa-dashboard"></i> <b>Módulos</b></a></li>
                <li class="active">Pessoas</li>
                <li class="active">Lista</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Lista de Pessoas</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Contato</th>
                                <th>Status</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = $db->prepare("SELECT p.id, p.nome, p.contato1, p.email, p.status 
                             FROM pessoas p
                             ORDER BY p.nome ASC");
                            $sql->execute();
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td><?php echo $linha['id']; ?></td>
                                    <td><?php echo $linha['nome']; ?></td>
                                    <td><?php echo $linha['email']; ?></td>
                                    <td><?php echo $linha['contato1']; ?></td>
                                    <td align="center">
                                        <?php
                                        if ($linha['id'] != 1) {
                                            if ($linha['status'] == 1) {
                                                ?>
                                                <a title="Desativar Pessoa" href="#" class="label label-success" onclick="remover(<?php echo $linha['id'] ?>)">
                                                    <i class="fa fa-unlock"></i>
                                                </a>
                                                <?php
                                            } else if ($linha['status'] == 0) {
                                                ?>
                                                <a title="Ativar Pessoa" href="#" class="label label-danger" onclick="ativar(<?php echo $linha['id'] ?>)">
                                                    <i class="fa fa-lock"></i>
                                                </a>
                                                <?php
                                            } else if ($linha['status'] == 2) {
                                                ?>
                                                <a title="Confirmar Pessoa" href="#" class="label label-warning" onclick="pendencia(<?php echo $linha['id'] ?>)">
                                                    <i class="fa fa-exclamation-circle text-black"></i>
                                                </a>
                                                <?php
                                            }
                                        } else {
                                            echo "Administrador";
                                        }
                                        ?>
                                    </td>
                                    <td align="center">
                                        <a href="cadastro.php?id=<?php echo $linha['id']; ?>" class="label label-warning">
                                            <i class="fa fa-pencil text-black"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->

        </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class='control-sidebar-bg'></div>
</div><!-- ./wrapper -->

<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape.php'); ?>

<!-- JS DA LISTA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/pessoas/index.js"></script>

<!-- DATA TABES SCRIPT -->
<script src="<?= PLUGINS_FOLDER; ?>datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?= PLUGINS_FOLDER; ?>datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>

<script type="text/javascript">
                                        $(function() {
                                            $("#example1").dataTable();
                                        });
</script>