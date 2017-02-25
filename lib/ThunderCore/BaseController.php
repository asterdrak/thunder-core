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

  protected function render_nothing($status = 204) {
    header('Content-type: application/json');
    http_response_code($status);
    die;
  }

  protected function bad_request() {
    http_response_code(400);
  }
}