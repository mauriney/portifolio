<?php
@session_start();
include_once ('../../../conf/config.php');
$db = Conexao::getInstance();

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

$dataGridCurrent = isset($_REQUEST['current']) && $_REQUEST['current'] != '' ? $_REQUEST['current'] : 1;
$dataGridRowCount = isset($_REQUEST['rowCount']) && $_REQUEST['rowCount'] != '' ? $_REQUEST['rowCount'] : 10;
$offSet = ($dataGridCurrent * $dataGridRowCount) - $dataGridRowCount;
$sorts = isset($_REQUEST['sort']) && $_REQUEST['sort'] != '' ? $_REQUEST['sort'] : NULL;
$orderBy = '';
if($sorts){
  $orderBy = ' ORDER BY ';
  foreach ($sorts AS $kS => $vS){
    $orderBy .= $kS . ' ' . $vS . ' ';
  }
} else {
  $orderBy = ' ORDER BY hc.id ASC ';
}

$limit = $dataGridRowCount;
$stmt = $db->prepare("SELECT hc.id, hp.id AS pessoa_id, hp.nome, hp.cpf, hp.email, hp.cadastro_unico, hp.data_nascimento, bm.nome AS municipio, hp.status, 
                      hpc.numero AS celular, (SELECT status FROM sort_candidato_apto WHERE candidato_id = hc.id) AS apto,
                      (SELECT COUNT(hf.id) AS qtd_filho FROM hab_familiar AS hf WHERE hf.hab_candidato_id = hc.id AND hf.hab_grau_parentesco_id = 3) AS qtd_filho, 
                      (SELECT COUNT(hpe.id) AS qtd FROM hab_pessoa_renda AS hpe WHERE hpe.hab_pessoa_id = hp.id AND hpe.hab_renda_tipo_id = 1) AS qtd_renda_comp, 
                      (SELECT COUNT(hpe.id) AS qtd FROM hab_pessoa_renda AS hpe WHERE hpe.hab_pessoa_id = hp.id AND hpe.hab_renda_tipo_id = 2) AS qtd_renda_ncomp 
                      FROM hab_candidato hc
                      LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                      LEFT JOIN bsc_municipio AS bm ON bm.id = hp.bsc_municipio_id_natural
                      LEFT JOIN hab_familiar AS hf ON hf.hab_candidato_id = hc.id
                      LEFT JOIN hab_pessoa_endereco AS hpe ON hp.id = hpe.hab_pessoa_id 
                      LEFT JOIN hab_pessoa_escolar AS hpec ON hpec.hab_pessoa_id = hp.id
                      LEFT JOIN hab_pessoa_contato AS hpc ON hpc.hab_pessoa_id = hp.id AND hpc.hab_contato_tipo_id = 2
                      WHERE hp.cadastro_retroativo_ano IS NOT NULL
    
                      " . $opCpf['query'] . "
                      " . $opNome['query'] . "
                      " . $opSexo['query'] . "
                      " . $opDtNcIni['query'] . "
                      " . $opDtNcFim['query'] . "
                      " . $opEstCivil['query'] . "
                      " . $opUniaoEst['query'] . "
                      " . $opEstrangeiro['query'] . "
                      " . $opCorRaca['query'] . "
                      " . $opMunicipio['query'] . "
                      " . $opBairro['query'] . "
                      " . $opCadIni['query'] . "
                      " . $opCadFim['query'] . "
                      " . $opCadUnico['query'] . "
                      " . $opBolsaFamilia['query'] . "
                      " . $opDeficiencia['query'] . "
                      " . $opProvLar['query'] . "
                      " . $opGrauEscolar['query'] . "
                      " . $opEstudando['query'] . "
                      " . $opEstudFinancia['query'] . "
                      GROUP BY hc.id, hp.id, hp.nome, hp.cpf, hp.email, hp.cadastro_unico, hp.data_nascimento, bm.nome, hp.status, qtd_filho, qtd_renda_comp, qtd_renda_ncomp 
                      $orderBy 
                      LIMIT $offSet, $limit ;");
$stmt->execute();
$rsCandidatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $db->prepare("SELECT hc.id, hp.id AS pessoa_id, hp.nome, hp.cpf, hp.email, hp.cadastro_unico, hp.data_nascimento, bm.nome AS municipio, hp.status,
                      hpc.numero AS celular,
                      (SELECT COUNT(hf.id) AS qtd_filho FROM hab_familiar AS hf WHERE hf.hab_candidato_id = hc.id AND hf.hab_grau_parentesco_id = 3) AS qtd_filho,
                      (SELECT COUNT(hpe.id) AS qtd FROM hab_pessoa_renda AS hpe WHERE hpe.hab_pessoa_id = hp.id AND hpe.hab_renda_tipo_id = 1) AS qtd_renda_comp,
                      (SELECT COUNT(hpe.id) AS qtd FROM hab_pessoa_renda AS hpe WHERE hpe.hab_pessoa_id = hp.id AND hpe.hab_renda_tipo_id = 2) AS qtd_renda_ncomp
                      FROM hab_candidato hc
                      LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                      LEFT JOIN bsc_municipio AS bm ON bm.id = hp.bsc_municipio_id_natural
                      LEFT JOIN hab_familiar AS hf ON hf.hab_candidato_id = hc.id
                      LEFT JOIN hab_pessoa_endereco AS hpe ON hp.id = hpe.hab_pessoa_id
                      LEFT JOIN hab_pessoa_escolar AS hpec ON hpec.hab_pessoa_id = hp.id
                      LEFT JOIN hab_pessoa_contato AS hpc ON hpc.hab_pessoa_id = hp.id AND hpc.hab_contato_tipo_id = 2
                      WHERE hp.cadastro_retroativo_ano IS NOT NULL

                      " . $opCpf['query'] . "
                      " . $opNome['query'] . "
                      " . $opSexo['query'] . "
                      " . $opDtNcIni['query'] . "
                      " . $opDtNcFim['query'] . "
                      " . $opEstCivil['query'] . "
                      " . $opUniaoEst['query'] . "
                      " . $opEstrangeiro['query'] . "
                      " . $opCorRaca['query'] . "
                      " . $opMunicipio['query'] . "
                      " . $opBairro['query'] . "
                      " . $opCadIni['query'] . "
                      " . $opCadFim['query'] . "
                      " . $opCadUnico['query'] . "
                      " . $opBolsaFamilia['query'] . "
                      " . $opDeficiencia['query'] . "
                      " . $opProvLar['query'] . "
                      " . $opGrauEscolar['query'] . "
                      " . $opEstudando['query'] . "
                      " . $opEstudFinancia['query'] . "
                      GROUP BY hc.id, hp.id, hp.nome, hp.cpf, hp.email, hp.cadastro_unico, hp.data_nascimento, bm.nome, hp.status, qtd_filho, qtd_renda_comp, qtd_renda_ncomp
                      $orderBy ");
$stmt->execute();
$rsCandidatosAll = $stmt->fetchAll(PDO::FETCH_ASSOC);

$somaRendaTipo = $opRendaComprovada['value'] + $opRendaNComprovada['value'] + $opRendaInexistente['value'];
$vetorRendaComp = [ 
  0,
  1,
  3,
  5,
  7 
];
$vetorRendaNComp = [ 
  0,
  2,
  3,
  6,
  7 
];
$vetorRendaInex = [ 
  0,
  4,
  5,
  6,
  7 
];

$total = 0;
foreach ( $rsCandidatosAll as $kCandidato => $objCandidato ) {
  if ($objCandidato['status'] == 1) {
    if (isset($objCandidato['id'])) {
      if ($opQtdFilho['value'] == - 1 || $objCandidato['qtd_filho'] == $opQtdFilho['value']) {
        if ($somaRendaTipo == 0 || $somaRendaTipo == 7 || (($objCandidato['qtd_renda_comp'] > 0 && in_array($somaRendaTipo, $vetorRendaComp)) || ($objCandidato['qtd_renda_ncomp'] > 0 && in_array($somaRendaTipo, $vetorRendaNComp)) || (($objCandidato['qtd_renda_comp'] == 0 && $objCandidato['qtd_renda_ncomp'] == 0) && in_array($somaRendaTipo, $vetorRendaInex)))) {
          $total++;
        }
      }
    }
  }
}

$retorno = [ ];
$retorno['rows'] = [ ];
foreach ( $rsCandidatos as $kCandidato => $objCandidato ) {
  if ($objCandidato['status'] == 1) {
    if (isset($objCandidato['id'])) {
      if ($opQtdFilho['value'] == - 1 || $objCandidato['qtd_filho'] == $opQtdFilho['value']) {
        if ($somaRendaTipo == 0 || $somaRendaTipo == 7 || (($objCandidato['qtd_renda_comp'] > 0 && in_array($somaRendaTipo, $vetorRendaComp)) || ($objCandidato['qtd_renda_ncomp'] > 0 && in_array($somaRendaTipo, $vetorRendaNComp)) || (($objCandidato['qtd_renda_comp'] == 0 && $objCandidato['qtd_renda_ncomp'] == 0) && in_array($somaRendaTipo, $vetorRendaInex)))) {
          array_push($retorno['rows'], $objCandidato);
        }
      }
    }
  }
}

$retorno['current'] = $_REQUEST['current'];
$retorno['rowCount'] = $_REQUEST['rowCount'];
$retorno['total'] = $total;
echo json_encode($retorno);
?>
