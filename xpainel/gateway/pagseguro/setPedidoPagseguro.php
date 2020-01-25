<?

function setOrderPagseguro($pedido_id,$token)

{

	$gateway='Pagseguro';

	try

	{



		require_once "lib/PagSeguroLibrary.php";





		class createPaymentRequest {



			public static function main ($gateway,$pedido_id,$token) {
				$parametros=L::getGateway($gateway);


				$paymentRequest = new PagSeguroPaymentRequest();
				$urlRetorno =HTTP.'/xpainel/gateway/pagseguro/updateRetornoPagseguro.php';
				// Sets the currency

				$paymentRequest->setCurrency("BRL");

				foreach ($_SESSION[X]['carrinho']['produtos'] as $produto)
				{
					$paymentRequest->addItem($produto['produto_id'], $produto['produto_nome'], $produto['produto_qtd'],U::moeda($produto['produto_preco']));
				}

				$frete = Frete::getFrete();

				if($frete['valor'] != '0.00')
				{
					$paymentRequest->addItem(256, 'FRETE', 1,U::moeda($frete['valor']));
				}





				// Sets a reference code for this payment request, it is useful to identify this payment in future notifications.

				$paymentRequest->setReference($token);



				// Sets shipping information for this payment request

				//$CODIGO_SEDEX = PagSeguroShippingType::getCodeByType('SEDEX');

				//$paymentRequest->setShippingType($CODIGO_SEDEX);

				//$paymentRequest->setShippingAddress($_SESSION['xpainel']['clientes_cep'],  $_SESSION['xpainel']['clientes_endereco'],  $_SESSION['xpainel']['clientes_numero'], $_SESSION['xpainel']['clientes_complemento'], $_SESSION['xpainel']['clientes_bairro'], $_SESSION['xpainel']['clientes_cidade'], $_SESSION['xpainel']['clientes_estado'], 'BRA');



				// Sets your customer information.

				$paymentRequest->setSender($_SESSION[X.X]['clientes_nome'], $_SESSION[X.X]['clientes_email'], '', '');



				$paymentRequest->setRedirectUrl($urlRetorno);
				$paymentRequest->setNotificationURL($urlRetorno);





				try {


					$credentials = new PagSeguroAccountCredentials($parametros['gateway_email'], $parametros['gateway_token']);

					$url = $paymentRequest->register($credentials);

					self::printPaymentUrl($url,$pedido_id,$urlRetorno,$token);

				} catch (PagSeguroServiceException $e) {

					die($e->getMessage());

				}

			}



			public static function printPaymentUrl($url,$pedido_id,$redireciona,$token) {



				if ($url) {

					L::msgCompra('pagseguro', $pedido_id, L::setLightBoxPagSeguro($url));

					L::setLinkPagamento($pedido_id, $url);

					L::sendStatusOrder($token);
					Cart::limpaCarrinho();
				}

			}





		}



		createPaymentRequest::main($gateway,$pedido_id,$token);





	}

	catch( Exception $e )

	{

		echo(sendErrors($e->getMessage(), __FUNCTION__.__LINE__));

	}

}

