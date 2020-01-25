<?
require_once('../../load.php');

$cielo = L::getGateway('cielo');

if(!isset ($_GET['hashCielo']))
{
	X::logXpainel('updateStatusPedidoCielo-log','hashCielo faltando');
	die('No Thanks');
}
if($_GET['hashCielo'] != $cielo['gateway_campo_adicional1'])
{
	X::logXpainel('updateStatusPedidoCielo-log','hashCielo errado->'.$_GET['hashCielo']);
	die('No Thanks');
}
if(! isset($_POST['payment_status']))
{
	X::logXpainel('updateStatusPedidoCielo-log','Sem Status');
    die('No Thanks');
}

if(! isset($_POST['order_number']))
{
	X::logXpainel('updateStatusPedidoCielo-log','Sem order_number');
    die('No Thanks');
}






$formasdepagamento = array(
	1 => 'Cartão de Crédito',
	2 => 'Boleto Bancário',
	3 => 'Débito Online',
	4 => 'Cartão de Débito'
);





switch ($_POST['payment_status'])
{
	case 1:
		$status = 'Pendente';
		// 1	Pendente	Para todos os meios de pagamento	Indica que o pagamento ainda está sendo processado; OBS: Boleto - Indica que o boleto não teve o status alterado pelo lojista
	break;

	case 2:
		$status = 'Pago';
		// 2	Pago	Para todos os meios de pagamento	Transação capturada e o dinheiro será depositado em conta.
	break;

	case 3:
		$status = 'Não Autorizado';
		// 3	Negado	Somente para Cartão Crédito	Transação não autorizada pelo responsável do meio de pagamento
	break;

	case 4:
		$status = 'Transação Expirada';
		// 4	Expirado	Cartões de Crédito e Boleto	Transação deixa de ser válida para captura - 15 dias pós Autorização
	break;

	case 5:
		$status = 'Cancelado';
		// 5	Cancelado	Para cartões de crédito	Transação foi cancelada pelo lojista
	break;

	case 6:
		$status = 'Venda Não Finalizado';
		// 6	Não Finalizado	Todos os meios de pagamento	Pagamento esperando Status - Pode indicar erro ou falha de processamento. Entre em contato com o Suporte cielo
	break;

	case 7:
		$status = 'Autorizado';
		// 7	Autorizado	somente para Cartão de Crédito	Transação autorizada pelo emissor do cartão. Deve ser capturada para que o dinheiro seja depositado em conta
	break;

	case 8:
		$status = 'Valor Extornado';
		// 8	Chargeback	somente para Cartão de Crédito	Transação cancelada pelo consumidor junto ao emissor do cartão. O Dinheiro não será depositado em conta.
	break;

	default:
		$status = 'Erro de Processamento';
}




$upPay ="UPDATE pedidos SET pedidos_status='{$status}', pedidos_formapagamento='{$formasdepagamento[$_POST['payment_method_type']]}' WHERE pedidos_id=".$_POST['order_number'];
X::logXpainel('updateStatusPedidoCielo-log-'.$_POST['order_number'],'Query: '.$upPay);
if(Sql::_query($upPay))
{
	$dadoP = Sql::_fetch("SELECT pedidos_token FROM pedidos WHERE pedidos_id=".$_POST['order_number']);
}
L::sendStatusOrder($dadoP['pedidos_token']);
