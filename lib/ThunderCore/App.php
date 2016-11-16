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
    private $environment;

    /**
     * string with system path to app root directory
     */
    public static $root_dir;

    /**
     * doctrine params for creating Doctrine\ORM\EntityManager object
     */
    private $dbParams;

    /**
     * app name based on directory name of application root
     */
    private $name;

    // ----------------------------------------
    //             PUBLIC METHODS
    // ----------------------------------------

    /**
    * get ENVIRONMENT_NAME as params (probably DEVELOPMENT, PRODUCTION or TEST)
    */
    function __construct($ENVIRONMENT_NAME) {
      $this->set_root_dir();
      $this->set_env($ENVIRONMENT_NAME);
      $this->set_app_name();
    }

    function __get($property) {
            if (property_exists($this, $property)) {
                return $this->$property;
            }
    }

    // ----------------------------------------
    //             PRIVATE METHODS
    // ----------------------------------------

    private function set_app_name() {
      $dirs = explode("/", self::$root_dir);
      $this->name = end($dirs);
    }

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