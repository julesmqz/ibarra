<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Imgmodel extends CI_Model {
	
    public $name = null;
    public $main = null;
    public $objectid = null;
    public $type = null;
    protected $_itemsPerPage = 100;
    protected $_tableName = "image";

    public function get_entries( $number = 10)
    {
    		$this->db->select("{$this->_tableName}.id,{$this->_tableName}.title,{$this->_tableName}.date_created,count(image.id) as totalImages");
			$this->db->from("{$this->_tableName}");
			$this->db->join("image", "image.objectid = {$this->_tableName}.id","left");
			$this->db->group_by("{$this->_tableName}.id");
			$this->db->order_by("{$this->_tableName}.date_created", "DESC");
			$this->db->limit($this->_itemsPerPage, $number);
			$query = $this->db->get();
            return $query->result();
    }

    public function insert_entry( $post )
    {
            $this->name   			= $post["name"]; // please read the below note
            $this->main             = $post["main"];
            $this->objectid         = $post["objectid"];
            $this->type  		    = $post["type"];

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
    		$res[] = ["id"=> $entry->id,"title" => $entry->title, "date_created"=> $entry->date_created,"dynamic_field_value" => $entry->totalImages,'url_edit' => base_url('news/edit/'.$entry->id),'url_delete' => base_url('news/delete/'.$entry->id)];
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

    public function getForObject($objectid,$type){
        $url = base_url();
        $this->db->select("{$this->_tableName}.id,CONCAT('$url/out/imgs/',{$this->_tableName}.name) as src,{$this->_tableName}.name as title,CONCAT('$url/image/delete/',{$this->_tableName}.id,'/$objectid/$type') as url_delete");
        $this->db->from("{$this->_tableName}");
        $this->db->order_by("{$this->_tableName}.date_created", "DESC");
        $this->db->where('objectid',$objectid);
        $this->db->where('type',$type);
        $query = $this->db->get();
        return $query->result();
    }

    public function delete($id){
        $this->db->where('id', $id);
        $this->db->delete($this->_tableName);
    }

    public function deleteAll($id,$type){
        $this->db->where('objectid', $id);
        $this->db->where('type', $type);
        $this->db->delete($this->_tableName);
    }
}
