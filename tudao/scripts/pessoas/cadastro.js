/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE REGISTRAR
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function() {
//---------------------------------------------------------------------------------------------------------
//REGISTRAR NO SISTEMA
    $('form#form_permissao').submit(function() {
        if (register_validator()) {
            $.ajax({
                type: "POST",
                url: PORTAL_URL + 'dao/pessoas/cadastro.php',
                data: $('#form_permissao').serialize(),
                cache: false,
                success: function(obj) {
                    obj = JSON.parse(obj);
                    if (obj.msg == 'success') {
                        swal({
                            title: "Registro de Permissão",
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
                            title: "Registro de Permissão",
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
                        title: "Registro de Permissão",
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
        var permissoes = $("#permissoes").val();
        var senha = $("#senha").val();
        var confirmar = $("#confirmar").val();

        //LIMPA MENSAGENS DE ERRO
        $('label.error').each(function() {
            $(this).remove();
        });

        if (senha == "" && confirmar != "") {
            $('div#div_senha').after('<label id="erro_senha" class="error">O campo senha é obrigatório.</label>');
            valido = false;
        }

        if (senha != "" && confirmar == "") {
            $('div#div_confirmar').after('<label id="erro_confirmar" class="error">O campo confirmar senha é obrigatório.</label>');
            valido = false;
        }

        if (senha != "" && confirmar != "" && senha != confirmar) {
            $('div#div_senha').after('<label id="erro_senha" class="error">A senha e confirmação não coincidem.</label>');
            $('div#div_confirmar').after('<label id="erro_confirmar" class="error">A senha e confirmação não coincidem.</label>');
            valido = false;
        }

        //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS
        if (permissoes == "") {
            $('div#div_permissoes').after('<label id="erro_permissoes" class="error">O campo permissão é obrigatório.</label>');
            valido = false;
        }

        return valido;
    }
});
//---------------------------------------------------------------------------------------------------------