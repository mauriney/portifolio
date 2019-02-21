<?php
$numero_sessao = session_id();
$sql = $db->prepare("SELECT pi.observacao, pi.id AS pedidos_itens_id, pr.pontuacao_cobrada, pr.id AS produto_id, pi.quantidade, pe.id, pr.nome AS produto, pr.foto_cortada, pr.valor
                     FROM pedidos_itens pi
                     LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                     LEFT JOIN produtos AS pr ON pr.id = pi.produto_id
                     WHERE pe.status = 0 AND pe.numero_sessao = ?
                     ORDER BY pr.nome ASC");
$sql->bindValue(1, $numero_sessao);
$sql->execute();
$qtd_pedidos = $sql->rowCount();
?>
<header class="pdv">
    <div class="row">
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-8">
                    <a <?= $qtd_pedidos == 0 ? 'style="display: none"' : ''; ?> id="nome_cliente" href="#" class="nome_cliente">
                        <?php
                        $sql = $db->prepare("SELECT c.id, p.nome AS cliente, p.contato1, p.contato2, m.numero AS mesa, m.id AS mesa_id 
                             FROM pedidos pe
                             LEFT JOIN clientes AS c ON c.id = pe.cliente_id
                             LEFT JOIN pessoas AS p ON p.id = c.pessoa_id
                             LEFT JOIN mesas AS m ON m.id = pe.mesa
                             WHERE pe.numero_sessao = ? AND pe.status = 0");
                        $sql->bindValue(1, session_id());
                        $sql->execute();
                        $cliente = $sql->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <i <?php echo isset($cliente['id']) && is_numeric($cliente['id']) ? 'style="display: none"' : ''; ?> class="fa fa-plus"></i>
                        <?php echo isset($cliente['id']) && is_numeric($cliente['id']) ? $cliente['cliente'] : '<br>ADICIONAR CLIENTE'; ?>
                        <?php echo isset($cliente['id']) && is_numeric($cliente['id']) ? "<br/>" . ($cliente['contato1'] != NULL && $cliente['contato1'] != "" ? $cliente['contato1'] : $cliente['contato2']) : ''; ?>
                    </a>
                    <a id="remover_cliente" <?php echo isset($cliente['id']) && is_numeric($cliente['id']) ? '' : 'style="display: none"'; ?> onclick="remover_cliente()" href="#" class="text-danger apagar-cliente">
                        <i class="fa fa-trash"></i>
                    </a>
                    <input type="hidden" id="cliente_id" name="cliente_id" value="<?php echo isset($cliente['id']) && is_numeric($cliente['id']) ? $cliente['id'] : ''; ?>"/>
                </div>
                <div class="col-md-4">
                    <a <?= $qtd_pedidos == 0 ? 'style="display: none"' : ''; ?> id="nome_mesa" href="#" class="mesa">
                        <i <?php echo isset($cliente['mesa_id']) && is_numeric($cliente['mesa_id']) ? 'style="display: none"' : ''; ?> class="fa fa-cutlery"></i>
                        <?php echo isset($cliente['mesa_id']) && is_numeric($cliente['mesa_id']) ? $cliente['mesa'] : '<br>SEM MESA'; ?>
                    </a>
                    <br/>
                    <a id="remover_mesa" <?php echo isset($cliente['mesa_id']) && is_numeric($cliente['mesa_id']) ? '' : 'style="display: none"'; ?> onclick="remover_mesa()" href="#" class="text-danger apagar-mesa">
                        <i class="fa fa-trash"></i>
                    </a>
                    <input type="hidden" id="mesa_id" name="mesa_id" value="<?php echo isset($cliente['mesa_id']) && is_numeric($cliente['mesa_id']) ? $cliente['mesa_id'] : ''; ?>"/>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <h2><?php echo ctexto($_SESSION['estabelecimento'], "mai"); ?></h2>  
        </div>
        <div class="col-md-2 text-right">
            <a id="a_menu" href="<?= PORTAL_URL; ?>view/geral/modulos.php">
                <i class="fa fa-reply"></i><br>
                Painel Admin
            </a>
        </div>
    </div>
</header>