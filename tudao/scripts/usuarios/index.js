/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE INDEX
 ---------------------------------------------------------------------------------------------------------*/
//Ativar Usuário
function ativar(id) {
    swal({
        title: "Acesso ao Sistema",
        text: "Deseja mesmo ativar este usuário no sistema?",
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: false
    },
    function() {
        $.post(PORTAL_URL + "dao/usuarios/index.php", {id: id, op: 1}, function(data) {
            setTimeout("location.href='index.php'", 1);
        }
        , "html");
    });
}
//---------------------------------------------------------------------------------------------------------
//Desativar Usuário
function remover(id) {
    swal({
        title: "Acesso ao Sistema",
        text: "Deseja mesmo cancelar este usuário no sistema?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: false
    },
    function() {
        $.post(PORTAL_URL + "dao/usuarios/index.php", {id: id, op: 2}, function(data) {
            setTimeout("location.href='index.php'", 1);
        }
        , "html");
    });
}
//---------------------------------------------------------------------------------------------------------
//Confirmar Validação de Acesso do Usuário
function pendencia(id) {
    swal({
        title: "Validação de Acesso",
        text: "Deseja validar esse usuário no sistema?",
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: false
    },
    function() {
        $.post(PORTAL_URL + "dao/usuarios/index.php", {id: id, op: 1}, function(data) {
            setTimeout("location.href='index.php'", 1);
        }
        , "html");
    });
}
//---------------------------------------------------------------------------------------------------------