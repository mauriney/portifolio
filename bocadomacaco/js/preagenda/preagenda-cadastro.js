/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE DEMANDA
 */

var caminho = window.location.href.toString().split(window.location.host)[1];//Pegando a url completa

if (retornaUrl(caminho) == "") {
    $("#titulo1").html("Marcar Pré-Agenda");
} else {
    $("#titulo1").html("Alterar Pré-Agenda");
}

$(document).ready(function () {

    $("#prioridade").change();

    $('#prazo').kendoDatePicker({
        // display month and year in the input
        format: "dd/MM/yyyy"
    });
    var datepicker = $("#prazo").data("kendoDatePicker");

    $('#prazo').click(function () {
        datepicker.open();
    });

    //SALVANDO DADOS DO FORMULÁRIO DE DEMANDA
    $('#form_preagenda').submit(function () {

        var form_valido = formulario_validator("");

        if (form_valido) {
            projetouniversal.util.getjson({
                url: PORTAL_URL + "controller/preagenda/salvar_preagenda.php",
                type: "POST",
                data: $('#form_preagenda').serialize(),
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
            postToURL(PORTAL_URL + 'preagenda-painel.php', {mensagem: obj.retorno});
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
    var segmento = $("#segmento").val();


    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label#erro_nome').remove();
    $('label#erro_segmento').remove();


    if (obj.tipo == "" || obj.tipo == null) {//VALIDAÇÃO SEM BANCO DE DADOS
        //VERIFICANDO SE O CAMPO SEGMENTO FOI INFORMADO
        if (segmento == "") {
            $('div#div_segmento').after('<label id="erro_segmento" class="error">O campo segmento é obrigatório.</label>');
            valido = false;
            element = $('div#div_segmento');
        }
        //VERIFICANDO SE O CAMPO NOME FOI INFORMADO
        if (nome == "") {
            $('div#div_nome').after('<label id="erro_nome" class="error">O campo nome é obrigatório.</label>');
            valido = false;
            element = $('div#div_nome');
        }
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