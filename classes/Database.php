<?php

/**
 * class Database
 * 
 */
class Database
{

  /** Aggregations: */

  /** Compositions: */

   /*** Attributes: ***/

  /**
   * 
   * @static
   * @access private
   */
  private static $isConnected = false;
  private static $connection;


  /**
   * 
   *
   * @param string query 

   * @return 
   * @static
   * @access public
   */
  public static function runQuery( $query ) {
    //FIXME: osetrit chyby
    return mysql_query($query);
  } // end of member function runQuery

  /**
   * 
   *
   * @return 
   * @static
   * @access public
   */
  public static function connect( ) {
    //TODO: nacitat z nastaveni
    //FIXME: osetrit chyby
    $connection = mysql_pconnect("localhost","user","drowssap");
    $isConnected = true;
    mysql_select_db("mio-gallery");
  } // end of member function connect

  /**
   * 
   *
   * @return 
   * @static
   * @access public
   */
  public static function disconnect( ) {
    //TODO: nakodit
  } // end of member function disconnect





} // end of Database
?>
