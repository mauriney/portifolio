<?php

//------------------------------------------------------------------------------
//DATA: 29/07/2016 às 15:00
//NOME: Pesquisa Dinâmica
//DESCRIÇÃO: Busca dinâmica da pesquisa de candidato
//------------------------------------------------------------------------------
@session_start();
include_once('../../../utils/funcoes.php');
include_once('../../../conf/config.php');
$db = Conexao::getInstance();


if ($_GET['valor'] != "" && strlen($_GET['valor']) > 0) {//CASO A PESQUISA TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO IF ABAIXO
  $result = $db->prepare("SELECT hp.cadastro_unico, bm.nome AS municipio, hc.id AS candidato_id, hp.id AS pessoa_id, hp.nome, hp.cpf, hp.email, hp.status 
            FROM hab_candidato hc
            LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
            LEFT JOIN bsc_municipio AS bm ON bm.id = hp.bsc_municipio_id_natural
            WHERE
            hc.id = ? AND hp.cadastro_retroativo_ano IS NOT NULL OR 
            hp.nome like ? AND hp.cadastro_retroativo_ano IS NOT NULL OR 
            hp.cpf like ? AND hp.cadastro_retroativo_ano IS NOT NULL OR 
            hp.email like ? AND hp.cadastro_retroativo_ano IS NOT NULL OR 
            hp.status = ? AND hp.cadastro_retroativo_ano IS NOT NULL
            ORDER BY upper(hp.nome) ASC");

  $result->bindValue(1, "" . $_GET['valor'] . "");
  $result->bindValue(2, "%" . $_GET['valor'] . "%");
  $result->bindValue(3, "%" . $_GET['valor'] . "%");
  $result->bindValue(4, "%" . $_GET['valor'] . "%");
  $result->bindValue(5, status_inverso($_GET['valor']));
  $result->execute();
} else if ($_GET['valor'] == "" || strlen($_GET['valor']) == 0) {//CASO A PESQUISA NÃO TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO ELSE IF ABAIXO
  $result = $db->prepare("SELECT hp.cadastro_unico, bm.nome AS municipio, hc.id AS candidato_id, hp.id AS pessoa_id, hp.nome, hp.cpf, hp.email, hp.status
                          FROM hab_candidato hc
                          LEFT JOIN hab_pessoa AS hp ON hp.id = hc.hab_pessoa_id
                          LEFT JOIN bsc_municipio AS bm ON bm.id = hp.bsc_municipio_id_natural
                          WHERE hp.cadastro_retroativo_ano IS NOT NULL ORDER BY hc.id ASC");
  $result->execute();
}

//ESTRUTURA DA TABELA PARA VISUALIZAÇÃO
echo '<table id="data-table-basic" class="table table-striped">
          <thead>
            <tr>
              <th data-column-id="id" data-type="numeric">#</th>
              <th data-column-id="nome">NOME</th>
              <th data-column-id="cpf">CPF</th>
              <th data-column-id="municipio">MUNICÍPIO</th>
              <th data-column-id="celular">CELULAR</th>
              <th data-column-id="cadunico" data-formatter="cadunico">CAD. ÚNICO</th>
              <th data-column-id="acao" data-formatter="commands" data-sortable="false"></th>
            </tr>
          </thead>
          <tbody>';

while ($candidato = $result->fetch(PDO::FETCH_ASSOC)) {

  echo '<tr data-row-id="' . $candidato['candidato_id'] . '">
                <td>' . $candidato['candidato_id'] . '</td>
                <td>' . $candidato['nome'] . '</td>
                <td>' . $candidato['cpf'] . '</td>
                <td>' . $candidato['municipio'] . '</td>
                <td>' . pesquisar_tabela("numero", "hab_pessoa_contato", "hab_pessoa_id", "=", $candidato['candidato_id'], "AND hab_contato_tipo_id = 2") . '</td>
                <td>';

  echo $candidato['cadastro_unico'] == 0 ? '<div class="status-cadunico cg-list palette-Red-A400 bg">
                    <span class="zmdi zmdi-close zmdi-hc-fw"></span>
                    </div>' : '<div class="status-cadunico cg-list cgl-main palette-Light-Green-500 bg">
                    <span class="zmdi zmdi-check zmdi-hc-fw"></span>
                    </div>';

  echo '
                </td>
                <td>
                <a href="' . PORTAL_URL . 'sistema/candidato/etapa1/' . $candidato['candidato_id'] . '"><button type="button" class="btn btn-icon palette-Orange bg waves-effect waves-circle" data-row-id="' . $candidato['candidato_id'] . '"><span class="zmdi zmdi-edit"></span></button></a>
                <a href="' . PORTAL_URL . 'sistema/candidato/visualiza/' . $candidato['candidato_id'] . '"><button type="button" class="btn btn-icon palette-Cyan bg waves-effect waves-circle" data-row-id="' . $candidato['candidato_id'] . '"><span class="zmdi zmdi-chevron-right"></span></button></a>
                </td>
          </tr>';
}

echo '</tbody>
        </table>';
?> 