<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null || $param != '' || $param != NULL) {
  $id = $param;
  $result = $db->prepare("SELECT id, nome, status FROM seg_grupo WHERE id = :id");
  $result->bindValue(":id", $id);
  $result->execute();

  $dados_grupo = $result->fetch(PDO::FETCH_ASSOC);

  $grupo_id = $dados_grupo['id'];
  $grupo_nome = ($dados_grupo['nome']);
  $grupo_status = $dados_grupo['status'];
} else {
  $grupo_id = "";
  $grupo_nome = "";
  $grupo_status = 1;
}

//SALVANDO GRUPO/MÓDULO/OBJETO/AÇÃO EM UM ARRAY
$modelo = array();
$sql5 = $db->prepare("SELECT grupo_id, modulo_objeto_acao_id FROM seg_grupo_modulo_objeto_acao ORDER BY grupo_id, modulo_objeto_acao_id ASC");
$sql5->execute();
while ($gmoa = $sql5->fetch(PDO::FETCH_ASSOC)) {
  $modelo['grupo']['modulo_objeto_acao'] = [$gmoa['grupo_id'], $gmoa['modulo_objeto_acao_id']];
}
?>
<form id="form_grupo" name="form_grupo" action="#" method="post">

  <section id="content">
    <div class="container">
      <div class="c-header">
        <div class="card icons-demo">
          <div class="card-header cw-header palette-Pink-700 bg">
            <div class="cwh-year">Grupo de acesso</div>
            <div class="cwh-day">Cadastro</div>

            <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a>
          </div>
          <div class="card-body card-padding-sm">
            <div id="cw-body">
              <div class="card-body card-padding">
                <p class="c-black f-500 m-b-5">DADOS DO GRUPO DE ACESSO</p>

                <div class="row space-50">
                  <div class="col-md-6">
                    <div class="input-group fg-float">
                      <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                      <div id="div_nome" class="fg-line">
                        <input type="text"  id="nome" name="nome" class="form-control" value="<?= $grupo_nome; ?>">
                        <label class="fg-label">Nome</label>
                      </div>
                    </div>
                  </div>
                </div> 
              </div>            
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php
  $cont = 1;
  $grupo = 1;

  $cond = "bg-azul-claro"; //bg-amarelo-escuro é o padrão
  $cond2 = "bg-azul-claro-20"; //bg-azul-claro-20 é o padrão

  $sql = $db->prepare("SELECT moa.modulo_id, m.nome "
          . "FROM seg_modulo_objeto_acao moa, seg_modulo m "
          . "WHERE moa.modulo_id = m.id group by moa.modulo_id ORDER BY m.nome ASC");
  $sql->execute();
  while ($modulo = $sql->fetch(PDO::FETCH_ASSOC)) {
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
          <div class="col-md-6 modulo"><?= ($modulo['nome']); ?></div>
        </div>
      </div>
      <div id="div_objetos" class="itens-sistema <?= $cond2; ?>">

        <fieldset class="espacamento">
          <div class="organizador">
            <div class="checkbox check-success">
              <div class="checkbox m-b-15">
                <input type="checkbox" name="todos_<?= $cont; ?>" id="todos_<?= $cont; ?>" value="todos" onclick="marcar_todos(this);"/>
                <i class="input-helper"></i>
              </div>

              <label for="todos_<?= $cont; ?>"> Todos </label>
            </div>

            <?php
            $sql2 = $db->prepare("select moa.objeto_id, o.nome from seg_modulo_objeto_acao moa, seg_objeto o where moa.objeto_id = o.id and moa.modulo_id = ? group by moa.objeto_id order by o.nome");
            $sql2->bindValue(1, $modulo['modulo_id']);
            $sql2->execute();
            while ($objeto = $sql2->fetch(PDO::FETCH_ASSOC)) {
              ?>
              <fieldset class="espacamento">
                <h2><?= ($objeto['nome']); ?></h2>
                <?php
                $sql3 = $db->prepare("select moa.acao_id, a.nome, moa.id as modulo_objeto_acao_id from seg_modulo_objeto_acao moa, seg_acao a where moa.acao_id = a.id and moa.modulo_id = ? and moa.objeto_id = ? order by a.nome");
                $sql3->bindValue(1, $modulo['modulo_id']);
                $sql3->bindValue(2, $objeto['objeto_id']);
                $sql3->execute();
                while ($acao = $sql3->fetch(PDO::FETCH_ASSOC)) {

                  $checked = '';

                  if (grupo_modulo_objeto_acao($acao['modulo_objeto_acao_id'], $grupo_id) && is_numeric($grupo_id))
                    $checked = 'checked="true"';
                  ?>
                  <div id="vf_check" class="col-md-2">
                    <div class="checkbox check-success">
                      <div class="checkbox m-b-15">
                        <input onclick="verificar(this, <?= $cont; ?>)" <?= $checked; ?> class="marcar" type="checkbox" id="acao_<?= $grupo; ?>" name="<?= $modulo['modulo_id']; ?>_<?= $objeto['objeto_id']; ?>_acao[]" value="<?= $acao['modulo_objeto_acao_id']; ?>"/>
                        <i class="input-helper"></i>
                      </div>
                      <label for="acao_<?= $grupo; ?>"> <?= ($acao['nome']); ?> </label>
                    </div>
                  </div>
                  <?php
                  $grupo++;
                }
                ?>
              </fieldset>
              <?php
            }
            ?>
          </div>
        </fieldset>
      </div>
    </div>
    <?php
    $cont++;
  }
  ?>

  <div class="card">
    <div class="card-header">
      <p class="c-black f-500 m-b-5">ACESSO AO SISTEMA</p>
    </div>

    <div class="card-body card-padding">                           
      <div class="row m-b-20">
          <div class="col-xs-1">BLOQUEADO</div>
          <div class="col-xs-1 m-b-20">
          <div class="toggle-switch" data-ts-color="blue">
            <label for="ts1" class="ts-label"></label>
            <input id="ts1" name="ts1" type="checkbox" hidden="hidden" <?= ($grupo_status == 1 ? 'checked="checked"' : ''); ?> value="<?= ($grupo_status == 1 ? 1 : 0); ?>">
            <label for="ts1" class="ts-helper"></label>
          </div>
        </div> 
        <div class="col-xs-1">ATIVO</div>                              
      </div>
    </div>
  </div>

  <input type="hidden" id="id" name="id" value="<?= $grupo_id ?>"/>

  <div align="center">
    <?php
    if ($grupo_id == "") {
      if (vf_objeto_acao("cadastrar")) {
      ?>
      <button type="submit" class="btn btn-primary btn-lg"><i class="zmdi zmdi-cloud-upload"></i> Cadastrar</button>
      <?php
      }
    } else {
      if (vf_objeto_acao("editar")) {
      ?>
      <button type="submit" class="btn btn-primary btn-lg"><i class="zmdi zmdi-cloud-upload"></i> Atualizar</button>
      <?php
      }
    }
    ?>
  </div>

</form>

<?php include('template/rodape.php'); ?>

<!-- JS DO USUARIO-CADASTRO -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/grupo/cadastro.js"></script>

<script type="text/javascript">
      verificar_checks();
</script>