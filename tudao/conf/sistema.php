<?PHP

header("Cache-Control: max-age=300, must-revalidate"); //CORRIGINDO O HISTORY.BACK
//DEFINIR TIMEZONE PADRÃO
date_default_timezone_set("Brazil/Acre");

//DEFININDO OS DADOS DE ACESSO AO BANCO DE DADOS
define("DB", 'mysql');
define("DB_HOST", "mysql556.umbler.com");
define("DB_NAME", "tudao");
define("DB_USER", "tudao");
define("DB_PASS", "1020304050a");

// CONFIGURACOES PADRAO DO SISTEMA
define("PORTAL_URL", 'https://acreideias.com.br/tudao/');
define("TITULOSISTEMA", 'Controle de Pedidos');
define("ASSETS_FOLDER", 'https://acreideias.com.br/tudao/assets/');
define("CSS_FOLDER", 'https://acreideias.com.br/tudao/assets/css/');
define("IMG_FOLDER", 'https://acreideias.com.br/tudao/assets/img/');
define("FAVICONSISTEMA", 'https://acreideias.com.br/tudao/assets/img/favicon.png');
define("JS_FOLDER", 'https://acreideias.com.br/tudao/assets/js/');
define("FONTS_FOLDER", 'https://acreideias.com.br/tudao/assets/fontes/');
define("PLUGINS_FOLDER", 'https://acreideias.com.br/tudao/assets/plugins/');
define("FUNCTIONS_FOLDER", 'https://acreideias.com.br/tudao/functions/');
define("FOLDER_IMG_PRODUTO", 'https://acreideias.com.br/tudao/assets/img/produtos/');
define("FOLDER_IMG_PRODUTO_2", 'https://acreideias.com.br/tudao/assets/img/tudao/');
define("PORTAL_URL_SERVIDOR", 'C:/xampp/htdocs/tudao/');

//$_SESSION['NOME_SISTEMA'] = '../../';
$_SESSION['NOME_SISTEMA'] = $_SERVER["DOCUMENT_ROOT"] . '/tudao/';

// ADICIONAR CLASSE DE CONEÇÃO
include_once("Conexao.class.php");
?>
