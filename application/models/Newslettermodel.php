<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Newslettermodel extends CI_Model {
	
    public $mail = null;
    public $date_created = null;
    protected $_itemsPerPage = 100;
    protected $_tableName = "newsletter";

    public function get_entries( $number = 10)
    {
    		$this->db->select("{$this->_tableName}.id,{$this->_tableName}.mail as title,{$this->_tableName}.date_created");
			$this->db->from("{$this->_tableName}");
			$this->db->group_by("{$this->_tableName}.id");
			$this->db->order_by("{$this->_tableName}.date_created", "DESC");
			$this->db->limit($this->_itemsPerPage, $number);
			$query = $this->db->get();
            return $query->result();
    }

    public function insert_entry( $post )
    {
            $this->title   			= $post["title"]; // please read the below note
            $this->body  			= $post["body"];

            $this->db->insert("{$this->_tableName}", $this);

            return $this->db->insert_id();
    }

    public function update_entry( $post )
    {
            $this->title    		= $post["title"];
            $this->body  			= $post["body"];

            $this->db->update("{$this->_tableName}", $this, array("id" => $post["id"]));
    }

    public function getList( $page = 0){
    	$entries = $this->get_entries($this->_itemsPerPage * $page);
    	$res = [];
    	foreach ($entries as $entry) {
    		$res[] = ["id"=> $entry->id,"title" => $entry->title, "date_created"=> $entry->date_created];
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
}
