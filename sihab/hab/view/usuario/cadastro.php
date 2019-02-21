<?php include('template/topo.php'); ?>

<!-- CSS DO PLUGIN DE UPLOAD DE FOTOS -->
<link href="<?= PLUGINS_FOLDER; ?>cropper/css/cropper.min.css" rel="stylesheet">
<link href="<?= PLUGINS_FOLDER; ?>cropper/css/main.css" rel="stylesheet">
<!-- FIM DO PLUGIN -->

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null || $param != '' || $param != NULL) {
  $id = $param;
  $result = $db->prepare("SELECT * FROM seg_usuario WHERE id = ?");
  $result->bindValue(1, $id);
  $result->execute();

  $dados_usuario = $result->fetch(PDO::FETCH_ASSOC);

  $usuario_id = $dados_usuario['id'];
  $usuario_nome = $dados_usuario['nome'];
  $usuario_status = $dados_usuario['status'];
  $usuario_cpf = $dados_usuario['cpf'];
  $usuario_sexo = $dados_usuario['sexo_id'];
  $usuario_nascimento = $dados_usuario['data_nascimento'];
  $usuario_rg = $dados_usuario['rg'];
  $usuario_uf_expedicao = $dados_usuario['uf_expedicao'];
  $usuario_email_institucional = $dados_usuario['email_institucional'];
  $usuario_cnh = $dados_usuario['cnh'];
  $usuario_municipio_nascimento = $dados_usuario['nasc_municipio_id'];
  $usuario_email_pessoal = $dados_usuario['email_pessoal'];
  $usuario_celular = $dados_usuario['telefone_celular'];
  $usuario_telefone = $dados_usuario['telefone_fixo'];
  $usuario_cep = $dados_usuario['cep'];
  $usuario_logradouro = $dados_usuario['logradouro'];
  $usuario_numero = $dados_usuario['numero'];
  $usuario_bairro = $dados_usuario['bairro'];
  $usuario_complemento = $dados_usuario['complemento'];
  $usuario_municipio = $dados_usuario['municipio_id'];
  $usuario_orgao = $dados_usuario['unidade_organizacional_id'];
  $usuario_telefone_institucional = $dados_usuario['telefone_institucional'];
  $usuario_setor = $dados_usuario['setor'];
  $usuario_cargo = $dados_usuario['cargo'];
  $usuario_data_admissao = $dados_usuario['data_admissao'];
  $usuario_foto = $dados_usuario['foto'];
} else {
  $usuario_id = "";
  $usuario_nome = "";
  $usuario_status = 1;
  $usuario_cpf = "";
  $usuario_sexo = 1;
  $usuario_nascimento = "";
  $usuario_rg = "";
  $usuario_uf_expedicao = "";
  $usuario_email_institucional = "";
  $usuario_cnh = "";
  $usuario_municipio_nascimento = "";
  $usuario_email_pessoal = "";
  $usuario_celular = "";
  $usuario_telefone = "";
  $usuario_cep = "";
  $usuario_logradouro = "";
  $usuario_numero = "";
  $usuario_bairro = "";
  $usuario_complemento = "";
  $usuario_municipio = "";
  $usuario_orgao = "";
  $usuario_telefone_institucional = "";
  $usuario_setor = "";
  $usuario_cargo = "";
  $usuario_data_admissao = "";
  $usuario_foto = "";
}
?>

<?php include('template/sidebar.php'); ?>

