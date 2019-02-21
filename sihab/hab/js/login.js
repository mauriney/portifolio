/*---------------------------------------------------------------------------------------------------------
 DATA: 26/07/2016 ÀS 09:42
 NOME: JS DA CLASSE DE LOGIN
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
//---------------------------------------------------------------------------------------------------------
//LOGIN NO SISTEMA
  $('form#form_login').submit(function () {
    if (login_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/autenticar.php',
        data: $('#form_login').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {
            setTimeout("location.href='dashboard'", 1);
          } else if (obj.msg == 'redefinir') {
            setTimeout("location.href='alterar_senha.php'", 1);
          } else if (obj.msg == 'error') {
            swal({
              title: "Autenticação",
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
            title: "Autenticação",
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
//REDEFINIR A SENHA
  $('form#form_redefinir').submit(function () {

    $("#div_loader").show();

    if (redefinir_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/redefinir.php',
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
              setTimeout("location.href='login'", 1);
            });
          } else if (obj.msg == 'error') {
            $('div#div_email').after('<label id="erro_email" class="error">' + obj.retorno + '</label>');
            $("#div_loader").hide();
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
});
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DO LOGIN
function login_validator() {
  var valido = true;
  var login = $("#login").val();
  var senha = $("#senha").val();

  //LIMPA MENSAGENS DE ERRO
  $('label.error').each(function () {
    $(this).remove();
  });

  //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS
  if (login == "") {
    $('div#div_login').after('<label id="erro_login" class="error">O campo usuário é obrigatório.</label>');
    valido = false;
  }
  if (senha == "") {
    $('div#div_senha').after('<label id="erro_senha" class="error">O campo senha é obrigatório.</label>');
    valido = false;
  }
  return valido;
}
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DA REDEFINIÇÃO DE SENHA
function redefinir_validator() {
  var valido = true;
  var email = $("#email").val();

  //LIMPA MENSAGENS DE ERRO
  $('label.error').each(function () {
    $(this).remove();
  });

  //VERIFICANDO SE OS CAMPOS FORAM INFORMADOS
  if (email == "") {
    $('div#div_email').after('<label id="erro_email" class="error">O campo e-mail é obrigatório.</label>');
    valido = false;
  }

  return valido;
}
//---------------------------------------------------------------------------------------------------------