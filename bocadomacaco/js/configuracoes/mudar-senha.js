/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE CONTATO
 */
$("#titulo1").html("Alteração de Senha");

$(document).ready(function () {

    //SALVANDO DADOS DO FORMULÁRIO DE DEMANDA
    $('#mudar_senha').submit(function () {

        var form_valido = formulario_validator("");

        if (form_valido) {
            projetouniversal.util.getjson({
                url: PORTAL_URL + "controller/configuracoes/alterar_senha.php",
                type: "POST",
                data: $('#mudar_senha').serialize(),
                enctype: 'multipart/form-data',
                success: onSuccessSend,
                error: onError
            });
            return false;
        } else {
            return false;
        }
    });

    function onSuccessSend(obj) {
        if (obj.msg == 'success') {
            postToURL(PORTAL_URL + 'logout.php', {mensagem: obj.retorno});
        } else if (obj.msg == 'error') {
            formulario_validator(obj);
        }
        return false;
    }
    /* ERRO AO ENVIAR AJAX */
    function onError(args) {
        $.prompt('onError: ' + args.retorno);
    }
});

//VALIDATOR DO LOGIN
function formulario_validator(obj) {
    var valido = true;
    var senha_atual = $("#senha-atual").val();
    var nova_senha = $("#nova-senha").val();
    var confirmar_senha = $("#confirmar-senha").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label#erro_senha_atual').remove();
    $('label#erro_nova_senha').remove();
    $('label#erro_confirmar_senha').remove();

    if (obj.tipo == "" || obj.tipo == null) {//VALIDAÇÃO SEM BANCO DE DADOS
        //VERIFICANDO SE O CAMPO SENHA ATUAL FOI INFORMADO
        if (confirmar_senha == "") {
            $('div#div_confirmar_senha').after('<label id="erro_confirmar_senha" class="error">O campo confirmar senha é obrigatório.</label>');
            valido = false;
            element = $('div#div_confirmar_senha');
        }
        //VERIFICANDO SE O CAMPO SENHA ATUAL FOI INFORMADO
        if (nova_senha == "") {
            $('div#div_nova_senha').after('<label id="erro_nova_senha" class="error">O campo nova senha é obrigatório.</label>');
            valido = false;
            element = $('div#div_nova_senha');
        }
        //VERIFICANDO SE O CAMPO SENHA ATUAL FOI INFORMADO
        if (senha_atual == "") {
            $('div#div_senha_atual').after('<label id="erro_senha_atual" class="error">O campo senha atual é obrigatório.</label>');
            valido = false;
            element = $('div#div_senha_atual');
        }

        //VERIFICANDO SE O CAMPO SENHA ATUAL FOI INFORMADO
        if (nova_senha != "" && confirmar_senha != "" && nova_senha != confirmar_senha) {
            $('div#div_nova_senha').after('<label id="erro_nova_senha" class="error">Nova senha e confirmação de senha não coincidem.</label>');
            valido = false;
            element = $('div#div_nova_senha');
        }
        //VERIFICANDO SE O CAMPO SENHA ATUAL FOI INFORMADO
        if (nova_senha != "" && confirmar_senha != "" && nova_senha != confirmar_senha) {
            $('div#div_confirmar_senha').after('<label id="erro_confirmar_senha" class="error">Nova senha e confirmação de senha não coincidem.</label>');
            valido = false;
            element = $('div#div_confirmar_senha');
        }
    } else if (obj.tipo == "senha-atual") {
        $('div#div_senha_atual').after('<label id="erro_senha_atual" class="error">' + obj.retorno + '</label>');
        valido = false;
        element = $('div#div_senha_atual');
    }

    if (element != null) {
        var topPosition = element.offset().top - 135;
        $('html, body').animate({
            scrollTop: topPosition
        }, 800);
    }
    return valido;
}