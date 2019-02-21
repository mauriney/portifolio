<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo_cardapio.php');

$obs = @$_POST['obs'];
$quantidade = @$_POST['quantidade'];
$ingredientes = @$_POST['ingrediente'];
$produto_id = @$_POST['produto_id'];
$numero_sessao = session_id();

//INSERINDO PRODUTO E ITENS
if (isset($quantidade) && isset($produto_id)) {
    inserir_ingredientes($produto_id, $obs, $quantidade, $ingredientes, $numero_sessao);
    echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "view/cardapios/carrinho.php';</script>";
}
?>

<div id="content">

    <ol class="breadcrumb">
        <li><a href="<?php echo PORTAL_URL;?>view/cardapios/index.php">Início</a></li>
        <li><a href="<?php echo PORTAL_URL;?>view/cardapios/carrinho.php">Carrinho de Compras</a></li>
    </ol>

    <form id="form_finalizar" action="#" method="post" enctype="multipart/form-data">

        <input type="hidden" id="session_id" name="session_id" value="<?php echo isset($_SESSION['id']) ? $_SESSION['id'] : ''; ?>"/>

        <div class="page-header">
            <h1>Carrinho de Compras</h1>
        </div>

        <div class="cart-info table-responsive">
            <table id="tabela_lista_produtos" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="image">Imagem</th>
                        <th class="name">Produto</th>
                        <th class="quantity">Quantidade</th>
                        <th class="obs">Observação</th>
                        <th class="price">Valor Unitário</th>
                        <th class="total">Valor-Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $subtotalgeral = 0;
                    $subpontosgeral = 0;
                    $subtotal = 0;
                    $subpontos = 0;
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
                        
                        /*$sql2 = $db->prepare("SELECT i.nome, i.add_valor, i.add_pontos
                                         FROM ingredientes i
                                         LEFT JOIN produtos_ingredientes AS pi ON pi.ingrediente_id = i.id
                                         WHERE pi.produto_id = ?
                                         GROUP BY i.id
                                         ORDER BY i.nome ASC");
                        $sql2->bindValue(1, $pedidos['produto_id']);
                        $sql2->execute();
                        while ($itens = $sql2->fetch(PDO::FETCH_ASSOC)) {
                            $ingredientes .= $itens['nome'] . ", ";
                        }*/

                        $sql3 = $db->prepare("SELECT i.nome, i.add_valor, i.add_pontos
                                         FROM ingredientes i
                                         LEFT JOIN pedidos_itens_ingredientes AS pii ON pii.ingrediente_id = i.id
                                         LEFT JOIN pedidos_itens AS pi ON pi.id = pii.pedidos_itens_id
                                         WHERE pi.produto_id = ? AND pi.pedido_id = ? AND pi.id = ?
                                         GROUP BY i.id
                                         ORDER BY i.nome ASC");
                        $sql3->bindValue(1, $pedidos['produto_id']);
                        $sql3->bindValue(2, $pedidos['id']);
                        $sql3->bindValue(3, $pedidos['pedidos_itens_id']);
                        $sql3->execute();
                        while ($pedidos_itens = $sql3->fetch(PDO::FETCH_ASSOC)) {
                            $ingredientes .= $pedidos_itens['nome'] . ", ";
                            $subtotal += $pedidos['quantidade'] == 1 ? $pedidos_itens['add_valor'] : ($pedidos_itens['add_valor'] * $pedidos['quantidade']);
                            $subpontos += $pedidos['quantidade'] == 1 ? $pedidos_itens['add_pontos'] : ($pedidos_itens['add_pontos'] * $pedidos['quantidade']);
                        }
                        ?>
                        <tr>
                            <td class="image">
                                <a target="_blank" href="<?php echo PORTAL_URL . $pedidos['foto_cortada']; ?>">
                                    <img width="45px" src="<?php echo PORTAL_URL . $pedidos['foto_cortada']; ?>" alt="<?php echo $pedidos['produto']; ?>" title="<?php echo $pedidos['produto']; ?>">
                                </a>
                            </td>
                            <td class="name">
                                <a><?php echo $pedidos['produto']; ?></a>
                                <div> 
                                    <small><?= $ingredientes != "" ? substr($ingredientes, 0, strlen($ingredientes) - 2) : ""; ?></small><br>
                                </div>
                            </td>
                            <td class="quantity">
                                <div id="div_item_qtd" class="input-group">
                                    <span class="input-group-btn">
                                        <a onclick="update_qtd_item(<?php echo $pedidos['pedidos_itens_id']; ?>, this)" class="btn btn-success" title="Update" style="cursor: pointer">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </span>

                                    <input class="form-control" type="text" id="quantidade_item" name="quantidade_item[]" value="<?php echo $pedidos['quantidade']; ?>" size="1" maxlength="3">

                                    <span class="input-group-btn">
                                        <a onclick="remover_item(<?php echo $pedidos['pedidos_itens_id']; ?>)" class="btn btn-danger" title="Remove" style="cursor: pointer">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </span>
                                </div>
                            </td>
                            <td class="obs">
                                <?php echo $pedidos['observacao']; ?>
                            </td>
                            <td class="price">
                                <div id="div_valor">
                                    R$ <?php echo fdec($pedidos['valor']); ?>
                                </div>
                                <div id="div_pontuacao" style="display: none">
                                    <?php echo $pedidos['pontuacao_cobrada'] == 0 ? "não aceita" : $pedidos['pontuacao_cobrada']; ?> pontos
                                </div>
                            </td>
                            <td class="total">
                                <?php
                                $subtotal += ($pedidos['valor'] * $pedidos['quantidade']);
                                $subpontos += ($pedidos['pontuacao_cobrada'] * $pedidos['quantidade']);

                                $subtotalgeral += $subtotal;
                                $subpontosgeral += $subpontos;
                                ?>
                                <div id="div_subtotal">
                                    R$ <?php echo fdec($subtotal); ?>
                                </div>
                                <div id="div_subpontos" style="display: none">
                                    <?php echo $subpontos == 0 ? "não aceita" : $subpontos; ?> pontos
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Local de Entrega</h3>
            </div>
            <div class="panel-body">
                <div class="content">
                    <p>Escolha a forma de entrega entre o número da mesa ou o endereço com o valor estimado da entrega.</p>
                    <div class="radio1 radio-danger">
                        <input checked="true" type="radio" id="entrega" name="next" value="div_entrega">
                        <label for="entrega">Endereço com valor estimado da Entrega</label>
                    </div>
                    <div class="radio1 radio-danger">
                        <input type="radio" id="mesa" name="next" value="div_mesa">
                        <label for="mesa">Número da Mesa</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="cart-module">
            <div id="div_mesa" style="display: none" class="content">
                <div id="div_numero_mesa" class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="help-block">Escolha o número da sua mesa:</p>
                            <div class="input-group">
                                <label class="control-label" for="district">
                                    <span class="text-danger">*</span> Número da Mesa:
                                </label>
                                <select id="numero_mesa" name="numero_mesa" class="form-control">
                                    <option value="">Escolha o número</option>
                                    <?php
                                    $sql = $db->prepare("SELECT numero FROM mesas WHERE status = 1 ORDER BY numero ASC");
                                    $sql->execute();
                                    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                        <option value="<?php echo $linha['numero']; ?>"><?php echo $linha['numero']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="next" value="coupon">
                        </div>
                    </div>
                </div>
            </div>

            <div id="div_entrega" class="content">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="help-block">Por favor, preencha as seguintes informações:</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="div_bairro" class="form-group">
                                            <label class="control-label" for="district">
                                                <span class="text-danger">*</span> Bairro:
                                            </label>
                                            <select class="form-control" name="bairro_id" id="bairro_id">
                                                <option pontos="0" rel="0" value="">Escolha o bairro</option>
                                                <?php
                                                $sql = $db->prepare("SELECT id, nome, valor, pontos FROM bairros WHERE status = 1 ORDER BY nome ASC");
                                                $sql->execute();
                                                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                    <option pontos="<?php echo $linha['pontos']; ?>" rel="<?php echo $linha['valor']; ?>" value="<?php echo $linha['id']; ?>"><?php echo $linha['nome']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div id="div_endereco" class="form-group">
                                            <label for="endereco"><span class="text-danger">*</span> Endereço</label>
                                            <input type="text" name="endereco" id="endereco" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div id="div_numero_casa" class="form-group">
                                            <label for="numero"><span class="text-danger">*</span> Número</label>
                                            <input type="text" name="numero" id="numero" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="complemento">Complemento</label>
                                            <input type="text" name="complemento" id="complemento" class="form-control" placeholder="Bloco, Apartamento, Referência e etc" />
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="div_contato" class="form-group">
                                            <label for="contato"><span class="text-danger">*</span> Telefone de Contato</label>
                                            <input type="text" name="contato" id="contato" class="form-control" data-inputmask='"mask": "(99) 9999-9999"' data-mask placeholder="celular, residencial ou comercial"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row cart-total">
            <div class="col-md-9">
                <div id="cupom" class="content">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="help-block">Digite aqui seu cupom de desconto:</p>
                                <div class="input-group">
                                    <input id="cupom" name="cupom" type="text" name="cupom" value="<?= isset($_SESSION['cupom']) ? $_SESSION['cupom'] : ''; ?>" class="form-control">
                                    <span class="input-group-btn">
                                        <button <?= isset($_SESSION['cupom']) ? '' : 'style="display: none"'; ?> id="remover_cupom" name="remover_cupom" class="btn btn-danger" type="button">Remover Cupom</button>
                                        <button <?= isset($_SESSION['cupom']) ? 'style="display: none"' : ''; ?> id="inserir_cupom" name="inserir_cupom" class="btn btn-warning" type="button">Inserir Cupom</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div id="div_forma_pagamento_dinheiro" class="row">
                            <div class="col-md-6">
                                <p class="help-block">Forma de Pagamento</p>
                                <div id="div_forma_pagamento" class="form-group">
                                    <select id="forma_pagamento" name="forma_pagamento" class="form-control">
                                        <option value="">Escolha a forma de pagamento</option>
                                        <?php
                                        $sql = $db->prepare("SELECT id, nome
                                                 FROM forma_pagamentos
                                                 WHERE status = 1
                                                 ORDER BY nome ASC");
                                        $sql->execute();
                                        while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                            <option value="<?php echo $linha['id']; ?>"><?php echo $linha['nome']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div id="div_pagamento_dinheiro_1" class="col-md-3" style="display: none">
                                <p class="help-block">Valor Pago</p>
                                <div id="div_valor_pagar" class="form-group">
                                    <input onkeyup="valor_a_pagar()" type="text" id="valor_pagar" name="valor_pagar" class="form-control" placeholder="Troco para quanto?" value="" />
                                </div>
                            </div>
                            <div id="div_pagamento_dinheiro_2" class="col-md-3" style="display: none">
                                <p class="help-block">Troco a Receber</p>
                                <div id="div_troco_receber" class="form-group">
                                    <input readonly="true" type="text" id="valor_troco" name="valor_troco" value="0,00"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <table id="total_dinheiro">
                    <tbody>
                        <tr>
                            <td class="text-right">
                                <strong>Sub-Total:</strong>
                            </td>
                            <td class="text-right">
                                R$ <text id="sub_total"><?php echo fdec($subtotalgeral); ?></text>             
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                <strong>Desconto Cupom:</strong>
                            </td>
                            <td class="text-right text-danger">
                                - R$ <text id="desconto_cupom"><?= isset($_SESSION['cupom_valor']) ? $_SESSION['cupom_valor'] : '0,00'; ?></text>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                <strong>Taxa de Entrega:</strong>
                            </td>
                            <td class="text-right">
                                R$ <text id="taxa_entrega_valor">0,00</text>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                <strong>Total:</strong>
                            </td>
                            <td class="text-right">
                                <strong>R$ <text id="total_valor"><?php echo fdec(isset($_SESSION['cupom_valor']) ? ($subtotalgeral - valorfloat($_SESSION['cupom_valor'])) : $subtotalgeral); ?></text></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table id="total_pontos" style="display: none">
                    <tbody>
                        <tr>
                            <td class="text-right">
                                <strong>Sub-Total:</strong>
                            </td>
                            <td class="text-right">
                                <text id="subtotal_pontos"><?php echo $subpontosgeral; ?></text> pontos               
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                <strong>Desconto Cupom:</strong>
                            </td>
                            <td class="text-right text-danger">
                                - <text id="desconto_cupom_pontos"><?= isset($_SESSION['cupom_pontos']) ? $_SESSION['cupom_pontos'] : '0'; ?></text> pontos
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                <strong>Taxa de Entrega:</strong>
                            </td>
                            <td class="text-right">
                                <text id="taxa_entrega_pontos">0</text> pontos
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                <strong>Total:</strong>
                            </td>
                            <td id="total_pontos" class="text-right">
                                <strong><text id="total_pontos"><?php echo isset($_SESSION['cupom_pontos']) ? ($subpontosgeral - $_SESSION['cupom_pontos']) : $subpontosgeral; ?></text> pontos</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="buttons">
            <div class="text-right">
                <button id="finalizar_compra" class="btn btn-success" type="submit"><i class="fa fa-credit-card"></i> <span>Finalizar Compra</span></button>

                <a href="<?= PORTAL_URL; ?>view/cardapios/index.php" class="btn btn-warning">
                    Continuar Comprando <i class="fa fa-long-arrow-right"></i>
                </a>
            </div>
        </div>
        <input type="hidden" id="vlr_total_dinheiro" name="vlr_total_dinheiro" value="<?php echo fdec(isset($_SESSION['cupom_valor']) ? ($subtotalgeral - valorfloat($_SESSION['cupom_valor'])) : $subtotalgeral); ?>"/>
        <input type="hidden" id="vlr_total_pontos" name="vlr_total_pontos" value="<?php echo isset($_SESSION['cupom_pontos']) ? ($subpontosgeral - $_SESSION['cupom_pontos']) : $subpontosgeral; ?>"/>
    </form>
</div>

<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape_cardapio.php'); ?>

<!-- JS DA LISTA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/cardapios/carrinho.js"></script>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Para Continuar é Necessário Fazer Login no Sistema</h4>
            </div>
            <form id="form_login" action="#" method="post">
                <div class="modal-body">
                    <div id="div_email" class="form-group has-feedback">
                        <input id="email" name="email" type="email" class="form-control" placeholder="E-mail"/>
                        <span class="fa fa-envelope-o form-control-feedback"></span>
                    </div>
                    <div id="div_senha" class="form-group has-feedback">
                        <input id="senha" name="senha" type="password" class="form-control" placeholder="Senha"/>
                        <span class="fa fa-key form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">    
                            <a href="<?= PORTAL_URL; ?>redefinir.php">Esqueci minha senha</a>                       
                        </div><!-- /.col -->
                    </div>

                </div>
                <div class="modal-footer">
                    <button onclick="entrar()" type="button" class="btn btn-primary">Entrar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

