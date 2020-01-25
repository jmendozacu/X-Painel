<?php
/**
 *  Inclui a classe Connection
 */
include_once( 'Connection.class.php' );
/**
 *  Classe respons�vel por gerar objetos PDO
 *  Created on 2011-07-23
 *  PHP version 5.3.0 and later
 *  @author Carlos Coelho <coelhoduda@hotmail.com>
 *  @version 0.1
 */
final class Transaction
{
    /**
     *  @var object Armazena a inst�ncia
     *  da classe PDO
     *  @access private
     */
    private static $instance = null;
    /**
     *  Construtor da classe declarado
     *  como private para evitar que se
     *  crie inst�ncias da classe Transaction
     */
    private function __construct( ){ }
    /**
     *  Abre uma conex�o com o banco de dados
     *  @param string O local do arquivo de configura��o
     *  @access public
     *  @return void
     */
    public static function open()
    {
        if( is_null( self::$instance ) )
        {
            self::$instance = Connection::open();    
        }
    }
    /**
     *  Pega a conex�o ativa
     *  @access public
     *  @return object A inst�ncia do PDO
     */
    public static function getInstance( )
    {
        try
        {
            if( ! is_null( self::$instance ) )
            {
                return self::$instance;
            }
            else
            {
                throw new Exception( 'Object PDO n�o localizado' );
            }
        }
        catch( Exception $e )
        {
            printf( "Error!: %s<br/>", $e->getMessage( ) );
            return false;
        }
    }
    /**
     *  Finaliza a conex�o com o banco de dados
     *  @access public
     *  @return void
     */
    public static function close( )
    {
        self::$instance = null;
    }
}
?>
