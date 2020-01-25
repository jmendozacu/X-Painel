<?php
	
if(!isset($_POST['id_transacao']) || !isset($_POST['status_pagamento']))
	die("No, Thank s");

require_once($_SERVER['DOCUMENT_ROOT'].'/xpainel/class/config.php');	

function updateStatusPedidoMoip()
{
    try
	
    {
        
		$quebra = '';
		$escreve = '';
		$status_pedido = array(
		'1' => 'Autorizado',
		'2' => 'Iniciado',
		'3' => 'BoletoImpresso',
		'4' => 'Concluido',
		'5' => 'Cancelado',
		'6' => 'EmAnalise',
		'7' => 'Estornado',
		'9' => 'Reembolsado'
		);		
		
		$sql = "UPDATE pedidos SET pedidos_status='{$status_pedido[$_POST['status_pagamento']]}', pedidos_formapagamento = '{$_POST['tipo_pagamento']}'  WHERE pedidos_token='{$_POST['id_transacao']}'";
		$escreve.=$quebra.'_SESSION <pre>'.print_r($_SESSION, true).'</pre>'.$quebra;
		$escreve.=$quebra.'_POST<pre>'.print_r($_POST, true).'</pre> STATUS DO MEU PEDIDO -> '.$status_pedido[$_POST['status_pagamento']].''.$quebra;
		$escreve.=$quebra.'_GET<pre>'.print_r($_GET, true).'</pre>'.$quebra;
		$escreve.=$quebra.'_COOKIE<pre>'.print_r($_COOKIE, true).'</pre>'.$quebra;
		$escreve.=$quebra.'_SERVER<pre>'.print_r($_SERVER, true).'</pre>'.$quebra;
		$escreve.=$sql;
		
		X::logXpainel(__FUNCTION__.'-log',$escreve);	
		
        Transaction::open();
        $conexao = Transaction::getInstance();
        
		$query = $conexao->prepare($sql);
        $query->execute();
        Transaction::close();
        X::logXpainel(__FUNCTION__.'-log',$sql);
        L::sendStatusOrder($_POST['id_transacao']);

    }

    catch( Exception $e )
    {
        echo(sendErrors($e->getMessage(), __FUNCTION__.__LINE__));
    }
}



updateStatusPedidoMoip();
