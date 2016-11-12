<?php
namespace ThunderCore;

/**
* 
*/
class App// extends AnotherClass
{
    private $ENVIRONMENT;

    function __construct($ENVIRONMENT) {
      $this->ENVIRONMENT = $ENVIRONMENT;
      echo $ENVIRONMENT;
      new Environment;
    }

    public function set_environment_variables() {
    }

    public function start() {
        return 'Hallo!';
    }
}