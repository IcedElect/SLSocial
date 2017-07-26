<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class permissions_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = 'permissions';
        $this->entity_table = 'permissions_entity';
    }

    function save_entity($aData)
    {
        $this->db->insert($this->entity_table, $aData);
        return $this->db->insert_id();
    }

    function getForGroup($group_id)
    {
        $this->db->select('*');
        $this->db->from($this->entity_table . ' pe');
        $this->db->join($this->table . ' p', 'pe.id = p.perm_id and p.group_id = "' . $group_id . '"', 'left');

        $query = $this->db->get();

        return $query->result();
    }

    function getAllEntity()
    {
        $this->db->select('*');
        $this->db->from($this->entity_table . ' pe');
//        $this->db->join($this->table . ' p', 'pe.id = p.perm_id', 'left');
        $this->db->order_by('pe.module, pe.action');
        $query = $this->db->get();
        return $query->result();
    }

    function getAllPermission()
    {
        $this->db->select('*');
        $this->db->from($this->table);
//        $this->db->join($this->table . ' p', 'pe.id = p.perm_id', 'left');
//        $this->db->order_by('perm_id, group_id');
        $query = $this->db->get();
        return $query->result();
    }

    function del_entity()
    {

    }

    function del($fp_rowId)
    {
        foreach ((array)$fp_rowId as $k => $id) {
            $this->db->or_where('id', $id);
        }
        $this->db->delete($this->table);
        return true;
    }

    function save_permission($perm_id, $group_id, $value)
    {
        $this->db->select('id');
        $this->db->from($this->table);
        $this->db->where('perm_id', $perm_id);
        $this->db->where('group_id', $group_id);
        $query = $this->db->get();
        $perm = $query->row();
        $aData = array(
            'perm_id' => $perm_id,
            'group_id' => $group_id,
            'value' => $value
        );
        if (empty($perm)) {
            $this->db->insert($this->table, $aData);
            $res = $this->db->insert_id();
        } else {
            $this->db->where('id', $perm->id);
            $res = $this->db->update($this->table, $aData);
        }
        return $res;
    }

}