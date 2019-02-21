/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE CONTATO
 */
var caminho = window.location.href.toString().split(window.location.host)[1];//Pegando a url completa

if (retornaUrl(caminho) == "") {
    $("#titulo1").html("Cadastro de Contato");
} else {
    $("#titulo1").html("Alteração de Contato");
}

//SE TODOS OS CHECKBOX ESTIVEREM MARCADOS, MARCAR TODOS
if ($("#vf_checados").val()) {
    $('#marcar-todos').attr("checked", true);
}

$(document).ready(function () {

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

    $('#nascimento').kendoDatePicker({
        // display month and year in the input
        format: "dd/MM/yyyy"
    });
    var datepicker = $("#nascimento").data("kendoDatePicker");

    $('#nascimento').click(function () {
        datepicker.open();
    });

    //SALVANDO DADOS DO FORMULÁRIO DE DEMANDA
    $('#form_contato').submit(function () {

        var form_valido = formulario_validator("");
        
        $("#enviar").hide();

        if (form_valido) {
            projetouniversal.util.getjson({
                url: PORTAL_URL + "controller/contato/salvar_contato.php",
                type: "POST",
                data: $('#form_contato').serialize(),
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
            postToURL(PORTAL_URL + 'contato-painel.php', {mensagem: obj.retorno});
        } else if (obj.msg == 'error') {
            $("#enviar").show();
            formulario_validator(obj);
        }
        return false;
    }

    function onError(args) {
        $("#enviar").show();
        $.prompt('onError: ' + args.retorno);
    }
});

//MARCAR TODOS OS CAMPOS DO MÓDULO DO FORMULÁRIO DE USUÁRIOS
function marcar(obj) {
    if ($(obj).attr("checked")) {
        $(obj).parents('div#grupos').find('input').each(function (i, acao) {
            $(acao).attr("checked", true);
        });
    } else {
        $(obj).parents('div#grupos').find('input').each(function (i, acao) {
            $(acao).attr("checked", false);
        });
    }
}

function vf(obj) {
    if ($(obj).attr("checked")) {
        var checado = true;
        $('#grupos').find('input').each(function (i, value) {

            if (!$(value).attr("checked") && $(value).val() != $(obj).val() && $(value).attr("id") != 'marcar-todos') {
                checado = false;
            }
        });

        if (checado) {
            $('#marcar-todos').attr("checked", true);
        }
    } else {
        $('#marcar-todos').attr("checked", false);
    }
}

function duplicarCampos() {
    var clone = document.getElementById('origem').cloneNode(true);
    var destino = document.getElementById('destino');
    destino.appendChild(clone);

    var camposClonados = clone.getElementsByTagName('input');

    for (i = 0; i < camposClonados.length; i++) {
        camposClonados[i].value = '';
    }
}

function removerCampos(obj) {
    var qtd = 0;

    //CASO SEJA ALTERAÇÃO  
    if (document.getElementById("id").value == "") {
        qtd++;
    }

    $('#destino').find('input').each(function (i, value) {
        qtd++;
    });

    if (qtd > 1) {
        $(obj).parents('#origem').remove();
    }
}

//VALIDATOR DO LOGIN
function formulario_validator(obj) {
    var valido = true;
    var nome = $("#nome").val();
    var celular_principal = $("#celular_principal").val();
    var dia = $("#dia").val();
    var mes = $("#mes").val();
    var referencia = $("#referencia").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label#erro_nome').remove();
    $('label#erro_celular_principal').remove();
    $('label#erro_nascimento').remove();
    $('label#erro_referencia').remove();
    $('label#erro_segmento').remove();
    $('label#erro_dia').remove();
    $('label#erro_mes').remove();

    if (obj.tipo == "" || obj.tipo == null) {//VALIDAÇÃO SEM BANCO DE DADOS

        if (vf_segmentos() == false) {
            $('div#div_segmento').after('<label id="erro_segmento" class="error">É necessário escolher pelo menos 1 segmento.</label>');
            valido = false;
            element = $('div#div_segmento');
        }

        if (referencia == "") {
            $('div#div_referencia').after('<label id="erro_referencia" class="error">O campo referência é obrigatório.</label>');
            valido = false;
            element = $('div#div_referencia');
        }

        if (mes == "") {
            $('div#div_mes').after('<label id="erro_mes" class="error">O campo mês é obrigatório.</label>');
            valido = false;
            element = $('div#div_mes');
        }

        if (dia == "") {
            $('div#div_dia').after('<label id="erro_dia" class="error">O campo dia é obrigatório.</label>');
            valido = false;
            element = $('div#div_dia');
        }

        if (celular_principal == "") {
            $('div#div_celular_principal').after('<label id="erro_celular_principal" class="error">O campo celular principal é obrigatório.</label>');
            valido = false;
            element = $('div#div_celular_principal');
        }

        //VERIFICANDO SE O CAMPO NOME FOI INFORMADO
        if (nome == "") {
            $('div#div_nome').after('<label id="erro_nome" class="error">O campo nome é obrigatório.</label>');
            valido = false;
            element = $('div#div_nome');
        }

    } else if (obj.tipo == "celular") {
        $('div#div_celular_principal').after('<label id="erro_celular_principal" class="error">' + obj.retorno + '</label>');
        valido = false;
        element = $('div#div_celular_principal');
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

function vf_segmentos() {
    var checado = false;
    $('#grupos').find('input').each(function (i, value) {
        if ($(value).attr("checked")) {
            checado = true;
        }
    });
    return checado;
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