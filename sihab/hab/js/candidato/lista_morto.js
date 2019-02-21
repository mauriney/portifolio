/*---------------------------------------------------------------------------------------------------------
 DATA: 04/11/2016 ÀS 09:50
 NOME: JS DA CLASSE DE LISTA DE ARQUIVO MORTO
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
  // createTableBootGrid('data_table_candidato');
  // $('input#situacao_candidato').change();
  $('#data_table_candidato').each(function () {
    createTableBootGrid($(this).attr('id'));
  });

  if ($('input#situacao_opcao').val() != '') {
    $('a.' + $('input#situacao_opcao').val()).click();
  }

  // Range slider with value
  if ($('div.input-slider-values')[0]) {
    $('div.input-slider-values').noUiSlider({
      start: [0, 900],
      connect: true,
      direction: 'rtl',
      behaviour: 'tap-drag',
      range: {
        'min': 0,
        'max': 900
      }
    });
    $('div.input-slider-values').Link('lower').to($('strong#value-lower'));
    $('div.input-slider-values').Link('upper').to($('strong#value-upper'), 'html');
    $('div.input-slider-values').Link('upper').to($('input#renda_valor_min'));
    $('div.input-slider-values').Link('lower').to($('input#renda_valor_max'));
  }

});

function createTableBootGrid(tableId) {
  var formInput = $('#form_lista_morto').serializeArray();
  var formSend = {};
  $.each(formInput, function (k, v) {
    formSend[v['name']] = v['value'];
  });
  $('table#' + tableId).bootgrid({
    css: {
      icon: 'zmdi icon',
      iconColumns: 'zmdi-view-module',
      iconDown: 'zmdi-expand-more',
      iconRefresh: 'zmdi-refresh',
      iconUp: 'zmdi-expand-less'
    },
    visible: true,
    ajax: true,
    post: function () {
      return formSend
    },
    url: PORTAL_URL + 'hab/view/candidato/lista_dinamica_morto.php',
    // formatters : {
    // "link" : function(column, row) {
    // alert(column.id);
    // alert(row.id);
    // }
    // }
    formatters: {
//		    "cadastro_unico" : function(column, row) {
//				    if (row.cadastro_unico == 1) {
//						    return "<div class='status-cadunico cg-list cgl-main palette-Light-Green-500 bg'><span class='zmdi zmdi-check zmdi-hc-fw'></span></div>";
//				    } else {
//						    return "<div class='status-cadunico cg-list palette-Red-A400 bg'><span class='zmdi zmdi-close zmdi-hc-fw'></span></div>";
//				    }
//		    },
      "apto": function (column, row) {

        var permissao = $("input#permissao").val() == true ? '' : 'disabled="true"';

        var text = "" + (row.apto == 2 ? 'Sorteado' : (row.apto == 1 ? '<div class="checkbox"><label><input ' + permissao + ' rel="' + row.id + '" checked="true" class="select-box" id="candidato_apto" name="candidato_apto" value="1" type="checkbox"><i class="input-helper"></i></label></div>' : '<div class="checkbox"><label><input ' + permissao + ' rel="' + row.id + '" class="select-box" id="candidato_apto" name="candidato_apto" value="0" type="checkbox"><i class="input-helper"></i></label></div>')) + "";
        return text;
      },
      "visualiza": function (column, row) {
        var text = "<a title='Visualizar' href='" + PORTAL_URL + "sistema/candidato/visualiza/" + row.id + "/1'><button type=\"button\" class=\"btn btn-icon palette-Cyan bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-chevron-right\"></span></button></a>";
        return text;
      },
      "edita": function (column, row) {
        var text = "<a title='Editar' href='" + PORTAL_URL + "sistema/candidato/etapa1/" + row.id + "/1'><button type=\"button\" class=\"btn btn-icon palette-Orange bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button></a>";

        return text;
      },
      "geral": function (column, row) {
        var text = "<a title='Editar' href='" + PORTAL_URL + "sistema/candidato/etapa1/" + row.id + "/1'><button type=\"button\" class=\"btn btn-icon palette-Orange bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button></a>\n\
                    <a title='Ativar Candidato' href='" + PORTAL_URL + "sistema/candidato/etapa1/" + row.id + "/0'><button type=\"button\" class=\"btn btn-icon palette-Light-Green bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-account-add\"></span></button></a>\n\
                    <a title='Visualizar' href='" + PORTAL_URL + "sistema/candidato/visualiza/" + row.id + "/1'><button type=\"button\" class=\"btn btn-icon palette-Cyan bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-chevron-right\"></span></button></a>";
        return text;
      }
    }
  });
}

$('ul.tab-nav li').livequery('click', function () {
  $(this).parents('ul').attr('class', 'tab-nav').addClass('tab-' + $(this).find('a').attr('class'));
  $('input#situacao_opcao').val($(this).find('a').attr('class'));
});

// ---------------------------------------------------------------------------------------------------------
// FUNÇÃO PARA PESQUISAR DINÂMICO EM INPUT
// Typeahead Auto Complete

if ($('input#bairro')) {

  // var bairrosAcre = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
  // 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
  // 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
  // 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
  // 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
  // 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
  // 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
  // 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
  // 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
  // ];
  var bairros = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: bairrosAcre
  });

  $('input#bairro').typeahead({
    hint: true,
    highlight: true,
    minLength: 1
  }, {
    name: 'bairros',
    source: bairros
  });
}

// ---------------------------------------------------------------------------------------------------------

// Jogando a busca para a lista de Candidato
$("form#form_busca").attr('action', PORTAL_URL + "sistema/candidato/lista");
// ---------------------------------------------------------------------------------------------------------

$("input#candidato_apto").livequery('change', function () {

  var opcao = $(this).parents('label').find("input:checked").val();
  var candidato_id = $(this).attr('rel');

  $.ajax({
    type: "POST",
    url: PORTAL_URL + 'hab/dao/candidato/lista_morto',
    data: {candidato_id: candidato_id, opcao: opcao},
    cache: false,
    success: function (obj) {
      obj = JSON.parse(obj);
      if (obj.msg == 'success') {

        return false;

      } else if (obj.msg == 'error') {
        return false;
      }
    },
    error: function (obj) {
      return false;
    }
  });

});

// ---------------------------------------------------------------------------------------------------------