/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE INDEX
 ---------------------------------------------------------------------------------------------------------*/
//Desativar Pedido
function remover(id) {
    swal({
        title: "Atendimento de Pedido",
        text: "Deseja mesmo cancelar este pedido no sistema?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: false
    },
    function() {
        $.post(PORTAL_URL + "dao/pedidos/index.php", {id: id, op: 2}, function(data) {
            setTimeout("location.href='pagamentos.php'", 1);
        }
        , "html");
    });
}
//---------------------------------------------------------------------------------------------------------