<?
class File
{
	static function criar($arquivo,$dados)
	{
		try
		{
			file_put_contents ($arquivo , $dados);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

}