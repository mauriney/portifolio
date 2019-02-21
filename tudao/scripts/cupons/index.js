/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE INDEX
 ---------------------------------------------------------------------------------------------------------*/
//Ativar Cupom
function ativar(id) {
    swal({
        title: "Acesso de Cupom",
        text: "Deseja mesmo ativar este cupom no sistema?",
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: false
    },
    function() {
        $.post(PORTAL_URL + "dao/cupons/index.php", {id: id, op: 1}, function(data) {
            setTimeout("location.href='index.php'", 1);
        }
        , "html");
    });
}
//---------------------------------------------------------------------------------------------------------
//Desativar Cupom
function remover(id) {
    swal({
        title: "Acesso de Cupom",
        text: "Deseja mesmo cancelar este cupom no sistema?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: false
    },
    function() {
        $.post(PORTAL_URL + "dao/cupons/index.php", {id: id, op: 2}, function(data) {
            setTimeout("location.href='index.php'", 1);
        }
        , "html");
    });
}
//---------------------------------------------------------------------------------------------------------