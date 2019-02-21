<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo.php');

$id = antiSQL(@$_GET['id']);

if (is_numeric($id)) {//VERIFICA SE O ID PASSADO POR PARÂMETRO É UM NÚMERO
    $sql = $db->prepare("SELECT p.id, p.nome, p.contato1, p.email, p.cpf, p.status
                FROM pessoas p
                WHERE p.id = ?");
    $sql->bindParam(1, $id);
    $sql->execute();
    $reg = $sql->fetch(PDO::FETCH_BOTH);
} else {
    $reg = 0;
    $id = "";
}
?>
<div class="wrapper">
    <?php include($_SESSION['NOME_SISTEMA'] . 'template/header.php'); ?>
    <?php include($_SESSION['NOME_SISTEMA'] . 'template/sidebar.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Módulo
                <small>Pessoa</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo PORTAL_URL; ?>view/geral/modulos.php"><i class="fa fa-dashboard"></i> <b>Módulos</b></a></li>
                <li><a href="<?php echo PORTAL_URL; ?>view/pessoas/index.php"> <b>Pessoas</b></a></li>
                <li class="active">Cadastro</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Formulário de Permissão do <?php echo $reg['nome']; ?></h3>
                </div><!-- /.box-header -->

                <form id="form_permissao" name="form_permissao" action="#" method="post">
                    <div class="box-body">
                        <input type="hidden" id="codigo" name="codigo" value="<?php echo $reg['id']; ?>" />
                        <div class="row">
                            <div class="col-md-12">
                                <label>STATUS</label>
                                <br/>
                                <div class="form-group has-feedback">
                                    <div class="col-md-2">
                                        <input <?= $reg['status'] == 1 ? 'checked="true"' : ''; ?> class="flat-red" type="radio" id="ativo" name="status" value="1"/>
                                        <label for="ativo">ATIVO</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input <?= $reg['status'] == 1 ? '' : 'checked="true"'; ?> class="flat-red" type="radio" id="inativo" name="status" value="0"/>
                                        <label for="inativo">INATIVO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label>TIPO DE CONTA</label>
                                <br/>
                                <div class="form-group has-feedback">
                                    <div class="col-md-2">
                                        <input <?= is_numeric(pesquisar_tabela("id", "clientes", "pessoa_id", "=", $id, "AND status = 1")) ? 'checked="true"' : '' ?> class="flat-red" type="checkbox" id="cliente" name="cliente" value="1"/>
                                        <label for="cliente">CLIENTE</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input <?= is_numeric(pesquisar_tabela("id", "fornecedores", "pessoa_id", "=", $id, "AND status = 1")) ? 'checked="true"' : '' ?> class="flat-red" type="checkbox" id="fornecedor" name="fornecedor" value="2"/>
                                        <label for="fornecedor">FORNECEDOR</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input <?= is_numeric(pesquisar_tabela("id", "funcionarios", "pessoa_id", "=", $id, "AND status = 1")) ? 'checked="true"' : '' ?> class="flat-red" type="checkbox" id="funcionario" name="funcionario" value="3"/>
                                        <label for="funcionario">FUNCIONÁRIO</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input <?= is_numeric(pesquisar_tabela("id", "usuarios", "pessoa_id", "=", $id, "AND status = 1")) ? 'checked="true"' : '' ?> class="flat-red" type="checkbox" id="usuario" name="usuario" value="4"/>
                                        <label for="usuario">USUÁRIO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div <?php echo $id == 1 ? 'style="display:none"' : "" ?> class="row">
                            <div class="col-md-6">
                                <div id="div_permissoes" class="form-group has-feedback">
                                    <label for="permissoes">PERMISSÕES</label>
                                    <select id="permissoes" name="permissoes[]" placeholder="Permissões" class="ls-select" multiple="">
                                        <option value="">Escolha a permissão</option>
                                        <?php
                                        $sql = $db->prepare("SELECT nome, nivel
                                                                    FROM sistema_nivel
                                                                    WHERE sistema = 1 AND nivel NOT IN (SELECT nivel FROM permissoes WHERE user_id = ?)
                                                                    ORDER BY nome ASC");
                                        $sql->bindValue(1, $id);
                                        $sql->execute();
                                        while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                            <option  value="<?php echo $linha['nivel']; ?>"><?php echo $linha['nome']; ?></option>
                                            <?php
                                        }

                                        $sql2 = $db->prepare("SELECT nome, nivel
                                                                    FROM sistema_nivel
                                                                    WHERE sistema = 1 AND nivel IN (SELECT nivel FROM permissoes WHERE user_id = ?)
                                                                    ORDER BY nome ASC");
                                        $sql2->bindValue(1, $id);
                                        $sql2->execute();
                                        while ($linha2 = $sql2->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                            <option selected="true" value="<?php echo $linha2['nivel']; ?>"><?php echo $linha2['nome']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>    
                        <br/>
                        <div class="row">
                            <div class="col-md-4">
                                <div id="div_senha" class="form-group has-feedback">
                                    <label for="senha">SENHA</label>
                                    <input id="senha" name="senha" type="password" class="form-control" placeholder="Senha"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div id="div_confirmar" class="form-group has-feedback">
                                    <label for="confirmar">CONFIRMAR</label>
                                    <input id="confirmar" name="confirmar" type="password" class="form-control" placeholder="Confirmar Senha"/>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </div>
                </form>       
            </div><!-- /.box -->

        </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class='control-sidebar-bg'></div>
</div><!-- ./wrapper -->

<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape.php'); ?>

<script>
    $(function() {
        //Money Euro
        $("[data-mask]").inputmask();
    });
</script>

<!-- JS DO REGISTER -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/pessoas/cadastro.js"></script>