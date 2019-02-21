<?php

include_once('conf/config.php');
include_once('utils/funcoes.php');
include_once('plugins/fpdf/fpdf.php');
include_once('utils/contato/funcoes.php');

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

    $result = $db->prepare("SELECT *"
            . " FROM tb_bsc_contato "
            . "WHERE idcontato = ? GROUP BY idcontato");
    $result->bindValue(1, $codigo);
    $result->execute();
    $dados_contato = $result->fetch(PDO::FETCH_ASSOC);
    $contato_id = $dados_contato['idcontato'];
    $nome = $dados_contato['nome'];
    $dia = $dados_contato['dia'];
    $mes = $dados_contato['mes'];
    $instituicao = $dados_contato['instituicao'];
    $departamento = $dados_contato['departamento'];
    $cargo = $dados_contato['cargo'];
    $email = $dados_contato['email'];
    $celular_princiapl = $dados_contato['celular_principal'];
    $observacoes = $dados_contato['obs'];
    $data_cadastro = obterDataBRTimestamp($dados_contato['data_cadastro']);
    //INFORMAÇÕES DE ENDEREÇO
    $result2 = $db->prepare("SELECT * FROM tb_bsc_endereco WHERE idcontato = ?");
    $result2->bindValue(1, $codigo);
    $result2->execute();
    $dados_endereco = $result2->fetch(PDO::FETCH_ASSOC);
    $cep = $dados_endereco['cep'];
    $endereco = utf8_decode($dados_endereco['endereco']);
    $numero = $dados_endereco['numero'];
    $complemento = utf8_decode($dados_endereco['complemento']);
    $bairro = utf8_decode($dados_endereco['bairro']);
    $municipio_id = $dados_endereco['idcidade'];
    $municipio_nome = nome_cidade_id($municipio_id);
    $estado_nome = nome_estado_id(estado_municipio($municipio_id));
    $tipo = $dados_endereco['tipo'];

    $referencia = utf8_encode(pesquisar("nome", "tb_bsc_contato", "idcontato", "=", $dados_contato['referencia'], ""));
    $sql_tel = $db->prepare("SELECT telefone FROM tb_bsc_telefone WHERE idcontato = ?");
    $sql_tel->bindValue(1, $dados_contato['referencia']);
    $sql_tel->execute();
    $dados_tel = $sql_tel->fetch(PDO::FETCH_ASSOC);

    //CORPO DA IMPRESSÃO
    $pdf->SetFillColor(240);
    $pdf->SetTextColor(100, 100, 100);

    $pdf->SetFont('arial', 'B', 10);

    $pdf->SetFillColor(255, 0, 0); //Vermelho
    $pdf->SetTextColor(255, 255, 255); //Branco

    $pdf->SetxY(10, 30);
    $pdf->MultiCell(190, 8, utf8_decode(ctexto($nome, "mai")), '0', 'C', 1);

    $pdf->SetTextColor(0, 0, 0); //Preto

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetxY(10, 42);
    $pdf->MultiCell(50, 5, utf8_decode("Dia de Nascimento"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(10, 47);
    $pdf->MultiCell(50, 5, $dia, '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetxY(70, 42);
    $pdf->MultiCell(50, 5, utf8_decode("Mês de Nascimento"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(70, 47);
    $pdf->MultiCell(50, 5, getMes($mes), '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetxY(10, 55);
    $pdf->MultiCell(50, 5, utf8_decode("Instituição"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(10, 60);
    $pdf->MultiCell(50, 5, $instituicao, '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetxY(10, 68);
    $pdf->MultiCell(50, 5, utf8_decode("E-mail"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(10, 73);
    $pdf->MultiCell(50, 5, $email, '0', 'L', 0);


    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetxY(70, 55);
    $pdf->MultiCell(50, 5, utf8_decode("Departamento"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(70, 60);
    $pdf->MultiCell(50, 5, $departamento, '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetxY(150, 55);
    $pdf->MultiCell(50, 5, utf8_decode("Cargo"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(150, 60);
    $pdf->MultiCell(50, 5, $cargo, '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 10);
    $pdf->SetxY(10, 82);
    $pdf->MultiCell(50, 5, utf8_decode("Telefone(s)"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(10, 87);
    $pdf->MultiCell(190, 0.1, "", '1', 'L', 0);

    $pdf->SetFont('arial', '', 9);

    $col = 90;

    if ($celular_princiapl != "") {
        $pdf->SetFont('arial', 'B', 9);
        $pdf->SetxY(10, $col);
        $pdf->MultiCell(50, 5, "Celular Principal", '0', 'L', 0);
        $pdf->SetFont('arial', '', 9);
        $pdf->SetxY(10, $col + 5);
        $pdf->MultiCell(50, 5, $celular_princiapl, '0', 'L', 0);
    }

    $li = 1;
    $col = 105;

    $result5 = $db->prepare("SELECT * FROM tb_bsc_telefone WHERE idcontato = ?");
    $result5->bindValue(1, $contato_id);
    $result5->execute();
    while ($telefone = $result5->fetch(PDO::FETCH_ASSOC)) {

        if ($li == 1) {
            $pdf->SetFont('arial', 'B', 9);
            $pdf->SetxY(10, $col);
            $pdf->MultiCell(50, 5, "Telefone", '0', 'L', 0);
            $pdf->SetFont('arial', '', 9);
            $pdf->SetxY(10, $col + 5);
            $pdf->MultiCell(50, 5, $telefone['telefone'], '0', 'L', 0);
            $li = 2;
        } else if ($li == 2) {
            $pdf->SetFont('arial', 'B', 9);
            $pdf->SetxY(90, $col);
            $pdf->MultiCell(50, 5, "Telefone", '0', 'L', 0);
            $pdf->SetFont('arial', '', 9);
            $pdf->SetxY(90, $col + 5);
            $pdf->MultiCell(50, 5, $telefone['telefone'], '0', 'L', 0);
            $li = 3;
        } else if ($li == 3) {
            $pdf->SetFont('arial', 'B', 9);
            $pdf->SetxY(150, $col);
            $pdf->MultiCell(50, 5, "Telefone", '0', 'L', 0);
            $pdf->SetFont('arial', '', 9);
            $pdf->SetxY(150, $col + 5);
            $pdf->MultiCell(50, 5, $telefone['telefone'], '0', 'L', 0);
            $li = 1;
        }

        if ($li == 1) {
            $col+= 15;
        }
    }

    $pdf->Ln();

    $pdf->SetFont('arial', 'B', 10);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, utf8_decode("Endereço(s)"), '0', 'L', 0);

    $pdf->SetFont('arial', '', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(190, 0.1, "", '1', 'L', 0);

    $pdf->Ln(4);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, "CEP", '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, $cep, '0', 'L', 0);

    $pdf->Ln(4);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, utf8_decode("Endereço"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, $endereco, '0', 'L', 0);

    $pdf->Ln(4);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, utf8_decode("Complemento"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, $complemento, '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetxY(80, $pdf->getY() - 10);
    $pdf->MultiCell(50, 5, utf8_decode("Bairro"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(80, $pdf->getY());
    $pdf->MultiCell(50, 5, $bairro, '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetxY(150, $pdf->getY() - 10);
    $pdf->MultiCell(50, 5, utf8_decode("Número"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(150, $pdf->getY());
    $pdf->MultiCell(50, 5, $numero, '0', 'L', 0);

    $pdf->Ln(4);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, utf8_decode("Estado"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, $estado_nome, '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetxY(80, $pdf->getY() - 10);
    $pdf->MultiCell(50, 5, utf8_decode("Município"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(80, $pdf->getY());
    $pdf->MultiCell(50, 5, $municipio_nome, '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetxY(150, $pdf->getY() - 10);
    $pdf->MultiCell(50, 5, utf8_decode("Tipo"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(150, $pdf->getY());
    $pdf->MultiCell(50, 5, tipo_endereco($tipo), '0', 'L', 0);

    $pdf->Ln();

    $pdf->SetFont('arial', 'B', 10);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, utf8_decode("Outras Informações"), '0', 'L', 0);

    $pdf->SetFont('arial', '', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(190, 0.1, "", '1', 'L', 0);

    $pdf->Ln(4);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, utf8_decode("Observações"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, $observacoes, '0', 'L', 0);

    $pdf->Ln(4);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(50, 5, utf8_decode("Referência de Contato"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->Setx(10);
    $pdf->MultiCell(60, 5, $referencia . " - " . $dados_tel['telefone'], '0', 'L', 0);

    $pdf->SetFont('arial', 'B', 9);
    $pdf->SetxY(70, $pdf->getY() - 10);
    $pdf->MultiCell(50, 5, utf8_decode("Data Cadastro"), '0', 'L', 0);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetxY(70, $pdf->getY());
    $pdf->MultiCell(50, 5, $data_cadastro, '0', 'L', 0);

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
