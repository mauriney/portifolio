<?php 
  include("topo.php");
  include_once('utils/demanda/funcoes.php');
  include("menu-lateral.php");
?>

<!-- Painel de Demandas -->
<div class="container conteudo-bd">

  <div class="botoes-acao">
    <a href="#" title="Imprimir" class="imprimir" onclick="window.print();"></a>
  </div>
  <div class="report">
    <div class="row">
      <div class="col-md-12">
        <span class="rp-agente">EQUIPE</span>
      </div>
    </div><!-- fim linha -->
    <div class="row">
      <div class="col-md-2">
        <select id="ano" name="ano" class="form-control">
<!--           <option value="2014" <?=(isset($_GET['ano']) ? $_GET['ano'] == 2014 ? 'selected="true"' : '' : '');?>>2014</option> -->
          <option value="2015" <?=(isset($_GET['ano']) ? $_GET['ano'] == 2015 ? 'selected="true"' : '' : 'selected="true"');?>>2015</option>
          <option value="2016" <?=(isset($_GET['ano']) ? $_GET['ano'] == 2016 ? 'selected="true"' : '' : '');?>>2016</option>
          <option value="2017" <?=(isset($_GET['ano']) ? $_GET['ano'] == 2017 ? 'selected="true"' : '' : '');?>>2017</option>
          <option value="2018" <?=(isset($_GET['ano']) ? $_GET['ano'] == 2018 ? 'selected="true"' : '' : '');?>>2018</option>
        </select>
      </div>
    </div><!-- fim linha -->
    <div class="row">
      <div class="col-md-6">
        <span class="area-grafico grafico-circular">
          <div id="chart_pie_equipe_demanda" style="width:100%; height:230px;"></div>
        </span>
      </div>
      <div class="col-md-6">
        <span class="area-grafico grafico-circular">
          <div id="chart_pie_equipe_agenda" style="width:100%; height:230px;"></div>
        </span>
      </div>
    </div><!-- fim linha -->
    <div class="row">
      <div class="col-md-6">
        <span class="area-grafico grafico-circular">
          <div id="chart_pie_status_demanda" style="width:100%; height:230px;"></div>
        </span>
      </div>
      <div class="col-md-6">
        <span class="area-grafico grafico-circular">
          <div id="chart_pie_situacao_demanda" style="width:100%; height:230px;"></div>
        </span>
      </div>
    </div><!-- fim linha -->
    <div class="row">
      <div class="col-md-12">
        <span class="area-grafico grafico-linhas">
          <!-- <h3>Quantidade de Demandas por Mês</h3> -->
          <div id="chart_line_qtd_demanda_mes" style="width:100%; height:250px;"></div>
        </span>
      </div>
    </div><!-- fim linha -->
    <div class="row">
      <div class="col-md-12">
        <span class="area-grafico grafico-linhas">
          <div id="chart_line_qtd_agenda_mes" style="width:100%; height:250px;"></div>
        </span>
      </div>
    </div><!-- fim linha -->
    <div class="clearfix"></div>
  </div>
</div>

