<?
function trataValorCielo($valor){ 
    return str_replace(array(',','.'),'',$valor);
}
function setOrderCielo($pedido_id,$token)
{

	$gateway='cielo';
	try
	{
		$parametros=L::getGateway($gateway);

		$urlRetorno =HTTP.'/xpainel/gateway/pagseguro/updateRetornoPagseguro.php';

	    $order = new stdClass();
	    $order->OrderNumber = $pedido_id;
	    $order->SoftDescriptor = $pedido_id;
	    $order->Cart = new stdClass();
	    $order->Cart->Discount = new stdClass();
	    $order->Cart->Discount->Type = 'Percent';
	    $order->Cart->Discount->Value = 0;
	    $order->Cart->Items = array();
	
	    $it = 0;
	    foreach ($_SESSION[X]['carrinho']['produtos'] as $produto)
		{
			//echo __LINE__.'<<<<<<<<<br /><pre>'.print_r($produto, true).'</pre>';
			$order->Cart->Items[$it] = new stdClass();
            $order->Cart->Items[$it]->Name = U::limitaCaracteres($produto['produto_nome'], 125, '...');
            $order->Cart->Items[$it]->Description = U::limitaCaracteres($produto['produto_descricao'], 250, '...');
            $order->Cart->Items[$it]->UnitPrice = trataValorCielo($produto['produto_preco']);
            $order->Cart->Items[$it]->Quantity = $produto['produto_qtd'];
            $order->Cart->Items[$it]->Type = 'Digital';           
            $it++;
		}
	
		$frete = Frete::getFrete();

		if($frete['valor'] >'0')
		{
			$order->Cart->Items[$it] = new stdClass();
            $order->Cart->Items[$it]->Name = U::limitaCaracteres('Taxa de Entrega', 125, '...');
            $order->Cart->Items[$it]->Description = U::limitaCaracteres($frete['nome'].'-'.$frete['prazo'], 250, '...');
            $order->Cart->Items[$it]->UnitPrice = trataValorCielo($frete['valor']);
            $order->Cart->Items[$it]->Quantity = 1;
            $order->Cart->Items[$it]->Type = 'Digital'; 
		}


		
	    $order->Shipping = new stdClass();
	    $order->Shipping->Type = 'WithoutShipping';

	    $order->Payment = new stdClass();
	    $order->Payment->BoletoDiscount = 0;
	    $order->Payment->DebitDiscount = 0;

	    $order->Customer = new stdClass();
	    $order->Customer->Identity = str_pad((int)$invoiceid, 14, 0, STR_PAD_LEFT);
	    $order->Customer->FullName = Cliente::getDado('clientes_nome');
	    $order->Customer->Email = Cliente::getDado('clientes_email');
	    //$order->Customer->Phone = $clientsdetails['phonecc'].$clientsdetails['phonenumber'];
	    $order->Options = new stdClass();
	    $order->Options->AntifraudEnabled = false;
	    
	    $curl = curl_init();
	    
	    curl_setopt($curl, CURLOPT_URL, 'https://cieloecommerce.cielo.com.br/api/public/v1/orders');
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($order));
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	        'MerchantId: '.$parametros['gateway_token'],
	        'Content-Type: application/json'
	    ));
	 
	    $response = curl_exec($curl);

	    curl_close($curl);

	    $json = json_decode($response);    

	   
	    if(isset($json->settings->checkoutUrl))
	    {
	        $urlCielo= $json->settings->checkoutUrl;
	  
	        L::msgCompra('cielo', $pedido_id, $urlCielo);
			L::setLinkPagamento($pedido_id, $urlCielo);
			L::sendStatusOrder($token);
			L::limpaCarrinho();

	    }
	    else
	    {
	    	Layout::setError('Houve um erro ao processar seu pagamento');
	    }
	    
    }
	catch( Exception $e )
	{
		X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
	}
    
}