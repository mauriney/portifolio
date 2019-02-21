<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php

  $stmt = $db->prepare('SELECT UPPER(u.nome) AS nome 
                        FROM tb_bsc_usuario AS u 
                        WHERE u.IdUsuario = :usuario_id ');
  $stmt->bindValue(':usuario_id', (isset($_GET['id']) ? $_GET['id'] : 0));
  $stmt->execute();
  $rsStmt = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!-- Painel de Demandas -->
<div class="container conteudo-bd">

  <input type="hidden" id="id" value="<?=(isset($_GET['id']) ? $_GET['id'] : 0);?>">

  <div class="botoes-acao">
    <a href="#" title="Imprimir" class="imprimir" onclick="window.print();"></a>
  </div>
  <div class="report">
    <div class="row">
      <div class="col-md-12">
          <span class="rp-agente"><?= utf8_encode($rsStmt['nome']);?></span>
      </div>
    </div><!-- fim linha -->
    <div class="row">
      <div class="col-md-2">
        <select id="ano" name="ano" class="form-control">
          <option value="2014" <?=(isset($_GET['ano']) ? $_GET['ano'] == 2014 ? 'selected="true"' : '' : '');?>>2014</option>
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
          <div id="chart_pie_participacao_segmento" style="width:100%; height:230px;"></div>
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
          <div id="chart_line_qtd_demanda_mes" style="width:100%; height:250px;"></div>
        </span>
      </div>
    </div><!-- fim linha -->
    <div class="clearfix"></div>
  </div>
</div>

<?php 

  //DADOS PARA GRAFICO DE EQUIPE NAS DEMANDAS
  $stmt = $db->prepare('SELECT s.Descricao AS nome, COUNT(d.id) AS qtd 
                        FROM x_demanda AS d 
                        LEFT JOIN tb_bsc_segmento AS s ON s.IdSegmento = d.segmento 
                        LEFT JOIN x_demanda_responsavel AS dr ON dr.demanda_id = d.id 
                        LEFT JOIN tb_bsc_usuario AS u ON u.idUsuario = dr.responsavel_id 
                        WHERE (u.demanda = 1 OR u.quemvai = 1) AND YEAR(d.prazo) = :ano 
                          AND u.IdUsuario = :usuario_id 
                        GROUP BY s.Descricao 
                        ORDER BY s.Descricao ASC');
  $stmt->bindValue(':ano', (isset($_GET['ano']) ? $_GET['ano'] : 2015));
  $stmt->bindValue(':usuario_id', (isset($_GET['id']) ? $_GET['id'] : 0));
  $stmt->execute();
  $rsStmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $baseGrafico0 = array();
  $qtdTot = 0;
  foreach ($rsStmt as $k => $v) {
    $qtdTot = ($qtdTot) + ($v['qtd']);
  }
  foreach ($rsStmt as $k => $v) {
    $dado = array();
    $dado['descricao'] = ( ( number_format( ( ($v['qtd']) / ($qtdTot)*(100) ), 0, ',', '.') ) . '% ' . $v['nome']);
    $dado['qtd'] = $v['qtd'];
    array_push($baseGrafico0, $dado);
  }

  //DADOS PARA GRAFICO DE SITUACAO DE TODAS AS DEMANDAS
  $stmt = $db->prepare('SELECT SUM(s.qtd) AS qtd, s.situacao AS nome 
                        FROM ( 
                          SELECT d.prazo, d.status, COUNT(d.id) AS qtd, 
                          CASE d.status 
                            WHEN 3 THEN "Concluída" 
                            WHEN 2 THEN "Não Iniciada" 
                          ELSE 
                            CASE 
                              WHEN d.prazo >= NOW() THEN "No Prazo" 
                              WHEN d.prazo < NOW() THEN "Atrasada" 
                            END 
                          END AS situacao 
                          FROM x_demanda AS d 
                          LEFT JOIN x_demanda_responsavel AS dr ON dr.demanda_id = d.id 
                          LEFT JOIN tb_bsc_usuario AS u ON u.idUsuario = dr.responsavel_id 
                          WHERE (u.demanda = 1 OR u.quemvai = 1) AND YEAR(d.prazo) = :ano 
                          AND u.IdUsuario = :usuario_id 
                          GROUP BY d.prazo, d.status 
                          ORDER BY d.status ASC 
                        ) AS s 
                        GROUP BY s.situacao');
  $stmt->bindValue(':ano', (isset($_GET['ano']) ? $_GET['ano'] : 2015));
  $stmt->bindValue(':usuario_id', (isset($_GET['id']) ? $_GET['id'] : 0));
  $stmt->execute();
  $rsStmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $baseGrafico1 = array();
  $qtdTot = 0;
  foreach ($rsStmt as $k => $v) {
    $qtdTot = ($qtdTot) + ($v['qtd']);
  }
  foreach ($rsStmt as $k => $v) {
    $dado = array();
    $dado['descricao'] = ( ( number_format( ( ($v['qtd']) / ($qtdTot)*(100) ), 0, ',', '.') ) . '% ' . $v['nome']);
    $dado['qtd'] = $v['qtd'];
    array_push($baseGrafico1, $dado);
  }

  //DADOS PARA GRAFICO DE DEMANDAS POR MES
  $stmt = $db->prepare('SELECT MONTH(d.prazo) AS mes, COUNT(d.id) AS qtd
                        FROM x_demanda AS d 
                        LEFT JOIN x_demanda_responsavel AS dr ON dr.demanda_id = d.id 
                        LEFT JOIN tb_bsc_usuario AS u ON u.idUsuario = dr.responsavel_id 
                          WHERE (u.demanda = 1 OR u.quemvai = 1) AND YEAR(d.prazo) = :ano 
                          AND u.IdUsuario = :usuario_id 
                        GROUP BY MONTH(d.prazo)
                        ORDER BY MONTH(d.prazo) ASC');
  $stmt->bindValue(':ano', (isset($_GET['ano']) ? $_GET['ano'] : 2015));
  $stmt->bindValue(':usuario_id', (isset($_GET['id']) ? $_GET['id'] : 0));
  $stmt->execute();
  $rsStmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $baseGrafico2 = array();
  foreach ($rsStmt as $k => $v) {
    $dado = array();
    $dado['mes'] = $v['mes'];
    $dado['qtd'] = $v['qtd'];
    array_push($baseGrafico2, $dado);
  }

?>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/highcharts.js"></script>
<script type="text/javascript" src="js/highcharts-3d.js"></script>
<script type="text/javascript" src="js/relatorio/relatorio-desempenho-agentes.js"></script>

<script type="text/javascript">

    var baseGrafico0    = <?=json_encode($baseGrafico0);?>;
    var baseGrafico1     = <?=json_encode($baseGrafico1);?>;
    var baseGrafico2        = <?=json_encode($baseGrafico2);?>;

</script>
