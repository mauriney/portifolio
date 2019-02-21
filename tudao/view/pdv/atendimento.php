<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo.php');
?>

<?php include('header_pdv.php'); ?>

<main>
    <div class="row">

        <?php include('menu_lateral.php'); ?>

        <div class="col-md-7">

            <div class="tabbing">
                <ul id="ul_menu" class="tabNav flex flex-wrap">
                    <li onclick="menu(1)" class="<?= isset($_SESSION['menu_selecionado']) && $_SESSION['menu_selecionado'] == 1 ? 'active' : (!isset($_SESSION['menu_selecionado']) || $_SESSION['menu_selecionado'] == '' ? 'active' : '') ?>" <?= is_numeric(pesquisar_tabela("id", "categorias", "id", "=", "2", "AND status = 1")) ? '' : 'style="display: none"' ?> id="sanduiche">
                        <a href="#tab1">
                            <img src="<?= PORTAL_URL; ?>assets/img/menu/sanduiche1.png" alt="">
                            SANDUICHES
                        </a>
                    </li>
                    <li onclick="menu(2)" class="<?= isset($_SESSION['menu_selecionado']) && $_SESSION['menu_selecionado'] == 2 ? 'active' : '' ?>" <?= is_numeric(pesquisar_tabela("id", "categorias", "id", "=", "1", "AND status = 1")) ? '' : 'style="display: none"' ?> id="bebidas">
                        <a href="#tab2">
                            <img src="<?= PORTAL_URL; ?>assets/img/menu/cocacola.png" alt="">
                            BEBIDAS
                        </a>
                    </li>
                    <li onclick="menu(3)" class="<?= isset($_SESSION['menu_selecionado']) && $_SESSION['menu_selecionado'] == 3 ? 'active' : '' ?>" <?= is_numeric(pesquisar_tabela("id", "categorias", "id", "=", "3", "AND status = 1")) ? '' : 'style="display: none"' ?> id="sucos">
                        <a href="#tab3">
                            <img src="<?= PORTAL_URL; ?>assets/img/menu/sucos.png" alt="">
                            SUCOS
                        </a>
                    </li>
                    <li onclick="menu(4)" class="<?= isset($_SESSION['menu_selecionado']) && $_SESSION['menu_selecionado'] == 4 ? 'active' : '' ?>" <?= is_numeric(pesquisar_tabela("id", "categorias", "id", "=", "6", "AND status = 1")) ? '' : 'style="display: none"' ?> id="doces">
                        <a href="#tab4">
                            <img src="<?= PORTAL_URL; ?>assets/img/menu/doces.png" alt="">
                            DOCES
                        </a>
                    </li>
                    <li onclick="menu(7)" class="<?= isset($_SESSION['menu_selecionado']) && $_SESSION['menu_selecionado'] == 7 ? 'active' : '' ?>" <?= is_numeric(pesquisar_tabela("id", "categorias", "id", "=", "5", "AND status = 1")) ? '' : 'style="display: none"' ?> id="acai">
                        <a href="#tab7">
                            <img src="<?= PORTAL_URL; ?>assets/img/menu/acais.png" alt="">
                            AÇAÍS
                        </a>
                    </li>
                    <li onclick="menu(8)" class="<?= isset($_SESSION['menu_selecionado']) && $_SESSION['menu_selecionado'] == 8 ? 'active' : '' ?>" <?= is_numeric(pesquisar_tabela("id", "categorias", "id", "=", "9", "AND status = 1")) ? '' : 'style="display: none"' ?> id="empadinha">
                        <a href="#tab8">
                            <img src="<?= PORTAL_URL; ?>assets/img/menu/empada.png" alt="">
                            EMPADINHAS
                        </a>
                    </li>
                    <!-- <li onclick="menu(9)" class="<?= isset($_SESSION['menu_selecionado']) && $_SESSION['menu_selecionado'] == 9 ? 'active' : '' ?>" <?= is_numeric(pesquisar_tabela("id", "categorias", "id", "=", "7", "AND status = 1")) ? '' : 'style="display: none"' ?> id="entradas">
                        <a href="#tab9">
                            <img src="http://www.asiaexpress.com.br/images/rolinho-primavera.png" alt="">
                            ENTRADAS
                        </a>
                    </li> -->
                    <li onclick="menu(10)" class="<?= isset($_SESSION['menu_selecionado']) && $_SESSION['menu_selecionado'] == 10 ? 'active' : '' ?>" <?= is_numeric(pesquisar_tabela("id", "categorias", "id", "=", "10", "AND status = 1")) ? '' : 'style="display: none"' ?> id="picoles">
                        <a href="#tab10">
                            <img src="<?= PORTAL_URL; ?>assets/img/menu/picoles.png" alt="">
                            PICOLÉS
                        </a>
                    </li>
                    <li onclick="menu(11)" class="<?= isset($_SESSION['menu_selecionado']) && $_SESSION['menu_selecionado'] == 11 ? 'active' : '' ?>" <?= is_numeric(pesquisar_tabela("id", "categorias", "id", "=", "8", "AND status = 1")) ? '' : 'style="display: none"' ?> id="sopas">
                        <a href="#tab11">
                            <img src="<?= PORTAL_URL; ?>assets/img/menu/soup.png" alt="">
                            SOPAS
                        </a>
                    </li>
                    <li onclick="menu(12)" class="<?= isset($_SESSION['menu_selecionado']) && $_SESSION['menu_selecionado'] == 12 ? 'active' : '' ?>" <?= is_numeric(pesquisar_tabela("id", "categorias", "id", "=", "4", "AND status = 1")) ? '' : 'style="display: none"' ?> id="executivos">
                        <a href="#tab12">
                            <img src="<?= PORTAL_URL; ?>assets/img/menu/executivos.png" alt="">
                            EXCECUTIVOS
                        </a>
                    </li>
                    <li onclick="menu(13)" class="<?= isset($_SESSION['menu_selecionado']) && $_SESSION['menu_selecionado'] == 13 ? 'active' : '' ?>" id="adicionais">
                        <a href="#tab13">
                            <img src="<?= PORTAL_URL; ?>assets/img/menu/adicionais.png" alt="">
                            ADICIONAIS
                        </a>
                    </li>
                </ul>
                <div class="tabContainer">
                    <div class="tabContent" id="tab1">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT pe.id AS pedido_id, p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.categoria_id = 2 AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
                            $sql->execute();
                            if ($sql->rowCount() == 0) {
                              echo "<div class='col-md-12 col-sm-12 col-xs-12'>Nenhum item cadastrado.</div>";
                            }
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>

                              <div class="col-md-3 col-sm-4 col-xs-12">
                                  <div onclick="add_produto(<?php echo $linha['id']; ?>)" class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['nome'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xs-5">
                                              <figure>
                                                  <img src="<?= PORTAL_URL . $linha['foto_cortada']; ?>" title="<?php echo $linha['nome']; ?>" alt="<?php echo $linha['nome']; ?>" />
                                              </figure>
                                          </div>
                                          <div class="col-xs-7">
                                              <h2>PREÇO <strong>R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></strong></h2>
                                              <h3>1 UN</h3>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="tabContent" id="tab2">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.categoria_id = 1 AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
                            $sql->execute();
                            if ($sql->rowCount() == 0) {
                              echo "<div class='col-md-12 col-sm-12 col-xs-12'>Nenhum item cadastrado.</div>";
                            }
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>
                              <div class="col-md-3 col-sm-4 col-xs-12">
                                  <div onclick="add_produto(<?php echo $linha['id']; ?>)" class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['nome'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xs-5">
                                              <figure>
                                                  <img src="<?= PORTAL_URL . $linha['foto_cortada']; ?>" title="<?php echo $linha['nome']; ?>" alt="<?php echo $linha['nome']; ?>" />
                                              </figure>
                                          </div>
                                          <div class="col-xs-7">
                                              <h2>PREÇO <strong>R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></strong></h2>
                                              <h3>1 UN</h3>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="tabContent" id="tab3">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.categoria_id = 3 AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
                            $sql->execute();
                            if ($sql->rowCount() == 0) {
                              echo "<div class='col-md-12 col-sm-12 col-xs-12'>Nenhum item cadastrado.</div>";
                            }
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>

                              <div class="col-md-3 col-sm-4 col-xs-12">
                                  <div onclick="add_produto(<?php echo $linha['id']; ?>)" class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['nome'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xs-5">
                                              <figure>
                                                  <img src="<?= PORTAL_URL . $linha['foto_cortada']; ?>" title="<?php echo $linha['nome']; ?>" alt="<?php echo $linha['nome']; ?>" />
                                              </figure>
                                          </div>
                                          <div class="col-xs-7">
                                              <h2>PREÇO <strong>R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></strong></h2>
                                              <h3>1 UN</h3>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <?php
                            }
                            ?>
                        </div>    
                    </div>
                    <div class="tabContent" id="tab4">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.categoria_id = 6 AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
                            $sql->execute();
                            if ($sql->rowCount() == 0) {
                              echo "<div class='col-md-12 col-sm-12 col-xs-12'>Nenhum item cadastrado.</div>";
                            }
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>

                              <div class="col-md-3 col-sm-4 col-xs-12">
                                  <div onclick="add_produto(<?php echo $linha['id']; ?>)" class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['nome'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xs-5">
                                              <figure>
                                                  <img src="<?= PORTAL_URL . $linha['foto_cortada']; ?>" title="<?php echo $linha['nome']; ?>" alt="<?php echo $linha['nome']; ?>" />
                                              </figure>
                                          </div>
                                          <div class="col-xs-7">
                                              <h2>PREÇO <strong>R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></strong></h2>
                                              <h3>1 UN</h3>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <?php
                            }
                            ?>
                        </div>    
                    </div>
                    <div class="tabContent" id="tab5">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Contato1</th>
                                    <th>Contato2</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = $db->prepare("SELECT c.id, p.id AS pessoa_id, c.status, p.nome, p.contato1, p.contato2, p.email
                             FROM clientes c
                             LEFT JOIN pessoas AS p ON p.id = c.pessoa_id
                             WHERE c.status = 1
                             ORDER BY p.nome ASC");
                                $sql->execute();
                                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                  if (ver_nivel(1, 1) && $_SESSION['id'] == $linha['pessoa_id'] || ver_nivel(1, 2)) {
                                    ?>
                                    <tr style="cursor: pointer" onclick="escolha_cliente('<?php echo $linha['id']; ?>', '<?php echo $linha['nome']; ?>', '<?php echo $linha['contato1']; ?>', '<?php echo $linha['contato2']; ?>')">
                                        <td><?php echo $linha['id']; ?></td>
                                        <td><?php echo $linha['nome']; ?></td>
                                        <td><?php echo $linha['email']; ?></td>
                                        <td><?php echo $linha['contato1']; ?></td>
                                        <td><?php echo $linha['contato2']; ?></td>
                                    </tr>
                                    <?php
                                  }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tabContent" id="tab6">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT id, numero
                             FROM mesas
                             WHERE status = 1");
                            $sql->execute();
                            if ($sql->rowCount() == 0) {
                              echo "<div class='col-md-12 col-sm-12 col-xs-12'>Nenhum item cadastrado.</div>";
                            }
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>

                              <div onclick="escolha_mesa('<?php echo $linha['id']; ?>', '<?php echo $linha['numero']; ?>')" class="col-md-3 col-sm-4 col-xs-12">
                                  <div class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['numero'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <?php
                            }
                            ?>
                        </div>    
                    </div>
                    <div class="tabContent" id="tab7">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.categoria_id = 5 AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
                            $sql->execute();
                            if ($sql->rowCount() == 0) {
                              echo "<div class='col-md-12 col-sm-12 col-xs-12'>Nenhum item cadastrado.</div>";
                            }
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>

                              <div class="col-md-3 col-sm-4 col-xs-12">
                                  <div onclick="add_produto(<?php echo $linha['id']; ?>)" class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['nome'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xs-5">
                                              <figure>
                                                  <img src="<?= PORTAL_URL . $linha['foto_cortada']; ?>" title="<?php echo $linha['nome']; ?>" alt="<?php echo $linha['nome']; ?>" />
                                              </figure>
                                          </div>
                                          <div class="col-xs-7">
                                              <h2>PREÇO <strong>R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></strong></h2>
                                              <h3>1 UN</h3>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <?php
                            }
                            ?>
                        </div>    
                    </div>
                    <div class="tabContent" id="tab8">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.categoria_id = 9 AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
                            $sql->execute();
                            if ($sql->rowCount() == 0) {
                              echo "<div class='col-md-12 col-sm-12 col-xs-12'>Nenhum item cadastrado.</div>";
                            }
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>

                              <div class="col-md-3 col-sm-4 col-xs-12">
                                  <div onclick="add_produto(<?php echo $linha['id']; ?>)" class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['nome'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xs-5">
                                              <figure>
                                                  <img src="<?= PORTAL_URL . $linha['foto_cortada']; ?>" title="<?php echo $linha['nome']; ?>" alt="<?php echo $linha['nome']; ?>" />
                                              </figure>
                                          </div>
                                          <div class="col-xs-7">
                                              <h2>PREÇO <strong>R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></strong></h2>
                                              <h3>1 UN</h3>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <?php
                            }
                            ?>
                        </div>    
                    </div>
                    <div class="tabContent" id="tab9">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.categoria_id = 7 AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
                            $sql->execute();
                            if ($sql->rowCount() == 0) {
                              echo "<div class='col-md-12 col-sm-12 col-xs-12'>Nenhum item cadastrado.</div>";
                            }
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>

                              <div class="col-md-3 col-sm-4 col-xs-12">
                                  <div onclick="add_produto(<?php echo $linha['id']; ?>)" class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['nome'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xs-5">
                                              <figure>
                                                  <img src="<?= PORTAL_URL . $linha['foto_cortada']; ?>" title="<?php echo $linha['nome']; ?>" alt="<?php echo $linha['nome']; ?>" />
                                              </figure>
                                          </div>
                                          <div class="col-xs-7">
                                              <h2>PREÇO <strong>R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></strong></h2>
                                              <h3>1 UN</h3>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <?php
                            }
                            ?>
                        </div>    
                    </div>
                    <div class="tabContent" id="tab10">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.categoria_id = 10 AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
                            $sql->execute();
                            if ($sql->rowCount() == 0) {
                              echo "<div class='col-md-12 col-sm-12 col-xs-12'>Nenhum item cadastrado.</div>";
                            }
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>

                              <div class="col-md-3 col-sm-4 col-xs-12">
                                  <div onclick="add_produto(<?php echo $linha['id']; ?>)" class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['nome'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xs-5">
                                              <figure>
                                                  <img src="<?= PORTAL_URL . $linha['foto_cortada']; ?>" title="<?php echo $linha['nome']; ?>" alt="<?php echo $linha['nome']; ?>" />
                                              </figure>
                                          </div>
                                          <div class="col-xs-7">
                                              <h2>PREÇO <strong>R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></strong></h2>
                                              <h3>1 UN</h3>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <?php
                            }
                            ?>
                        </div>    
                    </div>
                    <div class="tabContent" id="tab11">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.categoria_id = 8 AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
                            $sql->execute();
                            if ($sql->rowCount() == 0) {
                              echo "<div class='col-md-12 col-sm-12 col-xs-12'>Nenhum item cadastrado.</div>";
                            }
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>

                              <div class="col-md-3 col-sm-4 col-xs-12">
                                  <div onclick="add_produto(<?php echo $linha['id']; ?>)" class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['nome'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xs-5">
                                              <figure>
                                                  <img src="<?= PORTAL_URL . $linha['foto_cortada']; ?>" title="<?php echo $linha['nome']; ?>" alt="<?php echo $linha['nome']; ?>" />
                                              </figure>
                                          </div>
                                          <div class="col-xs-7">
                                              <h2>PREÇO <strong>R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></strong></h2>
                                              <h3>1 UN</h3>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <?php
                            }
                            ?>
                        </div>    
                    </div>
                    <div class="tabContent" id="tab12">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT p.foto_cortada, p.id, p.nome, p.valor, p.pontuacao_cobrada, p.categoria_id, p.status
                             FROM produtos p
                             LEFT JOIN pedidos_itens AS pi ON pi.produto_id = p.id
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             WHERE p.categoria_id = 4 AND p.status = 1
                             GROUP BY p.id
                             ORDER BY p.nome ASC");
                            $sql->execute();
                            if ($sql->rowCount() == 0) {
                              echo "<div class='col-md-12 col-sm-12 col-xs-12'>Nenhum item cadastrado.</div>";
                            }
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>

                              <div class="col-md-3 col-sm-4 col-xs-12">
                                  <div onclick="add_produto(<?php echo $linha['id']; ?>)" class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['nome'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xs-5">
                                              <figure>
                                                  <img src="<?= PORTAL_URL . $linha['foto_cortada']; ?>" title="<?php echo $linha['nome']; ?>" alt="<?php echo $linha['nome']; ?>" />
                                              </figure>
                                          </div>
                                          <div class="col-xs-7">
                                              <h2>PREÇO <strong>R$ <?php echo fdec($linha['valor']); ?><?php echo $linha['pontuacao_cobrada'] > 0 ? ' ou ' . $linha['pontuacao_cobrada'] . ' pontos.' : '' ?></strong></h2>
                                              <h3>1 UN</h3>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <?php
                            }
                            ?>
                        </div>    
                    </div>
                    <div class="tabContent" id="tab13">
                        <div class="row">
                            <?php
                            $sql = $db->prepare("SELECT id, nome, add_valor, add_pontos
                                                 FROM ingredientes
                                                 WHERE status = 1
                                                 ORDER BY nome ASC");
                            $sql->execute();
                            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                              ?>
                              <div class="col-md-3 col-sm-4 col-xs-12">
                                  <div onclick="add_ingrediente(<?php echo $linha['id']; ?>)" class="card">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($linha['nome'], 'mai'); ?></h1>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <label><small>(+ R$ <?= fdec($linha['add_valor']); ?><?php echo $linha['add_pontos'] > 0 ? ' + ' . $linha['add_pontos'] . ' pontos' : '' ?>)</small></label>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <?php
                            }
                            ?>
                        </div>  
                    </div>
                    <div class="tabContent" id="tab14">
                        <div class="row">
                            <?php
                            $numero_sessao = session_id();
                            $subtotalgeral2 = 0;
                            $subpontosgeral2 = 0;
                            $subtotal2 = 0;
                            $subpontos2 = 0;

                            $sql0 = $db->prepare("SELECT pe.id, ps.nome AS cliente_nome, pe.mesa 
                             FROM pedidos pe
                             LEFT JOIN clientes AS c ON c.id = pe.cliente_id
                             LEFT JOIN pessoas AS ps ON ps.id = c.pessoa_id
                             WHERE pe.status = 1
                             ORDER BY pe.id ASC");
                            $sql0->execute();
                            $qtd_p = $sql0->rowCount();
                            while ($pedido_id = $sql0->fetch(PDO::FETCH_ASSOC)) {
                              ?>
                              <div onclick="colocar_em_atendimento(<?php echo $pedido_id['id']; ?>)" class="col-md-4 col-sm-4 col-xs-4">
                                  <div class="card2">
                                      <div class="row">
                                          <div class="col-xs-12">
                                              <h1><?php echo ctexto($pedido_id['cliente_nome'] == "" || $pedido_id['cliente_nome'] == NULL ? "Sem Cliente" : $pedido_id['cliente_nome'], "mai") . " - " . $pedido_id['id']; ?></h1>
                                          </div>
                                      </div>
                                      <?php
                                      $sql = $db->prepare("SELECT pi.observacao, pi.id AS pedidos_itens_id, pr.pontuacao_cobrada, pr.id AS produto_id, pi.quantidade, pe.id, pr.nome AS produto, pr.foto_cortada, pr.valor
                             FROM pedidos_itens pi
                             LEFT JOIN pedidos AS pe ON pe.id = pi.pedido_id
                             LEFT JOIN produtos AS pr ON pr.id = pi.produto_id
                             WHERE pe.status = 1 AND pe.id = ?
                             ORDER BY pr.nome ASC");
                                      $sql->bindValue(1, $pedido_id['id']);
                                      $sql->execute();
                                      while ($pedidos = $sql->fetch(PDO::FETCH_ASSOC)) {

                                        $sql5 = $db->prepare("SELECT pi.id AS pedidos_itens_id, i.nome, i.add_valor, i.add_pontos
                                         FROM ingredientes i
                                         LEFT JOIN pedidos_itens_ingredientes AS pii ON pii.ingrediente_id = i.id
                                         LEFT JOIN pedidos_itens AS pi ON pi.id = pii.pedidos_itens_id
                                         WHERE pi.produto_id = ? AND pi.pedido_id = ? AND pi.id = ? AND i.status = 1
                                         GROUP BY i.id
                                         ORDER BY i.nome ASC");
                                        $sql5->bindValue(1, $pedidos['produto_id']);
                                        $sql5->bindValue(2, $pedidos['id']);
                                        $sql5->bindValue(3, $pedidos['pedidos_itens_id']);
                                        $sql5->execute();
                                        while ($pedidos_itens_calc = $sql5->fetch(PDO::FETCH_ASSOC)) {
                                          $subtotal2 += $pedidos['quantidade'] == 1 ? $pedidos_itens_calc['add_valor'] : ($pedidos_itens_calc['add_valor'] * $pedidos['quantidade']);
                                          $subpontos2 += $pedidos['quantidade'] == 1 ? $pedidos_itens_calc['add_pontos'] : ($pedidos_itens_calc['add_pontos'] * $pedidos['quantidade']);
                                        }
                                        ?>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label><small><?php echo $pedidos['quantidade'] . "x " . ctexto($pedidos['produto'], "mai") . " R$ " . fdec(($subtotal2 + ($pedidos['valor'] * $pedidos['quantidade']))); ?></small></label>
                                            </div>
                                        </div>

                                        <?php
                                        $subtotalgeral2 += ($subtotal2 + ($pedidos['valor'] * $pedidos['quantidade']));

                                        $sql3 = $db->prepare("SELECT pii.id AS pedidos_itens_ingredientes_id, i.id, i.nome, i.add_valor, i.add_pontos
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
                                          ?>

                                          <div class="row">
                                              <div class="col-xs-2"></div>
                                              <div class="col-xs-10">
                                                  <small>- <?php echo ctexto($pedidos_itens['nome'], "mai"); ?></small>
                                              </div>
                                          </div>


                                          <?php
                                        }
                                      }
                                      ?>
                                  </div>
                              </div>
                              <?php
                            }
                            if ($qtd_p == 0) {
                              echo "Nenhum pedido em atendimento.";
                            }
                            ?>
                        </div>  
                    </div>
                    <div class="tabContent" id="tab15">
                        <div <?php echo isset($cliente['mesa_id']) && is_numeric($cliente['mesa_id']) ? 'style="display: none"' : ''; ?> id="div_entrega_endereco" class="row">
                            <div class="col-md-12">
                                <p class="help-block">Por favor, preencha as seguintes informações para entrega:</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="div_bairro" class="form-group">
                                            <p class="help-block">
                                                <span class="text-danger">*</span> Bairro:
                                            </p>
                                            <select name="bairro_id" id="bairro_id">
                                                <option pontos="0" rel="0" value="">Escolha o bairro</option>
                                                <?php
                                                $sql = $db->prepare("SELECT id, nome, valor, pontos FROM bairros WHERE status = 1 ORDER BY nome ASC");
                                                $sql->execute();
                                                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                  ?>
                                                  <option rel="<?php echo $linha['valor']; ?>" value="<?php echo $linha['id']; ?>"><?php echo $linha['nome']; ?></option>
                                                  <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-9">
                                        <div id="div_endereco" class="form-group">
                                            <p class="help-block"><span class="text-danger">*</span> Endereço</p>
                                            <input type="text" name="endereco" id="endereco" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="div_numero_casa" class="form-group">
                                            <p class="help-block"><span class="text-danger">*</span> Número</p>
                                            <input type="text" name="numero" id="numero" class="form-control" onkeypress="return SomenteNumero(event);"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <p class="help-block">Complemento</p>
                                            <input type="text" name="complemento" id="complemento" class="form-control" placeholder="Bloco, Apartamento, Referência e etc" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="div_contato" class="form-group">
                                            <p class="help-block"><span class="text-danger">*</span> Telefone de Contato</p>
                                            <input type="text" name="contato" id="contato" class="form-control" data-inputmask='"mask": "(99) 9999-9999"' data-mask placeholder="celular, residencial ou comercial"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <text class="help-block">Total a Receber</text>
                                <div id="div_troco_receber" class="form-group">
                                    <input readonly="true" type="text" id="total_a_receber" name="total_a_receber" value="<?php echo fdec($subtotalgeral3); ?>" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div id="div_forma_pagamento_dinheiro" class="row">
                            <div class="col-md-5">
                                <p class="help-block"><span class="text-danger">*</span> Forma de Pagamento</p>
                                <div id="div_forma_pagamento" class="form-group">
                                    <select id="forma_pagamento" name="forma_pagamento">
                                        <option value="">Escolha a forma de pagamento</option>
                                        <?php
                                        $sql = $db->prepare("SELECT id, nome
                                                 FROM forma_pagamentos
                                                 WHERE id <> 3 AND status = 1
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
                                <p class="help-block"><span class="text-danger">*</span> Valor Pago</p>
                                <div id="div_valor_pagar" class="form-group">
                                    <input onkeyup="valor_a_pagar()" type="text" id="valor_pagar" name="valor_pagar" class="form-control" placeholder="Troco para quanto?" value="" />
                                </div>
                            </div>
                            <div id="div_pagamento_dinheiro_2" class="col-md-3" style="display: none">
                                <p class="help-block">Troco</p>
                                <div id="div_troco_receber" class="form-group">
                                    <input readonly="true" type="text" id="valor_troco" name="valor_troco" value="0,00" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2">
                                <div class="buttons">
                                    <div class="text-right">
                                        <button onclick="finaliza_compra()" id="finalizar_compra" class="btn btn-success" type="submit"><i class="fa fa-credit-card"></i> <span>Enviar para o Atendimento</span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-10">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('footer_pdv.php'); ?>

<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape_pdv.php' ); ?>

<!-- JS DO ATENDIMENTO -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/pdv/atendimento.js"></script>

<!-- DATA TABES SCRIPT -->
<script src="<?= PLUGINS_FOLDER; ?>datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?= PLUGINS_FOLDER; ?>datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>

<script type="text/javascript">
                                          $(function () {
                                              $("#example1").dataTable();
                                          });
</script>