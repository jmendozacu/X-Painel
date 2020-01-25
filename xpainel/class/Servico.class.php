<?
class Servico
{
	static function count()
	{
		try
		{
			$count = Sql::_fetch("SELECT COUNT(*) as linhas FROM servicos WHERE servico_deletado=0 AND servico_ativo=1");
			return $count['linhas'];
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getServicos()
	{
		try
		{
			$ret = array('servicos'=> '', 'servicosComplementares' => '');
			$sql = "SELECT * FROM servicos s INNER JOIN subcategoria_servicos ss ON ss.subcategoria_id = s.subcategoria_id WHERE servico_deletado=0 AND servico_ativo=1 ";

			$result = Sql::_fetchAll($sql);


			foreach ($result as $res)
			{
				$link = 'servico?servico='.$res['servico_id'];
				$active = isset($_GET['id']) && $_GET['id']==$res['servico_id'] ? 'active' : '';
				$ret['menu'].='<li><a href="'.$link.'" class="'.$active.'">'.$res['servico_titulo'].'</a></li>';
				$img1 = U::getImg('imagens/servicos/'.$res['servico_id'].'_1_1.'.$res['servico_extensao1']);
				$img2 = U::getImg('imagens/servicos/'.$res['servico_id'].'_2_1.'.$res['servico_extensao2']);
				if($res['subcategoria_id'] == 1)
				{
					$ret['servicos'].='
					<a href="'.$link.'">
						<div class="col-xs-12 col-sm-12 col-md-12 service-block">
							<div class="container">
								<div class="service-content col-xs-8 col-sm-12 col-md-8">
									<div class="service-desc">
										<h3>'.$res['servico_titulo'].'</h3>
										<p>'.$res['servico_texto2'].'</p>
									</div>
								</div>
								<div class="service-img col-xs-4 col-sm-12 col-md-4">
									<img src="'.$img1.'" alt="service">
									<img src="'.$img2.'" alt="service">
								</div>
							</div>
						</div>
					</a>';
				}
				else
				{
					$ret['servicosComplementares'].='
					<div class="col-xs-12 col-sm-6 col-md-6 feature feature-1 mb-30-xs">
						<a href="'.$link.'" class="">
							<h4 class="text-uppercase font-16" style="color: #c23033; text-align: center">'.$res['servico_titulo'].'</h4>
						</a>
						<p style="text-align: center;">'.$res['servico_texto2'].'</p>
					</div>';
				}

			}
			return $ret;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getServicosLiMenu()
	{
		try
		{
			$ret = array('servicos'=>'','servicosComplementares'=>'');
			$result = Sql::_fetchall("SELECT * FROM servicos WHERE servico_deletado=0 AND servico_ativo=1 ORDER BY ordem");
			foreach ($result as $res)
			{

				$link = HTTP.'/servicos?servico='.$res['servico_id'];
				if($res['subcategoria_id'] == 1)
				{
					$ret['servicos'].='
					<li>
						<a href="servico?servico='.$res['servico_id'].'">'.$res['servico_titulo'].'</a>
					</li>';
				}
				else
				{
					$ret['servicosComplementares'].='
					<li>
						<a href="servico?servico='.$res['servico_id'].'">'.$res['servico_titulo'].'</a>
					</li>';
				}

			}
			return $ret;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getServico()
	{
		try
		{

			if(! isset($_GET['servico']))
			{
				U::goHome(HTTP.'/servicos');
			}

			$sql = "SELECT * FROM servicos WHERE servico_deletado=0 AND servico_ativo=1 AND servico_id =".(int)$_GET['servico'];
			$result = Sql::_fetch($sql);

			if(! $result)
			{
				U::goHome(HTTP.'/servicos');
			}

			$result['imagem'] = '';
			$result['assunto'] = 'Orçamento de servico: '.$result['servico_titulo'];

			$result['imagens'] = '';
			$result['imagem-1'] = U::getImg('imagens/servicos/'.$result['servico_id'].'_1_1.'.$result['servico_extensao1'],true);
			$result['imagem-2'] = U::getImg('imagens/servicos/'.$result['servico_id'].'_2_1.'.$result['servico_extensao2'],true);

			

			return $result;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getServicosHome()
	{
		try
		{
			$ret = array('servicos'=> '', 'servicosComplementares' => '');
			$sql = "SELECT * FROM servicos s INNER JOIN subcategoria_servicos ss ON ss.subcategoria_id = s.subcategoria_id WHERE servico_deletado=0 AND servico_ativo=1 ";

			$result = Sql::_fetchAll($sql);


			foreach ($result as $res)
			{
				$link = 'servico?servico='.$res['servico_id'];
				$active = isset($_GET['id']) && $_GET['id']==$res['servico_id'] ? 'active' : '';
				$ret['menu'].='<li><a href="'.$link.'" class="'.$active.'">'.$res['servico_titulo'].'</a></li>';
				$img1 = U::getImg('imagens/servicos/'.$res['servico_id'].'_1_1.'.$res['servico_extensao1']);
				$img2 = U::getImg('imagens/servicos/'.$res['servico_id'].'_2_1.'.$res['servico_extensao2']);
				if($res['subcategoria_id'] == 1)
				{
					$ret['servicos'].='
					<div class="col-xs-12 col-sm-6 col-md-6 feature feature-1 mb-60 mb-30-xs">
						<a href="'.$link.'" class="">
							<h4 class="text-uppercase font-16" style="color: #c23033; text-align: center">'.$res['servico_titulo'].'</h4>
						</a>
						<p style="text-align: center;">'.$res['servico_texto2'].'</p>
					</div>';
				}
				else
				{
					$ret['servicosComplementares'].='
					<div class="col-xs-12 col-sm-6 col-md-6 feature feature-1 mb-30-xs">
						<a href="'.$link.'" class="">
							<h4 class="text-uppercase font-16" style="color: #c23033; text-align: center">'.$res['servico_titulo'].'</h4>
						</a>
						<p style="text-align: center;">'.$res['servico_texto2'].'</p>
					</div>';
				}

			}
			return $ret;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
}