<?
class Sql
{
	static function checkDuplicate($tabela, $coluna, $valor, $colId = false, $colIdVal = false)
	{

		try
		{
			$primariaAtual = '';
			if($colId && $colIdVal)
			{
				$primariaAtual = " AND {$colId} != '{$colIdVal}'";
			}

			return Sql::_fetch("SELECT {$coluna} FROM {$tabela} WHERE {$coluna}='{$valor}' {$primariaAtual}");
		}

		catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }

	}
	static function toLike($str)
	{
		try
		{
			return " LIKE '%".U::setUrlAmigavel($str,'-', '%')."%'";
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function _fetchAll($sql, $params = array())
	{
		try
		{
			X::echoBug($sql);
			Transaction::open();
			$conexao = Transaction::getInstance();
			$conexao->beginTransaction();
			$query = $conexao->prepare($sql);
			$query->execute($params);
			Transaction::close();
			return $query->fetchAll();
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}

	}

	static function _fetch($sql,$params = array())
	{
		try
		{
			X::echoBug($sql);
			Transaction::open();
			$conexao = Transaction::getInstance();
			$conexao->beginTransaction();
			$query = $conexao->prepare($sql);
			$query->execute($params);
			Transaction::close();
			return $query->fetch();
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}

	}

	static function _fetchOrAll($sql,$params = array())
	{
		try
		{
			X::echoBug($sql);
			Transaction::open();
			$conexao = Transaction::getInstance();
			$conexao->beginTransaction();
			$query = $conexao->prepare($sql);
			$query->execute($params);
			Transaction::close();
			return $query;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}

	}

	static function _fetchAllAssoc($sql,$params = array())
	{
		try
		{
			X::echoBug($sql);
			Transaction::open();
			$conexao = Transaction::getInstance();
			$conexao->beginTransaction();
			$query = $conexao->prepare($sql);
			$query->execute($params);
			Transaction::close();
			return $query->fetchAll(PDO::FETCH_KEY_PAIR);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}

	}

	static function _query($sql,$params = array())
	{
		try
		{
			$update = strtolower(strtok($sql, ' ')) == 'insert' ? true : false; // UPDATE, INSERT

		    X::echoBug($sql);
			Transaction::open();
			$conexao = Transaction::getInstance();
			$query = $conexao->prepare($sql);
			$result = $query->execute($params);
			Transaction::close();
			if($result)
			{
			    if($update)
			    {
			        return $conexao->lastInsertId();
			    }
			    return $result;
			}
			return false;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}

	}

	static function _rowCount($sql,$params = array())
	{
		try
		{
			X::echoBug($sql);
			Transaction::open();
			$conexao = Transaction::getInstance();
			$conexao->beginTransaction();
			$query = $conexao->prepare($sql);
			$result = $query->execute($params);
			Transaction::close();
			return $query->rowCount();
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}

	}
	static function checaTabelaExists($tabela)
	{
		try
		{
			$sql = "SHOW TABLES LIKE '{$tabela}'";
			X::echoBug($sql);
			Transaction::open();
			$conexao = Transaction::getInstance();
			$conexao->beginTransaction();
			$query = $conexao->prepare($sql);
			$result = $query->execute();
			Transaction::close();
			return $query->fetch();
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
}
