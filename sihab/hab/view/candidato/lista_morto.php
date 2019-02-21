<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>

<?php
$_SESSION['retroativo'] = 1;

$stmt = $db->prepare("SELECT s.id, s.nome, s.status
                      FROM bsc_sexo AS s
                      WHERE 1 = 1
                      ORDER BY s.nome DESC");
$stmt->execute();
$rsSexo = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $db->prepare("SELECT ec.id, ec.nome, ec.status
                      FROM bsc_estado_civil AS ec
                      WHERE 1 = 1
                      ORDER BY ec.nome ASC");
$stmt->execute();
$rsEstadosCivis = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $db->prepare("SELECT pc.id, pc.nome, pc.status
                      FROM bsc_pele_cor AS pc
                      WHERE 1 = 1
                      ORDER BY pc.nome ASC");
$stmt->execute();
$rsPeleCores = $stmt->fetchAll(PDO::FETCH_ASSOC);
// O id 1 deve ser referente ao Estado ACRE
$stmt = $db->prepare("SELECT m.id, m.nome, m.status
                      FROM bsc_municipio AS m
                      WHERE m.estado_id = 1
                      ORDER BY m.nome ASC");
$stmt->execute();
$rsMunicipiosAcre = $stmt->fetchAll(PDO::FETCH_ASSOC);
// O id 1 deve ser referente ao Estado ACRE
$stmt = $db->prepare("SELECT pe.id, pe.bairro, pe.status 
                      FROM hab_candidato AS c 
                      LEFT JOIN hab_pessoa AS p ON p.id = c.hab_pessoa_id 
                      LEFT JOIN hab_pessoa_endereco AS pe ON pe.hab_pessoa_id = p.id 
                      WHERE 1 = 1 AND pe.bairro IS NOT NULL AND pe.bairro NOT LIKE '' 
                      GROUP BY UPPER(pe.bairro) 
                      ORDER BY UPPER(pe.bairro) ASC");
$stmt->execute();
$rsBairrosAcre = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $db->prepare("SELECT dt.id, dt.nome, dt.status
                      FROM bsc_deficiencia_tipo AS dt 
                      WHERE 1 = 1 
                      GROUP BY UPPER(dt.nome) 
                      ORDER BY UPPER(dt.nome) ASC");
$stmt->execute();
$rsDeficienciaTipo = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $db->prepare("SELECT n.id, n.nome, n.status
                      FROM hab_instituicao_natureza AS n
                      WHERE 1 = 1
                      ORDER BY n.nome DESC");
$stmt->execute();
$rsInstituicaoNaturezas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $db->prepare("SELECT ge.id, ge.nome, ge.status
                      FROM hab_grau_escolar AS ge
                      WHERE 1 = 1
                      ORDER BY ge.id ASC");
$stmt->execute();
$rsGrausEscolares = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $db->prepare("SELECT 
                      (SELECT COUNT(hpe.id) AS qtd FROM hab_pessoa_renda AS hpe WHERE hpe.hab_pessoa_id = hp.id AND hpe.hab_renda_tipo_id = 1) AS qtd_renda_comp, 
                      (SELECT COUNT(hpe.id) AS qtd FROM hab_pessoa_renda AS hpe WHERE hpe.hab_pessoa_id = hp.id AND hpe.hab_renda_tipo_id = 2) AS qtd_renda_ncomp, 
                      (SELECT MAX(hpe.valor) AS qtd FROM hab_pessoa_renda AS hpe WHERE hpe.hab_pessoa_id = hp.id AND hpe.hab_renda_tipo_id = 1) AS min_renda, 
                      (SELECT MIN(hpe.valor) AS qtd FROM hab_pessoa_renda AS hpe WHERE hpe.hab_pessoa_id = hp.id AND hpe.hab_renda_tipo_id = 2) AS max_renda
                      FROM hab_candidato hc
                      LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                      WHERE hp.cadastro_retroativo_ano IS NOT NULL
                      GROUP BY qtd_renda_comp, qtd_renda_ncomp");
$stmt->execute();
$rsCandidatos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$opSituacaoEmDigitacao = isset($_REQUEST['situacao_em_digitacao']) && $_REQUEST['situacao_em_digitacao'] != '' ? (array (
  'value' => $_REQUEST['situacao_em_digitacao'],
  'query' => $_REQUEST['situacao_em_digitacao'] 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opSituacaoAguardandoValidacao = isset($_REQUEST['situacao_aguardando_validacao']) && $_REQUEST['situacao_aguardando_validacao'] != '' ? (array (
  'value' => $_REQUEST['situacao_aguardando_validacao'],
  'query' => $_REQUEST['situacao_aguardando_validacao'] 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opSituacaoFinalizado = isset($_REQUEST['situacao_finalizado']) && $_REQUEST['situacao_finalizado'] != '' ? (array (
  'value' => $_REQUEST['situacao_finalizado'],
  'query' => $_REQUEST['situacao_finalizado'] 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opCpf = isset($_REQUEST['cpf']) && $_REQUEST['cpf'] != '' ? (array (
  'value' => $_REQUEST['cpf'],
  'query' => " AND hp.cpf LIKE '" . $_REQUEST['cpf'] . "' " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opNome = isset($_REQUEST['nome']) && $_REQUEST['nome'] != '' ? (array (
  'value' => $_REQUEST['nome'],
  'query' => " AND hp.nome LIKE UPPER('%" . $_REQUEST['nome'] . "%') " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opSexo = isset($_REQUEST['sexo']) && $_REQUEST['sexo'] != '' ? (array (
  'value' => $_REQUEST['sexo'],
  'query' => " AND hp.bsc_sexo_id = " . $_REQUEST['sexo'] . " " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opDtNcIni = isset($_REQUEST['nascimento_inicio']) && $_REQUEST['nascimento_inicio'] != '' ? (array (
  'value' => convertDataBR2ISO($_REQUEST['nascimento_inicio']),
  'query' => " AND hp.data_nascimento >= '" . convertDataBR2ISO($_REQUEST['nascimento_inicio']) . "' " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opDtNcFim = isset($_REQUEST['nascimento_fim']) && $_REQUEST['nascimento_fim'] != '' ? (array (
  'value' => convertDataBR2ISO($_REQUEST['nascimento_fim']),
  'query' => " AND hp.data_nascimento <= '" . convertDataBR2ISO($_REQUEST['nascimento_fim']) . "' " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opEstCivil = isset($_REQUEST['estado_civil']) && $_REQUEST['estado_civil'] != '' ? (array (
  'value' => $_REQUEST['estado_civil'],
  'query' => " AND hp.bsc_estado_civil_id = " . $_REQUEST['estado_civil'] . " " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opUniaoEst = isset($_REQUEST['uniao_estavel']) && $_REQUEST['uniao_estavel'] != '' ? (array (
  'value' => $_REQUEST['uniao_estavel'],
  'query' => " AND hp.uniao_estavel = " . $_REQUEST['uniao_estavel'] . " " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opQtdFilho = isset($_REQUEST['filhos_qtd']) && $_REQUEST['filhos_qtd'] != - 1 ? (array (
  'value' => $_REQUEST['filhos_qtd'],
  'query' => $_REQUEST['filhos_qtd'] 
)) : (array (
  'value' => - 1,
  'query' => '' 
));
$opEstrangeiro = isset($_REQUEST['estrangeiro']) && $_REQUEST['estrangeiro'] != '' ? ($_REQUEST['estrangeiro'] == 1 ? (array (
  'value' => $_REQUEST['estrangeiro'],
  'query' => " AND hp.bsc_nacionalidade_id <> 30 " 
)) : ($_REQUEST['estrangeiro'] == 2 ? (array (
  'value' => $_REQUEST['estrangeiro'],
  'query' => " AND hp.bsc_nacionalidade_id = 30 " 
)) : (array (
  'value' => NULL,
  'query' => '' 
)))) : (array (
  'value' => NULL,
  'query' => '' 
));
$opCorRaca = isset($_REQUEST['cor_raca']) && $_REQUEST['cor_raca'] != '' ? (array (
  'value' => $_REQUEST['cor_raca'],
  'query' => " AND hp.bsc_pele_cor_id = " . $_REQUEST['cor_raca'] . " " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opMunicipio = isset($_REQUEST['municipio']) && $_REQUEST['municipio'] != '' ? (array (
  'value' => $_REQUEST['municipio'],
  'query' => " AND hpe.bsc_municipio_id = " . $_REQUEST['municipio'] . " " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opBairro = isset($_REQUEST['bairro']) && $_REQUEST['bairro'] != '' ? (array (
  'value' => $_REQUEST['bairro'],
  'query' => " AND UPPER(hpe.bairro) LIKE UPPER('%" . $_REQUEST['bairro'] . "%') " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opCadIni = isset($_REQUEST['cadastro_inicio']) && $_REQUEST['cadastro_inicio'] != '' ? (array (
  'value' => convertDataBR2ISO($_REQUEST['cadastro_inicio']),
  'query' => " AND hc.data_cadastro >= '" . convertDataBR2ISO($_REQUEST['cadastro_inicio']) . "' " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opCadFim = isset($_REQUEST['cadastro_fim']) && $_REQUEST['cadastro_fim'] != '' ? (array (
  'value' => convertDataBR2ISO($_REQUEST['cadastro_fim']),
  'query' => " AND hc.data_cadastro <= '" . convertDataBR2ISO($_REQUEST['cadastro_fim']) . "' " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opCadUnico = isset($_REQUEST['cad_unico']) && $_REQUEST['cad_unico'] != '' ? ($_REQUEST['cad_unico'] == 1 ? (array (
  'value' => $_REQUEST['cad_unico'],
  'query' => "  AND hp.cadastro_unico IS NOT NULL " 
)) : ($_REQUEST['cad_unico'] == 2 ? (array (
  'value' => $_REQUEST['cad_unico'],
  'query' => "  AND hp.cadastro_unico IS NULL " 
)) : (array (
  'value' => NULL,
  'query' => '' 
)))) : (array (
  'value' => NULL,
  'query' => '' 
));
$opBolsaFamilia = isset($_REQUEST['bolsa_familia']) && $_REQUEST['bolsa_familia'] != '' ? (array (
  'value' => $_REQUEST['bolsa_familia'],
  'query' => " AND hp.hab_beneficio_social_id = 3 " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opDeficiencia = isset($_REQUEST['deficiencia']) && $_REQUEST['deficiencia'] != '' ? ($_REQUEST['deficiencia'] == 1 ? (array (
  'value' => $_REQUEST['deficiencia'],
  'query' => " AND (hp.bsc_deficiencia_tipo_id IS NOT NULL AND hp.bsc_deficiencia_tipo_id <> 0) " 
)) : ($_REQUEST['deficiencia'] == 2 ? (array (
  'value' => $_REQUEST['deficiencia'],
  'query' => " AND (hp.bsc_deficiencia_tipo_id IS NULL OR hp.bsc_deficiencia_tipo_id = 0)" 
)) : (array (
  'value' => NULL,
  'query' => '' 
)))) : (array (
  'value' => NULL,
  'query' => '' 
));
$opCidCapitulo = isset($_REQUEST['cid_capitulo']) && $_REQUEST['cid_capitulo'] != '' ? (array (
  'value' => $_REQUEST['cid_capitulo'],
  'query' => " AND " . $_REQUEST['cid_capitulo'] . " " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opCidGrupo = isset($_REQUEST['cid_grupo']) && $_REQUEST['cid_grupo'] != '' ? (array (
  'value' => $_REQUEST['cid_grupo'],
  'query' => " AND " . $_REQUEST['cid_grupo'] . " " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opCidCategoria = isset($_REQUEST['cid_categoria']) && $_REQUEST['cid_categoria'] != '' ? (array (
  'value' => $_REQUEST['cid_categoria'],
  'query' => " AND " . $_REQUEST['cid_categoria'] . " " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opProvLar = isset($_REQUEST['provedor_lar']) && $_REQUEST['provedor_lar'] != '' ? ($_REQUEST['provedor_lar'] == 1 ? (array (
  'value' => $_REQUEST['provedor_lar'],
  'query' => " AND hp.provedor_lar = 1 " 
)) : ($_REQUEST['provedor_lar'] == 2 ? (array (
  'value' => $_REQUEST['provedor_lar'],
  'query' => " AND hp.provedor_lar IS NULL " 
)) : (array (
  'value' => NULL,
  'query' => '' 
)))) : (array (
  'value' => NULL,
  'query' => '' 
));
$opRendaComprovada = isset($_REQUEST['renda_comprovada']) && $_REQUEST['renda_comprovada'] != '' ? (array (
  'value' => $_REQUEST['renda_comprovada'],
  'query' => $_REQUEST['renda_comprovada'] 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opRendaNComprovada = isset($_REQUEST['renda_n_comprovada']) && $_REQUEST['renda_n_comprovada'] != '' ? (array (
  'value' => $_REQUEST['renda_n_comprovada'],
  'query' => $_REQUEST['renda_n_comprovada'] 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opRendaInexistente = isset($_REQUEST['renda_inexistente']) && $_REQUEST['renda_inexistente'] != '' ? (array (
  'value' => $_REQUEST['renda_inexistente'],
  'query' => $_REQUEST['renda_inexistente'] 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opVlrMin = isset($_REQUEST['renda_valor_min']) && $_REQUEST['renda_valor_min'] != '' ? (array (
  'value' => $_REQUEST['renda_valor_min'],
  'query' => " AND " . $_REQUEST['renda_valor_min'] . " " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opVlrMax = isset($_REQUEST['renda_valor_max']) && $_REQUEST['renda_valor_max'] != '' ? (array (
  'value' => $_REQUEST['renda_valor_max'],
  'query' => " AND " . $_REQUEST['renda_valor_max'] . " " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opGrauEscolar = isset($_REQUEST['grau_escolar']) && $_REQUEST['grau_escolar'] != '' ? (array (
  'value' => $_REQUEST['grau_escolar'],
  'query' => " AND hp.hab_grau_escolar_id = " . $_REQUEST['grau_escolar'] . " " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opEstudando = isset($_REQUEST['estudando']) && $_REQUEST['estudando'] != '' ? ($_REQUEST['estudando'] == 1 ? (array (
  'value' => $_REQUEST['estudando'],
  'query' => " AND hpec.id <> NULL " 
)) : ($_REQUEST['estudando'] == 2 ? (array (
  'value' => $_REQUEST['estudando'],
  'query' => " AND hpec.id IS NULL " 
)) : (array (
  'value' => NULL,
  'query' => '' 
)))) : (array (
  'value' => NULL,
  'query' => '' 
));
$opEstudInstituicao = isset($_REQUEST['instituicao']) && $_REQUEST['instituicao'] != '' ? (array (
  'value' => $_REQUEST['instituicao'],
  'query' => " AND hpec.hab_instituicao_natureza_id IN (" . $_REQUEST['instituicao'] . ") " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));
$opEstudFinancia = isset($_REQUEST['meios_proprios']) && $_REQUEST['meios_proprios'] != '' ? (array (
  'value' => $_REQUEST['meios_proprios'],
  'query' => " AND hpec.hab_financia_natureza_id = 1 " 
)) : (array (
  'value' => NULL,
  'query' => '' 
));

// $stmt = $db->prepare("SELECT hc.id, hp.id AS pessoa_id, hp.nome, hp.cpf, hp.email, hp.cadastro_unico, hp.data_nascimento, bm.nome AS municipio, hp.status, 
//                       hpc.numero AS celular, 
//                       (SELECT COUNT(hf.id) AS qtd_filho FROM hab_familiar AS hf WHERE hf.hab_candidato_id = hc.id AND hf.hab_grau_parentesco_id = 3) AS qtd_filho, 
//                       (SELECT COUNT(hpe.id) AS qtd FROM hab_pessoa_renda AS hpe WHERE hpe.hab_pessoa_id = hp.id AND hpe.hab_renda_tipo_id = 1) AS qtd_renda_comp, 
//                       (SELECT COUNT(hpe.id) AS qtd FROM hab_pessoa_renda AS hpe WHERE hpe.hab_pessoa_id = hp.id AND hpe.hab_renda_tipo_id = 2) AS qtd_renda_ncomp 
//                       FROM hab_candidato hc
//                       LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
//                       LEFT JOIN bsc_municipio AS bm ON bm.id = hp.bsc_municipio_id_natural
//                       LEFT JOIN hab_familiar AS hf ON hf.hab_candidato_id = hc.id
//                       LEFT JOIN hab_pessoa_endereco AS hpe ON hp.id = hpe.hab_pessoa_id 
//                       LEFT JOIN hab_pessoa_escolar AS hpec ON hpec.hab_pessoa_id = hp.id
//                       LEFT JOIN hab_pessoa_contato AS hpc ON hpc.hab_pessoa_id = hp.id AND hpc.hab_contato_tipo_id = 2
//                       WHERE hp.cadastro_retroativo_ano IS NOT NULL
    
//                       " . $opCpf['query'] . "
//                       " . $opNome['query'] . "
//                       " . $opSexo['query'] . "
//                       " . $opDtNcIni['query'] . "
//                       " . $opDtNcFim['query'] . "
//                       " . $opEstCivil['query'] . "
//                       " . $opUniaoEst['query'] . "
//                       " . $opEstrangeiro['query'] . "
//                       " . $opCorRaca['query'] . "
//                       " . $opMunicipio['query'] . "
//                       " . $opBairro['query'] . "
//                       " . $opCadIni['query'] . "
//                       " . $opCadFim['query'] . "
//                       " . $opCadUnico['query'] . "
//                       " . $opBolsaFamilia['query'] . "
//                       " . $opDeficiencia['query'] . "
//                       " . $opProvLar['query'] . "
//                       " . $opGrauEscolar['query'] . "
//                       " . $opEstudando['query'] . "
//                       " . $opEstudFinancia['query'] . "
                          
//                       GROUP BY hc.id, hp.id, hp.nome, hp.cpf, hp.email, hp.cadastro_unico, hp.data_nascimento, bm.nome, hp.status, qtd_filho, qtd_renda_comp, qtd_renda_ncomp 
//                       ORDER BY hc.id ASC");
// $stmt->execute();
// $rsCandidatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<script>
  var bairrosAcre = <?= json_encode(array_column($rsBairrosAcre, 'bairro')); ?>;
  //var arrayCandidatos = <?//= json_encode($rsCandidatos); ?>;
  var somaSituacao = <?= $opSituacaoEmDigitacao['value'] + $opSituacaoAguardandoValidacao['value'] + $opSituacaoFinalizado['value']; ?>;
  var vetorSituacaoEmDigitacao = [
    0,
    1,
    3,
    5,
    7
  ];
  var vetorSituacaoAguardandoValidacao = [
    0,
    2,
    3,
    6,
    7
  ];
  var vetorSituacaoFinalizado = [
    0,
    4,
    5,
    6,
    7
  ];
</script>
<form id="form_lista_morto" action="<?= PORTAL_URL ?>sistema/candidato/lista_morto" method="post">
  <input type="hidden" id="situacao_opcao" name="situacao_opcao" value="<?= isset($_REQUEST['situacao_opcao']) ? $_REQUEST['situacao_opcao'] : ''; ?>">
  <div class="card icons-demo">
    <div class="card-header cw-header palette-Black bg">
      <div class="cwh-year">Candidato</div>
      <div class="cwh-day">Lista Arquivo Morto</div>
      <?php
      if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
        ?>
        <a href="<?= PORTAL_URL; ?>sistema/candidato/etapa1" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float">
        <i class="zmdi zmdi-plus"></i>
      </a>
        <?php
      }
      ?>
      <?php
      if (vf_objeto_acao("filtrar")) {
        ?>
        <a data-toggle="modal" href="#modalWider" class="btn palette-Orange-700 bg btn-float waves-effect waves-circle waves-float filtro">
        <i class="zmdi zmdi-search"></i>
      </a>

        <?php
      }
      ?>
    </div>
    <div class="card-body card-padding">
      <br />
      <br />
      <div role="tabpanel">
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="cadastrando">
            <div id="pagina" class="table-responsive">
              <input type="hidden" id="permissao" name="permissao" value="<?= vf_objeto_acao("admin"); ?>"/>
              <table id="data_table_candidato" class="table table-striped table-candidate">
                <thead>
                  <tr>
                    <th data-column-id="id" data-type="numeric" data-identifier="true">#</th>
                    <th data-column-id="nome">NOME</th>
                    <th data-column-id="cpf">CPF</th>
                    <th data-column-id="apto" data-formatter="apto">APTO AO SORTEIO</th>
<!--                     <th data-column-id="municipio">MUNICÍPIO</th> -->
<!--                     <th data-column-id="celular">CELULAR</th> -->
<!--                     <th data-column-id="cadastro_unico" data-formatter="cadastro_unico">CAD. ÚNICO</th> -->
                    <?php
                    if (vf_objeto_acao("visualizar") && vf_objeto_acao("editar")) {
                      ?>
                      <th data-column-id="acao" data-formatter="geral" data-sortable="false"></th>
                      <?php
                    } elseif (vf_objeto_acao("visualizar")) {
                      ?>
                      <th data-column-id="acao" data-formatter="visualiza" data-sortable="false"></th>
                      <?php
                    } elseif (vf_objeto_acao("editar")) {
                      ?>
                      <th data-column-id="acao" data-formatter="edita" data-sortable="false"></th>
                      <?php
                    }
                    ?>
                  </tr>
                </thead>
<!--                 <tbody> -->

                  <?php
//                   $somaRendaTipo = $opRendaComprovada['value'] + $opRendaNComprovada['value'] + $opRendaInexistente['value'];
//                   $vetorRendaComp = [ 
//                     0,
//                     1,
//                     3,
//                     5,
//                     7 
//                   ];
//                   $vetorRendaNComp = [ 
//                     0,
//                     2,
//                     3,
//                     6,
//                     7 
//                   ];
//                   $vetorRendaInex = [ 
//                     0,
//                     4,
//                     5,
//                     6,
//                     7 
//                   ];
                  
//                   foreach ( $rsCandidatos as $kCandidato => $objCandidato ) {
//                     if ($objCandidato['status'] == 1) {
//                       if (isset($objCandidato['id'])) {
//                         if ($opQtdFilho['value'] == - 1 || $objCandidato['qtd_filho'] == $opQtdFilho['value']) {
//                           if ($somaRendaTipo == 0 || $somaRendaTipo == 7 || (($objCandidato['qtd_renda_comp'] > 0 && in_array($somaRendaTipo, $vetorRendaComp)) || ($objCandidato['qtd_renda_ncomp'] > 0 && in_array($somaRendaTipo, $vetorRendaNComp)) || (($objCandidato['qtd_renda_comp'] == 0 && $objCandidato['qtd_renda_ncomp'] == 0) && in_array($somaRendaTipo, $vetorRendaInex)))) {
//                             ?>
<!--                             <tr data-row-id="<?//= $objCandidato['id']; ?>">
<!--                     <td relsituacao="<?//= $objCandidato['status']; ?>"><?//= $objCandidato['id']; ?></td>
<!--                     <td><?//= $objCandidato['nome']; ?></td>
<!--                     <td><?//= $objCandidato['cpf']; ?></td>
<!--                     <td><?//= $objCandidato['municipio']; ?></td>
<!--                     <td><?//= $objCandidato['celular']; ?></td>
<!--                     <td><?//= $objCandidato['cadastro_unico'] > 0 ? '1' : '0'; ?></td>
<!--                     <td></td> -->
<!--                   </tr> -->
                            <?php
//                             // echo json_encode($objCandidato);
//                           }
//                         }
//                       }
//                     }
//                   }
//                   ?>

<!--                 </tbody> -->
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!---------------------------------------------------------------------- FILTRO DA TABELA ----------------------------------------------------------------------->
  <!-- Modal Large -->
  <div class="modal fade" id="modalWider" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header m-b-10">
          <h4 class="modal-title">FILTRO</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group fg-float">
                <div class="fg-line">
                  <input type="text" name="cpf" id="cpf" class="input-sm form-control fg-input" data-mask="000.000.000-00" value="<?= $opCpf['value']; ?>" />
                  <label for="cpf" class="fg-label">CPF</label>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group fg-float">
                <div class="fg-line">
                  <input type="text" name="nome" id="nome" class="input-sm form-control fg-input" value="<?= $opNome['value']; ?>">
                  <label for="nome" class="fg-label">NOME</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <p class="c-black f-500 space-b-5 label-fix">SEXO</p>
                </div>

                <?php
                foreach ( $rsSexo as $kS => $objS ) {
                  ?>
                  <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="sexo" name="sexo" type="radio" <?= $opSexo['value'] == $objS['id'] ? 'checked="true"' : '' ?> value="<?= $objS['id']; ?>">
                      <i class="input-helper"></i>
                        <?= strtoupper($objS['nome']); ?>
                      </label>
                  </div>
                </div>
                  <?php
                }
                ?>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group fg-float space-t-25">
                <div class="fg-line">
                  <input type="text" name="nascimento_inicio" id="nascimento_inicio" class="input-sm form-control fg-input date-picker" data-mask="00/00/0000" value="<?= $opDtNcIni['value'] != NULL ? obterDataBRTimestamp($opDtNcIni['value']) : ''; ?>">
                  <label for="nascimento_inicio" class="fg-label">NASCIMENTO INÍCIO</label>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group fg-float space-t-25">
                <div class="fg-line">
                  <input type="text" name="nascimento_fim" id="nascimento_fim" class="input-sm form-control fg-input date-picker" data-mask="00/00/0000" value="<?= $opDtNcFim['value'] != NULL ? obterDataBRTimestamp($opDtNcFim['value']) : ''; ?>">
                  <label for="nascimento_fim" class="fg-label">NASCIMENTO FIM</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <label for="estado_civil" class="f-500 c-black label-fix">ESTADO CIVIL</label>
              <select id="estado_civil" name="estado_civil" class="selectpicker" data-live-search="true">
                <option value="">TODOS</option>
                <?php
                foreach ( $rsEstadosCivis as $kEC => $objEC ) {
                  ?>
                  <option <?= $opEstCivil['value'] == $objEC['id'] ? 'selected="true"' : ''; ?> value="<?= $objEC['id']; ?>"><?= $objEC['nome']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
            <div class="col-md-4">
              <div class="checkbox space-t-34">
                <label>
                  <input type="checkbox" value="1" name="uniao_estavel" id="uniao_estavel" <?= $opUniaoEst['value'] == 1 ? 'checked="true"' : ''; ?> />
                  <i class="input-helper"></i>
                  UNIÃO ESTÁVEL
                </label>
              </div>
            </div>
            <div class="col-md-4">
              <label for="filhos_qtd" class="f-500 c-black label-fix">QTD. DE FILHOS</label>
              <select class="selectpicker" data-live-search="true" name="filhos_qtd" id="filhos_qtd">
                <option value="-1">TODOS</option>
                <?php
                for ($i = 0; $i <= 30; $i ++) {
                  ?>
                  <option <?= $opQtdFilho['value'] == $i ? 'selected="true"' : ''; ?> value="<?= $i; ?>"><?= $i; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
          <!-- brasileiro = id - 35 -->
          <div class="row space-t-20">
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <p class="c-black f-500 space-b-5 label-fix">ESTRANGEIRO</p>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="estrangeiro" name="estrangeiro" type="radio" <?= $opEstrangeiro['value'] == 1 ? 'checked="true"' : ''; ?> value="1" />
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="estrangeiro" name="estrangeiro" type="radio" <?= $opEstrangeiro['value'] == 2 ? 'checked="true"' : ''; ?> value="2" />
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <label for="cor_raca" class="f-500 c-black label-fix">COR/RAÇA</label>
              <select class="selectpicker" data-live-search="true" name="cor_raca" id="cor_raca">
                <option value="">TODOS</option>
                <?php
                foreach ( $rsPeleCores as $kPC => $objPC ) {
                  ?>
                  <option <?= $opCorRaca['value'] == $objPC['id'] ? 'selected="true"' : ''; ?> value="<?= $objPC['id']; ?>"><?= $objPC['nome']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
            <div class="col-md-4">
              <label for="" class="f-500 c-black label-fix">MUNICÍPIO</label>
              <select class="selectpicker" data-live-search="true" id="municipio" name="municipio">
                <option value="">TODOS</option>
                <?php
                foreach ( $rsMunicipiosAcre as $kMA => $objMA ) {
                  ?>
                  <option <?= $opMunicipio['value'] == $objMA['id'] ? 'selected="true"' : ''; ?> value="<?= $objMA['id']; ?>"><?= $objMA['nome']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
          <div class="row space-t-10">
            <div class="col-md-4">
              <div class="form-group fg-float space-t-25">
                <div class="fg-line">
                  <input type="text" name="bairro" id="bairro" class="input-sm form-control fg-input" value="<?= $opBairro['value']; ?>">
                  <label for="bairro" class="fg-label">BAIRRO</label>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group fg-float space-t-25">
                <div class="fg-line">
                  <input type="text" name="cadastro_inicio" id="cadastro_inicio" class="input-sm form-control fg-input date-picker" data-mask="00/00/0000" value="<?= $opCadIni['value'] != NULL ? obterDataBRTimestamp($opCadIni['value']) : ''; ?>">
                  <label for="cadastro_inicio" class="fg-label">INÍCIO DO CADASTRO</label>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group fg-float space-t-25">
                <div class="fg-line">
                  <input type="text" name="cadastro_fim" id="cadastro_fim" class="input-sm form-control fg-input date-picker" data-mask="00/00/0000" value="<?= $opCadFim['value'] != NULL ? obterDataBRTimestamp($opCadFim['value']) : ''; ?>">
                  <label for="cadastro_fim" class="fg-label">FIM DO CADASTRO</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <p class="c-black f-500 space-b-5 label-fix">CAD. ÚNICO</p>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="cad_unico" name="cad_unico" type="radio" <?= $opCadUnico['value'] == 1 ? 'checked="true"' : ''; ?> value="1" />
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="cad_unico" name="cad_unico" type="radio" <?= $opCadUnico['value'] == 2 ? 'checked="true"' : ''; ?> value="2" />
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <p class="c-black f-500 space-b-5 label-fix">BOLSA FAMÍLIA</p>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="bolsa_familia" name="bolsa_familia" type="radio" value="3" />
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="bolsa_familia" name="bolsa_familia" type="radio" value="0" />
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <p class="c-black f-500 space-b-5 label-fix">DEFICIÊNCIA FÍSICA</p>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="deficiencia" name="deficiencia" type="radio" <?= $opDeficiencia['value'] == 1 ? 'checked="true"' : ''; ?> value="1" />
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="deficiencia" name="deficiencia" type="radio" <?= $opDeficiencia['value'] == 2 ? 'checked="true"' : ''; ?> value="2" />
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row space-t-10">
            <div class="col-md-4">
              <label for="" class="f-500 c-black label-fix">
                CID
                <small>(capítulo)</small>
              </label>
              <select class="selectpicker" data-live-search="true" id="cid_capitulo" name="cid_capitulo">
                <option value="">CAPÍTULO 01</option>
                <option value="">CAPÍTULO 02</option>
                <option value="">CAPÍTULO 03</option>
                <option value="">CAPÍTULO 04</option>
                <option value="">CAPÍTULO 05</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="" class="f-500 c-black label-fix">
                CID
                <small>(grupo)</small>
              </label>
              <select class="selectpicker" data-live-search="true" id="cid_grupo" name="cid_grupo">
                <option value="">GRUPO 01</option>
                <option value="">GRUPO 02</option>
                <option value="">GRUPO 03</option>
                <option value="">GRUPO 04</option>
                <option value="">GRUPO 05</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="" class="f-500 c-black label-fix">
                CID
                <small>(categoria)</small>
              </label>
              <select class="selectpicker" data-live-search="true" id="cid_categoria" name="cid_categoria">
                <option value="">CATEGORIA 01</option>
                <option value="">CATEGORIA 02</option>
                <option value="">CATEGORIA 03</option>
                <option value="">CATEGORIA 04</option>
                <option value="">CATEGORIA 05</option>
              </select>
            </div>
          </div>
          <div class="row space-t-20">
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <p class="c-black f-500 space-b-5 label-fix">PROVEDOR DO LAR</p>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="provedor_lar" name="provedor_lar" type="radio" <?= $opProvLar['value'] == 1 ? 'checked="true"' : ''; ?> value="1" />
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="provedor_lar" name="provedor_lar" type="radio" <?= $opProvLar['value'] == 2 ? 'checked="true"' : ''; ?> value="2" />
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-12">
                  <p class="c-black f-500 space-b-5 label-fix">RENDA</p>
                </div>
                <div class="col-lg-4">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="renda_comprovada" id="renda_comprovada" <?= $opRendaComprovada['value'] == 1 ? 'checked="true"' : ''; ?> value="1" />
                      <i class="input-helper"></i>
                      COMPROVADA
                    </label>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="renda_n_comprovada" id="renda_n_comprovada" <?= $opRendaNComprovada['value'] == 2 ? 'checked="true"' : ''; ?> value="2" />
                      <i class="input-helper"></i>
                      NÃO COMPROVADA
                    </label>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="renda_inexistente" id="renda_inexistente" <?= $opRendaInexistente['value'] == 4 ? 'checked="true"' : ''; ?> value="4" />
                      <i class="input-helper"></i>
                      SEM RENDA
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row space-t-25">
            <div class="col-md-12">
              <div class="m-b-20 clearfix">
                <div class="input-slider-values m-b-15"></div>
                <strong class="pull-left text-muted" id="value-upper"></strong>
                <strong class="pull-right text-muted" id="value-lower"></strong>
                <input type="hidden" id="renda_valor_min" name="renda_valor_min" value="">
                <input type="hidden" id="renda_valor_max" name="renda_valor_max" value="">
              </div>
            </div>
          </div>
          <div class="row space-t-10">
            <div class="col-md-4">
              <label for="" class="f-500 c-black label-fix">GRAU ESCOLAR</label>
              <select class="selectpicker" data-live-search="true" id="grau_escolar" name="grau_escolar">
                <option value="">TODOS</option>
                <?php
                foreach ( $rsGrausEscolares as $kGE => $objGE ) {
                  ?>
                  <option <?= $opGrauEscolar['value'] == $objGE['id'] ? 'selected="true"' : ''; ?> value="<?= $objGE['id']; ?>"><?= $objGE['nome']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
          <div class="row space-t-20">
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <p class="c-black f-500 space-b-5 label-fix">ESTUDANDO</p>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="estudando" name="estudando" type="radio" <?= $opEstudando['value'] == 1 ? 'checked="true"' : ''; ?> value="1" />
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="estudando" name="estudando" type="radio" <?= $opEstudando['value'] == 2 ? 'checked="true"' : ''; ?> value="2" />
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <p class="c-black f-500 space-b-5 label-fix">INSTITUIÇÃO</p>
                </div>
                <?php
                foreach ( $rsInstituicaoNaturezas as $kIN => $objIN ) {
                  ?>
                  <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="instituicao" name="instituicao" type="radio" value="<?= $objIN['id']; ?>">
                      <i class="input-helper"></i>
                        <?= strtoupper($objIN['nome']); ?>
                      </label>
                  </div>
                </div>
                  <?php
                }
                ?>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <p class="c-black f-500 space-b-5 label-fix">FINANCIADO POR MEIOS PRÓRIOS?</p>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="meios_proprios" name="meios_proprios" type="radio" value="1" />
                      <i class="input-helper"></i>
                      SIM
                    </label>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="radio m-b-15">
                    <label>
                      <input id="meios_proprios" name="meios_proprios" type="radio" value="2" />
                      <i class="input-helper"></i>
                      NÃO
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer palette-Orange bg">
          <button type="submit" class="btn palette-Light-Blue bg waves-effect btn-icon-text">
            <i class="zmdi zmdi-search"></i>
            Filtrar
          </button>
          <button type="button" class="btn palette-Red bg waves-effect btn-icon-text" data-dismiss="modal">
            <i class="zmdi zmdi-close"></i>
            Fechar
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
<?php include('template/rodape.php'); ?>
<!-- JS DO OBJETO-LISTA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/candidato/lista_morto.js"></script>
<script>
//Função para desmarcar input radio
  $('input[type="radio"]:checked').livequery('click', function () {
    $(this).prop("checked", false);
  });
</script>