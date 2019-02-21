<?php

include_once('utils/agenda/funcoes.php');
include_once('conf/config.php');
include_once('utils/funcoes.php');
include_once('plugins/fpdf/fpdf.php');

$db = Conexao::getInstance();

$codigo = @$_GET['id'];

//Controle
$y = 2; //Linha
$x = 0;
$fonte = 12;
//definindo a fonte !
define('FPDF_FONTPATH', 'fpdf/font/');

//Definições iniciais obrigatorias
$pdf = new FPDF("P", "mm", "A4"); // Define o tamanho do papel, orientea��o e metrica utilizada
$pdf->SetFont('arial', '', 10);  // Define a fonte e o tamanho
$pdf->SetFillColor(150, 150, 150);

//CabeçalhO
$pdf->SetMargins(3, 3, 2);
$pdf->SetY("-1");
$pdf->MultiCell(160, 5, ' ', '0', 'C', 0);

$pdf->SetFont('arial', 'B', 10);
$pdf->SetxY(0, 20);
$pdf->MultiCell(0, 5, utf8_decode("GABINETE DO DEPUTADO FEDERAL SIBÁ MACHADO"), '0', 'C', 0);
$pdf->ln();

if (is_numeric($codigo) && antiSQL($codigo != "Error")) {

    $result = $db->prepare("SELECT  u.Nome AS responsavel, a.IdUsuario, a.DataHoraCadastro, a.TelefoneCelDem, a.TelefoneFixoDem, a.EmailDemandante, bai.nome as Bairro, a.IdAgenda, Demandante, Contato, LocalEvento, DataAgenda, HoraAgenda, Pauta, Confirmado, p.Cor, p.IdPrioridade, atencao, p.Descricao as descr, m.NomeMunicipio, a.IdMunicipio
		FROM tb_bsc_agenda a 
		LEFT JOIN tb_bsc_municipios m ON m.IdMunicipio = a.IdMunicipio 
		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade 
		LEFT JOIN tb_bsc_acesso_agenda aa ON aa.IdAgenda = a.IdAgenda 
		LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.bairro
		LEFT JOIN tb_bsc_segmento seg ON seg.IdSegmento = a.IdSegmento
		LEFT JOIN tb_bsc_aviso_agenda avi ON avi.idagenda = a.IdAgenda
		LEFT JOIN tb_bsc_aviso_agenda avi2 ON avi2.idagenda = a.recorrente
                LEFT JOIN tb_bsc_usuario u ON u.IdUsuario = a.IdUsuario
		WHERE a.IdAgenda = ? GROUP BY a.IdAgenda ORDER BY DataAgenda, HoraAgenda ASC");
    $result->bindValue(1, $codigo);
    $result->execute();
    $dados_agenda = $result->fetch(PDO::FETCH_ASSOC);

    //CORPO DA IMPRESSÃO

    $pdf->SetTextColor(0, 150, 200, 20);
    $pdf->SetFont('arial', '', 14);
    $pdf->SetxY(10, 42);
    $pdf->MultiCell(50, 5, dataExtensoSemAno(data_volta($dados_agenda['DataAgenda'])), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(10, 48);
    $pdf->MultiCell(190, 0.1, "", '1', 'L', 0);

    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetFont('arial', 'B', 11);
    $pdf->SetxY(10, 53);
    $pdf->MultiCell(50, 5, hora($dados_agenda['HoraAgenda']), '0', 'L', 0);

    if ($dados_agenda['Confirmado'] == 0) {
        $pdf->SetTextColor(255, 255, 255, 255);
        $pdf->SetFillColor(338, 10, 39, 20);
    } else {
        $pdf->SetTextColor(255, 255, 255, 255);
        $pdf->SetFillColor(0, 150, 39, 20);
    }

    $pdf->SetFont('arial', 'B', 11);
    $pdf->SetxY(70, 53);
    $pdf->MultiCell(65, 5, utf8_decode($dados_agenda['Confirmado'] == 1 ? 'Evento Confirmado' : 'Evento não Confirmado'), '0', 'C', 1);

    if ($dados_agenda['IdPrioridade'] == 1) {//Baixa
        $pdf->SetTextColor(9999);
        $pdf->SetFillColor(0, 150, 39, 20);
    } else if ($dados_agenda['IdPrioridade'] == 2) {//Média
        $pdf->SetTextColor(000);
        $pdf->SetFillColor(255, 252, 127, 1);
    } else if ($dados_agenda['IdPrioridade'] == 3) {//Alta
        $pdf->SetTextColor(9999);
        $pdf->SetFillColor(255, 0, 0, 1);
    }

    $pdf->SetFont('arial', 'B', 11);
    $pdf->SetxY(140, 53);
    $pdf->MultiCell(60, 5, utf8_decode(prioridade($dados_agenda['IdPrioridade'])) . " Prioridade", '0', 'C', 1);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetTextColor(0, 150, 200, 20);
    $pdf->SetxY(10, 63);
    $pdf->MultiCell(50, 5, "Pauta", '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetxY(10, 67);
    $pdf->MultiCell(190, 5, utf8_decode(ctexto($dados_agenda['Pauta'], "min")), '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetTextColor(0, 150, 200, 20);
    $pdf->SetxY(10, 77);
    $pdf->MultiCell(50, 5, "Local", '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetxY(10, 82);
    $pdf->MultiCell(190, 5, utf8_decode(ctexto($dados_agenda['LocalEvento'], "min")), '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetTextColor(0, 150, 200, 20);
    $pdf->SetxY(10, 92);
    $pdf->MultiCell(50, 5, "Bairro", '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetxY(10, 97);
    $pdf->MultiCell(190, 5, ctexto($dados_agenda['Bairro'], "min"), '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetTextColor(0, 150, 200, 20);
    $pdf->SetxY(10, 107);
    $pdf->MultiCell(50, 5, "Quem Vai", '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetxY(10, 112);
    $pdf->MultiCell(190, 5, str_replace("<br/>", ";", ctexto(agenda_participantes($dados_agenda['IdAgenda']), "min")), '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetTextColor(0, 150, 200, 20);
    $pdf->SetxY(10, 122);
    $pdf->MultiCell(50, 5, "Solicitante", '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetxY(10, 127);
    $pdf->MultiCell(60, 5, utf8_decode(ctexto($dados_agenda['Demandante'], "min")), '10', 'L', 0);


    $telefones = "";

    $telefones = $dados_agenda['TelefoneFixoDem'];

    if ($dados_agenda['TelefoneFixoDem'] != "") {
        $telefones .= ' | ';
    }

    $telefones .= $dados_agenda['TelefoneCelDem'];

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetTextColor(0, 150, 200, 20);
    $pdf->SetxY(75, 122);
    $pdf->MultiCell(50, 5, "Telefone(s)", '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetxY(75, 127);
    $pdf->MultiCell(70, 5, $telefones, '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetTextColor(0, 150, 200, 20);
    $pdf->SetxY(150, 122);
    $pdf->MultiCell(40, 5, "E-mail", '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetxY(150, 127);
    $pdf->MultiCell(50, 5, $dados_agenda['EmailDemandante'], '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetTextColor(0, 150, 200, 20);
    $pdf->SetxY(10, 142);
    $pdf->MultiCell(50, 5, "Cadastrado Por", '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetxY(10, 147);
    $pdf->MultiCell(60, 5, $dados_agenda['responsavel'], '10', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetTextColor(0, 150, 200, 20);
    $pdf->SetxY(75, 142);
    $pdf->MultiCell(50, 5, "Data de Cadastro", '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetxY(75, 147);
    $pdf->MultiCell(70, 5, obterDataBRTimestamp($dados_agenda['DataHoraCadastro']) . utf8_decode(" às ") . obterHoraTimestamp($dados_agenda['DataHoraCadastro']), '0', 'L', 0);

    //FIM CORPO

    $pdf->SetFont('arial', '', 8);
    $pdf->SetxY(5, 270);
    $pdf->MultiCell(200, 4, 'Rio Branco, Acre - ' . obterDataBR(), '0', 'C', 0);
} else {
    $pdf->SetFont('Times', 'B', 10);
    $pdf->SetxY(5, 10);
    $pdf->MultiCell(200, 5, utf8_decode('NENHUMA PESQUISA REALIZADA'), '0', 'C', 0);
}

$arquivo = "relatorio.pdf";

//Gera o arquivo PDF a ser baixado
$pdf->Output($arquivo, "I");
?>
