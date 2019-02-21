<?php

@session_start();
include_once('../../../utils/funcoes.php');
include_once('../../../conf/config.php');
$db = Conexao::getInstance();


if ($_GET['valor'] != "" && strlen($_GET['valor']) > 0) {//CASO A PESQUISA TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO IF ABAIXO
  $result = $db->prepare("SELECT m.status, m.descricao, m.id, m.data_cadastro, m.url, m.versao, m.nome AS modulo ,m.responsavel_id, u.nome AS usuario 
             FROM seg_modulo m 
             LEFT JOIN seg_usuario u ON u.id = m.responsavel_id 
             WHERE m.responsavel_id = u.id and m.nome like ? OR 
             m.responsavel_id = u.id and m.descricao like ? OR 
             m.responsavel_id = u.id and m.url like ? OR 
             m.responsavel_id = u.id and m.versao like ? OR 
             m.responsavel_id = u.id and m.status = ? ORDER BY upper(m.nome) ASC");

  $result->bindValue(1, "%" . ($_GET['valor']) . "%");
  $result->bindValue(2, "%" . ($_GET['valor']) . "%");
  $result->bindValue(3, "%" . ($_GET['valor']) . "%");
  $result->bindValue(4, "%" . ($_GET['valor']) . "%");
  $result->bindValue(5, status_inverso($_GET['valor']));
  $result->execute();

} else if ($_GET['valor'] == "" || strlen($_GET['valor']) == 0) {//CASO A PESQUISA NÃO TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO ELSE IF ABAIXO
  $result = $db->prepare("SELECT m.status, m.descricao, m.id, m.data_cadastro, m.url, m.versao, m.nome AS modulo ,m.responsavel_id, u.nome AS usuario 
                          FROM seg_modulo m 
                          LEFT JOIN seg_usuario u ON u.id = m.responsavel_id 
                          WHERE m.responsavel_id = u.id
                          GROUP BY m.id ORDER BY upper(m.nome) ASC");
  $result->execute();
}

//ESTRUTURA DA TABELA PARA VISUALIZAÇÃO
echo '<table id="data-table-basic" class="table table-striped">
          <thead>
            <tr>
              <th data-column-id="#" data-type="numeric">#</th>
              <th data-column-id="id" data-type="numeric">ID</th>
              <th data-column-id="nome">NOME</th>
              <th data-column-id="responsavel">DESCRIÇÃO</th>
              <th data-column-id="data">VERSÃO</th>
              <th data-column-id="data">URL</th>
              <th data-column-id="situacao">SITUAÇÃO</th>
              <th data-column-id="acao" data-formatter="commands" data-sortable="false">AÇÃO</th>
            </tr>
          </thead>
          <tbody>';

$cont = 1;

while ($modulo = $result->fetch(PDO::FETCH_ASSOC)) {

  echo '<tr data-row-id="' . $modulo['id'] . '">
                <td>' . $cont . '</td>
                <td>' . $modulo['id'] . '</td>
                <td>' . $modulo['modulo'] . '</td>
                <td>' . $modulo['descricao'] . '</td>
                <td>' . $modulo['versao'] . '</td>
                <td>' . $modulo['url'] . '</td>
                <td>' . status($modulo['status']) . '</td>
                <td>
                <a href="' . PORTAL_URL . 'sistema/modulo/visualiza/' . $modulo['id'] . '"><button type="button" class="btn btn-icon palette-Cyan bg waves-effect waves-circle" data-row-id=><span class="zmdi zmdi-edit"></span></button></a>
                <button onclick="remover_modulo(' . $modulo['id'] . ')" type="button" class="btn btn-icon btn-danger bg waves-effect waves-circle" data-row-id="' . $modulo['id'] . '"><span class="zmdi zmdi-delete"></span></button>
                </td>
          </tr>';

  $cont++;
}

echo '</tbody>
        </table>';
?> 