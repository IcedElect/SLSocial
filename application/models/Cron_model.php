<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class cron_model extends CI_Model
{


    function __construct()
    {
        parent::__construct();
        $this->table = 'cron';
    }

    function add($function_name)
    {
        $this->db->insert($this->table, array('name' => $function_name));
    }

    function update($function)
    {
        $this->db->where('name', $function->name);
        $res = $this->db->update($this->table, array(
            'last_run' => date('Y-m-d G:i:s'),
        ));

    }

    function getAllFunctions()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $query = $this->db->get();
        //dump($this->db->last_query());
        return $query->result();
    }

}