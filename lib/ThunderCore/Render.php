<?php
namespace ThunderCore;

/**
* allows for rendering views, wraps up mt haml object
*/
class Render extends Helpers\BasicHelper
{
  private $haml;
  private $executor;

  private $render;

  function __construct()
  {
    $this->haml = new \MtHaml\Environment('php');
    $this->executor = new \MtHaml\Support\Php\Executor($this->haml, array(
        'cache' => sys_get_temp_dir().'/haml'
    ));
  }

  /**
  * function calls haml display with passed params
  */
  public function display($path, $values = []) {

    $values['render'] = $this->render_factory($this->executor);

    $this->executor->display($path, $values);
  }

  private function render_factory($executor, $group = null)
  {
    if(empty($group)) {
      $groups = App::Instance()->groups;
      $group = $groups->public[0];
    }

    return function($template_path, $values = []) use (&$executor, &$group) {
        $executor->display(getcwd() . "/app/views/" . $group . "/" . $template_path . ".haml", $values);
    };
  }
}