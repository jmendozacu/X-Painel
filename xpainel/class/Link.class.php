<?
class Link
{
	static function whats($num)
	{
		try
		{
			return 'https://api.whatsapp.com/send?phone=55'.U::getNumbersStr($num);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function fone($num)
	{
		try
		{
			return 'tel:'.U::getNumbersStr($num);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function mail($mail)
	{
		try
		{
			return 'mailto:'.$mail;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getLinks($s)
	{
		try
		{
			$ret = '';
			$sql = "SELECT * FROM links WHERE link_deletado=0 AND link_ativo=1";
			$result = Sql::_fetchAll($sql);

			foreach($result as $res)
			{
				$img = U::getImg('imagens/links/'.$res['link_id'].'_1_1.'.$res['link_extensao1']);
				$link = $res['link_url'];

				$ret.='
					<div class="col-xs-12 col-sm-4 col-md-2">
						<div class="brand">
							<img class="img-responsive center-block" src="'.$img.'" alt="'.$res['link_titulo'].'" >
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
}