<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Otter_Controller extends CI_Controller {

	protected $_genericPageTitle = 'Geo Ibarra -';
	public function __construct()
    {
        parent::__construct();
        $this->_checkSession();
    }

    protected function _checkSession(){
    	if( !$_SESSION['logged_in'] ){
			redirect('/login/destroy');
		}
    }
}
