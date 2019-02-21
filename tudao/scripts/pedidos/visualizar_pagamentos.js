/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE REGISTRAR
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function() {
//---------------------------------------------------------------------------------------------------------
//REGISTRAR NO SISTEMA
    $('form#form_pagamento').submit(function() {
        swal({
            title: "Confirmação de Entrega",
            text: "Deseja mesmo confirmar a entrega deste pedido?",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "Sim",
            cancelButtonText: "Não",
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                type: "POST",
                url: PORTAL_URL + 'dao/pedidos/visualizar_pagamentos.php',
                data: $('#form_pagamento').serialize(),
                cache: false,
                success: function(obj) {
                    obj = JSON.parse(obj);
                    if (obj.msg == 'success') {
                        swal({
                            title: "Confirmação de Entrega",
                            text: obj.retorno,
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#8CD4F5",
                            confirmButtonText: "OK",
                            closeOnConfirm: false
                        }, function() {
                            setTimeout("location.href='" + PORTAL_URL + "view/pedidos/pagamentos.php'", 1);
                        });
                        return false;
                    } else if (obj.msg == 'error') {
                        swal({
                            title: "Confirmação de Entrega",
                            text: obj.retorno,
                            type: "error",
                            showCancelButton: false,
                            confirmButtonColor: "#8CD4F5",
                            confirmButtonText: "OK",
                            closeOnConfirm: false
                        });
                        return false;
                    }
                },
                error: function(obj) {
                    swal({
                        title: "Confirmação de Entrega",
                        text: obj.retorno,
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#8CD4F5",
                        confirmButtonText: "OK",
                        closeOnConfirm: false
                    });
                    return false;
                }
            });
            return false;
        });
        return false;
    });
});
//---------------------------------------------------------------------------------------------------------