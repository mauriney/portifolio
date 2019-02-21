/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE CONTATO
 */
$("#titulo1").html("Painel de Aniversariantes");


document.getElementById("aniv_todos").style.color = "white";
document.getElementById("aniv_todos").style.backgroundColor = "rgba(17,140,245,1)";

$('#aniv_outro').mouseover(function (event) {
    document.getElementById("aniv_todos").style.color = "black";
    document.getElementById("aniv_todos").style.backgroundColor = "white";
});

$('#aniv_todos').mouseover(function (event) {
    document.getElementById("aniv_todos").style.backgroundColor = "rgba(17,140,245,1)";
    document.getElementById("aniv_todos").style.color = "white";
});

function pesquisa(op) {
    url = PORTAL_URL + "controller/aniversariante/busca_nome.php?op=" + op;
    ajax(url);
}