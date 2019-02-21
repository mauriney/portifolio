<!-- Vendor CSS -->
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">

<!-- CSS -->
<link href="<?= PORTAL_URL; ?>assets/css/app.min.1.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/css/app.min.2.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/css/style.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/css/cores.css" rel="stylesheet">

<?php
/**
 * Created by PhpStorm.
 * User: mauriney
 * Date: 21/09/16
 * Time: 16:14
 */
$db = Conexao::getInstance();

$erro = false;
$msg = "";

$indiceAux = @$_POST['index_anexo'];
$index_anexo_conjuge = @$_POST['index_anexo_conjuge'];
$index_anexo_familiar = @$_POST['index_anexo_familiar'];
$index_anexo_outro = @$_POST['index_anexo_outro'];

$candidato_id = $_POST['candidato_id'];

$caminho_pagina = "etapa3";

$situacao = 1; //Cadastrando

if (isset($_POST["anterior"])) {
  $caminho_pagina = "etapa3";
} else if (isset($_POST["salvar"])) {
  $caminho_pagina = "lista";
} else if (isset($_POST["finalizar"])) {
  $caminho_pagina = "lista";
  $situacao = 2; //Aguardando Validação
} else if (isset($_POST['proxima_etapa']) != "") {
  $caminho_pagina = $_POST['proxima_etapa'];
}

//INFO ARQUIVO
$arquivo = $_FILES['anexo'];

