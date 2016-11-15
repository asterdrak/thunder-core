<?php
namespace ThunderCore;

/**
* General app class
*/
class App
{
    /**
     * ENVIRONMENT object with env variables
     */
    public $environment;

    /**
     * string with system path to app root directory
     */
    public static $root_dir;

    /**
     * doctrine params for creating Doctrine\ORM\EntityManager object
     */
    public $dbParams;

    // ----------------------------------------
    //             PUBLIC METHODS
    // ----------------------------------------

    /**
    * get ENVIRONMENT_NAME as params (probably DEVELOPMENT, PRODUCTION or TEST)
    */
    function __construct($ENVIRONMENT_NAME) {
      $this->set_root_dir();
      $this->set_env($ENVIRONMENT_NAME);
    }

    // ----------------------------------------
    //             PRIVATE METHODS
    // ----------------------------------------

    private function set_root_dir() {
      self::$root_dir = getcwd();
    }

    /**
    * set environment object and this object fields (from Environment object)
    */
    private function set_env($ENVIRONMENT_NAME) {
      $this->environment = new Environment($ENVIRONMENT_NAME);
      $this->dbParams = $this->environment['dbParams'];
    }
}