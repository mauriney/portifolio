<?php

include_once('utils/demanda/funcoes.php');
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

    $result = $db->prepare("SELECT u.Nome AS responsavel, d.IdUsuario, d.status, d.solicitante, d.celular, d.email, d.prazo, s.descricao as segmento, d.id, d.demanda, d.prioridade, d.cidade_id, d.data_cadastro"
            . " FROM x_demanda d, x_demanda_responsavel r, tb_bsc_segmento s, tb_bsc_usuario u"
            . " WHERE d.id = r.demanda_id AND d.segmento = s.IdSegmento AND d.IdUsuario = u.IdUsuario AND d.id = ?");
    $result->bindValue(1, $codigo);
    $result->execute();
    $dados_demanda = $result->fetch(PDO::FETCH_ASSOC);

    $demanda_id = $dados_demanda['id'];
    $demanda = utf8_encode($dados_demanda['demanda']);
    $prioridade = $dados_demanda['prioridade'];
    $segmento = utf8_encode($dados_demanda['segmento']);
    $municipio = $dados_demanda['cidade_id'];
    $data_cadastro = obterDataBRTimestamp($dados_demanda['data_cadastro']);
    $prazo = obterDataBRTimestamp($dados_demanda['prazo']);
    $prazo2 = $dados_demanda['prazo'];
    $solicitante = utf8_encode($dados_demanda['solicitante']);
    $celular = $dados_demanda['celular'];
    $email = $dados_demanda['email'];
    $status = $dados_demanda['status'];
    $resp = utf8_encode($dados_demanda['responsavel']);

    //CORPO DA IMPRESSÃO
    $pdf->SetFillColor(240);
    $pdf->SetTextColor(100, 100, 100);

    $pdf->SetFont('arial', 'B', 10);
    $pdf->SetxY(10, 40);
    $pdf->MultiCell(190, 10, utf8_decode(status_demanda($status)), '0', 'C', 1);

    //CONCLUÍDO - AZUL
    if (situacao_demanda2($prazo2, $status) == "concluido") {
        $pdf->SetFillColor(17, 140, 245);
        $pdf->SetTextColor(255, 255, 255);
    }
    //NO PRAZO - VERDE
    if (situacao_demanda2($prazo2, $status) == "no-prazo") {
        $pdf->SetFillColor(122, 201, 67);
        $pdf->SetTextColor(255, 255, 255);
    }
    //ATRASADO - VERMELHO
    if (situacao_demanda2($prazo2, $status) == "atrasado") {
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
    }

    $pdf->SetxY(10, 50.4);
    $pdf->MultiCell(190, 10, utf8_decode(situacao_demanda($prazo2, $status)), '0', 'C', 1);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(50, 50, 50);

    $pdf->SetxY(10, 65);
    $pdf->MultiCell(190, 5, "Demanda", '0', 'L', 0);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetxY(10, 70);
    $pdf->MultiCell(190, 5, utf8_decode($demanda), '0', 'L', 0);


    $pdf->SetFont('helvetica', 'B', 8);

    $pdf->SetxY(10, 80);
    $pdf->MultiCell(30, 5, "Prioridade", '0', 'L', 0);
    $pdf->SetFont('helvetica', '', 10);

    //BAIXA - VERDE
    if (prioridade_cor($prioridade) == "baixa") {
        $pdf->SetFillColor(30, 150, 30);
        $pdf->SetTextColor(255, 255, 255);
    }
    //MÉDIA - BERGE
    if (prioridade_cor($prioridade) == "media") {
        $pdf->SetFillColor(255, 252, 127);
        $pdf->SetTextColor(0, 0, 0);
    }
    //ALTA - VERMELHO
    if (prioridade_cor($prioridade) == "alta") {
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
    }

    $pdf->SetxY(10, 85);
    $pdf->MultiCell(30, 5, utf8_decode(prioridade($prioridade)), '0', 'C', 1);

    //SEGMENTO
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(50, 50, 50);

    $pdf->SetxY(50, 80);
    $pdf->MultiCell(50, 5, "Segmento", '0', 'L', 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetxY(50, 85);
    $pdf->MultiCell(50, 5, utf8_decode($segmento), '0', 'L', 0);

    //MUNICÍPIO
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(50, 50, 50);

    $pdf->SetxY(135, 80);
    $pdf->MultiCell(50, 5, utf8_decode("Município"), '0', 'L', 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetxY(135, 85);
    $pdf->MultiCell(50, 5, nome_cidade_id($municipio), '0', 'L', 0);


    //DATA DE CADASTRO
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(50, 50, 50);

    //SOLICITANTE
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(50, 50, 50);
    $pdf->SetxY(10, 94);
    $pdf->MultiCell(80, 5, "Cadastrado por", '0', 'L', 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetxY(10, 99);
    $pdf->MultiCell(80, 5, utf8_decode($resp), '0', 'L', 0);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(50, 50, 50);
    $pdf->SetxY(100, 94);
    $pdf->MultiCell(35, 5, "Data de cadastro", '0', 'L', 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetxY(100, 99);
    $pdf->MultiCell(30, 5, $data_cadastro, '0', 'L', 0);

    //PRAZO
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(50, 50, 50);

    $pdf->SetxY(135, 94);
    $pdf->MultiCell(50, 5, utf8_decode("Prazo de conclusão"), '0', 'L', 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetxY(135, 99);
    $pdf->MultiCell(50, 5, $prazo . " (" . hoje($prazo) . ")", '0', 'L', 0);


    //OUTRA LINHA
    //SOLICITANTE
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(50, 50, 50);
    $pdf->SetxY(10, 108);
    $pdf->MultiCell(80, 5, "Solicitante", '0', 'L', 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetxY(10, 113);
    $pdf->MultiCell(80, 5, utf8_decode($solicitante), '0', 'L', 0);

    //CELULAR
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(50, 50, 50);
    $pdf->SetxY(100, 108);
    $pdf->MultiCell(30, 5, "Celular", '0', 'L', 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetxY(100, 113);
    $pdf->MultiCell(30, 5, $celular, '0', 'L', 0);
    //E-MAIL
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(50, 50, 50);
    $pdf->SetxY(135, 108);
    $pdf->MultiCell(55, 5, "E-mail", '0', 'L', 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetxY(135, 113);
    $pdf->MultiCell(55, 5, $email, '0', 'L', 0);

    //OUTRA LINHA

    $contador = 12;

    $result = $db->prepare("SELECT u.Nome as responsavel
                             FROM x_demanda_responsavel r, tb_bsc_usuario u
                             WHERE r.responsavel_id = u.IdUsuario AND r.demanda_id = ? GROUP BY u.IdUsuario");
    $result->bindValue(1, $demanda_id);
    $result->execute();
    while ($responsavel = $result->fetch(PDO::FETCH_ASSOC)) {
        $contador+=5;
    }

    $pdf->SetFillColor(240);
    $pdf->SetxY(60, 124);
    $pdf->MultiCell(80, $contador, "", '0', 'C', 1);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(50, 50, 50);
    $pdf->SetxY(60, 127);
    $pdf->MultiCell(80, 5, utf8_decode("Responsável"), '0', 'C', 0);

    $pdf->SetTextColor(100, 100, 100);
    $pdf->SetFont('helvetica', '', 8);

    $cont2 = 0;

    $result2 = $db->prepare("SELECT u.Nome as responsavel
                             FROM x_demanda_responsavel r, tb_bsc_usuario u
                             WHERE r.responsavel_id = u.IdUsuario AND r.demanda_id = ? GROUP BY u.IdUsuario");
    $result2->bindValue(1, $demanda_id);
    $result2->execute();
    while ($responsavel2 = $result2->fetch(PDO::FETCH_ASSOC)) {
        $pdf->SetxY(60, 132 + $cont2);
        $pdf->MultiCell(80, 5, $responsavel2['responsavel'], '0', 'C', 0);
        $cont2+=5;
    }

    //OUTRA LINHA
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetTextColor(50, 50, 50);
    $pdf->SetxY(10, (124 + $contador + 10));
    $pdf->MultiCell(190, 5, utf8_decode("Observações"), '0', 'L', 0);

    $pdf->SetxY(10, (124 + $contador + 16));
    $pdf->MultiCell(190, 0.0, "", '1', 'L', 0);

    $y = 0;

    $result3 = $db->prepare("SELECT *
                             FROM x_demanda_obs
                             WHERE demanda_id = ?");
    $result3->bindValue(1, $demanda_id);
    $result3->execute();
    while ($responsavel3 = $result3->fetch(PDO::FETCH_ASSOC)) {

        if ($pdf->GetY() > 230) {

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
            $pdf->ln();

            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetTextColor(50, 50, 50);
            $pdf->SetxY(10, $pdf->GetY());
            $pdf->MultiCell(190, 5, utf8_decode("Observações"), '0', 'L', 0);

            $pdf->SetxY(10, $pdf->GetY());
            $pdf->MultiCell(190, 0.0, "", '1', 'L', 0);
        }

        $y = $pdf->GetY() + 3;

        $pdf->SetFont('helvetica', '', 8);

        $pdf->SetxY(150, $y);
        $pdf->MultiCell(40, 5, pesquisar("Nome", "tb_bsc_usuario", "IdUsuario", "=", $responsavel3['responsavel_id'], ""), '0', 'C', 0);

        $pdf->SetxY(150, ($pdf->getY()));
        $pdf->MultiCell(40, 5, obterDataBRTimestamp($responsavel3['data_cadastro']) . utf8_decode(" às ") . obterHoraTimestamp($responsavel3['data_cadastro']), '0', 'C', 0);

        $pdf->SetxY(10, ($pdf->getY() - 10));
        $pdf->MultiCell(130, 5, $responsavel3['obs'], '0', 'L', 0);

        $pdf->ln();
    }
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
