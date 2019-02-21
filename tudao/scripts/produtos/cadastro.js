/*---------------------------------------------------------------------------------------------------------
 DATA: 20/12/2016 ÀS 09:42
 NOME: JS DA CLASSE DE REGISTRAR
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function() {
//---------------------------------------------------------------------------------------------------------
//REGISTRAR NO SISTEMA
    $('form#form_produto').submit(function() {
        if (register_validator()) {
            $.ajax({
                type: "POST",
                url: PORTAL_URL + 'dao/produtos/cadastro.php',
                data: $('#form_produto').serialize(),
                cache: false,
                success: function(obj) {
                    obj = JSON.parse(obj);
                    if (obj.msg == 'success') {
                        swal({
                            title: "Registro de Produto",
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
                            title: "Registro de Produto",
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
                        title: "Registro de Produto",
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
        var valor = $("#valor").val();
        var categoria = $("#categoria").val();
        var pontuacao = $("input[id='pontuacao_sim']:checked").val();
        var pontuacao_cobrada = $("#valor_pontuacao").val();
        var pontuacao_recebida = $("#valor_pontuacao_recebida").val();

        //LIMPA MENSAGENS DE ERRO
        $('label.error').each(function() {
            $(this).remove();
        });

        if (valor == "" || valor == "0,00") {
            $('div#div_valor').after('<label id="erro_valor" class="error">O campo valor é obrigatório.</label>');
            valido = false;
        }
        
        if (pontuacao == 1 && pontuacao_recebida == "" || pontuacao == 1 && pontuacao_recebida == 0) {
            $('div#div_pontuacao_recebida').after('<label id="erro_pontuacao_recebida" class="error">O campo pontuação para receber é obrigatório.</label>');
            valido = false;
        }

        if (pontuacao == 1 && pontuacao_cobrada == "" || pontuacao == 1 && pontuacao_cobrada == 0) {
            $('div#div_pontuacao_cobrada').after('<label id="erro_pontuacao_cobrada" class="error" style="margin-bottom: -10px">O campo pontuação para compra é obrigatório.</label>');
            valido = false;
        }

        if (categoria == "" || categoria == 0) {
            $('div#div_categoria').after('<label id="erro_categoria" class="error">O campo categoria é obrigatório.</label>');
            valido = false;
        }

        if (nome == "") {
            $('div#div_nome').after('<label id="erro_nome" class="error">O campo nome é obrigatório.</label>');
            valido = false;
        }
        return valido;
    }
//---------------------------------------------------------------------------------------------------------
//CARREGANDO RADIO DE PONTUAÇÃO
    $("#pontuacao_sim").click(function() {
        $("div#div_valor_pontuacao").show();
    });

    $("#pontuacao_nao").click(function() {
        $("div#div_valor_pontuacao").hide();
    });
});
//---------------------------------------------------------------------------------------------------------