<?php

namespace ThunderCore;


/**
* is base class for app controllers (not obligatory)
*/
class BaseController
{
  protected function render_json($data) {
    header('Content-type: application/json');
    echo json_encode($data);
    die;
  }
}