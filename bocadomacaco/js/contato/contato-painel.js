/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE CONTATO
 */
$("#titulo1").html("Painel de Contatos");

$(document).ready(function () {
    $('#mostrar-busca').click(function (event) {
        event.stopPropagation();
        if (document.getElementById("filtro").style.display == 'none') {
            $('div#filtro').slideDown(1000);
        } else {
            $('div#filtro').slideUp(1000);
        }
    });

    //COMBO ESTADO E MUNICIPIO
    $("#estado").change(function () {
        $("#municipio").html('<option value="0">Carregando...</option>');
        $.post(PORTAL_URL + "controller/basico/combo/cidades.php",
                {estado: $(this).val()},
        function (valor) {
            $("#municipio").html(valor);
            $("#municipio").select2();
        });
    });
});

function pesquisa(op) {

    var buscar = $("#nome").val();
    var grupos = $("#grupos").val();
    var referencia = $("#referencia").val();
    var estado = $("#estado").val();
    var municipio = $("#municipio").val();
    var bairro = $("#bairro").val();

    if (grupos == null) {
        grupos = "";
    }
    if (referencia == null) {
        referencia = "";
    }
    if (estado == null) {
        estado = "";
    }
    if (municipio == null || municipio == 0) {
        municipio = "";
    }

    if (bairro == null) {
        bairro = "";
    }

    url = PORTAL_URL + "controller/contato/busca_nome.php?buscar=" + buscar + "&grupos=" + grupos + "&estado=" + estado + "&municipio=" + municipio + "&bairro=" + bairro + "&referencia=" + referencia + "&op=" + op;
    ajax(url);
}

function pesquisa2(letra) {
    url = PORTAL_URL + "controller/contato/busca_nome2.php?letra=" + letra;
    ajax(url);
}

//CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
$('div#div_success').slideDown(1000).delay(5000).slideUp(1000);