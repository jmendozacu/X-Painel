<?
//die($_SERVER['DOCUMENT_ROOT'].'/xpainel/class/config.php'); 
require_once($_SERVER['DOCUMENT_ROOT'].'/xpainel/class/config.php');
if(DEBUG)
{
	define('PAYPAL_USER', 'comprador2_api1.grupothx.com.br');
	define('PAYPAL_PASS', 'XALAS2WPCZRUAH9X');
	define('PAYPAL_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31AZxhhUcb6yg.TcumCztE1Uvc9Eih');
	define('PAYPAL_APIENDPOINT', 'https://api-3t.sandbox.paypal.com/nvp');	
	define('PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
}
else
{
	$resultados_gateway =  L::getGateway('paypal');
	define('PAYPAL_USER', $resultados_gateway['gateway_email']);
	define('PAYPAL_PASS', $resultados_gateway['gateway_token']);
	define('PAYPAL_SIGNATURE', $resultados_gateway['gateway_campo_adicional1']);
	define('PAYPAL_APIENDPOINT', 'https://api-3t.paypal.com/nvp');
	define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');
}
	define('PAYPAL_VERSION','108.0'); // 108.0