<?php
require_once('paypal-config.php');
function sendNvpRequest(array $requestNvp,$token)
{
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_URL, PAYPAL_APIENDPOINT);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestNvp)); 
    $response = urldecode(curl_exec($curl));  
    curl_close($curl);

    $responseNvp = array();  

    if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) 
	{
        foreach ($matches['name'] as $offset => $name) 
		{
            $responseNvp[$name] = $matches['value'][$offset];
        }
    }
  
    if (isset($responseNvp['ACK']) && $responseNvp['ACK'] != 'Success') 
	{
        for ($i = 0; isset($responseNvp['L_ERRORCODE' . $i]); ++$i) 
		{
			$message = sprintf("PayPal NVP %s[%d]: %s\n",
			$responseNvp['L_SEVERITYCODE' . $i],
			$responseNvp['L_ERRORCODE' . $i],
			$responseNvp['L_LONGMESSAGE' . $i]);
        }
    }  
    return $responseNvp;
}



function setOrderPaypal($pedido_id, $token)  
{
    try
    {
        $url_retorno = HTTP.'/xpainel/gateway/paypal/updateRetornoPaypal.php';
		$url_retorno_cancelamento = HTTP.'/xpainel/gateway/paypal/updateRetornoPaypalCancelamento.php?pedido='.$pedido_id.'&token_send='.$token;
        Transaction::open();
        $conexao = Transaction::getInstance();
        $conexao->beginTransaction();  
        $query = $conexao->prepare("SELECT p.*, i.*, prod.produto_id, prod.produto_nome
	                                   FROM pedidos p
		                                  INNER JOIN pedido_itens i ON p.pedidos_id = i.pedidos_id
			                                 INNER JOIN produto prod ON i.produto_id = prod.produto_id
			                                     WHERE p.pedidos_id = '{$pedido_id}' ");

        $query->execute();
        $result = $query->fetchAll();
        Transaction::close();        

        $requestNvp = array(
                'USER' => PAYPAL_USER,
                'PWD' => PAYPAL_PASS,
                'SIGNATURE' => PAYPAL_SIGNATURE,
                'VERSION' => PAYPAL_VERSION,
                'METHOD'=> 'SetExpressCheckout',
                'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
                'PAYMENTREQUEST_0_CURRENCYCODE' => 'BRL',
                'RETURNURL' => $url_retorno,
                'CANCELURL' => $url_retorno_cancelamento,
                'BUTTONSOURCE' => 'BR_EC_EMPRESA'
        );
         

        $i = 0;
        $valor_produtos = '';        

        foreach($result as $ped)
        {               

            $requestNvp['L_PAYMENTREQUEST_0_NAME'.$i.''] = utf8_encode($ped['produto_nome']);
            $requestNvp['L_PAYMENTREQUEST_0_AMT'.$i.''] = U::moeda($ped['pedido_itens_valor']);
            $requestNvp['L_PAYMENTREQUEST_0_QTY'.$i.''] = 1; 
            $valor_produtos += $ped['pedido_itens_valor'];  
            $i++;
        }    
        //die('------->'.$result[0]['pedidos_frete']);
        $requestNvp['PAYMENTREQUEST_0_SHIPPINGAMT'] = U::moeda($result[0]['pedidos_frete']);
        $requestNvp['PAYMENTREQUEST_0_ITEMAMT'] = U::moeda($valor_produtos); 
        $requestNvp['PAYMENTREQUEST_0_AMT'] = U::moeda($result[0]['pedidos_valor'] + $result[0]['pedidos_frete']); 
        $requestNvp['SHIPTOZIP'] = $result[0]['pedidos_entrega_cep'];
        $requestNvp['SHIPTOSTREET'] = $result[0]['pedidos_entrega_endereco'];
        $requestNvp['SHIPTOCITY'] = $result[0]['pedidos_entrega_cidade'];
        $requestNvp['SHIPTOSTATE'] = $result[0]['pedidos_entrega_estado'];
        $requestNvp['SHIPTOZIP'] = $result[0]['pedidos_entrega_cep'];
        $requestNvp['SHIPTOCOUNTRY'] = 'BR';
		$requestNvp['PAYMENTREQUEST_0_NOTIFYURL'] = HTTP.'/xpainel/gateway/paypal/updateRetornoPaypal.php';		
		$requestNvp['PAYMENTREQUEST_0_INVNUM'] = $pedido_id;
		$requestNvp['PAYMENTREQUEST_0_CUSTOM'] = $token;

        $responseNvp = sendNvpRequest($requestNvp, $token);
        
        if (isset($responseNvp['ACK']) && $responseNvp['ACK'] == 'Success')
        {

            $query = array(
                    'cmd'    => '_express-checkout',
                    'token'  => $responseNvp['TOKEN']
            );

           
            $redirectURL = sprintf('%s?%s', PAYPAL_URL, http_build_query($query)); 
            L::setLinkPagamento($pedido_id, $redirectURL);          
            L::sendStatusOrder($token);            		
			X::logXpainel(__FUNCTION__.'-log','Cliente enviado ao paypal | response-nvp | <pre>'.print_r($requestNvp,1).'</pre>');
			L::limpaCarrinho();
			return L::msgCompra('paypal', $pedido_id, $redirectURL);			
        }
        else 
        {
            X::logXpainel(__FUNCTION__.'-log','Erro Paypal | response-nvp: <pre>'.print_r($responseNvp,true).'</pre>');
            die('Desculpe, Sua compra não foi realizada corretamente.');
        } 
    }
    catch( Exception $e )
    {
        echo(sendErrors($e->getMessage(), __FUNCTION__.__LINE__));
    }  
}





