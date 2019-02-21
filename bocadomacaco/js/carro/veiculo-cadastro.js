/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE DEMANDA
 */
var caminho = window.location.href.toString().split(window.location.host)[1];//Pegando a url completa

if (retornaUrl(caminho) == "") {
    $("#titulo1").html("Cadastro de Veículo");
} else {
    $("#titulo1").html("Alteração de Veículo");
}

$(document).ready(function () {

    //SALVANDO DADOS DO FORMULÁRIO DE DEMANDA
    $('#form_veiculo').submit(function () {

        var form_valido = formulario_validator("");

        if (form_valido) {
            projetouniversal.util.getjson({
                url: PORTAL_URL + "controller/carro/salvar_veiculo.php",
                type: "POST",
                data: $('#form_veiculo').serialize(),
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
            postToURL(PORTAL_URL + 'veiculo-painel.php', {mensagem: obj.retorno});
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
    var modelo = $("#modelo").val();
    var placa = $("#placa").val();
    var cor = $("#cor").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label#erro_modelo').remove();
    $('label#erro_placa').remove();
    $('label#erro_cor').remove();

    if (obj.tipo == "" || obj.tipo == null) {//VALIDAÇÃO SEM BANCO DE DADOS
        //VERIFICANDO SE FOI INFORMADO A HORA PREVISTA DE CHEGADA
        if (cor == "") {
            $('div#div_cor').after('<label id="erro_cor" class="error">O campo cor é obrigatório.</label>');
            valido = false;
            element = $('div#div_cor');
        }
        //VERIFICANDO SE O CAMPO HORA DE SAÍDA FOI INFORMADO
        if (placa == "") {
            $('div#div_placa').after('<label id="erro_placa" class="error">O campo placa é obrigatório.</label>');
            valido = false;
            element = $('div#div_placa');
        }
        //VERIFICANDO SE O CAMPO DATA DE SAÍDA FOI INFORMADO
        if (modelo == "") {
            $('div#div_modelo').after('<label id="erro_modelo" class="error">O campo modelo é obrigatório.</label>');
            valido = false;
            element = $('div#div_modelo');
        }
    }
    else if (obj.tipo == "placa") {
        $('div#div_placa').after('<label id="erro_placa" class="error">' + obj.retorno + '</label>');
        valido = false;
        element = $('div#div_placa');
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