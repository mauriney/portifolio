<?php
include("topo.php");
include_once('utils/agenda/funcoes.php');
include("menu-lateral.php");
?>

<?php
$contador = @$_POST["carre"];
if ($contador == "") {
    $contador = 1;
}
if (isset($_POST['carre'])) {
    $contador+=1;
    echo "<script>document.getElementById('voltar_pagina').setAttribute('onclick', 'window.history.go(-$contador)');</script>";
}
?>

<div id="carregando" class="box" style="display: none">
    <div class="clock"></div>
</div>

<!-- Painel de Demandas -->
<div class="container conteudo">

    <?php
    //CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
    if (isset($_POST['mensagem']) && @$_SESSION['mensagem'] == "OK") {
        @$_SESSION['mensagem'] = "NO";
        echo '<div id="div_success" class="alert alert-success" style="display: none;"><button class="close" data-dismiss="alert"></button><b>Sucesso:</b> ' . $_POST['mensagem'] . '</div>';
    }
    ?>

    <div class="botoes-acao">
        <a href="marcar-agenda-cadastro.php" title="Marcar Agenda" class="adicionar"></a>
        <a id="mostrar-busca" href="#" title="Filtrar" class="filtrar"></a>
        <a title="Imprimir" class="imprimir" style="cursor: pointer" onclick="document.getElementById('formulario_imprimir').submit();
                return false;"></a>
    </div>

    <p>&nbsp;</p><p>&nbsp;</p>

    <div id="filtro" class="filtro" style="display: none">
        <form id="form_agendacompleta" action="#" method="post">
            <input type="hidden" id="carre" name="carre" value="<?php echo $contador; ?>"/>
            <!-- linha -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inicio">Data Inicial</label>
                        <input type="text" name="inicio" id="inicio" class="form-control data" placeholder="Início" value="<?php echo @$_POST['inicio']; ?>"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="final">Data Final</label>
                        <input type="text" name="fim" id="fim" class="form-control data" placeholder="Fim" value="<?php echo @$_POST['fim']; ?>"/>
                    </div>
                </div>
            </div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="segmento">Segmento</label>
                        <select name="segmento" id="segmento" class="ls-select" placeholder="Segmento">
                            <option value="">Escolha o segmento</option>
                            <?php
                            $result = $db->prepare("SELECT IdSegmento, Descricao 
	                             FROM tb_bsc_segmento
	                             ORDER BY Descricao ASC");
                            $result->execute();
                            while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option label='<?php echo $estado['Descricao']; ?>' value='<?php echo $estado['IdSegmento']; ?>'><?php echo utf8_encode($estado['Descricao']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-4">
                    <label for="estado">Estado </label>
                    <select id="estado" name="estado" placeholder="Estado" class="ls-select">
                        <option value="">Escolha o estado</option>
                        <?php
                        $result = $db->prepare("SELECT nome, idestado, sigla 
	                             FROM estado
	                             ORDER BY nome ASC");
                        $result->execute();
                        while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                            if ($estado['idestado'] == 1) {
                                ?>
                                <option selected="true" label='<?php echo $estado['sigla']; ?>' value='<?php echo $estado['idestado']; ?>'><?php echo utf8_encode($estado['nome']); ?></option>
                                <?php
                            } else {
                                ?>
                                <option label='<?php echo $estado['sigla']; ?>' value='<?php echo $estado['idestado']; ?>'><?php echo utf8_encode($estado['nome']); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="municipio">Município </label>
                    <select onchange="vf_bairro(this.value)" id="municipio" name="municipio" placeholder="Município" class="ls-select">
                        <option value="">Escolha primeiro o estado</option>
                        <?php
                        $result2 = $db->prepare("SELECT nome, idcidade 
	                             FROM cidade WHERE idestado = 1
	                             ORDER BY nome ASC");
                        $result2->execute();
                        while ($cidade = $result2->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <option value='<?php echo $cidade['idcidade']; ?>'><?php echo utf8_encode($cidade['nome']); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div id="mostrar-bairro" class="col-md-4" style="display: none">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <select name="bairro" id="bairro" class="ls-select" placeholder="Bairro">
                            <option value="">Escolha o bairro</option>
                            <?php
                            $result = $db->prepare("SELECT idbairro, nome FROM tb_bsc_bairro ORDER by nome");
                            $result->execute();
                            while ($value = $result->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value='<?php echo $value['idbairro']; ?>'><?php echo utf8_encode($value['nome']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="demandante">Demandante</label>
                        <input type="text" name="demandante" id="demandante" class="form-control" placeholder="Demandante" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="quem_vai">Quem Vai</label>
                        <select name="quem_vai" id="quem_vai" class="ls-select" placeholder="Quem Vai">
                            <option value="">Escolha quem vai</option>
                            <?php
                            $sql1 = $db->prepare("SELECT u.IdUsuario AS id_participante, u.Nome as participante
                                 FROM tb_bsc_agenda a
                                 LEFT JOIN x_agenda_participante AS p ON p.IdAgenda = a.IdAgenda 
                                 LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = p.IdContato 
                                 WHERE a.Confirmado = 1 GROUP BY u.IdUsuario");
                            $sql1->execute();
                            while ($quemvai = $sql1->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value='<?php echo $quemvai['id_participante']; ?>'><?php echo utf8_encode($quemvai['participante']); ?></option>
                                <?php
                            }
                            ?>
                        </select>    
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="confirmados">Confirmados </label>
                    <select id="confirmados" name="confirmados" placeholder="Confirmados" class="ls-select">
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                </div>
            </div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="palavra-chave">Palavra-Chave</label>
                        <input type="text" name="palavra" id="palavra" class="form-control" placeholder="Palavra Chave" />
                    </div>
                </div>
            </div>
            <button type="button" name="enviar" id="enviar" class="btn btn-redondo btn-buscar" onclick="carregar()">buscar</button>
        </form>
    </div>

    <?php
    $campo1 = "DataAgenda >= CURDATE()";
    $pesquisa1 = "DataAgenda >= CURDATE()";
    $valor1 = "";
    $campo2 = "";
    $pesquisa2 = "";
    $valor2 = "";
    $campo3 = "";
    $pesquisa3 = "";
    $valor3 = "";
    $campo4 = "";
    $pesquisa4 = "";
    $valor4 = "";
    $campo5 = "";
    $pesquisa5 = "";
    $valor5 = "";
    $campo6 = "";
    $pesquisa6 = "";
    $valor6 = "";
    $campo7 = "";
    $pesquisa7 = "";
    $valor7 = "";
    $campo8 = "";
    $pesquisa8 = "";
    $valor8 = "";
    $campo9 = "";
    $pesquisa9 = "";
    $valor9 = "";

    if (@$_POST['palavra'] != "" ||
            @$_POST['demandante'] != "" ||
            @$_POST['municipio'] > 0 ||
            @$_POST['estado'] > 0 ||
            @$_POST['segmento'] > 0 ||
            @$_POST['fim'] != "" && strlen(@$_POST['fim']) > 0 ||
            @$_POST['inicio'] != "" && strlen(@$_POST['inicio']) > 0 ||
            @$_POST['confirmados'] != "" && is_numeric(@$_POST['confirmados']) ||
            @$_POST['palavra'] != "" && strlen(@$_POST['palavra']) > 0) {

        if (@$_POST['fim'] == "" && @$_POST['inicio'] != "" && strlen(@$_POST['inicio']) > 0) {
            $campo1 = "DataAgenda BETWEEN (?) AND (NOW())";
            $valor1 = "" . convertDataBR2ISO(@$_POST['inicio']) . "";
            $pesquisa1 = "DataAgenda BETWEEN ('" . convertDataBR2ISO(@$_POST['inicio']) . "') AND (NOW())";
        }

        if (@$_POST['inicio'] == "" && @$_POST['fim'] != "" && strlen(@$_POST['fim']) > 0) {
            $campo2 = "AND DataAgenda BETWEEN (NOW()) AND (?)";
            $valor2 = "" . convertDataBR2ISO(@$_POST['fim']) . "";
            $pesquisa2 = "AND DataAgenda BETWEEN (NOW()) AND ('" . convertDataBR2ISO(@$_POST['fim']) . "')";
        }

        if (@$_POST['inicio'] != "" && strlen(@$_POST['inicio']) > 0 && @$_POST['fim'] != "" && strlen(@$_POST['fim']) > 0) {
            $campo1 = "DataAgenda BETWEEN (?) AND (?)";
            $valor1 = "" . convertDataBR2ISO(@$_POST['inicio']) . "";
            $valor2 = "" . convertDataBR2ISO(@$_POST['fim']) . "";
            $pesquisa1 = "DataAgenda BETWEEN ('" . convertDataBR2ISO(@$_POST['inicio']) . "') AND ('" . convertDataBR2ISO(@$_POST['fim']) . "')";
        }

        if (@$_POST['segmento'] > 0) {
            $campo3 = "AND seg.IdSegmento = ?";
            $valor3 = "" . @$_POST['segmento'] . "";
            $pesquisa3 = "AND seg.IdSegmento = '" . @$_POST['segmento'] . "'";
        }

        if (@$_POST['municipio'] > 0) {
            $campo4 = "AND a.IdMunicipio = ?";
            $valor4 = "" . @$_POST['municipio'] . "";
            $pesquisa4 = "AND a.IdMunicipio = '" . @$_POST['municipio'] . "'";
        }

        if (@$_POST['demandante'] != "") {
            $campo5 = "AND demandante LIKE ?";
            $valor5 = "%" . @$_POST['demandante'] . "%";
            $pesquisa5 = "AND demandante LIKE '%" . @$_POST['demandante'] . "%'";
        }

        if (is_numeric(@$_POST['confirmados'])) {
            $campo6 = "AND Confirmado LIKE ?";
            $valor6 = "" . @$_POST['confirmados'] . "";
            $pesquisa6 = "AND Confirmado LIKE '" . @$_POST['confirmados'] . "'";
        }

        if (@$_POST['palavra'] != "") {
            $campo7 = "AND Pauta LIKE ?";
            $valor7 = "%" . @$_POST['palavra'] . "%";
            $pesquisa7 = "AND Pauta LIKE '%" . @$_POST['palavra'] . "%'";
        }

        if (@$_POST['bairro'] > 0) {
            $campo8 = "AND a.bairro = ?";
            $valor8 = "" . @$_POST['bairro'] . "";
            $pesquisa8 = "AND a.bairro = '" . @$_POST['bairro'] . "'";
        }

        if (@$_POST['quem_vai'] > 0 && @$_POST['quem_vai'] != "") {
            $campo9 = "AND u.IdUsuario = ?";
            $valor9 = "" . @$_POST['quem_vai'] . "";
            $pesquisa9 = "AND u.IdUsuario = " . @$_POST['quem_vai'] . "";
        }
    }

    $contador = 1;

    $sql = $db->prepare("SELECT a.IdUsuario, a.IdAgenda, Demandante, Contato, LocalEvento, DataAgenda, HoraAgenda, Pauta, Confirmado, p.Cor, p.IdPrioridade, atencao, p.Descricao as descr, m.nome, a.IdMunicipio
		FROM tb_bsc_agenda a 
		LEFT JOIN cidade m ON m.idcidade = a.IdMunicipio 
		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade 
		LEFT JOIN tb_bsc_acesso_agenda aa ON aa.IdAgenda = a.IdAgenda 
		LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.bairro
		LEFT JOIN tb_bsc_segmento seg ON seg.IdSegmento = a.IdSegmento
		LEFT JOIN tb_bsc_aviso_agenda avi ON avi.idagenda = a.IdAgenda
		LEFT JOIN tb_bsc_aviso_agenda avi2 ON avi2.idagenda = a.recorrente
                LEFT JOIN x_agenda_participante AS pa ON pa.IdAgenda = a.IdAgenda 
                LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = pa.IdContato
		WHERE $campo1 $campo2 $campo3 $campo4 $campo5 $campo6 $campo7 $campo8 $campo9 GROUP BY a.IdAgenda ORDER BY DataAgenda, HoraAgenda ASC");

    if ($valor1 != "") {
        $sql->bindValue($contador, $valor1);
        $contador++;
    }
    if ($valor2 != "") {
        $sql->bindValue($contador, $valor2);
        $contador++;
    }
    if ($valor3 != "") {
        $sql->bindValue($contador, $valor3);
        $contador++;
    }
    if ($valor4 != "") {
        $sql->bindValue($contador, $valor4);
        $contador++;
    }
    if ($valor5 != "") {
        $sql->bindValue($contador, $valor5);
        $contador++;
    }
    if ($valor6 != "") {
        $sql->bindValue($contador, $valor6);
        $contador++;
    }
    if ($valor7 != "") {
        $sql->bindValue($contador, $valor7);
        $contador++;
    }
    if ($valor8 != "") {
        $sql->bindValue($contador, $valor8);
        $contador++;
    }
    if ($valor9 != "") {
        $sql->bindValue($contador, $valor9);
        $contador++;
    }

    $sql->execute();
    $dataaux = '';
    if ($sql->rowCount() > 0) {
        ?>
        <form id="formulario_imprimir" action="imprimir-agendacompleta-painel.php" method="post" target="_blank">
            <table class="tabela">
                <thead>
                    <tr>
                        <th><i class="hora-lista"></i><th>Pauta<th>Solicitante<th>Local<th>Quem Vai<th>Prioridade<th>Confirmado<th>
                <tbody id="pagina">

                    <?php
                    $codigo = "SELECT a.IdUsuario, a.IdAgenda, Demandante, Contato, LocalEvento, DataAgenda, HoraAgenda, Pauta, Confirmado, p.Cor, p.IdPrioridade, atencao, p.Descricao as descr, m.nome, a.IdMunicipio
		FROM tb_bsc_agenda a 
		LEFT JOIN cidade m ON m.idcidade = a.IdMunicipio 
		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade 
		LEFT JOIN tb_bsc_acesso_agenda aa ON aa.IdAgenda = a.IdAgenda 
		LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.bairro
		LEFT JOIN tb_bsc_segmento seg ON seg.IdSegmento = a.IdSegmento
		LEFT JOIN tb_bsc_aviso_agenda avi ON avi.idagenda = a.IdAgenda
		LEFT JOIN tb_bsc_aviso_agenda avi2 ON avi2.idagenda = a.recorrente
                LEFT JOIN x_agenda_participante AS pa ON pa.IdAgenda = a.IdAgenda 
                LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = pa.IdContato
		WHERE $pesquisa1 $pesquisa2 $pesquisa3 $pesquisa4 $pesquisa5 $pesquisa6 $pesquisa7 $pesquisa8 $pesquisa9 GROUP BY a.IdAgenda ORDER BY DataAgenda, HoraAgenda ASC";
                    ?>
                <input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>"/>


                <?php
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

                    if ($_SESSION['acesso'] == 1 || vf_participante($linha['IdAgenda'], $_SESSION['id'])) {//MOSTRA SÓ AS AGENDAS DO RESPONSÁVEL OU MOSTRA TODAS PARA O ADMINISTRADOR
                       
                        /*
                        
                        $interm = 0;
                        $basic = 0;

                        if (is_numeric(pesquisa2("IdAgenda", "tb_bsc_acesso_agenda", "IdAgenda = ?", $linha['IdAgenda'], " AND IdAcesso = ?", 2, "", "", "", "", ""))) {
                            $interm = 1;
                        }
                        if (is_numeric(pesquisa2("IdAgenda", "tb_bsc_acesso_agenda", "IdAgenda = ?", $linha['IdAgenda'], " AND IdAcesso = ?", 3, "", "", "", "", ""))) {
                            $basic = 1;
                        }

                        if ($_SESSION['acesso'] == 1 || $_SESSION['acesso'] == 2 && $interm == 1 ||
                                $_SESSION['acesso'] == 3 && $basic == 1) {*/

                            $participante = agenda_participantes($linha['IdAgenda']);

                            $cores = "";
                            if ($linha['atencao'] == 1) {
                                $cores = 'style="background-color: #FFFFD0"';
                            }

                            if ($dataaux != $linha['DataAgenda']) {
                                ?>
                                <tr>
                                    <td colspan="8" class="dia-mes"><span><?php echo dataExtensoComAno(data_volta($linha['DataAgenda'])) ?> - <?php echo hoje(data_volta($linha['DataAgenda'])) ?></span>
                                        <?php
                                    }
                                    $dataaux = $linha['DataAgenda'];
                                    ?>
                            <tr>
                                <td <?php echo $cores; ?> data-th="#"><?php echo hora($linha['HoraAgenda']); ?>
                                <td <?php echo $cores; ?> data-th="Pauta"><?php echo $linha['Pauta']; ?>
                                <td <?php echo $cores; ?> data-th="Solicitante"><?php echo $linha['Demandante']; ?>
                                <td <?php echo $cores; ?> data-th="Local"><?php echo $linha['LocalEvento']; ?>
                                <td <?php echo $cores; ?> data-th="Quem Vai"><?php echo utf8_encode($participante); ?>
                                <td <?php echo $cores; ?> data-th="Prioridade"><span class="prioridade-lista <?php echo prioridade_cor($linha['IdPrioridade']); ?>-lista"><?php echo prioridade($linha['IdPrioridade']); ?></span>
                                <td <?php echo $cores; ?> data-th="Segmento"><span class="confirmado <?php echo $linha['Confirmado'] == 1 ? 'sim' : 'nao'; ?>-lista"><?php echo $linha['Confirmado'] == 1 ? 'Sim' : 'Não'; ?></span>
                                <td <?php echo $cores; ?> data-th="Link"><a href="agendacompleta-detalhe.php?id=<?php echo $linha['IdAgenda']; ?>" title="Agendar" class="visualizar">Visualizar</a>
                                    <?php
                                //}
                            }
                        }
                        ?>			   					   
            </table>
        </form>
        <?php
    } else {
        echo "<div style='diplay:block; clear: both; width: 100%; text-align: center'>Nenhum resultado encontrado</div>";
    }
    ?>
</div>


<?php include("rodape.php") ?>

<script type="text/javascript" src="js/agenda/agendacompleta-painel.js"></script>

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

