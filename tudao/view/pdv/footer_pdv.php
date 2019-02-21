<footer>
    <div class="row">
        <div class="col-md-5">

            <div class="acao">
                <!-- <div class="mensagem">
                    <div class="row">
                        <div class="col-xs-10">Deseja cancelar o pedido?</div>
                        <div class="col-xs-1"><a href="#" class="text-success"><i class="fa fa-check"></i></a></div>
                        <div class="col-xs-1"><a href="#" class="text-danger"><i class="fa fa-close"></i></a></div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-4">
                        <a id="cancelar" <?= $qtd_pedidos == 0 ? 'style="display: none"' : ''; ?> onclick="cancelar_pedido()" href="#">
                            <i class="fa fa-trash"></i><br>
                            CANCELAR
                        </a>
                    </div>
<!--                    <div class="col-md-3">
                        <a id="espera" <?= $qtd_pedidos == 0 ? 'style="display: none"' : ''; ?> onclick="pedido_espera()" href="#">
                            <i class="fa fa-th-list"></i><br>
                            ESPERA
                        </a>
                    </div>-->
                    <div class="col-md-6 col-md-offset-2">
                        <a <?= $qtd_pedidos == 0 ? 'style="display: none"' : ''; ?> id="a_receber" href="#" class="text-right">
                            A RECEBER (R$) <br>
                            <strong id="vlr_receber" class="text-success"><?php echo fdec($subtotalgeral3); ?></strong>
                        </a>
                    </div>
                </div>        
            </div>

        </div>
        <div class="col-md-7">

            <div class="row">
                <div class="col-md-2">
                    <a id="listar_pedidos" href="#">
                        <i class="fa fa-bars"></i><br>
                        PEDIDOS
                    </a>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div id="div_menu" class="col-md-3" style="display: none">
                            <a id="a_menu" href="#">
                                <i class="fa fa-reply"></i><br>
                                MENU
                            </a>
                        </div>
                        <div class="col-md-3 col-md-offset-6">
                            <p class="text-center"><text id="text_hora"><?php echo date("H:i:s") ?></text> <br> <?php echo date("d") . " de " . getMes(date("m")) . " de " . date("Y") ?></p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</footer>