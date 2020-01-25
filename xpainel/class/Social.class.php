<?
class Social
{
	static function getCommentsFacebook($url=false, $width = '100%', $maxPosts = 5)
	{
		try
		{
			if($url)
			{
				$url = HTTP.$_SERVER['REQUEST_URI'];
			}
			return '
			<div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.6&appId=1529123514084914";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, \'script\', \'facebook-jssdk\'));</script>
            <div class="fb-comments" data-href="'.$url.'" data-width="'.$width.'" data-numposts="'.$maxPosts.'"></div>';
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function getRedesSociais($padrao = '<a target="_blank" href="{social_url}" title="{social_titulo}"><i class="fa fa-{social_chave}"></i></a>')
	{
		try
		{
			$ret='';
			$result = Sql::_fetchall('SELECT * FROM rede_social  WHERE social_deletada=0 AND social_ativa=1');

			foreach($result as $res)
			{
				if(isset($res['social_url'][0]))
				{
					$res['img'] = U::getImg('imagens/redes/'.$res['social_chave'].'.'.$res['social_extensao']);
					$res['social_chave'] = self::updateFontAwezome($res['social_chave']);
					$ret.=X::replace($padrao,$res);
				}
			}

			return $ret;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function updateFontAwezome($chave)
	{
		try
		{
			switch($chave)
			{
				case 'facebook':
				return 'facebook';

				break;
				default:
				return $chave;

			}
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function shareFacebook($foto,$titulo,$resumo,$url = false)
	{
	    try
	    {
	      if(! $url)
	      {
	        $url = HTTP.$_SERVER['REQUEST_URI'];
	      }
	      return 'javascript:window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]='.$titulo.'&amp;p[summary]='.$resumo.'&amp;p[url]='.$url.'&amp;&amp;p[images][0]='.$foto.'\',\'sharer\',\'toolbar=0,status=0,width=590,height=500\');';
	    }
	    catch( Exception $e )
	    {
	      X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
	    }
	}
	static function shareTwitter($url = false)
	{
	    try
	    {
	      if(! $url)
	      {
	        $url = HTTP.$_SERVER['REQUEST_URI'];
	      }
	      return 'javascript:window.open(\'http://twitter.com/share?text=&url='.$url.'&hashtags=\',\'sharer\',\'toolbar=0,status=0,width=590,height=500\');';
	    }
	    catch( Exception $e )
	    {
	      X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
	    }
	}
	static function shareGooglePlus($url = false)
	{
		try
		{
			if(! $url)
			{
				$url = HTTP.$_SERVER['REQUEST_URI'];
			}
			return "javascript:window.open('https://plus.google.com/share?url={$url}','sharer','toolbar=0,status=0,width=400,height=500')";
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function getCompartihar($rede)
	{
		try
		{
			switch ($_SERVER['PHP_SELF']) 
			{
				case '/blog-post.php':	
					$search = U::setUrlAmigavel($_GET['blog'], '%');
					$res = Sql::_fetch("SELECT * FROM noticias WHERE noticia_deletada=0 AND noticia_ativa=1 AND noticia_titulo LIKE '{$search}'");;
					if(! $res)
					{
						return false;
					}
					$res['imagem'] = '';
					if(file_exists($_SERVER['DOCUMENT_ROOT'].'/imagens/noticias/'.$res['noticia_id'].'_1_1.'.$res['noticia_extensao1']))
					{
						$res['imagem'] = HTTP.'/imagens/noticias/'.$res['noticia_id'].'_1_1.'.$res['noticia_extensao1'];
					}
					$link = HTTP.'/blog-post/'.U::setUrlAmigavel($res['noticia_titulo']);
					$redesCompartilhadas = 
					array(
					"facebook" => Social::shareFacebook($res['imagem'], $res['noticia_titulo'], $res['noticia_texto1'], $link), 
					"google-plus" => Social::shareGooglePlus($link));
					$share = '';
					foreach ($rede as $social) 
					{
						$share .= '
						<div class="author column_1_2">
							<div class="details">
								<a href="'.$redesCompartilhadas[$social].'" class="more highlight">
								<i class="fa fa-'.$social.'-square" aria-hidden="true"></i> COMPARTILHAR</a>
							</div>
						</div>';
					};
					break;
				case '/produto.php':	
				if(! isset($_GET['prod']))
				{
					return false;
				}      
				$search = U::setUrlAmigavel($_GET['prod'], '%');
				$res = Sql::_fetch("
				SELECT sub.*, prod.*, c.* FROM produto prod
					INNER JOIN subcategoria sub ON prod.subcategoria_id = sub.subcategoria_id 
						INNER JOIN categoria c ON sub.categoria_id=c.categoria_id
							WHERE sub.subcategoria_ativa = 1 AND sub.subcategoria_deletada = 0 
								AND prod.produto_ativo = 1 AND produto_deletado = 0 
									AND prod.produto_nome LIKE '{$search}' 
										ORDER BY prod.produto_id DESC");
				$res['imagem'] = '';
				$res['imagem'] = U::getImg('/imagens/produtos/'.$res['produto_id'].'_1_1.'.$res['produto_extensao1']);
				$link = HTTP.'/produtos/'.U::setUrlAmigavel($res['categoria_nome']).'/'.U::setUrlAmigavel($res['subcategoria_nome']).'/'.U::setUrlAmigavel($res['produto_nome']);
				$redesCompartilhadas = 
				array(
				"facebook" => self::shareFacebook($res['imagem'], $res['produto_nome'], U::limitaCaracteres($res['produto_descricao'], 150), $link), 
				"google-plus" => self::shareGooglePlus($link));
				$share = '';
				foreach ($rede as $social) 
				{
					$share .= '
					<li>
	                    <a title="" href="'.$redesCompartilhadas[$social].'" class="social_icon '.$social.'">
	                    	&nbsp;
	                    </a>
	                 </li>';
				}
			}
			return $share;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
}