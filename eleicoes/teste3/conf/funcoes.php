<?php
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: PESQUISAR TABELA
// DESCRIÇÃO: PESQUISAR NO BANCO DE DADOS POR ALGUMA INFORMAÇÃO
function pesquisar_tabela($retorno, $tabela, $campo, $cond, $variavel, $add) {
  $db = Conexao::getInstance();

  $rs = $db->prepare("SELECT $retorno FROM $tabela WHERE $campo $cond ? $add");
  $rs->bindValue(1, "%" . $variavel . "%");
  $rs->execute();
  $dados = $rs->fetch(PDO::FETCH_ASSOC);

  return $dados[$retorno];
}
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: PESQUISAR VOTOS BRANCOS
// DESCRIÇÃO: PESQUISAR NO BANCO DE DADOS POR VOTOS BRANCOS
function pesquisar_brancos() {
    $votos = 0;
    
      $db = Conexao::getInstance();
    
        $result2 = $db->prepare("SELECT r.brancos AS votos
                                 FROM resultado r
                                 WHERE 1");
              $result2->execute();
              while ($rs2 = $result2->fetch(PDO::FETCH_ASSOC)) {
                  $votos += $rs2['votos'];
              }
              
    return $votos;
}
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: PESQUISAR VOTOS NULOS
// DESCRIÇÃO: PESQUISAR NO BANCO DE DADOS POR VOTOS NULOS
function pesquisar_nulos() {
    $votos = 0;
    
      $db = Conexao::getInstance();
    
        $result2 = $db->prepare("SELECT r.nulos AS votos
                                 FROM resultado r
                                 WHERE 1");
              $result2->execute();
              while ($rs2 = $result2->fetch(PDO::FETCH_ASSOC)) {
                  $votos += $rs2['votos'];
              }
              
    return $votos;
}
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: PESQUISAR AS SEÇÕES DO BAIRRO
// DESCRIÇÃO: RETORNA TODAS AS SEÇÕES DO BAIRRO
function buscar_secao($bairro) {

  $secoes = 0;    
    
  $db = Conexao::getInstance();
  
  $result = $db->prepare("SELECT *   
          FROM secao
          WHERE bairro = ?");
          $result->bindValue(1, $bairro);
          $result->execute();
          while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
              $result2 = $db->prepare("SELECT secao FROM resultado
                                       WHERE secao = ? AND zona = ?");
              $result2->bindValue(1, $rs['secao_numero']);
              $result2->bindValue(2, $rs['zona_id']);
              $result2->execute();
              while ($rs2 = $result2->fetch(PDO::FETCH_ASSOC)) {
                  //$secoes .= "".$rs2['secao'].", ";
                  $secoes++;
              }
          }
          
  //return substr_replace($secoes, '', -2);
  return $secoes;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: PESQUISAR OS VOTOS PELO BAIRRO
// DESCRIÇÃO: RETORNA TODAS OS VOTOS PELO BAIRRO
function buscar_votos($bairro, $candidato_id) {

  $votos = 0;    
    
  $db = Conexao::getInstance();
  
  $result = $db->prepare("SELECT *   
          FROM secao
          WHERE bairro = ?");
          $result->bindValue(1, $bairro);
          $result->execute();
          while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
              $result2 = $db->prepare("SELECT (SELECT rc.votos FROM resultado_candidato rc WHERE rc.resultado_id = r.id AND rc.candidato_id = ?) AS votos
                                       FROM resultado r
                                       WHERE r.secao = ? AND r.zona = ?");
              $result2->bindValue(1, $candidato_id);
              $result2->bindValue(2, $rs['secao_numero']);
              $result2->bindValue(3, $rs['zona_id']);
              $result2->execute();
              while ($rs2 = $result2->fetch(PDO::FETCH_ASSOC)) {
                  $votos += $rs2['votos'];
              }
          }
          
  return $votos;
}
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// NOME: PESQUISAR OS VOTOS DO CANDIDATO
// DESCRIÇÃO: RETORNA TODAS OS VOTOS DO CANDIDATO
function buscar_votos_candidato($candidato_id) {

  $votos = 0;    
    
  $db = Conexao::getInstance();
  
  $result = $db->prepare("SELECT * FROM secao");
          $result->execute();
          while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
              $result2 = $db->prepare("SELECT (SELECT rc.votos FROM resultado_candidato rc WHERE rc.resultado_id = r.id AND rc.candidato_id = ?) AS votos
                                       FROM resultado r
                                       WHERE r.secao = ? AND r.zona = ?");
              $result2->bindValue(1, $candidato_id);
              $result2->bindValue(2, $rs['secao_numero']);
              $result2->bindValue(3, $rs['zona_id']);
              $result2->execute();
              while ($rs2 = $result2->fetch(PDO::FETCH_ASSOC)) {
                  $votos += $rs2['votos'];
              }
          }
          
  return $votos;
}
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
?>