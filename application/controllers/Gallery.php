<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends Otter_Controller {

	protected $_pageTitle = ' Lista Galerías';
	
	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		echo 'gallery';
	}

	public function list($offset_results=0){

		$this->load->model('gallerymodel', 'gallery');
		$itemsPerPage = $this->gallery->getItemsPerPage();
		$page_number = $offset_results == 0 ? $offset_results : $offset_results/$itemsPerPage;
		$data['items'] = $this->gallery->getList($page_number);
		$data['list_title']  = 'Lista de Galerías';
		$data['dynamic_field']  = 'Categoría';
		$data['title'] =  $this->_genericPageTitle.$this->_pageTitle;
		$data['error_msg'] = $this->session->flashdata('error_msg');
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['url_add'] = base_url('gallery/add');

		$this->load->library('pagination');

		$config['base_url'] = base_url('gallery/list');
		$config['total_rows'] = $this->gallery->getTotal();
		$config['per_page'] = $itemsPerPage;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$this->parser->parse('template/list', $data);
		//$this->load->view('pages/gallery/list',$data);
	}

	public function add(){

		$data['title'] =  $this->_genericPageTitle.$this->_pageTitle;
		$data['error_msg'] = null;
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['title_field'] = null;
		$data['content_field'] = null;
		$data['can_add_images'] = false;
		$data['post_url'] = base_url('gallery/add');
		$data['image_post_url'] = null;

		// get all categories
		$this->load->model('categorymodel', 'cat');
		$data['allOptions'] = $this->cat->getForGalleries();

		$post = $this->input->post(NULL, TRUE);

		if( $post ){
			// run the add script
			$data['title_field'] = $post['title'];
			$data['description'] = $post['description'];
			$data['type'] 		 = $post['type'];
			$error = $this->_validateError($post);

			if( count($error) > 0){
				$data['error_msg'] = 'Se han presentado los siguientes errores: <br /> <ul>';
				foreach( $error as $e){
					$data['error_msg'] .= '<li>'.$e.'</li>';
				}
				$data['error_msg'] .= '</ul>';
			}else{
				$this->load->model('gallerymodel', 'gallery');
				$id = $this->gallery->insert_entry($post);
				$this->session->set_flashdata('success_msg', 'Galería guardada con éxito');
				redirect('gallery/edit/'.$id);
			}
		}

		$this->parser->parse('pages/gallery/add',$data);
	}


	public function edit($id){
		$this->load->model('gallerymodel', 'gallery');

		$data['title'] =  $this->_genericPageTitle.$this->_pageTitle;
		$data['error_msg'] = null;
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['title_field'] = null;
		$data['description'] = null;
		$data['can_add_images'] = true;
		$data['post_url'] = base_url('gallery/edit/'.$id);
		$data['image_post_url'] = base_url('image/add/'.$id.'/gallery');

		$gallery = $this->gallery->getOne($id);
		$data['title_field'] = $gallery->title;
		$data['description'] = $gallery->description;
		$data['type'] 		= $gallery->type;
		// get all images
		$this->load->model('imgmodel', 'img');
		$data['images'] = $this->img->getForObject($gallery->id,'gallery');

		// get all categories
		$this->load->model('categorymodel', 'cat');
		$data['allOptions'] = $this->cat->getForGalleries();
		foreach ($data['allOptions'] as &$op) {
			foreach( $op['options'] as &$o ){
				if( $o['id'] === $data['type'] ){
					$o['selected'] = 'selected';
				}else{
					$o['selected'] = '';
				}
			}
		}

		$post = $this->input->post(NULL, TRUE);

		if( $post ){
			// run the add script
			$data['title_field'] = $post['title'];
			$data['description'] = $post['description'];
			$data['type'] 		 = $post['type'];
			$error = $this->_validateError($post);

			if( count($error) > 0){
				$data['error_msg'] = 'Se han presentado los siguientes errores: <br /> <ul>';
				foreach( $error as $e){
					$data['error_msg'] .= '<li>'.$e.'</li>';
				}
				$data['error_msg'] .= '</ul>';
			}else{
				$post['id'] = $id;
				$this->gallery->update_entry($post);
				$this->session->set_flashdata('success_msg', 'Galería guardada con éxito');
				redirect('gallery/edit/'.$id);
			}
		}

		$this->parser->parse('pages/gallery/add',$data);
	}

	protected function _validateError($post){
		$error = [];
		if( !isset( $post['title']) || !$post['title'] != ''){
			$error[] = 'El título es obligatorio';
		}

		if( !isset( $post['description']) || !$post['description'] != ''){
			$error[] = 'El contenido es obligatorio';
		}

		if( !isset( $post['type']) || !$post['type'] != ''){
			$error[] = 'La categoría es obligatoria';
		}

		return $error;
	}

	public function delete($id){
		$this->load->model('gallerymodel', 'gallery');
		$this->load->model('imgmodel', 'img');

		$this->img->deleteAll($id,'gallery');
		$this->gallery->delete($id);

		$this->session->set_flashdata('success_msg', 'Galería eliminada con éxito');
		redirect('gallery/list');
	}
}
