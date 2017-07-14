<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Specialevent extends Otter_Controller {
	
	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		echo 'special';
	}

	public function list($offset_results=0){

		$this->load->model('specialeventmodel', 'special');
		$itemsPerPage = $this->special->getItemsPerPage();
		$page_number = $offset_results == 0 ? $offset_results : $offset_results/$itemsPerPage;
		$data['items'] = $this->special->getList($page_number);
		$data['list_title']  = 'Lista de Eventos Especiales';
		$data['dynamic_field']  = 'Cantidad de Imágenes';
		$data['title'] =  $this->_genericPageTitle.' Lista Eventos Especiales';
		$data['error_msg'] = $this->session->flashdata('error_msg');
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['url_add'] = base_url('specialevent/add');

		$this->load->library('pagination');

		$config['base_url'] = base_url('specialevent/list');
		$config['total_rows'] = $this->special->getTotal();
		$config['per_page'] = $itemsPerPage;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$this->parser->parse('template/list', $data);
		//$this->load->view('pages/special/list',$data);
	}

	public function add(){

		$data['title'] =  $this->_genericPageTitle.' Lista Eventos Especiales';
		$data['error_msg'] = null;
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['title_field'] = null;
		$data['description'] = null;
		$data['can_add_images'] = false;
		$data['post_url'] = base_url('specialevent/add');
		$data['image_post_url'] = null;

		$post = $this->input->post(NULL, TRUE);

		if( $post ){
			// run the add script
			$data['title_field'] = $post['title'];
			$data['description'] = $post['description'];
			$error = [];
			if( !isset( $post['title']) || !$post['title'] != ''){
				$error[] = 'El título es obligatorio';
			}

			if( !isset( $post['description']) || !$post['description'] != ''){
				$error[] = 'El contenido es obligatorio';
			}

			if( count($error) > 0){
				$data['error_msg'] = 'Se han presentado los siguientes errores: <br /> <ul>';
				foreach( $error as $e){
					$data['error_msg'] .= '<li>'.$e.'</li>';
				}
				$data['error_msg'] .= '</ul>';
			}else{
				$this->load->model('specialeventmodel', 'special');
				$id = $this->special->insert_entry($post);
				$this->session->set_flashdata('success_msg', 'Evento Especial guardado con éxito');
				redirect('specialevent/edit/'.$id);
			}
		}

		$this->parser->parse('pages/specialevent/add',$data);
	}


	public function edit($id){
		$this->load->model('specialeventmodel', 'special');

		$data['title'] =  $this->_genericPageTitle.' Lista Eventos Especiales';
		$data['error_msg'] = null;
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['title_field'] = null;
		$data['description'] = null;
		$data['can_add_images'] = true;
		$data['post_url'] = base_url('specialevent/edit/'.$id);
		$data['image_post_url'] = base_url('image/add/'.$id.'/specialevent');

		$special = $this->special->getOne($id);
		$data['title_field'] = $special->title;
		$data['description'] = $special->description;

		// get all images
		$this->load->model('imgmodel', 'img');
		$data['images'] = $this->img->getForObject($special->id,'specialevent');

		$post = $this->input->post(NULL, TRUE);

		if( $post ){
			// run the add script
			$data['title_field'] = $post['title'];
			$data['description'] = $post['description'];
			$error = [];
			if( !isset( $post['title']) || !$post['title'] != ''){
				$error[] = 'El título es obligatorio';
			}

			if( !isset( $post['description']) || !$post['description'] != ''){
				$error[] = 'El contenido es obligatorio';
			}

			if( count($error) > 0){
				$data['error_msg'] = 'Se han presentado los siguientes errores: <br /> <ul>';
				foreach( $error as $e){
					$data['error_msg'] .= '<li>'.$e.'</li>';
				}
				$data['error_msg'] .= '</ul>';
			}else{
				$post['id'] = $id;
				$this->special->update_entry($post);
				$this->session->set_flashdata('success_msg', 'Evento Especial guardado con éxito');
				redirect('specialevent/edit/'.$id);
			}
		}

		$this->parser->parse('pages/specialevent/add',$data);
	}

	public function delete($id){
		$this->load->model('specialeventmodel', 'special');
		$this->load->model('imgmodel', 'img');

		$this->img->deleteAll($id,'specialevent');
		$this->special->delete($id);

		$this->session->set_flashdata('success_msg', 'Evento especial eliminado con éxito');
		redirect('specialevent/list');
	}
}
