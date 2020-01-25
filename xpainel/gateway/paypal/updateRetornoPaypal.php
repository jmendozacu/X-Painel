<?
//require_once '../../load.php';
require_once('paypal-config.php');
function updateRetornoPaypal()
{
    try
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, PAYPAL_APIENDPOINT);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
        'USER' => PAYPAL_USER,
        'PWD' => PAYPAL_PASS,
        'SIGNATURE' => PAYPAL_SIGNATURE, 
        'METHOD' => 'GetExpressCheckoutDetails',
        'VERSION' => PAYPAL_VERSION, 
        'TOKEN' => $_GET['token']
        )));

        $response =  curl_exec($curl);

        curl_close($curl);

        $nvp = array();        

        if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) 
		{
            foreach ($matches['name'] as $offset => $name) 
			{
                $nvp[$name] = urldecode($matches['value'][$offset]);
            }
        }

		 
		
		if(_DoExpressCheckoutPayment($nvp['PAYERID'],$nvp['INVNUM'],$nvp['CUSTOM']))
		{
        	
			$query_update = "UPDATE pedidos SET pedidos_status = 'Aprovado', pedidos_codstatus ='{$nvp['PAYERID']}-{$nvp['PAYERSTATUS']}-{$nvp['TOKEN']}'  WHERE pedidos_id = {$nvp['INVNUM']} AND pedidos_token ='{$nvp['CUSTOM']}'"; 
			//die($query_update);
			Transaction::open();	
			$conexao = Transaction::getInstance();	
			$sql = $query_update;
			X::logXpainel(__FUNCTION__.'-log',$sql);	
			$query = $conexao->prepare($sql);	
			$query->execute();	
			Transaction::close();	
			L::sendStatusOrder($nvp['CUSTOM']); 
		}
		else
		{
			$query_update = "UPDATE pedidos SET pedidos_status = 'Aguardando Pagamento', pedidos_codstatus ='{$nvp['PAYERID']}-{$nvp['PAYERSTATUS']}-{$nvp['TOKEN']}'  WHERE pedidos_id = {$nvp['INVNUM']} AND pedidos_token ='{$nvp['CUSTOM']}'"; 
			//die($query_update);
			Transaction::open();	
			$conexao = Transaction::getInstance();	
			$sql = $query_update;
			X::logXpainel(__FUNCTION__.'-log',$sql);	
			$query = $conexao->prepare($sql);	
			$query->execute();	
			Transaction::close();	
			L::sendStatusOrder($nvp['CUSTOM']);
			$url_retorno = HTTP.'/meus-pedidos.php';
			header("Location: $url_retorno");	
		}
		
    }
    catch( Exception $e )
    {
        echo(sendErrors($e->getMessage(), __FUNCTION__.__LINE__));
    }
}

function _DoExpressCheckoutPayment($payerID,$pedido_id,$tokenBD)
{

	$myquery = "SELECT * FROM pedidos WHERE pedidos_id = {$pedido_id} AND pedidos_token ='{$tokenBD}'"; 
	Transaction::open();	
	$conexao = Transaction::getInstance();	
	$query = $conexao->prepare($myquery);
	$query->execute();
	$result = $query->fetch();
	Transaction::close();
	ini_set('session.bug_compat_42',0);
	ini_set('session.bug_compat_warn',0);

	$token =urlencode($_GET['token']);
	$paymentAmount =urlencode (U::moeda($result['pedidos_valor'] + $result['pedidos_frete']));
	
	$paymentType = urlencode('SALE');
	$currCodeType = urlencode('BRL');
	$payerID = urlencode($payerID);
	$serverName = urlencode($_SERVER['SERVER_NAME']);
	$nvpstr='&TOKEN='.$token.'&PAYERID='.$payerID.'&PAYMENTACTION='.$paymentType.'&AMT='.$paymentAmount.'&CURRENCYCODE='.$currCodeType.'&IPADDRESS='.$serverName ;
	 /* Make the call to PayPal to finalize payment
		If an error occured, show the resulting errors
		*/

	
	
	
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, PAYPAL_APIENDPOINT);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
        'USER' => PAYPAL_USER,
        'PWD' => PAYPAL_PASS,
		'METHOD' => '',
        'SIGNATURE' => PAYPAL_SIGNATURE, 
        'METHOD' => 'DoExpressCheckoutPayment',
        'VERSION' => PAYPAL_VERSION, 
		'PAYERID' => $payerID,
		'CURRENCYCODE' => $currCodeType,
		'PAYMENTACTION' => $paymentType,
		'AMT' => $paymentAmount,
		'IPADDRESS' => $serverName,
        'TOKEN' => $_GET['token']
        )));
        
        $response =  curl_exec($curl);
		X::logXpainel(__FUNCTION__.'-log','Verificação de Pagamento | response-nvp | <br />'.$response.'---');
        curl_close($curl);
        
        $nvp = array();        

        if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) 
		{
            foreach ($matches['name'] as $offset => $name) 
			{
                $nvp[$name] = urldecode($matches['value'][$offset]);
            }
        }	
	
	
	
	/* Display the API response back to the browser.
	If the response from PayPal was a success, display the response parameters'
	If the response was an error, display the errors received using APIError.php.
	*/
	X::logXpainel(__FUNCTION__.'-log','Verificação de Pagamento | response-nvp | <pre>'.print_r($nvp,1).'</pre>');
	
	/*
	[PAYMENTINFO_0_PAYMENTSTATUS] => Pending
    [PAYMENTINFO_0_PENDINGREASON] => paymentreview
	
	
	*/
	$ack = strtoupper($nvp["PAYMENTINFO_0_PAYMENTSTATUS"]);
	if($ack == 'COMPLETED')
	{
		return true;
	}
	return false;
}


X::logXpainel('updateRetornoPaypal-log','URL de Retorno Paypal Acessada');
if(isset($_GET['token']))
{
	updateRetornoPaypal();
}
header('Location: '.HTTP.'/meus-pedidos.php');












