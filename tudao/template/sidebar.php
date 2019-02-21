<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <!--<div class="user-panel">
            <div class="pull-left image">
                <img src="<?= ASSETS_FOLDER; ?>img/user2-160x160.jpg" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p><?php echo primeiro_nome($_SESSION['nome']); ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
       
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Pesquisar..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        -->
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">NAVEGAÇÃO</li>

            <?php
            if (ver_nivel(1, 1)) {
              ?>

              <li class="treeview">
                  <a href="<?= PORTAL_URL; ?>view/clientes/historico.php">
                      <i class="fa fa-dashboard"></i> <span>Acompanhamento</span>
                  </a>
              </li>

              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="<?= PORTAL_URL; ?>view/pdv/atendimento.php">
                      <i class="fa fa-dashboard"></i> <span>Atendimento</span>
                  </a>
              </li>
              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Bairros</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li class="active"><a href="<?= PORTAL_URL; ?>view/bairros/cadastro.php"><i class="fa fa-circle-o"></i> Novo</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/bairros/index.php"><i class="fa fa-circle-o"></i> Lista</a></li>
                  </ul>
              </li>
              <?php
            }
            ?>

            <li class="treeview">
                <a href="<?= PORTAL_URL; ?>view/cardapios/index.php">
                    <i class="fa fa-dashboard"></i> <span>Cardápio</span>
                </a>
            </li>

            <?php
            if (ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Categorias</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li class="active"><a href="<?= PORTAL_URL; ?>view/categorias/cadastro.php"><i class="fa fa-circle-o"></i> Novo</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/categorias/index.php"><i class="fa fa-circle-o"></i> Lista</a></li>
                  </ul>
              </li>
              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Clientes</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <?php
                      if (ver_nivel(1, 2)) {
                        ?>
                        <li class="active"><a href="<?= PORTAL_URL; ?>view/clientes/cadastro.php"><i class="fa fa-circle-o"></i> Novo</a></li>
                        <li><a href="<?= PORTAL_URL; ?>view/clientes/index.php"><i class="fa fa-circle-o"></i> Lista</a></li>
                        <?php
                      }
                      ?>
                  </ul>
              </li>

              <?php
            }
            ?>


            <?php
            if (ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Cupons</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li class="active"><a href="<?= PORTAL_URL; ?>view/cupons/cadastro.php"><i class="fa fa-circle-o"></i> Novo</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/cupons/index.php"><i class="fa fa-circle-o"></i> Lista</a></li>
                  </ul>
              </li>

              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 4) || ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Fornecedores</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li class="active"><a href="<?= PORTAL_URL; ?>view/fornecedores/cadastro.php"><i class="fa fa-circle-o"></i> Novo</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/fornecedores/index.php"><i class="fa fa-circle-o"></i> Lista</a></li>
                  </ul>
              </li>

              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 3) || ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Funcionários</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li class="active"><a href="<?= PORTAL_URL; ?>view/funcionarios/cadastro.php"><i class="fa fa-circle-o"></i> Novo</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/funcionarios/index.php"><i class="fa fa-circle-o"></i> Lista</a></li>
                  </ul>
              </li>

              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Ingredientes</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li class="active"><a href="<?= PORTAL_URL; ?>view/ingredientes/cadastro.php"><i class="fa fa-circle-o"></i> Novo</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/ingredientes/index.php"><i class="fa fa-circle-o"></i> Lista</a></li>
                  </ul>
              </li>
              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a target="_blank" href="<?= PORTAL_URL; ?>view/pdv/guiche.php">
                      <i class="fa fa-dashboard"></i> <span>Guichê</span>
                  </a>
              </li>
              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Mesas</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li class="active"><a href="<?= PORTAL_URL; ?>view/mesas/cadastro.php"><i class="fa fa-circle-o"></i> Novo</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/mesas/index.php"><i class="fa fa-circle-o"></i> Lista</a></li>
                  </ul>
              </li>
              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Pedidos</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li class="active"><a href="<?= PORTAL_URL; ?>view/pedidos/index.php"><i class="fa fa-circle-o"></i> Em Atendimento</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/pedidos/envio.php"><i class="fa fa-circle-o"></i> Pronto para Envio</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/pedidos/pagamentos.php"><i class="fa fa-circle-o"></i> Enviado para Entrega</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/pedidos/concluidos.php"><i class="fa fa-circle-o"></i> Concluídos</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/pedidos/cancelados.php"><i class="fa fa-circle-o"></i> Cancelados</a></li>
                  </ul>
              </li>
              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 1)) {
              ?>

              <li class="treeview">
                  <a href="<?= PORTAL_URL; ?>view/clientes/cadastro.php?id=<?php echo $_SESSION['id']; ?>">
                      <i class="fa fa-dashboard"></i> <span>Perfil</span>
                  </a>
              </li>

              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Permissões</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li class="active"><a href="<?= PORTAL_URL; ?>view/pessoas/index.php"><i class="fa fa-circle-o"></i> Lista</a></li>
                  </ul>
              </li>
              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 2)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Produtos</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li class="active"><a href="<?= PORTAL_URL; ?>view/produtos/cadastro.php"><i class="fa fa-circle-o"></i> Novo</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/produtos/index.php"><i class="fa fa-circle-o"></i> Lista</a></li>
                  </ul>
              </li>

              <?php
            }
            ?>

            <?php
            if (ver_nivel(1, 2) || ver_nivel(1, 5)) {
              ?>
              <li class="treeview">
                  <a href="#">
                      <i class="fa fa-dashboard"></i> <span>Usuários</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li class="active"><a href="<?= PORTAL_URL; ?>view/usuarios/cadastro.php"><i class="fa fa-circle-o"></i> Novo</a></li>
                      <li><a href="<?= PORTAL_URL; ?>view/usuarios/index.php"><i class="fa fa-circle-o"></i> Lista</a></li>
                  </ul>
              </li>
              <?php
            }
            ?>

            <li class="treeview">
                <a href="<?= PORTAL_URL; ?>logout.php">
                    <i class="fa fa-dashboard"></i> <span>Sair</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>