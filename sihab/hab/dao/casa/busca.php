<?php

//------------------------------------------------------------------------------
//DATA: 14/06/2017 às 17:07
//NOME: Pesquisa Dinâmica
//DESCRIÇÃO: Busca dinâmica da pesquisa de casa
//------------------------------------------------------------------------------
@session_start();
include_once('../../../utils/funcoes.php');
include_once('../../../conf/config.php');
$db = Conexao::getInstance();


if ($_GET['valor'] != "" && strlen($_GET['valor']) > 0) {//CASO A PESQUISA TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO IF ABAIXO
  $result = $db->prepare("SELECT c.nome, c.id, c.status, c.endereco, c.numero, u.nome as usuario_pai_nome, sl.nome AS loteamento 
            FROM sort_casa c
            LEFT JOIN seg_usuario AS u ON u.id = c.seg_usuario_pai
            LEFT JOIN snch_loteamento AS sl ON sl.id = c.loteamento_id
            WHERE 1 AND
            c.nome like ? OR 
            u.nome like ? OR 
            c.endereco like ? OR 
            c.numero = ? OR 
            sl.nome like ? OR 
            c.status = ?
            ORDER BY upper(c.nome) ASC");

  $result->bindValue(1, "%" . ($_GET['valor']) . "%");
  $result->bindValue(2, "%" . ($_GET['valor']) . "%");
  $result->bindValue(3, "%" . ($_GET['valor']) . "%");
  $result->bindValue(4, "%" . ($_GET['valor']) . "%");
  $result->bindValue(5, "%" . ($_GET['valor']) . "%");
  $result->bindValue(6, status_inverso($_GET['valor']));
  $result->execute();
} else if ($_GET['valor'] == "" || strlen($_GET['valor']) == 0) {//CASO A PESQUISA NÃO TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO ELSE IF ABAIXO
  $result = $db->prepare("SELECT c.nome, c.id, c.status, c.endereco, c.numero, u.nome as usuario_pai_nome, sl.nome AS loteamento 
                          FROM sort_casa c
                          LEFT JOIN seg_usuario AS u ON u.id = c.seg_usuario_pai
                          LEFT JOIN snch_loteamento AS sl ON sl.id = c.loteamento_id
                          WHERE 1
                          ORDER BY upper(c.nome) ASC");
  $result->execute();
}

//ESTRUTURA DA TABELA PARA VISUALIZAÇÃO
echo '<table id="data-table-basic" class="table table-striped">
          <thead>
            <tr>
              <th data-column-id="#" data-type="numeric">#</th>
              <th data-column-id="id" data-type="numeric">ID</th>
              <th data-column-id="nome">NOME</th>
              <th data-column-id="numero">NÚMERO</th>
              <th data-column-id="endereco">ENDEREÇO</th>
              <th data-column-id="loteamento">LOTEAMENTO</th>
              <th data-column-id="responsavel">RESPONSÁVEL</th>
              <th data-column-id="situacao">SITUAÇÃO</th>
              <th data-column-id="acao" data-formatter="commands" data-sortable="false"></th>
            </tr>
          </thead>
          <tbody>';

$cont = 1;

while ($casa = $result->fetch(PDO::FETCH_ASSOC)) {

  echo '<tr id="casa_tr_' . $casa['id'] . '" data-row-id="' . $casa['id'] . '">
                <td>' . $cont . '</td>
                <td>' . $casa['id'] . '</td>
                <td>' . $casa['nome'] . '</td>
                <td>' . $casa['numero'] . '</td>
                <td>' . $casa['endereco'] . '</td>
                <td>' . $casa['loteamento'] . '</td>
                <td>' . $casa['usuario_pai_nome'] . '</td>
                <td>' . status_casa($casa['status']) . '</td>
                <td>';
  if ($casa['status'] != 2) {
    echo '<a href="' . PORTAL_URL . 'sistema/casa/cadastro/' . $casa['id'] . '"><button type="button" class="btn btn-icon palette-Cyan bg waves-effect waves-circle" data-row-id=><span class="zmdi zmdi-edit"></span></button></a>';
  }

  if (pesquisar_tabela("id", "sort_candidato_casa", "casa_id", "=", $casa['id'], "") == 0) {
    echo '<button onclick="remover_casa(' . $casa['id'] . ')" type="button" class="btn btn-icon btn-danger bg waves-effect waves-circle" data-row-id="' . $casa['id'] . '"><span class="zmdi zmdi-delete"></span></button>';
  }

  echo '</td>
          </tr>';

  $cont++;
}

echo '</tbody>
        </table>';
?> 