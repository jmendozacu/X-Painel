<?
class Blog
{

	static function getNoticias()
	{
		try
		{
			$condicao = '';
			if(isset($_GET['subcategoria']))
			{
				$condicao = " AND subcategoria_id=".(int)$_GET['subcategoria'];
			}
			$noticias = array('noticias' => '');
			$sql = "SELECT * FROM noticias WHERE noticia_deletada=0 AND noticia_ativa=1 {$condicao} ORDER BY UNIX_TIMESTAMP(noticia_data) DESC";

			$pagSql = Paginacao::getPaginacao($sql, 50);

			$noticias['paginacao'] = $pagSql['paginacao'];

			$result = Sql::_fetchAll($pagSql['query']);

			foreach ($result as $res)
			{

				$img = U::getImg('imagens/noticias/'.$res['noticia_id'].'_1_1.'.$res['noticia_extensao1']);
				$imgf = U::getImg('imagens/noticias/'.$res['noticia_id'].'_2_1.'.$res['noticia_extensao2']);
				$link = HTTP.'/post/'.U::setUrlAmigavel($res['noticia_titulo']);
				$data = U::formataData($res['noticia_data'], "%b %d");
				$noticias['noticias'].=
				'<div class="col-xs-12 col-sm-6 col-md-4 entry">
					<div class="entry-img">
						<a class="img-popup" href="'.$img.'">
							<img src="'.$img.'" alt="'.$res['noticia_titulo'].'" />
						</a>
					</div>
					<div class="entry-meta clearfix">
						<ul class="pull-left">
							<li class="entry-format">
								<i class="fa fa-video-camera"></i>
							</li>
							<li class="entry-date">'.$data.'</li>
						</ul>
					</div>
					<div class="entry-title">
						<h3>
							<a href="'.$link.'">'.$res['noticia_titulo'].'</a>
						</h3>
					</div>
					<div class="entry-content">
						<p>'.$res['noticia_texto1'].'</p>
						<a class="entry-more" href="'.$link.'"><i class="fa fa-plus"></i>
							<span>Leia Mais</span>
						</a>
					</div>
				</div>';
                // U::clearFix(++$loop,2);
            }


            return U::clearStr($noticias);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getNoticiasHome()
	{
		try
		{
			$noticias = '';
			$sql = "SELECT * FROM noticias WHERE noticia_deletada=0 AND noticia_ativa=1 ORDER BY UNIX_TIMESTAMP(noticia_data) DESC LIMIT 3";

			$result = Sql::_fetchAll($sql);

			foreach ($result as $res)
			{

				$img = U::getImg('imagens/noticias/'.$res['noticia_id'].'_1_1.'.$res['noticia_extensao1']);
				$imgf = U::getImg('imagens/noticias/'.$res['noticia_id'].'_2_1.'.$res['noticia_extensao2']);
				$link = HTTP.'/post/'.U::setUrlAmigavel($res['noticia_titulo']);

				$data = U::formataData($res['noticia_data'], "%b %d");
				$noticias.=
				'<div class="col-xs-12 col-sm-6 col-md-4 entry">
					<div class="entry-img">
						<a class="img-popup" href="'.$img.'">
							<img src="'.$img.'" alt="'.$res['noticia_titulo'].'" />
						</a>
					</div>
					<div class="entry-meta clearfix">
						<ul class="pull-left">
							<li class="entry-format">
								<i class="fa fa-video-camera"></i>
							</li>
							<li class="entry-date">'.$data.'</li>
						</ul>
					</div>
					<div class="entry-title">
						<h3>
							<a href="'.$link.'">'.$res['noticia_titulo'].'</a>
						</h3>
					</div>
					<div class="entry-content">
						<p>'.$res['noticia_texto1'].'</p>
						<a class="entry-more" href="'.$link.'"><i class="fa fa-plus"></i>
							<span>Leia Mais</span>
						</a>
					</div>
				</div>';
            }


            return U::clearStr($noticias);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getNoticiasRelacionadas($id)
	{
		try
		{
			$noticias = '';
			$sql = "SELECT * FROM noticias WHERE noticia_deletada=0 AND noticia_ativa=1 AND noticia_id != ".$id." ORDER BY UNIX_TIMESTAMP(noticia_data) DESC LIMIT 3";

			$result = Sql::_fetchAll($sql);

			foreach ($result as $res)
			{

				$img = U::getImg('imagens/noticias/'.$res['noticia_id'].'_1_1.'.$res['noticia_extensao1']);
				$imgf = U::getImg('imagens/noticias/'.$res['noticia_id'].'_2_1.'.$res['noticia_extensao2']);
				$link = HTTP.'/post/'.U::setUrlAmigavel($res['noticia_titulo']);

				$noticias.='
				
					<div class="col-xs-12 col-sm-4 col-md-4 entry">
						
						<img src="'.$img.'" alt="title" style="width: 100%;"/>
						<div class="entry-title">
								<h5><a href="'.$link.'">'.$res['noticia_titulo'].'</a></h5>
						</div>
						<div class="entry-content" style="margin: 0;">
						'.$res['noticia_texto1'].'
						</div>
					</div>
				';
            }


            return U::clearStr($noticias);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getNoticiasRecentes()
	{
		try
		{
			$noticias='';
			$result = Sql::_fetchall('SELECT * FROM noticias WHERE noticia_deletada=0 AND noticia_ativa=1 ORDER BY noticia_data, noticia_id DESC LIMIT 5');
			if(! $result)
			{
				return '';
			}

			foreach ($result as $res)
			{
				$link = HTTP.'/blog-post.php?post='.$res['noticia_id'];
				$noticias.='
				<div '.U::divLink($link).' class="latest-content">
					<h3><a href="'.$link.'" title="'.$res['noticia_titulo'].'">'.$res['noticia_titulo'].'</a></h3>
					<span>'.U::getDataExtenso($res['noticia_data']).'</span>
				</div>';
            }

            return $noticias;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}


	static function getBlogArquivo()
	{
		try
		{
			$arquivo = '';
			$result = Sql::_fetchAll("SELECT DATE_FORMAT(noticia_data, '%b %Y') AS sDate,DATE_FORMAT(noticia_data, '%Y') AS ano, DATE_FORMAT(noticia_data, '%c') AS mes, COUNT(noticia_id) AS cont FROM noticias WHERE noticia_deletada=0 AND noticia_ativa=1 GROUP BY sDate ORDER BY noticia_data DESC");
			$anolistado = false;
			foreach ($result as $ano)
			{
				if($anolistado != $ano['ano'])
				{
					$arquivo.='<li><a href="#">'.$ano['ano'].'</a><ul>';

					foreach ($result as $mes)
					{
						if($mes['ano'] == $ano['ano'])
						{
							$arquivo.='<li><a href="blog.php?ano='.$mes['ano'].'&mes='.$mes['mes'].'">'.U::getMesExtenso($mes['mes']).' ('.$mes['cont'].')</a></li>';
						}
					}


					$arquivo.='</ul></li>';

					$anolistado = $ano['ano'];
				}
			}

			return $arquivo;
			return'
			 <li><a href="#">Janeiro</a><ul><li><a href="#">Março</a></li></ul></li>
            <li><a href="#">Fevereiro</a></li>
            <li><a href="#">Março</a></li>
            <li><a href="#">Abril</a></li>
            <li><a href="#">Maio</a></li>
            <li><a href="#">Junho</a></li>
            <li><a href="#">Julho</a></li>';
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}


	static function getNoticia()
	{
		try
		{


			if(! isset($_GET['post']))
			{
				U::goHome();
			}
			$like = Sql::toLike($_GET['post']);
			$sql = "SELECT * FROM noticias WHERE noticia_deletada=0 AND noticia_ativa=1 AND noticia_titulo".$like;
			$res = Sql::_fetch($sql);
			if(! $res)
			{
				U::goHome();
			}

			$_GET['subcategoria'] = $res['subcategoria_id'];


			$res['imagem'] = U::getImg('imagens/noticias/'.$res['noticia_id'].'_1_1.'.$res['noticia_extensao1']);
			$res['imagem2'] = U::getImg('imagens/noticias/'.$res['noticia_id'].'_2_1.'.$res['noticia_extensao2']);

			return U::clearStr($res);

		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}


	static function getSubcategoriasCount()
	{
		try
		{
			$ret = '';
			$sql = "SELECT *, (SELECT count(*) FROM noticias n WHERE sub.subcategoria_id=n.subcategoria_id AND n.noticia_deletada=0 AND noticia_ativa=1) as linhas FROM subcategoria_noticias sub WHERE subcategoria_deletada=0 AND subcategoria_ativa=1  ORDER BY  subcategoria_ordem";
			$result = Sql::_fetchAll($sql);

			foreach($result as $res)
			{
				$class = isset($_GET['subcategoria']) && $_GET['subcategoria'] == $res['subcategoria_id'] ? 'class="trotsactive"' : '';
				$img = U::getImg('');
				$link = 'noticias.php?subcategoria='.$res['subcategoria_id'];
				$ret.='
				<li><a '.$class.' href="'.$link.'">'.$res['subcategoria_nome'].'<span>'.$res['linhas'].'</span></a></li>';
			}

			return U::clearStr($ret);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}


}
