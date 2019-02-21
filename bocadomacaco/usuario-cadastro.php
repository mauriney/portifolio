<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
if (@is_numeric($_GET['id']) && @antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT * FROM tb_bsc_usuario WHERE IdUsuario = ?");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_usuario = $result->fetch(PDO::FETCH_ASSOC);

    $usuario_id = $dados_usuario['IdUsuario'];
    $usuario_nome = utf8_encode($dados_usuario['Nome']);
    $funcao = utf8_encode($dados_usuario['Funcao']);
    $email = $dados_usuario['Email'];
    $fixo = $dados_usuario['TelefoneFixo'];
    $celular = $dados_usuario['TelefoneCel'];
    $nivel_acesso = $dados_usuario['IdAcesso'];
    $status = $dados_usuario['Status'];
    $login = $dados_usuario['login'];

    $agenda = $dados_usuario['RecebeEmail'];
    $resumo = $dados_usuario['RecebeEmail2'];
    $vai = $dados_usuario['quemvai'];
    $demanda = $dados_usuario['Demanda'];

    if ($resumo == 1) {
        $resumo = "checked='true'";
    } else {
        $resumo = "";
    }

    if ($agenda == 1) {
        $agenda = "checked='true'";
    } else {
        $agenda = "";
    }

    if ($vai == 1) {
        $vai = "checked='true'";
    } else {
        $vai = "";
    }

    if ($demanda == 1) {
        $demanda = "checked='true'";
    } else {
        $demanda = "";
    }
} else {
    $usuario_id = "";
    $usuario_nome = "";
    $funcao = "";
    $email = "";
    $fixo = "";
    $celular = "";
    $nivel_acesso = "";
    $status = "";
    $agenda = "";
    $resumo = "";
    $login = "";
    $vai = "";
    $demanda = "";
}
?>


<!-- Painel de Demandas -->
<div class="container conteudo">
    <form id="form_usuario" action="#" method="post">
        <input type="hidden" id="id" name="id" value="<?php echo $usuario_id ?>"/>
        <div class="cadastro">
            <h1>Cadastro de Usuário</h1>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <div id="div_nome">
                            <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome" value="<?php echo $usuario_nome; ?>"/>
                        </div>
                    </div>
                </div>
            </div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="funcao">Função</label>
                        <input type="text" name="funcao" id="funcao" class="form-control" placeholder="Função" value="<?php echo $funcao; ?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" value="<?php echo $email; ?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nivel-acesso">Nível de Acesso</label>
                        <div id="div_nivel">
                            <select name="nivel-acesso" id="nivel-acesso" class="form-control">
                                <option value="">Escolha o nível de acesso</option>
                                <?php
                                $result3 = $db->prepare("SELECT * 
                             FROM tb_bsc_acesso
                             ORDER BY Descricao ASC");
                                $result3->execute();
                                while ($nivel = $result3->fetch(PDO::FETCH_ASSOC)) {
                                    if ($nivel_acesso == $nivel['IdAcesso']) {
                                        ?>
                                        <option selected="true" value='<?php echo $nivel['IdAcesso']; ?>'><?php echo ctexto(utf8_encode($nivel['Descricao'])); ?></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value='<?php echo $nivel['IdAcesso']; ?>'><?php echo ctexto(utf8_encode($nivel['Descricao'])); ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fixo">Telefone Fixo</label>
                        <input type="text" name="fixo" id="fixo" class="form-control" placeholder="Telefone Fixo" value="<?php echo $fixo; ?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input type="text" name="celular" id="celular" class="form-control" placeholder="Celular" value="<?php echo $celular; ?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status-usuario">Status do Usuário</label>
                        <div id="div_status">
                            <select name="status-usuario" id="status-usuario" class="form-control">
                                <option value="">Escolha o Status do Usuário</option>
                                <?php
                                if ($status == 1) {
                                    ?>
                                    <option selected="trrue" value="1">Ativo</option>
                                    <option value="2">Inativo</option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="1">Ativo</option>
                                    <option selected="true" value="2">Inativo</option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <span class="org-recebe">
                            <input <?php echo $vai; ?> type="checkbox" name="quemvai" id="quemvai" value="1">
                            <label for="quem-vai">Agenda</label>
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <span class="org-recebe">
                            <input <?php echo $demanda; ?> type="checkbox" name="demanda" id="demanda" value="1">
                            <label for="demanda">Demanda</label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <span class="org-recebe">
                            <input <?php echo $agenda; ?> type="checkbox" name="receber-agenda" id="receber-agenda" value="1">
                            <label for="receber-agenda">Recebe agenda em tempo real</label>
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <span class="org-recebe">
                            <input <?php echo $resumo; ?> type="checkbox" name="receber-resumo" id="receber-resumo" value="1">
                            <label for="receber-resumo">Recebe resumo da agenda diariamente</label>
                        </span>
                    </div>
                </div>
            </div>

            <h2>Acesso</h2>
            <!-- linha -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="login">Login</label>
                        <div id="div_login">
                            <input  type="text" name="login" id="login" class="form-control" placeholder="Login" value="<?php echo $login; ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <div id="div_senha">
                            <input  type="password" name="senha" id="senha" class="form-control" placeholder="Senha" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="senha">Confirmar Senha</label>
                        <div id="div_confirmarsenha">
                            <input  type="password" name="confirmarsenha" id="confirmarsenha" class="form-control" placeholder="ConfirmarSenha" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/configuracoes/usuario-cadastro.js"></script>

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>