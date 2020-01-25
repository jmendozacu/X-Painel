<?php
require_once('connection.php');
abstract class Connection extends PDO
{
    /**
     *  Obtém uma conexão com o banco de dados
     *  @param string O local do arquivo de configuração
     *  @access public
     *  @return object A instância do PDO
     */    
    public static function open()
    {
        try
        {

			$host = DB_HOST;
            $name = DB_NAME;
            $user = DB_USER;
            $pass = DB_PASS;
            $type = DB_TYPE;
            $port = DB_PORT;


            switch( $type )
            {
                case 'pgsql':
                    $conn = new PDO( sprintf( 'pgsql:dbname=%s; user=%s; password=%s; host=%s; port=%s', $name, $user, $pass, $host, $port ) );
                    break;
                case 'mysql':
                    $conn = new PDO( sprintf( 'mysql:host=%s; port=%s; dbname=%s', $host, $port, $name ), $user, $pass );
                    break;
                case 'sqlite':
                    $conn = new PDO( sprintf( 'sqlite:%s', $name ) );
                    break;
                case 'ibase':
                    $conn = new PDO( sprintf( 'firebird:dbname=%s', $name ), $user, $pass );
                    break;
                case 'oci8':
                    $conn = new PDO( sprintf( 'oci:dbname=%s', $name ), $user, $pass );
                    break;
                case 'mssql':
                    $conn = new PDO( sprintf( 'mssql:host=%s,1433; dbname=%s', $host, $name ), $user, $pass );
                    break;
            }        
            if( $conn instanceof PDO )
            {
				if(SET_UTF8_BD)
					$conn->exec("set names utf8");
                $conn->setAttribute( PDO::ATTR_CASE , PDO::CASE_LOWER );
                $conn->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
                $conn->setAttribute( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
                $conn->setAttribute( PDO::ATTR_AUTOCOMMIT, true );
                $conn->setAttribute( PDO::ATTR_TIMEOUT, 10 );
            }
        }
        catch( Exception $e )
        {
            die(DEBUG ? 'Erro!: '.$e->getMessage( ).'<br/>' : 'Erro de conexão');
            return false;    
        }
        return $conn;
    }
    static function ancorar($ancora, $prefixjs = 'javascript:', $home = '/index.php')
	{
		try
		{
			if($_SERVER['PHP_SELF'] == $home)
			{
				return $prefixjs."ancorar('{$ancora}')";
			}

			return HTTP.'#'.$ancora;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
}
?>
