<?php
namespace includes;

use includes\Db;
use includes\tools\Orm;

/**
 * Class Request (Singleton).
 * The Request object allows to easily retreive the variables received by the client request.
 * Additionally, it also allows to automatically put/update these variables in the $_SESSION array.
 * Singleton means that there is only one instance of the Request class.
 * A reference to the Request instance can be get with Request::getInstance().
 */
final class Request
{
    /**
     * The only instance of the Request class.
     *
     * @var Request object.
     */
    private static $_instance = NULL;

    /**
     * An array of references for storing the environment variables related to the client request.
     *
     * @var array.
     */
    private $_vars            = array();


    /**
     * Class constructor.
     * It cannot be used to instanciate a Request object.
     * This class is a singleton class, this means that there is only one instance
     * of the Request class and this instance is shared by every one who does a call
     * to Request::getInstance().
     *
     * @see getInstance to know how to create the Request instance or to get a reference to it.
     */
    private function __construct()
    {
        $this->_vars = array(
                'G' => &$_GET,
                'P' => &$_POST,
                'C' => &$_COOKIE,
                'S' => &$_SESSION
        );
    }

    /**
     * Creates or returns a reference to the only instance of the Request class.
     *
     * @return Request a reference to the only instance of the Request class.
     */
    public static function getInstance()
    {
        if( !self::$_instance ) {

            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Checks if an environment variable exists in $_GET, $_POST, $_COOKIE or $_SESSION.
     *
     * @param  string  $name  the name of the variable to search for.
     * @param  string  $order ( optional ) the order in which the function must look for the variable in the global variables.
     *                        The default order is 'GPCS' for $_GET, $_POST, $_COOKIE, $_SESSION.
     *                        It is possible to search, by example, a variable only in $_GET and $_POST by passing 'GP'.
     * @return boolean        true if the variable is set.
     *                        IMOPORTANT:
     *                        Note that the method will return false for an existing variable which has the value NULL.
     */
    public function varExists( $name, $order = 'GPCS' )
    {
        $order = preg_split( '//', strtoupper( $order ), -1, PREG_SPLIT_NO_EMPTY );

        foreach( $order as $k ) {

            if( isset( $this->_vars[ $k ][ $name ] ) ) {

                return true;
            }
        }

        return false;
    }

    /**
     * Gets an environment variable from $_GET, $_POST, $_COOKIE or $_SESSION.
     *
     * @param  string  $name         the name of the variable to search for.
     * @param  boolean $putToSession ( optional ) if true, the variable will be copied in the session variables.
     *                               (if a session has been started)
     * @param  string  $order        ( optional ) the order in which the function must look for the variable in the global variables.
     *                               The default order is 'GPCS' for $_GET, $_POST, $_COOKIE, $_SESSION.
     *                               It is possible to search, by example, a variable only in $_GET and $_POST by passing 'GP'.
     * @param  string  $value        Used to set a value in the session 
     * @return mixed|NULL            the value of the variable, if it is found, or NULL if it is not found.
     */
    public function getVar( $name, $putToSession = false, $order = 'GPCS', $value = null )
    {
        $order = preg_split( '//', strtoupper( $order ), -1, PREG_SPLIT_NO_EMPTY );

        foreach( $order as $k ) 
        {
            if( $putToSession && session_id() && $k === 'S' && isset( $value ) )
            {
                $this->_vars[ $k ][ $name ] = $value;

                $_SESSION[ $name ] = $this->_vars[ $k ][ $name ];

                return $this->_vars[ $k ][ $name ];
            }
            else if( isset( $this->_vars[ $k ][ $name ] ) ) 
            {
                if( $putToSession && session_id() && $k !== 'S' ) 
                {
                    $_SESSION[ $name ] = $this->_vars[ $k ][ $name ];
                }
                return $this->_vars[ $k ][ $name ];
            }
            else if( isset( $value ) )
            {
                $this->_vars[ $k ][ $name ] = $value;

                return $this->_vars[ $k ][ $name ];
            }

        }
        return NULL;
    }


    /**
     * Puts an environment variable(or several) in the $_SESSION array.
     * Note that this function will have no effect if no session has been started
     * or if the variable is found in the $_SESSION array.
     *
     * @param  string|array $name  the name of the variable to put in the $_SESSION array. It can also be an array of names.
     * @param  string       $order ( optional ) the order in which the function must look for the variable in the global variables.
     *                             The default order is 'GP' for $_GET, $_POST.
     *                             Note that it is possible to pass 'C' to search for a variable in $_COOKIE
     *                             even if this does not make a lot of sens ...
     *                             Note also that pass 'S' is useless, it will have no effect.
     * @return NULL                nothing.
     */
    public function putToSession( $name, $order = 'GP', $value = null )
    {
        if( session_id() ) { // true if a session has been started

            if( is_array( $name ) ) {

                foreach( $name as $vName ) {

                    $this->getVar( $vName, true, $order, $value );
                }

            } else {

                $this->getVar( $name, true, $order, $value );
            }
        }
    }

    /**
     * Removes a variable(or several) from the $_SESSION array.
     * Note that this method will have no effect if no session has been started.
     *
     * @param  string|array $name  the name of the variable to remove from the $_SESSION array. It can also be an array of names.
     * @return NULL                nothing.
     */
    public function removeFromSession( $name )
    {
        if( session_id() ) { // true if a session has been started

            if( is_array( $name ) ) {

                foreach( $name as $vName ) {

                    unset( $_SESSION[ $vName ] );
                }

            } else {

                unset( $_SESSION[ $name ] );
            }
        }
    }

    
    public function generateToken( $length = 16 )
    {
        return bin2hex( openssl_random_pseudo_bytes( $length ) );
    }
    
}