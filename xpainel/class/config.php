<?
/*
  +----------------------------------------------------------------------+
  | X-Painel V 1.2                                                       |
  +----------------------------------------------------------------------+
  | Copyright (c)      2020                                              |
  +----------------------------------------------------------------------+
  | Copyright  X-Painel Rental Code ®                                    |
  | Email :  contato@grupothx.com      			                         |
  | WhatsApp e Fixo : 1146450330                                         |
  | https://github.com/Grupo-THX/X-Painel                                |
  |           			                                                 |
  |        "Quanto mais aumenta meu conhecimento,                        |
  |                   mais evidente fica minha ignorância."              |
  |                                                                      |
  +----------------------------------------------------------------------+

          GGGGGGGGGGGGG
       GGG::::::::::::G
     GG:::::::::::::::G
    G:::::GGGGGGGG::::G
   G:::::G       GGGGGrrrrr   rrrrrrrrr  uuuuuu    uuuuuuppppp   ppppppppp     ooooooooooo
  G:::::G             r::::rrr:::::::::r u::::u    u::::up::::ppp:::::::::p  oo:::::::::::oo
  G:::::G             r:::::::::::::::::ru::::u    u::::up:::::::::::::::::po:::::::::::::::o
  G:::::G    GGGGGGGGGrr::::::rrrrr::::::u::::u    u::::upp::::::ppppp::::::o:::::ooooo:::::o
  G:::::G    G::::::::Gr:::::r     r:::::u::::u    u::::u p:::::p     p:::::o::::o     o::::o
  G:::::G    GGGGG::::Gr:::::r     rrrrrru::::u    u::::u p:::::p     p:::::o::::o     o::::o
  G:::::G        G::::Gr:::::r           u::::u    u::::u p:::::p     p:::::o::::o     o::::o
   G:::::G       G::::Gr:::::r           u:::::uuuu:::::u p:::::p    p::::::o::::o     o::::o
    G:::::GGGGGGGG::::Gr:::::r           u:::::::::::::::up:::::ppppp:::::::o:::::ooooo:::::o
     GG:::::::::::::::Gr:::::r            u:::::::::::::::p::::::::::::::::po:::::::::::::::o
       GGG::::::GGG:::Gr:::::r             uu::::::::uu:::p::::::::::::::pp  oo:::::::::::oo
          GGGGGG   GGGGrrrrrrr               uuuuuuuu  uuup::::::pppppppp      ooooooooooo
                                                          p:::::p
                                                          p:::::p
                                                         p:::::::p
                                                         p:::::::p
                                                         p:::::::p
                                                         ppppppppp

                            TTTTTTTTTTTTTTTTTTTTTTHHHHHHHHH     HHHHHHHHXXXXXXX       XXXXXXX
                            T:::::::::::::::::::::H:::::::H     H:::::::X:::::X       X:::::X
                            T:::::::::::::::::::::H:::::::H     H:::::::X:::::X       X:::::X
                            T:::::TT:::::::TT:::::HH::::::H     H::::::HX::::::X     X::::::X
                            TTTTTT  T:::::T  TTTTTT H:::::H     H:::::H XXX:::::X   X:::::XXX
                                    T:::::T         H:::::H     H:::::H    X:::::X X:::::X
                                    T:::::T         H::::::HHHHH::::::H     X:::::X:::::X
                                    T:::::T         H:::::::::::::::::H      X:::::::::X
                                    T:::::T         H:::::::::::::::::H      X:::::::::X
                                    T:::::T         H::::::HHHHH::::::H     X:::::X:::::X
                                    T:::::T         H:::::H     H:::::H    X:::::X X:::::X
                                    T:::::T         H:::::H     H:::::H XXX:::::X   X:::::XXX
                                  TT:::::::TT     HH::::::H     H::::::HX::::::X     X::::::X
                                  T:::::::::T     H:::::::H     H:::::::X:::::X       X:::::X
                                  T:::::::::T     H:::::::H     H:::::::X:::::X       X:::::X
                                  TTTTTTTTTTT     HHHHHHHHH     HHHHHHHHXXXXXXX       XXXXXXX
_____________________________________________________________________________________________
*/
//=======================================Configuração idioma,encode e hora
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL,'pt_BR.UTF8');
mb_internal_encoding('UTF8');
mb_regex_encoding('UTF8');
define('CHARSET','UTF-8');
//=============================================LOJAS VIRTUAIS
define ('X_ECOMMERCE', false);
define('ESTOQUE','GP'); // nome da coluna caso seja simples; GP(grade si) para estoques com uma caracteristicas primarias, GS(grade dupla) para estoques com duas  caracteristica
//=============================================CONSTANTES E GLOBAIS



$developer=''; //caso o site esteja em uma pasta. Exemplo: ambiente de testes
define('ACEITA_POST_REMOTO', false); // Define se o site aceita ou não posts de outros sites. Caso seja um ecomercce sete true para que o site possa receber posts de qualquer um. um Gateway possa
define('SET_UTF8_BD',true); // força o banco para UTF8. Pode resolver problemas de acentuação caso existam
define('HTTP','https://'.$_SERVER['HTTP_HOST'].$developer);
define('ROOT',$_SERVER['DOCUMENT_ROOT'].$developer);
define('MODE_DEVELOPER', file_exists(ROOT.'/xpainel/rest/'.$_SERVER['REMOTE_ADDR']));
define ('DEBUG', MODE_DEVELOPER);
define ('SUPER_DEBUG', $_SERVER['REMOTE_ADDR'] == FALSE);
define('TYPE',DEBUG?'xpainelinput':'hidden'); // crie os type hidden como TYPE e poderá vê-los no ambiente de desenvolvimento
define('DISPLAY',DEBUG?'block':'none'); // crie os elementos como DISPLAY  e poderá vê-los no ambiente de desenvolvimento

define('JQUERY','$'); //Resolve conflito js
define('X','hashXpainelSession-1');//Chave de criptografia.

// Dados Google
define('CAPTCHA_SITE_KEY','');
define('CAPTCHA_SITE_SECRET_KEY','');

// 
define('SCRIPTS_ADICIONAIS','
  <script src="//www.google.com/recaptcha/api.js"></script>'
);

$GLOBALS['Xdebug'] = array();
$GLOBALS['Xjs'] = array();

//==========================================autoLoad
require_once(ROOT.'/xpainel/class/autoLoadX.php');


//=====================================Tratamento de erros
ini_set('display_errors', DEBUG ? 'on' : 'off');
ini_set( 'display_errors', DEBUG ? 1 : 0 );
ini_set('error_reporting', DEBUG ? E_ALL : 0);
ini_set('log_errors', true);
ini_set('html_errors',DEBUG ? true : false);
ini_set('display_errors',DEBUG ? true : false); // production: FALSE, development: TRUE
error_reporting(DEBUG ? E_ALL : -1);

function getErrosX($errno, $errstr, $errfile, $errline)
{
    return Relatorios::setRelatorioErros($errno, $errstr, $errfile, $errline);
}

set_error_handler('getErrosX');
session_set_cookie_params(155520000);

ob_start();
session_start();
Traducao::setIdioma();

if(isset($_GET[X]))
{
  unset($_SESSION);
  session_destroy();
  die(X::alert(false, strtok($_SERVER['HTTP_REFERER'], '?'),false));
}

if(isset($_GET[X.'debug']))
{
  $debug = ROOT.'/xpainel/rest/'.$_SERVER['REMOTE_ADDR'];
  file_exists($debug) ? unlink($debug) : File::criar($debug);
  die(X::alert(false, strtok($_SERVER['HTTP_REFERER'], '?'),false));
}