<div class="card icons-demo">
  <div class="card-header cw-header palette-Purple-500 bg">
    <div class="cwh-year">Usuário</div>
    <div class="cwh-day">Cadastro</div>
    <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a>
  </div>

  <section id="content">
    <div class="container">
      <div> <!-- Painel 1° dados pessoais-->
        <div class="card-body card-padding">
          <p class="c-black f-500 m-b-15">DADOS PESSOAIS</p>
          <div class="row">

            <div class="col-md-2">

              <div id="crop-avatar">

                <!-- Current avatar -->
                <div class="avatar-view" title="Trocar o Foto">
                  <?php
                  if ($usuario_foto != "") {
                    ?>
                    <img src = "<?= $usuario_foto ?>" alt = "Avatar"/>
                    <?php
                  } else {
                    ?>
                    <img src="<?= PORTAL_URL; ?>assets/img/avatar/picture.jpg" alt="Avatar"/>
                    <?php
                  }
                  ?>
                </div>

                <!-- Cropping modal -->
                <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <form class="avatar-form" action="crop.php" enctype="multipart/form-data" method="post">
                        <div class="modal-header">
                          <button class="close" data-dismiss="modal" type="button">&times;</button>
                          <h4 class="modal-title" id="avatar-modal-label">Trocar Foto</h4>
                        </div>
                        <div class="modal-body">
                          <div class="avatar-body">

                            <!-- Upload image and data -->
                            <div class="avatar-upload">
                              <input class="avatar-src" name="avatar_src" type="hidden">
                              <input class="avatar-data" name="avatar_data" type="hidden">
                              <label for="avatarInput">Local upload</label>
                              <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
                            </div>

                            <!-- Crop and preview -->
                            <div class="row">
                              <div class="col-md-9">
                                <div class="avatar-wrapper"></div>
                              </div>
                              <div class="col-md-3">
                                <div class="avatar-preview preview-lg"></div>
                                <div class="avatar-preview preview-md"></div>
                                <div class="avatar-preview preview-sm"></div>
                              </div>
                            </div>

                            <div class="row avatar-btns">
                              <div class="col-md-9">
                                <div class="btn-group">
                                  <button class="btn btn-primary" data-method="rotate" data-option="-90" type="button" title="Rotate -90 degrees">Rotate Left</button>
                                  <button class="btn btn-primary" data-method="rotate" data-option="-15" type="button">-15deg</button>
                                  <button class="btn btn-primary" data-method="rotate" data-option="-30" type="button">-30deg</button>
                                  <button class="btn btn-primary" data-method="rotate" data-option="-45" type="button">-45deg</button>
                                </div>
                                <div class="btn-group">
                                  <button class="btn btn-primary" data-method="rotate" data-option="90" type="button" title="Rotate 90 degrees">Rotate Right</button>
                                  <button class="btn btn-primary" data-method="rotate" data-option="15" type="button">15deg</button>
                                  <button class="btn btn-primary" data-method="rotate" data-option="30" type="button">30deg</button>
                                  <button class="btn btn-primary" data-method="rotate" data-option="45" type="button">45deg</button>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <button class="btn btn-primary btn-block avatar-save" type="submit">Salvar</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- <div class="modal-footer">
                          <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                        </div> -->
                      </form>
                    </div>
                  </div>
                </div><!-- /.modal -->

                <!-- Loading state -->
                <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
              </div>
            </div>

            <form id="form_usuario" name="form_usuario" action="#" method="post">

              <div class="col-md-10">

                <div class="row space-t-15">
                  <div class="col-md-5">
                    <div class="form-group fg-float">
                      <div id="div_nome" class="fg-line">
                        <input type="text"  id="nome" name="nome" class="form-control" value="<?= $usuario_nome; ?>">
                        <label for="nome" class="fg-label"> NOME</label>
                      </div>
                    </div>
                  </div>

                  <div id="div_sexo" class="col-md-2 sel-form">
                    <div class="input-groud fg-float">
                      <label for="sexo" class="">SEXO</label>
                      <select id="sexo" name="sexo" placeholder="Sexo" class="selectpicker">
                        <p class="f-500 m-b-15 c-black">Sexo</p>
                        <option value="">Escolha o sexo</option>
                        <?php
                        $result = $db->prepare("SELECT id, nome FROM bsc_sexo ORDER BY nome DESC");
                        $result->execute();
                        while ($sexo = $result->fetch(PDO::FETCH_ASSOC)) {
                          if ($usuario_sexo == $sexo['id']) {
                            ?>
                            <option selected='true' value='<?= $sexo['id']; ?>'><?= ($sexo['nome']); ?></option>
                            <?php
                          } else {
                            ?>
                            <option value='<?= $sexo['id']; ?>'><?= ($sexo['nome']); ?></option>
                            <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 item-form">
                    <div class="has-feedback form-group fg-float">
                      <span class="zmdi zmdi-card form-control-icons"></span>
                      <div id="div_cpf" class="fg-line">
                        <input type="text" id="cpf" name="cpf" class="form-control input-mask" data-mask="000.000.000-00" value="<?= $usuario_cpf; ?>">
                        <label class="fg-label">CPF</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 item-form">
                    <div class="has-feedback form-group fg-float">
                      <span class="zmdi zmdi-calendar form-control-icons"></span>
                      <div id="div_nascimento" class="fg-line">
                        <input type="text" id="nascimento" name="nascimento" class="form-control date-picker" value="<?= obterDataBRTimestamp($usuario_nascimento); ?>">
                        <label class="fg-label">NASCIMENTO</label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row space-t-15">
                  <div class="col-md-4 item-form">
                    <div class="form-group fg-float">
                      <div id="div_rg" class="fg-line">
                        <input type="text" id="rg" name="rg" class="form-control" maxlength="15" value="<?= $usuario_rg; ?>">
                        <label class="fg-label">RG</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 item-form">
                    <div class="form-group fg-float">
                      <div id="div_uf_expedicao" class="fg-line">
                        <input type="text" id="uf_expedicao" name="uf_expedicao" class="form-control" maxlength="13" value="<?= $usuario_uf_expedicao; ?>">
                        <label class="fg-label">UF EXPEDIÇÃO</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 item-form">
                    <div class="form-group fg-float">
                      <div id="div_cnh" class="fg-line">
                        <input type="text" id="cnh" name="cnh" class="form-control" maxlength="13" value="<?= $usuario_cnh; ?>">
                        <label class="fg-label">CNH</label>
                      </div>
                    </div>
                  </div>
                </div> 

                <div class="row space-t-15">
                  <div id="div_estado_nascimento" class="col-md-6 sel-form">
                    <div class="input-groud fg-float">
                      <label for="estado_nascimento" class="">ESTADO DE NASCIMENTO</label>
                      <select id="estado_nascimento" name="estado_nascimento" placeholder="estado" class="selectpicker" data-live-search="true">
                        <option value="">Escolha o estado</option>
                        <?php
                        $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
                        $result->execute();
                        while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                          if (estado_do_municipio($usuario_municipio_nascimento) == $estado['id']) {
                            ?>
                            <option label='<?= $estado['sigla']; ?>' selected='true' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                            <?php
                          } else {
                            ?>
                            <option label='<?= $estado['sigla']; ?>' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                            <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div id="div_municipio_nascimento" class="col-md-6 sel-form">
                    <div class="input-groud fg-float">
                      <label for="municipio_nascimento" class="">MUNICÍPIO DE NASCIMENTO</label>
                      <select id="municipio_nascimento" name="municipio_nascimento" class="selectpicker" data-live-search="true">
                        <?php
                        if ($usuario_id == "") {
                          ?>
                          <option value="">Escolha primeiro o estado</option>
                          <?php
                        } else {
                          $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio ORDER BY nome ASC");
                          $result2->execute();
                          while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                            if ($usuario_municipio_nascimento == $municipio['id']) {
                              ?>
                              <option label='<?= $municipio['nome']; ?>' selected='true' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                              <?php
                            }
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
          </div>

        </div>    
      </div>
    </div>
  </section>
</div>

<div class="card"> <!-- Painel 2° dados de contato-->
  <div class="card-body card-padding">
    <p class="c-black f-500 m-b-5 m-t-20">DADOS DE CONTATO</p>
    </br>
    <div class="row space-t-15">
      <div class="col-md-4 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-email-open form-control-icons"></span>
          <div id="div_email_pessoal" class="fg-line">
            <input type="text" id="email_pessoal" name="email_pessoal" class="form-control" value="<?= $usuario_email_pessoal; ?>">
            <label class="fg-label">E-MAIL PESSOAL</label>
          </div>
        </div>
      </div>
      <div class="col-md-4 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-smartphone-android form-control-icons"></span>
          <div id="div_celular" class="fg-line">
            <input type="text" id="celular" name="celular" class="form-control"  maxlength="14" data-mask="(00) 00000-0000" value="<?= $usuario_celular; ?>">
            <label class="fg-label">CELULAR</label>
          </div>
        </div>
      </div>
      <div class="col-md-4 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-phone form-control-icons"></span>
          <div id="div_telefone" class="fg-line">
            <input type="text" id="telefone" name="telefone" class="form-control" data-mask="(00) 0000-0000" value="<?= $usuario_telefone; ?>">
            <label class="fg-label">TELEFONE FIXO</label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card"> <!-- Painel 3° dados de endereço-->
  <div class="card-body card-padding">                    
    <p class="c-black f-500 m-b-5 m-t-20">DADOS DE ENDEREÇO</p>
    </br>
    <div class="row space-t-15">
      <div class="col-md-2 item-form">
        <div class="form-group fg-float">
          <div id="div_cep" class="fg-line">
<!--            <input onKeyUp="consultacep()" type="text" id="cep" name="cep" class="form-control" maxlength="10" data-mask="00.000-000" value="<?= $usuario_cep; ?>">-->
            <input type="text" id="cep" name="cep" class="form-control" maxlength="10" data-mask="00.000-000" value="<?= $usuario_cep; ?>">
            <label class="fg-label">CEP</label>
          </div>
        </div>
      </div>
      <div class="col-md-8 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-home form-control-icons"></span>
          <div id="div_logradouro" class="fg-line">
            <input type="text" id="logradouro" name="logradouro" class="form-control" value="<?= $usuario_logradouro; ?>">
            <label class="fg-label">LOGRADOURO</label>
          </div>
        </div>
      </div>
      <div class="col-md-2 item-form">
        <div class="form-group fg-float">
          <div id="div_numero" class="fg-line">
            <input type="text" id="numero" name="numero" class="form-control" maxlength="6" value="<?= $usuario_numero; ?>">
            <label class="fg-label">NÚMERO</label>
          </div>
        </div>
      </div>
    </div>
    <div class="row space-t-15">
      <div class="col-md-6 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-map form-control-icons"></span>
          <div id="div_bairro" class="fg-line">
            <input type="text" id="bairro" name="bairro" class="form-control" value="<?= $usuario_bairro; ?>">
            <label class="fg-label">BAIRRO</label>
          </div>
        </div>
      </div>
      <div class="col-md-6 item-form">
        <div class="form-group fg-float">
          <div id="div_complemento" class="fg-line">
            <label class="fg-label">COMPLEMENTO</label>
            <input type="text" id="complemento" name="complemento" class="form-control" value="<?= $usuario_complemento; ?>">
          </div>
        </div>
      </div>
    </div>

    <div class="row space-t-15">
      <div id="div_estado" class="col-md-6 sel-form">
        <div class="input-groud fg-float">
          <label for="estado" class="">ESTADO ATUAL</label>
          <select id="estado" name="estado" placeholder="estado" class="selectpicker" data-live-search="true">
            <option value="">Escolha o estado</option>
            <?php
            $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
            $result->execute();
            while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
              if (estado_do_municipio($usuario_municipio) == $estado['id']) {
                ?>
                <option label='<?= $estado['sigla']; ?>' selected='true' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                <?php
              } else {
                ?>
                <option label='<?= $estado['sigla']; ?>' value='<?= $estado['id']; ?>'><?= $estado['nome']; ?></option>
                <?php
              }
            }
            ?>
          </select>
        </div>
      </div>
      <div id="div_municipio" class="col-md-6 sel-form">
        <div class="input-groud fg-float">
          <label for="municipio" class="">MUNICÍPIO ATUAL</label>
          <select id="municipio" name="municipio" class="selectpicker" data-live-search="true">
            <?php
            if ($usuario_id == "") {
              ?>
              <option value="">Escolha primeiro o estado</option>
              <?php
            } else {
              $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio ORDER BY nome ASC");
              $result2->execute();
              while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                if ($usuario_municipio == $municipio['id']) {
                  ?>
                  <option label='<?= $municipio['nome']; ?>' selected='true' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                  <?php
                }
              }
            }
            ?>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body card-padding">
    <p class="c-black f-500 m-t-20">DADOS DA INSTITUIÇÃO</p>
    <div class="row space-t-40">
      <div class="col-md-4 sel-form">
        <div id="div_orgao" class="input-groud fg-float">
          <label for="orgao" class="">UNIDADE ORGANIZACIONAL</label>
          <select id="orgao" name="orgao" placeholder="Órgão" class="selectpicker" data-live-search="true">
            <p class="f-500 m-b-15 c-black">Órgão</p>
            <option value="">Escolha o órgão</option>
            <?php
            $result = $db->prepare("SELECT id, sigla, nome FROM bsc_unidade_organizacional ORDER BY nome ASC");
            $result->execute();
            while ($orgao = $result->fetch(PDO::FETCH_ASSOC)) {
              if ($usuario_orgao == $orgao['id']) {
                ?>
                <option selected='true' value='<?= $orgao['id']; ?>'><?= $orgao['sigla'] . " - " . $orgao['nome']; ?></option>
                <?php
              } else {
                ?>
                <option value='<?= $orgao['id']; ?>'><?= $orgao['sigla'] . " - " . $orgao['nome']; ?></option>
                <?php
              }
            }
            ?>
          </select>
        </div>
      </div>
      <div class="col-md-3 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-phone form-control-icons"></span>
          <div id="div_telefone_institucional" class="fg-line">
            <input type="text" id="telefone_institucional" name="telefone_institucional" class="form-control" data-mask="(00) 0000-0000" value="<?= $usuario_telefone_institucional; ?>">
            <label class="fg-label">TELEFONE INSTITUCIONAL</label>
          </div>
        </div>
      </div>
      <div class="col-md-5 item-form">
        <div class="form-group fg-float">
          <div id="div_setor" class="fg-line">
            <input type="text" id="setor" name="setor" class="form-control" value="<?= $usuario_setor; ?>">
            <label class="fg-label">SETOR</label>
          </div>
        </div>
      </div>
    </div>

    <div class="row space-t-15">
      <div class="col-md-6 item-form">
        <div class="form-group fg-float">
          <div id="div_cargo" class="fg-line">
            <input type="text" id="cargo" name="cargo" class="form-control" value="<?= $usuario_cargo; ?>">
            <label class="fg-label">CARGO</label>
          </div>
        </div>
      </div>

      <div class="col-md-2 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-calendar form-control-icons"></span>
          <div id="div_data_admissao" class="dtp-container fg-line">
            <input type="text" id="data_admissao" name="data_admissao" class="form-control date-picker" value="<?= obterDataBRTimestamp($usuario_data_admissao); ?>">
            <label class="fg-label">DATA DE ADMISSÃO</label>
          </div>
        </div>
      </div>
      <div class="col-md-4 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-email-open form-control-icons"></span>
          <div id="div_email_institucional" class="fg-line">
            <input type="text" id="email_institucional" name="email_institucional" class="form-control" value="<?= $usuario_email_institucional; ?>">
            <label class="fg-label">E-MAIL INSTITUCIONAL</label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
    <div id="div_modulo_status" class="acesso-sistema <?= $cond; ?>">
      <input type="hidden" id="modulo_id" name="modulo_id[]" value="<?= $modulo['id']; ?>" />
      <div class="row">
        <div class="col-md-8 modulo fonte-branca"><?= colocaAcentoMaiusculo(strtoupper(($rsModulo['nome']))); ?></div>
        <div class="col-md-1 bloqueado"><label class="bloqueado-color" for="usuario_modulo">Bloqueado</label></div>
        <div class="col-md-1">

          <div class="toggle-switch" data-ts-color="blue">
            <label for="ts1" class="ts-label"></label>
            <input id="ts1" type="checkbox" name="usuario_modulo[]" rel="<?= $rsModulo['usuario_modulo_status']; ?>" <?= ($rsModulo['usuario_modulo_status'] == 1 ? 'checked="checked"' : ''); ?> value="<?= $rsModulo['id']; ?>">
            <label for="ts1" class="ts-helper"></label>
          </div>

        </div>
        <div class="col-md-2 ativo"><label class="bloqueado-color" for="usuario_modulo">Ativo</label></div>
      </div>
    </div>

    <div id="div_objetos" class="itens-sistema <?= $cond2; ?>">
      <div class="row">
        <div class="col-md-3">
          <div id="grupo_todos" class="checkbox check-success">
            <div class="checkbox m-b-15">
              <input id="todos_<?= $modulo['id']; ?>" rel="<?= $modulo['id']; ?>" type="checkbox" value="0"/>
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
                <input type="checkbox" id="<?= $rsGrupo['nome']; ?>_<?= $cont; ?>" class="grupo_check" rel="<?= $modulo['id']; ?>" <?= ($rsGrupo['usuario_grupo_status'] == 1 && $rsGrupo['usuario_grupo_status'] == 1 ? 'checked="checked"' : ''); ?> name="grupo_modulo_<?= $modulo['id']; ?>[]" value="<?= $rsGrupo["id"]; ?>"/>
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
                                                WHERE moa.objeto_id = o.id AND moa.modulo_id = ? 
                                                GROUP BY moa.objeto_id ORDER BY UPPER(o.nome)");
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
                    <input type="checkbox" rel="<?= $modulo['id']; ?>" <?= $checked; ?>  id="<?= $rsModulo['id']; ?>_<?= $objeto['objeto_id']; ?>_<?= $acao['acao_id']; ?>" name="<?= $rsModulo['id']; ?>_<?= $objeto['objeto_id']; ?>_<?= $acao['acao_id']; ?>" value="<?= $acao['modulo_objeto_acao_id']; ?>" />
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


<div class="card">
  <div class="card-header">
    <p class="c-black f-500 m-b-5">ACESSO AO SISTEMA</p>
  </div>

  <div class="card-body card-padding">                           
    <div class="row m-b-20">
      <div class="col-sm-4 m-b-20">
        <div class="toggle-switch" data-ts-color="blue">
          <label for="acesso" class="ts-label"></label>
          <input id="acesso" name="acesso" type="checkbox" hidden="hidden" <?= ($usuario_status == 1 ? 'checked="checked"' : ''); ?> value="<?= ($usuario_status == 1 ? 1 : 0); ?>">
          <label for="acesso" class="ts-helper"></label>
        </div>
      </div>                               
    </div>
  </div>
</div>


<input type="hidden" id="id" name="id" value="<?= $usuario_id ?>"/>

<div align="center">
  <?php
  if ($usuario_id == "") {
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

<!-- JAVASCRIPT BÁSICOS -->
<script src="<?= PLUGINS_FOLDER; ?>cropper/js/cropper.min.js"></script>
<script src="<?= PLUGINS_FOLDER; ?>cropper/js/main.js"></script>

<!-- JS DO OBJETO-LISTA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/usuario/cadastro.js"></script>

<script type="text/javascript">

              verificar_checks();

              var modulos = '';
              var modulos = <?php echo json_encode($modulos); ?>

              //CHECK PARA MARCAR TODOS DO GRUPO SELECIONADO OU REMOVER TODOS
              $('div#grupo_modulo').livequery('change', function () {
                var objGrupo = $(this);
                var modulo_id = $(this).find('input[type="checkbox"]').attr('rel');
                var grupo_id = $(this).find('input[type="checkbox"]').val();

                if ($(this).find('.checkbox').find('input[type="checkbox"]').attr('checked') != 'checked') {
                  $(this).find('.checkbox').find('input[type="checkbox"]').attr('checked', 'checked');
                  $(this).find('.checkbox').find('input[type="checkbox"]').prop("checked", true);
                } else {
                  $(this).find('.checkbox').find('input[type="checkbox"]').removeAttr('checked');
                  $(this).find('.checkbox').find('input[type="checkbox"]').prop("checked", false);
                }

                if ($(this).find('.checkbox').find('input[type="checkbox"]').attr('checked') == 'checked') {
                  $.each(modulos[modulo_id].grupos[grupo_id].modsObjsAcoes, function (key, value) {
                    $('input#' + value.modObjAcaoId).attr('checked', 'checked');
                    $('input#' + value.modObjAcaoId).prop("checked", true);
                  });
                } else {
                  $.each(modulos[modulo_id].grupos[grupo_id].modsObjsAcoes, function (key, value) {
                    $('input#' + value.modObjAcaoId).removeAttr('checked');
                    $('input#' + value.modObjAcaoId).prop("checked", false);
                  });
                  $(this).parents('div.row').find('div#grupo_modulo').each(function (k, obj) {
                    if ($(objGrupo) != $(obj) && $(obj).find('.checkbox').find('input[type="checkbox"]').attr('checked') == 'checked') {
                      $(obj).change();
                      $(obj).change();
                    }
                  });
                }

                //ABAIXO VAI CHECAR SE TODOS OS CHECKBOX ESTÃO MARCADOS PARA MARCAR O CHECKBOX TODOS
                var checado = true;

                $(this).parents('div#div_objetos').find('input[rel="' + modulo_id + '"]').each(function (key, value) {
                  if ($(value).attr('checked') != 'checked' && ($(value).attr("id") != ('todos_' + modulo_id)) && (!($(value).hasClass("grupo_check")))) {
                    checado = false;
                  }
                });

                if (checado) {
                  $('#todos_' + modulo_id).attr('checked', 'checked');
                  $('#todos_' + modulo_id).prop("checked", true);
                } else {
                  $('#todos_' + modulo_id).removeAttr('checked');
                  $('#todos_' + modulo_id).prop("checked", false);
                }

              });
</script>