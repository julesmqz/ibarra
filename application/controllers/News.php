<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Otter_Controller {
	
	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		echo 'news';
	}

	public function list($offset_results=0){

		$this->load->model('newsmodel', 'news');
		$itemsPerPage = $this->news->getItemsPerPage();
		$page_number = $offset_results == 0 ? $offset_results : $offset_results/$itemsPerPage;
		$data['items'] = $this->news->getList($page_number);
		$data['list_title']  = 'Lista de Noticias';
		$data['dynamic_field']  = 'Cantidad de Imágenes';
		$data['title'] =  $this->_genericPageTitle.' Lista Noticias';
		$data['error_msg'] = $this->session->flashdata('error_msg');
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['url_add'] = base_url('news/add');

		$this->load->library('pagination');

		$config['base_url'] = base_url('news/list');
		$config['total_rows'] = $this->news->getTotal();
		$config['per_page'] = $itemsPerPage;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$this->parser->parse('template/list', $data);
		//$this->load->view('pages/news/list',$data);
	}

	public function add(){

		$data['title'] =  $this->_genericPageTitle.' Lista Noticias';
		$data['error_msg'] = null;
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['title_field'] = null;
		$data['content_field'] = null;
		$data['can_add_images'] = false;
		$data['post_url'] = base_url('news/add');
		$data['image_post_url'] = null;

		$post = $this->input->post(NULL, TRUE);

		if( $post ){
			// run the add script
			$data['title_field'] = $post['title'];
			$data['content_field'] = $post['body'];
			$error = [];
			if( !isset( $post['title']) || !$post['title'] != ''){
				$error[] = 'El título es obligatorio';
			}

			if( !isset( $post['body']) || !$post['body'] != ''){
				$error[] = 'El contenido es obligatorio';
			}

			if( count($error) > 0){
				$data['error_msg'] = 'Se han presentado los siguientes errores: <br /> <ul>';
				foreach( $error as $e){
					$data['error_msg'] .= '<li>'.$e.'</li>';
				}
				$data['error_msg'] .= '</ul>';
			}else{
				$this->load->model('newsmodel', 'news');
				$id = $this->news->insert_entry($post);
				$this->session->set_flashdata('success_msg', 'Noticia guardada con éxito');
				redirect('news/edit/'.$id);
			}
		}

		$this->parser->parse('pages/news/add',$data);
	}


	public function edit($id){
		$this->load->model('newsmodel', 'news');

		$data['title'] =  $this->_genericPageTitle.' Lista Noticias';
		$data['error_msg'] = null;
		$data['success_msg'] = $this->session->flashdata('success_msg');
		$data['title_field'] = null;
		$data['content_field'] = null;
		$data['can_add_images'] = true;
		$data['post_url'] = base_url('news/edit/'.$id);
		$data['image_post_url'] = base_url('image/add/'.$id.'/news');

		$news = $this->news->getOne($id);
		$data['title_field'] = $news->title;
		$data['content_field'] = $news->body;

		// get all images
		$this->load->model('imgmodel', 'img');
		$data['images'] = $this->img->getForObject($news->id,'news');

		$post = $this->input->post(NULL, TRUE);

		if( $post ){
			// run the add script
			$data['title_field'] = $post['title'];
			$data['content_field'] = $post['body'];
			$error = [];
			if( !isset( $post['title']) || !$post['title'] != ''){
				$error[] = 'El título es obligatorio';
			}

			if( !isset( $post['body']) || !$post['body'] != ''){
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
				$this->news->update_entry($post);
				$this->session->set_flashdata('success_msg', 'Noticia guardada con éxito');
				redirect('news/edit/'.$id);
			}
		}

		$this->parser->parse('pages/news/add',$data);
	}

	public function delete($id){
		$this->load->model('newsmodel', 'news');
		$this->load->model('imgmodel', 'img');

		$this->img->deleteAll($id,'news');
		$this->news->delete($id);

		$this->session->set_flashdata('success_msg', 'Noticia eliminada con éxito');
		redirect('news/list');
	}
}
