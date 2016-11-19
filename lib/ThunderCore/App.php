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
     * database object
     */
    private $database;

    /**
     * sets up entity menager
     */
    private $entityManager;

    /**
     * string with system path to app root directory
     */
    public static $root_dir;

    /**
     * app name based on directory name of application root
     */
    private $name;

    // ----------------------------------------
    //             PUBLIC METHODS
    // ----------------------------------------

    /**
    * get ENVIRONMENT_NAME as params (probably DEVELOPMENT, PRODUCTION or TEST), sets up app in GLOBALS
    */
    function __construct($ENVIRONMENT_NAME) {
      $this->set_root_dir();
      $this->set_env($ENVIRONMENT_NAME);
      $this->set_app_name();
      $this->set_database();

      $GLOBALS['app'] = $this;
    }

    function __get($property) {
            if (property_exists($this, $property)) {
                return $this->$property;
            }
    }

    // ----------------------------------------
    //             PRIVATE METHODS
    // ----------------------------------------

    /**
     * sets app name from root directory name
     */
    private function set_app_name() {
      $dirs = explode("/", self::$root_dir);
      $this->name = end($dirs);
    }

    private function set_root_dir() {
      self::$root_dir = getcwd();
    }

    private function set_env($ENVIRONMENT_NAME) {
      $this->environment = new Environment($ENVIRONMENT_NAME);
    }

    private function set_database() {
      // if(empty($this->environment)) 
      //   throw new Exception('You have to set environment first, use set_env method.')
      // else
      $this->database = new Database($this->environment);
      $this->entityManager = $this->database->getEntityManager();
    }
}