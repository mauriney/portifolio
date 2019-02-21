<?php

include_once('conf/config.php');
include_once('utils/funcoes.php');
include_once('plugins/fpdf/fpdf.php');

$db = Conexao::getInstance();

$codigo = utf8_decode(@$_POST['codigo']);

//Controle
$y = 2; //Linha
$x = 0;
$fonte = 12;
//definindo a fonte !
define('FPDF_FONTPATH', 'fpdf/font/');

//Definições iniciais obrigatorias
$pdf = new FPDF("P", "mm", "A4"); // Define o tamanho do papel, orienteação e metrica utilizada
$pdf->SetFont('arial', '', 10);  // Define a fonte e o tamanho
$pdf->SetFillColor(150, 150, 150);

//CabeçalhO
$pdf->SetMargins(3, 3, 2);
$pdf->SetY("-1");
$pdf->MultiCell(160, 5, ' ', '0', 'C', 0);

if ($codigo != "" && $codigo != NULL) {

    $sql = $db->prepare("$codigo");
    $sql->execute();

    if ($sql->rowCount() > 0) {

        $pdf->SetFont('arial', 'B', 10);
        $pdf->SetxY(0, 20);
        $pdf->MultiCell(0, 5, utf8_decode("GABINETE DO DEPUTADO FEDERAL SIBÁ MACHADO"), '0', 'C', 0);
        $pdf->ln();

        $pdf->SetFont('Times', 'B', 9);
        $pdf->SetFillColor(240);
        $pdf->SetTextColor(000);
        $pdf->SetxY(10, 35);
        $pdf->MultiCell(55, 5, utf8_decode('NOME'), '0', 'L', 1);
        $pdf->SetxY(65, 35);
        $pdf->MultiCell(40, 5, utf8_decode('DIA DE NASCIMENTO'), '0', 'L', 1);
        $pdf->SetxY(105, 35);
        $pdf->MultiCell(40, 5, utf8_decode('MÊS DE NASCIMENTO'), '0', 'L', 1);
        $pdf->SetxY(145, 35);
        $pdf->MultiCell(55, 5, utf8_decode('E-MAIL'), '0', 'C', 1);

        $pdf->SetTextColor(0000);

        $grupos = "";

        while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

            $tamanho1 = 0;
            $tamanho2 = 0;
            $largura = 5;

            $pdf->SetFont('Times', '', 8);

            $pdf->SetxY(10, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(55, 5, $linha['nome'], '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(65, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(40, 5, $linha['dia'], '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(105, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(40, 5, getMes($linha['mes']), '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(145, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(55, 5, $linha['email'], '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;


            $y+=$largura + 2;

            $pdf->SetDrawColor(204, 204, 204);

            $pdf->SetxY(10, 40 + $y);
            $pdf->MultiCell(190, 0.0, '', '1', 'L', 0);
            $y+=2;

            if ($pdf->GetY() > 150) {

                $pdf->SetFont('arial', '', 8);
                $pdf->SetxY(5, 270);
                $pdf->MultiCell(200, 4, 'Rio Branco, Acre - ' . obterDataBR(), '0', 'C', 0);

                //Cabeçalho
                $pdf->SetMargins(3, 3, 2);
                $pdf->SetY("-1");
                $pdf->MultiCell(160, 5, ' ', '0', 'C', 0);
                $pdf->SetFont('arial', 'B', 10);
                $pdf->SetTextColor(000);
                $pdf->SetxY(0, 20);
                $pdf->MultiCell(0, 5, utf8_decode("GABINETE DO DEPUTADO FEDERAL SIBÁ MACHADO"), '0', 'C', 0);
                $pdf->ln();

                $pdf->SetFont('Times', 'B', 9);
                $pdf->SetFillColor(240);
                $pdf->SetTextColor(000);
                $pdf->SetxY(10, 35);
                $pdf->MultiCell(55, 5, utf8_decode('NOME'), '0', 'L', 1);
                $pdf->SetxY(65, 35);
                $pdf->MultiCell(40, 5, utf8_decode('DIA DE NASCIMENTO'), '0', 'L', 1);
                $pdf->SetxY(105, 35);
                $pdf->MultiCell(40, 5, utf8_decode('MÊS DE NASCIMENTO'), '0', 'L', 1);
                $pdf->SetxY(145, 35);
                $pdf->MultiCell(55, 5, utf8_decode('E-MAIL'), '0', 'C', 1);

                $pdf->SetTextColor(0000);

                $y = 2;

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
} else {
    $pdf->SetFont('Times', 'B', 10);
    $pdf->SetxY(5, 10);
    $pdf->MultiCell(200, 5, utf8_decode('NENHUMA PESQUISA REALIZADA'), '0', 'C', 0);
}

$arquivo = "relatorio.pdf";

//Gera o arquivo PDF a ser baixado
$pdf->Output($arquivo, "I");
?>
