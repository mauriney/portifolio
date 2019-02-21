<div class="row space-t-10">
  <div class="row space-t-10">
    <div class="col-xs-12">
      <div class="form-header">
        <div class="form-title"><i class="zmdi zmdi-account topo-icons-etapas"></i>
          <b>INFORMAÇÕES DO FAMILIAR</b>
        </div>
      </div><!--/ .form-header-->
    </div>
  </div>
</div><!--/ .row-->

<div class="form-wizard">

  <input type="hidden" id="familiar_id" name="familiar_id[]" value="<?= $familiar_id; ?>"/>

  <input type="hidden" id="array_familiar_contador" name="array_familiar_contador[]" value="<?= $contador; ?>"/>
  <div class="row space-t-10">
    <div class="row space-t-25">
      <div id="div_grau_parentesco" class="col-md-3 sel-form">
        <label for="grau_parentesco">GRAU DE PARENTESCO COM O TITULAR<sup>*</sup></label>
        <select id="grau_parentesco" name="grau_parentesco[]" class="selectpicker" data-live-search="true">
          <option value="">ESCOLHA O GRAU DE PARENTESCO COM O TITULAR</option>
          <?php
          $result = $db->prepare("SELECT id, nome FROM hab_grau_parentesco WHERE status = 1 ORDER BY nome ASC");
          $result->execute();
          while ($parentesco = $result->fetch(PDO::FETCH_ASSOC)) {
            if (!in_array($parentesco['id'], $parentesco_array) || $parentesco['id'] == 1 || $parentesco['id'] == 2 || $parentesco['id'] == 5 || $parentesco['id'] == 6 || $parentesco['id'] == 7 || $parentesco['id'] == 8 || $parentesco['id'] == 9 || $parentesco['id'] == 10 || $dados_familiar['hab_grau_parentesco_id'] == $parentesco['id']) {
              if ($dados_familiar['hab_grau_parentesco_id'] == $parentesco['id']) {
                ?>
                <option rel="<?= $parentesco['nome']; ?>" selected='true' value='<?= $parentesco['id']; ?>'><?= $parentesco['nome']; ?></option>
                <?php
              } else {
                ?>
                <option rel="<?= $parentesco['nome']; ?>" value='<?= $parentesco['id']; ?>'><?= $parentesco['nome']; ?></option>
                <?php
              }
            }
          }
          ?>
        </select>
      </div>
    </div>

    <div class="row space-t-40">
      <div class="col-md-3 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-card form-control-icons"></span>
          <div id="div_cpf" class="fg-line">
            <input id="cpf" name="cpf[]" type="text" class="input-sm form-control fg-input" data-mask="000.000.000-00" value="<?= $dados_familiar['cpf'] == '' ? '' : $dados_familiar['cpf']; ?>">
            <label class="fg-label">CPF<sup>*</sup></label>
          </div>
        </div>
      </div>
      <div class="col-md-5 item-form">
        <div class="form-group fg-float">
          <div id="div_nome" class="fg-line">
            <input onKeyUp="busca_autocomplete()" id="nome" name="nome[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['nome'] == '' ? '' : $dados_familiar['nome']; ?>">
            <label class="fg-label">NOME<sup>*</sup></label>
          </div>
        </div>
      </div>
      <div class="col-md-2 sel-form">
        <div class="input-groud fg-float">
          <label for="cor" class="">COR/RAÇA<sup>*</sup></label>
          <select id="cor" name="cor[]" class="selectpicker" data-live-search="true">
            <option value="">ESCOLHA A COR/RAÇA</option>
            <?php
            $result = $db->prepare("SELECT id, nome FROM bsc_pele_cor WHERE status = 1 ORDER BY nome ASC");
            $result->execute();
            while ($cor = $result->fetch(PDO::FETCH_ASSOC)) {
              if ($dados_familiar['bsc_pele_cor_id'] == $cor['id']) {
                ?>
                <option selected='true' value='<?= $cor['id']; ?>'><?= $cor['nome']; ?></option>
                <?php
              } else {
                ?>
                <option value='<?= $cor['id']; ?>'><?= $cor['nome']; ?></option>
                <?php
              }
            }
            ?>
          </select>
        </div>
      </div>
      <div class="col-md-2 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-calendar form-control-icons"></span>
          <div id="div_data_nascimento" class="fg-line">
            <input id="data_nascimento" name="data_nascimento[]" type='text' data-mask="00/00/0000" class="form-control date-picker" value="<?= $dados_familiar['data_nascimento'] == "" || $dados_familiar['data_nascimento'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['data_nascimento']); ?>">
            <label class="fg-label">DATA DE NASCIMENTO<sup>*</sup></label>
          </div>
        </div>
      </div>
    </div>
    <div class="row space-t-25">
      <div class="col-md-3 sel-form">
        <label for="documento1" class="">TIPO DE DOCUMENTO<sup>*</sup></label>
        <select id="documento1" name="documento1[]" class="selectpicker" data-live-search="true">
          <option value="">ESCOLHA O TIPO DE DOCUMENTO</option>
          <?php
          if ($dados_familiar['rg_numero'] != "") {
            ?>
            <option selected="true" value="1">REGISTRO GERAL - RG</option>
            <option value="2">CARTEIRA NACIONAL DE HABILITAÇÃO - CNH</option>
            <?php
          } else if ($dados_familiar['cnh_numero'] != "") {
            ?>
            <option value="1">REGISTRO GERAL - RG</option>
            <option selected="true" value="2">CARTEIRA NACIONAL DE HABILITAÇÃO - CNH</option>
            <?php
          } else {
            ?>
            <option value="1">REGISTRO GERAL - RG</option>
            <option value="2">CARTEIRA NACIONAL DE HABILITAÇÃO - CNH</option>
            <?php
          }
          ?>
        </select>
      </div>


      <div id="documento_1_1" <?= $documento_1_1; ?>>
        <div class="col-md-3 item-form">
          <div class="form-group fg-float">
            <div id="div_numero_registro" class="fg-line">
              <input id="numero_registro" name="numero_registro[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['rg_numero'] == "" ? "" : $dados_familiar['rg_numero']; ?>">
              <label class="fg-label">Nº DE REGISTRO<sup>*</sup></label>
            </div>
          </div>
        </div>
        <div id="div_orgao_expedidor" class="col-md-2 item-form">
          <label for="orgao_expedidor">ÓRGÃO EXPEDIDOR<sup>*</sup></label>
          <select id="orgao_expedidor" name="orgao_expedidor[]" class="selectpicker" data-live-search="true">
            <option value="">ESCOLHA O ÓRGÃO EXPEDIDOR</option>
            <?php
            $result = $db->prepare("SELECT id, nome FROM bsc_orgao_expedidor WHERE status = 1 ORDER BY id ASC");
            $result->execute();
            while ($orgao = $result->fetch(PDO::FETCH_ASSOC)) {
              if ($dados_familiar['rg_orgao_expedicao_id'] == $orgao['id']) {
                ?>
                <option selected="true" value="<?= $orgao['id']; ?>"><?= $orgao['nome']; ?></option>
                <?php
              } else {
                ?>
                <option value="<?= $orgao['id']; ?>"><?= $orgao['nome']; ?></option>
                <?php
              }
            }
            ?>
          </select>
        </div>
        <div id="div_uf_expedicao" class="col-md-2 item-form">
          <label for="uf_expedicao">UF EXPEDIÇÃO<sup>*</sup></label>
          <select id="uf_expedicao" name="uf_expedicao[]" class="selectpicker" data-live-search="true">
            <option value="">ESCOLHA A UF DE EXPEDIÇÃO</option>
            <?php
            $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
            $result->execute();
            while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
              if ($dados_familiar['rg_uf_expedicao'] == $estado['id']) {
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
        <div class="col-md-2 item-form">
          <div class="has-feedback form-group fg-float">
            <span class="zmdi zmdi-calendar form-control-icons"></span>
            <div id="div_data_expedicao" class="fg-line">
              <input id="data_expedicao" name="data_expedicao[]" type='text' class="form-control date-picker" value="<?= $dados_familiar['rg_data_expedicao'] == "" || $dados_familiar['rg_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['rg_data_expedicao']); ?>" data-mask="00/00/0000">
              <label class="fg-label">DATA DE EXPEDIÇÃO<sup>*</sup></label>
            </div>
          </div>
        </div>
      </div>

      <div id="documento_1_2" <?= $documento_1_2; ?>>
        <div class="col-md-2 item-form">
          <div class="form-group fg-float">
            <div id="div_numero_registro_2" class="fg-line">
              <input id="numero_registro_2" name="numero_registro_2[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['cnh_numero'] == "" ? "" : $dados_familiar['cnh_numero']; ?>">
              <label class="fg-label">Nº DE REGISTRO<sup>*</sup></label>
            </div>
          </div>
        </div>
        <div id="div_uf_expedicao_2" class="col-md-3 sel-form">
          <label for="documento1" class="">UF EXPEDIÇÃO</label>
          <select id="uf_expedicao_2" name="uf_expedicao_2[]" class="selectpicker" data-live-search="true">
            <option value="">ESCOLHA A UF DE EXPEDIÇÃO</option>
            <?php
            $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
            $result->execute();
            while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
              if ($dados_familiar['cnh_uf_expedicao'] == $estado['id']) {
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
        <div class="col-md-2 item-form">
          <div class="has-feedback form-group fg-float">
            <span class="zmdi zmdi-calendar form-control-icons"></span>
            <div id="div_data_expedicao_2"class="fg-line">
              <input id="data_expedicao_2" name="data_expedicao_2[]" type='text' class="form-control date-picker" value="<?= $dados_familiar['cnh_data_expedicao'] == "" || $dados_familiar['cnh_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cnh_data_expedicao']); ?>" data-mask="00/00/0000">
              <label class="fg-label">DATA DE EXPEDIÇÃO<sup>*</sup></label>
            </div>
          </div>
        </div>
        <div class="col-md-2 item-form">
          <div class="has-feedback form-group fg-float">
            <span class="zmdi zmdi-calendar form-control-icons"></span>
            <div id="div_data_validade_2" class="dtp-container fg-line">
              <input id="data_validade_2" name="data_validade_2[]" type='text' class="form-control date-picker" value="<?= $dados_familiar['cnh_data_validade'] == "" || $dados_familiar['cnh_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cnh_data_validade']); ?>" data-mask="00/00/0000">
              <label class="fg-label">DATA DE VALIDADE<sup>*</sup></label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row space-t-10">
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <p class="c-black f-500">SEXO</p>
          </div>
          <div class="col-lg-6">
            <div class="radio m-b-15">
              <label>
                <input <?= $sexo_masculino; ?> id="masculino" name="sexo_<?= $familiar_id; ?>" type="radio" value="1">
                <i class="input-helper"></i>
                MASCULINO
              </label>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="radio m-b-15">
              <label>
                <input <?= $sexo_feminino; ?> id="feminino" name="sexo_<?= $familiar_id; ?>" type="radio" value="2">
                <i class="input-helper"></i>
                FEMININO
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row space-t-10">
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <p class="c-black f-500">NACIONALIDADE</p>
          </div>
          <div class="col-lg-6">
            <div class="radio m-b-15">
              <label>
                <input <?= $nacionalidade_brasileiro; ?> checked="true" id="nacionalidade_brasileiro" name="nacionalidade_<?= $familiar_id; ?>" type="radio" value="1">
                <i class="input-helper"></i>
                BRASILEIRO
              </label>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="radio m-b-15">
              <label>
                <input <?= $nacionalidade_estrangeiro; ?> id="nacionalidade_estrangeiro" name="nacionalidade_<?= $familiar_id; ?>" type="radio" value="0">
                <i class="input-helper"></i>
                ESTRANGEIRO
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div <?= $estrangeiro; ?> id="estrangeiro" class="row space-t-25">
      <div id="div_pais" class="col-md-3 item-form">
        <label for="pais" class="">PAÍS<sup>*</sup></label>
        <select id="pais" name="pais[]" placeholder="PAÍS" class="selectpicker" data-live-search="true">
          <option value="">ESCOLHA A NACIONALIDADE</option>
          <?php
          $result = $db->prepare("SELECT id, nome FROM bsc_nacionalidade WHERE id <> 30 ORDER BY nome ASC");
          $result->execute();
          while ($nacionalidade = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($dados_familiar['bsc_nacionalidade_id'] == $nacionalidade['id']) {
              ?>
              <option selected='true' value='<?= $nacionalidade['id']; ?>'><?= ctexto($nacionalidade['nome'], "mai"); ?></option>
              <?php
            } else {
              ?>
              <option value='<?= $nacionalidade['id']; ?>'><?= ctexto($nacionalidade['nome'], "mai"); ?></option>
              <?php
            }
          }
          ?>
        </select>
      </div>
      <div class="col-md-2 item-form">
        <div class="form-group fg-float">
          <div id="div_cod_rne" class="fg-line">
            <input id="cod_rne" name="cod_rne[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['cie_rne'] == "" ? "" : $dados_familiar['cie_rne']; ?>">
            <label class="fg-label">CÓD. RNE (letras e números)<sup>*</sup></label>
          </div>
        </div>
      </div>
      <div class="col-md-3 sel-form">
        <label for="pais" class="">CLASSIFICAÇÃO<sup>*</sup></label>
        <div class="form-group fg-float">
          <div id="div_classificacao" class="fg-line">
            <select id="classificacao" name="classificacao[]" class="selectpicker" data-live-search="true" data-actions-box="true">
              <option value="">ESCOLHA A CLASSIFICAÇÃO</option>
              <?php
              $result = $db->prepare("SELECT id, nome FROM bsc_cie_classificacao WHERE status = 1 ORDER BY nome ASC");
              $result->execute();
              while ($classificacao = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($dados_familiar['bsc_cie_classificacao_id'] == $classificacao['id']) {
                  ?>
                  <option selected='true' value='<?= $classificacao['id']; ?>'><?= $classificacao['nome']; ?></option>
                  <?php
                } else {
                  ?>
                  <option value='<?= $classificacao['id']; ?>'><?= $classificacao['nome']; ?></option>
                  <?php
                }
              }
              ?>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-2 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-calendar form-control-icons"></span>
          <div id="div_data_expedicao_1" class="dtp-container fg-line">
            <input id="data_expedicao_1" name="data_expedicao_1[]" type='text' class="form-control date-picker" value="<?= $dados_familiar['cie_data_expedicao'] == "" || $dados_familiar['cie_data_expedicao'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cie_data_expedicao']); ?>" data-mask="00/00/0000">
            <label class="fg-label">DATA EXPEDIÇÃO<sup>*</sup></label>
          </div>
        </div>
      </div>
      <div class="col-md-2 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-calendar form-control-icons"></span>
          <div  id="div_validade" class="dtp-container fg-line">
            <input id="validade" name="validade[]" type='text' class="form-control date-picker" value="<?= $dados_familiar['cie_data_validade'] == "" || $dados_familiar['cie_data_validade'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['cie_data_validade']); ?>" data-mask="00/00/0000">
            <label class="fg-label">VALIDADE<sup>*</sup></label>
          </div>
        </div>
      </div>
    </div>

    <div <?= $brasileiro; ?> id="brasileiro" class="row space-t-20">
      <div class="col-xs-12">
        <p class="c-black f-500">NATURALIDADE</p>
      </div>
      <br style="clear: both; margin-bottom: 30px" />
      <div id="div_naturalidade_estado" class="col-md-4 item-form">
        <label for="naturalidade_estado">ESTADO<sup>*</sup></label>
        <select id="naturalidade_estado" name="naturalidade_estado[]" placeholder="estado" class="selectpicker" data-live-search="true">
          <option value="">ESCOLHA O ESTADO</option>
          <?php
          $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
          $result->execute();
          while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
            if (estado_do_municipio($dados_familiar['bsc_municipio_id_natural']) == $estado['id']) {
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
      <div id="div_naturalidade_municipio" class="col-md-4 item-form">
        <label for="naturalidade_municipio">CIDADE<sup>*</sup></label>
        <select id="naturalidade_municipio" name="naturalidade_municipio[]" class="selectpicker" data-live-search="true">
          <?php
          if ($familiar_id == "" || $dados_familiar['bsc_municipio_id_natural'] == "") {
            ?>
            <option value="">ESCOLHA PRIMEIRO O ESTADO</option>
            <?php
          } else {
            $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio WHERE estado_id = ? ORDER BY nome ASC");
            $result2->bindValue(1, estado_do_municipio($dados_familiar['bsc_municipio_id_natural']));
            $result2->execute();
            while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
              if ($dados_familiar['bsc_municipio_id_natural'] == $municipio['id']) {
                ?>
                <option label='<?= $municipio['nome']; ?>' selected='true' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                <?php
              } else {
                ?>
                <option label='<?= $municipio['nome']; ?>' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                <?php
              }
            }
          }
          ?>
        </select>
      </div>
    </div>

    <div class="row space-t-25">
      <div id="div_estado_civil" class="col-md-4 sel-form">
        <label for="">ESTADO CIVIL<sup>*</sup></label>
        <select id="estado_civil" name="estado_civil[]" class="selectpicker" data-live-search="true">
          <option value="">ESCOLHA SEU ESTADO CIVIL</option>
          <?php
          $result = $db->prepare("SELECT id, nome FROM bsc_estado_civil WHERE status = 1 ORDER BY nome ASC");
          $result->execute();
          while ($civil = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($dados_familiar['bsc_estado_civil_id'] == $civil['id']) {
              ?>
              <option selected='true' value='<?= $civil['id']; ?>'><?= $civil['nome']; ?></option>
              <?php
            } else {
              ?>
              <option value='<?= $civil['id']; ?>'><?= $civil['nome']; ?></option>
              <?php
            }
          }
          ?>
        </select>
      </div>
    </div>

    <div class="row space-t-10">
      <div class="col-xs-12">
        <div class="form-header">
          <div class="form-title"><i class="zmdi zmdi-home topo-icons-etapas"></i>
            <b>ENDEREÇO RESIDENCIAL</b>
          </div>
        </div><!--/ .form-header-->
      </div>
    </div>

    <div class="row space-t-20">
      <div class="row">
        <div class="col-md-4">
          <div class="col-md-12">
            <p class="c-black f-500">MORA NO MESMO ENDEREÇO?</p>
          </div>
          <div class="col-lg-4">
            <div class="radio m-b-15">
              <label>
                <input <?= $mora_sim; ?> id="mora_sim" name="mora_<?= $familiar_id; ?>" type="radio" value="1">
                <i class="input-helper"></i>
                SIM
              </label>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="radio m-b-15">
              <label>
                <input <?= $mora_nao; ?> id="mora_nao" name="mora_<?= $familiar_id; ?>" type="radio" value="0">
                <i class="input-helper"></i>
                NÃO
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div <?= $mesmo_endereco; ?> id="mesmo_endereco">
      <div class="row space-t-25">
        <div class="col-md-4 item-form">
          <div class="has-feedback form-group fg-float">
            <span class="zmdi zmdi-map form-control-icons"></span>
            <div id="div_cep" class="fg-line">
<!--              <input onKeyUp="consultacep(this)" id="cep" name="cep[]" type="text" class="input-sm form-control fg-input" maxlength="10" data-mask="00.000-000" value="<?= $dados_familiar['cep'] == "" ? "" : $dados_familiar['cep']; ?>">-->
              <input id="cep" name="cep[]" type="text" class="input-sm form-control fg-input" maxlength="10" data-mask="00.000-000" value="<?= $dados_familiar['cep'] == "" ? "" : $dados_familiar['cep']; ?>">
              <label class="fg-label">CEP<sup>*</sup></label>
            </div>
          </div>
        </div>
        <div id="div_estado" class="col-md-4 sel-form">
          <label for="estado">ESTADO<sup>*</sup></label>
          <select id="estado" name="estado[]" placeholder="ESTADO" class="selectpicker" data-live-search="true">
            <option value="">ESCOLHA O ESTADO</option>
            <?php
            $result = $db->prepare("SELECT id, nome, sigla FROM bsc_estado ORDER BY nome ASC");
            $result->execute();
            while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
              if (estado_do_municipio($dados_familiar['bsc_municipio_id']) == $estado['id']) {
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
        <div id="div_municipio" class="col-md-4 sel-form">
          <label for="municipio">CIDADE<sup>*</sup></label>
          <select id="municipio" name="municipio[]" class="selectpicker" data-live-search="true">
            <?php
            if ($familiar_id == "" || $mora_sim == "checked='true'") {
              ?>
              <option value="">ESCOLHA PRIMEIRO O ESTADO</option>
              <?php
            } else {
              $result2 = $db->prepare("SELECT nome, id FROM bsc_municipio ORDER BY nome ASC");
              $result2->execute();
              while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                if ($dados_familiar['bsc_municipio_id'] == $municipio['id']) {
                  ?>
                  <option label='<?= $municipio['nome']; ?>' selected='true' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                  <?php
                } else {
                  ?>
                  <option label='<?= $municipio['nome']; ?>' value='<?= $municipio['id']; ?>'><?= $municipio['nome']; ?></option>
                  <?php
                }
              }
            }
            ?>
          </select>
        </div>
      </div>

      <div class="row space-t-25">
        <div class="col-md-10 item-form">
          <div class="form-group fg-float">
            <div id="div_logradouro" class="fg-line">
              <input id="logradouro" name="logradouro[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['logradouro'] == "" ? "" : $dados_familiar['logradouro']; ?>">
              <label class="fg-label">LOGRADOURO<sup>*</sup></label>
            </div>
          </div>
        </div>
        <div class="col-md-2 item-form">
          <div class="has-feedback form-group fg-float">
            <span class="zmdi zmdi-keyboard form-control-icons"></span>
            <div id="div_numero" class="fg-line">
              <input id="numero" name="numero[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['numero'] == "" ? "" : $dados_familiar['numero']; ?>" data-mask="#####">
              <label class="fg-label">NÚMERO<sup>*</sup></label>
            </div>
          </div>
        </div>
      </div>

      <div class="row space-t-25">
        <div class="col-md-3 item-form">
          <div class="form-group fg-float">
            <div id="div_bairro" class="fg-line">
              <input onKeyUp="bairro_autocomplete()" id="bairro" name="bairro[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['bairro'] == "" ? "" : $dados_familiar['bairro']; ?>">
              <label class="fg-label">BAIRRO<sup>*</sup></label>
            </div>
          </div>
        </div>
        <div class="col-md-3 item-form">
          <div class="form-group fg-float">
            <div id="div_quadra" class="fg-line">
              <input id="quadra" name="quadra[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['quadra'] == "" ? "" : $dados_familiar['quadra']; ?>">
              <label class="fg-label">QUADRA</label>
            </div>
          </div>
        </div>
        <div class="col-md-3 item-form">
          <div class="form-group fg-float">
            <div id="div_casa" class="fg-line">
              <input id="casa" name="casa[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['lote'] == "" ? "" : $dados_familiar['lote']; ?>">
              <label class="fg-label">CASA</label>
            </div>
          </div>
        </div>
        <div class="col-md-3 item-form">
          <div class="form-group fg-float">
            <div id="div_complemento" class="fg-line">
              <input id="complemento" name="complemento[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['complemento'] == "" ? "" : $dados_familiar['complemento']; ?>">
              <label class="fg-label">COMPLEMENTO</label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <div class="form-header">
          <div class="form-title"><i class="zmdi zmdi-assignment-account topo-icons-etapas"></i>
            <b>INFORMAÇÕES DE CONTATO</b>
          </div>
        </div><!--/ .form-header-->
      </div>
    </div>

    <div class="row space-t-25">
      <div class="col-md-6 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-email form-control-icons"></span>
          <div id="div_contato_email" class="fg-line">
            <input type="text" id="contato_email" name="contato_email[]" class="form-control" value="<?= $dados_familiar['email'] == "" ? "" : $dados_familiar['email']; ?>">
            <label class="fg-label">E-MAIL PESSOAL</label>
          </div>
        </div>
      </div>
    </div>

    <div class="row space-t-20">
      <div class="col-md-3 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-phone form-control-icons"></span>
          <div id="div_residencial" class="fg-line">
            <input type="text" id="residencial" name="residencial[]" class="form-control" maxlength="14" data-mask="(00) 00000-0000" value="<?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 1"); ?>">
            <label class="fg-label">RESIDENCIAL</label>
          </div>
        </div>
      </div>
      <div class="col-md-3 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-smartphone-iphone form-control-icons"></span>
          <div id="div_celular" class="fg-line">
            <input type="text" id="celular" name="celular[]" class="form-control" maxlength="14" data-mask="(00) 00000-0000" value="<?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 2"); ?>">
            <label class="fg-label">CELULAR</label>
          </div>
        </div>
      </div>
      <div class="col-md-3 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-phone form-control-icons"></span>
          <div id="div_comercial" class="fg-line">
            <input type="text" id="comercial" name="comercial[]" class="form-control" maxlength="14" data-mask="(00) 00000-0000" value="<?= pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 3"); ?>">
            <label class="fg-label">COMERCIAL</label>
          </div>
        </div>
      </div>
      <div class="col-md-3 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-phone-forwarded form-control-icons"></span>
          <div id="div_ramal" class="fg-line">
            <input type="text" id="ramal" name="ramal[]" class="form-control" maxlength="6" data-mask="000000" value="<?= pesquisar_tabela("ramal", "hab_pessoa_contato", "hab_pessoa_id", "=", $pessoa_id, "AND hab_contato_tipo_id = 3"); ?>">
            <label class="fg-label">RAMAL</label>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <div class="form-header">
          <div class="form-title"><i class="zmdi zmdi-city topo-icons-etapas"></i>
            <b>INFORMAÇÕES DE TRABALHO</b>
          </div>
        </div><!--/ .form-header-->
      </div>
    </div>

    <div class="row space-t-20">
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <p class="c-black f-500">VOCÊ TRABALHA?</p>
          </div>
          <div class="col-lg-4">
            <div class="radio m-b-15">
              <label>
                <input <?= $dados_familiar['cargo'] != "" ? "checked='true'" : ""; ?> id="trabalha_sim" name="trabalha_<?= $familiar_id; ?>" type="radio" value="1">
                <i class="input-helper"></i>
                SIM
              </label>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="radio m-b-15">
              <label>
                <input <?= $dados_familiar['cargo'] != "" ? "" : "checked='true'"; ?> id="trabalha_nao" name="trabalha_<?= $familiar_id; ?>" type="radio" value="0">
                <i class="input-helper"></i>
                NÃO
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="trabalha_sim_nao_mesmo_endereco" <?= $dados_familiar['cargo'] != "" ? "" : "style='display: none'"; ?> class="row space-t-10">
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <p class="c-black f-500">TRABALHA NO MESMO ENDEREÇO?</p>
          </div>
          <div class="col-lg-4">
            <div class="radio m-b-15">
              <label>
                <input <?= $dados_familiar['trab_mesmo_endereco'] == 1 ? "checked='true'" : ""; ?> id="trab_mesmo_endereco_sim" name="trab_mesmo_endereco_<?= $familiar_id; ?>" type="radio" value="1">
                <i class="input-helper"></i>
                SIM
              </label>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="radio m-b-15">
              <label>
                <input <?= $dados_familiar['trab_mesmo_endereco'] == 0 ? "checked='true'" : ""; ?> id="trab_mesmo_endereco_nao" name="trab_mesmo_endereco_<?= $familiar_id; ?>" type="radio" value="0">
                <i class="input-helper"></i>
                NÃO
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div <?= $dados_familiar['cargo'] != "" ? "" : "style='display: none'"; ?> id="trabalha_sim_nao" class="row space-t-25">
      <div class="col-md-3 item-form">
        <div class="form-group fg-float">
          <div id="div_cargo_funcao" class="fg-line">
            <input id="cargo_funcao" name="cargo_funcao[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['cargo'] == "" ? "" : $dados_familiar['cargo']; ?>">
            <label class="fg-label">OCUPAÇÃO/CARGO/FUNÇÃO</label>
          </div>
        </div>
      </div>
      <div id="trab_mesmo_endereco_sim_nao" <?= $dados_familiar['trab_mesmo_endereco'] == 1 ? "style='display: none'" : "" ?>>
        <div class="col-md-3 item-form">
          <div class="form-group fg-float">
            <div id="div_local_trabalho" class="fg-line">
              <input id="local_trabalho" name="local_trabalho[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['instituicao'] == "" ? "" : $dados_familiar['instituicao']; ?>">
              <label class="fg-label">LOCAL DE TRABALHO</label>
            </div>
          </div>
        </div>
        <div class="col-md-4 item-form">
          <div class="form-group fg-float">
            <div id="div_trab_endereco" class="fg-line">
              <input id="trab_endereco" name="trab_endereco[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['endereco'] == "" ? "" : $dados_familiar['endereco']; ?>">
              <label class="fg-label">ENDEREÇO</label>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-2 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-calendar form-control-icons"></span>
          <div id="div_data_inicio" class="fg-line">
            <input id="data_inicio" name="data_inicio[]" type="text" class="form-control date-picker" value="<?= $dados_familiar['data_inicio'] == "" || $dados_familiar['data_inicio'] == "0000-00-00 00:00:00" ? "" : obterDataBRTimestamp($dados_familiar['data_inicio']); ?>" data-mask="00/00/0000">
            <label class="fg-label">DATA INÍCIO</label>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <div class="form-header">
          <div class="form-title"><i class="zmdi zmdi-money topo-icons-etapas"></i>
            <b>INFORMAÇÕES DE RENDA</b>
          </div>
        </div><!--/ .form-header-->
      </div>
    </div>

    <div id="clonar" class="row space-t-25">

      <div id="div_tipo_renda" class="col-md-4 sel-form">
        <label for="">TIPO DE RENDA</label>
        <select id="tipo_renda" name="tipo_renda_<?= $contador; ?>[]" class="selectpicker" data-live-search="true">
          <option value="">ESCOLHA O TIPO DE RENDA</option>
          <?php
          $renda_tipo = 0;
          $result = $db->prepare("SELECT id, nome FROM hab_renda_tipo WHERE status = 1 ORDER BY nome ASC");
          $result->execute();
          while ($tipo_renda = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($renda_tipo == $tipo_renda['id']) {
              ?>
              <option selected='true' value='<?= $tipo_renda['id']; ?>'><?= $tipo_renda['nome']; ?></option>
              <?php
            } else {
              ?>
              <option value='<?= $tipo_renda['id']; ?>'><?= $tipo_renda['nome']; ?></option>
              <?php
            }
          }
          ?>
        </select>
      </div>
      <div class="col-md-3 item-form">
        <div class="has-feedback form-group fg-float">
          <span class="zmdi zmdi-money-box form-control-icons"></span>
          <div id="div_renda_valor" class="fg-line">
            <input id="renda_valor" name="renda_valor_<?= $contador; ?>[]" type="text" class="input-sm form-control fg-input" value="">
            <label class="fg-label">VALOR DA RENDA</label>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group fg-float">
          <div class="fg-line">
            <a id="add_renda" class="btn palette-Light-Green-200 bg btn-success">
              <i class="zmdi zmdi-plus"></i>
            </a>
            &nbsp;
            <a id="remover_renda" class="btn palette-Red-200 bg btn-danger" style="display: none">
              <i class="zmdi zmdi-close"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <?php
    $result1 = $db->prepare("SELECT valor, hab_renda_tipo_id FROM hab_pessoa_renda WHERE hab_pessoa_id = ?");
    $result1->bindValue(1, $pessoa_id);
    $result1->execute();
    while ($pessoa_renda = $result1->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <div id="clonar" class="row space-t-25">

        <div id="div_tipo_renda" class="col-md-4 sel-form">
          <label for="">TIPO DE RENDA</label>
          <select id="tipo_renda" name="tipo_renda_<?= $contador; ?>[]" class="selectpicker" data-live-search="true">
            <option value="">ESCOLHA O TIPO DE RENDA</option>
            <?php
            $renda_tipo = 0;
            $result = $db->prepare("SELECT id, nome FROM hab_renda_tipo WHERE status = 1 ORDER BY nome ASC");
            $result->execute();
            while ($tipo_renda = $result->fetch(PDO::FETCH_ASSOC)) {
              if ($pessoa_renda['hab_renda_tipo_id'] == $tipo_renda['id']) {
                ?>
                <option selected='true' value='<?= $tipo_renda['id']; ?>'><?= $tipo_renda['nome']; ?></option>
                <?php
              } else {
                ?>
                <option value='<?= $tipo_renda['id']; ?>'><?= $tipo_renda['nome']; ?></option>
                <?php
              }
            }
            ?>
          </select>
        </div>
        <div class="col-md-3 item-form">
          <div class="has-feedback form-group fg-float">
            <span class="zmdi zmdi-money-box form-control-icons"></span>
            <div id="div_renda_valor" class="fg-line">
              <input id="renda_valor" name="renda_valor_<?= $contador; ?>[]" type="text" class="input-sm form-control fg-input" value="<?= fdec($pessoa_renda['valor']); ?>">
              <label class="fg-label">VALOR DA RENDA</label>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group fg-float">
            <div class="fg-line">
              <a id="add_renda" class="btn palette-Light-Green-200 bg btn-success">
                <i class="zmdi zmdi-plus"></i>
              </a>
              &nbsp;
              <a id="remover_renda" class="btn palette-Red-200 bg btn-danger">
                <i class="zmdi zmdi-close"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <?php
    }
    ?>

    <div class="row">
      <div class="col-xs-12">
        <div class="form-header">
          <div class="form-title"><i class="zmdi zmdi-graduation-cap topo-icons-etapas"></i>
            <b>ESCOLARIDADE</b>
          </div>
        </div><!--/ .form-header-->
      </div>
    </div>

    <div class="row space-t-25">
      <div id="div_grau_escolar" class="col-md-6 sel-form">
        <label for="">GRAU ESCOLAR<sup>*</sup></label>
        <select id="grau_escolar" name="grau_escolar[]" class="selectpicker" data-live-search="true">
          <option value="">ESCOLHA O SEU GRAU ESCOLAR</option>
          <?php
          $result = $db->prepare("SELECT id, nome FROM hab_grau_escolar WHERE status = 1 ORDER BY nome ASC");
          $result->execute();
          while ($grau = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($dados_familiar['hab_grau_escolar_id'] == $grau['id']) {
              ?>
              <option selected='true' value='<?= $grau['id']; ?>'><?= $grau['nome']; ?></option>
              <?php
            } else {
              ?>
              <option value='<?= $grau['id']; ?>'><?= $grau['nome']; ?></option>
              <?php
            }
          }
          ?>
        </select>
      </div>
    </div>

    <div class="row space-t-25">
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <p class="c-black f-500">ESTÁ ESTUDANDO?</p>
          </div>
          <div class="col-lg-4">
            <div class="radio m-b-15">
              <label>
                <input <?= $estudando_sim; ?> id="estudando_sim" name="estudando_<?= $familiar_id; ?>" type="radio" value="1">
                <i class="input-helper"></i>
                SIM
              </label>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="radio m-b-15">
              <label>
                <input <?= $estudando_nao; ?> id="estudando_nao" name="estudando_<?= $familiar_id; ?>" type="radio" value="0">
                <i class="input-helper"></i>
                NÃO
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div <?= $esta_estudando; ?> id="estiver_estudando">
      <div class="row space-t-25">
        <div class="col-md-6 item-form">
          <div class="form-group fg-float">
            <div id="div_nome_instituicao" class="fg-line">
              <input id="nome_instituicao" name="nome_instituicao[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['instituicao_nome']; ?>">
              <label class="fg-label">NOME DA INSTITUIÇÃO</label>
            </div>
          </div>
        </div>
        <div class="col-md-3 item-form">
          <div class="form-group fg-float">
            <div id="div_serie_periodo" class="fg-line">
              <input id="serie_periodo" name="serie_periodo[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['serie_periodo']; ?>">
              <label class="fg-label">SÉRIE/PERÍODO</label>
            </div>
          </div>
        </div>
        <div id="div_rede_publica_privada" class="col-md-3 sel-form">
          <label for="rede_publica_privada">TIPO</label>
          <select id="rede_publica_privada" name="rede_publica_privada[]" class="selectpicker" data-live-search="true">
            <option value="">ESCOLHA A NATUREZA DA INSTITUIÇÃO</option>
            <?php
            $result = $db->prepare("SELECT id, nome FROM hab_instituicao_natureza WHERE status = 1 ORDER BY nome ASC");
            $result->execute();
            while ($natureza = $result->fetch(PDO::FETCH_ASSOC)) {
              if ($dados_familiar['hab_instituicao_natureza_id'] == $natureza['id']) {
                ?>
                <option selected='true' value='<?= $natureza['id']; ?>'><?= $natureza['nome']; ?></option>
                <?php
              } else {
                ?>
                <option value='<?= $natureza['id']; ?>'><?= $natureza['nome']; ?></option>
                <?php
              }
            }
            ?>
          </select>
        </div>



        <div id="div_rede_publica_privada" class="col-md-3">

        </div>
      </div>

      <div <?= $natureza_instituicao; ?> id="natureza_instituicao">
        <div class="row space-t-10">
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-12">
                <p class="c-black f-500">FINANCIADO POR MEIOS PRÓPRIOS?</p>
              </div>
              <div class="col-lg-4">
                <div class="radio m-b-15">
                  <label>
                    <input <?= $financia_sim; ?> id="financia_sim" name="financia_<?= $familiar_id; ?>" type="radio" value="1">
                    <i class="input-helper"></i>
                    SIM
                  </label>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="radio m-b-15">
                  <label>
                    <input <?= $financia_nao; ?> id="financia_nao" name="financia_<?= $familiar_id; ?>" type="radio" value="2">
                    <i class="input-helper"></i>
                    NÃO
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div <?= $proprios_meios; ?> id="proprios_meios">
          <div class="row space-t-25">
            <div class="col-md-6 sel-form">
              <div class="form-group fg-float">
                <div id="div_programa_social" class="fg-line">
                  <p>PROGRAMA SOCIAL</p>
                  <select id="programa_social" name="programa_social[]" class="selectpicker" data-live-search="true">
                    <option value="">ESCOLHA O PROGRAMA SOCIAL</option>
                    <?php
                    $result = $db->prepare("SELECT id, nome FROM hab_programa_social WHERE status = 1 ORDER BY nome ASC");
                    $result->execute();
                    while ($social = $result->fetch(PDO::FETCH_ASSOC)) {
                      if ($dados_familiar['hab_programa_social_id'] == $social['id']) {
                        ?>
                        <option selected='true' value='<?= $social['id']; ?>'><?= $social['nome']; ?></option>
                        <?php
                      } else {
                        ?>
                        <option value='<?= $social['id']; ?>'><?= $social['nome']; ?></option>
                        <?php
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-3 item-form">
              <div class="form-group fg-float">
                <div id="div_porcentagem" class="fg-line">
                  <input id="porcentagem" name="porcentagem[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['bolsa_percentual']; ?>" data-mask="###" max="3">
                  <label class="fg-label">PORCENTAGEM</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <div class="form-header">
          <div class="form-title"><i class="zmdi zmdi-hospital topo-icons-etapas"></i>
            <b>INFORMAÇÕES COMPLEMENTARES</b>
          </div>
        </div><!--/ .form-header-->
      </div>
    </div>

    <div class="row space-t-10">
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <p class="c-black f-500">POSSUI ALGUM TIPO DE DEFICIÊNCIA?</p>
          </div>
          <div class="col-lg-4">
            <div class="radio m-b-15">
              <label>
                <input <?= $deficiencia_sim; ?> id="deficiencia_sim" name="deficiencia_<?= $familiar_id; ?>" type="radio" value="1">
                <i class="input-helper"></i>
                SIM
              </label>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="radio m-b-15">
              <label>
                <input <?= $deficiencia_nao; ?> id="deficiencia_nao" name="deficiencia_<?= $familiar_id; ?>" type="radio" value="0">
                <i class="input-helper"></i>
                NÃO
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <br>
    <div class="row">
      <div class="col-md-12">
        <p class="c-black f-500">CLASSIFICAÇÃO INTERNACIONAL DE DOENÇA</p>
      </div>
    </div>
    <div class="row space-t-25">
      <div id="div_capitulo" class="col-md-4 sel-form">
        <label for="">CAPÍTULO</label>
        <select id="capitulo" name="capitulo[]" placeholder="Capítulo" class="selectpicker" data-live-search="true">
          <option value="">Escolha o capítulo</option>
          <?php
          $result = $db->prepare("SELECT id, catinic, catfim, descricao
						FROM hab_cid10_capitulo
						ORDER BY catinic ASC");
          $result->execute();
          while ($capitulo = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($dados_familiar['hab_cid10_capitulo_id'] == $capitulo['id']) {
              ?>
              <option selected="true" value="<?= $capitulo['id']; ?>" catinic1="<?= $capitulo['catinic']; ?>" catfim1="<?= $capitulo['catfim']; ?>" ><?= ($capitulo['catinic'] . " até " . $capitulo['catfim'] . " - " . $capitulo['descricao']); ?></option>
              <?php
            } else {
              ?>
              <option value="<?= $capitulo['id']; ?>" catinic1="<?= $capitulo['catinic']; ?>" catfim1="<?= $capitulo['catfim']; ?>" ><?= ($capitulo['catinic'] . " até " . $capitulo['catfim'] . " - " . $capitulo['descricao']); ?></option>
              <?php
            }
          }
          ?>
        </select>
      </div>
      <div id="div_grupo" class="col-md-4 sel-form">
        <label>GRUPO</label>
        <select id="grupo" name="grupo[]" placeholder="Grupo" class="selectpicker" data-live-search="true">
          <?php
          if ($familiar_id == "" || $dados_familiar['hab_cid10_grupo_id'] == "") {
            ?>
            <option value="">ESCOLHA PRIMEIRO O CAPÍTULO</option>
            <?php
          } else {
            $result2 = $db->prepare("SELECT * FROM hab_cid10_grupo ORDER BY catinic ASC");
            $result2->execute();
            while ($grupo = $result2->fetch(PDO::FETCH_ASSOC)) {
              if ($dados_familiar['hab_cid10_grupo_id'] == $grupo['id']) {
                ?>
                <option selected="true" value="<?= $grupo['id']; ?>" catinic2="<?= $grupo['catinic']; ?>" catfim2="<?= $grupo['catfim']; ?>"><?= $grupo['catinic'] . " até " . $grupo['catfim'] . " - " . $grupo['descricao']; ?></option>
                <?php
              }
            }
          }
          ?>
        </select>
      </div>
      <div id="div_categoria" class="col-md-4 sel-form">
        <label for="">CATEGORIA</label>
        <select id="categoria" name="categoria[]" placeholder="Categoria" class="selectpicker" data-live-search="true">
          <?php
          if ($familiar_id == "" || $dados_familiar['hab_cid10_categoria_id'] == "") {
            ?>
            <option value="">ESCOLHA PRIMEIRO O GRUPO</option>
            <?php
          } else {
            $result3 = $db->prepare("SELECT * FROM hab_cid10_categoria ORDER BY cat ASC");
            $result3->execute();
            while ($categoria = $result3->fetch(PDO::FETCH_ASSOC)) {
              if ($dados_familiar['hab_cid10_categoria_id'] == $categoria['id']) {
                ?>
                <option  value="<?= $categoria['id']; ?>"><?= $categoria['cat'] . " - " . $categoria['descricao']; ?></option>
                <?php
              }
            }
          }
          ?>
        </select>
      </div>
    </div>
    <div class="row space-t-40">
      <div class="col-md-4 item-form">
        <div class="form-group fg-float">
          <div id="div_cad_unico" class="fg-line">
            <input id="cad_unico" name="cad_unico[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['cadastro_unico']; ?>">
            <label class="fg-label">CAD. ÚNICO<sup>*</sup></label>
          </div>
        </div>
      </div>
      <div class="col-md-4 item-form">
        <div class="form-group fg-float">
          <div id="div_nis" class="fg-line">
            <input id="nis" name="nis[]" type="text" class="input-sm form-control fg-input" value="<?= $dados_familiar['nis']; ?>" data-mask="###########" maxlength="11">
            <label class="fg-label">NIS<sup>*</sup></label>
          </div>
        </div>
      </div>
    </div>
  </div> <!--/ .form-wizard-->
</div><!--/ .container-->