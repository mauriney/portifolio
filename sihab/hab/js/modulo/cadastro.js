/*---------------------------------------------------------------------------------------------------------
 DATA: 29/07/2016 ÀS 15:07
 NOME: JS DA CLASSE CADASTRO DE AÇÃO
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {

  //Jogando a busca para a lista de ação
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/modulo/lista");

//---------------------------------------------------------------------------------------------------------
//CADASTRO DE MÓDULO
  $('form#form_modulo').submit(function () {

    $("#div_loader").show();

    if (recuperar_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/modulo/cadastro',
        data: $('#form_modulo').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {

            swal({
              title: "Formulário de Módulo",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/modulo/lista'", 1);
            });

            return false;

          } else if (obj.msg == 'error') {
            swal({
              title: "Formulário de Módulo",
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
            title: "Formulário de Módulo",
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
//VALIDAÇÃO DE MÓDULO
  function recuperar_validator() {
    var valido = true;
    var nome = $("#nome").val();
    var versao = $("#versao").val();
    var url = $("#url").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS

    if (url == "") {
      $('div#div_url').after('<label id="erro_descricao" class="error">O campo url é obrigatório.</label> ');
      valido = false;
      element = $('div#div_url');
    }

    if (versao == "") {
      $('div#div_versao').after('<label id="erro_versao" class="error">O campo versão é obrigatório.</labe>');
      valido = false;
      element = $('div#div_versao');
    }

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
  //---------------------------------------------------------------------------------------------------------
  function modulo_objeto_acao(obj) {
    $(obj).parent('div').find('input#modulo_objeto_acao_id').val('0');
  }
  //---------------------------------------------------------------------------------------------------------
});