/*---------------------------------------------------------------------------------------------------------
 DATA: 19/01/2017 ÀS 10:01
 NOME: JS DA CLASSE DE CARRINHO
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {

  $("[data-mask]").inputmask();

  $("input#valor_pagar").priceFormat({
    prefix: '',
    centsSeparator: ',',
    thousandsSeparator: '.'
  });

//---------------------------------------------------------------------------------------------------------
//FINALIZAR COMPRA
  $('form#form_finalizar').submit(function () {
    if (register_validator()) {
      if ($("input#session_id").val() != "") {

        swal({
          title: "Processo de Pagamento",
          text: "Deseja mesmo finalizar este pedido?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "Sim",
          cancelButtonText: "Não",
          closeOnConfirm: false
        },
        function () {

          $.ajax({
            type: "POST",
            url: PORTAL_URL + 'dao/cardapios/pagamento.php',
            data: $('#form_finalizar').serialize(),
            cache: false,
            success: function (obj) {
              obj = JSON.parse(obj);
              if (obj.msg == 'success') {
                swal({
                  title: "Processo de Pagamento",
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
                  title: "Processo de Pagamento",
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
                title: "Processo de Pagamento",
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
          
        });
        return false;
        
      } else {
        $('#myModal').modal('show');
        return false;
      }
    } else {
      return false;
    }
  });
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DO REGISTRO
  function register_validator() {
    var valido = true;
    var bairro = $("#bairro_id").val();
    var endereco = $("#endereco").val();
    var numero = $("#numero").val();
    var contato = $("#contato").val();
    var numero_mesa = $("#numero_mesa").val();
    var entrega = $('input[name="next"]:checked').val();
    var forma_pagamento = $("select#forma_pagamento").val();
    var valor_pagar = $("#valor_pagar").val();
    var valor_troco = $("#valor_troco").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    if (forma_pagamento == 3) {
      $("div#div_pontuacao").each(function () {
        if ($(this).html().indexOf("0 pontos") != -1 || $(this).html().indexOf("não aceita pontos") != -1) {
          $('table#tabela_lista_produtos').after('<label id="erro_tabela_lista_produtos" class="error">Não é possível finalizar a compra por pontos, pois um item adicionado na lista não é vendido por pontos, por favor remova o item para continuar.</label>');
          valido = false;
          element = $('table#tabela_lista_produtos');
        }
      });
    }

    if (forma_pagamento == "" || forma_pagamento == "0") {
      $('div#div_forma_pagamento').after('<label id="erro_forma_pagamento" class="error">O campo forma de pagamento é obrigatório.</label>');
      valido = false;
      element = $('div#div_forma_pagamento');
    } else {
      if (forma_pagamento == 1) {

        if (getMoney(valor_troco) < 0) {
          $('div#div_troco_receber').after('<label id="erro_troco_receber" class="error">Valor pago menor que o total.</label>');
          valido = false;
          element = $('div#div_troco_receber');
        }

        if (valor_pagar == "" || valor_pagar == "0,00") {
          $('div#div_valor_pagar').after('<label id="erro_valor_pagar" class="error">O campo valor pago é obrigatório.</label>');
          valido = false;
          element = $('div#div_valor_pagar');
        }
      }
    }

    if (entrega == "div_entrega") {
      if (contato == "") {
        $('div#div_contato').after('<label id="erro_contato" class="error">O campo telefone é obrigatório.</label>');
        valido = false;
        element = $('div#div_contato');
      }
      if (numero == "") {
        $('div#div_numero_casa').after('<label id="erro_numero_casa" class="error">obrigatório.</label>');
        valido = false;
        element = $('div#div_numero_casa');
      }
      if (endereco == "") {
        $('div#div_endereco').after('<label id="erro_endereco" class="error">O campo endereço é obrigatório.</label>');
        valido = false;
        element = $('div#div_endereco');
      }
      if (bairro == "") {
        $('div#div_bairro').after('<label id="erro_bairro" class="error">O campo bairro é obrigatório.</label>');
        valido = false;
        element = $('div#div_bairro');
      }
    } else {
      if (numero_mesa == "") {
        $('div#div_numero_mesa').after('<label id="erro_numero" class="error">O campo número da mesa é obrigatório.</label>');
        valido = false;
        element = $('div#div_numero_mesa');
      }
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
//MARCANDO O CHECKBOX MODO DE PAGAMENTO EM DINHEIRO
$('select#forma_pagamento').change(function () {
  if ($(this).val() == 3) {
    $('div#div_valor').hide();
    $('div#div_pontuacao').show();
    $('div#div_subtotal').hide();
    $('div#div_subpontos').show();
    $('table#total_dinheiro').hide();
    $('table#total_pontos').show();
  } else {
    $('div#div_valor').show();
    $('div#div_pontuacao').hide();
    $('div#div_subtotal').show();
    $('div#div_subpontos').hide();
    $('table#total_dinheiro').show();
    $('table#total_pontos').hide();
  }
});
//------------------------------------------------------------------------------
//ATUALIZANDO QTD NOVA AO ITEM
function update_qtd_item(item_id, obj) {
  var qtd = $(obj).parents('div#div_item_qtd').find('input#quantidade_item').val();
  swal({
    title: "Informações do Item",
    text: "Deseja mesmo alterar a quantidade do item?",
    type: "success",
    showCancelButton: true,
    confirmButtonColor: "#8CD4F5",
    confirmButtonText: "Sim",
    cancelButtonText: "Não",
    closeOnConfirm: false
  },
  function () {
    $.post(PORTAL_URL + "dao/cardapios/carrinho.php", {item_id: item_id, qtd: qtd, op: 1}, function (data) {
      setTimeout("location.href='" + PORTAL_URL + "view/cardapios/carrinho.php'", 1);
    }
    , "html");
  });
}

//------------------------------------------------------------------------------
$("input#entrega").click(function () {
  $("div#div_entrega").show();
  $("div#div_mesa").hide();
  $("select#bairro_id").val('');

  var subtotal1 = $("text#sub_total").html();
  var valor1 = 0;
  var desconto1 = $("text#desconto_cupom").html();

  var subtotal2 = $("text#subtotal_pontos").html();
  var valor2 = '0';
  var desconto2 = $("text#desconto_cupom_pontos").html();

  $("text#taxa_entrega_valor").html(formatReal(valor1));
  $("text#taxa_entrega_pontos").html(valor2);
  $("text#total_valor").html(formatReal("" + (parseInt(getMoney(valor1)) + parseInt(getMoney(subtotal1)) - parseInt(getMoney(desconto1))) + ""));
  $("text#total_pontos").html(parseInt(subtotal2) + parseInt(valor2) - parseInt(getMoney(desconto2)));
  $("input#vlr_total_dinheiro").val($("text#total_valor").html());
  $("input#vlr_total_pontos").val($("text#total_pontos").html());
});
//------------------------------------------------------------------------------
$("input#mesa").click(function () {
  $("div#div_entrega").hide();
  $("div#div_mesa").show();
  $("select#bairro_id").val('');

  var subtotal1 = $("text#sub_total").html();
  var valor1 = '0';
  var desconto1 = $("text#desconto_cupom").html();

  var subtotal2 = $("text#subtotal_pontos").html();
  var valor2 = 0;
  var desconto2 = $("text#desconto_cupom_pontos").html();

  $("text#taxa_entrega_valor").html(formatReal(valor1));
  $("text#taxa_entrega_pontos").html(valor2);
  $("text#total_valor").html(formatReal("" + (parseInt(getMoney(valor1)) + parseInt(getMoney(subtotal1)) - parseInt(getMoney(desconto1))) + ""));
  $("text#total_pontos").html(parseInt(subtotal2) + parseInt(valor2) - parseInt(getMoney(desconto2)));
  $("input#vlr_total_dinheiro").val($("text#total_valor").html());
  $("input#vlr_total_pontos").val($("text#total_pontos").html());

});
//------------------------------------------------------------------------------
$("select#bairro_id").change(function () {
  var subtotal1 = $("text#sub_total").html();
  var valor1 = $(this).find('option:selected').attr('rel');
  var desconto1 = $("text#desconto_cupom").html();

  var subtotal2 = $("text#subtotal_pontos").html();
  var valor2 = $(this).find('option:selected').attr('pontos');
  var desconto2 = $("text#desconto_cupom_pontos").html();

  $("text#taxa_entrega_valor").html(formatReal(valor1));
  $("text#taxa_entrega_pontos").html(valor2);
  $("text#total_valor").html(formatReal("" + (parseInt(getMoney(valor1)) + parseInt(getMoney(subtotal1)) - parseInt(getMoney(desconto1))) + ""));
  $("text#total_pontos").html(parseInt(subtotal2) + parseInt(valor2) - parseInt(getMoney(desconto2)));
  $("input#vlr_total_dinheiro").val($("text#total_valor").html());
  $("input#vlr_total_pontos").val($("text#total_pontos").html());

  valor_a_pagar();

});
//------------------------------------------------------------------------------
function formatReal(int)
{
  int = int.replace(".", "").replace(",", "");
  var tmp = int + '';
  tmp = tmp.replace(/([0-9]{2})$/g, ",$1");
  if (tmp.length > 6)
    tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");

  return tmp;
}
//------------------------------------------------------------------------------
function getMoney(val) {
  var money = val.replace('.', '').replace(',', '');
  return money;
}
//------------------------------------------------------------------------------
//INSERIR CUPOM DE DESCONTO
$("button#inserir_cupom").livequery('click', function () {
  var cupom = $("input#cupom").val();

  if (cupom == "") {
    swal({
      title: "Cupom de Desconto",
      text: "É necessário informar o cupom de desconto para continuar",
      type: "error",
      showCancelButton: false,
      confirmButtonColor: "#8CD4F5",
      confirmButtonText: "OK",
      closeOnConfirm: false
    });
  } else {
    $.ajax({
      type: "POST",
      url: PORTAL_URL + 'dao/cardapios/cupom.php',
      data: {cupom: cupom},
      cache: false,
      success: function (obj) {
        obj = JSON.parse(obj);
        if (obj.msg == 'success') {

          $("text#desconto_cupom").html(obj.valor);
          $("text#desconto_cupom_pontos").html(obj.pontos);

          var total_valor = $("text#total_valor").html();
          var total_pontos = $("text#total_pontos").html();

          $("text#total_valor").html(formatReal("" + (parseInt(getMoney(total_valor)) - parseInt(getMoney(obj.valor))) + ""));
          $("text#total_pontos").html(parseInt(total_pontos) - parseInt(obj.pontos));
          $("input#vlr_total_dinheiro").val($("text#total_valor").html());
          $("input#vlr_total_pontos").val($("text#total_pontos").html());

          $("button#inserir_cupom").hide();
          $("button#remover_cupom").show();

          return false;
        } else if (obj.msg == 'error') {
          swal({
            title: "Cupom de Desconto",
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
          title: "Cupom de Desconto",
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
  }

});
//------------------------------------------------------------------------------
//REMOVER CUPOM DE DESCONTO
$("button#remover_cupom").livequery('click', function () {
  var cupom = $("input#cupom").val();
  $.ajax({
    type: "POST",
    url: PORTAL_URL + 'dao/cardapios/limpar_cupom.php',
    data: {cupom: cupom},
    cache: false,
    success: function (obj) {

      obj = JSON.parse(obj);
      if (obj.msg == 'success') {

        var desconto_cupom = $("text#desconto_cupom").html();
        var desconto_cupom_pontos = $("text#desconto_cupom_pontos").html();

        var total_valor = $("text#total_valor").html();
        var total_pontos = $("text#total_pontos").html();

        $("text#total_valor").html(formatReal("" + (parseInt(getMoney(total_valor)) + parseInt(getMoney(desconto_cupom))) + ""));
        $("text#total_pontos").html(parseInt(total_pontos) + parseInt(desconto_cupom_pontos));

        $("input#vlr_total_dinheiro").val($("text#total_valor").html());
        $("input#vlr_total_pontos").val($("text#total_pontos").html());

        $("text#desconto_cupom").html('0,00');
        $("text#desconto_cupom_pontos").html('0');
        $("input#cupom").val('');
        $("button#remover_cupom").hide();
        $("button#inserir_cupom").show();

        return false;
      } else if (obj.msg == 'error') {
        swal({
          title: "Cupom de Desconto",
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
        title: "Cupom de Desconto",
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
});
//---------------------------------------------------------------------------------------------------------
//LOGIN NO SISTEMA
function entrar() {

  var email = $("#email").val();
  var senha = $("#senha").val();

  if (login_validator(email, senha)) {
    $.ajax({
      type: "POST",
      url: PORTAL_URL + 'dao/cliente_login.php',
      data: {email: email, senha: senha},
      cache: false,
      success: function (obj) {
        obj = JSON.parse(obj);
        if (obj.msg == 'success') {
          setTimeout("location.href='" + PORTAL_URL + "view/cardapios/carrinho.php'", 1);
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
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DO LOGIN
  function login_validator(email, senha) {
    var valido = true;

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS
    if (email == "") {
      $('div#div_email').after('<label id="erro_email" class="error">O campo de e-mail é obrigatório.</label>');
      valido = false;
    }
    if (senha == "") {
      $('div#div_senha').after('<label id="erro_senha" class="error">O campo senha é obrigatório.</label>');
      valido = false;
    }
    return valido;
  }
}
//---------------------------------------------------------------------------------------------------------
$("select#forma_pagamento").livequery('change', function () {

  $("input#valor_pagar").val("");
  $("input#valor_troco").val("0,00");

  if ($(this).val() == 1) {
    $("div#div_pagamento_dinheiro_1").show();
    $("div#div_pagamento_dinheiro_2").show();
  } else {
    $("div#div_pagamento_dinheiro_1").hide();
    $("div#div_pagamento_dinheiro_2").hide();
  }
});
//---------------------------------------------------------------------------------------------------------
function valor_a_pagar() {
  var total = $("text#total_valor").html();
  var valor = $("input#valor_pagar").val();
  var resultado = getMoney(valor) - getMoney(total);

  $("input#valor_troco").val(formatReal("" + resultado + ""));
}
//---------------------------------------------------------------------------------------------------------
