<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo_cardapio.php');

$id = antiSQL(@$_GET['id']);
$categoria = antiSQL(@$_GET['categoria']);
$busca = antiSQL(@$_POST['busca']);
?>
<div id="column-left" class="hidden-xs hidden-sm">
    <div class="box">
        <div class="box-heading">
            <span>CATEGORIAS</span>
        </div>
        <div class="box-content">
            <ul class="box-category treemenu">
                <?php
                $class = "active";
                $sql = $db->prepare("SELECT id, nome, status
                             FROM categorias
                             WHERE status = 1
                             ORDER BY nome ASC");
                $sql->execute();
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <li id="cat" rel="<?php echo $linha['id']; ?>"><a href="cardapio.php?categoria=<?php echo $linha['id']; ?>" class="<?php echo $class; ?>"><span><?php echo $linha['nome']; ?> (<?php echo qtd_produtos_categoria($linha['id']); ?>)</span></a></li>
                    <?php
                    $class = "";
                }
                ?>
            </ul>
        </div>
    </div>

    <div id="banner0" class="banner clearfix">
        <div class="item pull-left">
            <a href="http://conceptlogic.ru/">
                <img src="<?= IMG_FOLDER; ?>coca-cola.jpg" alt="E-commerce solutions" title="E-commerce solutions">
            </a>
        </div>
    </div>

    <div class="box box-featured">
        <div class="box-heading">
            <span>Destaque</span>
        </div>

        <div class="box-content">
            <div class="box-product product-grid">
                <?php
                //PRODUTOS EM DESTAQUE
                $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.destaque = 1 AND p.status = 1 AND p.foto_original <> ''
                             GROUP BY p.id
                             ORDER BY p.nome ASC
                             LIMIT 0, 12");
                $sql->execute();
                if ($sql->rowCount() == 0) {
                    echo "Nenhum item encontrado";
                }
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="item">
                        <div class="image">
                            <a href="cardapio.php?id=<?php echo $linha['id']; ?>">
                                <img alt="<?php echo $linha['nome']; ?>" title="<?php echo $linha['nome']; ?>" src="<?php echo PORTAL_URL . $linha['foto_cortada']; ?>" style="width: 80px; height: 80px;">
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
                                    <button class="btn btn-success" type="button">
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
        </div>
    </div>
</div>

