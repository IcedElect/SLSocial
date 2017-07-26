<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class language_model extends CI_Model
{

    /**
     * Constructor
     *
     * @return    void
     */
    function __construct()
    {
        parent::__construct();
        $this->table = $this->config->item('languages', 'tables');
    }

    function getAllData()
    {
        $sql = "SELECT *
	            FROM " . $this->table;
        $query = $this->db->query($sql);
        //dump($this->db->last_query());
        return $query->result();
    }

    /**
     * Function admin user data
     *
     * @param    array
     * @return    bool
     */
    function getDataById($fp_nId)
    {
        if (!$fp_nId) return false;
        $query = $this->db->get_where($this->table, array('id' => $fp_nId));
        $page = $query->row_array();
        return $page;
    }

}