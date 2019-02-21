<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo.php');

//LIMPANDO A SESSION DAS FOTOS
$_SESSION['foto_origin'] = '';
$_SESSION['foto_origin_nome'] = '';
$_SESSION['foto_cut'] = '';
$_SESSION['foto_cut_nome'] = '';

$id = antiSQL(@$_GET['id']);

if (is_numeric($id)) {//VERIFICA SE O ID PASSADO POR PARÂMETRO É UM NÚMERO
    $sql = $db->prepare("SELECT destaque, foto_cortada, id, nome, valor, categoria_id, pontuacao_recebida, pontuacao_cobrada
                FROM produtos
                WHERE id = ?");
    $sql->bindParam(1, $id);
    $sql->execute();
    $reg = $sql->fetch(PDO::FETCH_BOTH);
} else {
    $reg = 0;
    $id = "";
}
?>

<!-- CSS DO PLUGIN DE UPLOAD DE FOTOS -->
<link href="<?= PLUGINS_FOLDER; ?>upload_foto/dist/cropper.min.css" rel="stylesheet">
<link href="<?= PLUGINS_FOLDER; ?>upload_foto/css/main.css" rel="stylesheet">
<!-- FIM DO PLUGIN -->

<div class="wrapper">
    <?php include($_SESSION['NOME_SISTEMA'] . 'template/header.php'); ?>
    <?php include($_SESSION['NOME_SISTEMA'] . 'template/sidebar.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Módulo
                <small>Produtos</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo PORTAL_URL; ?>view/geral/modulos.php"><i class="fa fa-dashboard"></i> <b>Módulos</b></a></li>
                <li><a href="<?php echo PORTAL_URL; ?>view/produtos/index.php"> <b>Produtos</b></a></li>
                <li class="active">Cadastro</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Formulário de Produto</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div id="crop-avatar">
                                <!-- Current avatar -->
                                <div class="avatar-view" title="Trocar o Foto">
                                    <?php
                                    if ($reg['foto_cortada'] != "") {
                                        ?>
                                        <img src="<?= PORTAL_URL . $reg['foto_cortada'] ?>" alt="Avatar"/>
                                        <?php
                                    } else {
                                        ?>
                                        <img src="<?= IMG_FOLDER; ?>produtos/produto-sem-imagem.gif" alt="Avatar"/>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <!-- Cropping modal -->
                                <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form class="avatar-form" action="crop.php" enctype="multipart/form-data" method="post">
                                                <div class="modal-header">
                                                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                                                    <h4 class="modal-title" id="avatar-modal-label">Trocar Foto</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="avatar-body">

                                                        <!-- Upload image and data -->
                                                        <div class="avatar-upload">
                                                            <input class="avatar-src" name="avatar_src" type="hidden">
                                                            <input class="avatar-data" name="avatar_data" type="hidden">
                                                            <label for="avatarInput">Local upload</label>
                                                            <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
                                                        </div>

                                                        <!-- Crop and preview -->
                                                        <div class="row">
                                                            <div class="col-md-9">
                                                                <div class="avatar-wrapper"></div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="avatar-preview preview-lg"></div>
                                                                <div class="avatar-preview preview-md"></div>
                                                                <div class="avatar-preview preview-sm"></div>
                                                            </div>
                                                        </div>

                                                        <div class="row avatar-btns">
                                                            <div class="col-md-9">
                                                                <div class="btn-group">
                                                                    <button class="btn btn-primary" data-method="rotate" data-option="-90" type="button" title="Rotate -90 degrees">Rotate Left</button>
                                                                    <button class="btn btn-primary" data-method="rotate" data-option="-15" type="button">-15deg</button>
                                                                    <button class="btn btn-primary" data-method="rotate" data-option="-30" type="button">-30deg</button>
                                                                    <button class="btn btn-primary" data-method="rotate" data-option="-45" type="button">-45deg</button>
                                                                </div>
                                                                <div class="btn-group">
                                                                    <button class="btn btn-primary" data-method="rotate" data-option="90" type="button" title="Rotate 90 degrees">Rotate Right</button>
                                                                    <button class="btn btn-primary" data-method="rotate" data-option="15" type="button">15deg</button>
                                                                    <button class="btn btn-primary" data-method="rotate" data-option="30" type="button">30deg</button>
                                                                    <button class="btn btn-primary" data-method="rotate" data-option="45" type="button">45deg</button>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button class="btn btn-primary btn-block avatar-save" type="submit">Salvar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="modal-footer">
                                                  <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                                                </div> -->
                                            </form>
                                        </div>
                                    </div>
                                </div><!-- /.modal -->

                                <!-- Loading state -->
                                <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <form id="form_produto" action="#" method="post">
                                <input type="hidden" id="codigo" name="codigo" value="<?php echo $reg['id']; ?>" />

                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="div_nome" class="form-group has-feedback">
                                            <label for="nome">NOME</label>
                                            <input id="nome" name="nome" type="text" class="form-control" placeholder="Nome completo" value="<?php echo $reg['nome']; ?>"/>
                                        </div>  
                                    </div>
                                    <div class="col-md-6">
                                        <div id="div_categoria" class="form-group has-feedback">
                                            <label for="categoria">CATEGORIA</label>
                                            <select id="categoria" name="categoria" placeholder="Categoria" class="ls-select">
                                                <option value="">Escolha a categoria</option>
                                                <?php
                                                $sql = $db->prepare("SELECT id, nome
                                                                    FROM categorias
                                                                    WHERE status = 1
                                                                    ORDER BY nome ASC");
                                                $sql->execute();
                                                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                    if ($linha['id'] == $reg['categoria_id']) {
                                                        ?>
                                                        <option selected="true" value="<?php echo $linha['id']; ?>"><?php echo $linha['nome']; ?></option>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <option value="<?php echo $linha['id']; ?>"><?php echo $linha['nome']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="div_ingredientes" class="form-group has-feedback">
                                            <label for="ingredientes">INGREDIENTES</label>
                                            <select id="ingredientes" name="ingredientes[]" placeholder="Ingredientes" class="ls-select" multiple>
                                                <?php
                                                $sql = $db->prepare("SELECT id, nome
                                                                    FROM ingredientes
                                                                    WHERE status = 1
                                                                    ORDER BY nome ASC");
                                                $sql->execute();
                                                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                    <option value="<?php echo $linha['id']; ?>"><?php echo $linha['nome']; ?></option>
                                                    <?php
                                                }
                                                //CARREGANDO OS INGREDIENTES QUE FORAM ADICIONADOS
                                                if (is_numeric($id) && $id != "") {
                                                    $result = $db->prepare("SELECT i.id, i.nome
                                                        FROM produtos_ingredientes pi
                                                        LEFT JOIN ingredientes AS i ON i.id = pi.ingrediente_id
                                                        WHERE pi.produto_id = ? ORDER BY i.nome ASC");
                                                    $result->bindValue(1, $id);
                                                    $result->execute();
                                                    while ($ingredientes = $result->fetch(PDO::FETCH_ASSOC)) {
                                                        ?>
                                                        <option selected="true" value='<?= $ingredientes['id']; ?>'><?= $ingredientes['nome']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div id="div_pontuacao">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h5>Pontuação:</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="radio radio-danger" id="div_tipo">
                                                <input <?php echo $reg['pontuacao_cobrada'] > 0 ? 'checked="true"' : ''; ?> class="flat-red" type="radio" id="pontuacao_sim" name="pontuacao" value="1" onclick="pontuacao(1)"/>
                                                <label for="pontuacao_sim">SIM</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="radio radio-danger" id="div_tipo">
                                                <input <?php echo $reg['pontuacao_cobrada'] > 0 ? '' : 'checked="true"'; ?> type="radio" class="flat-red" id="pontuacao_nao" name="pontuacao" value="0" onclick="pontuacao(0)"/>
                                                <label for="pontuacao_nao">NÃO</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div <?php echo $reg['pontuacao_cobrada'] > 0 ? '' : 'style="display:none"' ?> id="div_valor_pontuacao" class="form-group has-feedback row">
                                    <div class="col-md-4">
                                        <div id="div_pontuacao_cobrada" class="form-group has-feedback">
                                            <label for="valor_pontuacao">PONTUAÇÃO PARA COMPRA</label>
                                            <input id="valor_pontuacao" name="valor_pontuacao" type="text" class="form-control" placeholder="Valor" onkeypress="return SomenteNumero(event);" value="<?php echo $reg['pontuacao_cobrada']; ?>" maxlength="5"/>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div id="div_pontuacao_recebida" class="form-group has-feedback">
                                            <label for="valor_pontuacao">PONTUAÇÃO PARA RECEBER</label>
                                            <input id="valor_pontuacao_recebida" name="valor_pontuacao_recebida" type="text" class="form-control" placeholder="Valor" onkeypress="return SomenteNumero(event);" value="<?php echo $reg['pontuacao_recebida']; ?>" maxlength="5"/>
                                        </div>
                                    </div>
                                </div>

                                <div id="div_pontuacao">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h5>Destaque:</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="radio radio-danger" id="div_tipo">
                                                <input <?php echo $reg['destaque'] > 0 ? 'checked="true"' : ''; ?> class="flat-red" type="radio" id="destaque_sim" name="destaque" value="1" />
                                                <label for="destaque_sim">SIM</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="radio radio-danger" id="div_tipo">
                                                <input <?php echo $reg['destaque'] > 0 ? '' : 'checked="true"'; ?> type="radio" class="flat-red" id="destaque_nao" name="destaque" value="0" />
                                                <label for="destaque_nao">NÃO</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div id="div_valor" class="form-group has-feedback">
                                            <label for="valor">VALOR</label>
                                            <input id="valor" name="valor" type="text" class="form-control" placeholder="Valor" value="<?php echo fdec($reg['valor']); ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-success"><?php echo is_numeric($id) ? 'Atualizar' : 'Cadastrar' ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>        

                </div><!-- /.box-body -->
            </div><!-- /.box -->

        </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class='control-sidebar-bg'></div>
</div><!-- ./wrapper -->

<?php include($_SESSION['NOME_SISTEMA'] . 'template/rodape.php'); ?>

<!-- MÁSCARA PARA DINHEIRO -->
<script type="text/javascript" src="<?= ASSETS_FOLDER; ?>js/jquery.price_format.1.3.js"></script>
<!-- JAVASCRIPT BÁSICOS -->
<script src="<?= PLUGINS_FOLDER; ?>upload_foto/dist/cropper.min.js"></script>
<script src="<?= PLUGINS_FOLDER; ?>upload_foto/js/main.js"></script>

<script>
                                                $(function() {
                                                    $("input#valor").priceFormat({
                                                        prefix: '',
                                                        centsSeparator: ',',
                                                        thousandsSeparator: '.'
                                                    });
                                                });
</script>

<!-- JS DO REGISTER -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>scripts/produtos/cadastro.js"></script>