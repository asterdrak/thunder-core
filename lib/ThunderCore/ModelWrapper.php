<?php
namespace ThunderCore;


/**
* wraps up entities class in object to easy use Models
*/
class ModelWrapper extends Helpers\BasicHelper
{
  private $model_name;
  private $app;
  

  /**
   * 
   */
  function __construct($model_name)
  {
    $this->model_name = $model_name;
    $this->app = App::Instance();
  }

  /**
  * returns reference to newly created entity
  */
  public function new() {
    $new_object = new $this->model_name();
    $new_object->set_do_persist(true);
    return $new_object;
  }


  /**
  * return array of all entities for chosen model
  */
  public function all() {
    return $this->app->entityManager->getRepository($this->model_name)->findAll();
  }

  /**
   * return model with specific id
   */
  public function find($param) {
    if(is_numeric($param))
      return $this->app->entityManager->getRepository($this->model_name)->find($param);
    elseif (is_array($param)) {
      $result = $this->app->entityManager->getRepository($this->model_name)->findBy($param);
      if($result == [])
        return null;
      else
        return $result[0];
    }
  }


  public function first() {
    return $this->all()[0];
  }

}