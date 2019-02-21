/*
 * SCRIPT DO FORMULÁRIO DO PAINEL DE DEMANDA
 */
$("#titulo1").html("Marcar Agenda");

//CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
$('div#div_success').slideDown(1000).delay(5000).slideUp(1000);

$('input#sim1').click(function () {
    document.getElementById("obs_agenda").style.display = "block";
});

$('input#nao1').click(function () {
    document.getElementById("obs_agenda").style.display = "none";
});

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

    $("#prioridade").change();

    $('#data').kendoDatePicker({
        // display month and year in the input
        format: "dd/MM/yyyy"
    });
    var datepicker = $("#data").data("kendoDatePicker");

    $('#data').click(function () {
        datepicker.open();
    });

    //SALVANDO DADOS DO FORMULÁRIO DE DEMANDA
    $('#form_marcar_agenda').submit(function () {

        var form_valido = formulario_validator("");
        
        $("#enviar").hide();

        if (form_valido) {
            projetouniversal.util.getjson({
                url: PORTAL_URL + "controller/preagenda/salvar_marcar_agenda.php",
                type: "POST",
                data: $('#form_marcar_agenda').serialize(),
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
            postToURL(PORTAL_URL + 'agendacompleta-painel.php', {mensagem: obj.retorno});
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

//VALIDATOR DO LOGIN
function formulario_validator(obj) {
    var valido = true;
    var demandante = $("#demandante").val();
    var quemvai = $("#quemvai").val();
    var data_agenda = $("input#data").val();
    var hora = $("input#hora").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label#erro_demandante').remove();
    $('label#erro_quemvai').remove();
    $('label#erro_data_agenda').remove();
    $('label#erro_hora').remove();

    if (obj.tipo == "" || obj.tipo == null) {//VALIDAÇÃO SEM BANCO DE DADOS
        //VERIFICANDO SE O CAMPO QUEM VAI FOI INFORMADO
        if (quemvai == "" || quemvai <= 0) {
            $('div#div_quemvai').after('<label id="erro_quemvai" class="error">O campo quem vai é obrigatório.</label>');
            valido = false;
            element = $('div#div_quemvai');
        }
        //VERIFICANDO SE O CAMPO HORA FOI INFORMADO
        if (hora == "") {
            $('div#div_hora').after('<label id="erro_hora" class="error">O campo hora é obrigatório.</label>');
            valido = false;
            element = $('div#div_hora');
        }
        //VERIFICANDO SE O CAMPO DATA DA AGENDA FOI INFORMADO
        if (data_agenda == "") {
            $('div#div_data_agenda').after('<label id="erro_data_agenda" class="error">O campo data é obrigatório.</label>');
            valido = false;
            element = $('div#div_data_agenda');
        }
        //VERIFICANDO SE O CAMPO DEMANDANTE FOI INFORMADO
        if (demandante == "") {
            $('div#div_demandante').after('<label id="erro_demandante" class="error">O campo demandante é obrigatório.</label>');
            valido = false;
            element = $('div#div_demandante');
        }

    } else if (obj.tipo == "demandante") {
        $('div#div_demandante').after('<label id="erro_demandante" class="error">' + obj.retorno + '</label>');
        valido = false;
        element = $('div#div_demandante');
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

function confirmar_cor() {

    var checkbox = $("#opcao3").is(':checked') ? 1 : 0;

    var confirmar = document.getElementById("confirmar_cor");

    if (checkbox) {
        confirmar.className = "confirmado-agenda";
    } else {
        confirmar.className = "confirmar";
    }
}

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

function vf_bairro(id) {
    var bairro = document.getElementById("mostrar-bairro");

    if (id == 16) {
        bairro.style.display = 'block';
    } else {
        bairro.style.display = 'none';
    }
}