<?
class Banner
{
	static function getBanner($estilo=1)
	{
		try
		{
			$mural = '';
			$result = Sql::_fetchAll('SELECT * FROM banner WHERE banner_ativo = 1 AND estilos_banners_id='.$estilo.' ORDER BY banner_ordem');
			foreach($result as $res)
			{
				$img = 'imagens/banners/'.$estilo.'/'.$res['banner_id'].'.'.$res['banner_extensao'];

				if($res['banner_link'] != '')
				{
					$textoBotao = $res['campo_adicional1'] != '' ? $res['campo_adicional1'] : 'VER MAIS';
				}
				$mural.='
				<div class="item">
					<div class="item-bg bg-overlay">
						<div class="bg-section">
							<img src="'.$img.'" alt="Background" />
						</div>
					</div>
					<div class="container">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<div class="hero-slide">
									<div class="slide-heading">
										<p style="text-transform: uppercase; font-weight: bold; text-align: center;">'.$res['banner_titulo'].'</p>
									</div>
									<div class="slide-title">
										<h2 style="font-size: 16px;">Saiba mais</h2>
									</div>
									<div class="slide-action">
										<a class="btn btn-primary" href="'.$res['banner_link'].'">ver mais</a>
										<a class="btn btn-secondary pull-right" href="contato.php" data-toggle="modal" data-target="#model-quote">orçamento</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
			}
			return $mural;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}

	}
}