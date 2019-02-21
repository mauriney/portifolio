<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo_cardapio.php');
?>

<div class="box product-slider clearfix">
    <div class="banner clearfix pull-right" style="width: 200px">
        <div class="item pull-left">
            <a href="cardapio.php?categoria=3">
                <img src="<?= IMG_FOLDER; ?>bn-sucos.png" alt="Hot" title="Hot">
            </a>
            <br>
        </div>
        <div class="item pull-left">
            <a href="cardapio.php?categoria=1">
                <img src="<?= IMG_FOLDER; ?>bn-refrigerantes.png" alt="Special" title="Special">
            </a>
            <br>
        </div>
    </div>

    <div class="box-content hidden-xs" style="margin-right: 250px;">
        <div class="bx-wrapper">
            <div class="bx-viewport">
                <div id="product-slider-0">
                    <?php
                    $sql = $db->prepare("SELECT foto_original, id, nome, valor, pontuacao_cobrada, categoria_id, status
                             FROM produtos
                             WHERE destaque = 1 AND status = 1 AND foto_original <> ''
                             ORDER BY nome ASC
                             LIMIT 0, 10");
                    $sql->execute();
                    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <div class="slide-item" >
                            <div class="si-image">
                                <a href="cardapio.php?id=<?php echo $linha['id']; ?>">
                                    <img src="<?= PORTAL_URL . $linha['foto_original']; ?>" alt="X-BaconEgg">
                                </a>
                            </div>

                            <div class="si-caption">
                                <div class="si-title">
                                    <a href="cardapio.php?id=<?php echo $linha['id']; ?>"><?php echo $linha['nome']; ?></a>
                                </div>

                                <div class="si-desc">
                                    <span>
                                        <?php
                                        $ingredientes = "";
                                        $sql2 = $db->prepare("SELECT i.nome
                                         FROM ingredientes i
                                         LEFT JOIN produtos_ingredientes AS pi ON pi.ingrediente_id = i.id
                                         WHERE pi.produto_id = ?
                                         GROUP BY i.id
                                         ORDER BY i.nome ASC");
                                        $sql2->bindValue(1, $linha['id']);
                                        $sql2->execute();
                                        while ($linha2 = $sql2->fetch(PDO::FETCH_ASSOC)) {
                                            $ingredientes .= $linha2['nome'] . ", ";
                                        }
                                        ?>
                                        <?php echo $ingredientes != "" ? "(" . substr($ingredientes, 0, strlen($ingredientes) - 2) . ")" : ""; ?>
                                    </span>
                                </div>

                                <div class="si-price price">
                                    <div><span class="price-fixed">R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></span></div>
                                </div>

                                <div class="si-buy cart">
                                    <a href="detalha_produto.php?id=<?= $linha['id']; ?>"><button type="button" class="btn btn-success">
                                            <span>Comprar Agora</span>
                                        </button></a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="bx-controls bx-has-pager bx-has-controls-direction">
                <div class="bx-pager bx-default-pager"></div>
                <div class="bx-controls-direction" id="bx-controls-direction"></div>
            </div>
        </div>
    </div>

    <div id="banner0" class="banner clearfix">
        <div class="item pull-left">
            <a href="cardapio.php?categoria=6">
                <img src="<?= IMG_FOLDER; ?>doces.jpg" alt="Doces" title="Doces">
            </a>
        </div>
        <div class="item pull-left">
            <a href="cardapio.php?categoria=9">
                <img src="<?= IMG_FOLDER; ?>empada.jpg" alt="Empadinhas" title="Empadinhas">
            </a>
        </div>
        <div class="item pull-left">
            <a href="cardapio.php?categoria=10">
                <img src="<?= IMG_FOLDER; ?>picoles.jpg" alt="Fresh basil" title="Fresh basil">
            </a>
        </div>
    </div>

    <div class="box">

        <ul id="product-tabs-0" class="nav nav-tabs">
            <li class="active">
                <a href="#product-tab-0-featured" data-toggle="tab">
                    <span>Produtos em Destaque</span>
                </a>
            </li>
            <li>
                <a href="#product-tab-0-bestseller" data-toggle="tab">
                    <span>Mais Vendidos</span>
                </a>
            </li>
            <li>
                <a href="#product-tab-0-latest" data-toggle="tab">
                    <span>Mais Recentes</span>
                </a>
            </li>
        </ul>


        <div class="box-content tab-content" id="product-slideshow0">

            <div class="box-product product-grid tab-pane fade in active" id="product-tab-0-featured">

                <?php
                //PRODUTOS EM DESTAQUE
                $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.destaque = 1 AND p.status = 1 AND p.foto_original <> ''
                             GROUP BY p.id
                             ORDER BY p.nome ASC
                             LIMIT 0,8");
                $sql->execute();
                if ($sql->rowCount() == 0) {
                    echo "Nenhum item encontrado";
                }
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                    ?>

                    <!-- Begin box-product div -->
                    <div class="item">
                        <div class="image">
                            <a href="cardapio.php?id=<?php echo $linha['id']; ?>">
                                <img src="<?php echo PORTAL_URL . $linha['foto_cortada']; ?>" alt="<?php echo $linha['nome']; ?>">
                            </a>
                        </div>

                        <div class="caption">
                            <div class="name">
                                <a href="cardapio.php?id=<?php echo $linha['id']; ?>"><?php echo $linha['nome']; ?></a>
                            </div>

                            <div class="price">
                                <div>
                                    <span class="price-fixed">R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></span>
                                </div>
                            </div>

                            <div class="cart">
                                <a href="detalha_produto.php?id=<?= $linha['id']; ?>">
                                <button type="button" class="btn btn-success">
                                    <i class="fa fa-shopping-cart"></i> <span>Adicionar ao Carrinho</span>
                                </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div class="box-product product-grid tab-pane fade" id="product-tab-0-bestseller">
                <?php
                //PRODUTOS MAIS VENDIDOS
                $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE pe.status = 2 AND p.status = 1 AND p.foto_original <> ''
                             GROUP BY p.id
                             ORDER BY p.nome ASC
                             LIMIT 0,8");
                $sql->execute();
                if ($sql->rowCount() == 0) {
                    echo "Nenhum item encontrado";
                }
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                    ?>

                    <!-- Begin box-product div -->
                    <div class="item">
                        <div class="image">
                            <a href="cardapio.php?id=<?php echo $linha['id']; ?>">
                                <img src="<?php echo PORTAL_URL . $linha['foto_cortada']; ?>" alt="<?php echo $linha['nome']; ?>">
                            </a>
                        </div>

                        <div class="caption">
                            <div class="name">
                                <a href="cardapio.php?id=<?php echo $linha['id']; ?>"><?php echo $linha['nome']; ?></a>
                            </div>

                            <div class="price">
                                <div>
                                    <span class="price-fixed">R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></span>
                                </div>
                            </div>

                            <div class="cart">
                                <button onclick="addToCart( & #39; 44 & #39; );" type="button" class="btn btn-success">
                                    <i class="fa fa-shopping-cart"></i> <span>Adicionar ao Carrinho</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div class="box-product product-grid tab-pane fade" id="product-tab-0-latest">
                <?php
                //PRODUTOS MAIS RECENTES
                $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             WHERE p.status = 1 AND p.foto_original <> ''
                             GROUP BY p.id
                             ORDER BY p.cadastro DESC
                             LIMIT 0,8");
                $sql->execute();
                if ($sql->rowCount() == 0) {
                    echo "Nenhum item encontrado";
                }
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                    ?>

                    <!-- Begin box-product div -->
                    <div class="item">
                        <div class="image">
                            <a href="cardapio.php?id=<?php echo $linha['id']; ?>">
                                <img src="<?php echo PORTAL_URL . $linha['foto_cortada']; ?>" alt="<?php echo $linha['nome']; ?>">
                            </a>
                        </div>

                        <div class="caption">
                            <div class="name">
                                <a href="cardapio.php?id=<?php echo $linha['id']; ?>"><?php echo $linha['nome']; ?></a>
                            </div>

                            <div class="price">
                                <div>
                                    <span class="price-fixed">R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></span>
                                </div>
                            </div>

                            <div class="cart">
                                <button onclick="addToCart( & #39; 44 & #39; );" type="button" class="btn btn-success">
                                    <i class="fa fa-shopping-cart"></i> <span>Adicionar ao Carrinho</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <div class="carousel">
        <div class="bx-wrapper">
            <div class="bx-viewport">
                <ul id="carousel0" class="slider">
                    <?php
                    $sql = $db->prepare("SELECT foto_cortada, id, nome, valor, pontuacao_cobrada, categoria_id, status
                             FROM produtos
                             WHERE categoria_id = 4 AND status = 1 AND foto_cortada <> ''
                             ORDER BY nome ASC");
                    $sql->execute();
                    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <li>
                            <a class="item" href="cardapio.php?id=<?php echo $linha['id']; ?>">
                                <div class="imgItem">
                                    <img src="<?php echo PORTAL_URL . $linha['foto_cortada']; ?>" alt="<?php echo $linha['nome']; ?>" title="<?php echo $linha['nome']; ?>"> 
                                </div>
                                <div class="caption"><?php echo $linha['nome']; ?></div>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="bx-controls bx-has-controls-direction">
                <div class="bx-controls-direction" id="bx-controls-direction2"></div>
            </div>
        </div>
    </div>
</div>

<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape_cardapio.php'); ?>
