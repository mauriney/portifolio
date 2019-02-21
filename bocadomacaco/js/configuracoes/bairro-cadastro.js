/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE CONTATO
 */
var caminho = window.location.href.toString().split(window.location.host)[1];//Pegando a url completa

if (retornaUrl(caminho) == "") {
    $("#titulo1").html("Cadastro de Bairro");
} else {
    $("#titulo1").html("Alteração de Bairro");
}

$(document).ready(function () {

    //SALVANDO DADOS DO FORMULÁRIO DE DEMANDA
    $('#form_bairro').submit(function () {

        var form_valido = formulario_validator("");

        if (form_valido) {
            projetouniversal.util.getjson({
                url: PORTAL_URL + "controller/configuracoes/bairro/salvar_bairro.php",
                type: "POST",
                data: $('#form_bairro').serialize(),
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
            postToURL(PORTAL_URL + 'bairro-painel.php', {mensagem: obj.retorno});
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
    var regional = $("#regional").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label#erro_nome').remove();
    $('label#erro_regional').remove();

    if (obj.tipo == "" || obj.tipo == null) {//VALIDAÇÃO SEM BANCO DE DADOS
        //  //VERIFICANDO SE O CAMPO REGIONAL FOI INFORMADO
        if (regional == "") {
            $('div#div_regional').after('<label id="erro_regional" class="error">O campo regional é obrigatório.</label>');
            valido = false;
            element = $('div#div_regional');
        }
        //VERIFICANDO SE O CAMPO NOME FOI INFORMADO
        if (nome == "") {
            $('div#div_nome').after('<label id="erro_nome" class="error">O campo nome é obrigatório.</label>');
            valido = false;
            element = $('div#div_nome');
        }

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