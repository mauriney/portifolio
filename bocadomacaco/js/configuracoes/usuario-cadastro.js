/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE CONTATO
 */
var caminho = window.location.href.toString().split(window.location.host)[1];//Pegando a url completa

if (retornaUrl(caminho) == "") {
    $("#titulo1").html("Cadastro de Usuário");
} else {
    $("#titulo1").html("Alteração de Usuário");
}

$(document).ready(function () {

    //SALVANDO DADOS DO FORMULÁRIO DE DEMANDA
    $('#form_usuario').submit(function () {

        var form_valido = formulario_validator("");

        if (form_valido) {
            projetouniversal.util.getjson({
                url: PORTAL_URL + "controller/configuracoes/usuario/salvar_usuario.php",
                type: "POST",
                data: $('#form_usuario').serialize(),
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
            postToURL(PORTAL_URL + 'usuarios-painel.php', {mensagem: obj.retorno});
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
    var nome = $("#nome").val();
    var login = $("#login").val();
    var senha = $("#senha").val();
    var confirmarsenha = $("#confirmarsenha").val();
    var nivel = $("#nivel-acesso").val();
    var status = $("#status-usuario").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label#erro_nome').remove();
    $('label#erro_login').remove();
    $('label#erro_senha').remove();
    $('label#erro_confirmarsenha').remove();
    $('label#erro_nivel').remove();
    $('label#erro_status').remove();

    if (obj.tipo == "" || obj.tipo == null) {//VALIDAÇÃO SEM BANCO DE DADOS

        //VERIFICANDO SE O CAMPO SENHA ATUAL FOI INFORMADO
        if (confirmarsenha == "") {
            $('div#div_confirmarsenha').after('<label id="erro_confirmarsenha" class="error">O campo confirmar senha é obrigatório.</label>');
            valido = false;
            element = $('div#div_confirmarsenha');
        }
        //VERIFICANDO SE O CAMPO SENHA FOI INFORMADO
        if (senha == "") {
            $('div#div_senha').after('<label id="erro_senha" class="error">O campo senha é obrigatório.</label>');
            valido = false;
            element = $('div#div_senha');
        }
        //VERIFICANDO SE O CAMPO LOGIN FOI INFORMADO
        if (login == "") {
            $('div#div_login').after('<label id="erro_login" class="error">O campo login é obrigatório.</label>');
            valido = false;
            element = $('div#div_login');
        }

        //VERIFICANDO SE O CAMPO SENHA E CONFIRMAÇÃO DE SENHA SÃO IGUAIS
        if (senha != "" && confirmarsenha != "" && senha != confirmarsenha) {
            $('div#div_senha').after('<label id="erro_senha" class="error">A senha e confirmação de senha não coincidem.</label>');
            valido = false;
            element = $('div#div_senha');
        }
        //VERIFICANDO SE O CAMPO SENHA E CONFIRMAÇÃO DE SENHA SÃO IGUAIS
        if (senha != "" && confirmarsenha != "" && senha != confirmarsenha) {
            $('div#div_confirmarsenha').after('<label id="erro_confirmarsenha" class="error">A senha e confirmação de senha não coincidem.</label>');
            valido = false;
            element = $('div#div_confirmarsenha');
        }

        //VERIFICANDO SE O CAMPO STATUS FOI INFORMADO
        if (status == "") {
            $('div#div_status').after('<label id="erro_status" class="error">O campo status é obrigatório.</label>');
            valido = false;
            element = $('div#div_status');
        }

        //VERIFICANDO SE O CAMPO NÍVEL FOI INFORMADO
        if (nivel == "") {
            $('div#div_nivel').after('<label id="erro_nivel" class="error">O campo nível é obrigatório.</label>');
            valido = false;
            element = $('div#div_nivel');
        }

        //VERIFICANDO SE O CAMPO NOME FOI INFORMADO
        if (nome == "") {
            $('div#div_nome').after('<label id="erro_nome" class="error">O campo nome é obrigatório.</label>');
            valido = false;
            element = $('div#div_nome');
        }

    } else if (obj.tipo == "login") {
        $('div#div_login').after('<label id="erro_login" class="error">' + obj.retorno + '</label>');
        valido = false;
        element = $('div#div_login');
    } else if (obj.tipo == "nome") {
        $('div#div_nome').after('<label id="erro_nome" class="error">' + obj.retorno + '</label>');
        valido = false;
        element = $('div#div_nome');
    }

    if (element != null) {
        var topPosition = element.offset().top - 135;
        $('html, body').animate({
            scrollTop: topPosition
        }, 800);
    }
    return valido;
}

//FUNÇÃO PARA PEGAR VALOR PASSADO PELA URL
function retornaUrl(url) {
    var urlTratada = "";
    // dar um split para gerar um array para poder tratar a url e poder tirar por exemplo os primeiro dois parâmetros
    var arrUrl = url.split('id');
    // deleta o indice um do array que é um parâmetro vazio
    delete arrUrl[0];

    // dar um join para refazer a string agora sem os dois primeiros valores
    var joinUrl = arrUrl.join('/');

    urlTratada = joinUrl.substring(2);

    // retornar a url passando por substring 2 para tirar as primeiras duas barras
    return urlTratada;
}