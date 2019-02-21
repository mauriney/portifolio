<?php

include_once('conf/config.php');
include_once('utils/funcoes.php');
include_once('plugins/fpdf/fpdf.php');
include_once('utils/agenda/funcoes.php');

session_start();

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
    $pdf->MultiCell(35, 10, utf8_decode('HORÁRIO'), '0', 'C', 1);
    $pdf->SetxY(40, 35);
    $pdf->MultiCell(50, 10, utf8_decode('PAUTA'), '0', 'C', 1);
    $pdf->SetxY(90, 35);
    $pdf->MultiCell(50, 10, utf8_decode('SOLICITANTE'), '0', 'C', 1);
    $pdf->SetxY(140, 35);
    $pdf->MultiCell(40, 10, utf8_decode('LOCAL'), '0', 'C', 1);
    $pdf->SetxY(180, 35);
    $pdf->MultiCell(80, 10, utf8_decode('QUEM VAI'), '0', 'C', 1);
    //$pdf->SetxY(230, 35);
    //$pdf->MultiCell(30, 10, utf8_decode('PRIORIDADE'), '0', 'C', 1);
    $pdf->SetxY(260, 35);
    $pdf->MultiCell(30, 10, utf8_decode('CONFIRMADO'), '0', 'C', 1);

    $pdf->SetTextColor(0000);

    $dataaux = '';
    $largura = -10;

    $sql = $db->prepare("$codigo");
    $sql->execute();
    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($_SESSION['acesso'] == 1 || vf_participante($linha['IdAgenda'], $_SESSION['id'])) {//MOSTRA SÓ AS AGENDAS DO RESPONSÁVEL OU MOSTRA TODAS PARA O ADMINISTRADOR
            if ($dataaux != $linha['DataAgenda']) {
                $y+=$largura + 10;
                $pdf->SetTextColor(9999);
                $pdf->SetFillColor(28, 195, 179, 1);
                $pdf->SetxY(5, 43 + $y);
                $pdf->MultiCell(285, 10, dataExtensoComAno(data_volta($linha['DataAgenda'])) . " - " . utf8_decode(hoje(data_volta($linha['DataAgenda']))), '0', 'L', 1);
            }

            $pdf->SetTextColor(0000);

            $dataaux = $linha['DataAgenda'];

            /* $interm = 0;
              $basic = 0;

              if (is_numeric(pesquisa2("IdAgenda", "tb_bsc_acesso_agenda", "IdAgenda = ?", $linha['IdAgenda'], " AND IdAcesso = ?", 2, "", "", "", "", ""))) {
              $interm = 1;
              }
              if (is_numeric(pesquisa2("IdAgenda", "tb_bsc_acesso_agenda", "IdAgenda = ?", $linha['IdAgenda'], " AND IdAcesso = ?", 3, "", "", "", "", ""))) {
              $basic = 1;
              }

              if ($_SESSION['acesso'] == 1 || $_SESSION['acesso'] == 2 && $interm == 1 ||
              $_SESSION['acesso'] == 3 && $basic == 1) { */

            $participante = agenda_participantes($linha['IdAgenda']);


            $tamanho1 = 0;
            $tamanho2 = 0;
            $largura = 5;

            $pdf->SetFont('Times', '', 10);

            $pdf->SetxY(5, 55 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(35, 5, hora($linha['HoraAgenda']), '0', 'C', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(40, 55 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(50, 5, utf8_decode($linha['Pauta']), '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(90, 55 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(50, 5, utf8_decode($linha['Demandante']), '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(140, 55 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(40, 5, utf8_decode($linha['LocalEvento']), '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $pdf->SetxY(180, 55 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(80, 5, str_replace("<br/>", ";", $participante), '0', 'L', 0);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            /*
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

              $pdf->SetxY(230, 55 + $y);
              $tamanho1 = $pdf->getY();
              $pdf->MultiCell(30, 5, utf8_decode(prioridade($linha['IdPrioridade'])), '0', 'C', 2);
              $tamanho2 = $pdf->getY();

              $pdf->SetTextColor(0000);

              if (($tamanho2 - $tamanho1) > $largura)
              $largura = $tamanho2 - $tamanho1; */


            if ($linha['Confirmado'] == 1) {//Sim
                $pdf->SetTextColor(9999);
                $pdf->SetFillColor(0, 150, 39, 20);
            } else if ($linha['Confirmado'] == 0) {//Não
                $pdf->SetTextColor(9999);
                $pdf->SetFillColor(255, 0, 0, 1);
            }

            $pdf->SetxY(265, 55 + $y);
            $tamanho1 = $pdf->getY();
            $pdf->MultiCell(25, 5, utf8_decode($linha['Confirmado'] == 1 ? 'Sim' : 'Não'), '0', 'C', 1);
            $tamanho2 = $pdf->getY();

            if (($tamanho2 - $tamanho1) > $largura)
                $largura = $tamanho2 - $tamanho1;

            $y+=$largura + 5;

            if ($pdf->GetY() > 120) {

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
                $pdf->MultiCell(35, 10, utf8_decode('HORÁRIO'), '0', 'C', 1);
                $pdf->SetxY(40, 35);
                $pdf->MultiCell(50, 10, utf8_decode('PAUTA'), '0', 'C', 1);
                $pdf->SetxY(90, 35);
                $pdf->MultiCell(50, 10, utf8_decode('SOLICITANTE'), '0', 'C', 1);
                $pdf->SetxY(140, 35);
                $pdf->MultiCell(40, 10, utf8_decode('LOCAL'), '0', 'C', 1);
                $pdf->SetxY(180, 35);
                $pdf->MultiCell(80, 10, utf8_decode('QUEM VAI'), '0', 'C', 1);
                //$pdf->SetxY(230, 35);
                //$pdf->MultiCell(30, 10, utf8_decode('PRIORIDADE'), '0', 'C', 1);
                $pdf->SetxY(260, 35);
                $pdf->MultiCell(30, 10, utf8_decode('CONFIRMADO'), '0', 'C', 1);

                $pdf->SetTextColor(0000);

                $y = 2;
                $largura = -10;
                $dataaux = '';

                $pdf->SetTextColor(0000);
            }
            //}
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
