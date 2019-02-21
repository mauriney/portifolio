/*---------------------------------------------------------------------------------------------------------
 DATA: 26/07/2016 ÀS 15:07
 NOME: JS DA CLASSE DE RECUPERAR SENHA
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
//---------------------------------------------------------------------------------------------------------
//RECUPERAR E ALTERAR SENHA
  $('form#form_recuperar').submit(function () {

    $("#div_loader").show();

    if (recuperar_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/recuperar.php',
        data: $('#form_recuperar').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {

            swal({
              title: "Alteração de Senha",
              text: "Senha alterada com sucesso!",
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='login'", 1);
            });

            return false;

          } else if (obj.msg == 'error') {
            $('div#div_senha').after('<label id="erro_senha" class="error">' + obj.retorno + '</label>');
            $("#div_loader").hide();
          }
        },
        error: function (obj) {
          swal({
            title: "Alteração de Senha",
            text: obj.retorn,
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
          });
          $("#div_loader").hide();
        }
      });
      return false;
    } else {
      $("#div_loader").hide();
      return false;
    }
  });
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DA RECUPERAÇÃO DE SENHA
  function recuperar_validator() {
    var valido = true;
    var senha = $("#senha").val();
    var conf_senha = $("#conf_senha").val();

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS
    if (conf_senha == "") {
      $('div#div_conf_senha').after('<label id="erro_conf_senha" class="error">O campo confirmação de senha é obrigatório.</label>');
      valido = false;
    }

    if (senha == "") {
      $('div#div_senha').after('<label id="erro_senha" class="error">O campo senha é obrigatório.</label>');
      valido = false;
    }

    if (valido && senha != conf_senha) {
      $('div#div_conf_senha').after('<label id="erro_conf_senha" class="error">A senha e confirmação de senha não coincidem.</label>');
      $('div#div_senha').after('<label id="erro_senha" class="error">A senha e confirmação de senha não coincidem.</label>');
      valido = false;
    }

    if (valido && senha.length < 6) {
      $('div#div_conf_senha').after('<label id="erro_conf_senha" class="error">A senha informada é inválida, por favor digite uma senha com no mínimo 6 digitos.</label>');
      $('div#div_senha').after('<label id="erro_senha" class="error">A senha informada é inválida, por favor digite uma senha com no mínimo 6 digitos.</label>');
      valido = false;
    }

    if (valido && vf_senha_letra(senha)) {
      $('div#div_conf_senha').after('<label id="erro_conf_senha" class="error">A senha informada é inválida, é necessário no mínimo 1 letra.</label>');
      $('div#div_senha').after('<label id="erro_senha" class="error">A senha informada é inválida, é necessário no mínimo 1 letra.</label>');
      valido = false;
    }

    if (valido && vf_senha_numero(senha)) {
      $('div#div_conf_senha').after('<label id="erro_conf_senha" class="error">A senha informada é inválida, é necessário no mínimo 1 número.</label>');
      $('div#div_senha').after('<label id="erro_senha" class="error">A senha informada é inválida, é necessário no mínimo 1 número.</label>');
      valido = false;
    }

    if (valido && vf_senha_caractere(senha)) {
      $('div#div_conf_senha').after('<label id="erro_conf_senha" class="error">A senha informada é inválida, é necessário no mínimo 1 caractere especial.</label>');
      $('div#div_senha').after('<label id="erro_senha" class="error">A senha informada é inválida, é necessário no mínimo 1 caractere especial.</label>');
      valido = false;
    }

    return valido;
  }
  //---------------------------------------------------------------------------------------------------------
  //VEFICAR SE SENHA TEM LETRA
  function vf_senha_letra(senha) {

    var vf = false;

    var regex = /^(?=(?:.*?[a-zA-Z]){1})(?!.*\s)[0-9a-zA-Z!@#$%;*(){}_+^&]*$/;

    if (!regex.exec(senha)) {
      vf = true;
    }

    return vf;

  }
  //---------------------------------------------------------------------------------------------------------
  //VEFICAR SE SENHA TEM NÚMERO
  function vf_senha_numero(senha) {

    var vf = false;

    var regex = /^(?=(?:.*?[0-9]){1})(?!.*\s)[0-9a-zA-Z!@#$%;*(){}_+^&]*$/;

    if (!regex.exec(senha)) {
      vf = true;
    }

    return vf;

  }
  //---------------------------------------------------------------------------------------------------------
  //VEFICAR SE SENHA TEM CARACTERE ESPECIAL
  function vf_senha_caractere(senha) {

    var vf = false;

    var regex = /^(?=(?:.*?[!@#$%*()_+^&}{:;?.]){1})(?!.*\s)[0-9a-zA-Z!@#$%;*(){}_+^&]*$/;

    if (!regex.exec(senha)) {
      vf = true;
    }

    return vf;

  }
//---------------------------------------------------------------------------------------------------------
//VOLTAR A TELA DE LOGIN
  $("#tela_login").click(function () {
    $("#l-login").hide();
    setTimeout("location.href='login.php'", 1);
  });
//---------------------------------------------------------------------------------------------------------
});