/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE DEMANDA
 */
var caminho = window.location.href.toString().split(window.location.host)[1];//Pegando a url completa

if (retornaUrl(caminho) == "") {
    $("#titulo1").html("Cadastro de Demanda");
} else {
    $("#titulo1").html("Alteração de Demanda");
}

$(document).ready(function () {

    $("#prioridade").change();

    //COMBO ESTADO E MUNICIPIO
    $("#estado").change(function () {
        $("#municipio").html('<option value="0">Carregando...</option>');
        $.post(PORTAL_URL + "controller/basico/combo/cidades.php",
                {estado: $(this).val()},
        function (valor) {
            $("#municipio").html(valor);
            $("#municipio").select2();
        });
    });

    $('#prazo').kendoDatePicker({
        // display month and year in the input
        format: "dd/MM/yyyy"
    });
    var datepicker = $("#prazo").data("kendoDatePicker");

    $('#prazo').click(function () {
        datepicker.open();
    });

    //SALVANDO DADOS DO FORMULÁRIO DE DEMANDA
    $('#form_demanda').submit(function () {

        var form_valido = formulario_validator("");
        
        $("#enviar").hide();

        if (form_valido) {
            projetouniversal.util.getjson({
                url: PORTAL_URL + "controller/demanda/salvar_demanda.php",
                type: "POST",
                data: $('#form_demanda').serialize(),
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
            postToURL(PORTAL_URL + 'demanda-painel.php', {mensagem: obj.retorno});
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
    var demanda = $("#demanda").val();
    //var responsavel = $("#responsavel").val();
    var prazo = $("#prazo").val();
    var segmento = $("#segmento").val();
    var estado = $("#estado").val();
    var cidade = $("#municipio").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label#erro_demanda').remove();
    //$('label#erro_responsavel').remove();
    $('label#erro_prazo').remove();
    $('label#erro_segmento').remove();
    $('label#erro_estado').remove();
    $('label#erro_municipio').remove();

    if (obj.tipo == "" || obj.tipo == null) {//VALIDAÇÃO SEM BANCO DE DADOS
        //VERIFICANDO SE FOI ESCOLHIDO ALGUM RESPONSÁVEL
        /*if (responsavel == "" || responsavel == "0" || responsavel == null) {
            $('div#div_responsavel').after('<label id="erro_responsavel" class="error">É necessário escolher um responsável.</label>');
            valido = false;
            element = $('div#div_responsavel');
        }*/
        //VERIFICANDO SE O CAMPO PRAZO FOI INFORMADO
        if (prazo == "") {
            $('div#div_prazo').after('<label id="erro_prazo" class="error">O campo prazo é obrigatório.</label>');
            valido = false;
            element = $('div#div_prazo');
        }
        //VERIFICANDO SE O CAMPO CIDADE FOI INFORMADO
        if (cidade == "") {
            $('div#div_municipio').after('<label id="erro_municipio" class="error">O campo cidade é obrigatório.</label>');
            valido = false;
            element = $('div#div_municipio');
        }
        //VERIFICANDO SE O CAMPO ESTADO FOI INFORMADO
        if (estado == "") {
            $('div#div_estado').after('<label id="erro_estado" class="error">O campo estado é obrigatório.</label>');
            valido = false;
            element = $('div#div_estado');
        }
        //VERIFICANDO SE O CAMPO SEGMENTO FOI INFORMADO
        if (segmento == "") {
            $('div#div_segmento').after('<label id="erro_segmento" class="error">O campo segmento é obrigatório.</label>');
            valido = false;
            element = $('div#div_segmento');
        }
        //VERIFICANDO SE O CAMPO NOME FOI INFORMADO
        if (demanda == "") {
            $('div#div_demanda').after('<label id="erro_demanda" class="error">O campo demanda é obrigatório.</label>');
            valido = false;
            element = $('div#div_demanda');
        }

    } else if (obj.tipo == "demanda") {
        $('div#div_demanda').after('<label id="erro_demanda" class="error">' + obj.retorno + '</label>');
        valido = false;
        element = $('div#div_demanda');
    } else if (obj.tipo == "responsavel") {
        $('div#div_responsavel').after('<label id="erro_responsavel" class="error">' + obj.retorno + '</label>');
        valido = false;
        element = $('div#div_responsavel');
    }

    if (element != null) {
        var topPosition = element.offset().top - 135;
        $('html, body').animate({
            scrollTop: topPosition
        }, 800);
    }
    return valido;
}

function cores(op) {

    var prioridade = document.getElementById("prioridade_cor");

    if (op == 1) {
        prioridade.className = "form-group prioridade baixa";
    } else if (op == 2) {
        prioridade.className = "form-group prioridade media";
    } else if (op == 3) {
        prioridade.className = "form-group prioridade alta";
    }
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