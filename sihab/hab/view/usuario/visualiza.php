<?php include('template/topo.php'); ?>
<?php include('template/sidebar.php'); ?>
<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null && $param != '' && $param != NULL && $param != 0) {
  $id = $param;
  $result = $db->prepare("SELECT u.foto, s.nome AS sexo, o.nome AS orgao, o.sigla, u.id, u.nome, u.data_nascimento,
             u.cpf, u.email_pessoal, u.telefone_celular, u.telefone_institucional, u.municipio_id, u.email_institucional, u.status, u.rg, u.uf_expedicao, u.cnh, u.logradouro, u.cep, u.numero, u.complemento, u.setor, u.cargo
             FROM seg_usuario u, bsc_sexo s, bsc_unidade_organizacional o
             WHERE u.unidade_organizacional_id = o.id AND u.sexo_id = s.id AND u.id = :id");
  $result->bindValue(":id", $id);
  $result->execute();

  $dados_usuario = $result->fetch(PDO::FETCH_ASSOC);

  $usuario_id = $dados_usuario['id'];
  $usuario_nome = $dados_usuario['nome'];
  $usuario_sexo = $dados_usuario['sexo'];
  $usuario_cpf = $dados_usuario['cpf'];
  $usuario_nascimento = $dados_usuario['data_nascimento'];
  $usuario_rg = $dados_usuario['rg'];
  $usuario_uf_expedicao = $dados_usuario['uf_expedicao'];
  $usuario_cnh = $dados_usuario['cnh'];
  $usuario_celular = $dados_usuario['telefone_celular'];
  $usuario_email_pessoal = $dados_usuario['email_pessoal'];
  $usuario_cep = $dados_usuario['cep'];
  $usuario_logradouro = $dados_usuario['logradouro'];
  $usuario_numero = $dados_usuario['numero'];
  $usuario_complemento = $dados_usuario['complemento'];
  $usuario_orgao = $dados_usuario['orgao'];
  $usuario_setor = $dados_usuario['setor'];
  $usuario_cargo = $dados_usuario['cargo'];
  $usuario_email_institucional = $dados_usuario['email_institucional'];
  $usuario_telefone_institucional = $dados_usuario['telefone_institucional'];
  $usuario_foto = $dados_usuario['foto'];

  $grupo_status = $dados_usuario['status'];
} else {
  echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "sistema/modulo/lista';</script>";
}
?>


<div class="card icons-demo">
  <div class="card-header cw-header palette-Purple-500 bg">
    <div class="cwh-year">Usuário</div>
    <div class="cwh-day">Informações Básicas</div>
    <?php
      if (vf_objeto_acao("editar")) {
    ?>
    <a href="<?= PORTAL_URL; ?>sistema/usuario/cadastro/<?= $usuario_id; ?>" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-edit"></i></a>
    <?php
    }
    ?>
  </div>
  <div class="card-body card-padding-sm">
    <div id="cw-body">
      <section id="content">
        <div class="container">
          <div class="card" id="profile-main">
            <div class="pm-overview c-overflow">
              <div class="pmo-pic">
                <div class="p-relative">

                  <?php
                  if ($usuario_foto != "") {
                    ?>
                    <a href="<?= $usuario_foto ?>">
                      <img src="<?= $usuario_foto ?>" class="img-responsive" alt=""/>
                    </a>
                    <?php
                  } else {
                    ?>
                    <a href="#">
                      <img src="<?= PORTAL_URL; ?>assets/img/avatar/picture.jpg" class="img-responsive" alt=""/>
                    </a>
                    <?php
                  }
                  ?>
                </div>

                <div class="pmo-stat"><?= $usuario_nome; ?></div>
              </div>
            </div>
            <div class="pm-body clearfix">

              <div class="pmb-block">
                <div class="pmb-block">
                  <div class="pmbb-header">
                    <h2><i class="zmdi zmdi-account m-r-5"></i> Informações Básicas</h2>

                  </div>
                  <div class="pmbb-body p-l-30">
                    <div class="pmbb-view">
                      <dl class="dl-horizontal">
                        <dt>Nome Completo</dt>
                        <dd><?= $usuario_nome; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>Sexo</dt>
                        <dd><?= $usuario_sexo; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>CPF</dt>
                        <dd><?= $usuario_cpf; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>Data de Nascimento</dt>
                        <dd><?= $usuario_nascimento; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>RG</dt>
                        <dd><?= $usuario_rg; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>CHN</dt>
                        <dd><?= $usuario_cnh; ?></dd>
                      </dl>                                                                               
                    </div>
                  </div>
                </div>


                <div class="pmb-block">
                  <div class="pmbb-header">
                    <h2><i class="zmdi zmdi-phone m-r-5"></i> Dados de Contatos</h2>

                  </div>
                  <div class="pmbb-body p-l-30">
                    <div class="pmbb-view">
                      <dl class="dl-horizontal">
                        <dt>Celular </dt>
                        <dd><?= $usuario_celular; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>E-mail Pessoal</dt>
                        <dd><?= $usuario_email_pessoal; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt></dt>
                        <dd></dd>
                      </dl>
                    </div>

                  </div>
                </div>

                <div class="pmb-block">
                  <div class="pmbb-header">
                    <h2><i class="zmdi zmdi-account-box-phone"></i> Dados de Enderenço</h2>
                  </div>
                  <div class="pmbb-body p-l-30">
                    <div class="pmbb-view">
                      <dl class="dl-horizontal">
                        <dt>Cep</dt>
                        <dd><?= $usuario_cep; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>Logradouro</dt>
                        <dd><?= $usuario_logradouro; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>Número</dt>
                        <dd><?= $usuario_numero; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>Completo</dt>
                        <dd><?= $usuario_complemento; ?></dd>
                      </dl>
                    </div>

                  </div>
                </div>

                <div class="pmb-block">
                  <div class="pmbb-header">
                    <h2><i class="zmdi zmdi-city-alt"></i> Dados da Instituição</h2>

                  </div>
                  <div class="pmbb-body p-l-30">
                    <div class="pmbb-view">
                      <dl class="dl-horizontal">
                        <dt>Orgão</dt>
                        <dd><?= $usuario_orgao; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>Setor</dt>
                        <dd><?= $usuario_setor; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>Cargo</dt>
                        <dd><?= $usuario_cargo; ?></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt></dt>
                        <dd></dd>
                      </dl>
                      <dl class="dl-horizontal">
                        <dt>E-mail Institucional</dt>
                        <dd><?= $usuario_email_institucional; ?></dd>
                      </dl>                                        
                      <dl class="dl-horizontal">
                        <dt>Telefone Institucional</dt>
                        <dd><?= $usuario_telefone_institucional; ?></dd>
                      </dl>                                        
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </section>
    </div>
  </div>
</div>

<!-- MÓDULO PROJETOS -->
<?php
$modulos = array();

$cont = 1;
$cond = "bg-azul-claro"; //bg-amarelo-escuro é o padrão
$cond2 = "bg-azul-claro-20"; //bg-azul-claro-20 é o padrão
$sql = $db->prepare("SELECT m.id, COALESCE(um.id, 0) AS usuario_modulo_id, m.nome, m.status, COALESCE(um.status, 0) AS usuario_modulo_status 
                                    FROM seg_modulo AS m 
                                    LEFT JOIN seg_usuario_modulo um ON um.modulo_id = m.id AND um.usuario_id = ?
                                    WHERE m.status = 1 
                                    GROUP BY m.id ORDER BY m.nome ASC");
$sql->bindValue(1, $id);
$sql->execute();

while ($rsModulo = $sql->fetch(PDO::FETCH_ASSOC)) {

  $modulo = array();
  $modulo["id"] = $rsModulo['id'];
  $modulo["grupos"] = array();

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
        <div class="col-md-8 modulo fonte-branca"><?= colocaAcentoMaiusculo(strtoupper(($rsModulo['nome']))); ?></div>
        <div class="col-md-1 bloqueado"><label class="bloqueado-color" for="projetos">Bloqueado</label></div>
        <div class="col-md-1">
          <div class="toggle-switch" data-ts-color="blue">
            <label for="ts1" class="ts-label"></label>
            <input disabled="true" id="ts1" name="ts1" type="checkbox" rel="<?= $rsModulo['usuario_modulo_status']; ?>" <?= ($rsModulo['usuario_modulo_status'] == 1 ? 'checked="checked"' : ''); ?> value="<?= $rsModulo['id']; ?>">
            <label for="ts1" class="ts-helper"></label>
          </div>
        </div>
        <div class="col-md-2 ativo"><label class="bloqueado-color" for="projetos">Ativo</label></div>
      </div>
    </div>

    <div id="div_objetos" class="itens-sistema <?= $cond2; ?>">
      <div class="row">

        <div class="col-md-3">
          <div id="grupo_todos" class="checkbox check-success">
            <div class="checkbox m-b-15">
              <input disabled="" id="todos_<?= $modulo['id']; ?>" rel="<?= $modulo['id']; ?>" type="checkbox" value="0">
              <i class="input-helper"></i>
            </div>

            <label for="todos_<?= $modulo['id']; ?>">Todos</label>
          </div>
        </div>
        <?php
        $sql2 = $db->prepare("SELECT g.id, COALESCE(umg.id, 0) AS usuario_grupo_id, g.nome, g.status, COALESCE(umg.status, 0) AS usuario_grupo_status 
                                                  FROM seg_grupo AS g
                                                  LEFT JOIN seg_usuario_modulo_grupo umg ON umg.grupo_id = g.id AND umg.modulo_id = :modulo_id AND umg.usuario_id = :usuario_id
                                                  WHERE g.status = 1 ORDER BY nome ASC");
        $sql2->bindValue(':modulo_id', $rsModulo['id']);
        $sql2->bindValue(':usuario_id', $usuario_id);
        $sql2->execute();

        while ($rsGrupo = $sql2->fetch(PDO::FETCH_ASSOC)) {

          $grupo = array();
          $grupo["id"] = $rsGrupo["id"];
          $grupo["modsObjsAcoes"] = array();
          ?>
          <div class="col-md-3">
            <div id="grupo_modulo" class="checkbox check-success">
              <div class="checkbox m-b-15">
                <input disabled="" type="checkbox" id="<?= $rsGrupo['nome']; ?>_<?= $cont; ?>" class="grupo_check" rel="<?= $modulo['id']; ?>" <?= ($rsGrupo['usuario_grupo_status'] == 1 && $rsGrupo['usuario_grupo_status'] == 1 ? 'checked="checked"' : ''); ?> name="grupo_modulo_<?= $modulo['id']; ?>[]" value="<?= $rsGrupo["id"]; ?>">
                <i class="input-helper"></i>
              </div>
              <label for="<?= $rsGrupo['nome']; ?>_<?= $cont; ?>"><?= ($rsGrupo['nome']); ?></label>
            </div>
          </div>
          <?php
          $sql3 = $db->prepare("SELECT DISTINCT(gmoa.grupo_id) AS grupo_id, moa.modulo_id, moa.objeto_id, moa.acao_id
                                                        FROM seg_grupo_modulo_objeto_acao AS gmoa
                                                        LEFT JOIN seg_modulo_objeto_acao AS moa ON moa.id = gmoa.modulo_objeto_acao_id 
                                                        WHERE moa.modulo_id = :modulo_id AND gmoa.grupo_id = :grupo_id");
          $sql3->bindValue(':modulo_id', $rsModulo['id']);
          $sql3->bindValue(':grupo_id', $rsGrupo['id']);
          $sql3->execute();


          while ($rsModObjAcao = $sql3->fetch(PDO::FETCH_ASSOC)) {

            $modObjAcao = array();
            $modObjAcao["id"] = $rsModObjAcao["grupo_id"];
            $modObjAcao["modObjAcaoId"] = $rsModObjAcao["modulo_id"] . "_" . $rsModObjAcao["objeto_id"] . "_" . $rsModObjAcao["acao_id"];

            array_push($grupo["modsObjsAcoes"], $modObjAcao);
          }

          $modulo["grupos"][$grupo['id']] = $grupo;
        }
        ?>

      </div>

      <?php
      $sql4 = $db->prepare("SELECT moa.objeto_id, o.nome 
                                                FROM seg_modulo_objeto_acao moa
                                                LEFT JOIN seg_objeto AS o ON o.id = moa.objeto_id 
                                                WHERE moa.objeto_id = o.id AND moa.modulo_id = ? GROUP BY moa.objeto_id ORDER BY moa.objeto_id");
      $sql4->bindValue(1, $rsModulo['id']);
      $sql4->execute();
      while ($objeto = $sql4->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="row">
          <ul class="">
            <h2> <?= ($objeto['nome']); ?> </h2>
            <?php
            $sql5 = $db->prepare("SELECT moa.acao_id, a.nome, moa.id AS modulo_objeto_acao_id 
                                                            FROM seg_modulo_objeto_acao moa, seg_acao a 
                                                            WHERE moa.acao_id = a.id AND moa.modulo_id = ? AND moa.objeto_id = ? ORDER BY a.nome");
            $sql5->bindValue(1, $rsModulo['id']);
            $sql5->bindValue(2, $objeto['objeto_id']);
            $sql5->execute();
            while ($acao = $sql5->fetch(PDO::FETCH_ASSOC)) {

              $checked = '';

              if (usuario_modulo_objeto_acao($acao['modulo_objeto_acao_id'], $usuario_id) && is_numeric($usuario_id))
                $checked = 'checked="true"';
              ?>

              <li class="col-md-4">
                <div id="check" class="checkbox check-success">
                  <div class="checkbox m-b-15">
                    <input disabled="" type="checkbox" rel="<?= $modulo['id']; ?>" <?= $checked; ?>  id="<?= $rsModulo['id']; ?>_<?= $objeto['objeto_id']; ?>_<?= $acao['acao_id']; ?>" name="<?= $rsModulo['id']; ?>_<?= $objeto['objeto_id']; ?>_<?= $acao['acao_id']; ?>" value="<?= $acao['modulo_objeto_acao_id']; ?>" />
                    <i class="input-helper"></i>
                  </div>
                  <label for="<?= $rsModulo['id']; ?>_<?= $objeto['objeto_id']; ?>_<?= $acao['acao_id']; ?>"><?= ($acao['nome']); ?></label>
                </div>
              </li>
              <?php
            }
            ?>
          </ul>
        </div>

        <?php
      }
      ?>

    </div>
  </div>
  <?php
  $modulos[$modulo['id']] = $modulo;

  $cont++;
}
?>

<!-- Page Loader -->
<div class="page-loader palette-Teal bg">
  <div class="preloader pl-xl pls-white">
    <svg class="pl-circular" viewBox="25 25 50 50">
    <circle class="plc-path" cx="50" cy="50" r="20"/>
    </svg>
  </div>
</div>		




<?php include('template/rodape.php'); ?>

<!-- JS DO OBJETO-LISTA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/usuario/visualiza.js"></script>