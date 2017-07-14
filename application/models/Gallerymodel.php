<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Gallerymodel extends CI_Model {
	
    public $title = null;
    public $description = null;
    public $type = null;
    public $date_created = null;
    protected $_itemsPerPage = 10;
    protected $_tableName = "gallery";

    public function get_entries( $number = 10)
    {
    		$this->db->select("{$this->_tableName}.id,{$this->_tableName}.title,{$this->_tableName}.date_created,category.title as ctitle");
			$this->db->from("{$this->_tableName}");
			$this->db->join("category", "category.id = {$this->_tableName}.type","left");
			$this->db->group_by("{$this->_tableName}.id");
			$this->db->order_by("{$this->_tableName}.title", "ASC");
			$this->db->limit($this->_itemsPerPage, $number);
			$query = $this->db->get();
            return $query->result();
    }

    public function insert_entry( $post )
    {
            $this->title   			= $post["title"]; // please read the below note
            $this->description      = $post["description"];
            $this->type  	        = $post["type"];

            $this->db->insert("{$this->_tableName}", $this);

            return $this->db->insert_id();
    }

    public function update_entry( $post )
    {
            $this->title    		= $post["title"];
            $this->description      = $post["description"];
            $this->type             = $post["type"];

            $this->db->update("{$this->_tableName}", $this, array("id" => $post["id"]));
    }

    public function getList( $page = 0){
    	$entries = $this->get_entries($this->_itemsPerPage * $page);
    	$res = [];
    	foreach ($entries as $entry) {
    		$res[] = ["id"=> $entry->id,"title" => $entry->title, "date_created"=> $entry->date_created,"dynamic_field_value" => $entry->ctitle,'url_edit' => base_url('gallery/edit/'.$entry->id),'url_delete' => base_url('gallery/delete/'.$entry->id)];
    	}

    	return $res;
    	
    }

    public function getTotal(){
    	return $this->db->count_all_results("{$this->_tableName}");
    }

    public function getItemsPerPage(){
    	return $this->_itemsPerPage;
    }

    public function getOne($id){
    	$query = $this->db->get_where("{$this->_tableName}", array('id' => $id),1);
    	return $query->row();
    }

    public function delete($id){
        $this->db->where('id', $id);
        $this->db->delete($this->_tableName);
    }
}
