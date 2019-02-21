/*
 * SCRIPT DO FORMULÁRIO DO PAINEL DE VEÍCULOS
 */
$("#titulo1").html("Painel de Veículos");

function pesquisa() {

    var modelo = $("#modelo").val();
    var placa = $("#placa").val();
    var cor = $("#cor").val();

    url = PORTAL_URL + "controller/carro/busca_nome2.php?modelo=" + modelo + "&cor=" + cor + "&placa=" + placa;
    ajax(url);
}

$(document).ready(function () {

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