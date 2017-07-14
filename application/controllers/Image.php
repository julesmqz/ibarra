<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image extends Otter_Controller {

	public function add($objectid,$type){
		if( array_key_exists('file', $_FILES)){
			$imgName = uniqid().$_FILES['file']['name'];
			$newPath = str_replace('application/','',APPPATH).'out/imgs/'.$imgName;
			move_uploaded_file($_FILES['file']['tmp_name'], $newPath);

			if( file_exists($newPath) ){
				$this->load->model('imgmodel', 'img');
				$obj = array(
					'name' => $imgName,
					'main' => false,
					'objectid' => $objectid,
					'type' => $type
					);
				$this->img->insert_entry($obj);
			}
		}

		echo false;
	}

	public function delete($imageid,$objectid,$type){
		$this->load->model('imgmodel', 'img');

		$img = $this->img->getOne($imageid);

		if( $img ){
			$path = str_replace('application/','',APPPATH).'out/imgs/'.$img->name;
			unlink($path);
			$this->img->delete($imageid);
			$this->session->set_flashdata('success_msg', 'Imagen eliminada con éxito');
		}else{
			$this->session->set_flashdata('error_msg', 'Imagen no pudo ser eliminada');
		}
		
		redirect($type.'/edit/'.$objectid);
	}
}
