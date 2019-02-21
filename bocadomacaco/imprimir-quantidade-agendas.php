<?php

include_once('conf/config.php');
include_once('utils/funcoes.php');
include_once('plugins/fpdf/fpdf.php');

$db = Conexao::getInstance();

$contador = 1;
$valor1 = "";
$valor2 = "";

$inicio = $_POST['inicio'];
$fim = $_POST['fim'];

$inicial = $_POST['inicio'] != '' ? formata_data($_POST['inicio']) : '';
$fim = $_POST['fim'] != '' ? formata_data($_POST['fim']) : '';

$where = '';

if ($inicial != '') {
    if ($fim != '') {
        $where = "AND DataAgenda BETWEEN (?) AND (?)";
        $valor1 = "" . convertDataBR2ISO($_POST['inicio']) . "";
        $valor2 = "" . convertDataBR2ISO($_POST['fim']) . "";
    } else {
        $where = "AND DataAgenda >= ?";
        $valor1 = "" . convertDataBR2ISO($_POST['inicio']) . "";
    }
}

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

if ($inicio != "" && $inicio != NULL || $inicio != "" && $inicio != NULL && $fim != "" && $fim != NULL) {

    $pdf->SetFont('arial', 'B', 10);
    $pdf->SetxY(0, 20);
    $pdf->MultiCell(0, 5, utf8_decode("GABINETE DO DEPUTADO FEDERAL SIBÁ MACHADO"), '0', 'C', 0);
    $pdf->ln();

    $pdf->SetFont('Times', 'B', 9);
    $pdf->SetFillColor(240);
    $pdf->SetTextColor(000);
    $pdf->SetxY(5, 35);
    $pdf->MultiCell(50, 5, utf8_decode('MÊS'), '0', 'L', 1);
    $pdf->SetxY(55, 35);
    $pdf->MultiCell(50, 5, utf8_decode('EXECUTADAS'), '0', 'L', 1);
    $pdf->SetxY(105, 35);
    $pdf->MultiCell(50, 5, utf8_decode('A EXECUTAR'), '0', 'L', 1);
    $pdf->SetxY(155, 35);
    $pdf->MultiCell(50, 5, utf8_decode('TOTAL'), '0', 'L', 1);

    $pdf->SetTextColor(0000);


    $cont = 1;
    $sql = $db->prepare("SELECT MONTH(DataAgenda) as mes, contato_participante FROM tb_bsc_agenda WHERE Confirmado = 1 $where GROUP BY MONTH(DataAgenda) ORDER BY MONTH(DataAgenda)");
    if ($valor1 != "") {
        $sql->bindValue($contador, $valor1);
        $contador++;
    }
    if ($valor2 != "") {
        $sql->bindValue($contador, $valor2);
        $contador++;
    }
    $sql->execute();

    $mes = '';
    $totale = 0;
    $totala = 0;

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        $tamanho1 = 0;
        $tamanho2 = 0;
        $largura = 5;

        $pdf->SetFont('Times', '', 8);

        $pdf->SetxY(5, 40 + $y);
        $tamanho1 = $pdf->getY();
        $pdf->MultiCell(50, 5, utf8_decode(getMes($linha['mes'])), '0', 'L', 0);
        $tamanho2 = $pdf->getY();

        if (($tamanho2 - $tamanho1) > $largura)
            $largura = $tamanho2 - $tamanho1;

        $contador = 1;

        $exec = $db->prepare("SELECT COUNT(*) AS total FROM tb_bsc_agenda WHERE Confirmado = 1 $where AND MONTH(DataAgenda) = ? AND DataAgenda <= NOW() GROUP BY MONTH(DataAgenda) ORDER BY MONTH(DataAgenda)");
        if ($valor1 != "") {
            $exec->bindValue($contador, $valor1);
            $contador++;
        }
        if ($valor2 != "") {
            $exec->bindValue($contador, $valor2);
            $contador++;
        }

        $exec->bindValue($contador, $linha['mes']);
        $contador++;

        $exec->execute();

        $numexec = $exec->rowCount();

        if ($numexec > 0) {
            $fexec = $exec->fetch(PDO::FETCH_ASSOC);
            $numexec = $fexec['total'];
        }

        $totale+=$numexec;

        $pdf->SetxY(55, 40 + $y);
        $tamanho1 = $pdf->getY();
        $pdf->MultiCell(50, 5, $numexec, '0', 'L', 0);
        $tamanho2 = $pdf->getY();

        if (($tamanho2 - $tamanho1) > $largura)
            $largura = $tamanho2 - $tamanho1;

        $contador = 1;

        $aexec = $db->prepare("SELECT COUNT(*) AS total FROM tb_bsc_agenda WHERE Confirmado = 1 $where AND MONTH(DataAgenda) = ? AND DataAgenda > NOW() GROUP BY MONTH(DataAgenda) ORDER BY MONTH(DataAgenda)");

        if ($valor1 != "") {
            $aexec->bindValue($contador, $valor1);
            $contador++;
        }
        if ($valor2 != "") {
            $aexec->bindValue($contador, $valor2);
            $contador++;
        }

        $aexec->bindValue($contador, $linha['mes']);
        $contador++;

        $aexec->execute();

        $numaexec = $aexec->rowCount();

        if ($numaexec > 0) {
            $faexec = $aexec->fetch(PDO::FETCH_ASSOC);
            $numaexec = $faexec['total'];
        }
        $totala+=$numaexec;


        $pdf->SetxY(105, 40 + $y);
        $tamanho1 = $pdf->getY();
        $pdf->MultiCell(50, 5, $numaexec, '0', 'L', 0);
        $tamanho2 = $pdf->getY();

        if (($tamanho2 - $tamanho1) > $largura)
            $largura = $tamanho2 - $tamanho1;

        $pdf->SetxY(155, 40 + $y);
        $tamanho1 = $pdf->getY();
        $pdf->MultiCell(50, 5, ($numaexec + $numexec), '0', 'L', 0);
        $tamanho2 = $pdf->getY();

        if (($tamanho2 - $tamanho1) > $largura)
            $largura = $tamanho2 - $tamanho1;

        $y+=$largura + 2;

        $pdf->SetDrawColor(204, 204, 204);

        $pdf->SetxY(5, 40 + $y);
        $pdf->MultiCell(200, 0.0, '', '1', 'L', 0);
        $y+=2;

        if ($pdf->GetY() > 150) {

            //Último Rodape
            $pdf->SetFont('arial', '', 8);
            $pdf->SetxY(5, 270);
            $pdf->MultiCell(200, 4, 'Rio Branco, Acre - ' . obterDataBR(), '0', 'C', 0);

            //Cabeçalho
            $pdf->SetMargins(3, 3, 2);
            $pdf->SetY("-1");
            $pdf->MultiCell(160, 5, ' ', '0', 'C', 0);
            $pdf->SetFont('arial', 'B', 10);
            $pdf->SetxY(0, 20);
            $pdf->MultiCell(0, 5, utf8_decode("GABINETE DO DEPUTADO FEDERAL SIBÁ MACHADO"), '0', 'C', 0);
            $pdf->ln();

            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetFillColor(240);
            $pdf->SetTextColor(000);
            $pdf->SetxY(5, 35);
            $pdf->MultiCell(50, 5, utf8_decode('MÊS'), '0', 'L', 1);
            $pdf->SetxY(55, 35);
            $pdf->MultiCell(50, 5, utf8_decode('EXECUTADAS'), '0', 'L', 1);
            $pdf->SetxY(105, 35);
            $pdf->MultiCell(50, 5, utf8_decode('A EXECUTAR'), '0', 'L', 1);
            $pdf->SetxY(155, 35);
            $pdf->MultiCell(50, 5, utf8_decode('TOTAL'), '0', 'L', 1);

            $pdf->SetTextColor(0000);

            $y = 0;

            $pdf->SetTextColor(0000);
        }
    }

//Último Rodape
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
