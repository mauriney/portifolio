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
                Lista
                <small>Pedidos</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo PORTAL_URL; ?>view/geral/modulos.php"><i class="fa fa-dashboard"></i> <b>Módulos</b></a></li>
                <li class="active">Pedido</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Pedidos Concluídos</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Pagamento</th>
                                <th>Troco</th>
                                <th>Forma</th>
                                <th>Solicitação</th>
                                <th>Atualização</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = $db->prepare("SELECT fp.id AS forma_pagamento_id, p.vlr_total_pontos, cp.nome AS cliente, p.cadastro, p.atualizacao, p.id, p.valor_pagamento, p.valor_troco, fp.nome AS forma_pagamento, p.status
                             FROM pedidos p
                             LEFT JOIN clientes AS c ON c.id = p.cliente_id
                             LEFT JOIN pessoas AS cp ON cp.id = c.pessoa_id
                             LEFT JOIN forma_pagamentos AS fp ON fp.id = p.forma_pagamento
                             WHERE p.status = 4
                             ORDER BY p.id ASC");
                            $sql->execute();
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>
                              <tr>
                                  <td><?php echo $linha['id']; ?></td>
                                  <td><?php echo $linha['cliente']; ?></td>
                                  <td><?php echo $linha['forma_pagamento_id'] == 3 ? $linha['vlr_total_pontos'] . " pontos" : "R$ " . fdec($linha['valor_pagamento']); ?></td>
                                  <td><?php echo $linha['forma_pagamento_id'] == 3 ? "0" : "R$ " . fdec($linha['valor_troco']); ?></td>
                                  <td><?php echo $linha['forma_pagamento']; ?></td>
                                  <td><?php echo obterDataBRTimestamp($linha['cadastro']) . " às " . obterHoraTimestamp($linha['cadastro']); ?></td>
                                  <td><?php echo obterDataBRTimestamp($linha['atualizacao']) . " às " . obterHoraTimestamp($linha['atualizacao']); ?></td>
                                  <td align="center">
                                      <a title="Desativar Pedido" href="#" class="label label-success" onclick="remover(<?php echo $linha['id'] ?>)">
                                          <i class="fa fa-unlock"></i>
                                      </a>
                                      &nbsp;
                                      <a href="visualizar_finalizados.php?id=<?php echo $linha['id']; ?>" class="label label-warning">  <i class="fa fa-search text-black"></i></a>
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/pedidos/concluidos.js"></script>

<!-- DATA TABES SCRIPT -->
<script src="<?= PLUGINS_FOLDER; ?>datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?= PLUGINS_FOLDER; ?>datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>

<script type="text/javascript">
                                          $(function () {
                                              $("#example1").dataTable();
                                          });
</script>