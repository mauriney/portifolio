<?php
@session_start();
include_once ($_SESSION['NOME_SISTEMA'] . 'conf/sistema.php');
include_once ($_SESSION['NOME_SISTEMA'] . 'assets/plugins/phpmailer/class.smtp.php');
include_once ($_SESSION['NOME_SISTEMA'] . 'assets/plugins/phpmailer/class.phpmailer.php');
include_once ($_SESSION['NOME_SISTEMA'] . 'functions/geral.php');
include_once ($_SESSION['NOME_SISTEMA'] . 'functions/Url.php');

$db = Conexao::getInstance();

$numero_sessao = session_id();

$_SESSION['estabelecimento'] = pesquisar_tabela("nome", "estabelecimento", "id", "=", 1, "");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= TITULOSISTEMA ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Sweetalert --> 
        <link href="<?= PLUGINS_FOLDER; ?>sweetalert/dist/sweetalert.css" rel="stylesheet">  
        <!-- Bootstrap 3.3.4 -->
        <link href="<?= ASSETS_FOLDER; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />    
        <link rel="stylesheet" type="text/css" href="<?= PLUGINS_FOLDER; ?>template2/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?= PLUGINS_FOLDER; ?>template2/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?= CSS_FOLDER; ?>style.css">
        <link rel="stylesheet" type="text/css" href="<?= PLUGINS_FOLDER; ?>template2/css/style.css">
        <link rel="stylesheet" type="text/css" href="<?= PLUGINS_FOLDER; ?>template2/css/jquery.bxslider.css">
        <link rel="stylesheet" type="text/css" href="<?= PLUGINS_FOLDER; ?>template2/css/colorbox.css">
        <link href="<?= PLUGINS_FOLDER; ?>template2/css/fonts.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="<?= PLUGINS_FOLDER; ?>template2/css/product_slider.css" media="screen">
        <link rel="stylesheet" type="text/css" href="<?= PLUGINS_FOLDER; ?>template2/css/product_tabs.css" media="screen">
        <!-- FontAwesome 4.3.0 -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons 2.0.0 -->
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />    
        <!-- Fonte -->
        <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="wrap">
            <!-- top panel -->
            <div class="navbar navbar-inverse navbar-fixed-top topbar">
                <div class="container">
                    <!-- cart -->
                    <div id="cart">
                        <div class="heading">
                            <a title="Carrinho">
                                <span id="cart-total">
                                    <?php echo isset($_SESSION['id']) ? primeiro_nome($_SESSION['nome']) . " - " : ''; ?>
                                    <i class="fa fa-shopping-cart fa-lg"></i>&nbsp;
                                    <span id="span_carrinho" class="hidden-xs"><?= valores_carirnho(); ?></span>
                                </span>
                            </a>
                        </div>
                        <div id="mini_cardapio" class="content">
                            <div class="inner">
                                <div class="mini-cart-info">
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                            <?php
                                            $subtotalgeral = 0;
                                            $subpontosgeral = 0;
                                            $subtotal = 0;
                                            $subpontos = 0;
                                            $carrinho_qtd = 0;
                                            $sql = $db->prepare("SELECT pi.observacao, pi.id AS pedidos_itens_id, pr.pontuacao_cobrada, pr.id AS produto_id, pi.quantidade, pe.id, pr.nome AS produto, pr.foto_cortada, pr.valor
                             FROM pedidos_itens pi
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             LEFT JOIN produtos AS pr ON pr.id = pi.produto_id
                             WHERE pe.status = 0 AND pe.numero_sessao = ?
                             GROUP BY pi.id
                             ORDER BY pr.nome ASC");
                                            $sql->bindValue(1, $numero_sessao);
                                            $sql->execute();
                                            while ($pedidos = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                $subtotal = 0;
                                                $subpontos = 0;
                                                $ingredientes = "";
                                                $sql2 = $db->prepare("SELECT i.nome, i.add_valor, i.add_pontos
                                         FROM ingredientes i
                                         LEFT JOIN produtos_ingredientes AS pi ON pi.ingrediente_id = i.id
                                         WHERE pi.produto_id = ?
                                         GROUP BY i.id
                                         ORDER BY i.nome ASC");
                                                $sql2->bindValue(1, $pedidos['produto_id']);
                                                $sql2->execute();
                                                while ($itens = $sql2->fetch(PDO::FETCH_ASSOC)) {
                                                    $ingredientes .= $itens['nome'] . ", ";
                                                }

                                                $sql3 = $db->prepare("SELECT i.nome, i.add_valor, i.add_pontos, pi.id AS pedidos_itens_id
                                         FROM ingredientes i
                                         LEFT JOIN pedidos_itens_ingredientes AS pii ON pii.ingrediente_id = i.id
                                         LEFT JOIN pedidos_itens AS pi ON pi.id = pii.pedidos_itens_id
                                         WHERE pi.produto_id = ? AND pi.pedido_id = ?
                                         GROUP BY i.id
                                         ORDER BY i.nome ASC");
                                                $sql3->bindValue(1, $pedidos['produto_id']);
                                                $sql3->bindValue(2, $pedidos['id']);
                                                $sql3->execute();
                                                while ($pedidos_itens = $sql3->fetch(PDO::FETCH_ASSOC)) {
                                                    $ingredientes .= $pedidos_itens['nome'] . ", ";
                                                    $subtotal += $pedidos['quantidade'] == 1 ? $pedidos_itens['add_valor'] : ($pedidos_itens['add_valor'] * $pedidos['quantidade']);
                                                    $subpontos += $pedidos['quantidade'] == 1 ? $pedidos_itens['add_pontos'] : ($pedidos_itens['add_pontos'] * $pedidos['quantidade']);
                                                }

                                                $subtotal += ($pedidos['valor'] * $pedidos['quantidade']);
                                                $subpontos += ($pedidos['pontuacao_cobrada'] * $pedidos['quantidade']);

                                                $subtotalgeral += $subtotal;
                                                $subpontosgeral += $subpontos;

                                                $carrinho_qtd += $pedidos['quantidade'];
                                                ?>
                                                <tr>
                                                    <td class="image">
                                                        <a href="<?php echo PORTAL_URL . "view/cardapios/carrinho.php"; ?>">
                                                            <img width="70px" src="<?php echo PORTAL_URL . $pedidos['foto_cortada']; ?>" alt="<?php echo $pedidos['produto']; ?>" title="<?php echo $pedidos['produto']; ?>">
                                                        </a>
                                                    </td>
                                                    <td class="name">
                                                        <a href="<?php echo PORTAL_URL . "view/cardapios/carrinho.php"; ?>"><?php echo $pedidos['produto']; ?></a>
                                                        <div> 
                                                            <small><?= $ingredientes != "" ? substr($ingredientes, 0, strlen($ingredientes) - 2) : ""; ?></small><br>
                                                        </div>
                                                    </td>
                                                    <td class="quantity">x&nbsp;<?php echo $pedidos['quantidade']; ?></td>
                                                    <td class="total">R$ <?php echo fdec($subtotal); ?></td>
                                                    <td class="remove">
                                                        <button onclick="remover_item(<?php echo $pedidos['pedidos_itens_id']; ?>)" type="button" class="close" aria-hidden="true" title="Remove">×</button>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mini-cart-total">
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                            <tr>
                                                <td class="name"><b>Sub-Total:</b></td>
                                                <td class="total">R$ <?php echo fdec($subtotalgeral); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="name"><b>Desconto Cupom:</b></td>
                                                <td class="total">R$ <?= isset($_SESSION['cupom_valor']) ? $_SESSION['cupom_valor'] : '0,00'; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="name"><b>Total:</b></td>
                                                <td class="total">R$ <?php echo fdec(isset($_SESSION['cupom_valor']) ? ($subtotalgeral - valorfloat($_SESSION['cupom_valor'])) : $subtotalgeral); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- menu -->
                    <div id="menu" class="menu-container">
                        <button type="button" class="menu-toggle" data-toggle="collapse" data-target="#menu-mobile">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <div class="collapse menu-collapse" id="menu-mobile">
                            <ul class="nav nav-pills">
                                <li><a href="<?php echo PORTAL_URL . "registrar.php"; ?>">Criar Conta</a></li>
                                <li><a href="<?php echo PORTAL_URL . "login.php"; ?>">Login</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="divider hidden-sm hidden-xs"></div>

                </div>
            </div>
            <!-- //top panel -->

            <!-- header -->
            <div class="header">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="header-left">
                                <div id="address">
                                    <p><strong>Horário de Funcionamento</strong></p>
                                    <p>SEG à DOM<strong> 18:00 - 00:00</strong></p>
                                </div>
                                <div id="phone"><span>(68)</span> 2102-1485</div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div id="logo">
                                <a href="<?php echo PORTAL_URL . "view/cardapios/index.php"; ?>"><img src="<?= IMG_FOLDER; ?>logo.png" title="<?php echo $_SESSION['estabelecimento']; ?>"></a>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="header-right">
                                <div id="welcome">
                                    Bem vindo, <a href="<?php echo PORTAL_URL . "login.php"; ?>"><strong>LOGIN</strong></a> ou <a href="<?php echo PORTAL_URL . "registrar.php"; ?>"><strong>CRIAR UMA CONTA</strong></a>.
                                </div>

                                <div class="links">
                                    <a id="link-cart" href="<?php echo PORTAL_URL . "view/cardapios/carrinho.php"; ?>">
                                        <i class="fa fa-shopping-cart"></i> Carrinho de compras
                                    </a><br>
                                    <?php echo isset($_SESSION['id']) ? '<a id="link-checkout" href="' . PORTAL_URL . 'view/geral/modulos.php"><i class="fa fa-credit-card"></i> Painel Administrativo</a>' : ''; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="search">
                        <form id="form_busca" name="form_busca" method="post" action="<?php echo PORTAL_URL . "view/cardapios/cardapio.php" ?>">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div id="search">
                                        <div class="input-group">
                                            <input type="text" id="busca" name="busca" class="form-control" placeholder="Buscar" value="">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default button-search" type="submit"><span class="fa fa-search"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- //header -->

            <div id="container" class="content">
                <div class="container">
                    <div id="notification"></div>
                    <div id="content">