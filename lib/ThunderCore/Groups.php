<?php

namespace ThunderCore;

/**
* class which handles grups menagement
*/
class Groups implements \SplObserver // implements \ArrayAccess
{
  private $all_groups = [];

  /**
  * stores Array of current groups (for logged user or just for public)
  */
  private $current_groups = [];
  private $public_groups = [];
  private $Group;
  private $current_session;

  function __construct() {
    $this->Group = new ModelWrapper('Group');
    $this->current_session = App::Instance()->session;
    $this->current_session->attach($this);

    $this->all_groups = array_map( function($group) { return $group->name; }, $this->Group->all());

  }

  /**
  * allows to add groups to array
  */
  public function add_public() {
      $groups = func_get_args();
      $args = array_merge([&$this->public_groups], $groups);
      call_user_func_array("array_push", $args);

      $this->set_current_groups();
  }

  public function __get($property) {
    $property = $property . '_groups';
    if (property_exists($this, $property)) {
        return $this->$property;
    }
  }

  /**
  * is called when session is updated (observer pattern)
  */
  public function update(\SplSubject $subject) {
    $this->set_current_groups();
  }

  // ----------------------------------------
  //            PRIVATE METHODS
  // ----------------------------------------

  /**
  * doc
  */
  private function set_current_groups() {
    if($this->current_session->check()) {
      $this->current_groups = array_map(
       function($group) { return $group->name; }, $this->current_session->user->groups->toArray()
       );
    }
    else {
      $this->current_groups = $this->public_groups;
    }
  }
}