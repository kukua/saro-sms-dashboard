<?php

class Testmodel extends CI_Model{
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		//$this-load-library('database');
	}
	
	function results($table,$attribute,$where)
	{
		$this->db->select($attribute);
		$this->db->from($table);
		
		if(strlen($where)==0)
		{
			
		}else{
		
		$this->db->where($where);	
		}
		
		return $this->db->get();
		
		
	}
	
	function insert($array = array())
	{
		
		$this->db->insert('users',$array);
		
	}
	
	
	
}

?>