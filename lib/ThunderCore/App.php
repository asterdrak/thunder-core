<?php
namespace ThunderCore;

// function Psysh() {
//   eval(\Psy\sh());
// }

/**
* 
*/
class App// extends AnotherClass
{
    public $environment;
    public static $root_dir;
    public $dbParams;

    /**
    *
    */
    function __construct($ENVIRONMENT_NAME) {
      self::$root_dir = getcwd();

      $this->environment = new Environment($ENVIRONMENT_NAME);

      $this->dbParams = $this->environment['dbParams'];
    }

    public function start() {
        return 'Hallo!';
    }
}