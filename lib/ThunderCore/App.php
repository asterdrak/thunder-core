<?php
namespace ThunderCore;

/**
* General app class
*/
final class App
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

  /**
   * render object
   */
  private $render;


  /**
   * groups object
   */
  private $groups;

  /**
   * session object
   */
  private $session;

  /**
   * object which allows to use safe params
   */
  private $params;

  /**
   * controller and method names for current query
   */
  public $controller;
  public $method;

  // ----------------------------------------
  //             PUBLIC METHODS
  // ----------------------------------------

  /**
  * get ENVIRONMENT_NAME as params (probably DEVELOPMENT, PRODUCTION or TEST), sets up app in GLOBALS
  */
  private function __construct($ENVIRONMENT_NAME) {

    if(is_null($ENVIRONMENT_NAME)) throw new Exception("Empty ENVIRONMENT_NAME in ThunderCore/App", 1);


    $this->set_root_dir();
    $this->set_env($ENVIRONMENT_NAME);
    $this->set_app_name();
    $this->set_database();

    $this->render = new Render();

    $this->session = new Session();

    $this->set_global_application();
  }

  /**
  * method which handles routing, controller and render view
  * additionaly it checks if there is at least one public group
  */
  public function run() {
    http_response_code(500);
    $this->register_controllers_loader();

    $this->session->start();

    try {

      $router = new Router($_SERVER["REQUEST_URI"]);

      if(count($this->groups->public) == 0) throw new \Exception(
        "You have to at least one public group, use eg \$app->groups->add_public('public')
        ", 1);

      $element = new Element($router->controller, $router->method, $router->id);

      if($element->render_template) {
        http_response_code(200);

        $this->render->display('app/views/public/template.haml',
          array_merge($element->vars, get_object_vars($this)));
      }

    } catch (\Exception $e) {
      http_response_code(404);
      require self::$root_dir . '/404.php';
      die();
    }


  }

  /**
   * standard magic getter with added mechanizm - if from outside someone want to access subobject
   * and this object wastn't initialized it will be now in initialize_property_if_empty()
   */
  public function __get($property) {
    if (property_exists($this, $property)) {
        $this->initialize_property_if_empty($property);
        return $this->$property;
    }
  }

  /**
  * reconnect do database and opens new entityManager
  */
  public function restart_entityManager() {
    $this->set_database();
    $this->set_global_application();
  }

  /**
  * reload database info by restarting EntityManager
  */
  public function reload_database() {
    $this->restart_entityManager();
  }

  /**
  * returns App object;
  */
  public static function Instance($ENVIRONMENT_NAME = null) {
    static $instance = null;

    if ($instance === null || !is_null($ENVIRONMENT_NAME)) {
      $instance = new App($ENVIRONMENT_NAME);
    }

    return $instance;
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
    $this->database = new Database($this->environment);
    $this->entityManager = $this->database->getEntityManager();
  }

  private function register_controllers_loader()
  {
    $Loader = new ControllersClassLoader();
    $Loader->register();
  }

  private function set_global_application() {
    $GLOBALS['application'] = $this;
  }

  /**
  * initializes passed property with class from ThunderCore\property if class exists
  */
  private function initialize_property_if_empty(string $property) {
    if(empty($this->$property)) {
      $class_name = '\ThunderCore\\' . ucfirst($property);
      if(class_exists($class_name))
        $this->$property = new $class_name;
    }
  }
}