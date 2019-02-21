/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE RECUPERAÇÃO
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
//---------------------------------------------------------------------------------------------------------
//RECUPERAÇÃO DE SENHA NO SISTEMA
  $('form#form_recuperar_senha').submit(function () {
    if (alteracao_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'dao/recuperar_senha.php',
        data: $('#form_recuperar_senha').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {
            swal({
              title: "Alteração de Senha",
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
              title: "Alteração de Senha",
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
            title: "Alteração de Senha",
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
//VALIDAÇÃO DE ALTERAÇÃO DE SENHA
  function alteracao_validator() {
    var valido = true;
    var senha = $("#senha").val();
    var confirmar = $("#confirmar").val();

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS FORAM DEVIDAMENTE INFORMADOS
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

    return valido;
  }
});
//---------------------------------------------------------------------------------------------------------