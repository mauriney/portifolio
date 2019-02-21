//----------------------------------------------------------------------------------------------
// Jogando a busca para a lista de ação
$("form#form_busca").attr('action', PORTAL_URL + "sistema/candidato/lista");
//----------------------------------------------------------------------------------------------
//FUNÇÃO PARA CARREGAR MODAL
$("a#atualizar_modal").livequery("click", function () {
  $("div#informacao_anterior").html($(this).attr('anterior'));
  $("div#informacao_nova").html($(this).attr('novo'));
  $("div.modal-header").removeClass("bl-endereco");
  $("div.modal-header").removeClass("bl-pessoal");
  $("div.modal-header").removeClass("bl-anexo");
  $("div.modal-header").removeClass("bl-contato");
  $("div.modal-header").addClass($(this).attr('class').replace("bl ", ""));
});
// ---------------------------------------------------------------------------------------------------------
// MENSAGEM DE USUÁRIO UTILIZANDO A MESMA PÁGINA
if ($('input#vf_usuario_pagina').val() > 0) {

  var nome_usuario = $("#nome_usuario").val();

  Messenger().post({
    message: nome_usuario + ' já está realizando atualizações nesta página, deseja mesmo continuar?<br/><br/><center><button  onclick="continuar()" id="continuar" class="btn btn-primary">Sim</button>&nbsp;&nbsp;&nbsp;<button onclick="sair()" id="sair" class="btn btn-danger">Não</button></center>',
    type: 'success',
    showCloseButton: true
  });
}

function continuar() {
  $("li.messenger-message-slot").toggle('slow');
}

function sair() {
  window.history.back();
}
//----------------------------------------------------------------------------------------------
//FUNÇÃO PARA CARREGAR DADOS PARA A MODAL
function carregar_campos(obj, element) {
  var campo = $(obj).attr('rel');
  var campoKey = $(obj).parents('div.field').find('a#modalDefaultClick').attr('relkey');
  $("#motivo_titulo").html("MOTIVO DA NÃO VALIDAÇÃO DO CAMPO " + campo + ":");
  $("textarea#motivo_validacao").val('');
  $("input#titulo_campo").val(campo);
  $("input#start_option").val(element);
  $("input#titulo_campo").attr('relkey', campoKey);
  var campoValidacao = $("div#text_validacao").find('p#' + campoKey).find('span#data_validate').text();
  $("textarea#motivo_validacao").val(campoValidacao);
}
//----------------------------------------------------------------------------------------------
//CLICANDO NA MODAL E PASSANDO AS INFORMAÇÕES PARA O FORMULÁRIO
$("a#modalDefaultClick").livequery("click", function () {

  $("div#modalDefault").modal('toggle');

  carregar_campos(this, 'button');

  return false;
});
// ----------------------------------------------------------------------------------------------
// JOGANDO AS INFORMAÇÕES DO FORMULÁRIO DA MODAL PARA A OBSERVAÇÃO NO FINAL DO FORMULÁRIO
$("button#salvar_motivo").livequery("click", function () {

  var titulo = $("input#titulo_campo").val();
  var campoKey = $("input#titulo_campo").attr('relkey');
  var motivo = $("textarea#motivo_validacao").val();
  $("div#text_validacao").find('p#' + campoKey).remove();
  var validacao = $("div#text_validacao").html();
  validacao += '<p id="' + campoKey + '"><b>' + titulo + ':</b><br><span id="data_validate">' + motivo + '</span></p>';
  $("div#text_validacao").html(validacao);
  $("div.note-editable").html(validacao);
  $("input#check_" + campoKey).attr("checked", true);

  vf_checkbox();

});
// ----------------------------------------------------------------------------------------------
//CLICK FORA DA MODAL
$('#modalDefault').on('hidden.bs.modal', function (e) {

  var campoKey = $("input#titulo_campo").attr('relkey');
  if ($("textarea#motivo_validacao").val() == "") {
    $("input#check_" + campoKey).removeAttr("checked");
    $("input#start_option").val('');
  } else {
    if ($("input#start_option").val() == 'button') {
      $("input#check_" + campoKey).removeAttr("checked");
      $("input#check_" + campoKey).click();
      $("input#start_option").val('');
    }
    else {
      $("input#check_" + campoKey).removeAttr("checked");
      $("input#start_option").val('button');
      $("input#check_" + campoKey).click();
      $("input#start_option").val('');
    }
  }

});
// ----------------------------------------------------------------------------------------------
//CLICK DO CHECKBOX
$("input.checkbox-validacao").livequery("change", function () {
  if ($("input#start_option").val() == 'checkbox' || $("input#start_option").val() == '') {
    $("div#modalDefault").modal('toggle');
  }
  carregar_campos(this, 'checkbox');
  return false;
});
// ----------------------------------------------------------------------------------------------
$("button#limpar_motivo").livequery("click", function () {
  var campoKey = $("input#titulo_campo").attr('relkey');
  $("input#check_" + campoKey).removeAttr("checked");
  $("div#text_validacao").find('p#' + campoKey).text('');
  var validacao = $("div#text_validacao").html();
  $("div.note-editable").html(validacao);
  $("textarea#motivo_validacao").val('');
  $("input#start_option").val('');

  vf_checkbox();
});
// ----------------------------------------------------------------------------------------------
$("button#cancelar_motivo").livequery("click", function () {
  if ($("textarea#motivo_validacao").val() == "") {
    var campoKey = $("input#titulo_campo").attr('relkey');
    $("input#check_" + campoKey).attr("checked", false);
    $("input#check_" + campoKey).removeAttr("disabled");
  }
});
// ----------------------------------------------------------------------------------------------
$('input[type="checkbox"].checkbox-validacao').livequery("change", function () {

  vf_checkbox();
});
function vf_checkbox() {

  var vf = false;
  $('input[type="checkbox"].checkbox-validacao').each(function () {
    if ($(this).is(":checked")) {
      vf = true;
    }
  });
  if (vf) {
    $("button#confirmar_validacao").hide();
    $("button#retornar_validacao").show();
  } else {
    $("button#confirmar_validacao").show();
    $("button#retornar_validacao").hide();
  }
}

