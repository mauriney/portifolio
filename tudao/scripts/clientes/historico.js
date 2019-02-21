/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE INDEX
 ---------------------------------------------------------------------------------------------------------*/
//Ativar Pedido
function ativar(id) {
    swal({
        title: "Solicitação de Pedido",
        text: "Deseja mesmo ativar este pedido no sistema?",
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: false
    },
    function() {
        $.post(PORTAL_URL + "dao/clientes/historico.php", {id: id, op: 1}, function(data) {
            setTimeout("location.href='historico.php'", 1);
        }
        , "html");
    });
}
//---------------------------------------------------------------------------------------------------------
//Desativar Pedido
function remover(id) {
    swal({
        title: "Solicitação de Pedido",
        text: "Deseja mesmo cancelar este pedido no sistema?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: false
    },
    function() {
        $.post(PORTAL_URL + "dao/clientes/historico.php", {id: id, op: 2}, function(data) {
            setTimeout("location.href='historico.php'", 1);
        }
        , "html");
    });
}
//---------------------------------------------------------------------------------------------------------