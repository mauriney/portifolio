<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<div class="conteudo container">
    <div class="cadastro">
        <h1>Mudar Senha</h1>
        <form name="mudar_senha" id="mudar_senha" action="#" method="post">
            <!-- linha -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="senha-atual">Senha Atual</label>
                        <div id="div_senha_atual">
                            <input type="password" name="senha-atual" id="senha-atual" class="form-control" placeholder="Senha Atual" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nova-senha">Nova Senha</label>
                        <div id="div_nova_senha">
                            <input type="password" name="nova-senha" id="nova-senha" class="form-control" placeholder="Nova Senha" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="confirmar-senha">Confirmar Senha</label>
                        <div id="div_confirmar_senha">
                            <input type="password" name="confirmar-senha" id="confirmar-senha" class="form-control" placeholder="Confirmar Senha" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
        </form>
    </div>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/configuracoes/mudar-senha.js"></script>

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>