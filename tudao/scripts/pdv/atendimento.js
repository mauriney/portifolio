/*---------------------------------------------------------------------------------------------------------
 DATA: 23/06/2017 ÀS 09:42
 NOME: JS DA CLASSE DE ATENDIMENTO
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {

    var qtd_active = 0;

    $('ul#ul_menu').find('li.active').each(function () {
        qtd_active++;
    });

    if (qtd_active > 1) {
        $('li#sanduiche').removeClass('active');
        $('div#tab1').removeClass('mTabActive');
    }

    var menu_selecionado = $("input#menu_selecionado").val();

    if (menu_selecionado != "") {
        $('div#tab' + menu_selecionado).removeClass("defaultState");
        $('div#tab' + menu_selecionado).addClass("mTabActive");
    }

    $("[data-mask]").inputmask();

    setInterval(relogio, 1000);
    $("input#valor_pagar").priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
    $("a#a_menu").click(function () {

        $("ul#ul_menu").show();
        $("div#div_menu").hide();
        $("li#sanduiche").removeClass("active");
        $("li#bebidas").removeClass("active");
        $("li#sucos").removeClass("active");
        $("li#doces").removeClass("active");
        $("li#sanduiche").addClass("active");
        $("div#tab1").removeClass("defaultState");
        $("div#tab1").addClass("mTabActive");
        $("div#tab5").removeClass("mTabActive");
        $("div#tab2").removeClass("mTabActive");
        $("div#tab3").removeClass("mTabActive");
        $("div#tab4").removeClass("mTabActive");
        $("div#tab6").removeClass("mTabActive");
        $("div#tab7").removeClass("mTabActive");
        $("div#tab8").removeClass("mTabActive");
        $("div#tab9").removeClass("mTabActive");
        $("div#tab10").removeClass("mTabActive");
        $("div#tab11").removeClass("mTabActive");
        $("div#tab12").removeClass("mTabActive");
        $("div#tab13").removeClass("mTabActive");
        $("div#tab14").removeClass("mTabActive");
        $("div#tab15").removeClass("mTabActive");
        $("div#tab5").removeClass("defaultState");
        $("div#tab2").removeClass("defaultState");
        $("div#tab3").removeClass("defaultState");
        $("div#tab4").removeClass("defaultState");
        $("div#tab6").removeClass("defaultState");
        $("div#tab7").removeClass("defaultState");
        $("div#tab8").removeClass("defaultState");
        $("div#tab9").removeClass("defaultState");
        $("div#tab10").removeClass("defaultState");
        $("div#tab11").removeClass("defaultState");
        $("div#tab12").removeClass("defaultState");
        $("div#tab13").removeClass("defaultState");
        $("div#tab14").removeClass("defaultState");
        $("div#tab15").removeClass("defaultState");
        $("div#tab5").addClass("defaultState");
        $("div#tab2").addClass("defaultState");
        $("div#tab3").addClass("defaultState");
        $("div#tab4").addClass("defaultState");
        $("div#tab6").addClass("defaultState");
        $("div#tab7").addClass("defaultState");
        $("div#tab8").addClass("defaultState");
        $("div#tab9").addClass("defaultState");
        $("div#tab10").addClass("defaultState");
        $("div#tab11").addClass("defaultState");
        $("div#tab12").addClass("defaultState");
        $("div#tab13").addClass("defaultState");
        $("div#tab14").addClass("defaultState");
        $("div#tab15").addClass("defaultState");
    });
    $("a#nome_cliente").click(function () {

        $("ul#ul_menu").hide();
        $("div#div_menu").show();
        $("li#sanduiche").removeClass("active");
        $("li#bebidas").removeClass("active");
        $("li#sucos").removeClass("active");
        $("li#doces").removeClass("active");
        $("div#tab5").removeClass("defaultState");
        $("div#tab5").addClass("mTabActive");
        $("div#tab1").removeClass("mTabActive");
        $("div#tab2").removeClass("mTabActive");
        $("div#tab3").removeClass("mTabActive");
        $("div#tab4").removeClass("mTabActive");
        $("div#tab6").removeClass("mTabActive");
        $("div#tab7").removeClass("mTabActive");
        $("div#tab8").removeClass("mTabActive");
        $("div#tab9").removeClass("mTabActive");
        $("div#tab10").removeClass("mTabActive");
        $("div#tab11").removeClass("mTabActive");
        $("div#tab12").removeClass("mTabActive");
        $("div#tab13").removeClass("mTabActive");
        $("div#tab14").removeClass("mTabActive");
        $("div#tab15").removeClass("mTabActive");
        $("div#tab1").removeClass("defaultState");
        $("div#tab2").removeClass("defaultState");
        $("div#tab3").removeClass("defaultState");
        $("div#tab4").removeClass("defaultState");
        $("div#tab6").removeClass("defaultState");
        $("div#tab7").removeClass("defaultState");
        $("div#tab8").removeClass("defaultState");
        $("div#tab9").removeClass("defaultState");
        $("div#tab10").removeClass("defaultState");
        $("div#tab11").removeClass("defaultState");
        $("div#tab12").removeClass("defaultState");
        $("div#tab13").removeClass("defaultState");
        $("div#tab14").removeClass("defaultState");
        $("div#tab15").removeClass("defaultState");
        $("div#tab1").addClass("defaultState");
        $("div#tab2").addClass("defaultState");
        $("div#tab3").addClass("defaultState");
        $("div#tab4").addClass("defaultState");
        $("div#tab6").addClass("defaultState");
        $("div#tab7").addClass("defaultState");
        $("div#tab8").addClass("defaultState");
        $("div#tab9").addClass("defaultState");
        $("div#tab10").addClass("defaultState");
        $("div#tab11").addClass("defaultState");
        $("div#tab12").addClass("defaultState");
        $("div#tab13").addClass("defaultState");
        $("div#tab14").addClass("defaultState");
        $("div#tab15").addClass("defaultState");
    });

    $("a#nome_mesa").click(function () {

        $("div#div_entrega_endereco").hide();
        $("ul#ul_menu").hide();
        $("div#div_menu").show();
        $("li#sanduiche").removeClass("active");
        $("li#bebidas").removeClass("active");
        $("li#sucos").removeClass("active");
        $("li#doces").removeClass("active");
        $("div#tab6").removeClass("defaultState");
        $("div#tab6").addClass("mTabActive");
        $("div#tab1").removeClass("mTabActive");
        $("div#tab2").removeClass("mTabActive");
        $("div#tab3").removeClass("mTabActive");
        $("div#tab4").removeClass("mTabActive");
        $("div#tab5").removeClass("mTabActive");
        $("div#tab7").removeClass("mTabActive");
        $("div#tab8").removeClass("mTabActive");
        $("div#tab9").removeClass("mTabActive");
        $("div#tab10").removeClass("mTabActive");
        $("div#tab11").removeClass("mTabActive");
        $("div#tab12").removeClass("mTabActive");
        $("div#tab13").removeClass("mTabActive");
        $("div#tab14").removeClass("mTabActive");
        $("div#tab15").removeClass("mTabActive");
        $("div#tab1").removeClass("defaultState");
        $("div#tab2").removeClass("defaultState");
        $("div#tab3").removeClass("defaultState");
        $("div#tab4").removeClass("defaultState");
        $("div#tab5").removeClass("defaultState");
        $("div#tab7").removeClass("defaultState");
        $("div#tab8").removeClass("defaultState");
        $("div#tab9").removeClass("defaultState");
        $("div#tab10").removeClass("defaultState");
        $("div#tab11").removeClass("defaultState");
        $("div#tab12").removeClass("defaultState");
        $("div#tab13").removeClass("defaultState");
        $("div#tab14").removeClass("defaultState");
        $("div#tab15").removeClass("defaultState");
        $("div#tab1").addClass("defaultState");
        $("div#tab2").addClass("defaultState");
        $("div#tab3").addClass("defaultState");
        $("div#tab4").addClass("defaultState");
        $("div#tab5").addClass("defaultState");
        $("div#tab7").addClass("defaultState");
        $("div#tab8").addClass("defaultState");
        $("div#tab9").addClass("defaultState");
        $("div#tab10").addClass("defaultState");
        $("div#tab11").addClass("defaultState");
        $("div#tab12").addClass("defaultState");
        $("div#tab13").addClass("defaultState");
        $("div#tab14").addClass("defaultState");
        $("div#tab15").addClass("defaultState");
    });
    $("a#listar_pedidos").click(function () {

        $("ul#ul_menu").hide();
        $("div#div_menu").show();
        $("li#sanduiche").removeClass("active");
        $("li#bebidas").removeClass("active");
        $("li#sucos").removeClass("active");
        $("li#doces").removeClass("active");
        $("div#tab14").removeClass("defaultState");
        $("div#tab14").addClass("mTabActive");
        $("div#tab1").removeClass("mTabActive");
        $("div#tab2").removeClass("mTabActive");
        $("div#tab3").removeClass("mTabActive");
        $("div#tab4").removeClass("mTabActive");
        $("div#tab5").removeClass("mTabActive");
        $("div#tab6").removeClass("mTabActive");
        $("div#tab7").removeClass("mTabActive");
        $("div#tab8").removeClass("mTabActive");
        $("div#tab9").removeClass("mTabActive");
        $("div#tab10").removeClass("mTabActive");
        $("div#tab11").removeClass("mTabActive");
        $("div#tab12").removeClass("mTabActive");
        $("div#tab13").removeClass("mTabActive");
        $("div#tab15").removeClass("mTabActive");
        $("div#tab1").removeClass("defaultState");
        $("div#tab2").removeClass("defaultState");
        $("div#tab3").removeClass("defaultState");
        $("div#tab4").removeClass("defaultState");
        $("div#tab5").removeClass("defaultState");
        $("div#tab6").removeClass("defaultState");
        $("div#tab7").removeClass("defaultState");
        $("div#tab8").removeClass("defaultState");
        $("div#tab9").removeClass("defaultState");
        $("div#tab10").removeClass("defaultState");
        $("div#tab11").removeClass("defaultState");
        $("div#tab12").removeClass("defaultState");
        $("div#tab13").removeClass("defaultState");
        $("div#tab15").removeClass("defaultState");
        $("div#tab1").addClass("defaultState");
        $("div#tab2").addClass("defaultState");
        $("div#tab3").addClass("defaultState");
        $("div#tab4").addClass("defaultState");
        $("div#tab5").addClass("defaultState");
        $("div#tab6").addClass("defaultState");
        $("div#tab7").addClass("defaultState");
        $("div#tab8").addClass("defaultState");
        $("div#tab9").addClass("defaultState");
        $("div#tab10").addClass("defaultState");
        $("div#tab11").addClass("defaultState");
        $("div#tab12").addClass("defaultState");
        $("div#tab13").addClass("defaultState");
        $("div#tab15").addClass("defaultState");
    });
    $("a#a_receber").click(function () {

        $("ul#ul_menu").hide();
        $("div#div_menu").show();
        $("li#sanduiche").removeClass("active");
        $("li#bebidas").removeClass("active");
        $("li#sucos").removeClass("active");
        $("li#doces").removeClass("active");
        $("div#tab15").removeClass("defaultState");
        $("div#tab15").addClass("mTabActive");
        $("div#tab1").removeClass("mTabActive");
        $("div#tab2").removeClass("mTabActive");
        $("div#tab3").removeClass("mTabActive");
        $("div#tab4").removeClass("mTabActive");
        $("div#tab5").removeClass("mTabActive");
        $("div#tab6").removeClass("mTabActive");
        $("div#tab7").removeClass("mTabActive");
        $("div#tab8").removeClass("mTabActive");
        $("div#tab9").removeClass("mTabActive");
        $("div#tab10").removeClass("mTabActive");
        $("div#tab11").removeClass("mTabActive");
        $("div#tab12").removeClass("mTabActive");
        $("div#tab13").removeClass("mTabActive");
        $("div#tab14").removeClass("mTabActive");
        $("div#tab1").removeClass("defaultState");
        $("div#tab2").removeClass("defaultState");
        $("div#tab3").removeClass("defaultState");
        $("div#tab4").removeClass("defaultState");
        $("div#tab5").removeClass("defaultState");
        $("div#tab6").removeClass("defaultState");
        $("div#tab7").removeClass("defaultState");
        $("div#tab8").removeClass("defaultState");
        $("div#tab9").removeClass("defaultState");
        $("div#tab10").removeClass("defaultState");
        $("div#tab11").removeClass("defaultState");
        $("div#tab12").removeClass("defaultState");
        $("div#tab13").removeClass("defaultState");
        $("div#tab14").removeClass("defaultState");
        $("div#tab1").addClass("defaultState");
        $("div#tab2").addClass("defaultState");
        $("div#tab3").addClass("defaultState");
        $("div#tab4").addClass("defaultState");
        $("div#tab5").addClass("defaultState");
        $("div#tab6").addClass("defaultState");
        $("div#tab7").addClass("defaultState");
        $("div#tab8").addClass("defaultState");
        $("div#tab9").addClass("defaultState");
        $("div#tab10").addClass("defaultState");
        $("div#tab11").addClass("defaultState");
        $("div#tab12").addClass("defaultState");
        $("div#tab13").addClass("defaultState");
        $("div#tab14").addClass("defaultState");
    });

    $('div#click_produto').click(function () {

        var obj = this;

        $('div.active').each(function () {
            $(this).removeClass('active');
        });

        $(obj).addClass('active');


    });

});
//------------------------------------------------------------------------------
function menu(cod) {
    $('input#menu_selecionado').val(cod);
}

//------------------------------------------------------------------------------
function escolha_cliente(id, nome, contato1, contato2) {

    $.post(PORTAL_URL + "dao/pdv/atendimento.php", {cliente_id: id, op: 9}, function (data) {

        $("a#remover_cliente").show();
        $("a#nome_cliente").html(nome + "<br/>" + (contato1 != "" && contato1 != null ? contato1 : contato2));
        $("input#cliente_id").val(id);
        $("li#sanduiche").addClass("active");
        $("div#tab5").addClass("defaultState");
        $("div#tab5").removeClass("mTabActive");
        $("div#tab1").removeClass("defaultState");
        $("div#tab1").addClass("mTabActive");

    }
    , "html");

}
//------------------------------------------------------------------------------
function escolha_mesa(id, numero) {

    $.post(PORTAL_URL + "dao/pdv/atendimento.php", {mesa_id: id, op: 10}, function (data) {

        $("a#remover_mesa").show();
        $("a#nome_mesa").html(numero);
        $("input#mesa_id").val(id);
        $("li#sanduiche").addClass("active");
        $("div#tab6").addClass("defaultState");
        $("div#tab6").removeClass("mTabActive");
        $("div#tab1").removeClass("defaultState");
        $("div#tab1").addClass("mTabActive");

    }
    , "html");
}

//------------------------------------------------------------------------------
//REMOVENDO ITEM DO ATENDIMENTO
function remover_item_atendimento(item_id) {
    swal({
        title: "Remoção de Produto",
        text: "Deseja mesmo remover este produto da lista?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: false
    },
    function () {
        $.post(PORTAL_URL + "dao/cardapios/carrinho.php", {item_id: item_id, op: 2, qtd: 0}, function (data) {
            setTimeout("location.href='" + PORTAL_URL + "view/pdv/atendimento.php'", 1);
        }
        , "html");
    });
}
//------------------------------------------------------------------------------
//REMOVENDO ADICIONAL DE UM PRODUTO
function remover_adicional_atendimento(pedidos_itens_ingredientes_id) {
    swal({
        title: "Remoção de Adicional",
        text: "Deseja mesmo remover este adicional do produto?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: false
    },
    function () {
        $.post(PORTAL_URL + "dao/pdv/atendimento.php", {pedidos_itens_ingredientes_id: pedidos_itens_ingredientes_id, op: 1}, function (data) {
            setTimeout("location.href='" + PORTAL_URL + "view/pdv/atendimento.php'", 1);
        }
        , "html");
    });
}
//------------------------------------------------------------------------------
function atualizar_qtd(obj, pedidos_item_id) {

    var qtd = $(obj).parents("div.col-xs-2").find("input#quantidade_item").val();
    $.post(PORTAL_URL + "dao/pdv/atendimento.php", {pedidos_item_id: pedidos_item_id, qtd: qtd, op: 2}, function (data) {
        setTimeout("location.href='" + PORTAL_URL + "view/pdv/atendimento.php'", 1);
    }
    , "html");
}
//------------------------------------------------------------------------------
function add_produto(produto_id) {

    var menu_selecionado = $("input#menu_selecionado").val();

    $.post(PORTAL_URL + "dao/pdv/atendimento.php", {produto_id: produto_id, menu_selecionado: menu_selecionado, op: 3}, function (data) {
        setTimeout("location.href='" + PORTAL_URL + "view/pdv/atendimento.php'", 1);
    }
    , "html");
}
//------------------------------------------------------------------------------
function add_ingrediente(ingrediente_id) {

    var pedidos_itens_id = $("input#produto_selecionado").val();
    var menu_selecionado = $("input#menu_selecionado").val();

    if (pedidos_itens_id == 0) {
        swal({
            title: "Informação",
            text: "É necessário selecionar um produto para adicionar ingrediente.",
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
        });
        return false;
    } else {
        $.post(PORTAL_URL + "dao/pdv/atendimento.php", {ingrediente_id: ingrediente_id, pedidos_itens_id: pedidos_itens_id, menu_selecionado: menu_selecionado, op: 4}, function (data) {
            setTimeout("location.href='" + PORTAL_URL + "view/pdv/atendimento.php'", 1);
        }
        , "html");
    }
}
//------------------------------------------------------------------------------
function cancelar_pedido() {
    var pedido_id = $("input#pedido_id").val();
    if (pedido_id == undefined) {
        swal({
            title: "Remoção de Pedido",
            text: "Nenhum produto no carrinho para ser removido.",
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
        });
        return false;
    } else {
        swal({
            title: "Remoção de Pedido",
            text: "Deseja mesmo cancelar este pedido?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "Sim",
            cancelButtonText: "Não",
            closeOnConfirm: false
        },
        function () {
            $.post(PORTAL_URL + "dao/pdv/atendimento.php", {pedido_id: pedido_id, op: 5}, function (data) {
                setTimeout("location.href='" + PORTAL_URL + "view/pdv/atendimento.php'", 1);
            }
            , "html");
        });
    }
}
//------------------------------------------------------------------------------
function pedido_espera() {

    var pedido_id = $("input#pedido_id").val();
    var mesa = $("input#mesa_id").val();
    var cliente_id = $("input#cliente_id").val();
    if (pedido_id == undefined) {
        swal({
            title: "Pedido em Espera",
            text: "Nenhum produto no carrinho para ser enviado para a lista de espera.",
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
        });
        return false;
    } else {
        swal({
            title: "Pedido em Espera",
            text: "Deseja mesmo enviar o pedido para a lista de espera?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "Sim",
            cancelButtonText: "Não",
            closeOnConfirm: false
        },
        function () {
            $.post(PORTAL_URL + "dao/pdv/atendimento.php", {pedido_id: pedido_id, mesa: mesa, cliente_id: cliente_id, op: 6}, function (data) {
                setTimeout("location.href='" + PORTAL_URL + "view/pdv/atendimento.php'", 1);
            }
            , "html");
        });
    }
}
//------------------------------------------------------------------------------
function colocar_em_atendimento(pedido_id) {
    if ($("input#pedido_id").val() == undefined) {
        swal({
            title: "Pedido em Espera",
            text: "Deseja mesmo confirmar o pedido?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "Sim",
            cancelButtonText: "Não",
            closeOnConfirm: false
        },
        function () {
            $.post(PORTAL_URL + "dao/pdv/atendimento.php", {pedido_id: pedido_id, op: 7}, function (data) {
                setTimeout("location.href='" + PORTAL_URL + "view/pdv/atendimento.php'", 1);
            }
            , "html");
        });
    } else {
        swal({
            title: "Pedido em Espera",
            text: "É necessáro atender o último pedido confirmado para continuar.",
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
        });
        return false;
    }
}
//------------------------------------------------------------------------------
function produto_selecionado(produto_selecionado_id) {
    $("input#produto_selecionado").val(produto_selecionado_id);
}
//------------------------------------------------------------------------------
function relogio() {
    var data = new Date();
    var horas = data.getHours();
    var minutos = data.getMinutes();
    var segundos = data.getSeconds();
    var exibe = document.getElementById("text_hora");
    exibe.innerHTML = (horas < 10 ? "0" + horas : horas) + ":" + (minutos < 10 ? "0" + minutos : minutos) + ":" + (segundos < 10 ? "0" + segundos : segundos);
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
//---------------------------------------------------------------------------------------------------------
function valor_a_pagar() {
    var valor_bairro = $("input#mesa_id").val() == "" ? $("select#bairro_id").find('option:selected').attr('rel') : 0;
    var total = $("input#total_a_receber").val();
    var valor = $("input#valor_pagar").val();
    var resultado = getMoney(valor) - getMoney(total);
    $("input#valor_troco").val(formatReal("" + resultado + ""));
}
//------------------------------------------------------------------------------
function getMoney(val) {
    var money = val.replace('.', '').replace(',', '');
    return money;
}
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
function finaliza_compra() {

    var pedido_id = $("#pedido_id").val();
    var numero_mesa = $("#mesa_id").val();
    var valor_pagamento = $("#valor_pagar").val();
    var valor_troco = $("#valor_troco").val();
    var forma_pagamento = $("select#forma_pagamento").val();
    var cliente = $("#cliente_id").val();
    var vlr_total_dinheiro = $("strong#vlr_receber").html();

    var endereco = $("#endereco").val();
    var bairro_id = $("#bairro_id").val();
    var numero = $("#numero").val();
    var complemento = $("#complemento").val();
    var contato = $("#contato").val();

    if (pedido_id == undefined) {
        swal({
            title: "Remoção de Pedido",
            text: "Nenhum produto no carrinho para ser efetuado a baixa no sistema.",
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
        });
        return false;
    } else {
        if (register_validator()) {
            $.ajax({
                type: "POST",
                url: PORTAL_URL + 'dao/pdv/atendimento.php',
                data: {endereco: endereco, bairro_id: bairro_id, numero: numero, complemento: complemento, contato: contato, pedido_id: pedido_id, numero_mesa: numero_mesa,
                    valor_pagamento: valor_pagamento, valor_troco: valor_troco, forma_pagamento: forma_pagamento,
                    cliente: cliente, vlr_total_dinheiro: vlr_total_dinheiro, op: 8},
                cache: false,
                success: function (obj) {
                    obj = JSON.parse(obj);
                    if (obj.msg == 'success') {
                        swal({
                            title: "Atendimento de Pedido",
                            text: "Deseja mesmo enviar este pedido para o atendimento?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#8CD4F5",
                            confirmButtonText: "Sim",
                            cancelButtonText: "Não",
                            closeOnConfirm: false
                        },
                        function () {
                            swal({
                                title: "Atendimento de Pedido",
                                text: "Pedido enviado com sucesso!",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonColor: "#8CD4F5",
                                confirmButtonText: "OK",
                                closeOnConfirm: false
                            }, function () {
                                setTimeout("location.href='" + PORTAL_URL + "view/pdv/atendimento.php'", 1);
                            });
                            return false;
                        });
                        return false;

                    } else if (obj.msg == 'error') {
                        swal({
                            title: "Atendimento de Pedido",
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
                        title: "Atendimento de Pedido",
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
        return false;
    }
}
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DO REGISTRO
function register_validator() {
    var valido = true;
    var numero_mesa = $("#numero_mesa").val();
    var forma_pagamento = $("select#forma_pagamento").val();
    var valor_pagar = $("#valor_pagar").val();
    var valor_troco = $("#valor_troco").val();
    var mesa_id = $("#mesa_id").val();

    var bairro = $("#bairro_id").val();
    var endereco = $("#endereco").val();
    var numero = $("#numero").val();
    var contato = $("#contato").val();

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
        $('div#div_forma_pagamento').after('<label id="erro_forma_pagamento" class="error">Forma de pagamento é obrigatório.</label>');
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
                $('div#div_valor_pagar').after('<label id="erro_valor_pagar" class="error">Valor pago é obrigatório.</label>');
                valido = false;
                element = $('div#div_valor_pagar');
            }
        }
    }

    if (numero_mesa == "") {
        $('div#div_numero_mesa').after('<label id="erro_numero" class="error">Número da mesa é obrigatório.</label>');
        valido = false;
        element = $('div#div_numero_mesa');
    }

    if (mesa_id == "") {
        if (contato == "") {
            $('div#div_contato').after('<label id="erro_contato" class="error">Contato é obrigatório.</label>');
            valido = false;
            element = $('div#div_contato');
        }

        if (numero == "") {
            $('div#div_numero_casa').after('<label id="erro_numero_casa" class="error">Número é obrigatório.</label>');
            valido = false;
            element = $('div#div_numero_casa');
        }

        if (endereco == "") {
            $('div#div_endereco').after('<label id="erro_endereco" class="error">Endereço é obrigatório.</label>');
            valido = false;
            element = $('div#div_endereco');
        }

        if (bairro == "") {
            $('div#div_bairro').after('<label id="erro_bairro" class="error">Bairro é obrigatório.</label>');
            valido = false;
            element = $('div#div_bairro');
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
//---------------------------------------------------------------------------------------------------------
//REMOVER CLIENTE
function remover_cliente() {

    swal({
        title: "Atendimento",
        text: "Deseja mesmo remover o cliente informado?",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: true
    },
    function () {

        $.post(PORTAL_URL + "dao/pdv/atendimento.php", {op: 11}, function (data) {

            $("a#nome_cliente").html('<i class="fa fa-plus"></i><br>ADICIONAR CLIENTE');
            $("input#cliente_id").val('');
            $("a#remover_cliente").hide();

        }
        , "html");

    });
    return false;

}
//---------------------------------------------------------------------------------------------------------
//REMOVER MESA
function remover_mesa() {

    swal({
        title: "Atendimento",
        text: "Deseja mesmo remover a mesa/balcão informado?",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: true
    },
    function () {

        $.post(PORTAL_URL + "dao/pdv/atendimento.php", {op: 12}, function (data) {

            $("a#nome_mesa").html('<i class="fa fa-cutlery"></i><br>SEM MESA');
            $("input#mesa_id").val('');
            $("a#remover_mesa").hide();
            $("div#div_entrega_endereco").show();

        }
        , "html");

    });
    return false;

}
//------------------------------------------------------------------------------
$("select#bairro_id").change(function () {

    var valor_bairro = $(this).find('option:selected').attr('rel');
    var receber = $("strong#vlr_receber").html();

    $("input#valor_pagar").val('');
    $("input#valor_troco").val('0,00');
    $("input#total_a_receber").val(formatReal("" + (parseInt(getMoney(valor_bairro)) + parseInt(getMoney(receber))) + ""));

});
//------------------------------------------------------------------------------
