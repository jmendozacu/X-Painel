<?
class Layout
{
	static function textColors($str, $padrao = '{1} {2}')
	{
		try
		{
			$padraoExplode = '  ';
			$words = explode($padraoExplode, $str);
			$text1 = $words[0];
			unset($words[0]);
			$text2 = implode($padraoExplode, $words);

			return str_replace(array('{1}', '{2}', $padraoExplode), array($text1, $text2, ''), $padrao);

		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function menu2()
	{
		try
		{
			return str_replace(basename($_SERVER['PHP_SELF']).'"',basename($_SERVER['PHP_SELF']).'" class="active"','
			<li><a href="blindagens-premium.php">Premium</a></li>
			<li><a href="blindagens-plus.php">Plus</a></li>
			<li><a href="blindagens-basic.php">Basic</a></li>
			<li><a href="blindagens-personalizada.php">Personalizada</a></li>');
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function btVoltar($text, $url = false)
	{
		try
		{
			$url = $url ? $url : HTTP;
			return '<button onclick="location=\''.$url.'\'" class="button btn-return" title="'.$text.'" type="button"><span><span>'.$text.'</span></span></button>';
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function modalX($html, $literal = false)
	{
		try
		{
			if($literal)
			{
				return U::clearStr($html);
			}

			$src = ROOT.'/xpainel/templates/'.$html.'.php';

			return file_exists($src) ? require_once($src) : $html.'-No-Thanks';
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function sidebar()
	{
		try
		{
			$ret = '
			<div class="col-sm-3  wow fadeInLeft" data-wow-delay="0.3s">
				<div class="sidebar-container">
					<div>
						<img src="img/mulher-atendimento.png" />
						<br /><br />
						<h1>Solicite seu Orçamento</h1>
						<hr />
						<p>Atendimento rápido e de qualidade.</p>
						<div class="adress-details wow fadeInLeft" data-wow-delay="0.3s">
							<div>
								<span><i class="fa fa-map-marker"></i></span>
								<div>'.Mapa::getDadoMapa('mapa_descricao').'</div>
							</div>
							<div>
								<span><i class="fa fa-phone"></i></span>
								<div><a href="tel:'.U::getNumbersStr(X::getDadoSite('campo_adicional2')).'" target="_blank">'.X::getDadoSite('campo_adicional2').'</a></div>
							</div>
							<div>
								<span><i class="fa fa-whatsapp"></i></span>
								<div><a href="'.Social::linkWhatsApp(X::getDadoSite('campo_adicional1')).'" target="_blank">'.X::getDadoSite('campo_adicional1').'</a></div>
							</div>
							<div>
								<span><i class="fa fa-envelope"></i></span>
								<div><a href="mailto:'.X::getDadoSite('campo_adicional3').' target="_blank">'.X::getDadoSite('campo_adicional3').'</a></div>
							</div>
							<div>
								<span><i class="fa fa-clock-o"></i></span>
								<div>'.X::getDadoSite('campo_adicional4').'</div>
							</div>
						</div>
					</div>
				</div>
			</div>';

			return U::clearStr($ret);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function breadcrumb($titulo)
	{
		try
		{
			return '
			<section id="page_banner" class="border_b">
		        <div class="container">
		            <div class="row">
		                <div class="col-md-12">
		                    <div class="page-header">
		                        <h2>'.$titulo.'</h2>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </section>';
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function display($layout, $str)
	{
		try
		{
			$ret = '';
			if($str != '')
			{
				$ret = str_replace('{XlayoutX}', $str, $layout);
			}

			return $ret;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function hiddenStyle($str)
	{
		try
		{
			if($str != '')
			{
				return '';
			}

			return 'display: none !important;';
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function setActive($local = 'index.php', $active = " active ")
	{
		try
		{
			$paginaAtual = basename($_SERVER['PHP_SELF']);

			if(strstr($local,','))
			{
				$urls = explode(',', $local);
				{
					foreach($urls as $url)
					{
						if($url == $paginaAtual)
						{
							return $active;
						}
					}
				}
			}

			if($local == $paginaAtual)
			{
				return $active;
			}


			return '';
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function menu($tipo = 'desktop')
	{
		try
		{

			$menu = '
			<nav id="main-nav" class="main-nav">
				<ul>
					<li class="'.Layout::setActive().'">
							<a href="'.U::ancorar('index', '-500').'">HOME</a>
					</li>
					<li>
						<a href="'.U::ancorar('quem-somos', '-100').'">QUEM SOMOS</a>
					</li>
					<li class="'.Layout::setActive('projetos.php').'">
						<a href="'.U::ancorar('projetos', '-100').'">PRODUTOS</a>
					</li>
					<li>
						<a href="'.U::ancorar('orcamento', '-100').'">ORÇAMENTOS</a>
					</li>
					<li class="'.Layout::setActive('blog.php,post.php').'">
						<a href="'.U::ancorar('blog', '-100').'">BLOG</a>
					</li>

					<li>
						<a href="'.U::ancorar('contato', '-100').'">CONTATO</a>
					</li>

					<li class="'.Layout::setActive('perfil.php').'">
						<a href="'.HTTP.'/perfil"><span class="btmenu">SEU PROJETO</span></a>
					</li>
				</ul>
			</nav>';

			if($tipo == 'mobile')
			{
				$menu = '
				<nav id="menu">
					<ul>
					<li class="'.Layout::setActive().'">
							<a href="'.U::ancorar('index', '-500').'">HOME</a>
					</li>
					<li>
						<a href="'.U::ancorar('quem-somos', '-100').'">QUEM SOMOS</a>
					</li>
					<li class="'.Layout::setActive('projetos.php').'">
						<a href="'.U::ancorar('projetos', '-100').'">PRODUTOS</a>
					</li>
					<li>
						<a href="'.U::ancorar('orcamento', '-100').'">ORÇAMENTOS</a>
					</li>
					<li class="'.Layout::setActive('blog.php,post.php').'">
						<a href="'.U::ancorar('blog', '-100').'">BLOG</a>
					</li>

					<li>
						<a href="'.U::ancorar('contato', '-100').'">CONTATO</a>
					</li>

					<li class="'.Layout::setActive('perfil.php').'">
						<a href="'.HTTP.'/perfil"><span class="btmenu">SEU PROJETO</span></a>
					</li>
				</ul>
				</nav>';
			}




			return $menu;


		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function header()
	{
		try
		{
			return '
			<li>
				<form  id="buscadecoracao" action="decoracoes.php" method="get" style="width: 130px;opacity: 1;display: block;">
					<input type="text" value="'.@$_GET['busca'].'" placeholder="Buscar Decorações" name="busca" />
					<input type="submit" id="search-submit" />
				</form><!--/ #search-form-->
			</li>
			<li class="search"><a href="javascript:$(\'#buscadecoracao\').submit()" title="Buscar"></a></li>
			'.Social::getRedesSociais('<li class="{social_chave}"><a href="{social_url}" target="_blank" title="{social_titulo}"></a></li>').'
			<li class="toptel"><a href="'.Social::linkWhatsApp(X::getDadoSite('campo_adicional1')).'" target="_blank" ><i class="fab fa-whatsapp" style="font-size: 25px; font-weight: bold"></i> '.X::getDadoSite('campo_adicional1').' </a></li>
			<li class="toptel"><a href="'.Social::linkWhatsApp(X::getDadoSite('campo_adicional2')).'" target="_blank" ><i class="fab fa-whatsapp" style="font-size: 25px; font-weight: bold"></i> '.X::getDadoSite('campo_adicional2').'</a></li>
			<li class="toptel"><a href="'.Social::linkFone(X::getDadoSite('campo_adicional3')).'" target="_blank" ><i class="fas fa-phone" style="font-size: 25px; font-weight: bold"></i> '.X::getDadoSite('campo_adicional3').'</a></li>
			<li class="toptel"><a href="'.Social::linkFone(X::getDadoSite('campo_adicional4')).'" target="_blank" ><i class="fas fa-phone" style="font-size: 25px; font-weight: bold"></i> '.X::getDadoSite('campo_adicional4').'</a></li>';
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

}