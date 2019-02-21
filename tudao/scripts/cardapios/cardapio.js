/*---------------------------------------------------------------------------------------------------------
 DATA: 13/01/2017 ÀS 09:42
 NOME: JS DA CLASSE DE CARDAPIO
 ---------------------------------------------------------------------------------------------------------*/
//MARCANDO A CATEGORIA ESCOLHIDA
    var categoria_id = $("#categoria").val();
    
    $('li#cat').each(function() {
        if (categoria_id == $(this).attr('rel')) {
            $(this).find('a').removeClass('active');
            $(this).find('a').addClass('active');
        } else {
            $(this).find('a').removeClass('active');
        }
    });
//------------------------------------------------------------------------------
//FUNÇÃO DO LINK MOSTRAR MAIS PRODUTOS
    function mostrar_mais(obj) {
        var valor = $(obj).attr('rel');

        if (valor == 20) {
            $(obj).attr('rel', 25);
            mostrar(20);
        } else if (valor == 25) {
            $(obj).attr('rel', 50);
            mostrar(25);

        } else if (valor == 50) {
            $(obj).attr('rel', 75);
            mostrar(50);

        } else if (valor == 75) {
            $(obj).attr('rel', 100);
            mostrar(75);

        } else if (valor == 100) {
            mostrar(100);
            $("#mostrar_mais").hide();
        }
    }
//------------------------------------------------------------------------------
//FUNÇÃO MOSTRAR MAIS PELO MENU
    function mostrar(qtd) {

        $("li#li_mostrar").each(function() {
            var msotrar = $(this).attr('rel');
            if (qtd == msotrar) {
                $(this).removeClass("disabled");
                $(this).addClass("disabled");
            } else {
                $(this).removeClass("disabled");
            }
        });

        $("div#item").each(function() {

            var contador = $(this).attr('rel');

            if (qtd == 10) {
                if (contador <= 10) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else if (qtd == 20) {
                if (contador <= 20) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else if (qtd == 25) {
                if (contador <= 25) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else if (qtd == 50) {
                if (contador <= 50) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else if (qtd == 75) {
                if (contador <= 75) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else if (qtd == 100) {
                if (contador <= 100) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }

        });
    }
//------------------------------------------------------------------------------
//FUNÇÃO TIPO DE DISPLAY PELO MENU
    function display(view) {
        if (view == 'list')
        {
            $('#content .product-grid').attr('class', 'product-list box-product');
            $('.display').html('' +
                    '<div class="btn-group btn-group-sm">' +
                    '<span class="btn btn-default" disabled="disabled"><i class="fa fa-th-list"></i> Lista</span>' +
                    '<a class="btn btn-default" onclick="display(\'grid\');"><i class="fa fa-th"></i> Grade</a>' +
                    '</div>'
                    );
            $.totalStorage('display', 'list');
        }
        else
        {
            $('#content .product-list').attr('class', 'product-grid box-product');
            $('.display').html('' +
                    '<div class="btn-group btn-group-sm">' +
                    '<a class="btn btn-default" onclick="display(\'list\');"><i class="fa fa-th-list"></i> Lista</a>' +
                    '<span class="btn btn-default" disabled="disabled"><i class="fa fa-th"></i> Grade</span>' +
                    '</div>'
                    );
            $.totalStorage('display', 'grid');
        }
    }
    view = $.totalStorage('display');
    if (view) {
        display(view);
    } else {
        display('list');
    }
//------------------------------------------------------------------------------
