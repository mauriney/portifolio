/*---------------------------------------------------------------------------------------------------------
 DATA: 28/07/2016 ÀS 11:39
 NOME: JS DA CLASSE DE CADASTRO DE USUÁRIO
 ---------------------------------------------------------------------------------------------------------*/
//COMBO ESTADO E MUNICÍPIO
$(document).ready(function () {

  //Jogando a busca para a lista de usuário
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/usuario/lista");

//---------------------------------------------------------------------------------------------------------
//ATUALIZAÇÃO DE SENHA
  $('form#form_acesso').submit(function () {

    $("#div_loader").show();

    if (usuario_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/usuario/senha',
        data: $('#form_acesso').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {

            swal({
              title: "Formulário de Acesso",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "dashboard'", 1);
            });

            return false;

          } else if (obj.msg == 'error') {
            swal({
              title: "Formulário de Acesso",
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
            title: "Formulário de Acesso",
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
//VALIDAÇÃO DA USUÁRIO
  function usuario_validator() {
    var valido = true;
    var senha_atual = sha1($("#senha_atual").val());
    var senha_antiga = $("#senha_antiga").val();
    var senha_nova = $("#senha_nova").val();
    var senha_confirma = $("#senha_confirma").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS
    if (senha_atual != senha_antiga) {
      $('div#div_senha_atual').after('<label id="erro_senha_atual" class="error">A senha atual informada está incorreta.</label>');
      valido = false;
      element = $('div#div_senha_atual');
    }

    if (senha_confirma == "") {
      $('div#div_senha_confirma').after('<label id="erro_senha_confirma" class="error">O campo confirmação de senha é obrigatório.</label>');
      valido = false;
      element = $('div#div_senha_confirma');
    }

    if (senha_nova == "") {
      $('div#div_senha_nova').after('<label id="erro_senha_nova" class="error">O campo nova senha é obrigatório.</label>');
      valido = false;
      element = $('div#div_senha_nova');
    }

    if (senha_nova != "" && senha_confirma != "") {
      if (senha_nova != senha_confirma) {
        $('div#div_senha_nova').after('<label id="erro_senha_nova" class="error">A senha e confirmação de senha não coincidem.</label>');
        $('div#div_senha_confirma').after('<label id="erro_senha_confirma" class="error">A senha e confirmação de senha não coincidem.</label>');
        valido = false;
        element = $('div#div_senha_nova');
      }
    }

    if (valido && senha_nova.length < 6) {
      $('div#div_senha_confirma').after('<label id="erro_senha_confirma" class="error">A senha informada é inválida, por favor digite uma senha com no mínimo 6 digitos.</label>');
      $('div#div_senha_nova').after('<label id="erro_senha_nova" class="error">A senha informada é inválida, por favor digite uma senha com no mínimo 6 digitos.</label>');
      valido = false;
    }

    if (valido && vf_senha_letra(senha_nova)) {
      $('div#div_senha_confirma').after('<label id="erro_senha_confirma" class="error">A senha informada é inválida, é necessário no mínimo 1 letra.</label>');
      $('div#div_senha_nova').after('<label id="erro_senha_nova" class="error">A senha informada é inválida, é necessário no mínimo 1 letra.</label>');
      valido = false;
    }

    if (valido && vf_senha_numero(senha_nova)) {
      $('div#div_senha_confirma').after('<label id="erro_senha_confirma" class="error">A senha informada é inválida, é necessário no mínimo 1 número.</label>');
      $('div#div_senha_nova').after('<label id="erro_senha_nova" class="error">A senha informada é inválida, é necessário no mínimo 1 número.</label>');
      valido = false;
    }

    if (valido && vf_senha_caractere(senha_nova)) {
      $('div#div_senha_confirma').after('<label id="erro_senha_confirma" class="error">A senha informada é inválida, é necessário no mínimo 1 caractere especial.</label>');
      $('div#div_senha_nova').after('<label id="erro_senha_nova" class="error">A senha informada é inválida, é necessário no mínimo 1 caractere especial.</label>');
      valido = false;
    }

    if (element != null) {
      var topPosition = element.offset().top - 135;
      $('html, body').animate({
        scrollTop: topPosition
      }, 800);
    }

    return valido;
  }
});
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
function sha1(str) {
  //  discuss at: http://locutus.io/php/sha1/
  // original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // improved by: Michael White (http://getsprink.com)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  //    input by: Brett Zamir (http://brett-zamir.me)
  //      note 1: Keep in mind that in accordance with PHP, the whole string is buffered and then
  //      note 1: hashed. If available, we'd recommend using Node's native crypto modules directly
  //      note 1: in a steaming fashion for faster and more efficient hashing
  //   example 1: sha1('Kevin van Zonneveld')
  //   returns 1: '54916d2e62f65b3afa6e192e6a601cdbe5cb5897'

  var hash
  try {
    var crypto = require('crypto')
    var sha1sum = crypto.createHash('sha1')
    sha1sum.update(str)
    hash = sha1sum.digest('hex')
  } catch (e) {
    hash = undefined
  }

  if (hash !== undefined) {
    return hash
  }

  var _rotLeft = function (n, s) {
    var t4 = (n << s) | (n >>> (32 - s))
    return t4
  }

  var _cvtHex = function (val) {
    var str = ''
    var i
    var v

    for (i = 7; i >= 0; i--) {
      v = (val >>> (i * 4)) & 0x0f
      str += v.toString(16)
    }
    return str
  }

  var blockstart
  var i, j
  var W = new Array(80)
  var H0 = 0x67452301
  var H1 = 0xEFCDAB89
  var H2 = 0x98BADCFE
  var H3 = 0x10325476
  var H4 = 0xC3D2E1F0
  var A, B, C, D, E
  var temp

  // utf8_encode
  str = unescape(encodeURIComponent(str))
  var strLen = str.length

  var wordArray = []
  for (i = 0; i < strLen - 3; i += 4) {
    j = str.charCodeAt(i) << 24 |
            str.charCodeAt(i + 1) << 16 |
            str.charCodeAt(i + 2) << 8 |
            str.charCodeAt(i + 3)
    wordArray.push(j)
  }

  switch (strLen % 4) {
    case 0:
      i = 0x080000000
      break
    case 1:
      i = str.charCodeAt(strLen - 1) << 24 | 0x0800000
      break
    case 2:
      i = str.charCodeAt(strLen - 2) << 24 | str.charCodeAt(strLen - 1) << 16 | 0x08000
      break
    case 3:
      i = str.charCodeAt(strLen - 3) << 24 |
              str.charCodeAt(strLen - 2) << 16 |
              str.charCodeAt(strLen - 1) <<
              8 | 0x80
      break
  }

  wordArray.push(i)

  while ((wordArray.length % 16) !== 14) {
    wordArray.push(0)
  }

  wordArray.push(strLen >>> 29)
  wordArray.push((strLen << 3) & 0x0ffffffff)

  for (blockstart = 0; blockstart < wordArray.length; blockstart += 16) {
    for (i = 0; i < 16; i++) {
      W[i] = wordArray[blockstart + i]
    }
    for (i = 16; i <= 79; i++) {
      W[i] = _rotLeft(W[i - 3] ^ W[i - 8] ^ W[i - 14] ^ W[i - 16], 1)
    }

    A = H0
    B = H1
    C = H2
    D = H3
    E = H4

    for (i = 0; i <= 19; i++) {
      temp = (_rotLeft(A, 5) + ((B & C) | (~B & D)) + E + W[i] + 0x5A827999) & 0x0ffffffff
      E = D
      D = C
      C = _rotLeft(B, 30)
      B = A
      A = temp
    }

    for (i = 20; i <= 39; i++) {
      temp = (_rotLeft(A, 5) + (B ^ C ^ D) + E + W[i] + 0x6ED9EBA1) & 0x0ffffffff
      E = D
      D = C
      C = _rotLeft(B, 30)
      B = A
      A = temp
    }

    for (i = 40; i <= 59; i++) {
      temp = (_rotLeft(A, 5) + ((B & C) | (B & D) | (C & D)) + E + W[i] + 0x8F1BBCDC) & 0x0ffffffff
      E = D
      D = C
      C = _rotLeft(B, 30)
      B = A
      A = temp
    }

    for (i = 60; i <= 79; i++) {
      temp = (_rotLeft(A, 5) + (B ^ C ^ D) + E + W[i] + 0xCA62C1D6) & 0x0ffffffff
      E = D
      D = C
      C = _rotLeft(B, 30)
      B = A
      A = temp
    }

    H0 = (H0 + A) & 0x0ffffffff
    H1 = (H1 + B) & 0x0ffffffff
    H2 = (H2 + C) & 0x0ffffffff
    H3 = (H3 + D) & 0x0ffffffff
    H4 = (H4 + E) & 0x0ffffffff
  }

  temp = _cvtHex(H0) + _cvtHex(H1) + _cvtHex(H2) + _cvtHex(H3) + _cvtHex(H4)
  return temp.toLowerCase()
}
//---------------------------------------------------------------------------------------------------------