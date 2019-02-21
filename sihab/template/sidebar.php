<aside id="s-user-alerts" class="sidebar">
  <ul class="tab-nav tn-justified tn-icon m-t-10" data-tab-color="teal">
    <!--    <li><a class="sua-messages" href="#sua-messages" data-toggle="tab"><i class="zmdi zmdi-email"></i></a></li>-->
    <!--    <li><a class="sua-notifications" href="#sua-notifications" data-toggle="tab"><i class="zmdi zmdi-notifications"></i></a></li>-->
    <!--    <li><a class="sua-tasks" href="#sua-tasks" data-toggle="tab"><i class="zmdi zmdi-view-list-alt"></i></a></li>-->
  </ul>
  <div class="tab-content">
    <div class="list-group lg-alt c-overflow mCustomScrollbar _mCS_2 mCS-autoHide mCS_no_scrollbar" style="position: relative; overflow: visible;">
      <div id="mCSB_2" class="mCustomScrollBox mCS-minimal-dark mCSB_vertical_horizontal mCSB_outside" style="max-height: 816px;" tabindex="0">
        <div id="mCSB_2_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y mCS_x_hidden mCS_no_scrollbar_x" style="position: relative; top: 0px; left: 0px; width: 100%;" dir="ltr">

          <?php
          $qtd = 0;

          $result = $db->prepare("SELECT id, hab_candidato_id, hab_tipo_situacao_id
                                 FROM hab_candidato_situacao
			                              WHERE id IN (SELECT MAX(id) FROM hab_candidato_situacao GROUP BY hab_candidato_id)
                                 GROUP BY hab_candidato_id");
          $result->execute();

          while ($situacao = $result->fetch(PDO::FETCH_ASSOC)) {

            if ($situacao['hab_tipo_situacao_id'] == 4) {

              $result2 = $db->prepare("SELECT c.validacao, u.foto, s.hab_candidato_id AS id, s.data_cadastro, u.nome AS usuario_nome
                          FROM hab_candidato_situacao s
                          LEFT JOIN seg_usuario AS u ON u.id = s.seg_usuario_pai_id
                          LEFT JOIN hab_candidato AS c ON c.id = s.hab_candidato_id
                          WHERE s.hab_candidato_id = ? AND hab_tipo_situacao_id = 2 AND s.seg_usuario_pai_id = ?
                          GROUP BY s.hab_candidato_id");
              $result2->bindValue(1, $situacao['hab_candidato_id']);
              $result2->bindValue(2, $_SESSION['id']);
              $result2->execute();

              while ($candidato_situacao = $result2->fetch(PDO::FETCH_ASSOC)) {

                if (pesquisar_tabela("seg_usuario_pai_id", "hab_candidato_situacao", "hab_candidato_id", "=", $situacao['hab_candidato_id'], "AND hab_tipo_situacao_id = 2") == $_SESSION['id']) {
                  $qtd = $result2->rowCount();
                  ?>

                  <a href="<?= PORTAL_URL; ?>sistema/candidato/visualiza/<?= $candidato_situacao['id']; ?>" class="list-group-item media">
                    <div class="pull-left">
                      <img class="avatar-img" src="<?= $candidato_situacao['foto'] == NULL || $candidato_situacao['foto'] == '' ? PORTAL_URL . "assets/img/profile-pics/1.jpg" : $candidato_situacao['foto']; ?>" alt="">
                    </div>
                    <div class="media-body">
                      <div class="lgi-heading">
                        Validador:
                        <small><?= $candidato_situacao['usuario_nome']; ?></small>
                      </div>
                      <div class="lgi-heading">
                        Erros:
                        <small><?= qtd_erros_validados($candidato_situacao['validacao']); ?></small>
                      </div>
                      <div class="lgi-heading">
                        Data:
                        <small><?= obterDataBRTimestamp($candidato_situacao['data_cadastro']) . " ÀS " . obterHoraTimestamp($candidato_situacao['data_cadastro']); ?></small>
                      </div>
                    </div>
                  </a>

                  <?php
                }
              }
            }
          }

          if ($qtd == 0) {
            echo "<center><b>Nenhuma notificação encontrada.</b></center>";
          }
          ?>

        </div>
      </div>
    </div>
  </div>
</aside>
<aside id="s-main-menu" class="sidebar">
  <div class="smm-header">
    <i class="zmdi zmdi-long-arrow-left" data-ma-action="sidebar-close"></i>
  </div>
  <ul class="smm-alerts">
    <li data-user-alert="sua-messages" data-ma-action="sidebar-open" data-ma-target="user-alerts">
      <i class="zmdi zmdi-email"></i>
    </li>
    <!--    <li data-user-alert="sua-notifications" data-ma-action="sidebar-open" data-ma-target="user-alerts">
          <i class="zmdi zmdi-notifications"></i>
        </li>-->
    <li data-user-alert="sua-tasks" data-ma-action="sidebar-open" data-ma-target="user-alerts">
      <i class="zmdi zmdi-view-list-alt"></i>
    </li>
  </ul>
  <ul class="main-menu">
    <li class="active">
      <a href="<?= PORTAL_URL; ?>dashboard">
        <i class="zmdi zmdi-home"></i>
        INÍCIO
      </a>
    </li>
    <?php
    if (vf_modulo_objeto_acao_pagina("hab", "candidato_lista", "visualizar") || vf_modulo_objeto_acao_pagina("hab", "candidato_cadastro", "visualizar")) {
      ?>
      <li class="sub-menu">
        <a href="" data-ma-action="submenu-toggle">
          <i class="zmdi zmdi-assignment-account"></i>
          CANDIDATO
        </a>
        <ul>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "candidato_cadastro", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/candidato/etapa1">Cadastro</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "candidato_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/candidato/lista">Lista</a>
            </li><?php } ?>
        </ul>
      </li>
      <?php
    }
    ?>
    <!--    <li>
          <a href="<?= PORTAL_URL; ?>dashboard"><i class="zmdi zmdi-assignment"></i> ARQUIVO MORTO</a>
        </li>-->
    <?php
    if (vf_modulo_objeto_acao_pagina("hab", "empreendimento_lista", "visualizar") || vf_modulo_objeto_acao_pagina("hab", "acao_lista", "visualizar") || vf_modulo_objeto_acao_pagina("hab", "objeto_lista", "visualizar") || vf_modulo_objeto_acao_pagina("hab", "modulo_lista", "visualizar") || vf_modulo_objeto_acao_pagina("hab", "grupo_lista", "visualizar") || vf_modulo_objeto_acao_pagina("hab", "usuario_lista", "visualizar") || vf_modulo_objeto_acao_pagina("hab", "origem_demanda_lista", "visualizar") || vf_modulo_objeto_acao_pagina("hab", "especificidade_lista", "visualizar")) {
      ?>

      <li class="sub-menu">
        <a href="" data-ma-action="submenu-toggle">
          <i class="zmdi zmdi-settings"></i>
          CENTRAL DE CONTROLE
        </a>
        <ul>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "empreendimento_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/empreendimento/lista">Empreendimento</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "acao_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/acao/lista">Ação</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "objeto_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/objeto/lista">Objeto</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "modulo_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/modulo/lista">Módulo</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "grupo_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/grupo/lista">Grupo</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "usuario_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/usuario/lista">Usuário</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "origem_demanda_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/origem_demanda/lista">Origem da Demanda</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "especificidade_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/especificidade/lista">Especificidade</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "loteamento_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/loteamento/lista">Loteamento</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "casa_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/casa/lista">Casas</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "apto_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/casa/apto">Apto</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "sorteio_lista", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/sorteio/index">Sorteio</a>
            </li><?php } ?>

        </ul>
      </li>
    <?php } ?>
    <?php
    if (vf_modulo("candidato")) {
      ?>
      <li class="sub-menu">
        <a href="" data-ma-action="submenu-toggle">
          <i class="zmdi zmdi-assignment-account"></i>
          ARQUIVO MORTO
        </a>
        <ul>
          <?php if (vf_modulo_objeto_acao_pagina("hab", "candidato_cadastro", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/candidato/etapa1">Cadastro</a>
            </li><?php } ?>
          <?php if (vf_modulo_objeto_acao_pagina("han", "candidato_lista_morto", "visualizar")) { ?><li>
              <a href="<?= PORTAL_URL; ?>sistema/candidato/lista_morto">Lista</a>
            </li><?php } ?>
        </ul>
      </li>
      <?php
    }
    ?>
    <li>
      <a href="<?= PORTAL_URL; ?>logout">
        <i class="zmdi zmdi-power"></i>
        SAIR
      </a>
    </li>
  </ul>
</aside>