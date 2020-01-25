<?php

function setOrderMoip($pedido_id,$token)
{
 try
	{	
		
			
		
		//require_once($_SERVER['DOCUMENT_ROOT'].'/xpainel/class/config.php');
		//include_once "autoload.inc.php";
		//include_once "lib/MoipStatus.php";

		$moip = new Moip();
		
		if(DEBUG)
		{
		    /*
		     * login: moipsandbox@grupothx.com.br
		     * senha: grupothx666
		     */
			$moip_key = 'KJ8ODYK9AB3KIAMSWA3DL4YYCGFMEGATXRWJBGVQ';
			$moip_token = 'YJ38HZEVVCT0XNZTHBCD2SD1HYN9OHDH';
			$moip->setEnvironment('test');
		}
		else		
		{
			//$resultados_gateway =  L::getGateway('paypal');
			$moip_key = 'UVEYD1POE9NGFMKCXFNT0SCFO9ORNCHLNCSLQQVZ';
			$moip_token = 'WVQTGS31SMMYOV4DTVC3TKGY7IL1QJQK';
		}
		
		$moip->setCredential(array(
			'key' => $moip_key,
			'token' => $moip_token
		));
		

		$moip->setUniqueID($token);
		$moip->setValue(U::moeda($_SESSION[X]['carrinho']['total']));
		$moip->setReason('Pedido Número: '.$pedido_id);
		$moip->setPayer(array('name' => $_SESSION[X.X]['clientes_nome'],
		'email' => $_SESSION[X.X]['clientes_email'],
		'payerId' => $_SESSION[X.X]['clientes_id'],
		'billingAddress' => array('address' => $_SESSION[X]['sessao_cliente']['clientes_endereco'],
		'number' => $_SESSION[X]['sessao_cliente']['clientes_numero'],
		'complement' => $_SESSION[X]['sessao_cliente']['clientes_complemento'],
		'city' => $_SESSION[X]['sessao_cliente']['clientes_cidade'],
		'neighborhood' => $_SESSION[X]['sessao_cliente']['clientes_bairro'],
		'state' => $_SESSION[X]['sessao_cliente']['clientes_estado'],
		'country' => 'BRA',
		'zipCode' => $_SESSION[X]['sessao_cliente']['clientes_cep'],
		'phone' => '')));
		
		//PRODUTOS
		$guarda = '';			
		foreach ($_SESSION[X]['carrinho']['produtos'] as $produto)
		{
			 $moip->addMessage($produto['produto_nome'].' - R$ '.U::moeda($produto['produto_preco']));
		}
		## Setando Frete caso necessário
		if(Frete::getFrete('valor') > 0)
		{
			$moip->addMessage('O valor de '.Frete::getFrete('valor').' é referente ao frete de entrega.');
			$moip->setAdds(Frete::getFrete('valor'));
		}
		
		$moip->validate('Basic');
		$moip->setNotificationURL(HTTP.'/xpainel/gateway/moip/updateRetornoMoip.php');
		if($moip->send())
		{
			$retorno = $moip->getAnswer();			
			//die(var_dump($retorno));
			if($retorno->error == '')
			{
				X::logXpainel(__FUNCTION__.'-log','Pedido Realizado: <pre>'.print_r($retorno,1).'</pre>');
				L::setLinkPagamento($pedido_id, $retorno->payment_url);
				L::sendStatusOrder($token);
				L::limpaCarrinho();
				echo L::msgCompra('moip',$pedido_id,$retorno->payment_url);
			}
			else
			{
				X::logXpainel(__FUNCTION__.'-log','Pedido Realizado: <pre>'.print_r($retorno,1).'</pre>');
				die('Houve um erro na transação: '.$retorno->error);
			}

		
		}		
	}
	catch( Exception $e )
	{
		echo(sendErrors($e->getMessage(), __FUNCTION__.__LINE__));     
	}
}

die(setOrderMoip($pedido_id,$token));
