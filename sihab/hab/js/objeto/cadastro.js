/*---------------------------------------------------------------------------------------------------------
 DATA: 29/07/2016 ÀS 15:07
 NOME: JS DA CLASSE CADASTRO DE AÇÃO
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {

  //Jogando a busca para a lista de objeto
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/objeto/lista");

//---------------------------------------------------------------------------------------------------------
//CADASTRO DE AÇÃO
  $('form#form_objeto').submit(function () {

    $("#div_loader").show();

    if (recuperar_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/objeto/cadastro',
        data: $('#form_objeto').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {

            swal({
              title: "Formulário de Objeto",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/objeto/lista'", 1);
            });

            return false;

          } else if (obj.msg == 'error') {
            swal({
              title: "Formulário de Objeto",
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
            title: "Formulário de Objeto",
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
//VALIDAÇÃO DO OBJETO
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
  //---------------------------------------------------------------------------------------------------------
});