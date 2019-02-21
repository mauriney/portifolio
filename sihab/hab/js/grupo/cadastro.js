/*---------------------------------------------------------------------------------------------------------
 DATA: 01/08/2016 ÀS 15:07
 NOME: JS DA CLASSE CADASTRO DE GRUPO
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {

//Jogando a busca para a lista de ação
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/grupo/lista");

//---------------------------------------------------------------------------------------------------------
//CADASTRO DE GRUPO
  $('form#form_grupo').submit(function () {

    $("#div_loader").show();

    if (recuperar_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/grupo/cadastro',
        data: $('#form_grupo').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {

            swal({
              title: "Formulário de Grupo",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/grupo/lista'", 1);
            });

            return false;

          } else if (obj.msg == 'error') {
            swal({
              title: "Formulário de Grupo",
              text: obj.retorno,
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
            title: "Formulário de Grupo",
            text: obj.retorno,
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
    } else {
      $("#div_loader").hide();
      return false;
    }
  });
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DA AÇÂO
  function recuperar_validator() {
    var valido = true;
    var nome = $("#nome").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS
    if (nome == "") {
      $('div#div_nome').after('<label id="erro_nome" class="error">O campo nome é obrigatório.</label>');
      valido = false;
      element = $('div#div_nome');
    }

    if (element != null) {
      var topPosition = element.offset().top - 135;
      $('html, body').animate({
        scrollTop: topPosition
      }, 800);
    }

    return valido;
  }
});
//---------------------------------------------------------------------------------------------------------

function marcar_todos(obj) {

  if ($(obj).attr('checked') != 'checked') {
    $(obj).attr('checked', 'checked');
    $(obj).prop("checked", true);
  } else {
    $(obj).removeAttr('checked');
    $(obj).prop("checked", false);
  }

  if ($(obj).attr("checked") == 'checked') {
    $(obj).parents('fieldset').find('input.marcar').each(function (i, acao) {
      $(acao).attr('checked', 'checked');
      $(acao).prop("checked", true);
    });
  } else {
    $(obj).parents('fieldset').find('input.marcar').each(function (i, acao) {
      $(acao).removeAttr('checked');
      $(acao).prop("checked", false);
    });
  }
}
//---------------------------------------------------------------------------------------------------------
function verificar(obj, cont) {

  if ($(obj).attr('checked') != 'checked') {
    $(obj).attr('checked', 'checked');
    $(obj).prop("checked", true);
  } else {
    $(obj).removeAttr('checked');
    $(obj).prop("checked", false);
  }

  if ($(obj).attr("checked") == 'checked') {
    var checado = true;
    $("div#vf_check").find("input.marcar").each(function (i, value) {
      if ($(value).attr('checked') != 'checked' && $(value).val() != $(obj).val() && $(value).attr("id") != 'todos_' + cont) {
        checado = false;
      }
    });

    if (checado) {
      $('#todos_' + cont).attr('checked', 'checked');
      $('#todos_' + cont).prop("checked", true);
    } else {
      $('#todos_' + cont).removeAttr('checked');
      $('#todos_' + cont).prop("checked", false);
    }
  } else {
    $('#todos_' + cont).removeAttr('checked');
    $('#todos_' + cont).prop("checked", false);
  }
}
//---------------------------------------------------------------------------------------------------------
//VERIFICA SE TODOS OS CHECKS ESTÃO MARCADOS, CASO ESTEJAM ENTÃO MARCA O CHECK TODOS
function verificar_checks() {

  var vf_checks = true;

  $("div.organizador").each(function () {

    vf_checks = true;

    $(this).find('div#vf_check').find('input[type="checkbox"]').each(function () {
      if ($(this).attr('checked') != 'checked') {
        vf_checks = false;
      }
    });

    if (vf_checks == true) {
      $(this).parents('div').find('.organizador').find('input[type="checkbox"]').attr('checked', 'checked');
      $(this).parents('div').find('.organizador').find('input[type="checkbox"]').prop("checked", true);
    }
  });
}
//---------------------------------------------------------------------------------------------------------
