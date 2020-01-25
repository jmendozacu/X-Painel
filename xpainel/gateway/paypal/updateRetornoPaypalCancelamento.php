<?
//require_once '../../load.php';
require_once('paypal-config.php');
function updateRetornoPaypalCancelamento()
{
    try
    {
		
		$query_update = "UPDATE pedidos SET pedidos_status = 'Cancelado' WHERE pedidos_id ='{$_GET['pedido']}' AND pedidos_token = '{$_GET['token_send']}'"; 
		Transaction::open();	
		$conexao = Transaction::getInstance();	
		$sql = $query_update;
		X::logXpainel(__FUNCTION__,$sql);	
		$query = $conexao->prepare($sql);	
		$query->execute();	
		Transaction::close();	
		L::sendStatusOrder($_GET['token_send']); 
    }
    catch( Exception $e )
    {
        echo(sendErrors($e->getMessage(), __FUNCTION__.__LINE__));
    }
}



X::logXpainel('updateRetornoPaypalCancelamento','URL de Retorno do Cancelamento Paypal Acessada');
if(isset($_GET['token']))
{
	updateRetornoPaypalCancelamento();
}
header('Location: '.HTTP.'/meus-pedidos.php');












