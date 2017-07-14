<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class CategoryModel extends CI_Model {
	
    public $title = null;
    public $date_created = null;
    protected $_itemsPerPage = 10;
    protected $_tableName = "category";

    public function get_entries( $number = 10)
    {
    		$this->db->select("{$this->_tableName}.id,{$this->_tableName}.title,{$this->_tableName}.date_created,count(c1.id) as totalCategories");
			$this->db->from("{$this->_tableName}");
			$this->db->join("category as c1", "c1.parentid = {$this->_tableName}.id","left");
			$this->db->group_by("{$this->_tableName}.id");
			$this->db->order_by("{$this->_tableName}.title", "ASC");
			$this->db->limit($this->_itemsPerPage, $number);
            $this->db->where('category.parentid',null);
			$query = $this->db->get();
            return $query->result();
    }

    public function insert_entry( $post )
    {
            $this->title   			= $post["title"]; // please read the below note
            $this->parentid  		= $post["parentid"];

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
    		$res[] = ["id"=> $entry->id,"title" => $entry->title, "date_created"=> $entry->date_created,"dynamic_field_value" => $entry->totalCategories,'url_edit' => base_url('category/edit/'.$entry->id),'url_delete' => base_url('category/delete/'.$entry->id)];
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

    public function getAll($omitId=null){
        $this->db->select("{$this->_tableName}.id,{$this->_tableName}.title");
        $this->db->from("{$this->_tableName}");
        $this->db->order_by("{$this->_tableName}.title", "ASC");
        $this->db->where('parentid',null);
        if( $omitId ){
            $this->db->where("{$this->_tableName}.id !=",$omitId);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function getSubcategories($parentid){
        $this->db->select("{$this->_tableName}.id,{$this->_tableName}.title,{$this->_tableName}.date_created");
        $this->db->from("{$this->_tableName}");
        $this->db->order_by("{$this->_tableName}.title", "ASC");
        $this->db->where('parentid',$parentid);
        $query = $this->db->get();
        return $query->result();
    }

    public function getForGalleries(){
        $this->db->select("{$this->_tableName}.id,{$this->_tableName}.title,c1.id as cid,c1.title as ctitle");
        $this->db->from("{$this->_tableName}");
        $this->db->join("category as c1", "c1.parentid = {$this->_tableName}.id");
        $this->db->order_by("{$this->_tableName}.title", "ASC");
        $this->db->where('category.parentid',null);
        $query = $this->db->get();
        $r = $query->result();
        $options = [];

        foreach ($r as $rr) {
            if( isset($options[$rr->id]) && !is_array( $options[$rr->id])){
                $options[$rr->id] = array();
            }

            $options[$rr->id]['optgroup'] = $rr->title;
            $options[$rr->id]['options'][] = array('id' => $rr->cid,'title' => $rr->ctitle);            
        }

        return $options;
    }

    public function delete($id){
        $this->db->where('parentid', $id);
        $this->db->delete($this->_tableName);

        $this->db->where('id', $id);
        $this->db->delete($this->_tableName);
    }
}