//LISTA DE TIPOS DE ARQUIVOS PERMITIDOS
$tiposPermitidos = array('application/pdf', 'application/save', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png');
//TAMANHO MÁXIMO DO ARQUIVO(em bytes)
$tamanhoPermitido = 1048576; //1MB

$arquivo_name = '';
$arquivo_error = 0;
$arquivo_type = '';
$arquivo_size = '';
$arquivo_tmp = '';
$valor_temp = 0;
$qtd_anexos_titular = 0;
//Outros arquivos
$arquivo_name2 = '';
$arquivo_error2 = 0;
$arquivo_type2 = '';
$arquivo_size2 = '';
$arquivo_tmp2 = '';
$total_arquivos = 0;
$arquivo_size_total = 0;
$tamanhoPermitidoTotal = 167772160; //160MB

$db->beginTransaction();

try {
//----------------------------------------------------------------------------------------------------------------------------  
//TITULAR

  $campos_erros_1 = "";
  $campos_erros_2 = "";

  for ($i = 0; $i <= $indiceAux; $i++) {

    $arquivo_name = $_FILES['anexo']['name'][$i];
    $arquivo_error = $_FILES['anexo']["error"][$i];
    $arquivo_type = $_FILES['anexo']["type"][$i];
    $arquivo_size = $_FILES['anexo']['size'][$i];
    $arquivo_tmp = $_FILES['anexo']["tmp_name"][$i];
    $campo_banco = $_POST['tabela_campo'][$i];
    $foto_caminho = $_POST['foto_caminho'][$i];
    $pessoa_id = $_POST['pessoa_id'][$i];
    $anexo_qtd = $_POST['anexo_qtd'][$i];

    if ($arquivo_name != "" && $pessoa_id != NULL && $campo_banco != "" && $arquivo_size != 0) {
      //SOMANDO O SIZE TOTAL DOS ARQUIVOS
      $arquivo_size_total += $arquivo_size;
      //TOTAL GERAL DE ARQUIVOS
      $total_arquivos++;
      //VERIFICA SE A EXTENSAO DO ARQUIVO ESTÁ CORRETO
      if (!in_array($arquivo_type, $tiposPermitidos)) {
        $campos_erros_1 .= "" . ctexto($campo_banco, "mai") . "; ";
        $msg = "Erro no Titular, por favor selecione um arquivo de formato válido.\\n\\nCampo(s) com erro: $campos_erros_1\\n\\n";
        $erro = true;
      } else if ($arquivo_size >= $tamanhoPermitido) {
        $campos_erros_2 .= "" . ctexto($campo_banco, "mai") . "; ";
        $msg = "Erro nos anexos do Titular\\n\\nMotivo: Por favor, selecione arquivos com tamanho máximo de 1MB.\\nCampo(s) com erro: $campos_erros_2\\n\\n";
        $erro = true;
      }
    }
  }
//----------------------------------------------------------------------------------------------------------------------------
//CONJUGE
  $campos_erros_conjuge_1 = "";
  $campos_erros_conjuge_2 = "";
  if (is_numeric(pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, ""))) {
    for ($ii = 12; $ii <= $index_anexo_conjuge; $ii++) {

      $arquivo_name_conjuge = $_FILES['anexo']['name'][$ii]; //$arquivo["name"][$i];
      $arquivo_error_conjuge = $_FILES['anexo']["error"][$ii];
      $arquivo_type_conjuge = $_FILES['anexo']["type"][$ii];
      $arquivo_size_conjuge = $_FILES['anexo']['size'][$ii]; //$arquivo["size"][$i];
      $arquivo_tmp_conjuge = $_FILES['anexo']["tmp_name"][$ii];
      $campo_banco_conjuge = $_POST['tabela_campo'][$ii];
      $foto_caminho_conjuge = $_POST['foto_caminho'][$ii];
      $pessoa_id_conjuge = $_POST['pessoa_id'][$ii];

      if ($arquivo_name_conjuge != "" && $pessoa_id_conjuge != NULL && $campo_banco_conjuge != "" && $arquivo_size_conjuge != 0 && $erro == false) {
        //SOMANDO O SIZE TOTAL DOS ARQUIVOS
        $arquivo_size_total += $arquivo_size_conjuge;
        //TOTAL GERAL DE ARQUIVOS
        $total_arquivos++;
        //VERIFICA SE A EXTENSAO DO ARQUIVO ESTÁ CORRETO
        if (!in_array($arquivo_type_conjuge, $tiposPermitidos)) {
          $campos_erros_conjuge_1 .= "" . ctexto($campo_banco_conjuge, "mai") . "; ";
          $msg = "Erro no Cônjuge, por favor selecione um arquivo de formato válido.\\n\\nCampo(s) com erro: $campos_erros_conjuge_1\\n\\n";
          $erro = true;
        } else if ($arquivo_size_conjuge >= $tamanhoPermitido) {
          $campos_erros_conjuge_2 .= "" . ctexto($campo_banco_conjuge, "mai") . "; ";
          $msg = "Erro nos anexos do Cônjuge\\n\\nMotivo: Por favor, selecione arquivos com tamanho máximo de 1MB.\\nCampo(s) com erro: $campos_erros_conjuge_2\\n\\n";
          $erro = true;
        }
      }
    }
  }
//----------------------------------------------------------------------------------------------------------------------------
//FAMILIAR
  $campos_erros_familiar_1 = "";
  $campos_erros_familiar_2 = "";
  if (is_numeric(pesquisar_tabela("id", "hab_familiar", "hab_candidato_id", "=", $candidato_id, ""))) {
    for ($ii = 23; $ii <= $index_anexo_familiar; $ii++) {

      $arquivo_name_familiar = $_FILES['anexo']['name'][$ii]; //$arquivo["name"][$i];
      $arquivo_error_familiar = $_FILES['anexo']["error"][$ii];
      $arquivo_type_familiar = $_FILES['anexo']["type"][$ii];
      $arquivo_size_familiar = $_FILES['anexo']['size'][$ii]; //$arquivo["size"][$i];
      $arquivo_tmp_familiar = $_FILES['anexo']["tmp_name"][$ii];
      $campo_banco_familiar = $_POST['tabela_campo'][$ii];
      $foto_caminho_familiar = $_POST['foto_caminho'][$ii];
      $pessoa_id_familiar = $_POST['pessoa_id'][$ii];

      if ($arquivo_name_familiar != "" && $pessoa_id_familiar != NULL && $campo_banco_familiar != "" && $arquivo_size_familiar != 0 && $erro == false) {
        //SOMANDO O SIZE TOTAL DOS ARQUIVOS
        $arquivo_size_total += $arquivo_size_familiar;
        //TOTAL GERAL DE ARQUIVOS
        $total_arquivos++;
        //VERIFICA SE A EXTENSAO DO ARQUIVO ESTÁ CORRETO
        if (!in_array($arquivo_type_familiar, $tiposPermitidos)) {
          $campos_erros_familiar_1 .= "" . ctexto($campo_banco_familiar, "mai") . "; ";
          $msg = "Erro no Familiar " . pesquisar_tabela("nome", "hab_pessoa", "id", "=", $pessoa_id_familiar, "") . ", por favor selecione um arquivo de formato válido.\\n\\nCampo(s) com erro: $campos_erros_familiar_1\\n\\n";
          $erro = true;
        } else if ($arquivo_size_familiar >= $tamanhoPermitido) {
          $campos_erros_familiar_2 .= "" . ctexto($campo_banco_familiar, "mai") . "; ";
          $msg = "Erro nos anexos do Familiar " . pesquisar_tabela("nome", "hab_pessoa", "id", "=", $pessoa_id_familiar, "") . "\\n\\nMotivo: Por favor, selecione arquivos com tamanho máximo de 1MB.\\nCampo(s) com erro: $campos_erros_familiar_2\\n\\n";
          $erro = true;
        }
      }
    }
  }

  //----------------------------------------------------------------------------------------------------------------------------
//OUTROS ANEXOS

  $nome_digitado_1 = @$_POST['nome_digitado'];
  $outro_arquivo_1 = $_FILES['outro_anexo'];

  $campos_erros_outro_1 = "";
  $campos_erros_outro_2 = "";

  foreach ($nome_digitado_1 as $key => $value) {
    if ($value != "") {
      $arquivo_name_outro = $_FILES['outro_anexo']['name'][$key];
      $arquivo_error_outro = $outro_arquivo_1["error"][$key];
      $arquivo_type_outro = $outro_arquivo_1["type"][$key];
      $arquivo_size_outro = $_FILES['outro_anexo']['size'][$key];
      $arquivo_tmp_outro = $outro_arquivo_1["tmp_name"][$key];

      if ($arquivo_name_outro != "" && $arquivo_size_outro != 0 && $erro == false) {
        //SOMANDO O SIZE TOTAL DOS ARQUIVOS
        $arquivo_size_total += $arquivo_size_outro;
        //TOTAL GERAL DE ARQUIVOS
        $total_arquivos++;
        //VERIFICA SE A EXTENSAO DO ARQUIVO ESTÁ CORRETO
        if (!in_array($arquivo_type_outro, $tiposPermitidos)) {
          $campos_erros_outro_1 .= "" . ctexto($arquivo_name_outro, "mai") . "; ";
          $msg = "Erro em outros Anexos, por favor selecione um arquivo de formato válido.\\n\\nCampo(s) com erro: $campos_erros_outro_1\\n\\n";
          $erro = true;
        } else if ($arquivo_size_outro > $tamanhoPermitido) {
          $campos_erros_outro_2 .= "" . ctexto($arquivo_name_outro, "mai") . "; ";
          $msg = "Erro em outros Anexos\\n\\nMotivo: Por favor, selecione arquivos com tamanho máximo de 1MB.\\nArquivo(s) com erro: $campos_erros_outro_2\\n\\n";
          $erro = true;
        }
      }
    }
  }

  if ($anexo_qtd < 11 && $erro == false && vf_retroativo($candidato_id) == false) {
    $msg = "É necessário adicionar todos os anexos do titular para salvar esse formulário.";
    $erro = true;
  }

  if ($arquivo_size_total > $tamanhoPermitidoTotal && $erro == false) {
    $msg = "Os arquivos anexados ultrapassaram o limite total de 160MB permitidos pelo servidor.";
    $erro = true;
  }

  if ($total_arquivos > 280) {
    $msg = "A quantidade de arquivos anexados ultrapassarram o limite total de 280 arquivos permitidos pelo servidor.";
    $erro = true;
  }

//----------------------------------------------------------------------------------------------------------------------------
  if ($erro == false) {

//ATUALIZANDO A SITUAÇÃO DO CADASTRO DE CANDIDATO
    $stmt = $db->prepare("UPDATE hab_candidato SET situacao = ? WHERE id = ?");
    $stmt->bindValue(1, $situacao);
    $stmt->bindValue(2, $candidato_id);
    $stmt->execute();

    $stmt = $db->prepare("INSERT INTO hab_candidato_situacao (hab_candidato_id, seg_usuario_pai_id, hab_tipo_situacao_id, data_cadastro, status) VALUES (?, ?, ?, NOW(), 1)");
    $stmt->bindValue(1, $candidato_id);
    $stmt->bindValue(2, $_SESSION['id']);
    $stmt->bindValue(3, $situacao);
    $stmt->execute();

//----------------------------------------------------------------------------------------------------------------------------
//TITULAR
    for ($i = 0; $i <= $indiceAux; $i++) {

      $arquivo_name = $_FILES['anexo']['name'][$i]; //$arquivo["name"][$i];
      $arquivo_error = $_FILES['anexo']["error"][$i];
      $arquivo_type = $_FILES['anexo']["type"][$i];
      $arquivo_size = $_FILES['anexo']['size'][$i]; //$arquivo["size"][$i];
      $arquivo_tmp = $_FILES['anexo']["tmp_name"][$i];
      $campo_banco = $_POST['tabela_campo'][$i];
      $foto_caminho = $_POST['foto_caminho'][$i];
      $pessoa_id = $_POST['pessoa_id'][$i];

      if (!is_numeric(pesquisar_tabela("id", "hab_pessoa_anexo", "hab_pessoa_id", "=", $pessoa_id, "AND $campo_banco = '$foto_caminho'")) && $arquivo_name != "" && $pessoa_id != "" && $pessoa_id != 0 && $pessoa_id != NULL) {

        //VERIFICAR
        if ($arquivo_error > 0) {
          $msg = "Error ao tentar fazer o upload do arquivo.";
          $erro = true;
        } else {

          //Pega a extensão do arquivo enviado
          $tmp = explode('.', $arquivo_name);
          $extensao = strtolower(end($tmp));

          //Gera um nome único para o arquivo
          $nomearquivo = md5(uniqid(time())) . "." . $extensao;

          if (!file_exists("../anexos/sishab/pessoa/")) {
            mkdir("../anexos/sishab/pessoa/", 0777);
          }

          //Move arquivo para a pasta informada
          if (move_uploaded_file($arquivo_tmp, "../anexos/sishab/pessoa/" . $nomearquivo)) {
            if (is_numeric(pesquisar_tabela("id", "hab_pessoa_anexo", "hab_pessoa_id", "=", $pessoa_id, ""))) {
              $stmt = $db->prepare("UPDATE hab_pessoa_anexo SET $campo_banco = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_pessoa_id = ?");
              $stmt->bindValue(1, "anexos/sishab/pessoa/" . $nomearquivo);
              $stmt->bindValue(2, $_SESSION['id']);
              $stmt->bindValue(3, $pessoa_id);
              $stmt->execute();
            } else {
              $stmt = $db->prepare("INSERT INTO hab_pessoa_anexo ($campo_banco, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (?, ?, NOW(), 1, ?)");
              $stmt->bindValue(1, "anexos/sishab/pessoa/" . $nomearquivo);
              $stmt->bindValue(2, $pessoa_id);
              $stmt->bindValue(3, $_SESSION['id']);
              $stmt->execute();
            }
          }
        }
      }
    }//END IF
//----------------------------------------------------------------------------------------------------------------------------
//CONJUGE
    if (is_numeric(pesquisar_tabela("id", "hab_conjuge", "hab_candidato_id", "=", $candidato_id, ""))) {
      for ($ii = 12; $ii <= $index_anexo_conjuge; $ii++) {

        $arquivo_name = $_FILES['anexo']['name'][$ii]; //$arquivo["name"][$i];
        $arquivo_error = $_FILES['anexo']["error"][$ii];
        $arquivo_type = $_FILES['anexo']["type"][$ii];
        $arquivo_size = $_FILES['anexo']['size'][$ii]; //$arquivo["size"][$i];
        $arquivo_tmp = $_FILES['anexo']["tmp_name"][$ii];
        $campo_banco = $_POST['tabela_campo'][$ii];
        $foto_caminho = $_POST['foto_caminho'][$ii];
        $pessoa_id_conjuge = $_POST['pessoa_id'][$ii];

        if (!is_numeric(pesquisar_tabela("id", "hab_pessoa_anexo", "hab_pessoa_id", "=", $pessoa_id_conjuge, "AND $campo_banco = '$foto_caminho'")) && $arquivo_name != "" && $pessoa_id_conjuge != "" && $pessoa_id_conjuge != 0 && $pessoa_id_conjuge != NULL) {

          //VERIFICAR
          if ($arquivo_error > 0) {
            $msg = "Error ao tentar fazer o upload do arquivo.";
            $erro = true;
          } else {

            // Pega a extensão do arquivo enviado
            $tmp = explode('.', $arquivo_name);
            $extensao = strtolower(end($tmp));
            // Gera um nome único para o arquivo
            $nomearquivo = md5(uniqid(time())) . "." . $extensao;
            if (!file_exists("../anexos/sishab/pessoa/")) {
              mkdir("../anexos/sishab/pessoa/", 0777);
            }
            //Move arquivo para a pasta informada
            move_uploaded_file($arquivo_tmp, "../anexos/sishab/pessoa/" . $nomearquivo);

            if (is_numeric(pesquisar_tabela("id", "hab_pessoa_anexo", "hab_pessoa_id", "=", $pessoa_id_conjuge, ""))) {
              $stmt = $db->prepare("UPDATE hab_pessoa_anexo SET $campo_banco = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_pessoa_id = ?");
              $stmt->bindValue(1, "anexos/sishab/pessoa/" . $nomearquivo);
              $stmt->bindValue(2, $_SESSION['id']);
              $stmt->bindValue(3, $pessoa_id_conjuge);
              $stmt->execute();
            } else {
              $stmt = $db->prepare("INSERT INTO hab_pessoa_anexo ($campo_banco, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (?, ?, NOW(), 1, ?)");
              $stmt->bindValue(1, "anexos/sishab/pessoa/" . $nomearquivo);
              $stmt->bindValue(2, $pessoa_id_conjuge);
              $stmt->bindValue(3, $_SESSION['id']);
              $stmt->execute();
            }
          }
        }
      }//END IF
    }
//----------------------------------------------------------------------------------------------------------------------------
//FAMILIAR
    if (is_numeric(pesquisar_tabela("id", "hab_familiar", "hab_candidato_id", "=", $candidato_id, ""))) {
      for ($iii = 23; $iii <= $index_anexo_familiar; $iii++) {

        $arquivo_name = $_FILES['anexo']['name'][$iii];
        $arquivo_error = $_FILES['anexo']["error"][$iii];
        $arquivo_type = $_FILES['anexo']["type"][$iii];
        $arquivo_size = $_FILES['anexo']['size'][$iii];
        $arquivo_tmp = $_FILES['anexo']["tmp_name"][$iii];
        $campo_banco = $_POST['tabela_campo'][$iii];
        $foto_caminho = $_POST['foto_caminho'][$iii];
        $pessoa_id_familiar = $_POST['pessoa_id'][$iii];

        if (!is_numeric(pesquisar_tabela("id", "hab_pessoa_anexo", "hab_pessoa_id", "=", $pessoa_id_familiar, "AND $campo_banco = '$foto_caminho'")) && $arquivo_name != "" && $pessoa_id_familiar != "" && $pessoa_id_familiar != 0 && $pessoa_id_familiar != NULL) {

          //VERIFICAR
          if ($arquivo_error > 0) {
            $msg = "Error ao tentar fazer o upload do arquivo.";
            $erro = true;
          } else {

            // Pega a extensão do arquivo enviado
            $tmp = explode('.', $arquivo_name);
            $extensao = strtolower(end($tmp));
            // Gera um nome único para o arquivo
            $nomearquivo = md5(uniqid(time())) . "." . $extensao;
            if (!file_exists("../anexos/sishab/pessoa/")) {
              mkdir("../anexos/sishab/pessoa/", 0777);
            }
            //Move arquivo para a pasta informada
            move_uploaded_file($arquivo_tmp, "../anexos/sishab/pessoa/" . $nomearquivo);

            if (is_numeric(pesquisar_tabela("id", "hab_pessoa_anexo", "hab_pessoa_id", "=", $pessoa_id_familiar, ""))) {
              $stmt = $db->prepare("UPDATE hab_pessoa_anexo SET $campo_banco = ?, data_update = NOW(), seg_usuario_pai_id = ? WHERE hab_pessoa_id = ?");
              $stmt->bindValue(1, "anexos/sishab/pessoa/" . $nomearquivo);
              $stmt->bindValue(2, $_SESSION['id']);
              $stmt->bindValue(3, $pessoa_id_familiar);
              $stmt->execute();
            } else {
              $stmt = $db->prepare("INSERT INTO hab_pessoa_anexo ($campo_banco, hab_pessoa_id, data_cadastro, status, seg_usuario_pai_id) VALUES (?, ?, NOW(), 1, ?)");
              $stmt->bindValue(1, "anexos/sishab/pessoa/" . $nomearquivo);
              $stmt->bindValue(2, $pessoa_id_familiar);
              $stmt->bindValue(3, $_SESSION['id']);
              $stmt->execute();
            }
          }
        }
      }//END IF
    }
//----------------------------------------------------------------------------------------------------------------------------
//OUTRO ANEXO

    $nome_digitado = @$_POST['nome_digitado'];
    $anexo_ids = "0";
    $outro_arquivo = $_FILES['outro_anexo'];

    //INSERIDO LINKS ATUALIZADOS DOS ANEXOS NO BANCO
    foreach ($nome_digitado as $key => $value) {

      $anexo_id = $_POST['anexo_id'][$key];

      if ($value != "") {
        $arquivo_name2 = $_FILES['outro_anexo']['name'][$key];
        $arquivo_error2 = $outro_arquivo["error"][$key];
        $arquivo_type2 = $outro_arquivo["type"][$key];
        $arquivo_size2 = $_FILES['outro_anexo']['size'][$key];
        $arquivo_tmp2 = $outro_arquivo["tmp_name"][$key];
      }

      if (is_numeric($anexo_id) && $value != "") {

        if (pesquisar_tabela("nomeAnexo", "hab_pessoa_outro_anexo", "id", "=", $anexo_id, "") != $_POST['anexo_nome'][$key]) {
          //Pega a extensão do arquivo enviado
          $tmp2 = explode('.', $arquivo_name2);
          //Joga um lower na extensão do arquivo
          $extensao2 = strtolower(end($tmp2));
          // Gera um nome único para o arquivo 
          $nomearquivo2 = md5(uniqid(time())) . "." . $extensao2;
          //Move arquivo para a pasta informada
          if (move_uploaded_file($arquivo_tmp2, "../anexos/sishab/pessoa/" . $nomearquivo2)) {
            $stmt1 = $db->prepare("UPDATE hab_pessoa_outro_anexo SET link = ?, nomeAnexo = ?, nome_digitado = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE id = ?");
            $stmt1->bindValue(1, "anexos/sishab/pessoa/" . $nomearquivo2);
            $stmt1->bindValue(2, $_POST['anexo_nome'][$key]);
            $stmt1->bindValue(3, $value);
            $stmt1->bindValue(4, $_SESSION['id']);
            $stmt1->bindValue(5, $anexo_id);
            $stmt1->execute();
          }
        } else {
          $stmt1 = $db->prepare("UPDATE hab_pessoa_outro_anexo SET nomeAnexo = ?, nome_digitado = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE id = ?");
          $stmt1->bindValue(1, $_POST['anexo_nome'][$key]);
          $stmt1->bindValue(2, $value);
          $stmt1->bindValue(3, $_SESSION['id']);
          $stmt1->bindValue(4, $anexo_id);
          $stmt1->execute();
        }

        $anexo_ids .= "," . $anexo_id;
      } else if ($value != "") {

        //Pega a extensão do arquivo enviado
        $tmp2 = explode('.', $arquivo_name2);
        //Joga um lower na extensão do arquivo
        $extensao2 = strtolower(end($tmp2));
        // Gera um nome único para o arquivo 
        $nomearquivo2 = md5(uniqid(time())) . "." . $extensao2;
        //Move arquivo para a pasta informada

        if (move_uploaded_file($arquivo_tmp2, "../anexos/sishab/pessoa/" . $nomearquivo2)) {
          $stmt2 = $db->prepare("INSERT INTO hab_pessoa_outro_anexo (link, nomeAnexo, nome_digitado, hab_candidato_id, seg_usuario_pai_id, data_cadastro, status) 
                 VALUES (?, ?, ?, ?, ?, NOW(), 1)");

          $stmt2->bindValue(1, "anexos/sishab/pessoa/" . $nomearquivo2);
          $stmt2->bindValue(2, $_POST['anexo_nome'][$key]);
          $stmt2->bindValue(3, $value);
          $stmt2->bindValue(4, $candidato_id);
          $stmt2->bindValue(5, $_SESSION['id']);
          $stmt2->execute();

          $anexo_ids .= "," . $db->lastInsertId();
        }
      }
    }

    //REMOVENDO ANEXO CASO TENHA SIDO DELATADO ALGUM ANEXO NA INTERFACE 
    $stmt1 = $db->prepare("DELETE FROM hab_pessoa_outro_anexo WHERE hab_candidato_id = ? AND id NOT IN ($anexo_ids)");
    $stmt1->bindValue(1, $candidato_id);
    $stmt1->execute();
//----------------------------------------------------------------------------------------------------------------------------
//OUTRAS OBSERVAÇÕES    

    $observacoes = @$_POST['observacao'];
    $obs_ids = "0";

    foreach ($observacoes as $key => $value) {

      $obs_id = $_POST['observacao_id'][$key];

      if (is_numeric($obs_id) && $value != "") {

        if (!is_numeric(pesquisar_tabela("id", "hab_candidato_observacao", "seg_usuario_pai_id", "=", $_SESSION['id'], "AND observacao = '$value' AND id = '$obs_id'"))) {
          $stmt1 = $db->prepare("UPDATE hab_candidato_observacao SET observacao = ?, seg_usuario_pai_id = ?, data_update = NOW() WHERE id = ?");
          $stmt1->bindValue(1, $value);
          $stmt1->bindValue(2, $_SESSION['id']);
          $stmt1->bindValue(3, $obs_id);
          $stmt1->execute();
        }

        $obs_ids .= "," . $obs_id;
      } else if ($value != "") {

        $stmt2 = $db->prepare("INSERT INTO hab_candidato_observacao (observacao, hab_candidato_id, seg_usuario_pai_id, data_cadastro, status) 
                 VALUES (?, ?, ?, NOW(), 1)");
        $stmt2->bindValue(1, $value);
        $stmt2->bindValue(2, $candidato_id);
        $stmt2->bindValue(3, $_SESSION['id']);
        $stmt2->execute();

        $obs_ids .= "," . $db->lastInsertId();
      }
    }

    //REMOVENDO OBS CASO TENHA SIDO DELATADO ALGUMA OBS NA INTERFACE 
    $stmt1 = $db->prepare("DELETE FROM hab_candidato_observacao WHERE hab_candidato_id = ? AND id NOT IN ($obs_ids)");
    $stmt1->bindValue(1, $candidato_id);
    $stmt1->execute();
//----------------------------------------------------------------------------------------------------------------------------

    $db->commit();

    $msg = "Arquivos salvos com sucesso!";
    $erro = false;
  }
} catch (PDOException $e) {
  $msg = "Erro ao tentar salvar os arquivos no servidor.";
  $erro = true;
}
?>

<div class="page-loader palette-Teal bg"></div>

<!-- Javascript Libraries -->
<script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.min.js"></script>

<?php
if ($erro) {
  echo "<script>swal({
      title: 'Formulário de Candidato',
      text: '" . $msg . "',
      type: 'error',
      showCancelButton: false,
      confirmButtonColor: '#8CD4F5',
      confirmButtonText: 'OK',
      closeOnConfirm: false
    }, function () {
        history.go(-1);
        return false;
     });</script>";
} else {
  if ($caminho_pagina == "lista") {
    if (vf_retroativo($candidato_id)) {
      echo "<script>swal({
      title: 'Formulário de Candidato',
      text: '" . $msg . "',
      type: 'success',
      showCancelButton: false,
      confirmButtonColor: '#8CD4F5',
      confirmButtonText: 'OK',
      closeOnConfirm: false
    }, function () {
        window.location = '" . PORTAL_URL . "sistema/candidato/lista_morto';
        return false;
      });</script>";
    } else {
      echo "<script>swal({
      title: 'Formulário de Candidato',
      text: '" . $msg . "',
      type: 'success',
      showCancelButton: false,
      confirmButtonColor: '#8CD4F5',
      confirmButtonText: 'OK',
      closeOnConfirm: false
    }, function () {
        window.location = '" . PORTAL_URL . "sistema/candidato/lista';
        return false;
      });</script>";
    }
  } else {
    echo "<script>swal({
      title: 'Formulário de Candidato',
      text: '" . $msg . "',
      type: 'success',
      showCancelButton: false,
      confirmButtonColor: '#8CD4F5',
      confirmButtonText: 'OK',
      closeOnConfirm: false
    }, function () {
        window.location = '" . PORTAL_URL . "sistema/candidato/$caminho_pagina/$candidato_id';
        return false;
      });</script>";
  }
}
?>