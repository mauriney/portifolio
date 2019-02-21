/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE REGISTRAR
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function() {

    $("input#valor_frete").priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });

//---------------------------------------------------------------------------------------------------------
//REGISTRAR NO SISTEMA
    $('form#form_bairro').submit(function() {
        if (register_validator()) {
            $.ajax({
                type: "POST",
                url: PORTAL_URL + 'dao/bairros/cadastro.php',
                data: $('#form_bairro').serialize(),
                cache: false,
                success: function(obj) {
                    obj = JSON.parse(obj);
                    if (obj.msg == 'success') {
                        swal({
                            title: "Registro de Bairro",
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
                            title: "Registro de Bairro",
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
                        title: "Registro de Bairro",
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
        var nome = $("input#nome").val();
        var valor = $("input#valor_frete").val();
        var pontos = $("input#pontos_frete").val();

        //LIMPA MENSAGENS DE ERRO
        $('label.error').each(function() {
            $(this).remove();
        });

        if (pontos == "") {
            $('div#div_pontos_frete').after('<label id="erro_pontos_frete" class="error">O campo frete em pontos é obrigatório.</label>');
            valido = false;
        }

        if (valor == "") {
            $('div#div_valor_frete').after('<label id="erro_valor_frete" class="error">O campo frete em valor é obrigatório.</label>');
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