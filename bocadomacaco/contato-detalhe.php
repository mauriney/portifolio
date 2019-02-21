<?php
include("topo.php");
include("menu-lateral.php");
include_once('utils/contato/funcoes.php');


if (is_numeric($_GET['id']) && antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $id = $_GET['id'];
    $result = $db->prepare("SELECT *"
            . " FROM tb_bsc_contato "
            . "WHERE idcontato = ? GROUP BY idcontato");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_contato = $result->fetch(PDO::FETCH_ASSOC);
    $contato_id = $dados_contato['idcontato'];
    $nome = utf8_encode($dados_contato['nome']);
    $dia = $dados_contato['dia'];
    $mes = $dados_contato['mes'];
    $instituicao = utf8_encode($dados_contato['instituicao']);
    $departamento = utf8_encode($dados_contato['departamento']);
    $cargo = utf8_encode($dados_contato['cargo']);
    $email = $dados_contato['email'];
    $observacoes = utf8_encode($dados_contato['obs']);
    $data_cadastro = obterDataBRTimestamp($dados_contato['data_cadastro']);
    $celular_principal = $dados_contato['celular_principal'];

    //INFORMAÇÕES DE ENDEREÇO
    $result2 = $db->prepare("SELECT * FROM tb_bsc_endereco WHERE idcontato = ?");
    $result2->bindValue(1, $id);
    $result2->execute();
    $dados_endereco = $result2->fetch(PDO::FETCH_ASSOC);
    $cep = $dados_endereco['cep'];
    $endereco = $dados_endereco['endereco'];
    $numero = $dados_endereco['numero'];
    $complemento = $dados_endereco['complemento'];
    $bairro = $dados_endereco['bairro'];
    $municipio_id = $dados_endereco['idcidade'];
    $municipio_nome = utf8_encode(nome_cidade_id($municipio_id));
    $estado_nome = utf8_encode(nome_estado_id(estado_municipio($municipio_id)));
    $tipo = $dados_endereco['tipo'];

    $referencia = utf8_encode(pesquisar("nome", "tb_bsc_contato", "idcontato", "=", $dados_contato['referencia'], ""));
    $sql_tel = $db->prepare("SELECT telefone FROM tb_bsc_telefone WHERE idcontato = ?");
    $sql_tel->bindValue(1, $dados_contato['referencia']);
    $sql_tel->execute();
    $dados_tel = $sql_tel->fetch(PDO::FETCH_ASSOC);
} else {
    echo "<script 'text/javascript'>window.location = 'contato-painel.php';</script>";
}
?>

