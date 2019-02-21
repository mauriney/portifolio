/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE DEMANDA
 */
var caminho = window.location.href.toString().split(window.location.host)[1];//Pegando a url completa

if (retornaUrl(caminho) == "") {
    $("#titulo1").html("Cadastro de Saída");
} else {
    $("#titulo1").html("Alteração de Saída");
}

$(document).ready(function () {

    $('#data_saida').kendoDatePicker({
        // display month and year in the input
        format: "dd/MM/yyyy"
    });
    var datepicker = $("#data_saida").data("kendoDatePicker");

    $('#data_saida').click(function () {
        datepicker.open();
    });

    $('#data_prevista').kendoDatePicker({
        // display month and year in the input
        format: "dd/MM/yyyy"
    });

    var datepicker2 = $("#data_prevista").data("kendoDatePicker");

    $('#data_prevista').click(function () {
        datepicker2.open();
    });

    //SALVANDO DADOS DO FORMULÁRIO DE DEMANDA
    $('#form_saida').submit(function () {

        var form_valido = formulario_validator("");

        $("#enviar").hide();

        if (form_valido) {
            projetouniversal.util.getjson({
                url: PORTAL_URL + "controller/carro/salvar_saida.php",
                type: "POST",
                data: $('#form_saida').serialize(),
                enctype: 'multipart/form-data',
                success: onSuccessSend,
                error: onError
            });
            return false;
        } else {
            $("#enviar").show();
            return false;
        }
    });

    function onSuccessSend(obj) {
        if (obj.msg == 'success') {
            postToURL(PORTAL_URL + 'carro-painel.php', {mensagem: obj.retorno});
        } else if (obj.msg == 'error') {
            $("#enviar").show();
            formulario_validator(obj);
        }
        return false;
    }
    /* ERRO AO ENVIAR AJAX */
    function onError(args) {
        $("#enviar").show();
        $.prompt('onError: ' + args.retorno);
    }
});

//VALIDATOR DO LOGIN
function formulario_validator(obj) {
    var valido = true;
    var motorista = $("#motorista_id").val();
    var veiculo = $("#veiculo_id").val();
    var data_saida = $("#data_saida").val();
    var hora_s = $("#horas").val();
    var data_prevista = $("#data_prevista").val();
    var hora_p = $("#horap").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label#erro_motorista').remove();
    $('label#erro_veiculo').remove();
    $('label#erro_data_saida').remove();
    $('label#erro_horas').remove();
    $('label#erro_data_prevista').remove();
    $('label#erro_horap').remove();

    if (obj.tipo == "" || obj.tipo == null) {//VALIDAÇÃO SEM BANCO DE DADOS
        //VERIFICANDO SE FOI INFORMADO A HORA PREVISTA DE CHEGADA
        if (hora_p == "") {
            $('div#div_horap').after('<label id="erro_horap" class="error">O campo hora prevista de chegada é obrigatório.</label>');
            valido = false;
            element = $('div#div_horap');
        }
        //VERIFICANDO SE O CAMPO DATA DE PREVISTA DE CHEGADA FOI INFORMADO
        if (data_prevista == "") {
            $('div#div_data_prevista').after('<label id="erro_data_prevista" class="error">O campo data prevista de chegada é obrigatório.</label>');
            valido = false;
            element = $('div#div_data_prevista');
        }
        //VERIFICANDO SE O CAMPO HORA DE SAÍDA FOI INFORMADO
        if (hora_s == "") {
            $('div#div_horas').after('<label id="erro_horas" class="error">O campo hora de saída é obrigatório.</label>');
            valido = false;
            element = $('div#div_horas');
        }
        //VERIFICANDO SE O CAMPO DATA DE SAÍDA FOI INFORMADO
        if (data_saida == "") {
            $('div#div_data_saida').after('<label id="erro_data_saida" class="error">O campo data de saída é obrigatório.</label>');
            valido = false;
            element = $('div#div_data_saida');
        }
        //VERIFICANDO SE O CAMPO ESTADO FOI INFORMADO
        if (motorista == "" || motorista == "0" || motorista == null) {
            $('div#div_motorista').after('<label id="erro_motorista" class="error" style="margin-top: -14px">O campo motorista é obrigatório.</label>');
            valido = false;
            element = $('div#div_motorista');
        }
        //VERIFICANDO SE O CAMPO SEGMENTO FOI INFORMADO
        if (veiculo == "" || veiculo == "0" || veiculo == null) {
            $('div#div_veiculo').after('<label id="erro_veiculo" class="error" style="margin-top: -14px">O campo veículo é obrigatório.</label>');
            valido = false;
            element = $('div#div_veiculo');
        }

    }
    else if (obj.tipo == "horap") {
        $('div#div_horap').after('<label id="erro_horap" class="error">' + obj.retorno + '</label>');
        valido = false;
        element = $('div#div_horap');
    } else if (obj.tipo == "data_prevista") {
        $('div#div_data_prevista').after('<label id="erro_data_prevista" class="error">' + obj.retorno + '</label>');
        valido = false;
        element = $('div#div_data_prevista');
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