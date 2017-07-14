<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Otter_Controller {
	
	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		echo 'category';
	}

	public function list($offset_results=0){

		$this->load->model('categorymodel', 'cat');
		$itemsPerPage = $this->cat->getItemsPerPage();
		$page_number = $offset_results == 0 ? $offset_results : $offset_results/$itemsPerPage;
		$data['items'] = $this->cat->getList($page_number);
		$data['list_title']  = 'Lista de Categorías';
		$data['dynamic_field']  = 'Cantidad de Subcategorías';
		$data['title'] =  $this->_genericPageTitle.' Lista Categorías';
		$data['error_msg'] = $this->session->flashdata('error_msg');
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['url_add'] = base_url('category/add');

		$this->load->library('pagination');

		$config['base_url'] = base_url('category/list');
		$config['total_rows'] = $this->cat->getTotal();
		$config['per_page'] = $itemsPerPage;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$this->parser->parse('template/list', $data);
		//$this->load->view('pages/cat/list',$data);
	}

	public function add(){
		$this->load->model('categorymodel', 'cat');
		$data['title'] =  $this->_genericPageTitle.' Lista Noticias';
		$data['error_msg'] = null;
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['title_field'] = null;
		$data['content_field'] = null;
		$data['can_add_images'] = false;
		$data['post_url'] = base_url('category/add');
		$data['image_post_url'] = null;
		$data['options'] = $this->cat->getAll();

		$post = $this->input->post(NULL, TRUE);

		if( $post ){
			// run the add script
			$data['title_field'] = $post['title'];
			$error = [];
			if( !isset( $post['title']) || !$post['title'] != ''){
				$error[] = 'El título es obligatorio';
			}

			if( count($error) > 0){
				$data['error_msg'] = 'Se han presentado los siguientes errores: <br /> <ul>';
				foreach( $error as $e){
					$data['error_msg'] .= '<li>'.$e.'</li>';
				}
				$data['error_msg'] .= '</ul>';
			}else{
				$post['parentid'] = $post['parentid'] == '' ? null : $post['parentid'];
				$id = $this->cat->insert_entry($post);
				$this->session->set_flashdata('success_msg', 'Categoría guardada con éxito');
				if($post['parentid'] == null){
					redirect('category/edit/'.$id);
				}else{
					$this->session->set_flashdata('success_msg', 'Subcategoría guardada con éxito');
					redirect('category/list/');
				}
				
			}
		}

		$this->parser->parse('pages/category/add',$data);
	}


	public function edit($id){
		$this->load->model('categorymodel', 'cat');

		$data['title'] =  $this->_genericPageTitle.' Editar Categoría';
		$data['error_msg'] = null;
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['title_field'] = null;
		$data['content_field'] = null;
		$data['can_add_images'] = true;
		$data['post_url'] = base_url('category/edit/'.$id);
		$data['image_post_url'] = base_url('image/add/'.$id.'/category');

		$cat = $this->cat->getOne($id);
		$data['title_field'] = $cat->title;
		$data['options'] = $this->cat->getAll($cat->id);

		// For the subcategories list
		$data['items'] = $this->cat->getSubcategories($cat->id);
		$data['pagination'] = null;

		foreach ($data['options'] as &$o) {
			if( $o->id === $cat->parentid){
				$o->selected = 'selected';
			}else{
				$o->selected = '';
			}
		}

		$post = $this->input->post(NULL, TRUE);

		if( $post ){
			// run the add script
			$data['title_field'] = $post['title'];
			$error = [];
			if( !isset( $post['title']) || !$post['title'] != ''){
				$error[] = 'El título es obligatorio';
			}

			if( count($error) > 0){
				$data['error_msg'] = 'Se han presentado los siguientes errores: <br /> <ul>';
				foreach( $error as $e){
					$data['error_msg'] .= '<li>'.$e.'</li>';
				}
				$data['error_msg'] .= '</ul>';
			}else{
				$post['id'] = $id;
				$post['parentid'] = $post['parentid'] == '' ? null : $post['parentid'];
				$this->cat->update_entry($post);
				$this->session->set_flashdata('success_msg', 'Categoría guardada con éxito');
				redirect('category/edit/'.$id);
			}
		}

		$this->parser->parse('pages/category/add',$data);
	}

	public function delete($id){
		$this->load->model('categorymodel', 'category');
		$this->category->delete($id);

		$this->session->set_flashdata('success_msg', 'Categoría eliminada con éxito');
		redirect('category/list');
	}
}
