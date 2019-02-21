<div class="col-md-5">
    <div class="itens-cardapio">

        <input type="hidden" id="produto_selecionado" name="produto_selecionado" value="<?php echo isset($_SESSION['produto_selecionado']) ? $_SESSION['produto_selecionado'] : '0'; ?>"/>
        
        <input type="hidden" id="menu_selecionado" name="menu_selecionado" value="<?php echo isset($_SESSION['menu_selecionado']) ? $_SESSION['menu_selecionado'] : ''; ?>"/>

        <?php
        $numero_sessao = session_id();
        $subtotalgeral3 = 0;
        $subpontosgeral3 = 0;
        $subtotal3 = 0;
        $subpontos3 = 0;
        $sql = $db->prepare("SELECT pi.observacao, pi.id AS pedidos_itens_id, pr.pontuacao_cobrada, pr.id AS produto_id, pi.quantidade, pe.id, pr.nome AS produto, pr.foto_cortada, pr.valor
                                 FROM pedidos_itens pi
                                 LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                                 LEFT JOIN produtos AS pr ON pr.id = pi.produto_id
                                 WHERE pe.status = 0 AND pe.numero_sessao = ?");
        $sql->bindValue(1, $numero_sessao);
        $sql->execute();
        $qtd_pedidos = $sql->rowCount();
        if ($qtd_pedidos == 0) {
          echo "Nenhum pedido cadastrado.";
        } else {
          while ($pedidos = $sql->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <input type="hidden" id="pedido_id" name="pedido_id" value="<?php echo $pedidos['id']; ?>"/>
            <?php
            $subtotal3 = 0;
            $subpontos3 = 0;

            $sql5 = $db->prepare("SELECT pi.id AS pedidos_itens_id, i.nome, i.add_valor, i.add_pontos
                                             FROM ingredientes i
                                             LEFT JOIN pedidos_itens_ingredientes AS pii ON pii.ingrediente_id = i.id
                                             LEFT JOIN pedidos_itens AS pi ON pi.id = pii.pedidos_itens_id
                                             WHERE pi.produto_id = ? AND pi.pedido_id = ? AND pi.id = ?
                                             GROUP BY i.id");
            $sql5->bindValue(1, $pedidos['produto_id']);
            $sql5->bindValue(2, $pedidos['id']);
            $sql5->bindValue(3, $pedidos['pedidos_itens_id']);
            $sql5->execute();
            while ($pedidos_itens_calc = $sql5->fetch(PDO::FETCH_ASSOC)) {
              $subtotal3 += $pedidos['quantidade'] == 1 ? $pedidos_itens_calc['add_valor'] : ($pedidos_itens_calc['add_valor'] * $pedidos['quantidade']);
              $subpontos3 += $pedidos['quantidade'] == 1 ? $pedidos_itens_calc['add_pontos'] : ($pedidos_itens_calc['add_pontos'] * $pedidos['quantidade']);
            }
            ?>

            <div id="click_produto" class="item <?php echo isset($_SESSION['produto_selecionado']) && $_SESSION['produto_selecionado'] == $pedidos['pedidos_itens_id'] ? 'active' : ''; ?>">
                <div onclick="produto_selecionado(<?php echo $pedidos['pedidos_itens_id']; ?>)" class="row">
                    <div class="col-xs-5">
                        <?php echo ctexto($pedidos['produto'], "mai"); ?>
                    </div>
                    <div class="col-xs-2 text-right">
                        <input onchange="atualizar_qtd(this, '<?php echo $pedidos['pedidos_itens_id']; ?>')" class="form-control" type="text" id="quantidade_item" name="quantidade_item[]" value="<?php echo $pedidos['quantidade']; ?>" size="1" maxlength="3">
                    </div>
                    <div class="col-xs-2 text-right">
                        R$ <?php echo fdec($pedidos['valor']); ?>
                    </div>
                    <div class="col-xs-2 text-right">
                        R$ <?php echo fdec(($subtotal3 + ($pedidos['valor'] * $pedidos['quantidade']))); ?>
                    </div>
                    <div class="col-xs-1">
                        <a onclick="remover_item_atendimento(<?php echo $pedidos['pedidos_itens_id']; ?>)" href="#" class="text-danger"><i class="fa fa-trash"></i></a>
                    </div>
                </div>

                <?php
                $subtotalgeral3 += ($subtotal3 + ($pedidos['valor'] * $pedidos['quantidade']));

                $sql3 = $db->prepare("SELECT pii.id AS pedidos_itens_ingredientes_id, i.id, i.nome, i.add_valor, i.add_pontos
                                             FROM ingredientes i
                                             LEFT JOIN pedidos_itens_ingredientes AS pii ON pii.ingrediente_id = i.id
                                             LEFT JOIN pedidos_itens AS pi ON pi.id = pii.pedidos_itens_id
                                             WHERE pi.produto_id = ? AND pi.pedido_id = ? AND pi.id = ?
                                             GROUP BY i.id");
                $sql3->bindValue(1, $pedidos['produto_id']);
                $sql3->bindValue(2, $pedidos['id']);
                $sql3->bindValue(3, $pedidos['pedidos_itens_id']);
                $sql3->execute();
                while ($pedidos_itens = $sql3->fetch(PDO::FETCH_ASSOC)) {
                  ?>

                  <div class="subitem">
                      <div onclick="produto_selecionado(<?php echo $pedidos['pedidos_itens_id']; ?>)" class="row">
                          <div class="col-xs-7">
                              <i class="fa fa-angle-up"></i> <?php echo ctexto($pedidos_itens['nome'], "mai"); ?>
                          </div>
                          <div class="col-xs-2 text-right">
                              R$ <?php echo fdec($pedidos_itens['add_valor']); ?>
                          </div>
                          <div class="col-xs-2 text-right">
                              R$ <?php echo fdec($pedidos['quantidade'] == 1 ? $pedidos_itens['add_valor'] : ($pedidos_itens['add_valor'] * $pedidos['quantidade'])); ?>
                          </div>
                          <div class="col-xs-1">
                              <a onclick="remover_adicional_atendimento(<?php echo $pedidos_itens['pedidos_itens_ingredientes_id']; ?>)" href="#" class="text-danger"><i class="fa fa-trash"></i></a>
                          </div>
                      </div>
                  </div>

                  <?php
                }
                ?>

            </div>

            <?php
          }
        }
        ?>
    </div>

</div>