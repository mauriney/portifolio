<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo_cardapio.php');

$id = antiSQL(@$_GET['id']);


if (is_numeric($id)) {
    $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.id = ?
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
    $sql->bindValue(1, $id);
    $sql->execute();

    $dados = $sql->fetch(PDO::FETCH_ASSOC);
//INGREDIENTES
    $ingredientes = "";
    $sql4 = $db->prepare("SELECT i.nome
                                         FROM ingredientes i
                                         LEFT JOIN produtos_ingredientes AS pi ON pi.ingrediente_id = i.id
                                         WHERE pi.produto_id = ?
                                         GROUP BY i.id
                                         ORDER BY i.nome ASC");
    $sql4->bindValue(1, $dados['id']);
    $sql4->execute();
    while ($linha4 = $sql4->fetch(PDO::FETCH_ASSOC)) {
        $ingredientes .= $linha4['nome'] . ", ";
    }
} else {
    echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "view/cardapios/cardapio.php';</script>";
}
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
                </li>
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
                            <button class="btn btn-success" type="button" onclick="addToCart( & #39; 44 & #39; );">
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

<div id="content">
    <ol class="breadcrumb">
        <li><a href="index.php">Início</a></li>
        <li><a href="detalha_produto.php?id=<?= $id; ?>">Detalhe</a></li>
    </ol>

    <form id="form_detalhe" action="carrinho.php" method="POST">
        <div class="product-info">
            <div class="row">
                <div class="col-md-4 popup-gallery">
                    <div class="image">
                        <a href="<?= PORTAL_URL . $dados['foto_cortada']; ?>" title="Margarita" class="imagebox">
                            <img src="<?= PORTAL_URL . $dados['foto_cortada']; ?>" title="Margarita" alt="Margarita" id="image">
                        </a>
                    </div>
                </div>

                <div class="col-md-8 product-center clearfix">
                    <h1><?= ctexto($dados['nome'], "mai"); ?></h1>
                    <div <?= $dados['categoria_id'] == 2 || $dados['categoria_id'] == 4 || $dados['categoria_id'] == 5 || $dados['categoria_id'] == 8 ? '' : 'style="display:none"' ?> class="description">
                        <strong>Ingredientes:</strong> <?= $ingredientes != "" ? substr($ingredientes, 0, strlen($ingredientes) - 2) : ""; ?><br>
                    </div>
                    <div <?= $dados['categoria_id'] == 2 || $dados['categoria_id'] == 4 || $dados['categoria_id'] == 5 || $dados['categoria_id'] == 8 ? '' : 'style="display:none"' ?> class="options">
                        <div id="option-243" class="option form-group">
                            <label>
                                Adicionar mais ingredientes:
                            </label><br>
                            <div class="row">
                                <?php
                                $sql = $db->prepare("SELECT id, nome, add_valor, add_pontos
                                                 FROM ingredientes
                                                 WHERE status = 1
                                                 ORDER BY nome ASC");
                                $sql->execute();
                                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                    <div class="col-md-6">
                                        <div class="checkbox checkbox-danger">
                                            <input type="checkbox" value="<?= $linha['id']; ?>" id="ingrediente_<?= $linha['id']; ?>" name="ingrediente[]">
                                            <label for="ingrediente_<?= $linha['id']; ?>"><?= $linha['nome']; ?> <small>(+ R$ <?= fdec($linha['add_valor']); ?><?php echo $linha['add_pontos'] > 0 ? ' + ' . $linha['add_pontos'] . ' pontos' : '' ?>)</small></label>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="note">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="obs">Observação</label>
                                <textarea name="obs" id="obs" class="form-control" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cart-area">
                <div class="row">
                    <div class="col-md-8">
                        <div class="price">
                            <div>
                                <span class="price-fixed">R$ <?= fdec($dados['valor']); ?><?php echo $dados['pontuacao_cobrada'] > 0 ? ' ou ' . $dados['pontuacao_cobrada'] . ' pontos.' : '' ?></span>
                            </div>
                        </div>
                        <div class="text-center" style="font-size: 12px"></div>
                    </div>

                    <div class="col-md-4">
                        <div class="cart">
                            <div class="add-to-cart clearfix" style="margin-top: 20px">
                                <!--span class="help-block">Qty:</span-->
                                <div class="input-group input-group-lg">
                                    <input type="text" id="quantidade" name="quantidade" class="form-control" size="2" value="1" onkeypress="return SomenteNumero(event);" maxlength="3">
                                    <span class="input-group-btn">
                                        <button type="submit" id="button-cart" class="btn btn-success">
                                            <i class="fa fa-shopping-cart"></i> &nbsp;
                                            Adicionar
                                        </button>
                                    </span>
                                </div>
                                <input type="hidden" id="produto_id" name="produto_id" value="<?php echo $id; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="box box-bestseller">
        <div class="box-heading">
            <span>Mais vendidos</span>
        </div>
        <div class="box-content">

            <div class="box-product product-grid">
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
                                <a href="detalha_produto.php?id=<?= $linha['id']; ?>"><button type="button" class="btn btn-success">
                                        <i class="fa fa-shopping-cart"></i> <span>Adicionar ao Carrinho</span>
                                    </button></a>
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

<script type="text/javascript">
    function display(view) {
        if (view == 'list')
        {
            $('#content .product-grid').attr('class', 'product-list box-product');
            $('.display').html('' +
                    '<div class="btn-group btn-group-sm">' +
                    '<span class="btn btn-default" disabled="disabled"><i class="fa fa-th-list"></i> List</span>' +
                    '<a class="btn btn-default" onclick="display(\'grid\');"><i class="fa fa-th"></i> Grid</a>' +
                    '</div>'
                    );
            $.totalStorage('display', 'list');
        }
        else
        {
            $('#content .product-list').attr('class', 'product-grid box-product');
            $('.display').html('' +
                    '<div class="btn-group btn-group-sm">' +
                    '<a class="btn btn-default" onclick="display(\'list\');"><i class="fa fa-th-list"></i> List</a>' +
                    '<span class="btn btn-default" disabled="disabled"><i class="fa fa-th"></i> Grid</span>' +
                    '</div>'
                    );
            $.totalStorage('display', 'grid');
        }
    }
    view = $.totalStorage('display');
    if (view) {
        display('view');
    } else {
        display('list');
    }
</script>
</div>
<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape_cardapio.php'); ?>

<!-- JS DA PÁGINA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/cardapios/detalha_produto.js"></script>