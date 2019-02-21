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
        $pdf->SetxY(5, 35);
        $pdf->MultiCell(70, 5, utf8_decode('NOME'), '0', 'C', 1);
        $pdf->SetxY(75, 35);
        $pdf->MultiCell(30, 5, utf8_decode('CONTATO'), '0', 'C', 1);
        $pdf->SetxY(105, 35);
        $pdf->MultiCell(30, 5, utf8_decode('E-MAIL'), '0', 'C', 1);
        $pdf->SetxY(135, 35);
        $pdf->MultiCell(70, 5, utf8_decode('SEGMENTO(S)'), '0', 'C', 1);

        $pdf->SetTextColor(0000);

        $grupos = "";

        while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

            $tamanho1 = 0;
            $tamanho2 = 0;
            $largura = 5;

            $pdf->SetFont('Times', '', 8);

            $pdf->SetxY(5, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(70, 5, $linha['nome'], '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $grupos = "";

            if ($linha['celular_principal'] == "") {
                $sql_tel = $db->prepare("SELECT telefone FROM tb_bsc_telefone WHERE idcontato = ?");
                $sql_tel->bindValue(1, $linha['idcontato']);
                $sql_tel->execute();
                $dados_tel = $sql_tel->fetch(PDO::FETCH_ASSOC);
                $telefone = $dados_tel['telefone'];
            } else {
                $telefone = $linha['celular_principal'];
            }

            $sql_grupo = $db->prepare("SELECT se.Descricao FROM tb_bsc_segmento_grupo sg 
        LEFT JOIN tb_bsc_segmento se ON se.IdSegmento = sg.idsegmento
        WHERE idcontato = ?");

            $sql_grupo->bindValue(1, $linha['idcontato']);
            $sql_grupo->execute();
            $cont_grupos = 0;

            while ($f_grupo = $sql_grupo->fetch(PDO::FETCH_ASSOC)) {
                $grupos .= $f_grupo['Descricao'] . '; ';
                $cont_grupos++;
            }

            $pdf->SetxY(75, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(30, 5, $telefone, '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(105, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(30, 5, $linha['email'], '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(135, 40 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(70, 5, $grupos, '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;


            $y+=$largura + 2;

            $pdf->SetDrawColor(204, 204, 204);

            $pdf->SetxY(5, 40 + $y);
            $pdf->MultiCell(285, 0.0, '', '1', 'L', 0);
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
                $pdf->SetxY(5, 35);
                $pdf->MultiCell(70, 5, utf8_decode('NOME'), '0', 'C', 1);
                $pdf->SetxY(75, 35);
                $pdf->MultiCell(30, 5, utf8_decode('CONTATO'), '0', 'C', 1);
                $pdf->SetxY(105, 35);
                $pdf->MultiCell(30, 5, utf8_decode('E-MAIL'), '0', 'C', 1);
                $pdf->SetxY(135, 35);
                $pdf->MultiCell(70, 5, utf8_decode('SEGMNETO(S)'), '0', 'C', 1);

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
