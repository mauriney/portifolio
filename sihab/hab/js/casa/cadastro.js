/*---------------------------------------------------------------------------------------------------------
 DATA: 14/06/2017 ÀS 16:54
 NOME: JS DA CLASSE CADASTRO DE CASA
 DESENVOLVEDOR: NIRO LIMA
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {

//Jogando a busca para a lista de ação
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/casa/lista");

//---------------------------------------------------------------------------------------------------------
//CADASTRO DE CASA
  $('form#form_casa').submit(function () {

    $("#div_loader").show();

    if (recuperar_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/casa/cadastro',
        data: $('#form_casa').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {

            swal({
              title: "Formulário de Casa",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/casa/lista'", 1);
            });

            return false;

          } else if (obj.msg == 'error') {
            swal({
              title: "Formulário de Casa",
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
            title: "Formulário de Casa",
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
//VALIDAÇÃO
  function recuperar_validator() {

    var valido = true;
    var nome = $("#nome").val();
    var endereco = $("#endereco").val();
    var numero = $("#numero").val();
    var loteamento_id = $("#loteamento_id").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS

    if (loteamento_id == "") {
      $('div#div_loteamento').after('<label id="erro_loteamento" class="error">O campo loteamento é obrigatório.</label>');
      valido = false;
      element = $('div#div_loteamento');
    }

    if (numero == "") {
      $('div#div_numero').after('<label id="erro_numero" class="error">O campo número é obrigatório.</label>');
      valido = false;
      element = $('div#div_numero');
    }

    if (endereco == "") {
      $('div#div_endereco').after('<label id="erro_endereco" class="error">O campo endereço é obrigatório.</label>');
      valido = false;
      element = $('div#div_endereco');
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
});