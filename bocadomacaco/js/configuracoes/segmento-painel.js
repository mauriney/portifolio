/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE CONTATO
 */
$("#titulo1").html("Painel de Segmentos");

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

function pesquisa() {

    var buscar = $("#nome").val();

    url = PORTAL_URL + "controller/configuracoes/segmento/busca_nome.php?buscar=" + buscar + "";
    ajax(url);
}

//CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
$('div#div_success').slideDown(1000).delay(5000).slideUp(1000);