/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE REGISTRAR
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
//---------------------------------------------------------------------------------------------------------
//REGISTRAR NO SISTEMA
  $('form#form_register').submit(function () {
    if (register_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'dao/registrar.php',
        data: $('#form_register').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {
            swal({
              title: "Registro de Usuário",
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
              title: "Registro de Usuário",
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
            title: "Registro de Usuário",
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
//VALIDAÇÃO DO REGISTRO
  function register_validator() {
    var valido = true;
    var nome = $("#nome").val();
    var cpf = $("#cpf").val();
    var email = $("#email").val();
    var senha = $("#senha").val();
    var confirmar = $("#confirmar").val();
    var contato = $("#contato").val();
    var termo = $("input[id='termo']:checked").val();

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS

    if (termo == undefined) {
      $('div#div_termo').after('<label id="erro_termo" class="error">Termo de uso é obrigatório.</label>');
      valido = false;
    }

    if (email == "") {
      $('div#div_email').after('<label id="erro_email" class="error">O campo de e-mail é obrigatório.</label>');
      valido = false;
    }

    if (senha == "") {
      $('div#div_senha').after('<label id="erro_senha" class="error">O campo senha é obrigatório.</label>');
      valido = false;
    }

    if (confirmar == "") {
      $('div#div_confirmar').after('<label id="erro_confirmar" class="error">O campo confirmar senha é obrigatório.</label>');
      valido = false;
    }

    if (senha != "" && confirmar != "" && senha != confirmar) {
      $('div#div_senha').after('<label id="erro_senha" class="error">A senha e confirmação não coincidem.</label>');
      $('div#div_confirmar').after('<label id="erro_confirmar" class="error">A senha e confirmação não coincidem.</label>');
      valido = false;
    }

    if (contato == "") {
      $('div#div_contato').after('<label id="erro_contato" class="error">O campo contato é obrigatório.</label>');
      valido = false;
    }

    if (cpf == "") {
      $('div#div_cpf').after('<label id="erro_cpf" class="error">O campo cpf é obrigatório.</label>');
      valido = false;
    }

    if (nome == "") {
      $('div#div_nome').after('<label id="erro_nome" class="error">O campo nome é obrigatório.</label>');
      valido = false;
    }
    return valido;
  }
});
//---------------------------------------------------------------------------------------------------------