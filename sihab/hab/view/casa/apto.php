<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>

<form id="form_casa" name="form_casa" action="#" method="post">
  <div class="card icons-demo">
    <div class="card-header cw-header palette-Pink-700 bg">
      <div class="cwh-year">Módulo</div>
      <div class="cwh-day">Loteamentos e Casas Aptas</div>
    </div>
    <!--  </div>-->

    <?php
    $cont = 1;
    $cond = "bg-azul-claro"; //bg-amarelo-escuro é o padrão
    $cond2 = "bg-azul-claro-20"; //bg-azul-claro-20 é o padrão
    $sql = $db->query("SELECT id, nome, apto
           FROM snch_loteamento
           WHERE status = 1 AND apto IN (0,1)
           ORDER BY nome ASC");
    while ($loteamento = $sql->fetch(PDO::FETCH_ASSOC)) {
      if ($cont % 2 == 0) {//SE O CONTADOR MOD 2 FOR == 0 ELE MUDA DE COR O LAYOUT DO MÓDULO PARA AMARELO
        $cond = "bg-amarelo-escuro";
        $cond2 = "bg-amarelo-escuro-20";
      } else {//SE O CONTADOR MOD 2 FOR DIFERENTE DE 0, ENTÃO ELE MANTE A COR DO MÓDULO PADRÃO AZUL
        $cond = "bg-azul-claro";
        $cond2 = "bg-azul-claro-20";
      }
      ?>
      <div class="card">
        <div class="acesso-sistema <?= $cond; ?>">
          <div class="row">
            <div class="col-md-6 modulo"><?= $loteamento['nome']; ?></div>
          </div>
        </div>

        <div id="div_objetos" class="itens-sistema <?= $cond2; ?>">
          <fieldset>
            <?php
            $sql2 = $db->prepare("SELECT id, nome, status
                   FROM sort_casa 
                   WHERE status IN (0,1) AND loteamento_id = ? 
                   ORDER BY nome ASC");
            $sql2->bindValue(1, $loteamento['id']);
            $sql2->execute();
            while ($casa = $sql2->fetch(PDO::FETCH_ASSOC)) {
              ?>
              <div class="col-md-2">
                <div class="checkbox check-success">
                  <div class="checkbox m-b-15">
                    <input <?= $casa['status'] == 1 ? 'checked="true"' : ''; ?> type="checkbox" id="casa_<?= $casa['id']; ?>" name="casas[]" value="<?= $casa['id']; ?>">
                    <i class="input-helper"></i>
                  </div>
                  <label for="casa_<?= $casa['id']; ?>"><?= ($casa['nome']); ?></label>
                </div>
              </div>
              <?php
            }
            ?>
          </fieldset>
        </div>
      </div>
      <?php
      $cont++;
    }
    ?>
  </div>
  
  <div align="center">
    <button type="submit" class="btn btn-primary btn-lg"><i class="zmdi zmdi-cloud-upload"></i> Atualizar</button>
  </div>

</form>

<?php include('template/rodape.php'); ?>

<!-- JS DO USUARIO-CADASTRO -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/casa/apto.js"></script>