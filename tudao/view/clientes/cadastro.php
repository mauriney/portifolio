<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo.php');

$id = antiSQL(@$_GET['id']);

if (is_numeric($id)) {//VERIFICA SE O ID PASSADO POR PARÂMETRO É UM NÚMERO
    $sql = $db->prepare("SELECT p.cidade_id, p.id, p.nome, p.contato1, p.contato2, p.sexo, p.nascimento, p.email, p.cpf, p.cep, p.bairro, 
                p.endereco, cid.nome AS cidade
                FROM clientes c
                LEFT JOIN pessoas AS p ON p.id = c.pessoa_id
                LEFT JOIN cidade AS cid ON cid.id = p.cidade_id
                WHERE c.id = ?");
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
                <small>Cliente</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo PORTAL_URL; ?>view/geral/modulos.php"><i class="fa fa-dashboard"></i> <b>Módulos</b></a></li>
                <li><a href="<?php echo PORTAL_URL; ?>view/clientes/index.php"> <b>Clientes</b></a></li>
                <li class="active">Cadastro</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Formulário de Cliente</h3>
                </div><!-- /.box-header -->

                <form id="form_cliente" action="#" method="post">
                    <div class="box-body">
                        <input type="hidden" id="codigo" name="codigo" value="<?php echo $reg['id']; ?>" />
                        <div class="row">
                            <div class="col-md-12">
                                <div id="div_nome" class="form-group has-feedback">
                                    <label for="nome">NOME</label>
                                    <input id="nome" name="nome" type="text" class="form-control" placeholder="Nome completo" value="<?php echo $reg['nome']; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div id="div_sexo" class="form-group has-feedback">
                                    <label for="sexo">SEXO</label>
                                    <select id="sexo" name="sexo" class="ls-select">
                                        <option value="">Escolha o sexo</option>
                                        <?php
                                        if ($reg['sexo'] == '2') {
                                            ?>
                                            <option value="1">Masculino</option>
                                            <option selected="true" value="2">Feminino</option>
                                            <?php
                                        } else {
                                            ?>
                                            <option selected="true" value="1">Masculino</option>
                                            <option  value="2">Feminino</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div id="nascimento" class="form-group">
                                    <label for="nascimento">DATA DE NASCIMENTO</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input id="nascimento" name="nascimento" type="text" class="form-control pull-right datepicker" data-inputmask='"mask": "99/99/9999"' data-mask value="<?php echo obterDataBRTimestamp($reg['nascimento']); ?>"/>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>
                            <div class="col-md-4">
                                <div id="div_cpf" class="form-group has-feedback">
                                    <label for="cpf">CPF</label>
                                    <input id="cpf" name="cpf" type="text" class="form-control" placeholder="CPF" data-inputmask='"mask": "999.999.999-99"' data-mask value="<?php echo $reg['cpf']; ?>"/>
                                </div>
                            </div>
                        </div>
                        <h2>Contato</h2>
                        <div class="row">
                            <div class="col-md-4">
                                <div id="div_contato" class="form-group has-feedback">
                                    <label for="contato">TELEFONE RESIDENCIAL</label>
                                    <input id="contato" name="contato" type="text" class="form-control" placeholder="Telefone Residêncial" data-inputmask='"mask": "(99) 9999-9999"' data-mask value="<?php echo $reg['contato1']; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div id="div_contato" class="form-group has-feedback">
                                    <label for="contato2">CELULAR</label>
                                    <input id="contato2" name="contato2" type="text" class="form-control" placeholder="Telefone Celular" data-inputmask='"mask": "(99) 9999-9999"' data-mask value="<?php echo $reg['contato2']; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div id="div_email" class="form-group has-feedback">
                                    <label for="email">E-MAIL</label>
                                    <input id="email" name="email" type="email" class="form-control" placeholder="E-mail" value="<?php echo $reg['email']; ?>"/>
                                </div>
                            </div>
                        </div>
                        <h2>Endereço</h2>
                        <div class="row">
                            <div class="col-md-4">
                                <div id="div_cep" class="form-group has-feedback">
                                    <label for="cep">CEP</label>
                                    <input id="cep" name="cep" type="text" class="form-control" placeholder="CEP" data-inputmask='"mask": "99.999-999"' data-mask value="<?php echo $reg['cep']; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div id="div_bairro" class="form-group has-feedback">
                                    <label for="bairro">BAIRRO</label>
                                    <input id="bairro" name="bairro" type="text" class="form-control" placeholder="Bairro" value="<?php echo $reg['bairro']; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div id="div_endereco" class="form-group has-feedback">
                                    <label for="endereco">ENDEREÇO</label>
                                    <input id="endereco" name="endereco" type="text" class="form-control" placeholder="Endereço" value="<?php echo $reg['endereco']; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div id="div_cidade" class="form-group has-feedback">
                                    <label for="cidade">CIDADE</label>
                                    <select class="ls-select" id="cidade" name="cidade">
                                        <option value="">Escolha uma cidade</option>
                                        <?php
                                        $sql = $db->prepare("SELECT id, nome FROM cidade WHERE estado_id = 1");
                                        $sql->execute();
                                        while ($cidade = $sql->fetch(PDO::FETCH_ASSOC)) {
                                            if ($cidade['id'] == $reg['cidade_id']) {
                                                ?>
                                                <option selected="true" value="<?php echo $cidade['id']; ?>"><?php echo $cidade['nome']; ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value="<?php echo $cidade['id']; ?>"><?php echo $cidade['nome']; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-success"><?php echo is_numeric($id) ? 'Atualizar' : 'Cadastrar' ?></button>
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
        //Date range picker
        //$('#nascimento').daterangepicker();
        //Money Euro
        $("[data-mask]").inputmask();
    });
</script>

<!-- JS DO REGISTER -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/clientes/cadastro.js"></script>