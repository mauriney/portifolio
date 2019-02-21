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
                Módulos
                <small>Geral</small>
            </h1>
            <ol class="breadcrumb">
                <li class="active">Módulos</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">


            <!-- Application buttons -->
            <div class="box box-danger">
                <div class="box-header"></div>
                <div class="box-body">

                    <b>Pontuação Atual:</b>&nbsp;&nbsp;<?php echo pesquisar_tabela("pontos", "clientes", "pessoa_id", "=", $_SESSION['id'], ""); ?>P

                    <nav class="modules">

                        <a href="<?php echo PORTAL_URL ?>view/cardapios/index.php" class="cardapio">
                            <img src="../../assets/img/novos/menu.svg" alt="" />
                            <p>CARDÁPIO</p>
                        </a>

                        <?php
                        if (ver_nivel(1, 2) || ver_nivel(1, 5)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/usuarios/index.php" class="">
                              <img src="../../assets/img/novos/user.svg" alt="" />
                              <p>USUÁRIOS</p>
                          </a>
                          <?php
                        }
                        ?>

                        <?php
                        if (ver_nivel(1, 2)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/clientes/index.php" class="">
                              <img src="../../assets/img/novos/clients.svg" alt="" />
                              <p>CLIENTES</p>
                          </a>
                          <?php
                        }
                        ?>

                        <?php
                        if (ver_nivel(1, 2)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/categorias/index.php" class="">
                              <img src="../../assets/img/novos/category.svg" alt="" />
                              <p>CATEGORIAS</p>
                          </a>
                          <?php
                        }
                        ?>

                        <?php
                        if (ver_nivel(1, 2)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/produtos/index.php" class="">
                              <img src="../../assets/img/novos/burger.svg" alt="" />
                              <p>PRODUTOS</p>
                          </a>
                          <?php
                        }
                        ?>

                        <?php
                        if (ver_nivel(1, 2)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/ingredientes/index.php" class="">
                              <img src="../../assets/img/novos/cheese.svg" alt="" />
                              <p>INGREDIENTES</p>
                          </a>
                          <?php
                        }
                        ?>

                        <?php
                        if (ver_nivel(1, 2) || ver_nivel(1, 4)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/fornecedores/index.php" class="">
                              <img src="../../assets/img/novos/delivery-truck.svg" alt="" />
                              <p>FORNECEDORES</p>
                          </a>
                          <?php
                        }
                        ?>

                        <?php
                        if (ver_nivel(1, 2) || ver_nivel(1, 3)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/funcionarios/index.php" class="">
                              <img src="../../assets/img/novos/employees.svg" alt="" />
                              <p>FUNCIONÁRIOS</p>
                          </a>
                          <?php
                        }
                        ?>

                        <?php
                        if (ver_nivel(1, 2)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/mesas/index.php" class="">
                              <img src="../../assets/img/novos/table.svg" alt="" />
                              <p>MESAS</p>
                          </a>
                          <?php
                        }
                        ?>

                        <?php
                        if (ver_nivel(1, 2)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/cupons/index.php" class="">
                              <img src="../../assets/img/novos/voucher.svg" alt="" />
                              <p>CUPONS</p>
                          </a>
                          <?php
                        }
                        ?>

                        <?php
                        if (ver_nivel(1, 2)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/pedidos/index.php" class="">
                              <img src="../../assets/img/novos/contact.svg" alt="" />
                              <p>PEDIDOS</p>
                          </a>
                          <?php
                        }
                        ?>

                        <?php
                        if (ver_nivel(1, 2)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/bairros/index.php" class="">
                              <img src="../../assets/img/novos/street.svg" alt="" />
                              <p>BAIRROS</p>
                          </a>
                          <?php
                        }
                        ?>

                        <?php
                        if (ver_nivel(1, 1)) {
                          ?>
                          <a href="<?php echo PORTAL_URL ?>view/clientes/historico.php" class="">
                              <img src="../../assets/img/novos/list.svg" alt="" />
                              <p>ACOMPANHAMENTO</p>
                          </a>
                          <?php
                        }
                        ?>

                    </nav>
                </div><!-- /.box-body -->
            </div><!-- /.box -->

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class='control-sidebar-bg'></div>
</div><!-- ./wrapper -->

<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape.php'); ?>