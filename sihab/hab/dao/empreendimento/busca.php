<?php
//------------------------------------------------------------------------------
//DATA: 06/10/2016 às 09:00
//NOME: Pesquisa Dinâmica
//DESCRIÇÃO: Busca dinâmica da pesquisa de ação
//------------------------------------------------------------------------------
@session_start();
include_once('../../../utils/funcoes.php');
include_once('../../../conf/config.php');
$db = Conexao::getInstance();


if ($_GET['valor'] != "" && strlen($_GET['valor']) > 0) {//CASO A PESQUISA TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO IF ABAIXO
  $result = $db->prepare("SELECT a.id, a.nome, a.numero_apf, a.data_cadastro, a.status, a.seg_usuario_pai_id, u.nome AS usuario_pai
            FROM snch_apf a, seg_usuario u
            WHERE a.seg_usuario_pai_id = u.id and
            a.nome like ? OR
            a.seg_usuario_pai_id = u.id and u.nome like ? OR
            a.seg_usuario_pai_id = u.id and DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') like ? OR
            a.seg_usuario_pai_id = u.id and a.status = ? ORDER BY upper(a.nome) ASC");


  $result->bindValue(1, "%" . ($_GET['valor']) . "%");
  $result->bindValue(2, "%" . ($_GET['valor']) . "%");
  $result->bindValue(3, "%" . ($_GET['valor']) . "%");
  $result->bindValue(4, status_inverso($_GET['valor']));
  $result->execute();

} else if ($_GET['valor'] == "" || strlen($_GET['valor']) == 0) {//CASO A PESQUISA NÃO TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO ELSE IF ABAIXO
  $result = $db->prepare("SELECT id, nome, numero_apf, data_cadastro, status
                          FROM snch_apf
                          WHERE id > 0 ORDER BY upper(nome) ASC");

  $result->execute();
}

//ESTRUTURA DA TABELA PARA VISUALIZAÇÃO
echo '<table id="data-table-basic" class="table table-striped">
          <thead>
            <tr>
              <th data-column-id="#" data-type="numeric">#</th>
              <th data-column-id="id" data-type="numeric">ID</th>
              <th data-column-id="nome">NOME</th>
              <th data-column-id="numero_apf">NUMERO APF</th>
              <th data-column-id="data_cadastro">DATA</th>
              <th data-column-id="situacao">SITUAÇÃO</th>
              <th data-column-id="acao" data-formatter="commands" data-sortable="false">AÇÃO</th>
            </tr>
          </thead>
          <tbody>';

$cont = 1;

while ($empreendimento = $result->fetch(PDO::FETCH_ASSOC)) {

  echo '<tr data-row-id="' . $empreendimento['id'] . '">
                <td>' . $cont . '</td>
                <td>' . $empreendimento['id'] . '</td>
                <td>' . $empreendimento['nome'] . '</td>
                <td>' . $empreendimento['numero_apf'] . '</td>
                <td>' . obterDataBRTimestamp($empreendimento['data_cadastro']) . '</td>
                <td>' . status($empreendimento['status']) . '</td>
                <td>
                <a href="' . PORTAL_URL . 'sistema/empreendimento/cadastro/' . $empreendimento['id'] . '"><button type="button" class="btn btn-icon palette-Cyan bg waves-effect waves-circle" data-row-id=><span class="zmdi zmdi-edit"></span></button></a>
                <button onclick="remover_acao(' . $empreendimento['id'] . ')" type="button" class="btn btn-icon btn-danger bg waves-effect waves-circle" data-row-id="' . $empreendimento['id'] . '"><span class="zmdi zmdi-delete"></span></button>
                </td>
          </tr>';

  $cont++;
}

echo '</tbody>
        </table>';
?>