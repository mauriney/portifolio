/*---------------------------------------------------------------------------------------------------------
 DATA: 17/10/2016 ÀS 09:30
 NOME: JS DA CLASSE CADASTRO DE ESPECIFICIDADE
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {

//Jogando a busca para a lista de ação
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/especificidade/lista");

//---------------------------------------------------------------------------------------------------------
//CADASTRO DE ESPECIFICIDADE
  $('form#form_especificidade').submit(function () {

    $("#div_loader").show();

    if (recuperar_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/especificidade/cadastro',
        data: $('#form_especificidade').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {

            swal({
              title: "Formulário de Especificidade",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/especificidade/lista'", 1);
            });

            return false;

          } else if (obj.msg == 'error') {
            swal({
              title: "Formulário de Especificidade",
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
            title: "Formulário de Especificidade",
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
//VALIDAÇÃO DA ESPECIFICIDADE
  function recuperar_validator() {
    var valido = true;
    var nome = $("#nome").val();
    var programa = $("#programa").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS NOME E ESPECIFICIDADE FORAM INFORMADOS
    if (nome == "") {
      $('div#div_nome').after('<label id="erro_nome" class="error">O campo nome é obrigatório.</label>');
      valido = false;
      element = $('div#div_nome');
    }

    if (programa == "") {
      $('div#div_programa').after('<label id="erro_programa" class="error">O campo origem da demanda é obrigatório.</label>');
      valido = false;
      element = $('div#div_programa');
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
});