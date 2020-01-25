<?

require_once('../../load.php');



header('Content-Type: text/html; charset=ISO-8859-1');



$parametros=L::getGateway('pagseguro');





define('TOKEN', $parametros['gateway_token']);



class PagSeguroNpi {

	

	private $timeout = 20; // Timeout em segundos

	

	public function notificationPost() {

		$postdata = 'Comando=validar&Token='.TOKEN;

		foreach ($_POST as $key => $value) {

			$valued    = $this->clearStr($value);

			$postdata .= "&$key=$valued";

		}
		X::logXpainel('updateStatusPedidoPagseguro-log','Post Data: <pre>'.print_r($postdata,true).'</pre>');
		return $this->verify($postdata);

	}

	

	private function clearStr($str) {

		if (!get_magic_quotes_gpc()) {

			$str = addslashes($str);

		}

		return $str;

	}

	

	private function verify($data) {

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, "https://pagseguro.uol.com.br/pagseguro-ws/checkout/NPI.jhtml");

		curl_setopt($curl, CURLOPT_POST, true);

		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_HEADER, false);

		curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$result = trim(curl_exec($curl));

		curl_close($curl);
		
		X::logXpainel('updateStatusPedidoPagseguro-log','Funcao Verify: <pre>'.print_r($result,true).'</pre>');
		
		
		return $result;

	}



}

function updateStatusPedidoPagseguro()
{
	try
	{
		$upPay ="UPDATE pedidos SET pedidos_formapagamento='{$_POST['TipoPagamento']}', pedidos_parcelas='{$_POST['Parcelas']}', pedidos_datatransacao='{$_POST['DataTransacao']}', pedidos_status='{$_POST['StatusTransacao']}' WHERE pedidos_token='{$_POST['Referencia']}'";
		X::logXpainel('updateStatusPedidoPagseguro-log','Query: '.$upPay);
		Transaction::open();
		$conexao = Transaction::getInstance();
		$conexao->beginTransaction();
		$query = $conexao->prepare($upPay);
		$query->execute();
		Transaction::close();
		L::sendStatusOrder($_POST['Referencia']);
		
	}
	catch( Exception $e )
	{
		X::logXpainel('updateStatusPedidoPagseguro-log',sendErrors($e->getMessage(), __FUNCTION__.__LINE__, false));
	  
	}		
}



if (count($_POST) > 0) {

	X::logXpainel('updateStatusPedidoPagseguro-log','POST recebido, indica que é a requisição do NPI.');

	$npi = new PagSeguroNpi();

	$result = $npi->notificationPost();

	X::logXpainel('updateStatusPedidoPagseguro-log','Retorno da consulta: <pre>'.print_r($result,true).'</pre>');

	$transacaoID = isset($_POST['TransacaoID']) ? $_POST['TransacaoID'] : '';

	

	if ($result == "VERIFICADO") {

		updateStatusPedidoPagseguro();

	} else if ($result == "FALSO") {

		X::logXpainel('updateStatusPedidoPagseguro-log','O post não foi validado pelo PagSeguro.');

	} else {

		X::logXpainel('updateStatusPedidoPagseguro-log','Erro na integração com o PagSeguro');

	}

	

} else {

	X::logXpainel('updateStatusPedidoPagseguro-log','POST não recebido, indica que a requisição é o retorno do Checkout PagSeguro.');
	header('location: '.HTTP.'/meus-pedidos.php');

}




?>