// ----------------------------------------------------------------------------------------------
// FINALIZANDO VALIDAÇÃO
$("button#confirmar_validacao").livequery("click", function () {

  $("#div_loader").show();
  $.ajax({
    type: "POST",
    url: PORTAL_URL + 'hab/dao/candidato/validacao',
    data: {
      candidato_id: $("#candidato_id").val(), validacao: $("div.note-editable").html()
    },
    cache: false,
    success: function (obj) {
      obj = JSON.parse(obj);
      if (obj.msg == 'success') {
        swal({
          title: "Formulário de Validação",
          text: obj.retorno,
          type: "success",
          showCancelButton: false,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "OK",
          closeOnConfirm: false
        }, function () {
          setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/lista'", 1);
        });
        return false;
      } else if (obj.msg == 'error') {
        swal({
          title: "Formulário de Validação",
          text: "Erro ao tentar validar o formulário",
          type: "error",
          showCancelButton: false,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "OK",
          closeOnConfirm: false
        });
        $("#div_loader").hide();
        return false;
      }
    },
    error: function (obj) {
      swal({
        title: "Formulário de Validação",
        text: "Erro ao tentar validar o formulário",
        type: "error",
        showCancelButton: false,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "OK",
        closeOnConfirm: false
      });
      $("#div_loader").hide();
      return false;
    }
  });
  return false;
});
// ----------------------------------------------------------------------------------------------
// RETORNANDO VALIDAÇÃO
$("button#retornar_validacao").livequery("click", function () {

  $("#div_loader").show();
  $.ajax({
    type: "POST",
    url: PORTAL_URL + 'hab/dao/candidato/retornar',
    data: {
      candidato_id: $("#candidato_id").val(),
      validacao: $("div.note-editable").html()
    },
    cache: false,
    success: function (obj) {
      obj = JSON.parse(obj);
      if (obj.msg == 'success') {
        swal({
          title: "Formulário de Validação",
          text: obj.retorno,
          type: "success",
          showCancelButton: false,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "OK",
          closeOnConfirm: false
        }, function () {
          setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/lista'", 1);
        });
        return false;
      } else if (obj.msg == 'error') {
        swal({
          title: "Formulário de Validação",
          text: "Erro ao tentar retornar a validação",
          type: "error",
          showCancelButton: false,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "OK",
          closeOnConfirm: false
        });
        $("#div_loader").hide();
        return false;
      }
    },
    error: function (obj) {
      swal({
        title: "Formulário de Validação",
        text: "Erro ao tentar retornar a validaçã",
        type: "error",
        showCancelButton: false,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "OK",
        closeOnConfirm: false
      });
      $("#div_loader").hide();
      return false;
    }
  });
  return false;
});
// ----------------------------------------------------------------------------------------------
// function pesquisar_texto(campo) {
//
// var texto = $("div#text_validacao").html();
// var guard = "";
// var cond = false;
// var cond2 = false;
// var temporario = "";
// var vf = false;
//
// for (var i = 0; i < texto.length; i++) {
//
// temporario += "" + texto[i] + "";
//
// if (texto[i - 4] + "" + texto[i - 3] + "" + texto[i - 2] + "" + texto[i - 1] + "" + texto[i] == ":</b>") {
// cond = true;
// }
//
// if (cond == true && texto[i - 3] + "" + texto[i - 2] + "" + texto[i - 1] + "" + texto[i] == "</p>") {
// cond2 = true;
// }
//
// if (cond == true && cond2 == false) {
// if (temporario == "<p><b>" + campo + ":</b>" || vf == true) {
// guard += texto[i];
// vf = true;
// }
// }
// }
//
// alert(guard);
//
// }

// ----------------------------------------------------------------------------------------------
