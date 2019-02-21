<?php
//------------------------------------------------------------------------------
//DATA: 29/07/2016 às 15:00
//NOME: Pesquisa Dinâmica
//DESCRIÇÃO: Busca dinâmica da pesquisa de ação
//------------------------------------------------------------------------------
@session_start();
include_once('../../../utils/funcoes.php');
include_once('../../../conf/config.php');
$db = Conexao::getInstance();


if ($_GET['valor'] != "" && strlen($_GET['valor']) > 0) {//CASO A PESQUISA TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO IF ABAIXO
  $result = $db->prepare("SELECT a.nome, a.data_cadastro, a.id, a.status, u.nome as usuario_pai_nome 
            FROM seg_objeto a, seg_usuario u
            WHERE a.usuario_pai_id = u.id and
            a.nome like ? OR 
            a.usuario_pai_id = u.id and u.nome like ? OR 
            a.usuario_pai_id = u.id and DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') like ? OR 
            a.usuario_pai_id = u.id and a.status = ? ORDER BY upper(a.nome) ASC");

  $result->bindValue(1, "%" . ($_GET['valor']) . "%");
  $result->bindValue(2, "%" . ($_GET['valor']) . "%");
  $result->bindValue(3, "%" . ($_GET['valor']) . "%");
  $result->bindValue(4, status_inverso($_GET['valor']));
  $result->execute();

} else if ($_GET['valor'] == "" || strlen($_GET['valor']) == 0) {//CASO A PESQUISA NÃO TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO ELSE IF ABAIXO
  $result = $db->prepare("SELECT a.nome, a.data_cadastro, a.id, a.status, u.nome as usuario_pai_nome 
                          FROM seg_objeto a, seg_usuario u
                          WHERE a.usuario_pai_id = u.id ORDER BY upper(a.nome) ASC");
  $result->execute();
}

//ESTRUTURA DA TABELA PARA VISUALIZAÇÃO
echo '<table id="data-table-basic" class="table table-striped">
          <thead>
            <tr>
              <th data-column-id="#" data-type="numeric">#</th>
              <th data-column-id="id" data-type="numeric">ID</th>
              <th data-column-id="nome">NOME</th>
              <th data-column-id="responsavel">RESPONSÁVEL</th>
              <th data-column-id="data">DATA</th>
              <th data-column-id="situacao">SITUAÇÃO</th>
              <th data-column-id="objeto" data-formatter="commands" data-sortable="false">AÇÃO</th>
            </tr>
          </thead>
          <tbody>';

$cont = 1;

while ($objeto = $result->fetch(PDO::FETCH_ASSOC)) {

  echo '<tr data-row-id="' . $objeto['id'] . '">
                <td>' . $cont . '</td>
                <td>' . $objeto['id'] . '</td>
                <td>' . $objeto['nome'] . '</td>
                <td>' . $objeto['usuario_pai_nome'] . '</td>
                <td>' . obterDataBRTimestamp($objeto['data_cadastro']) . '</td>
                <td>' . status($objeto['status']) . '</td>
                <td>
                <a href="' . PORTAL_URL . 'sistema/objeto/cadastro/' . $objeto['id'] . '"><button type="button" class="btn btn-icon palette-Cyan bg waves-effect waves-circle" data-row-id=><span class="zmdi zmdi-edit"></span></button></a>
                <button onclick="remover_acao(' . $objeto['id'] . ')" type="button" class="btn btn-icon btn-danger bg waves-effect waves-circle" data-row-id="' . $objeto['id'] . '"><span class="zmdi zmdi-delete"></span></button>
                </td>
          </tr>';

  $cont++;
}

echo '</tbody>
        </table>';
?> 