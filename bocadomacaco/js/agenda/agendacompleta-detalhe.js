/*
 * SCRIPT DO FORMULÁRIO DO PAINEL DE AGENDA
 */
$("#titulo1").html("Detalhes da Agenda");

//CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
$('div#div_success').slideDown(1000).delay(5000).slideUp(1000);

//SE TODOS OS CHECKBOX ESTIVEREM MARCADOS, MARCAR TODOS
if ($("#vf_checados").val()) {
    $('#marcar-todos').attr("checked", true);
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

function remover(id) {

    var temp = {
        state0: {
            html: '<p>Deseja mesmo remover está agenda?</p>',
            buttons: {SIM: 1, NÃO: 0},
            focus: 2,
            submit: function (e, v, m, f) {
                if (v == 0)
                    $.prompt.close()
                else if (v == 1) {
                    projetouniversal.util.getjson({
                        url: PORTAL_URL + "controller/agenda/remover_agenda.php",
                        type: "POST",
                        data: {id: id},
                        enctype: 'multipart/form-data',
                        success: onSuccessSend,
                        error: onError
                    });
                    return false;
                }
                return false;
            }
        }
    }

    $.prompt(temp, {
        close: function (e, v, m, f) {
            if (v !== undefined) {
                var str = "You can now process with this given information:<br />";
                $.each(f, function (i, obj) {
                    str += i + " - <em>" + obj + "</em><br />";
                });
                $('#results').html(str);
            }
        }
    });

}

$('#form_agenda').submit(function () {

    projetouniversal.util.getjson({
        url: PORTAL_URL + "controller/agenda/atualizar_agenda.php",
        type: "POST",
        data: $('#form_agenda').serialize(),
        enctype: 'multipart/form-data',
        success: onSuccessSend2,
        error: onError
    });
    return false;
});

function onSuccessSend2(obj) {
    if (obj.msg == 'success') {

        var carre = $("#carre").val();

        postToURL('#', {mensagem: obj.retorno, carre: carre});
    } else if (obj.msg == 'error') {
        formulario_validator(obj);
    }
    return false;
}

function onSuccessSend(obj) {
    if (obj.msg == 'success') {
        postToURL(PORTAL_URL + 'agendacompleta-painel.php', {mensagem: obj.retorno});
    } else if (obj.msg == 'error') {
        formulario_validator(obj);
    }
    return false;
}
/* ERRO AO ENVIAR AJAX */
function onError(args) {
    $.prompt('onError: ' + args.retorno);
}