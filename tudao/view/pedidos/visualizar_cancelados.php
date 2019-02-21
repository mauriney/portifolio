<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo.php');

$id = antiSQL(@$_GET['id']);

if (is_numeric($id)) {//VERIFICA SE O ID PASSADO POR PARÂMETRO É UM NÚMERO
    $sql = $db->prepare("SELECT p.status, pesc.nome AS confirmador, pesf.nome AS finalizador, pes.nome AS funcionario, fp.id AS forma_pagamento_id, fp.nome AS forma_pagamento, p.valor_pagamento as pago, p.valor_troco as troco, c.codigo AS cupom, p.id, p.mesa, b.nome AS bairro, p.endereco, p.complemento, p.numero, p.contato
                             FROM pedidos p
                             LEFT JOIN bairros AS b ON b.id = p.bairro_id
                             LEFT JOIN cupons AS c ON c.id = p.cupom_id
                             LEFT JOIN forma_pagamentos AS fp ON fp.id = p.forma_pagamento
                             LEFT JOIN pessoas AS pes ON pes.id = p.funcionario_id
                             LEFT JOIN pessoas AS pesc ON pesc.id = p.confirmador
                             LEFT JOIN pessoas AS pesf ON pesf.id = p.finalizador
                             WHERE p.id = ?");
    $sql->bindParam(1, $id);
    $sql->execute();
    $reg = $sql->fetch(PDO::FETCH_BOTH);
} else {
    $reg = 0;
    $id = "";
}
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
                <small>Pedido</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo PORTAL_URL; ?>view/geral/modulos.php"><i class="fa fa-dashboard"></i> <b>Módulos</b></a></li>
                <li><a href="<?php echo PORTAL_URL; ?>view/clientes/index.php"> <b>Clientes</b></a></li>
                <li class="active">Visualização</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <h4 class="box-title">Visualização do Pedido</h4><br>

         <div class="row">

                <div id="div_forma_pagamento" class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-bell"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">STIUAÇÃO?</span>
                            <span class="info-box-number"><?php echo $reg['status'] == 1 ? 'Em Atendimento' : ($reg['status'] == 2 ? 'Pronto para Entrega' : ($reg['status'] == 3 ? 'Enviado para Entrega' : ($reg['status'] == 4 ? 'Concluído' : ($reg['status'] == 5 ? 'Cancelado' : 'Nenhuma Situação')))); ?></span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div>

                <div id="div_forma_pagamento" class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-credit-card"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">MODO DE PAGAMENTO?</span>
                            <span class="info-box-number"><?php echo $reg['forma_pagamento']; ?></span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div>

                <div id="div_pagamento_dinheiro_1" <?php echo $reg['forma_pagamento_id'] == 1 ? '' : 'style="display: none"'; ?> class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>
                        <div class="info-box-content" id="div_valor_pagar">
                            <span class="info-box-text">VALOR A PAGAR</span>
                            <span class="info-box-number"><?php echo fdec($reg['pago']); ?></span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div>

                <div id="div_pagamento_dinheiro_2" <?php echo $reg['forma_pagamento_id'] == 1 ? '' : 'style="display: none"'; ?> class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>
                        <div class="info-box-content" id="div_troco_receber">
                            <span class="info-box-text">TROCO A RECEBER</span>
                            <span class="info-box-number"><?php echo fdec($reg['troco']); ?></span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div>
            </div>

            <div class="box box-danger">

                <div class="box-body">
                    <div class="cart-info table-responsive">
                        <table class="table table-bordered">
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
                                 WHERE pe.id = ?
                                 GROUP BY pi.id
                                 ORDER BY pr.nome ASC");
                                $sql->bindValue(1, $id);
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

                                    $sql3 = $db->prepare("SELECT i.nome, i.add_valor, i.add_pontos
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
                                                <?php echo $pedidos['quantidade']; ?>
                                            </div>
                                        </td>
                                        <td class="obs">
                                            <?php echo $pedidos['observacao']; ?>
                                        </td>
                                        <td class="price">
                                            <div <?php echo $reg['forma_pagamento_id'] == 3 ? 'style="display: none"' : ''; ?> id="div_valor">
                                                R$ <?php echo fdec($pedidos['valor']); ?>
                                            </div>
                                            <div <?php echo $reg['forma_pagamento_id'] == 3 ? '' : 'style="display: none"'; ?> id="div_pontuacao">
                                                <?php echo $pedidos['pontuacao_cobrada']; ?> pontos
                                            </div>
                                        </td>
                                        <td class="total">
                                            <?php
                                            $subtotal += ($pedidos['valor'] * $pedidos['quantidade']);
                                            $subpontos += ($pedidos['pontuacao_cobrada'] * $pedidos['quantidade']);

                                            $subtotalgeral += $subtotal;
                                            $subpontosgeral += $subpontos;
                                            ?>
                                            <div <?php echo $reg['forma_pagamento_id'] == 3 ? 'style="display: none"' : ''; ?> id="div_subtotal">
                                                R$ <?php echo fdec($subtotal); ?>
                                            </div>
                                            <div <?php echo $reg['forma_pagamento_id'] == 3 ? '' : 'style="display: none"'; ?> id="div_subpontos">
                                                <?php echo $subpontos; ?> pontos
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="panel-heading">
                        <h3 class="panel-title">Local de Entrega</h3>
                    </div>

                    <div <?php echo $reg['mesa'] == NULL ? 'style="display: none"' : ''; ?> id="div_numero_mesa" class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="callout callout-info">
                                    Mesa Número: <strong style="font-size: 18px"><?php echo $reg['mesa']; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div <?php echo $reg['mesa'] == NULL ? '' : 'style="display: none"'; ?> class="panel panel-default">
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="div_bairro" class="form-group">
                                        <label class="control-label" for="district">Bairro: </label>
                                        <?php echo $reg['bairro']; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <div id="div_endereco" class="form-group">
                                        <label for="endereco">Endereço: </label>
                                        <?php echo $reg['endereco']; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div id="div_numero_casa" class="form-group">
                                        <label for="numero">Número: </label>
                                        <?php echo $reg['numero']; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="complemento">Complemento: </label>
                                        <?php echo $reg['complemento']; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div id="div_contato" class="form-group">
                                        <label for="contato">Telefone de Contato: </label>
                                        <?php echo $reg['contato']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div <?php echo $reg['cupom'] == NULL || $reg['cupom'] == "" ? 'style="display: none"' : ''; ?> id="div_cupom" class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label class="control-label" for="district">
                                        Cupom de Desconto:
                                    </label>
                                    <?php echo $reg['cupom']; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row cart-total">
                        <div class="col-md-12">
                            <table id="total_dinheiro" style="float: right; <?php echo $reg['forma_pagamento_id'] == 3 ? 'display: none' : ''; ?>">
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
                            <table id="total_pontos" style="float: right; <?php echo $reg['forma_pagamento_id'] == 3 ? '' : 'display: none'; ?>">
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
                                            <strong><text id="total_pontos"><?php echo $subpontosgeral; ?></text> pontos</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br/>
                    <div class="row cart-total">
                        <div <?php echo $reg['funcionario'] != "" ? "" : "style='display: none'"; ?> class="col-md-4">
                            <label>Atendimento</label>
                            <div>
                                <?php echo $reg['funcionario']; ?>
                            </div>
                        </div>
                        <div <?php echo $reg['confirmador'] != "" ? "" : "style='display: none'"; ?> class="col-md-4">
                            <label>Confirmado</label>
                            <div>
                                <?php echo $reg['confirmador']; ?>
                            </div>
                        </div>
                        <div <?php echo $reg['finalizador'] != "" ? "" : "style='display: none'"; ?> class="col-md-4">
                            <label>Finalizado</label>
                            <div>
                                <?php echo $reg['finalizador']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.box -->

        </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class='control-sidebar-bg'></div>
</div><!-- ./wrapper -->

<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape.php'); ?>