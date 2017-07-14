<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter extends Otter_Controller {
	
	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		echo 'news';
	}

	public function list($offset_results=0){

		$this->load->model('newslettermodel', 'news');
		$itemsPerPage = $this->news->getItemsPerPage();
		$page_number = $offset_results == 0 ? $offset_results : $offset_results/$itemsPerPage;
		$data['items'] = $this->news->getList($page_number);
		$data['list_title']  = 'Lista de Correos';
		$data['dynamic_field']  = 'Cantidad de Imágenes';
		$data['title'] =  $this->_genericPageTitle.' Lista Correos';
		$data['error_msg'] = $this->session->flashdata('error_msg');
		$data['success_msg'] = $this->session->flashdata('success_msg');

		$this->load->library('pagination');

		$config['base_url'] = base_url('newsletter/list');
		$config['total_rows'] = $this->news->getTotal();
		$config['per_page'] = $itemsPerPage;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$this->parser->parse('template/list2', $data);
		//$this->load->view('pages/news/list',$data);
	}
}
