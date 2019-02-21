<?php
//OBS: O USUÁRIO COM ID 1 CADASTRADO TEM PERMISSÃO GERAL
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// VERIFICAR A PERMISSÃO NO MÓDULO
// DESCRIÇÃO: VERIFICAR A PERMISSÃO NO MÓDULO
function vf_modulo() {
  @session_start();

  $db = Conexao::getInstance();

  $result = $db->prepare("SELECT moa.id
                          FROM seg_modulo_objeto_acao moa
                          LEFT JOIN seg_modulo AS m ON m.id = moa.modulo_id
                          LEFT JOIN seg_objeto AS o ON o.id = moa.objeto_id
                          WHERE m.nome = ? AND o.nome = ?");

  $result->bindValue(1, "" . $GLOBALS['urlModulo'] . "");
  $result->bindValue(2, "" . $GLOBALS['urlPasta'] . "_" . $GLOBALS['urlArquivo'] . "");

  $result->execute();

  if ($result->rowCount() > 0) {//VERIFICA SE FOI CADASTRADO PERMISSÃO NO MÓDULO
    if ($GLOBALS['urlModulo'] != 'dashboard' || $_SESSION['id'] == 1) {
      if (isset($_SESSION['permissao']["" . $GLOBALS['urlModulo'] . ""]) || $_SESSION['id'] == 1) {
        return true; //TEM PERMISSÃO
      } else {
        // echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "dashboard';</script>"; //NÃO TEM PERMISSÃO
      }
    } else {
      return true; //TEM PERMISSÃO
    }
  }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// VERIFICAR OBJETO AÇÃO
// DESCRIÇÃO: VERIFICAR A PERMISSÃO DO OBJETO/AÇÃO
function vf_objeto_acao($acao) {
  
  @session_start();

  if ($GLOBALS['urlArquivo'] == "etapa1" || $GLOBALS['urlArquivo'] == "etapa2" || $GLOBALS['urlArquivo'] == "etapa3" || $GLOBALS['urlArquivo'] == "etapa4") {
    $objeto = "cadastro";
  } else {
    $objeto = $GLOBALS['urlArquivo'];
  }

  if (isset($_SESSION['permissao']["" . $GLOBALS['urlModulo'] . ""]["" . $GLOBALS['urlPasta'] . "_" . $objeto . ""]["" . $acao . ""]) || $_SESSION['id'] == 1) {
    return true; //TEM PERMISSÃO
  } else {
    return false; //NÃO TEM PERMISSÃO
  }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// VERIFICAR OBJETO AÇÃO PÁGINA
// DESCRIÇÃO: VERIFICAR A PERMISSÃO DO OBJETO/AÇÃO PASSANDO A PÁGINA
function vf_objeto_acao_pagina($pagina, $acao) {
  @session_start();

  if (isset($_SESSION['permissao']["" . $GLOBALS['urlModulo'] . ""]["" . $pagina . ""]["" . $acao . ""]) || $_SESSION['id'] == 1) {
    return true; //TEM PERMISSÃO
  } else {
    return false; //NÃO TEM PERMISSÃO
  }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// VERIFICAR MÓDULO OBJETO AÇÃO PÁGINA
// DESCRIÇÃO: VERIFICAR A PERMISSÃO DO OBJETO/AÇÃO PASSANDO A PÁGINA
function vf_modulo_objeto_acao_pagina($modulo, $pagina, $acao) {
  @session_start();

  if (isset($_SESSION['permissao']["" . $modulo . ""]["" . $pagina . ""]["" . $acao . ""]) || $_SESSION['id'] == 1) {
    return true; //TEM PERMISSÃO
  } else {
    return false; //NÃO TEM PERMISSÃO
  }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// VERIFICAR PERMISSÃO NA PÁGINA ATUAL
// DESCRIÇÃO: VERIFICAR SE O USUÁRIO TEM PERMISSÃO PARA A PÁGINA
function vf_permissao_pagina($acao) {
  @session_start();

  $db = Conexao::getInstance();

  $result = $db->prepare("SELECT moa.id
                          FROM seg_modulo_objeto_acao moa
                          LEFT JOIN seg_modulo AS m ON m.id = moa.modulo_id
                          LEFT JOIN seg_objeto AS o ON o.id = moa.objeto_id
                          LEFT JOIN seg_acao AS a ON a.id = moa.acao_id
                          WHERE m.nome = ? AND o.nome = ? AND a.nome = ?");

  $result->bindValue(1, "" . $GLOBALS['urlModulo'] . "");
  $result->bindValue(2, "" . $GLOBALS['urlPasta'] . "_" . $GLOBALS['urlArquivo'] . "");
  $result->bindValue(3, $acao);

  $result->execute();

  if ($result->rowCount() > 0) {//VERIFICA SE FOI CADASTRADO PERMISSÃO NO MÓDULO
    if (isset($_SESSION['permissao']["" . $GLOBALS['urlModulo'] . ""]["" . $GLOBALS['urlPasta'] . "_" . $GLOBALS['urlArquivo'] . ""]["" . $acao . ""]) || $_SESSION['id'] == 1) {
      return true; //TEM PERMISSÃO
    } else {
      echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "dashboard';</script>"; //NÃO TEM PERMISSÃO
    }
  }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// VERIFICAR USUÁRIO PERMISSÃO
// DESCRIÇÃO: VERIFICAR A PERMISSÃO NO MÓDULO DE USUÁRIOS
function vf_usuario_permissao($usuario_id) {
  @session_start();
  if (isset($_SESSION['permissao']["" . $GLOBALS['urlModulo'] . ""]["" . $GLOBALS['urlPasta'] . "_" . $GLOBALS['urlArquivo'] . ""]["admin"]) || $_SESSION['id'] == $usuario_id || $_SESSION['id'] == 1) {
    return true; //TEM PERMISSÃO
  } else {
    return false; //NÃO TEM PERMISSÃO
  }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// VERIFICAR PERMISSÃO DE ADMINISTRADOR
// DESCRIÇÃO: VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADMINISTRADOR
function vf_administrador() {
  @session_start();
  if (isset($_SESSION['permissao']["" . $GLOBALS['urlModulo'] . ""]["" . $GLOBALS['urlPasta'] . "_" . $GLOBALS['urlArquivo'] . ""]["admin"]) || $_SESSION['id'] == 1) {
    return true; //TEM PERMISSÃO
  } else {
    return false; //NÃO TEM PERMISSÃO
  }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// GERADOR DE SENHA ALFANUMÉRICO MAIÚSCULO
// DESCRIÇÃO: GERA SENHA COMPOSTA POR LETRAS MAIÚSCULAS E NÚMEROS
function gera_senha($length = 6) {
  $saltAlfa = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $saltNum = "0123456789";
  $lenAlfa = strlen($saltAlfa);
  $lenNum = strlen($saltNum);
  $pass = '';
  mt_srand(10000000 * (double) microtime());
  for ($i = 0; $i < $length; $i ++) {
    $pass .=!($i % 2) ? ($saltAlfa[mt_rand(0, $lenAlfa - 1)]) : ($saltNum[mt_rand(0, $lenNum - 1)]);
  }
  return $pass;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------

?>