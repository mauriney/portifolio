/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE REGISTRAR
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
//---------------------------------------------------------------------------------------------------------
//REGISTRAR NO SISTEMA
  $('form#form_usuario').submit(function () {
    if (register_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'dao/usuarios/cadastro.php',
        data: $('#form_usuario').serialize(),
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
              setTimeout("location.href='index.php'", 1);
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
    var contato = $("#contato").val();

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS

    if (email == "") {
      $('div#div_email').after('<label id="erro_email" class="error">O campo de e-mail é obrigatório.</label>');
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