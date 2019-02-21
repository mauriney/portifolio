$("#titulo1").html("Relatório de Desempenho de Equipe");

$('select#ano').on('change', function (){
  window.location = 'relatorio-desempenho-equipe.php?ano='+$(this).val();
});

$(function () {

  //DADOS PARA GRAFICO DE EQUIPE NAS DEMANDAS
  if (equipeDemandas.length > 0) {
    var dataEquipeDemanda = [];
    $.each(equipeDemandas, function (k, v){
      dataEquipeDemanda.push([]);
      dataEquipeDemanda[k][0] = v['descricao'];
      dataEquipeDemanda[k][1] = Number(v['qtd']);
    });

    //GRAFICO DE STATUS DE EQUIPE NAS DEMANDAS
    $('#chart_pie_equipe_demanda').highcharts({
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
        text: '% de participação da equipe nas demandas'
      },
      plotOptions: {
        pie: {
          innerSize: '80%',
          depth: 0
        }
      },
      series: [{
        name: 'Quantidade',
        data: dataEquipeDemanda
      }]
    });
  } else {
    $('#chart_pie_equipe_demanda').html("Não hão informções para formar o gráfico");
  }

  //DADOS PARA GRAFICO DE EQUIPE NAS AGENDAS
  if (equipeAgendas.length > 0) {
    var dataEquipeAgenda = [];
    $.each(equipeAgendas, function (k, v){
      dataEquipeAgenda.push([]);
      dataEquipeAgenda[k][0] = v['descricao'];
      dataEquipeAgenda[k][1] = Number(v['qtd']);
    });

    //GRAFICO DE STATUS DE EQUIPE NAS AGENDAS
    $('#chart_pie_equipe_agenda').highcharts({
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
        text: '% de participação da equipe nas agendas'
      },
      plotOptions: {
        pie: {
          innerSize: '80%',
          depth: 0
        }
      },
      series: [{
        name: 'Quantidade',
        data: dataEquipeAgenda
      }]
    });
  } else {
    $('#chart_pie_equipe_agenda').html("Não hão informções para formar o gráfico");
  }

  //DADOS PARA GRAFICO DE STATUS DE TODAS AS DEMANDAS
  if (statusDemanda.length > 0) {
    var dataStatusDemanda = [];
    var colorsSet = [];
    var colorsAll = ['#5DD300', '#EF0C69', '#CCCCCC', '#0090FC', '#6A18EA'];
    $.each(statusDemanda, function (k, v){
      dataStatusDemanda.push([]);
      dataStatusDemanda[k][0] = v['descricao'];
      dataStatusDemanda[k][1] = Number(v['qtd']);
      colorsSet[k] = colorsAll[v['status']];
    });

    //GRAFICO DE STATUS DE TODAS AS DEMANDAS
    $('#chart_pie_status_demanda').highcharts({
      colors: colorsSet,
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
        text: 'status de todas as demandas'
      },
      plotOptions: {
        pie: {
          innerSize: '80%',
          depth: 0
        }
      },
      series: [{
        name: 'Quantidade',
        data: dataStatusDemanda
      }]
    });
  } else {
    $('#chart_pie_status_demanda').html("Não hão informções para formar o gráfico");
  }

  //DADOS PARA GRAFICO DE SITUACAO DE TODAS AS DEMANDAS
  if (situacaoDemanda.length > 0) {
    var dataSituacaoDemanda = [];
    var colorsSet = [];
    var colorsAll = ['#75C811', '#59BAD1', '#FF1D25', '#6A18EA'];
    var situacaoAll = ["No Prazo", "Concluída", "Atrasada", "Cancelada"];
    $.each(situacaoDemanda, function (k, v){
      dataSituacaoDemanda.push([]);
      dataSituacaoDemanda[k][0] = v['descricao'];
      dataSituacaoDemanda[k][1] = Number(v['qtd']);
      // var aux = ;
      // console.log(aux);
      colorsSet[k] = colorsAll[situacaoAll.indexOf(v['situacao'])];
    });

    //GRAFICO DE SITUACAO DE TODAS AS DEMANDAS
    $('#chart_pie_situacao_demanda').highcharts({
      colors: colorsSet,
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
        text: 'situação de todas as demandas'
      },
      plotOptions: {
        pie: {
          innerSize: '80%',
          depth: 0
        }
      },
      series: [{
        name: 'Quantidade',
        data: dataSituacaoDemanda
      }]
    });
  } else {
    $('#chart_pie_situacao_demanda').html("Não hão informções para formar o gráfico");
  }

  var meses = ['JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ'];

  //DADOS PARA GRAFICO DE DE DEMANDAS POR MES
  if (demandaMes.length > 0) {
    var dataDemandaMes = [];
    $.each(meses, function (i, m){
      dataDemandaMes.push([]);
      dataDemandaMes[i][0] = m;
      dataDemandaMes[i][1] = 0;
      $.each(demandaMes, function (k, v){
        if ((i+1) == v['mes']) {
          dataDemandaMes[i][1] = Number(v['qtd']);
        }
      });
    });

    //GRAFICO DE DEMANDAS POR MES
    $('#chart_line_qtd_demanda_mes').highcharts({
      chart: {
        type: 'line'
      },
      title: {
        text: 'Quantidade de Demandas por Mês'
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
        data: dataDemandaMes,
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

  //DADOS PARA GRAFICO DE DE AGENDAS POR MES
  if (agendaMes.length > 0) {
    var dataAgendaMes = [];
    $.each(meses, function (i, m){
      dataAgendaMes.push([]);
      dataAgendaMes[i][0] = m;
      dataAgendaMes[i][1] = 0;
      $.each(agendaMes, function (k, v){
        if ((i+1) == v['mes']) {
          dataAgendaMes[i][1] = Number(v['qtd']);
        }
      });
    });

    //GRAFICO DE AGENDAS POR MES
    $('#chart_line_qtd_agenda_mes').highcharts({
      chart: {
        type: 'line'
      },
      title: {
        text: 'Quantidade de Agendas por Mês'
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
        name: 'Agendas',
        type: 'line',
        data: dataAgendaMes,
        marker: {
          lineWidth: 2,
          lineColor: '#7CB5EC',
          fillColor: '#FFFFFF'
        }
      }]
    });
  } else {
    $('#chart_line_qtd_agenda_mes').html("Não hão informções para formar o gráfico");
  }
});