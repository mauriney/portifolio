/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE REDEFINIR
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
//---------------------------------------------------------------------------------------------------------
//REDEFINIÇÃO DE SENHA NO SISTEMA
  $('form#form_redefinir').submit(function () {
    if (redefinir_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'dao/redefinir.php',
        data: $('#form_redefinir').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {
            swal({
              title: "Redefinição de Senha",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='login.php'", 1);
            });
            return false;
          } else if (obj.msg == 'error') {
            swal({
              title: "Redefinição de Senha",
              text: obj.retorno,
              type: "error",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            });
            return false;
          }
        },
        error: function (obj) {
          swal({
            title: "Redefinição de Senha",
            text: obj.retorno,
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
          });
          return false;
        }
      });
      return false;
    } else {
      return false;
    }
  });
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DE REDEFINIÇÃO DE SENHA
  function redefinir_validator() {
    var valido = true;
    var email = $("#email").val();

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS E-MAIL FOI INFORMADO
    if (email == "") {
      $('div#div_email').after('<label id="erro_email" class="error">O campo de e-mail é obrigatório.</label>');
      valido = false;
    }
    return valido;
  }
});
//---------------------------------------------------------------------------------------------------------