<!-- Painel de Demandas -->
<div class="container conteudo-bd">

    <div class="botoes-acao">
        <?php
        if($_SESSION['acesso'] == 1)
        ?>
        <a href="contato-cadastro.php?id=<?php echo $id; ?>" title="Editar" class="editar"></a>
        <?php
        if ($_SESSION['acesso'] == 1 ) {// SOMENTE ADMINISTRADOR
            ?>
            <a href="#" title="Excluir" class="excluir" onclick="remover(<?php echo $dados_contato['idcontato']; ?>)"></a>
            <?php
        }
        //NIRO GIT
        ?>
        <a target="_blank" href="imprimir-contato-detalhe.php?id=<?php echo $contato_id; ?>" title="Imprimir" class="imprimir"></a>
    </div>

    <div class="detalhe-contato view">
        <h1><?php echo $nome; ?></h1>
        <!-- linha -->
        <div class="row">
            <div class="col-md-3">
                <span class="titulo">Dia de Nascimento</span>
                <span class="conteudo-bd"><?php echo $dia; ?></span>
            </div>
            <div class="col-md-3">
                <span class="titulo">Mês de Nascimento</span>
                <span class="conteudo-bd"><?php echo getMes($mes); ?></span>
            </div>
        </div>
        <!-- linha -->
        <div class="row">
            <div class="col-md-4">
                <span class="titulo">Instituição</span>
                <span class="conteudo-bd"><?php echo $instituicao; ?></span>
            </div>
            <div class="col-md-4">
                <span class="titulo">Departamento</span>
                <span class="conteudo-bd"><?php echo $departamento; ?></span>
            </div>
            <div class="col-md-4">
                <span class="titulo">Cargo</span>
                <span class="conteudo-bd"><?php echo $cargo; ?></span>
            </div>
        </div>
        <!-- linha -->
        <div class="row">
            <div class="col-md-4">
                <span class="titulo">E-mail</span>
                <span class="conteudo-bd"><?php echo $email; ?></span>
            </div>
        </div>

        <h2>Telefone(s)</h2>
        <!-- linha -->
        <div class="row">
            <?php
            if ($celular_principal != "") {
                ?>

                <div class="col-md-4">
                    <span class="titulo">Celular Principal</span>
                    <span class="conteudo-bd"><?php echo $celular_principal; ?></span>
                </div>

                <?php
            }
            ?>
            <?php
            $result5 = $db->prepare("SELECT * FROM tb_bsc_telefone WHERE idcontato = ? AND telefone <> ''");
            $result5->bindValue(1, $contato_id);
            $result5->execute();
            while ($telefone = $result5->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="col-md-4">
                    <span class="titulo">Telefone</span>
                    <span class="conteudo-bd"><?php echo $telefone['telefone']; ?></span>
                </div>
                <?php
            }
            ?>
        </div>

        <h2>Endereço(s)</h2>
        <!-- linha -->
        <div class="row">
            <div class="col-md-4">
                <span class="titulo">CEP</span>
                <span class="conteudo-bd"><?php echo $cep; ?></span>
            </div>
        </div>
        <!-- linha -->
        <div class="row">
            <div class="col-md-8">
                <span class="titulo">Endereço</span>
                <span class="conteudo-bd"><?php echo $endereco; ?></span>
            </div>
            <div class="col-md-4">
                <span class="titulo">Número</span>
                <span class="conteudo-bd"><?php echo $numero; ?></span>
            </div>
        </div>
        <!-- linha -->
        <div class="row">
            <div class="col-md-4">
                <span class="titulo">Complemento</span>
                <span class="conteudo-bd"><?php echo $complemento; ?></span>
            </div>
            <div class="col-md-4">
                <span class="titulo">Bairro</span>
                <span class="conteudo-bd"><?php echo $bairro; ?></span>
            </div>
        </div>
        <!-- linha -->
        <div class="row">
            <div class="col-md-4">
                <span class="titulo">Estado</span>
                <span class="conteudo-bd"><?php echo $estado_nome; ?></span>
            </div>
            <div class="col-md-4">
                <span class="titulo">Município</span>
                <span class="conteudo-bd"><?php echo $municipio_nome; ?></span>
            </div>
            <div class="col-md-4">
                <span class="titulo">Tipo</span>
                <span class="conteudo-bd"><?php echo tipo_endereco($tipo); ?></span>
            </div>
        </div>

        <h2>Outras Informações</h2>
        <!-- linha -->
        <div class="row">
            <div class="col-md-12">
                <span class="titulo">Observação</span>
                <span class="conteudo-bd"><?php echo $observacoes; ?></span>
            </div>
        </div>
        <!-- linha -->
        <div class="row">

            <?php
            if ($referencia != "") {
                ?>
                <div class="col-md-4">
                    <span class="titulo">Referência de Contato</span>
                    <span class="conteudo-bd"><?php echo $referencia . " - " . $dados_tel['telefone']; ?></span>
                </div>
                <?php
            }
            ?>

            <div class="col-md-4">
                <span class="titulo">Data Cadastro</span>
                <span class="conteudo-bd"><?php echo $data_cadastro; ?></span>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/contato/contato-detalhe.js"></script>

<script>

                var headertext = [];
                var headers = document.querySelectorAll(".tabela th"),
                        tablerows = document.querySelectorAll(".tabela th"),
                        tablebody = document.querySelector(".tabela tbody");
                for (var i = 0; i < headers.length; i++) {
                    var current = headers[i];
                    headertext.push(current.textContent.replace(/\r?\n|\r/, ""));
                }
                for (var i = 0, row; row = tablebody.rows[i]; i++) {
                    for (var j = 0, col; col = row.cells[j]; j++) {
                        col.setAttribute("data-th", headertext[j]);
                    }
                }
</script>

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>
