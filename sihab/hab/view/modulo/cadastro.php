<?php include('template/topo.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null || $param != '' || $param != NULL) {
  $id = $param;
  $result = $db->prepare("SELECT id, nome, descricao, responsavel_id, versao, url, status FROM seg_modulo WHERE id = :id");
  $result->bindValue(":id", $id);
  $result->execute();

  $dados_modulo = $result->fetch(PDO::FETCH_ASSOC);

  $modulo_id = $dados_modulo['id'];
  $modulo_nome = ($dados_modulo['nome']);
  $modulo_descricao = ($dados_modulo['descricao']);
  $modulo_responsavel = $dados_modulo['responsavel_id'];
  $modulo_versao = ($dados_modulo['versao']);
  $modulo_url = $dados_modulo['url'];
  $modulo_status = $dados_modulo['status'];
} else {
  $modulo_id = "";
  $modulo_nome = "";
  $modulo_descricao = "";
  $modulo_responsavel = "";
  $modulo_versao = "";
  $modulo_url = "";
  $modulo_status = 1;
}
?>

<?php include('template/sidebar.php'); ?>

<form id="form_modulo" name="form_modulo" action="#" method="post">
  <div class="card icons-demo">
    <div class="card-header cw-header palette-Pink-700 bg">
      <div class="cwh-year">Módulo</div>
      <div class="cwh-day">Cadastro</div>
    <?php
      if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
    ?>      
      <a href="<?= PORTAL_URL; ?>sistema/modulo/cadastro/<?= $modulo_id; ?>" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-edit"></i></a>
      <?php
      }
      ?>
    </div>

    <section id="content">
      <div class="container">
        <div> <!-- Painel-->
          <div class="card-body card-padding">
            <p class="c-black f-500 m-b-15">DADOS DO MÓDULO</p>

            <div class="row space-50">
              <div class="col-md-12">
                <div class="input-group fg-float">
                  <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                  <div id="div_nome" class="fg-line">
                    <label class="fg-label">Nome</label>
                    <input type="text"  id="nome" name="nome" class="form-control" value="<?= $modulo_nome ?>">
                  </div>
                </div>
              </div>
            </div>


            <div class="row space-50">
              <div class="col-md-12">
                <div class="input-group fg-float">
                  <span class="input-group-addon"><i class="zmdi zmdi-keyboard"></i></span>
                  <div id="div_descricao" class="fg-line">
                    <label class="fg-label">Descrição</label>
                    <textarea id="descricao" name="descricao" class="form-control" placeholder="Descrição"><?= $modulo_descricao ?></textarea>
                  </div>
                </div>
              </div>
            </div>


            <div class="row space-50">
              <div class="col-md-6">    
                <div class="input-groud fg-float">    
                  <label for="responsavel_id"> Responsável </label>
                  <select id="responsavel_id" name="responsavel_id" placeholder="Responsável" class="selectpicker">
                    <p class="f-500 m-b-15 c-black">Responsável</p>          
                    <option value="">Escolha o responsável</option>
                    <?php
                    $result = $db->query("SELECT nome, id FROM seg_usuario WHERE status = 1 ORDER BY nome ASC");
                    while ($responsavel = $result->fetch(PDO::FETCH_ASSOC)) {
                      if ($modulo_responsavel == $responsavel['id'])
                        echo "<option selected='selected' value=" . $responsavel['id'] . "'>" . ($responsavel['nome']) . "</option>";
                      else
                        echo"<option value='" . $responsavel['id'] . "'>" . ($responsavel['nome']) . "</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6 space-50">
                <div class="input-group fg-float">
                  <span class="input-group-addon"><i class="zmdi zmdi-keyboard"></i></span>
                  <div id="div_versao" class="fg-line">
                    <label class="fg-label">Versão</label>
                    <input type="text"  id="versao" name="versao" class="form-control" value="<?= $modulo_versao ?>">
                  </div>
                </div>
              </div>
            </div>

            <div class="row space-50">
              <div class="col-md-12">
                <div class="input-group fg-float">
                  <span class="input-group-addon"><i class="zmdi zmdi-keyboard"></i></span>
                  <div id="div_url" class="fg-line">
                    <label class="fg-label">Url</label>
                    <input type="text" id="url" name="url" class="form-control" placeholder="Url" value="<?= $modulo_url ?>"/>
                  </div>
                </div>
              </div>
            </div>        

          </div>    
        </div>
      </div>
    </section>
  </div>

  <?php
  $acum = 1;
  $cont = 1;
  $cond = "bg-azul-claro"; //bg-amarelo-escuro é o padrão
  $cond2 = "bg-azul-claro-20"; //bg-azul-claro-20 é o padrão
  $sql = $db->query("SELECT nome, id FROM seg_objeto ORDER BY nome ASC");
  while ($objeto = $sql->fetch(PDO::FETCH_ASSOC)) {
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
          <div class="col-md-6 modulo"><?= $objeto['nome']; ?></div>
        </div>
      </div>

      <div id="div_objetos" class="itens-sistema <?= $cond2; ?>">
        <fieldset>
          <?php
          $sql2 = $db->query("SELECT nome, id FROM seg_acao ORDER BY nome ASC");
          while ($acao = $sql2->fetch(PDO::FETCH_ASSOC)) {
            $checked = '';

            $objeto_acao = check_objeto_acao($modulo_id, $objeto['id'], $acao['id']);

            if ($objeto_acao > 0 && is_numeric($modulo_id))
              $checked = 'checked="true"';
            ?>
            <div class="col-md-2">
              <div class="checkbox check-success">
                <div class="checkbox m-b-15">
                  <input type="hidden" type="checkbox" id="modulo_objeto_acao_id" name="modulo_objeto_acao_id[]" value="<?= $objeto_acao; ?>" />
                  <input onchange="modulo_objeto_acao(this)" <?= $checked; ?> type="checkbox" id="acao_<?= $acum; ?>" name="<?= $objeto['id']; ?>_acao[]" value="<?= $acao['id']; ?>">
                  <i class="input-helper"></i>
                </div>
                <label for="acao_<?= $acum; ?>"><?= ($acao['nome']); ?></label>
              </div>
            </div>
            <?php
            $acum++;
          }
          ?>
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
            <input id="ts1" name="ts1" type="checkbox" hidden="hidden" <?= ($modulo_status == 1 ? 'checked="checked"' : ''); ?> value="<?= ($modulo_status == 1 ? 1 : 0); ?>">
            <label for="ts1" class="ts-helper"></label>
          </div>
        </div>
        <div class="col-xs-1">ATIVO</div>                               
      </div>
    </div>
  </div>

  <input type="hidden" id="id" name="id" value="<?= $modulo_id ?>"/>

  <div align="center">
    <?php
    if ($modulo_id == "") {
      if (vf_objeto_acao("cadastrar")) {
      ?>
      <button type="submit" class="btn btn-primary btn-lg"><i class="zmdi zmdi-cloud-upload"></i> Cadastrar</button>
      <?php
      }
    } else {
      if (vf_objeto_acao("editar")){
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/modulo/cadastro.js"></script>