<div id="content">
    <ol class="breadcrumb">
        <li><a href="index.php">Início</a></li>
        <li><a href="cardapio.php">Cardápio</a></li>
    </ol>

    <?php
    //PRODUTOS EM DESTAQUE
    if (is_numeric($id)) {
        $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.id = ? AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
        $sql->bindValue(1, $id);
    } else if (is_numeric($categoria)) {
        $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.categoria_id = ? AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC
                             LIMIT 0,1");
        $sql->bindValue(1, $categoria);
    } else if ($busca != "") {
        $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.nome like ? AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC
                             LIMIT 0,1");
        $sql->bindValue(1, "%" . $busca . "%");
    }

    $sql->execute();
    if ($sql->rowCount() == 0) {
        echo "Nenhum item encontrado";
        ?>
        <input type="hidden" id="categoria" name="categoria" value="<?php echo is_numeric($categoria) && $categoria > 0 ? $categoria : ""; ?>"/>
        <?php
    }
    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <input type="hidden" id="categoria" name="categoria" value="<?php echo $linha['categoria_id']; ?>"/>
        <div class="category-info clearfix">
            <div class="item">
                <div class="image" style="float: left; margin-top: -45px; margin-right: 20px">
                    <a href="cardapio.php?id=<?php echo $linha['id']; ?>">
                        <img src="<?= PORTAL_URL . $linha['foto_cortada']; ?>" title="<?php echo $linha['nome']; ?>" alt="<?php echo $linha['nome']; ?>" style="width: 250px; height: 250px">
                    </a>
                </div>
                <div class="caption">
                    <h1><?php echo $linha['nome']; ?></h1>
                    <div class="description">
                        <?php
                        $ingredientes = "";
                        $sql2 = $db->prepare("SELECT i.nome
                                         FROM ingredientes i
                                         LEFT JOIN produtos_ingredientes AS pi ON pi.ingrediente_id = i.id
                                         WHERE pi.produto_id = ? AND i.status = 1
                                         GROUP BY i.id
                                         ORDER BY i.nome ASC");
                        $sql2->bindValue(1, $linha['id']);
                        $sql2->execute();
                        while ($linha2 = $sql2->fetch(PDO::FETCH_ASSOC)) {
                            $ingredientes .= $linha2['nome'] . ", ";
                        }
                        ?>
                        <?php echo $ingredientes != "" ? "(" . substr($ingredientes, 0, strlen($ingredientes) - 2) . ")" : ""; ?>
                    </div>
                    <p></p>
                    <div class="price">
                        <div>
                            <span class="price-fixed">R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></span>
                        </div>
                    </div>   
                    <p></p>
                    <div class="cart">
                        <a href="detalha_produto.php?id=<?= $linha['id']; ?>">
                            <button class="btn btn-success" type="button">
                                <i class="fa fa-shopping-cart"></i> <span>Adicionar ao Carrinho</span>
                            </button>
                        </a>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-filter clearfix">
            <div class="display">
                <div class="btn-group btn-group-sm">
                    <span class="btn btn-default" disabled="disabled"><i class="fa fa-th-list"></i> Lista</span>
                    <a class="btn btn-default" onclick="display('grid');"><i class="fa fa-th"></i> Grade</a>
                </div>
            </div>
            <div class="limit">
                <div class="dropdown">
                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="limit-goods" data-toggle="dropdown">
                        <i class="fa fa-level-down"></i>&nbsp;Mostrar:&nbsp;<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="limit-goods">
                        <li id="li_mostrar" rel="10" class="disabled"><a tabindex="-1" style="cursor: pointer" onclick="mostrar(10)">10</a></li>
                        <li id="li_mostrar" rel="20"><a tabindex="-1" style="cursor: pointer" onclick="mostrar(20)">20</a></li>
                        <li id="li_mostrar" rel="25"><a tabindex="-1" style="cursor: pointer" onclick="mostrar(25)">25</a></li>
                        <li id="li_mostrar" rel="50"><a tabindex="-1" style="cursor: pointer" onclick="mostrar(50)">50</a></li>
                        <li id="li_mostrar" rel="75"><a tabindex="-1" style="cursor: pointer" onclick="mostrar(75)">75</a></li>
                        <li id="li_mostrar" rel="100"><a tabindex="-1" style="cursor: pointer" onclick="mostrar(100)">100</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="box-product product-list">
            <?php
            $contador = 1;
            $diplay = "";
            $sql3 = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.status = 1 AND p.id <> ? AND p.categoria_id = ?
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
            $sql3->bindValue(1, $linha['id']);
            $sql3->bindValue(2, $linha['categoria_id']);
            $sql3->execute();
            while ($linha3 = $sql3->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div id="item" class="item" rel="<?php echo $contador; ?>" style="<?php echo $diplay; ?>">
                    <div class="image">
                        <a href="cardapio.php?id=<?php echo $linha3['id']; ?>">
                            <img src="<?= PORTAL_URL . $linha3['foto_cortada']; ?>" style="width: 150px; height: 150px" title="<?php echo $linha3['nome']; ?>" alt="<?php echo $linha3['nome']; ?>">
                        </a>
                    </div>
                    <div class="caption">
                        <div class="name">
                            <a href="cardapio.php?id=<?php echo $linha3['id']; ?>"><?php echo $linha3['nome']; ?></a>
                        </div>
                        <div class="description">
                            <?php
                            $ingredientes = "";
                            $sql4 = $db->prepare("SELECT i.nome
                                         FROM ingredientes i
                                         LEFT JOIN produtos_ingredientes AS pi ON pi.ingrediente_id = i.id
                                         WHERE pi.produto_id = ? AND i.status = 1
                                         GROUP BY i.id
                                         ORDER BY i.nome ASC");
                            $sql4->bindValue(1, $linha3['id']);
                            $sql4->execute();
                            while ($linha4 = $sql4->fetch(PDO::FETCH_ASSOC)) {
                                $ingredientes .= $linha4['nome'] . ", ";
                            }
                            ?>
                            <?php echo $ingredientes != "" ? "(" . substr($ingredientes, 0, strlen($ingredientes) - 2) . ")" : ""; ?>
                        </div>

                        <div class="price">
                            <div>
                                <span class="price-fixed">R$ <?php echo fdec($linha3['valor']); ?><?php echo $linha3['pontuacao_cobrada'] > 0 ? ' ou ' . $linha3['pontuacao_cobrada'] . ' pontos.' : '' ?></span>
                            </div>
                        </div>

                        <div class="cart">
                            <a href="detalha_produto.php?id=<?= $linha3['id']; ?>">
                                <button class="btn btn-success" type="button">
                                    <i class="fa fa-shopping-cart"></i> <span>Adicionar ao Carrinho</span>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                if ($contador >= 10) {
                    $diplay = "display: none";
                }

                $contador++;
            }
            ?>

        </div>
        <a id="mostrar_mais" style="cursor: pointer" onclick="mostrar_mais(this)" rel="20">Mostrar Mais</a>
        <?php
    }
    ?>
</div>

<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape_cardapio.php'); ?>

<!-- JS DA LISTA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/cardapios/cardapio.js"></script>