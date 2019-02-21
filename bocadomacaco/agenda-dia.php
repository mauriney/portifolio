<?php
include("topo.php");
include_once('utils/agenda/funcoes.php');
include("menu-lateral.php");

$dia = isset($_REQUEST['dia']) ? $_REQUEST['dia'] : 'hoje';
$opd = $dia == 'hoje' ? date('d') : date('d') + 1;
$dd = date('Y-m-d', mktime(0, 0, 0, date('m'), $opd, date('Y')));
?>

<script type="text/javascript">

    function printDiv() {
        //Get the HTML of div

        var result = "";
        var divElements1 = document.getElementById("div1").innerHTML;
        var divElements2 = document.getElementById("div2").innerHTML;
        if ($("#vf_div1").val() == 1) {
            result += divElements1;
        }

        result += divElements2;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;
        //Reset the page's HTML with div's HTML only
        document.body.innerHTML =
                "<html><body>" +
                result + "</body></html>";
        //Print Page
        window.print();
        //Restore orignal HTML
        document.body.innerHTML = oldPage;
    }
</script>

<form id="form_agenda_dia">
    <!-- Painel de Demandas -->
    <div class="container conteudo">

        <div class="botoes-acao">
            <a href="#" title="Imprimir" class="imprimir" onclick="printDiv()"></a>
        </div>
        <p>&nbsp;</p>
        <div class="row">
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
                                 WHERE a.DataAgenda = ? AND a.Confirmado = 1 GROUP BY u.IdUsuario");
                        $sql1->bindValue(1, $dd);
                        $sql1->execute();
                        while ($quemvai = $sql1->fetch(PDO::FETCH_ASSOC)) {
                            if ($_SESSION['acesso'] == 1 || $quemvai['id_participante'] == $_SESSION['id']) {//MOSTRA SÓ AS AGENDAS DO RESPONSÁVEL OU MOSTRA TODAS PARA O ADMINISTRADOR
                                ?>
                                <option value='<?php echo $quemvai['id_participante']; ?>'><?php echo utf8_encode($quemvai['participante']); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <p>&nbsp;</p>
        <div id="div1">
            <?php
            $cont = 1;
            $sql = $db->prepare("SELECT a.IdUsuario, a.IdAgenda, Demandante, Contato, LocalEvento, DataAgenda, HoraAgenda, Pauta, Confirmado, p.Cor, p.IdPrioridade, atencao, p.Descricao as descr, a.IdMunicipio
                    FROM tb_bsc_agenda a 
                    LEFT JOIN tb_bsc_municipios m ON m.IdMunicipio = a.IdMunicipio 
                    LEFT JOIN tb_bsc_acesso_agenda aa ON aa.IdAgenda = a.IdAgenda 
                    LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade 
                    LEFT JOIN tb_bsc_contato cont ON cont.idcontato = a.contato_participante 
                    LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.bairro
                    WHERE DataAgenda = ? AND Confirmado = 1
                    GROUP BY a.IdAgenda ORDER BY HoraAgenda ASC");
            $sql->bindValue(1, $dd);
            $sql->execute();
            if ($sql->rowCount() > 0) {
                ?>
                <input type="hidden" id="vf_div1" name="vf_div1" value="1"/>

                <table class="tabela">
                    <thead>
                        <tr>
                            <th><i class="hora-lista"></i><th>Pauta<th>Solicitante<th>Local<th>Quem Vai<th>Prioridade
                    <tbody id="pagina">
                        <?php
                        while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                            if ($_SESSION['acesso'] == 1 || vf_participante($linha['IdAgenda'], $_SESSION['id'])) {//MOSTRA SÓ AS AGENDAS DO RESPONSÁVEL OU MOSTRA TODAS PARA O ADMINISTRADOR
                                $participante = utf8_encode(agenda_participantes($linha['IdAgenda']));
                                ?>
                                <tr>
                                    <td data-th="#"><?php echo hora($linha['HoraAgenda']); ?>
                                    <td data-th="Pauta"><?php echo $linha['Pauta']; ?>
                                    <td data-th="Solicitante"><?php echo $linha['Demandante']; ?>
                                    <td data-th="Local"><?php echo $linha['LocalEvento']; ?>
                                    <td data-th="Quem Vai"><?php echo $participante; ?>
                                    <td data-th="Prioridade"><span class="prioridade-lista <?php echo prioridade_cor($linha['IdPrioridade']); ?>-lista"><?php echo prioridade($linha['IdPrioridade']); ?></span>
                                        <?php
                                    }
                                }
                                ?>

                </table>

                <?php
            }
            ?>
        </div>
        <div class="clearfix"></div>
        <div id="div2">
            <div class="row">
                <div class="col-sm-6">

                    <?php
                    $retorno = "";
                    $mes = date('m');
                    $mesExtenso = getMes($mes);
                    $ano = date('Y');
                    $dias = date("t", mktime(0, 0, 0, $mes, 1, $ano));
                    ?>

                    <table class="calendario primeiro">
                        <caption><?php echo strtoupper($mesExtenso) . ' | ' . '<span>' . $ano . '</span>' ?></caption>
                        <thead>
                            <tr>
                                <th>D</th>
                                <th>S</th>
                                <th>T</th>
                                <th>Q</th>
                                <th>Q</th>
                                <th>S</th>
                                <th>S</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 1; $i <= $dias; $i++) {
                                $diaDaSemana = date("N", mktime(0, 0, 0, $mes, $i, $ano));
                                $d = 0;
                                $ultimo = $dias;
                                $compara = date('Y-m-d', mktime(0, 0, 0, $mes, $i, $ano)); //Monta a data atual
                                $classe = '';
                                if ($dd == $compara) {
                                    $classe = "hoje";
                                }
                                if ($i == 1) {
                                    $colspan = $diaDaSemana;
                                    $retorno .= "<tr>";
                                    if ($colspan != 7) {
                                        $retorno .= "<td class='padding' colspan='" . $colspan . "'>&nbsp;</td>";
                                    }
                                } else {
                                    if ($diaDaSemana == 7) {
                                        $retorno .= "<tr>";
                                    }
                                }
                                $retorno .= "<td><span class='" . $classe . "'>$i</span></td>";
                                if ($dia == $ultimo) {
                                    $colspan = 7 - $diaDaSemana;
                                    if ($colspan != 0) {
                                        $retorno .= "<td class='padding' colspan='" . $colspan . "'></td>";
                                    }
                                }
                                if ($diaDaSemana == 6) {
                                    $retorno .= "</tr>";
                                }
                            }
                            echo $retorno;
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-6">
                    <!-- MÊS SEGUINTE -->
                    <?php
                    $dias = date("t", mktime(0, 0, 0, date('m') + 1, 1, $ano));
                    $mesExtenso = getMes(date("m", mktime(0, 0, 0, date('m') + 1, 1, $ano)));
                    ?>
                    <table class="calendario">
                        <caption><?php echo strtoupper($mesExtenso) . ' | ' . '<span>' . date("Y", mktime(0, 0, 0, date('m') + 1, 1, $ano)) . '</span>' ?></caption>
                        <thead>
                            <tr>
                                <th>D</th>
                                <th>S</th>
                                <th>T</th>
                                <th>Q</th>
                                <th>Q</th>
                                <th>S</th>
                                <th>S</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 1; $i <= $dias; $i++) {
                                $diaDaSemana = date("N", mktime(0, 0, 0, date('m') + 1, $i, $ano));
                                $d = 0;
                                $ultimo = $dias;
                                $compara = date('Y-m-d', mktime(0, 0, 0, date('m') + 1, $i, $ano)); //Monta a data atual
                                $classe = '';
                                if ($dd == $compara) {
                                    $classe = "hoje";
                                }
                                if ($i == 1) {
                                    $colspan = $diaDaSemana;
                                    $retorno = "<tr>";
                                    if ($colspan != 7) {
                                        $retorno .= "<td class='padding' colspan='" . $colspan . "'>&nbsp;</td>";
                                    }
                                } else {
                                    if ($diaDaSemana == 7) {
                                        $retorno .= "<tr>";
                                    }
                                }
                                $retorno .= "<td><span class='" . $classe . "'>$i</span></td>";
                                if ($dia == $ultimo) {
                                    $colspan = 7 - $diaDaSemana;
                                    if ($colspan != 0) {
                                        $retorno .= "<td class='padding' colspan='" . $colspan . "'></td>";
                                    }
                                }
                                if ($diaDaSemana == 6) {
                                    $retorno .= "</tr>";
                                }
                            }
                            echo $retorno;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/agenda/agenda-dia.js"></script>

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

