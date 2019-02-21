<?php

include_once('utils/demanda/funcoes.php');
include_once('conf/config.php');
include_once('utils/funcoes.php');
include_once('plugins/fpdf/fpdf.php');

@session_start();

$db = Conexao::getInstance();

$codigo = @$_POST['codigo'];

//Controle
$y = 2; //Linha
$x = 0;
$fonte = 12;
//definindo a fonte !
define('FPDF_FONTPATH', 'fpdf/font/');

//Definições iniciais obrigatorias
$pdf = new FPDF("L", "mm", "A4"); // Define o tamanho do papel, orientea��o e metrica utilizada
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
    $pdf->MultiCell(35, 5, utf8_decode('PRAZO'), '0', 'C', 1);
    $pdf->SetxY(40, 35);
    $pdf->MultiCell(50, 5, utf8_decode('NOME'), '0', 'C', 1);
    $pdf->SetxY(90, 35);
    $pdf->MultiCell(50, 5, utf8_decode('ASSUNTO'), '0', 'C', 1);
    $pdf->SetxY(140, 35);
    $pdf->MultiCell(30, 5, utf8_decode('CELULAR'), '0', 'C', 1);
    $pdf->SetxY(170, 35);
    $pdf->MultiCell(30, 5, utf8_decode('E-MAIL'), '0', 'C', 1);
    $pdf->SetxY(200, 35);
    $pdf->MultiCell(30, 5, utf8_decode('SEGMENTO'), '0', 'C', 1);
    $pdf->SetxY(230, 35);
    $pdf->MultiCell(30, 5, utf8_decode('PRIORIDADE'), '0', 'C', 1);
    $pdf->SetxY(260, 35);
    $pdf->MultiCell(30, 5, utf8_decode('REGISTRADO'), '0', 'C', 1);

    $pdf->SetTextColor(0000);

    $sql = $db->prepare("$codigo");
    $sql->execute();
    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($_SESSION['acesso'] == 1 || $linha['responsavel'] == $_SESSION['id']) {//MOSTRA SÓ AS AGENDAS DO RESPONSÁVEL OU MOSTRA TODAS PARA O ADMINISTRADOR
            $tamanho1 = 0;
            $tamanho2 = 0;
            $largura = 5;

            $pdf->SetFont('Times', '', 10);

            $pdf->SetxY(5, 40 + $y);

            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(35, 5, data_volta($linha['prazo']), '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(40, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(50, 5, strip_tags(utf8_decode($linha['Nome'])), '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(90, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(50, 5, strip_tags(utf8_decode($linha['Assunto'])), '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (( $tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(140, 40 + $y);

            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(30, 5, $linha['TelefoneCel'], '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (( $tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(170, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(30, 5, $linha['Email'], '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(200, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(30, 5, strip_tags(utf8_decode($linha ['segmento'])), '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            if ($linha['IdPrioridade'] == 1) {//baixa
                $pdf->SetTextColor(9999);
                $pdf->SetFillColor(0, 150, 39, 20);
            } else if ($linha['IdPrioridade'] == 2) {//media
                $pdf->SetTextColor(000);
                $pdf->SetFillColor(255, 252, 127, 1);
            } else if ($linha['IdPrioridade'] == 3) {//alta
                $pdf->SetTextColor(9999);
                $pdf->SetFillColor(255, 0, 0, 1);
            }

            $pdf->SetxY(230, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(30
                    , 5, utf8_decode(prioridade($linha['IdPrioridade'])), '0', 'C', 1);
            $tamanho2 = $pdf->getY();

            $pdf->SetTextColor(0000);

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(260, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(30, 5, data_volta_hora($linha['data_cadastro']) . "\n" . $linha['responsavel_nome'], '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $y+=$largura + 2;

            $pdf->SetDrawColor(204, 204, 204);
            $pdf->SetxY(5, 40 + $y);
            $pdf->MultiCell(285, 0.0, '', '1', 'L', 0);
            $y+=2;

            if ($pdf->GetY() > 140) {

                //Último Rodape
                $pdf->SetFont('arial', '', 8);
                $pdf->SetxY(5, 180);
                $pdf->MultiCell(285, 4, 'Rio Branco, Acre - ' . obterDataBR(), '0', 'C', 0);

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
                $pdf->MultiCell(35, 5, utf8_decode('PRAZO'), '0', 'L', 1);
                $pdf->SetxY(40, 35);
                $pdf->MultiCell(50, 5, utf8_decode('NOME'), '0', 'L', 1);
                $pdf->SetxY(90, 35);
                $pdf->MultiCell(50, 5, utf8_decode('ASSUNTO'), '0', 'L', 1);
                $pdf->SetxY(140, 35);
                $pdf->MultiCell(30, 5, utf8_decode('CELULAR'), '0', 'L', 1);
                $pdf->SetxY(170, 35);
                $pdf->MultiCell(30, 5, utf8_decode('E-MAIL'), '0', 'L', 1);
                $pdf->SetxY(200, 35);
                $pdf->MultiCell(30, 5, utf8_decode('SEGMENTO'), '0', 'L', 1);
                $pdf->SetxY(230, 35);
                $pdf->MultiCell(30, 5, utf8_decode('PRIORIDADE'), '0', 'L', 1);
                $pdf->SetxY(260, 35);
                $pdf->MultiCell(30, 5, utf8_decode('REGISTRADO'), '0', 'L', 1);

                $pdf->SetTextColor(0000);

                $y = 2;
                $pdf->SetTextColor(0000);
            }
        }
    }

//Último Rodape
    $pdf->SetFont('arial', '', 8);
    $pdf->SetxY(5, 180);
    $pdf->MultiCell(285, 4, 'Rio Branco, Acre - ' . obterDataBR(), '0', 'C', 0);
} else {
    $pdf->SetFont('Times', 'B', 10);
    $pdf->SetxY(5, 10);
    $pdf->MultiCell(285, 5, utf8_decode('NENHUMA PESQUISA REALIZADA'), '0', 'C', 0);
}

$arquivo = "relatorio.pdf";

//Gera o arquivo PDF a ser baixado
$pdf->Output($arquivo, "I");
?>
