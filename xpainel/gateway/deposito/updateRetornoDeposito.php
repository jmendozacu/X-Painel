<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/topo.php');

$pedido = L::getDadosPedido();
$retorno = ''; 
if(! $pedido)
{
	die('Pedido não localizado');
}

$dadosParaDeposito = L::getGateway('deposito');

if($_POST)
{
	if($_POST['mensagem'] == '' && !isset($_FILES['comprovante_deposito']['name'][0][1]))
	{
		$retorno = '* Preencha os dados do Depósito Corretamente ou Anexe um Comprovante';
	}
	else
	{
				$id = $_GET['token'];
                //die($_GET['token']);
        		$result = Sql::_query("UPDATE pedidos SET pedidos_status = 'Pagamento Informado' WHERE pedidos_token = ?", array($_GET['token']));
				L::sendStatusOrder($_GET['token']);
				echo X::setComprovante('nome','assunto','comprovante_deposito');
				header('Location: '.HTTP.'/meus-pedidos.php');
	}
}
?>
<div class="container">
    <div class="row text-center">
        <div class="col-md-12">
            <h2>Informar Pagamento</h2>
            <h3>Dados para Depósito ou Transferência Bancária</h3>
            <br />
            <div>
                <?=L::getDescontoPedido($pedido)?>
                <br /><hr />
            </div>
            <div>
                <?=stripslashes(stripslashes($dadosParaDeposito['gateway_texto_extenso1']))?>
                <br /><hr />
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-12">
                <h3 class="bordered">Confirmação De Depósito Ou Transferência</h3>
                <h5>Número do Pedido: <?php echo $pedido['pedidos_id'];?></h5>
            </div>
            <div class="col-md-12">
                <?=L::getPedidoEmail($pedido['pedidos_id'],'Pedido: ',$pedido['clientes_id'])?> 
            </div>
        </div>
        <div class="col-md-6">             
            <h3>Depósito Ou Transferência</h3>
            <h5>Envie seu comprovante de depósito ou tranferência <br/> no formulário abaixo. Obrigado !!</h5>
            <form method="post" enctype="multipart/form-data" class="dialog-form" onSubmit="return validaComprovante()">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" class="form-control">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Dados do Depósito</label>
                     <textarea name="mensagem" class="form-control" ></textarea>
                </div>

                <div class="form-group">
                    <label>Comprovante de Pagamento</label>
                     <input class="le-input" type="file" id="comprovante" name="comprovante_deposito[]">
                </div>
                 <input type="hidden" name="xblock">
                 <input type="hidden" name="assunto" value="Comprovante de Depósito">
                <input type="submit"  value="Enviar Comprovante" class="btn btn-primary">
                <script>
					function validaComprovante()
					{
						if(document.getElementById('mensagem').value.length < 10 && document.getElementById('comprovante').value == '')
						{
							alert('Preencha os dados do Depósito Corretamente ou Anexe um Comprovante');
							return false;
						}
					}
				</script>
                            
                <h4 style="color:red"><br /><?=$retorno?></h4>
            </form>                       
        </div>
    </div>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/rodape.php');;?>
