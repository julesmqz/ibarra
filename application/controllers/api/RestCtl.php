<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';

class RestCtl extends REST_Controller {
    public function index_get()
  {
    // Display all books
    $this->response([
		'returned' => true,
	]);
  }
}
