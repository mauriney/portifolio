/*
 * SCRIPT DO FORMULÁRIO DO PAINEL DE DEMANDA
 */
$("#titulo1").html("Painel de Demandas");

function pesquisa() {

    var demanda = $("#nome").val();
    var status = $("#status").val();
    var situacao = $("#situacao").val();
    var responsavel = $("#responsavel").val();
    var inicio = $("#inicio").val();
    var fim = $("#fim").val();

    url = PORTAL_URL + "controller/demanda/busca_nome.php?inicio=" + inicio + "&fim=" + fim + "&demanda=" + demanda + "&situacao=" + situacao + "&status=" + status + "&responsavel=" + responsavel;
    ajax(url);
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