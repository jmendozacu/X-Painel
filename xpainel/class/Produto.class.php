<?
class Produto
{
	static function count()
	{
		try
		{
			$count = Sql::_fetch("SELECT COUNT(*) as linhas FROM produto WHERE produto_deletado=0 AND produto_ativo=1");
			return $count['linhas'];
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getProdutosHome()
	{
		try
		{
			$ret = array('descricoes' => '', 'subcategorias' => '');
			$sql = "SELECT * FROM subcategoria WHERE subcategoria_deletada=0 AND subcategoria_ativa=1 AND checkbox0=1 ORDER BY subcategoria_ordem";
			$result = Sql::_fetchAll($sql);
			$style = '';
			$class = 'active';
			foreach($result as $res)
			{
				$link = HTTP.'/projetos/'.U::setUrlAmigavel($res['subcategoria_nome']);
				$img = HTTP.'/imagens/subcategorias/'.$res['subcategoria_id'].'_1_1.'.$res['subcategoria_extensao1'];
				$ico = HTTP.'/imagens/subcategorias/'.$res['subcategoria_id'].'_2_1.'.$res['subcategoria_extensao2'];;

				$ret['subcategorias'].='<li><a class="ativaveis ativavel'.$res['subcategoria_id'].' '.$class.'" href="javascript:alterProjeto('.$res['subcategoria_id'].', \''.$res['subcategoria_nome'].'\', \''.$res['subcategoria_descricao'].'\', \''.$img.'\', \''.$link.'\')"><span>'.$res['subcategoria_nome'].'</span><img src="'.$ico.'" /></a></li>';
				$class = '';

				$link = '';
				if($ret['descricoes'] == '')
				{
					$ret['descricoes']='
					<div class="col-md-5">
						<img id="imgProjeto" src="'.$img.'" class="img-responsive" alt="Image" />
					</div>
					<div class="col-md-4">
						<span class="font1 montserrat">Projetos</span>
						<h2 class="montserrat" id="tituloProjeto">'.$res['subcategoria_nome'].'</h2>
						<p class="textogeral montserrat" id="descricaoProjeto">
							'.$res['subcategoria_descricao'].'
						</p>
						<br /><br /><a href="'.HTTP.'/projetos/sala-de-estar" id="linkProjeto" class="bt2">Ver projetos</a>
					</div>';
				}

				$style=' style="display:none; "';
			}

			return U::clearStr($ret);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function getProdutosSelect()
	{
		try
		{

			$selects='';
			$qtd='<option value="1">1</option><option value="2">2</option><option value="3">3</option></select>';


			$ret = '';
			$sql = "SELECT * FROM produto p
						INNER JOIN subcategoria s
							ON s.subcategoria_id=p.subcategoria_id
								WHERE produto_deletado=0 AND produto_ativo=1
									ORDER BY subcategoria_ordem, ordem";
			$result = Sql::_fetchAll($sql);

			$i = 0;
			foreach($result as $res)
			{
				$selects.='<option valor_produto="'.$res['valor1'].'" descricaoproduto="'.U::clearStr($res['produto_descricao']).'" nome_produto="'.$res['produto_nome'].'" value="'.$res['produto_nome'].'">'.$res['produto_nome'].'</option>';
			}



			for($i=1;$i<=10;$i++)
			{
				$ret.='
				<tr>
					<td><select id="codigo'.$i.'" onChange="recalculaPreco('.$i.')"><option>Escolha</option>'.$selects.'</select></td>
					<td id="descricaoproduto'.$i.'"></td>
					<td><input type="text" id="largura_bobina'.$i.'"  onkeydown="Mascara(this,Integer); recalculaPreco('.$i.');" onkeypress="Mascara(this,Integer); recalculaPreco('.$i.');" onkeyup="Mascara(this,Integer); recalculaPreco('.$i.');" /></td></td>
					<td>500</td>
					<td><select  id="qtd'.$i.'" onChange="recalculaPreco('.$i.')">'.$qtd.'</select></td>
					<td>R$ <span id="result_linha'.$i.'"></span></td>
				</tr>

				<input type="hidden" name="codigodhs['.$i.']" id="codigodhs'.$i.'" value="" />
				<input type="hidden" name="descricao['.$i.']" id="descricao'.$i.'" value="" />
				<input type="hidden" name="largura['.$i.']" id="largura'.$i.'" value="" />
				<input type="hidden" name="comprimento['.$i.']" id="comprimento'.$i.'" value="" />
				<input type="hidden" name="qtd['.$i.']" id="qtdmail'.$i.'" value="" />
				<input type="hidden" name="valor['.$i.']" id="valor'.$i.'" value="" />';
			}

			return U::clearStr($ret);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}


	static function getProdutosMenu()
	{
		try
		{

			$ret = '';
			$sql = "SELECT * FROM subcategoria WHERE subcategoria_deletada=0 AND subcategoria_ativa=1 AND checkbox0=1 ORDER BY subcategoria_ordem";
			$result = Sql::_fetchAll($sql);


			$i = 0;
			foreach($result as $res)
			{
				$link = 'produto.php?subcategoria='.$res['subcategoria_id'];
				$ret.='
				<li class="container3d relative">
					<a href="'.$link.'" class="d_block color_dark relative">'.$res['subcategoria_nome'].'
					</a>
				</li>';
			}

			return U::clearStr($ret);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function getSubcategoriasMenu()
	{
		try
		{
			$ret = '';
			$sql = "SELECT * FROM subcategoria
			WHERE subcategoria_deletada=0 AND subcategoria_ativa=1 AND checkbox0=1";
			$result = Sql::_fetchAll($sql);
			$_GET['subcategoria_atual'] = '';
			foreach($result as $res)
			{
				$link = HTTP.'/projetos/'.U::setUrlAmigavel($res['subcategoria_nome']);
				if($_GET['subcategoria_atual'] == '')
				{$_GET['subcategoria_atual'] = $res['subcategoria_id'];}
				$active = '';
				if(isset($_GET['projeto']) && $_GET['projeto'] == U::setUrlAmigavel($res['subcategoria_nome'])){
					$_GET['subcategoria_atual'] = $res['subcategoria_id'];
					$active = 'current';
				}
				(isset($_GET['projeto']) && $_GET['projeto'] == U::setUrlAmigavel($res['subcategoria_nome'])) ? 'current' : '';
				$ret.='<a href="'.$link.'" data-filter="*" class="'.$active.'">'.$res['subcategoria_nome'].'</a>';
			}

			return U::clearStr($ret);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getProduto()
	{
		try
		{
			if(empty($_GET['id']))
			{
				U::goHome();
			}
			$sql = "SELECT * FROM produto WHERE produto_deletado=0 AND produto_ativo=1 AND produto_id=".$_GET['id'];
			$result = Sql::_fetch($sql);
			if(! $result)
			{
				U::goHome();
			}
			$result['imagem'] = U::getImg('imagens/produtos/'.$result['produto_id'].'_2_2.'.$result['produto_extensao2']);
			// $result['imagens'] = '';
			// $result['thumbs'] = '';
			// $class = 'moreview_thumb_active';

			// for($i=1; $i<=5; $i++)
			// {

			// 	$img = U::getImg('imagens/produtos/'.$result['produto_id'].'_'.$i.'_1.'.$result['produto_extensao'.$i], true);
			// 	if($img)
			// 	{

			// 		$result['thumbs'].='
			// 		<li class="moreview_thumb thumb_'.$i.' '.$class.'"> <img class="moreview_thumb_image" src="'.$img.'" alt="thumbnail"> <img class="moreview_source_image" src="'.$img.'" alt=""> <span class="roll-over">passe o mouse</span> <img  class="zoomImg" src="'.$img.'" alt="thumbnail"></li>';
			// 	}
			// 	$class = '';


			// }

			X::setJsCss("getEstoque({$result['produto_id']});");

			return U::clearStr($result);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getProdutos()
    {
        try
        {
					$ret = '';
        	$condicao = "WHERE produto_deletado = 0 AND produto_ativo = 1 AND subcategoria_id = {$_GET['subcategoria_atual']}";
					$sql = "SELECT * FROM produto {$condicao}";




			$result = Sql::_fetchAll($sql);


			foreach($result as $res)
			{
				$img = U::getImg('imagens/produtos/'.$res['produto_id'].'_1_1.'.$res['produto_extensao1']);
                $link = 'estoque_detalhe.php?id='.$res['produto_id'];

				$ret.='
				<a href="'.$img.'" data-toggle="lightbox" class="group4">
					<div class="col-md-3 col-sm-6  col-xs-12 element-item Commercial">
						<div class="portfolio-home2-item">
							<img src="'.$img.'" class="img-responsive" alt="Image">
						</div>
					</div>
				</a>';
			}

			return  U::clearStr($ret);
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }



	static function getProdutosCheckbox($checkbox = 0)
    {
        try
        {
        	$produtos = '';


            $query = "SELECT *
                            FROM produto
                                WHERE produto_deletado=0 AND produto_ativo=1 AND checkbox{$checkbox}=1";


            $result = Sql::_fetchAll($query);

            if(! $result)
            {
            	U::ocultaX('produtosdestaque');
            }


            $loop = 0;
            foreach ($result as $res)
            {

                $img = U::getImg('/imagens/produtos/'.$res['produto_id'].'_1_1.'.$res['produto_extensao1']);
                $link = 'estoque_detalhe.php?id='.$res['produto_id'];

                $produtos.='
                <div class="col-md-12" '.U::divLink($link).'>
	                  <div class="ts-service-wrapper">
	                     <span class="service-img">
	                        <img class="img-fluid" src="'.$img.'" alt="'.$res['produto_nome'].'">
	                     </span>
	                     <div class="service-content">
	                        <div class="service-icon" style="line-height: 20px;">
	                           <i style="font-size: 15px;">ANO '.$res['valor3'].'</i>
	                        </div>
	                        <h3><a href="'.$link.'">'.$res['produto_nome'].'</a></h3>
	                        <p style="font-weight: 300;"><b>Marca: </b><span style="margin-right: 20px; color: #0270B3;">'.$res['valor2'].'</span><b>Ano: </b><span style="color: #0270B3;">'.$res['valor3'].'</span></p>
	                        <p style="font-weight: 300;"><b>Km: </b><span style="margin-right: 27px; color: #0270B3;">'.$res['valor4'].'</span><b>Câmbio: </b><span style="color: #0270B3;">'.$res['valor5'].'</span></p>
	                        <p>'.$res['produto_descricao2'].'</p>
	                        <a href="'.$link.'" class="readmore" style="color: #0270B3;">Saiba Mais<i class="fa fa-angle-double-right"></i></a>
	                     </div>
	                  </div>
	            </div>';

                $produtos.=U::clearFix(++$loop, 4);
            }


            return $produtos;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function getProdutosRelacionados($p)
    {
        try
        {

        	$produtos = '';


            $query = "SELECT *
                            FROM produto
                            	WHERE produto_deletado=0 AND produto_ativo=1 AND produto_id != {$p['produto_id']} AND subcategoria_id = {$p['subcategoria_id']} ORDER BY RAND() LIMIT 10";




            $result = Sql::_fetchAll($query);

            foreach ($result as $res)
            {

                $img = U::getImg('/imagens/produtos/'.$res['produto_id'].'_1_2.'.$res['produto_extensao1']);
                $link = 'produto.php?produto='.$res['produto_id'];

                $produtos.='
                <div class="product-item" '.U::divLink($link).'>
	              <div class="product-img">
	                <a href="'.$link.'">
	                  <img src="'.$img.'" alt="'.$res['produto_nome'].'" title="'.$res['produto_nome'].'"">
	                </a>
	                <a href="'.$link.'" class="product-quickview">Ver Produto</a>
	              </div>
	              <div class="product-details">
	                <h3>
	                  <a class="product-title" href="'.$link.'">'.$res['produto_nome'].'</a>
	                </h3>
	                <span class="price">
	                  <ins>
	                    <span class="ammount">'.Cart::mostraPreco($res['valor1']).'</span>
	                  </ins>
	                </span>
	              </div>
	            </div>';
            }


            return $produtos;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function getProdutosMaisVendidos()
    {
        try
        {

        	$produtos = '';


            $query = "SELECT p.*, s.subcategoria_nome
                            FROM produto p
                                INNER JOIN subcategoria s ON s.subcategoria_id=p.subcategoria_id
                                        WHERE p.produto_deletado=0 AND produto_ativo=1 AND p.checkbox0=1 ORDER BY produto_nome";




            $result = Sql::_fetchAll($query);

            foreach ($result as $res)
            {

                $img = U::getImg('/imagens/produtos/'.$res['produto_id'].'_1_1.'.$res['produto_extensao1']);
                $link = 'produto.php?produto_id='.$res['produto_id'];

                $produtos.='
                <div class="item" '.U::divLink($link).'>
                  <div class="col-item">
                    <!-- <div class="sale-label sale-top-right">Sale</div> -->
                    <div class="product-image-area"> <a class="product-image" title="'.$res['produto_nome'].'" href="'.$link.'"> <img src="'.$img.'" class="img-responsive" alt="'.$res['produto_nome'].'" /> </a>
                    <div class="hover_fly">
                        <a class="exclusive ajax_add_to_cart_button" href="#" title="Adicionar ao Carrinho">
                        <div>
                          <i class="icon-shopping-cart"></i>
                          <span>Adicionar ao Carrinho</span>
                        </div>
                        </a>
                      </div>
                    </div>
                    <div class="info">
                      <div class="info-inner">
                        <div class="item-title"> <a title="'.$res['produto_nome'].'" href="'.$link.'">'.$res['produto_nome'].'</a> </div>
                        <!--item-title-->
                        <div class="item-content">
                          <!-- <div class="ratings">
                            <div class="rating-box">
                              <div class="rating"></div>
                            </div>
                          </div> -->
                          <div class="price-box">
                            <p class="special-price"> <span class="price"> R$ '.Produto::getPreco($res).' </span> </p>
                          </div>
                        </div>
                        <!--item-content-->
                      </div>
                      <!--info-inner-->
                      <div class="clearfix"> </div>
                    </div>
                  </div>
                </div>';
            }


            return $produtos;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }


    static function getProdutosDestaque()
    {
        try
        {

        	$produtos = '';


            $query = "SELECT p.*, s.subcategoria_nome
                            FROM produto p
                                INNER JOIN subcategoria s ON s.subcategoria_id=p.subcategoria_id
                                        WHERE p.produto_deletado=0 AND produto_ativo=1 AND p.checkbox1=1 ORDER BY produto_nome";




            $result = Sql::_fetchAll($query);

            foreach ($result as $res)
            {

                $img = U::getImg('/imagens/produtos/'.$res['produto_id'].'_1_1.'.$res['produto_extensao1']);
                $link = 'produto.php?produto_id='.$res['produto_id'];

                $produtos.='
                <div class="item" '.U::divLink($link).' >
		            <div class="col-item">
		              <!-- <div class="sale-label sale-top-right">Sale</div> -->
		              <div class="product-image-area"> <a class="product-image" title="'.$res['produto_nome'].'" href="'.$link.'"> <img src="'.$img.'" class="img-responsive" alt="'.$res['produto_nome'].'" /> </a>
		              </div>
		              <div class="info">
		                <div class="info-inner">
		                  <div class="item-title"> <a title=" '.$res['produto_nome'].'" href="'.$link.'"> '.$res['produto_nome'].' </a> </div>
		                  <!--item-title-->
		                  <div class="item-content">
		                      <div class="price-box">
		                      <p class="special-price"> <span class="price"> R$ '.Produto::getPreco($res).' </span> </p>
		                    </div>
		                  </div>
		                  <!--item-content-->
		                </div>
		                <!--info-inner-->
		                <div class="actions">
		                  <button type="button" title="Adicionar ao Carrinho" class="button btn-cart"><span>Adicionar ao Carrinho</span></button>
		                </div>
		                <!--actions-->
		                <div class="clearfix"> </div>
		              </div>
		            </div>
		         </div>';
            }


            return $produtos;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function getPreco($p)
    {
    	try
    	{
    		$dataLimite = Cliente::getDado('clientes_associacao');
    		if($dataLimite && $dataLimite != '0000-00-00' && $dataLimite != '' && !is_null($dataLimite))
    		{

    			$hoje = date('Y-m-d');
				if(strtotime($hoje) <= strtotime($dataLimite))
				{
					return $p['valor2'];
				}

				return $p['valor3'];
    		}

    		return $p['valor1'];
    	}
    	catch( Exception $e )
    	{
    		X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
    	}
    }


	static function getProdutosRecentes()
	{
		try
		{
			$ret='';
			for ($i=0; $i < 10; $i++)
			{
				$ret.='
				<div class="item item-carousel">
		            <div class="products">
		                <div class="product">
		                    <div class="product-image">
		                        <div class="image">
		                            <a href="#"><img  src="assets/images/blank.gif" data-echo="assets/images/products/laco0.jpg" alt=""></a>
		                        </div><!-- /.image -->
		                    </div><!-- /.product-image -->
		                    <div class="product-info text-left">
		                        <h3 class="name"><a href="#">'.$i.'Laço de Onçinha</a></h3>
		                        <div class="description"></div>
		                        <div class="product-price">
		                            <span class="price">
		                                R$ 14,99
		                            </span>
		                        </div><!-- /.product-price -->
		                    </div><!-- /.product-info -->
		                    <div class="cart clearfix animate-effect">
		                        <div class="action">
		                            <ul class="list-unstyled">
		                                <li class="add-cart-button btn-group">
		                                    <button class="btn btn-primary icon" data-toggle="dropdown" type="button"><i class="fa fa-shopping-cart"></i></button><a href="produto-detalhado.php" class="btn btn-primary">Ver Detalhes</a>
		                                </li>
		                            </ul>
		                        </div><!-- /.action -->
		                    </div><!-- /.cart -->
		                </div><!-- /.product -->
		            </div><!-- /.products -->
		        </div>';
			}
			if($ret != '')
			{
				$ret='
				<h3 class="section-title2">Produtos Adicionados Recentemente</h3>
			    <div class="owl-carousel home-owl-carousel custom-carousel owl-theme outer-top-xs">
					'.$ret.'
			    </div>';
			}

			return U::clearStr($ret);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getCategoriasHome()
	{
		try
		{
			$ret = '';
			$sql = "SELECT * FROM categoria WHERE categoria_deletada=0 AND categoria_ativa=1";
			$result = Sql::_fetchAll($sql);

			foreach($result as $res)
			{
				$img = U::getImg('/imagens/categorias/'.$res['categoria_id'].'_1_1.'.$res['categoria_extensao1']);
				$link = HTTP.'/produtos/'.U::setUrlAmigavel($res['categoria_nome']);
				$ret.='
				<div class="col-sm-4" '.U::divLink($link).'>
					<div class="iconbox style3">
						<div class="iconbox-icon imgCategoria">
							<img src="'.$img.'"/>
						</div>
						<div class="iconbox-content">
							<h3 class="iconbox-title"><a href="'.$link.'">'.$res['categoria_nome'].'</a></h3>
							<div class="iconbox-desc">
								<a href="'.$link.'">'.$res['categoria_descricao'].'</a>
							</div>
						</div>
						<div class="clearfix">
						</div>
					</div>
				</div>';
			}
			return U::clearStr($ret);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getCategorias()
	{
		try
		{
			if(isset($_GET['categoria']))
			{
				return self::getSubcategorias();
			}
			$ret = '';
			$sql = "SELECT * FROM categoria WHERE categoria_deletada=0 AND categoria_ativa=1";
			$result = Sql::_fetchAll($sql);

			foreach($result as $res)
			{
				$img = U::getImg('/imagens/categorias/'.$res['categoria_id'].'_1_1.'.$res['categoria_extensao1']);
				$link = HTTP.'/produtos/'.U::setUrlAmigavel($res['categoria_nome']);
				$ret.='
				<div class="col-sm-4" '.U::divLink($link).'>
					<div class="imagebox style3">
						<div class="imagebox-image">
							<a href="'.$link.'" title="">
								<img src="'.$img.'" alt="">
								<i class="fa fa-link" aria-hidden="true"></i>
								<div class="overlay"></div>
							</a>
						</div>
						<div class="imagebox-header">
							<h3 class="imagebox-title">
								<a href="'.$link.'" title="">'.$res['categoria_nome'].'</a>
							</h3>
						</div>
						<div class="imagebox-content">
							<div class="imagebox-desc">
								'.$res['categoria_descricao'].'
							</div>
						</div>
					</div>
				</div>';
			}

			return array('titulo' => 'Produtos', 'conteudo' => U::clearStr($ret));
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getSubcategorias()
	{
		try
		{


			$ret = '';
			$sql = "SELECT * FROM subcategoria WHERE subcategoria_deletada=0 AND subcategoria_ativa=1 ORDER BY subcategoria_ordem";
			$result = Sql::_fetchAll($sql);

			foreach($result as $res)
			{
				$img = U::getImg('/imagens/subcategorias/'.$res['subcategoria_id'].'_1_1.'.$res['subcategoria_extensao1'], true);

				if(! $img)
				{
					$img = U::getImg('/imagens/categorias/'.$res['categoria_id'].'_1_1.'.$res['categoria_extensao1']);
				}
				$link = HTTP.'/produtos.php?subcategoria='.$res['subcategoria_id'];
				$active = isset($_GET['subcategoria']) && $_GET['subcategoria'] == $res['subcategoria_id'] ? ' active ' : '';
				$ret.='
				<li> <a class="'.$active.'" href="'.$link.'">'.$res['subcategoria_nome'].'</a></li>';
			}

			return $ret;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}


}
