/*
 * SCRIPT DO FORMULÁRIO DO PAINEL DE VEÍCULOS
 */
$("#titulo1").html("Painel de Saídas");

function pesquisa() {

    var motorista = $("#motorista").val();
    var veiculo = $("#veiculo").val();
    var inicio = $("#inicio").val();
    var fim = $("#fim").val();

    url = PORTAL_URL + "controller/carro/busca_nome.php?inicio=" + inicio + "&fim=" + fim + "&veiculo=" + veiculo + "&motorista=" + motorista;
    ajax(url);
}


function confirmar_chegada(motorista, modelo, placa, codigo) {
    var temp = {
        state0: {
            html: '<p>Confirmação de Chegada</p><p>' + motorista + ' - ' + modelo + ' - ' + placa + '</p>',
            buttons: {SIM: 1, NÃO: 0},
            focus: 2,
            submit: function (e, v, m, f) {
                if (v == 0)
                    $.prompt.close();
                else if (v == 1) {
                    projetouniversal.util.getjson({
                        url: PORTAL_URL + "controller/carro/salvar_chegada.php",
                        type: "POST",
                        data: {codigo: codigo},
                        enctype: 'multipart/form-data',
                        success: onSuccessSend,
                        error: onError
                    });
                    return false;
                }
                return false;
            }
        }
    }
    $.prompt(temp);
}

function onSuccessSend(obj) {
    if (obj.msg == 'success') {
        postToURL(PORTAL_URL + 'carro-painel.php', {mensagem: obj.retorno});
    }
    return false;
}

function onError(args) {
    $.prompt('onError: ' + args.retorno);
}

$(document).ready(function () {

    $('#inicio').kendoDatePicker({
        // display month and year in the input
        format: "dd/MM/yyyy"
    });
    var datepicker = $("#inicio").data("kendoDatePicker");

    $('#inicio').click(function () {
        datepicker.open();
    });

    $('#fim').kendoDatePicker({
        // display month and year in the input
        format: "dd/MM/yyyy"
    });

    var datepicker2 = $("#fim").data("kendoDatePicker");

    $('#fim').click(function () {
        datepicker2.open();
    });

    $('#mostrar-busca').click(function (event) {
        event.stopPropagation();
        if (document.getElementById("filtro").style.display == 'none') {
            $('div#filtro').slideDown(1000);
        } else {
            $('div#filtro').slideUp(1000);
        }
    });
});

//CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
$('div#div_success').slideDown(1000).delay(5000).slideUp(1000);