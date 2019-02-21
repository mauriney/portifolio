$("#titulo1").html("Relatório de Desempenho de Agente");

$('select#ano').on('change', function (){
  window.location = 'relatorio-desempenho-agentes.php?ano='+$(this).val()+'&id='+$('input#id').val();
});

$(function () {

  //DADOS PARA GRAFICO DE EQUIPE NAS DEMANDAS
  if (baseGrafico0.length > 0) {
    var dados = [];
    $.each(baseGrafico0, function (k, v){
      dados.push([]);
      dados[k][0] = v['descricao'];
      dados[k][1] = Number(v['qtd']);
    });

    //GRAFICO DE STATUS DE EQUIPE NAS DEMANDAS
    $('#chart_pie_participacao_segmento').highcharts({
      chart: {
        type: 'pie',
        options3d: {
          enabled: true,
          alpha: 0,
        },
        style: {
          fontSize: '12px'
        }
      },
      title: {
        text: 'Participação em segmentos'
      },
      plotOptions: {
        pie: {
          innerSize: '80%',
          depth: 0
        }
      },
      series: [{
        name: 'Quantidade',
        data: dados
      }]
    });
  } else {
    $('#chart_pie_participacao_segmento').html("Não hão informções para formar o gráfico");
  }

  // //DADOS PARA GRAFICO DE STATUS DE TODAS AS DEMANDAS
  if (baseGrafico1.length > 0) {
    var dados = [];
    $.each(baseGrafico1, function (k, v){
      dados.push([]);
      dados[k][0] = v['descricao'];
      dados[k][1] = Number(v['qtd']);
    });

    //GRAFICO DE STATUS DE TODAS AS DEMANDAS
    $('#chart_pie_situacao_demanda').highcharts({
      colors: ['#75C811', '#FF1D25', '#F5BD23', '#59BAD1'],
      chart: {
        type: 'pie',
        options3d: {
          enabled: true,
          alpha: 0,
        },
        style: {
          fontSize: '12px'
        }
      },
      title: {
        text: 'Situaçãoo de demandas'
      },
      plotOptions: {
        pie: {
          innerSize: '80%',
          depth: 0
        }
      },
      series: [{
        name: 'Quantidade',
        data: dados
      }]
    });
  } else {
    $('#chart_pie_situacao_demanda').html("Não hão informções para formar o gráfico");
  }

  var meses = ['JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ'];

  // //DADOS PARA GRAFICO DE DE DEMANDAS POR MES
  if (baseGrafico2.length > 0) {
    var dados = [];
    $.each(meses, function (i, m){
      dados.push([]);
      dados[i][0] = m;
      dados[i][1] = 0;
      $.each(baseGrafico2, function (k, v){
        if ((i+1) == v['mes']) {
          dados[i][1] = Number(v['qtd']);
        }
      });
    });

    //GRAFICO DE DEMANDAS POR MES
    $('#chart_line_qtd_demanda_mes').highcharts({
      chart: {
        type: 'line'
      },
      title: {
        text: 'Quantidade de demandas por mês'
      },
      xAxis: {
        categories: meses
      },
      yAxis: {
        title: {
          text: 'Quantidade'
        },
        min: 0
      },
      series: [{
        name: 'Demandas',
        type: 'line',
        data: dados,
        marker: {
          lineWidth: 2,
          lineColor: '#7CB5EC',
          fillColor: '#FFFFFF'
        }
      }]
    });
  } else {
    $('#chart_line_qtd_demanda_mes').html("Não hão informções para formar o gráfico");
  }

});