<?php 

  //DADOS PARA GRAFICO DE EQUIPE NAS DEMANDAS
  $stmt = $db->prepare('SELECT u.Nome AS nome, COUNT(d.id) AS qtd
                        FROM x_demanda AS d 
                        LEFT JOIN x_demanda_responsavel AS dr ON dr.demanda_id = d.id
                        LEFT JOIN tb_bsc_usuario AS u ON u.idUsuario = dr.responsavel_id 
                        WHERE (u.demanda = 1 OR u.quemvai = 1) AND YEAR(d.prazo) = :ano
                        GROUP BY u.Nome
                        ORDER BY u.Nome ASC');
  $stmt->bindValue(':ano', (isset($_GET['ano']) ? $_GET['ano'] : 2015));
  $stmt->execute();
  $rsEquipeDemanda = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $equipeDemandas = array();
  $equipeDemandasTot = 0;
  foreach ($rsEquipeDemanda as $k => $v) {
    $equipeDemandasTot = ($equipeDemandasTot) + ($v['qtd']);
  }
  foreach ($rsEquipeDemanda as $k => $v) {
    $equipeDemanda = array();
    $equipeDemanda['descricao'] = ( ( number_format( ( ($v['qtd']) / ($equipeDemandasTot)*(100) ), 0, ',', '.') ) . '% ' . $v['nome']);
    $equipeDemanda['qtd'] = $v['qtd'];
    array_push($equipeDemandas, $equipeDemanda);
  }

  //DADOS PARA GRAFICO DE EQUIPE NAS AGENDAS
  $stmt = $db->prepare('SELECT u.Nome AS nome, COUNT(d.idAgenda) AS qtd
                        FROM tb_bsc_agenda AS d 
                        LEFT JOIN x_agenda_participante AS dr ON dr.idAgenda = d.idAgenda
                        LEFT JOIN tb_bsc_usuario AS u ON u.idUsuario = dr.idContato 
                        WHERE (u.demanda = 1 OR u.quemvai = 1) AND YEAR(d.DataAgenda) = :ano
                        GROUP BY u.Nome
                        ORDER BY u.Nome ASC');
  $stmt->bindValue(':ano', (isset($_GET['ano']) ? $_GET['ano'] : 2015));
  $stmt->execute();
  $rsEquipeAgenda = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $equipeAgendas = array();
  $equipeAgendasTot = 0;
  foreach ($rsEquipeAgenda as $k => $v) {
    $equipeAgendasTot = ($equipeAgendasTot) + ($v['qtd']);
  }
  foreach ($rsEquipeAgenda as $k => $v) {
    $equipeAgenda = array();
    $equipeAgenda['descricao'] = ( ( number_format( ( ($v['qtd']) / ($equipeAgendasTot)*(100) ), 0, ',', '.') ) . '% ' . $v['nome']);
    $equipeAgenda['qtd'] = $v['qtd'];
    array_push($equipeAgendas, $equipeAgenda);
  }

  //DADOS PARA GRAFICO DE STATUS DE TODAS AS DEMANDAS
  $stmt = $db->prepare('SELECT DISTINCT(status), COUNT(status) AS qtd
                        FROM x_demanda 
                        WHERE YEAR(prazo) = :ano
                        GROUP BY status
                        ORDER BY FIELD(status, 2, 0, 3, 4, 1)');
  $stmt->bindValue(':ano', (isset($_GET['ano']) ? $_GET['ano'] : 2015));
  $stmt->execute();
  $rsStatusDemanda = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $statusDemandas = array();
  $statusDemandasTot = 0;
  foreach ($rsStatusDemanda as $k => $v) {
    $statusDemandasTot = ($statusDemandasTot) + ($v['qtd']);
  }
  foreach ($rsStatusDemanda as $k => $v) {
    $statusDemanda = array();
    $statusDemanda['descricao'] = ( ( number_format( ( ($v['qtd']) / ($statusDemandasTot)*(100) ), 0, ',', '.') ) . '% ' . status_demanda2($v['status']));
    $statusDemanda['qtd'] = $v['qtd'];
    $statusDemanda['status'] = $v['status'];
    array_push($statusDemandas, $statusDemanda);
  }

  //DADOS PARA GRAFICO DE SITUACAO DE TODAS AS DEMANDAS
  $stmt = $db->prepare('SELECT SUM(s.qtd) AS qtd, s.situacao AS descricao
                        FROM ( 
                          SELECT prazo, status, COUNT(id) AS qtd, 
                          CASE status 
                            WHEN 3 THEN "Concluída" 
                            WHEN 1 THEN "Cancelada" 
                          ELSE 
                            CASE 
                              WHEN prazo >= NOW() THEN "No Prazo" 
                              WHEN prazo < NOW() THEN "Atrasada" 
                            END 
                          END AS situacao 
                          FROM x_demanda 
                          WHERE YEAR(prazo) = :ano 
                          GROUP BY prazo, status 
                          ORDER BY status ASC 
                        ) AS s 
                        GROUP BY s.situacao
                        ORDER BY FIELD(s.situacao, "No Prazo", "Concluída", "Atrasada", "Cancelada") ');
  $stmt->bindValue(':ano', (isset($_GET['ano']) ? $_GET['ano'] : 2015));
  $stmt->execute();
  $rsSituacaoDemanda = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $situacaoDemandas = array();
  $situacaoDemandasTot = 0;
  foreach ($rsSituacaoDemanda as $k => $v) {
    $situacaoDemandasTot = ($situacaoDemandasTot) + ($v['qtd']);
  }
  foreach ($rsSituacaoDemanda as $k => $v) {
    $situacaoDemanda = array();
    $situacaoDemanda['descricao'] = ( ( number_format( ( ($v['qtd']) / ($situacaoDemandasTot)*(100) ), 0, ',', '.') ) . '% ' . $v['descricao']);
    $situacaoDemanda['qtd'] = $v['qtd'];
    $situacaoDemanda['situacao'] = $v['descricao'];
    array_push($situacaoDemandas, $situacaoDemanda);
  }

  //DADOS PARA GRAFICO DE DEMANDAS POR MES
  $stmt = $db->prepare('SELECT MONTH(prazo) AS mes, COUNT(id) AS qtd
                        FROM x_demanda 
                        WHERE YEAR(prazo) = :ano
                        GROUP BY MONTH(prazo)
                        ORDER BY MONTH(prazo) ASC');
  $stmt->bindValue(':ano', (isset($_GET['ano']) ? $_GET['ano'] : 2015));
  $stmt->execute();
  $rsDemandasMes = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $demandaMeses = array();
  foreach ($rsDemandasMes as $k => $v) {
    $demandaMes = array();
    $demandaMes['mes'] = $v['mes'];
    $demandaMes['qtd'] = $v['qtd'];
    array_push($demandaMeses, $demandaMes);
  }

  //DADOS PARA GRAFICO DE AGENDAS POR MES
  $stmt = $db->prepare('SELECT MONTH(DataAgenda) AS mes, COUNT(IdAgenda) AS qtd
                        FROM tb_bsc_agenda 
                        WHERE YEAR(DataAgenda) = :ano
                        GROUP BY MONTH(DataAgenda)
                        ORDER BY MONTH(DataAgenda) ASC');
  $stmt->bindValue(':ano', (isset($_GET['ano']) ? $_GET['ano'] : 2015));
  $stmt->execute();
  $rsAgendasMes = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $agendaMeses = array();
  foreach ($rsAgendasMes as $k => $v) {
    $agendaMes = array();
    $agendaMes['mes'] = $v['mes'];
    $agendaMes['qtd'] = $v['qtd'];
    array_push($agendaMeses, $agendaMes);
  }

?>

<?php include("rodape.php"); ?>

<script type="text/javascript" src="js/highcharts.js"></script>
<script type="text/javascript" src="js/highcharts-3d.js"></script>

<script type="text/javascript">

    var equipeDemandas    = <?=json_encode($equipeDemandas);?>;
    var equipeAgendas     = <?=json_encode($equipeAgendas);?>;
    var statusDemanda     = <?=json_encode($statusDemandas);?>;
    var situacaoDemanda   = <?=json_encode($situacaoDemandas);?>;
    var demandaMes        = <?=json_encode($demandaMeses);?>;
    var agendaMes         = <?=json_encode($agendaMeses);?>;

</script>

<script type="text/javascript" src="js/relatorio/relatorio-desempenho-equipe.js"></script>