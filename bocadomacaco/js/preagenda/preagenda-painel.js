/*
 * SCRIPT DO FORMULÁRIO DO PAINEL DE DEMANDA
 */
$("#titulo1").html("Painel de Pré-Agendas");

function pesquisa() {

    var nome = $("#nome").val();
    var segmento = $("#segmento").val();

    url = PORTAL_URL + "controller/preagenda/busca_nome.php?segmento=" + segmento + "&nome=" + nome;
    ajax(url);
}

function remover(id, contador) {

    var temp = {
        state0: {
            html: '<p>Deseja mesmo remover está pré-agenda?</p>',
            buttons: {SIM: 1, NÃO: 0},
            focus: 2,
            submit: function (e, v, m, f) {
                if (v == 0)
                    $.prompt.close()
                else if (v == 1) {
                    projetouniversal.util.getjson({
                        url: PORTAL_URL + "controller/preagenda/remover_preagenda.php",
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

    function onSuccessSend(obj) {
        if (obj.msg == 'success') {
            postToURL(PORTAL_URL + 'preagenda-painel.php', {mensagem: obj.retorno, carre: contador});
        } else if (obj.msg == 'error') {
            formulario_validator(obj);
        }
        return false;
    }
    /* ERRO AO ENVIAR AJAX */
    function onError(args) {
        $.prompt('onError: ' + args.retorno);
    }

}

$(document).ready(function () {
    $('#mostrar-busca').click(function (event) {
        event.stopPropagation();
        if (document.getElementById("filtro").style.display == 'none') {
            $('div#filtro').slideDown(1000);
        } else {
            $('div#filtro').slideUp(1000);
        }
    });
});

//CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
$('div#div_success').slideDown(1000).delay(5000).slideUp(1000);