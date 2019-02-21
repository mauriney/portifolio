/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE INDEX
 ---------------------------------------------------------------------------------------------------------*/
//Ativar Pedido
function ativar(id) {
    swal({
        title: "Atendimento de Pedido",
        text: "Deseja mesmo ativar este pedido no sistema?",
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: false
    },
    function() {
        $.post(PORTAL_URL + "dao/pedidos/index.php", {id: id, op: 1}, function(data) {
            setTimeout("location.href='cancelados.php'", 1);
        }
        , "html");
    });
}
//---------------------------------------------------------------------------------------------------------