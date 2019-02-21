/*
 * SCRIPT DO FORMULÁRIO DE CADASTRO DE DEMANDA
 */
$("#titulo1").html("Detalhe da Demanda");

$(document).ready(function () {

    //SALVANDO DADOS DO FORMULÁRIO DE DEMANDA
    $('#form_detalhe').submit(function () {

        var form_valido = formulario_validator("");

        if (form_valido) {
            projetouniversal.util.getjson({
                url: PORTAL_URL + "controller/demanda/salvar_detalhe.php",
                type: "POST",
                data: $('#form_detalhe').serialize(),
                enctype: 'multipart/form-data',
                success: onSuccessSend,
                error: onError
            });
            return false;
        } else {
            return false;
        }
    });

    $('#form_demanda').submit(function () {
        projetouniversal.util.getjson({
            url: PORTAL_URL + "controller/demanda/atualizar_demanda.php",
            type: "POST",
            data: $('#form_demanda').serialize(),
            enctype: 'multipart/form-data',
            success: onSuccessSend,
            error: onError
        });
        return false;
    });
});

function onSuccessSend(obj) {
    if (obj.msg == 'success') {

        var carre = $("#carre").val();

        postToURL('#', {mensagem: obj.retorno, carre: carre});
    } else if (obj.msg == 'error') {
        formulario_validator(obj);
    }
    return false;
}
/* ERRO AO ENVIAR AJAX */
function onError(args) {
    $.prompt('onError: ' + args.retorno);
}

//VALIDATOR DO LOGIN
function formulario_validator(obj) {
    var valido = true;
    var obs = $("#observacoes").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label#erro_obs').remove();

    if (obj.tipo == "" || obj.tipo == null) {//VALIDAÇÃO SEM BANCO DE DADOS
        //VERIFICANDO SE O CAMPO NOME FOI INFORMADO
        if (obs == "") {
            $('div#div_obs').after('<label id="erro_obs" class="error">O campo observação é obrigatório.</label>');
            valido = false;
            element = $('div#div_obs');
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

function visualizar_obs(codigo) {

    var obs_visualizar = document.getElementById("obs_visualizar_" + codigo);
    var obs_editar = document.getElementById("obs_editar_" + codigo);
    var obs_visualizar2 = document.getElementById("obs_visualizar2_" + codigo);
    var obs_editar2 = document.getElementById("obs_editar2_" + codigo);

    obs_visualizar.style.display = 'none';
    obs_editar.style.display = 'block';
    obs_visualizar2.style.display = 'none';
    obs_editar2.style.display = 'block';
}

function adicionar() {
    var obs_add = document.getElementById("obs_add");
    var obs_add2 = document.getElementById("obs_add2");
    obs_add.style.display = 'block';
    obs_add2.style.display = 'block';
}

function atualizar_obs(codigo) {

    var form_valido = formulario_validator2(codigo);

    if (form_valido) {
        projetouniversal.util.getjson({
            url: PORTAL_URL + "controller/demanda/atualizar_detalhe.php",
            type: "POST",
            data: {codigo: codigo, obs: $("#observacoes_" + codigo).val()},
            enctype: 'multipart/form-data',
            success: onSuccessSend,
            error: onError
        });
        return false;
    } else {
        return false;
    }

    function formulario_validator2(codigo) {
        var valido = true;
        var obs = $("#observacoes_" + codigo).val();
        var element = null;

        //LIMPA MENSAGENS DE ERRO
        $('label#erro_obs_' + codigo + '').remove();

//VERIFICANDO SE O CAMPO NOME FOI INFORMADO
        if (obs == "") {
            $('div#div_obs_' + codigo + '').after('<label id="erro_obs_' + codigo + '" class="error">O campo observação é obrigatório.</label>');
            valido = false;
            element = $('div#div_obs_' + codigo + '');
        }

        if (element != null) {
            var topPosition = element.offset().top - 135;
            $('html, body').animate({
                scrollTop: topPosition
            }, 800);
        }

        return valido;
    }
}

function remover(codigo) {

    var temp = {
        state0: {
            html: '<p>Deseja mesmo remover está observação?</p>',
            buttons: {SIM: 1, NÃO: 0},
            focus: 2,
            submit: function (e, v, m, f) {
                if (v == 0)
                    $.prompt.close()
                else if (v == 1) {
                    projetouniversal.util.getjson({
                        url: PORTAL_URL + "controller/demanda/remover_obs.php",
                        type: "POST",
                        data: {codigo: codigo},
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

//CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
$('div#div_success').slideDown(1000).delay(5000).slideUp(1000);