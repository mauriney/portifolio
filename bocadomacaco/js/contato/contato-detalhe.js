/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE CONTATO
 */
$("#titulo1").html("Detalhe do Contato");


function remover(id) {

    var temp = {
        state0: {
            html: '<p>Deseja mesmo remover este contato?</p>',
            buttons: {SIM: 1, NÃO: 0},
            focus: 2,
            submit: function (e, v, m, f) {
                if (v == 0)
                    $.prompt.close()
                else if (v == 1) {
                    projetouniversal.util.getjson({
                        url: PORTAL_URL + "controller/contato/remover_contato.php",
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

function onSuccessSend(obj) {
    if (obj.msg == 'success') {
        postToURL(PORTAL_URL + 'contato-painel.php', {mensagem: obj.retorno});
    } else if (obj.msg == 'error') {
        formulario_validator(obj);
    }
    return false;
}
/* ERRO AO ENVIAR AJAX */
function onError(args) {
    $.prompt('onError: ' + args.retorno);
}

