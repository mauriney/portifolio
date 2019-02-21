/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE CONTATO
 */
$("#titulo1").html("Agendas do Dia");

$('#quem_vai').change(function () {
    var codigo = $(this).val();
    url = PORTAL_URL + "controller/agenda/busca_nome.php?id=" + codigo + "";
    ajax(url);
});