<?php require_once '../../load.php';

$retorno = '';
$pedido = L::getDadosPedido();
if(! $pedido)
{
	die('Pedido não localizado');
}

$dadosParaDeposito = L::getGateway('deposito');

if($_POST)
{
	if($_POST['mensagem'] == '' && !isset($_FILES['comprovante_deposito']['name'][0][1]))
	{
		$retorno = 'Preencha os dados do Depósito Corretamente ou Anexe um Comprovante';
	}
	else
	{
				$id = $_GET['token'];
        $result = Sql::_query("UPDATE pedidos SET pedidos_status = 'Pagamento Informado' WHERE pedidos_token = ?", array($_GET['token']));
				L::sendStatusOrder($_GET['token']);
				$retorno = X::setContato('nome','assunto','comprovante_deposito');
	}
}

require_once(ROOT.'/topo.php');
?>
<main id="contact-us" class="inner-bottom-md">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <section class="section leave-a-message">
          <h2 class="bordered">Confirmar pagamento via Transferência ou Depósito</h2>
          <p>Informe abaixo os dados de seu depósito ou transferência bancária.</p>
          <form class="contact-form cf-style-1 inner-top-xs" method="post" onsubmit="return false;" >
                        <div class="row field-row">
                            <div class="col-xs-12 col-sm-6">
                                <label>Nome*</label>
                                <input type="text" name="nome" required class="le-input" >
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <label>Email*</label>
                                <input type="email" name="email" required class="le-input" >
                            </div>
                        </div><!-- /.field-row -->
                        <div class="field-row">
                            <label>Assunto</label>
                            <input type="text" name="assunto" required class="le-input">
                        </div><!-- /.field-row -->

                        <div class="field-row">
                            <label>Dados do Depósito ou Transferência</label>
                            <textarea name="mensagem" rows="8" class="le-input"></textarea>
                        </div><!-- /.field-row -->

                        <div class="field-row">
                           ou
                        </div><!-- /.field-row -->

                        <div class="field-row">
                            <label>Dados do Depósito ou Transferência</label>
                            <textarea name="mensagem" rows="8" class="le-input"></textarea>
                        </div><!-- /.field-row -->



                        <div class="buttons-holder">
                            <button type="submit" onclick="submitAjax(this.form, 'xpainel/lib/ajax.php?function=setContato')" class="le-button huge">Enviar</button>
                        </div><!-- /.buttons-holder -->
                        <input type="text" name="xblock" value="" style="display: none">
                    </form><!-- /.contact-form -->
        </section><!-- /.leave-a-message -->
      </div><!-- /.col -->

      <div class="col-md-4">
        <section class="our-store section inner-left-xs"><? $dim = S::getGerenciavel(3)?>
          <h2 class="bordered"><?=$dim['texto_titulo']?></h2>
          <address>
            <?=$dim['texto_emsi']?>
          </address><? $dim = S::getGerenciavel(7)?>
          <h3><?=$dim['texto_titulo']?></h3>
          <ul class="list-unstyled operation-hours">
            <li class="clearfix">
              <?=$dim['texto_emsi']?>
            </li>
          </ul>
        </section><!-- /.our-store -->
      </div><!-- /.col -->

    </div><!-- /.row -->
  </div><!-- /.container -->
</main>














  <!-- Start Main Content Holder DIVISAOOOOOOOOOOOOOOOOOOO-->
  <section id="content-holder" class="container-fluid container">
    <section class="row-fluid">
      <div class="heading-bar">
        <h2>Confirma&ccedil;&atilde;o de Dep&oacute;sito ou Transfer&ecirc;ncia</h2>
        <span class="h-line"></span> </div>
      <!-- Start Main Content -->
      <section class="checkout-holder">
        <section class="span9 first" style="width: 100%;">
          <!-- Start Accordian Section -->
          <div class="accordion" id="accordion2">
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href=""> N&uacute;mero do Pedido: <?php echo $pedido['pedidos_id'];?></a></div>
              <div id="collapseOne" class="accordion-body collapse in">
                <div class="accordion-inner">

                  <div class="span12 check-method-right" style="float: none; margin: 0 auto; margin-top: 45px; margin-bottom: 80px !Important;">

                    <div class="span4 check-method-left">
                    <h3>Seu Pedido</h3>
					<?=L::getDescontoPedido($pedido)?>

                    	<?=L::getPedidoEmail($pedido['pedidos_id'],'Pedido: ',$pedido['clientes_id'])?>

                    </div>
                    <div class="span4 check-method-left">
                    	<h3>Dados para Dep&oacute;sito ou Transfer&ecirc;ncia</h3>
                    	<?=stripslashes(stripslashes($dadosParaDeposito['gateway_texto_extenso1']))?>
                    </div>
                    <div class="span4 check-method-right">
                    <h3>Preencha o formul&aacute;rio abaixo para infomar o pagamento</h3>
                  <?=$retorno?>
                    <form method="post" enctype="multipart/form-data" class="cadastro_form" onSubmit="return validaComprovante()">
                         <label>
                            <span>Nome:</span>
                            <input type="text" name="nome" value="<?=@$_POST['nome']?>" required>
                        </label>

                        <label>
                            <span>E-mail</span>
                            <input type="email" name="email" value="<?=@$_POST['email']?>" required>
                        </label>
                        <label>
                            <span>Dados do dep&oacute;sito:</span>
                            <textarea name="mensagem" id="mensagem" style="width: 100%; height: 150px;"><?=@$_POST['mensagem']?></textarea>
                        </label>

                        <label>
                            <span>Comprovante de Pagamento</span>
                            <input type="file" id="comprovante" name="comprovante_deposito[]">
                        </label>

                        <label>
                            <input type="submit" class="more-btn" value="Enviar">
                        </label>

                        <input type="hidden" name="xblock">
                        <input type="hidden" name="assunto" value="Comprovante de Depósito">
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
                    </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>



          </div>
          <!-- End Accordian Section -->
        </section>



      </section>
      <!-- End Main Content -->
    </section>
  </section>
  <!-- End Main Content Holder -->
<?php require_once '../../../rodape.php';?>
