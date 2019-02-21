/*---------------------------------------------------------------------------------------------------------
 DATA: 05/10/2016 ÀS 17:07
 NOME: JS DA CLASSE CADASTRO DE EMPREENDIMENTO
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {

  //Jogando a busca para a lista de empreendimento
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/empreendimento/lista");

//---------------------------------------------------------------------------------------------------------
//CADASTRO DE EMPREENDIMENTO
  $('form#form_empreendimento').submit(function () {

    $("#div_loader").show();

    if (recuperar_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/empreendimento/cadastro',
        data: $('#form_empreendimento').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {

            swal({
              title: "Formulário de empreendimento",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/empreendimento/lista'", 1);
            });

            return false;

          } else if (obj.msg == 'error') {
            swal({
              title: "Formulário de empreendimento",
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
            title: "Formulário de empreendimento",
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
//VALIDAÇÃO DO EMPREENDIMENTO
  function recuperar_validator() {
    var valido = true;
    var numero_apf = $("#numero_apf").val();
    var nome = $("#nome").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS NOME E NUMERO FORAM INFORMADOS
    // if(numero_apf == ""){
    //   $('div#div_numero_apf').after('<label id="erro_numero_apf" class="error">O campo numero é obrigatório.</label>');
    //   valido = false;
    //   element = $('div#div_numero_apf');
    // }
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


$("input#apf_sim").click(function () {
  if ($(this).is(":checked")){
    $("#apf_sim_nao").show();
  } else {
      $("#apf_sim_nao").hide();
  }
});