/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE REGISTRAR
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function() {
//---------------------------------------------------------------------------------------------------------
//REGISTRAR NO SISTEMA
    $('form#form_cliente').submit(function() {
        if (register_validator()) {
            $.ajax({
                type: "POST",
                url: PORTAL_URL + 'dao/clientes/cadastro.php',
                data: $('#form_cliente').serialize(),
                cache: false,
                success: function(obj) {
                    obj = JSON.parse(obj);
                    if (obj.msg == 'success') {
                        swal({
                            title: "Registro de Cliente",
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
                            title: "Registro de Cliente",
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
                        title: "Registro de Cliente",
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
        var nome = $("#nome").val();
        var cpf = $("#cpf").val();
        var email = $("#email").val();
        var contato = $("#contato").val();
        var contato2 = $("#contato2").val();
        var cep = $("#cep").val();
        var bairro = $("#bairro").val();
        var endereco = $("#endereco").val();
        var cidade = $("#cidade").val();

        //LIMPA MENSAGENS DE ERRO
        $('label.error').each(function() {
            $(this).remove();
        });

        //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS

        if (cidade == "" || cidade == "0") {
            $('div#div_cidade').after('<label id="erro_cidade" class="error">O campo cidade é obrigatório.</label>');
            valido = false;
        }

        if (endereco == "") {
            $('div#div_endereco').after('<label id="erro_endereco" class="error">O campo endereço é obrigatório.</label>');
            valido = false;
        }

        if (bairro == "") {
            $('div#div_bairro').after('<label id="erro_bairro" class="error">O campo bairro é obrigatório.</label>');
            valido = false;
        }

        if (cep == "") {
            $('div#div_cep').after('<label id="erro_cep" class="error">O campo CEP é obrigatório.</label>');
            valido = false;
        }

        if (email == "") {
            $('div#div_email').after('<label id="erro_email" class="error">O campo de e-mail é obrigatório.</label>');
            valido = false;
        }

        if (contato2 == "" && contato == "") {
            $('div#div_contato2').after('<label id="erro_contato2" class="error">O campo telefone celular é obrigatório.</label>');
            valido = false;
        }

        if (contato == "" && contato2 == "") {
            $('div#div_contato').after('<label id="erro_contato" class="error">O campo telefone residêncial é obrigatório.</label>');
            valido = false;
        }

        if (cpf == "") {
            $('div#div_cpf').after('<label id="erro_cpf" class="error">O campo cpf é obrigatório.</label>');
            valido = false;
        }

        if (nome == "") {
            $('div#div_nome').after('<label id="erro_nome" class="error">O campo nome é obrigatório.</label>');
            valido = false;
        }
        return valido;
    }
});
//---------------------------------------------------------------------------------------------------------