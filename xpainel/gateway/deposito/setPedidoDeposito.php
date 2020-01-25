<?
function setOrderDeposito($pedido_id, $token)
{
	try
	{

	    $url = HTTP.'/xpainel/gateway/deposito/updateRetornoDeposito.php?token='.$token;

		$parametros=L::getGateway('deposito');

		$url = L::setLinkPagamento($pedido_id, $url);
		L::sendStatusOrder($token);
		//die(__LINE__.'===>');
		X::logXpainel(__CLASS__.'/'.__FUNCTION__.'-log','Compra Via Depósito | Parametros: <pre>'.print_r($parametros,1).'</pre> Sessão  <pre>'.print_r($_SESSION,1).'</pre>');
		
		L::limpaCarrinho();

		echo L::msgCompra('deposito', $pedido_id, $url);
	}

	catch( Exception $e )

	{

		echo(sendErrors($e->getMessage(), __FUNCTION__.__LINE__));

	}

}

