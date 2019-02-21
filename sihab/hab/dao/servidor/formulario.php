<?php

//------------------------------------------------------------------------------
//DATA: 06/06/2017 às 15:00
//NOME: Cadastro de servidor
//DESCRIÇÃO: Realiza o cadastro de um servidor no banco de dados
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$error = false;
$msg = array();
$mensagem = "";

$db->beginTransaction();

try {

  $id = $_POST['id'];
  $nome = $_POST['nome'];
  $cpf = $_POST['cpf'];
  $identidade = $_POST['identidade'];
  $orgao_expedidor = $_POST['orgao_expedidor'];
  $data_expedicao = $_POST['data_expedicao'];
  $data_nascimento = $_POST['data_nascimento'];
  $estado_civil = $_POST['estado_civil'];
  $uniao_estavel = isset($_POST['uniao_estavel']) && $estado_civil != 9 ? $_POST['uniao_estavel'] : NULL;
  $residencial = $_POST['residencial'];
  $bairro = $_POST['bairro'];
  $servidor_cidade = $_POST['servidor_cidade'];
  $tel_residencial = $_POST['tel_residencial'];
  $celular = $_POST['celular'];
  $email = $_POST['email'];

  $naturalidade_municipio = $_POST['naturalidade_municipio'];
  $servidor_pais = $_POST['servidor_pais'];

  $dependentes = $_POST['dependentes'];

  $mora_parentes = $_POST['mora_parentes'];
  $casa_propria = $_POST['casa_propria'];
  $aluguel = $_POST['aluguel'];
  $valor_alguel = $_POST['valor_aluguel'];

  $ano_reside_cidade = $_POST['ano_reside_cidade'];
  $mes_reside_cidade = $_POST['mes_reside_cidade'];

  $pai = $_POST['pai'];
  $mae = $_POST['mae'];

  //CÔNJUGE----------------------------------------------------------------------
  $conjuge_nome = $_POST['conjuge_nome'];
  $conjuge_cpf = $_POST['conjuge_cpf'];
  $conjuge_data_nasc = $_POST['conjuge_data_nasc'];
  $conjuge_profissao = $_POST['conjuge_profissao'];
  $conjuge_rg = $_POST['conjuge_rg'];
  $conjuge_naturalidade_municipio = $_POST['conjuge_naturalidade_municipio'];
  $conjuge_servidor_pais = $_POST['conjuge_servidor_pais'];

  //IMÓVEL----------------------------------------------------------------------
  $imovel = $_POST['imovel'];
  $conjuge_imovel = $_POST['conjuge_imovel'];
  $benficiado_imovel = $_POST['benficiado_imovel'];
  $tipo_edificacao = $_POST['tipo_edificacao'];

  //DADOS FUNCIONAL-------------------------------------------------------------
  $lotacao = $_POST['lotacao'];
  $funcional_entidade = $_POST['funcional_entidade'];
  $funcional_endereco = $_POST['funcional_endereco'];
  $funcional_bairro = $_POST['funcional_bairro'];
  $funcional_cidade = $_POST['funcional_cidade'];
  $funcional_vinculacao = $_POST['funcional_vinculacao'];
  $funcional_matricula = $_POST['funcional_matricula'];
  $funcional_telefone_comercial = $_POST['funcional_telefone_comercial'];
  $funcional_ramal = $_POST['funcional_ramal'];
  $funcional_data_admissao = $_POST['funcional_data_admissao'];
  $funcional_renumeracao = $_POST['funcional_renumeracao'];
  $funcional_cargo = $_POST['funcional_cargo'];

  $id_servidor = pesquisar_tabela("id", "svd_servidor", "nome", "=", $nome, "");

  if (is_numeric($id_servidor) && $id_servidor != $id) {
    $error = true;
    $mensagem .= "\n- O nome do servidor informado já existe no sistema.";
    $msg['tipo'] = "nome";
  }

  $cpf_servidor = pesquisar_tabela("id", "svd_servidor", "cpf", "=", $cpf, "");

  if (is_numeric($cpf_servidor) && $cpf_servidor != $id) {
    $error = true;
    $mensagem .= "\n- O cpf informado do servidor já está cadastrado no sistema como " . pesquisar_tabela("nome", "svd_servidor", "id", "=", $cpf_servidor, "") . ".";
    $msg['tipo'] = "cpf";
  } else {
    if (@!valida_cpf($cpf)) {
      $error = true;
      $mensagem .= "\n- O CPF do servidor informado é inválido.";
      $msg['tipo'] = "cpf";
    }
  }

  if ($uniao_estavel == 1 || $estado_civil == 9) {

    $conjuge_svd_servidor_id = pesquisar_tabela("svd_servidor_id", "svd_conjuge", "cpf", "=", $conjuge_cpf, "");

    if (is_numeric($conjuge_svd_servidor_id) && $id != $conjuge_svd_servidor_id) {
      $error = true;
      $mensagem .= "\n- O cpf informado do cônjuge já está cadastrado no sistema como " . pesquisar_tabela("nome", "svd_conjuge", "cpf", "=", $conjuge_cpf, "") . " e cônjuge de " . pesquisar_tabela("nome", "svd_servidor", "id", "=", $conjuge_svd_servidor_id, "") . ".";
      $msg['tipo'] = "cpf";
    } else {
      if (@!valida_cpf($conjuge_cpf)) {
        $error = true;
        $mensagem .= "\n- O CPF do cônjuge informado é inválido.";
        $msg['tipo'] = "cpf";
      } else if ($cpf == $conjuge_cpf) {
        $error = true;
        $mensagem .= "\n- O CPF do servidor não pode ser igual ao do cônjuge.";
        $msg['tipo'] = "cpf";
      }
    }
  }

  if ($error == false) {
    if (isset($_POST['id']) && $_POST['id'] != 0) {
      $stmt = $db->prepare("UPDATE svd_servidor SET nome = ?, cpf = ?, rg = ?, bsc_orgao_expedidor_id = ?, rg_data_expedicao = ?, endereco = ?, bairro = ?, telefone_residencial = ?, telefone_celular = ?, email = ?, numero_dependentes = ?, nome_pai = ?, nome_mae = ?, data_nascimento = ?, ano_reside_cidade = ?, mes_reside_cidade = ?, mora_parente = ?, casa_propria = ?, paga_aluguel = ?, valor_aluguel = ?, bsc_municipio_id = ?, bsc_estado_civil_id = ?, uniao_estavel = ?, bsc_nacionalidade_id = ?, naturalidade_municipio_id = ?, data_update = NOW(), seg_usuario_id = ? WHERE id = ?");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $cpf);
      $stmt->bindValue(3, $identidade);
      $stmt->bindValue(4, $orgao_expedidor);
      $stmt->bindValue(5, convertDataBR2ISO($data_expedicao));
      $stmt->bindValue(6, $residencial);
      $stmt->bindValue(7, $bairro);
      $stmt->bindValue(8, $tel_residencial);
      $stmt->bindValue(9, $celular);
      $stmt->bindValue(10, $email);
      $stmt->bindValue(11, $dependentes);
      $stmt->bindValue(12, $pai);
      $stmt->bindValue(13, $mae);
      $stmt->bindValue(14, convertDataBR2ISO($data_nascimento));
      $stmt->bindValue(15, $ano_reside_cidade);
      $stmt->bindValue(16, $mes_reside_cidade);
      $stmt->bindValue(17, $mora_parentes);
      $stmt->bindValue(18, $casa_propria);
      $stmt->bindValue(19, $aluguel);
      $stmt->bindValue(20, valorfloat($valor_alguel));
      $stmt->bindValue(21, $servidor_cidade);
      $stmt->bindValue(22, $estado_civil);
      $stmt->bindValue(23, $uniao_estavel);
      $stmt->bindValue(24, $servidor_pais == "" ? NULL : $servidor_pais);
      $stmt->bindValue(25, $naturalidade_municipio == "" ? NULL : $naturalidade_municipio);
      $stmt->bindValue(26, NULL);
      $stmt->bindValue(27, $id);
      $stmt->execute();

      $svd_servidor_id = $id;

      //ATUALIZANDO DADOS DO IMÓVEL
      $stmt2 = $db->prepare("UPDATE svd_servidor SET possui_imovel = ?, conjuge_possui_imovel = ?, beneficiado_programa_habitacional = ?, svd_tipo_edificacao_id = ?, data_update = NOW(), seg_usuario_id = ? WHERE svd_servidor_id = ?");
      $stmt2->bindValue(1, $imovel);
      $stmt2->bindValue(2, $conjuge_imovel);
      $stmt2->bindValue(3, $benficiado_imovel);
      $stmt2->bindValue(4, $tipo_edificacao);
      $stmt2->bindValue(5, NULL);
      $stmt2->bindValue(6, $svd_servidor_id);
      $stmt2->execute();

      if ($uniao_estavel == 1 || $estado_civil == 9) {
        //ATUALIZANDO DADOS DO CÔNJUGE
        $stmt3 = $db->prepare("UPDATE svd_conjuge SET nome = ?, data_nascimento = ?, rg = ?, cpf = ?, profissao = ?, bsc_nacionalidade_id = ?, naturalidade_municipio_id = ?, data_update = NOW(), seg_usuario_id = ? WHERE svd_servidor_id = ?");
        $stmt3->bindValue(1, $conjuge_nome);
        $stmt3->bindValue(2, convertDataBR2ISO($conjuge_data_nasc));
        $stmt3->bindValue(3, $conjuge_rg);
        $stmt3->bindValue(4, $conjuge_cpf);
        $stmt3->bindValue(5, $conjuge_profissao);
        $stmt3->bindValue(6, $conjuge_servidor_pais == "" ? NULL : $conjuge_servidor_pais);
        $stmt3->bindValue(7, $conjuge_naturalidade_municipio == "" ? NULL : $conjuge_naturalidade_municipio);
        $stmt3->bindValue(8, NULL);
        $stmt3->bindValue(9, $svd_servidor_id);
        $stmt3->execute();
      }

      //ATUALIZANDO DADOS FUNCIONAIS
      $stmt4 = $db->prepare("UPDATE svd_funcional SET lotacao = ?, endereco = ?, bairro = ?, matricula = ?, cargo = ?, telefone = ?, ramal = ?, data_admissao = ?, bsc_municipio_id = ?, svd_servidor_id = ?, svd_vinculacao_id = ?, svd_remuneracao_id = ?, bsc_unidade_organizacional_id = ?, data_update = NOW(), seg_usuario_id = ? WHERE svd_servidor_id = ?");
      $stmt4->bindValue(1, $lotacao);
      $stmt4->bindValue(2, $funcional_endereco);
      $stmt4->bindValue(3, $funcional_bairro);
      $stmt4->bindValue(4, $funcional_matricula);
      $stmt4->bindValue(5, $funcional_cargo);
      $stmt4->bindValue(6, $funcional_telefone_comercial);
      $stmt4->bindValue(7, $funcional_ramal);
      $stmt4->bindValue(8, convertDataBR2ISO($funcional_data_admissao));
      $stmt4->bindValue(9, $funcional_cidade);
      $stmt4->bindValue(10, $funcional_vinculacao);
      $stmt4->bindValue(11, $funcional_renumeracao);
      $stmt4->bindValue(12, $funcional_entidade);
      $stmt4->bindValue(13, NULL);
      $stmt4->bindValue(14, $svd_servidor_id);
      $stmt4->execute();

      $msg['retorno'] = 'Servidor atualizado com sucesso!';
    } else {
      $stmt = $db->prepare("INSERT INTO svd_servidor (nome, cpf, rg, bsc_orgao_expedidor_id, rg_data_expedicao, endereco, bairro, telefone_residencial, telefone_celular, email, numero_dependentes, nome_pai, nome_mae, data_nascimento, ano_reside_cidade, mes_reside_cidade, mora_parente, casa_propria, paga_aluguel, valor_aluguel, bsc_municipio_id, bsc_estado_civil_id, uniao_estavel, bsc_nacionalidade_id, naturalidade_municipio_id, data_cadastro, data_update, status, seg_usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 1, ?)");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $cpf);
      $stmt->bindValue(3, $identidade);
      $stmt->bindValue(4, $orgao_expedidor);
      $stmt->bindValue(5, convertDataBR2ISO($data_expedicao));
      $stmt->bindValue(6, $residencial);
      $stmt->bindValue(7, $bairro);
      $stmt->bindValue(8, $tel_residencial);
      $stmt->bindValue(9, $celular);
      $stmt->bindValue(10, $email);
      $stmt->bindValue(11, $dependentes);
      $stmt->bindValue(12, $pai);
      $stmt->bindValue(13, $mae);
      $stmt->bindValue(14, convertDataBR2ISO($data_nascimento));
      $stmt->bindValue(15, $ano_reside_cidade);
      $stmt->bindValue(16, $mes_reside_cidade);
      $stmt->bindValue(17, $mora_parentes);
      $stmt->bindValue(18, $casa_propria);
      $stmt->bindValue(19, $aluguel);
      $stmt->bindValue(20, valorfloat($valor_alguel));
      $stmt->bindValue(21, $servidor_cidade);
      $stmt->bindValue(22, $estado_civil);
      $stmt->bindValue(23, $uniao_estavel);
      $stmt->bindValue(24, $servidor_pais == "" ? NULL : $servidor_pais);
      $stmt->bindValue(25, $naturalidade_municipio == "" ? NULL : $naturalidade_municipio);
      $stmt->bindValue(26, NULL);
      $stmt->execute();

      $svd_servidor_id = $db->lastInsertId();

      //SALVANDO DADOS DO IMÓVEL
      $stmt2 = $db->prepare("INSERT INTO svd_imovel (svd_servidor_id, possui_imovel, conjuge_possui_imovel, beneficiado_programa_habitacional, svd_tipo_edificacao_id, data_cadastro, data_update, status, seg_usuario_id) VALUES (?, ?, ?, ?, ?, NOW(), NOW(), 1, ?)");
      $stmt2->bindValue(1, $svd_servidor_id);
      $stmt2->bindValue(2, $imovel);
      $stmt2->bindValue(3, $conjuge_imovel);
      $stmt2->bindValue(4, $benficiado_imovel);
      $stmt2->bindValue(5, $tipo_edificacao);
      $stmt2->bindValue(6, NULL);
      $stmt2->execute();

      if ($uniao_estavel == 1 || $estado_civil == 9) {
        //SALVANDO DADOS DO CÔNJUGE
        $stmt3 = $db->prepare("INSERT INTO svd_conjuge (svd_servidor_id, nome, data_nascimento, rg, cpf, profissao, bsc_nacionalidade_id, naturalidade_municipio_id, data_cadastro, data_update, status, seg_usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 1, ?)");
        $stmt3->bindValue(1, $svd_servidor_id);
        $stmt3->bindValue(2, $conjuge_nome);
        $stmt3->bindValue(3, convertDataBR2ISO($conjuge_data_nasc));
        $stmt3->bindValue(4, $conjuge_rg);
        $stmt3->bindValue(5, $conjuge_cpf);
        $stmt3->bindValue(6, $conjuge_profissao);
        $stmt3->bindValue(7, $conjuge_servidor_pais == "" ? NULL : $conjuge_servidor_pais);
        $stmt3->bindValue(8, $conjuge_naturalidade_municipio == "" ? NULL : $conjuge_naturalidade_municipio);
        $stmt3->bindValue(9, NULL);
        $stmt3->execute();
      }

      //SALVANDO DADOS FUNCIONAL
      $stmt4 = $db->prepare("INSERT INTO svd_funcional (lotacao, endereco, bairro, matricula, cargo, telefone, ramal, data_admissao, bsc_municipio_id, svd_servidor_id, svd_vinculacao_id, svd_remuneracao_id, bsc_unidade_organizacional_id, data_cadastro, data_update, status, seg_usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 1, ?)");
      $stmt4->bindValue(1, $lotacao);
      $stmt4->bindValue(2, $funcional_endereco);
      $stmt4->bindValue(3, $funcional_bairro);
      $stmt4->bindValue(4, $funcional_matricula);
      $stmt4->bindValue(5, $funcional_cargo);
      $stmt4->bindValue(6, $funcional_telefone_comercial);
      $stmt4->bindValue(7, $funcional_ramal);
      $stmt4->bindValue(8, convertDataBR2ISO($funcional_data_admissao));
      $stmt4->bindValue(9, $funcional_cidade);
      $stmt4->bindValue(10, $svd_servidor_id);
      $stmt4->bindValue(11, $funcional_vinculacao);
      $stmt4->bindValue(12, $funcional_renumeracao);
      $stmt4->bindValue(13, $funcional_entidade);
      $stmt4->bindValue(14, NULL);
      $stmt4->execute();

      $msg['retorno'] = 'Servidor cadastrado com sucesso!';
    }

    $db->commit();

    //MENSAGEM DE SUCESSO
    $msg['id'] = $svd_servidor_id;
    $msg['msg'] = 'success';

    echo json_encode($msg);
    exit();
  } else {
    $msg['msg'] = 'error';
    $msg['retorno'] = $mensagem;
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar cadastrar o servidor desejado:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>