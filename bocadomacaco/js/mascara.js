jQuery(function ($) {
    $("#cpf").mask("999.999.999-99");
    $("#numero").mask("9?9999");
    $("#prazo").mask("99/99/9999");
    $("#inicio").mask("99/99/9999");
    $("#data").mask("99/99/9999");
    $("#data_saida").mask("99/99/9999");
    $("#data_prevista").mask("99/99/9999");
    $("#fim").mask("99/99/9999");
    $("#nascimento").mask("99/99/9999");
    $("#cep").mask("99.999-999");
    $("#hora").mask("99:99");
    $("#horas").mask("99:99");
    $("#horap").mask("99:99");
    $("#celular_principal").mask("(99) 9999-9999?9").live('blur', function (event) {
        manipula_telefone(event);
    });
    $('#celular')
            .mask("(99) 9999-9999?9")
            .live('blur', function (event) {
                manipula_telefone(event);
            });
    $('#telefone')
            .mask("(99) 9999-9999?9")
            .live('blur', function (event) {
                manipula_telefone(event);
            });
    $('#fixo')
            .mask("(99) 9999-9999?9")
            .live('blur', function (event) {
                manipula_telefone(event);
            });
    function manipula_telefone(event) {
        var target, phone, element;
        target = (event.currentTarget) ? event.currentTarget : event.srcElement;
        phone = target.value.replace(/\D/g, '');
        element = $(target);
        element.unmask();
        if (phone.length > 10) {
            element.mask("(99) 99999-999?9");
        } else {
            element.mask("(99) 9999-9999?9");
        }
    }
});
