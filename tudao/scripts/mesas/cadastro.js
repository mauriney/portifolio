/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE REGISTRAR
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function() {
//---------------------------------------------------------------------------------------------------------
//REGISTRAR NO SISTEMA
    $('form#form_mesa').submit(function() {
        if (register_validator()) {
            $.ajax({
                type: "POST",
                url: PORTAL_URL + 'dao/mesas/cadastro.php',
                data: $('#form_mesa').serialize(),
                cache: false,
                success: function(obj) {
                    obj = JSON.parse(obj);
                    if (obj.msg == 'success') {
                        swal({
                            title: "Registro de Mesa",
                            text: obj.retorno,
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#8CD4F5",
                            confirmButtonText: "OK",
                            closeOnConfirm: false
                        }, function() {
                            setTimeout("location.href='index.php'", 1);
                        });
                        return false;
                    } else if (obj.msg == 'error') {
                        swal({
                            title: "Registro de Mesa",
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
                        title: "Registro de Mesa",
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
        } else {
            return false;
        }
    });
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DO REGISTRO
    function register_validator() {
        var valido = true;
        var numero = $("#numero").val();

        //LIMPA MENSAGENS DE ERRO
        $('label.error').each(function() {
            $(this).remove();
        });

        if (numero == "") {
            $('div#div_numero').after('<label id="erro_numero" class="error">O campo número é obrigatório.</label>');
            valido = false;
        }
        
        return valido;
    }
});
//---------------------------------------------------------------------------------------------------------