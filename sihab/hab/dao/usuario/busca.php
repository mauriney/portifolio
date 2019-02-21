<?php

//------------------------------------------------------------------------------
//DATA: 01/08/2016 às 15:00
//NOME: Pesquisa Dinâmica
//DESCRIÇÃO: Busca dinâmica da pesquisa de usuário
//------------------------------------------------------------------------------
@session_start();
include_once('../../../utils/funcoes.php');
include_once('../../../conf/config.php');
$db = Conexao::getInstance();


if ($_GET['valor'] != "" && strlen($_GET['valor']) > 0) {//CASO A PESQUISA TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO IF ABAIXO
  $result = $db->prepare("SELECT o.id AS unidade_organizacional_id, u.online, u.id, u.nome, u.cargo, u.email_institucional, o.sigla as sigla, u.telefone_celular, u.telefone_institucional, u.status 
            FROM seg_usuario u , bsc_unidade_organizacional o 
            WHERE u.unidade_organizacional_id = o.id and u.nome like ? OR 
            u.unidade_organizacional_id = o.id and o.sigla like ? OR 
            u.unidade_organizacional_id = o.id and u.telefone_celular like ? OR 
            u.unidade_organizacional_id = o.id and u.email_institucional like ? OR 
            u.unidade_organizacional_id = o.id and u.status = ? ORDER BY u.nome");

  $result->bindValue(1, "%" . ($_GET['valor']) . "%");
  $result->bindValue(2, "%" . ($_GET['valor']) . "%");
  $result->bindValue(3, "%" . ($_GET['valor']) . "%");
  $result->bindValue(4, "%" . ($_GET['valor']) . "%");
  $result->bindValue(5, status_inverso($_GET['valor']));
  $result->execute();
} else if ($_GET['valor'] == "" || strlen($_GET['valor']) == 0) {//CASO A PESQUISA NÃO TENHA ALGUM VALOR INFORMADO IRÁ ENTRAR NO ELSE IF ABAIXO
  $result = $db->prepare("SELECT o.id AS unidade_organizacional_id, u.online, u.id, u.nome, u.cargo, u.email_institucional, o.sigla as sigla, u.telefone_celular, u.telefone_institucional, u.status
                          FROM seg_usuario u , bsc_unidade_organizacional o 
                          WHERE u.unidade_organizacional_id = o.id ORDER BY u.nome");
  $result->execute();
}

//ESTRUTURA DA TABELA PARA VISUALIZAÇÃO
echo '<table id="data-table-basic" class="table table-striped">
          <thead>
            <tr>
              <th data-column-id="#" data-type="numeric">#</th>
              <th data-column-id="id" data-type="numeric">ID</th>
              <th data-column-id="nome">NOME</th>
              <th data-column-id="orgao">ÓRGÃO</th>
              <th data-column-id="email">E-MAIL</th>
               <th data-column-id="celular">CELULAR</th>
              <th data-column-id="situacao">SITUAÇÃO</th>
              <th data-column-id="acao" data-formatter="commands" data-sortable="false">AÇÃO</th>
            </tr>
          </thead>
          <tbody>';

$cont = 1;

while ($usuario = $result->fetch(PDO::FETCH_ASSOC)) {

  echo '<tr data-row-id="' . $usuario['id'] . '">
                <td>' . $cont . '</td>
                <td>' . $usuario['id'] . '</td>
                <td>' . $usuario['nome'] . '</td>
                <td>' . $usuario['sigla'] . '</td>
                <td>' . $usuario['email_institucional'] . '</td>
                <td>' . $usuario['telefone_celular'] . '</td>
                <td>' . status($usuario['status']) . '</td>
                <td>
                <a href="' . PORTAL_URL . 'sistema/usuario/visualiza/' . $usuario['id'] . '"><button type="button" class="btn btn-icon palette-Cyan bg waves-effect waves-circle" data-row-id=><span class="zmdi zmdi-edit"></span></button></a>
                </td>
          </tr>';

  $cont++;
}

echo '</tbody>
        </table>';
?> 