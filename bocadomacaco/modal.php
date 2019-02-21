<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Basic Modal</h4>
            </div>
            <div class="modal-body">
                <h3>Modal Body</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog md-grupo" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="fechar"></span></button>
                <span>Enviar a Agenda para Grupos de Contato</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="grupos">
                            <h3>Grupos de contato que receberão este evento no e-mail</h3>
                            <ul>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> MARCAR TODOS </label></li>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> Dirigentes partidários da FPRB </label></li>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> Administração </label></li>

                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> ASSOCIAÇÃO DE MORADORES </label></li>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> COOPERATIVAS </label></li>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> Coordenação Geral </label></li>

                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> EMPRESAS PARTICULARES </label></li>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> Secretários de Estado </label></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="compartilhar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog md-grupo" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="fechar"></span></button>
                <span>Enviar a Agenda para Grupos de Contato</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="grupos">
                            <h3>Grupos de contato que receberão este evento no e-mail</h3>
                            <ul>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> MARCAR TODOS </label></li>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> Dirigentes partidários da FPRB </label></li>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> Administração </label></li>

                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> ASSOCIAÇÃO DE MORADORES </label></li>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> COOPERATIVAS </label></li>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> Coordenação Geral </label></li>

                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> EMPRESAS PARTICULARES </label></li>
                                <li class="col-md-4"><input type="checkbox" name="marcar-todos" id="marcar-todos"> <label for="marcar-todos"> Secretários de Estado </label></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="md-status-demanda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog md-status-demanda" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="fechar"></span></button>
                <span>Status da Demanda</span>
            </div>
            <div class="modal-body">
                <ul class="status-modal">
                    <li class="col-md-3"><label class="st-nao-iniciado" for="nao-iniciado">NÃO INICIADO</label><input type="radio" name="opcao" id="nao-iniciado" /></li>
                    <li class="col-md-3"><label class="st-em-andamento" for="em-andamento">EM ANDAMENTO</label><input type="radio" name="opcao" id="em-andamento" /></li>
                    <li class="col-md-3"><label class="st-concluido" for="concluido">CONCLUÍDO</label><input type="radio" name="opcao" id="concluido" /></li>
                    <li class="col-md-3"><label class="st-cancelado" for="cancelado">CANCELADO</label><input type="radio" name="opcao" id="cancelado" /></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
            </div>
        </div>
    </div>
</div>