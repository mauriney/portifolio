<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo PORTAL_URL; ?>view/geral/modulos.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="assets/img/logo.svg" width="50px" alt=""></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b><?php echo ctexto($_SESSION['estabelecimento'], "mai"); ?></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php
                if (ver_nivel(1, 2)) {

                    $sql0 = $db->prepare("SELECT p.id, p.nome, p.contato1, p.email, p.status 
                             FROM pessoas p
                             WHERE p.status = 2
                             ORDER BY p.nome ASC");
                    $sql0->execute();
                    $qtd0 = $sql0->rowCount();
                    ?>
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-danger"><?php echo $qtd0; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">Você tem <?php echo $qtd0; ?> usuário(s) aguardando aprovação para acesso ao sistema.</li>
                            <li>
                                <ul class="menu">

                                    <?php
                                    while ($linha0 = $sql0->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                        <li>
                                            <a style="color:#444444;" href="<?php echo PORTAL_URL . "view/pessoas/cadastro.php?id=" . $linha0['id'] . ""; ?>">
                                                <i class="fa fa-users text-aqua"></i> <?php echo $linha0['id'] . " - " . $linha0['nome'] ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>

                                </ul>
                            </li>
                            <li class="footer"><a href="<?php echo PORTAL_URL . "view/pessoas/index.php"; ?>">Ver Todos</a></li>
                        </ul>
                    </li>

                    <!-- Notifications: style can be found in dropdown.less -->
                    <?php
                    $sql = $db->prepare("SELECT p.vlr_total_pontos, pf.nome AS responsavel, cp.nome AS cliente, p.cadastro, p.atualizacao, p.id, p.valor_pagamento, p.valor_troco, fp.id AS forma_pagamento_id, fp.nome AS forma_pagamento, p.status
                             FROM pedidos p
                             LEFT JOIN clientes AS c ON c.id = p.cliente_id
                             LEFT JOIN pessoas AS cp ON cp.id = c.pessoa_id
                             LEFT JOIN forma_pagamentos AS fp ON fp.id = p.forma_pagamento
                             LEFT JOIN funcionarios AS fu ON fu.id = p.funcionario_id
                             LEFT JOIN pessoas AS pf ON pf.id = fu.pessoa_id
                             WHERE p.status = 2
                             ORDER BY p.id ASC");
                    $sql->execute();
                    $qtd = $sql->rowCount();
                    ?>
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-danger"><?php echo $qtd; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">Você tem <?php echo $qtd; ?> pedido(s) aguardando envio.</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <?php
                                    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                        <li>
                                            <a style="color:#444444;" href="<?php echo PORTAL_URL . "view/pedidos/visualizar.php?id=" . $linha['id'] . ""; ?>">
                                                <i class="fa fa-users text-aqua"></i> <?php echo $linha['id'] . " - " . $linha['cliente'] ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                            <li class="footer"><a href="<?php echo PORTAL_URL . "view/pedidos/index.php"; ?>">Ver Todos</a></li>
                        </ul>
                    </li>
                    <!-- Tasks: style can be found in dropdown.less -->
                    <?php
                    $sql2 = $db->prepare("SELECT fp.id AS forma_pagamento_id, p.vlr_total_pontos, pf.nome AS responsavel, cp.nome AS cliente, p.cadastro, p.atualizacao, p.id, p.valor_pagamento, p.valor_troco, fp.nome AS forma_pagamento, p.status
                             FROM pedidos p
                             LEFT JOIN clientes AS c ON c.id = p.cliente_id
                             LEFT JOIN pessoas AS cp ON cp.id = c.pessoa_id
                             LEFT JOIN forma_pagamentos AS fp ON fp.id = p.forma_pagamento
                             LEFT JOIN funcionarios AS fu ON fu.id = p.funcionario_id
                             LEFT JOIN pessoas AS pf ON pf.id = fu.pessoa_id
                             WHERE p.status = 3
                             ORDER BY p.id ASC");
                    $sql2->execute();
                    $qtd2 = $sql2->rowCount();
                    ?>
                    <li class="dropdown tasks-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-success"><?php echo $qtd2; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">Você tem <?php echo $qtd2; ?> pedido(s) aguardando conclusão.</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">

                                    <?php
                                    while ($linha2 = $sql2->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                        <li>
                                            <a style="color:#444444;" href="<?php echo PORTAL_URL . "view/pedidos/visualizar_pagamentos.php?id=" . $linha2['id'] . ""; ?>">
                                                <i class="fa fa-users text-aqua"></i> <?php echo $linha2['id'] . " - " . $linha2['cliente'] ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>

                                </ul>
                            </li>
                            <li class="footer">
                                <a href="<?php echo PORTAL_URL . "view/pedidos/pagamentos.php"; ?>">Ver Todos</a>
                            </li>
                        </ul>
                    </li>
                    <?php
                }
                ?>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!--<img src="<?= IMG_FOLDER; ?>user2-160x160.jpg" class="user-image" alt="User Image"/>-->
                        <span class="hidden-xs"><?php echo $_SESSION['nome']; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <!--<img src="<?= IMG_FOLDER; ?>user2-160x160.jpg" class="img-circle" alt="User Image" />-->
                            <p> <?php echo $_SESSION['nome']; ?> <small>Membro desde <?php echo obterDiaTimestamp($_SESSION['cadastro']) . " de " . getMes(obterMesTimestamp($_SESSION['cadastro'])) . " de " . obterAnoTimestamp($_SESSION['cadastro']); ?></small> </p>
                        </li>
                        <!-- Menu Body -->
                        <!--<li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Seguidores</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Amigos</a>
                            </div>
                        </li>-->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= PORTAL_URL; ?>view/clientes/cadastro.php?id=<?= $_SESSION['id']; ?>" class="btn btn-default btn-flat">Perfil</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?= PORTAL_URL; ?>logout.php" class="btn btn-default btn-flat">Sair</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <!--<li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>-->
            </ul>
        </div>
    </nav>
</header>