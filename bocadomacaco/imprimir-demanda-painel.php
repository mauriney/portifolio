<?php

include_once('utils/demanda/funcoes.php');
include_once('conf/config.php');
include_once('utils/funcoes.php');
include_once('plugins/fpdf/fpdf.php');

@session_start();

$db = Conexao::getInstance();

$codigo = utf8_decode(@$_POST['codigo']);

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

if ($codigo != "" && $codigo != NULL) {

    $pdf->SetFont('arial', 'B', 10);
    $pdf->SetxY(0, 20);
    $pdf->MultiCell(0, 5, utf8_decode("GABINETE DO DEPUTADO FEDERAL SIBÁ MACHADO"), '0', 'C', 0);
    $pdf->ln();

    $pdf->SetFont('Times', 'B', 9);
    $pdf->SetFillColor(240);
    $pdf->SetTextColor(000);
    $pdf->SetxY(5, 35);
    $pdf->MultiCell(10, 5, utf8_decode('#'), '0', 'C', 1);
    $pdf->SetxY(15, 35);
    $pdf->MultiCell(60, 5, utf8_decode('DEMANDA'), '0', 'C', 1);
    $pdf->SetxY(75, 35);
    $pdf->MultiCell(30, 5, utf8_decode('RESPONSÁVEL'), '0', 'C', 1);
    $pdf->SetxY(105, 35);
    $pdf->MultiCell(30, 5, utf8_decode('PRAZO'), '0', 'C', 1);
    $pdf->SetxY(135, 35);
    $pdf->MultiCell(40, 5, utf8_decode('STATUS'), '0', 'C', 1);
    $pdf->SetxY(175, 35);
    $pdf->MultiCell(30, 5, utf8_decode('SITUAÇÃO'), '0', 'C', 1);

    $pdf->SetTextColor(0000);

    $cont = 1;
    $sql = $db->prepare("$codigo");
    $sql->execute();
    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($_SESSION['acesso'] == 1 || responsavel_demanda_id($linha['id'], $_SESSION['id'])) {

            $responsavel = utf8_decode(responsavel_demanda($linha['id']));

            $tamanho1 = 0;
            $tamanho2 = 0;
            $largura = 5;

            if ($cont < 10) {
                $cont = "0" . $cont;
            }

            $pdf->SetFont('Times', '', 8);

            $pdf->SetxY(5, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(10, 5, $cont, '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(15, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(60, 5, strip_tags($linha['demanda']), '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(75, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(30, 5, strip_tags($responsavel), '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(105, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(30, 5, obterDataBRTimestamp($linha['prazo']), '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(135, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(40, 5, utf8_decode(status_demanda($linha['status'])), '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetTextColor(9999);

            if (situacao_demanda2($linha['prazo'], $linha['status']) == "no-prazo")
                $pdf->SetFillColor(0, 150, 39, 20);
            else if (situacao_demanda2($linha['prazo'], $linha['status']) == "atrasado")
                $pdf->SetFillColor(338, 10, 39, 20);
            else if (situacao_demanda2($linha['prazo'], $linha['status']) == "concluido")
                $pdf->SetFillColor(0, 150, 200, 20);

            $pdf->SetxY(175, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(30, 5, utf8_decode(situacao_demanda($linha['prazo'], $linha['status'])), '0', 'C', 1);
            $tamanho2 = $pdf->getY();

            $pdf->SetTextColor(0000);

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
                $pdf->SetTextColor(000);
                $pdf->SetxY(0, 20);
                $pdf->MultiCell(0, 5, utf8_decode("GABINETE DO DEPUTADO FEDERAL SIBÁ MACHADO"), '0', 'C', 0);
                $pdf->ln();

                $pdf->SetFont('Times', 'B', 9);
                $pdf->SetFillColor(240);
                $pdf->SetTextColor(000);
                $pdf->SetxY(5, 35);
                $pdf->MultiCell(10, 5, utf8_decode('#'), '0', 'C', 1);
                $pdf->SetxY(15, 35);
                $pdf->MultiCell(60, 5, utf8_decode('DEMANDA'), '0', 'C', 1);
                $pdf->SetxY(75, 35);
                $pdf->MultiCell(30, 5, utf8_decode('RESPONSÁVEL'), '0', 'C', 1);
                $pdf->SetxY(105, 35);
                $pdf->MultiCell(30, 5, utf8_decode('PRAZO'), '0', 'C', 1);
                $pdf->SetxY(135, 35);
                $pdf->MultiCell(40, 5, utf8_decode('STATUS'), '0', 'C', 1);
                $pdf->SetxY(175, 35);
                $pdf->MultiCell(30, 5, utf8_decode('SITUAÇÃO'), '0', 'C', 1);

                $pdf->SetTextColor(0000);

                $y = 0;

                $pdf->SetTextColor(0000);
            }

            $cont++;
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
