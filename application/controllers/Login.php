<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	protected $_creds = array('user' => 'admin', 'pass' => 'rules');
	
	public function index()
	{
		if( $this->session->has_userdata('logged_in') ){
			redirect('/login/destroy');
		}
		$data['title'] = 'Geo Ibarra Admin Panel';
		$data['error_msg'] = $this->session->flashdata('error_msg');
		$data['success_msg'] = null;
		// Delete session here
		$this->load->view('pages/login',$data);
	}

	public function auth()
	{
		$post = $this->input->post(NULL, TRUE);

		if( $post['user'] != $this->_creds['user'] && $post['pass'] != $this->_creds['pass']){
			$this->session->set_flashdata('error_msg', 'Credenciales incorrectas');
			redirect('/');
		}else{
			$this->session->set_userdata('logged_in',TRUE);
			redirect('/news/list');
		}
	}

	public function destroy(){
		$this->session->unset_userdata('logged_in');
		redirect('/');
	}
}
