/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE CONTATO
 */
$("#titulo1").html("Relatório de Quantidade de Agendas");

$(document).ready(function() {

    $('#inicio').kendoDatePicker({
        // display month and year in the input
        format: "dd/MM/yyyy"
    });
    var datepicker = $("#inicio").data("kendoDatePicker");

    $('#inicio').click(function() {
        datepicker.open();
    });

    $('#fim').kendoDatePicker({
        // display month and year in the input
        format: "dd/MM/yyyy"
    });

    var datepicker2 = $("#fim").data("kendoDatePicker");

    $('#fim').click(function() {
        datepicker2.open();
    });
});