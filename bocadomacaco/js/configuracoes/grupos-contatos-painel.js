/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE CONTATO
 */
$("#titulo1").html("Painel de Grupos de Contatos");

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

function pesquisa() {

    var buscar = $("#nome").val();

    url = PORTAL_URL + "controller/configuracoes/grupo-contatos/busca_nome.php?buscar=" + buscar + "";
    ajax(url);
}

function remover(id, contador) {

    var temp = {
        state0: {
            html: '<p>Deseja mesmo remover este grupo?</p>',
            buttons: {SIM: 1, NÃO: 0},
            focus: 2,
            submit: function (e, v, m, f) {
                if (v == 0)
                    $.prompt.close()
                else if (v == 1) {
                    projetouniversal.util.getjson({
                        url: PORTAL_URL + "controller/configuracoes/grupo-contatos/remover_grupo.php",
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
            postToURL(PORTAL_URL + 'grupos-contatos-painel.php', {mensagem: obj.retorno, carre: contador});
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

//CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
$('div#div_success').slideDown(1000).delay(5000).slideUp(1000);