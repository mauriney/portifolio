/*
 * SCRIPT DO FORMULÁRIO DO PAINEL DE AGENDA
 */
$("#titulo1").html("Agenda Completa");

$(document).ready(function () {

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

function vf_bairro(id) {
    var bairro = document.getElementById("mostrar-bairro");

    if (id == 16) {
        bairro.style.display = 'block';
    } else {
        bairro.style.display = 'none';
    }
}

function carregar() {

    var carregando = document.getElementById("carregando");

    carregando.style.display = "block";

    document.forms['form_agendacompleta'].submit();

}

//CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
$('div#div_success').slideDown(1000).delay(5000).slideUp(1000);