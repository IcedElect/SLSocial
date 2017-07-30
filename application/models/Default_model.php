<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Default_model extends CI_Model
{


    var $table;
    var $translate_table = 'translate';

    /**
     * Constructor
     *
     * @return    void
     */
    function __construct()
    {
        parent::__construct();
        $this->table = "";
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    function getDataCountbyFilter()
    {
        $sql = "SELECT
                   count(*) as count
            FROM " . $this->db->dbprefix.$this->table;
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result->count;
    }

    function getDataByWhere($fp_aFilter, $select = '*')
    {
        $this->db->select($select);
        $this->db->from($this->table);
        $this->db->where($fp_aFilter);
        //dump($this->db->last_query(),0);
        $query = $this->db->get();
        //dump($this->db->last_query());
        return $query->result();
    }

    function getDataByOrWhere($fp_aFilter)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->or_where($fp_aFilter);
        //dump($this->db->last_query(),0);
        $query = $this->db->get();
        //dump($this->db->last_query());
        return $query->result();
    }

    function getDatabyFilter($fp_aFilter, $fp_sidx, $fp_sord)
    {
        $this->db->select('*');
        if (!empty($fp_aFilter['filters']->rules)) {
            foreach ($fp_aFilter['filters']->rules as $key => $value) {
                $this->set_filter_function($this->db, $value->op, $value->field, $value->data, $fp_aFilter['filters']->groupOp);
            }
        }
        $this->db->order_by($fp_sidx, $fp_sord);
        if (!isset($fp_aFilter['limit'])) {
            $query = $this->db->get_where($this->table, $fp_aFilter['fields']);
        } else {
            $query = $this->db->get_where($this->table, $fp_aFilter['fields'], $fp_aFilter['limit'], $fp_aFilter['offset']);
        }
        //dump($this->db->last_query());
        return $query->result();
    }

    function check_duplicate($fp_params)
    {
        foreach ($fp_params as $key => $value) {
            if (!empty($value)) {
                if ($key == 'id') {
                    $key = $key . ' !=';
                }
                $this->db->where($key, $value);
            }
        }
        $this->db->from($this->table);
        $this->db->select('*');
        $res = $this->db->count_all_results();
        return $res ? true : false;
    }

    function getDataById($fp_nId, $table = false)
    {
        if (!$fp_nId) return false;
        if (!$table) {
            $table = $this->table;
        }
        $query = $this->db->get_where($table, array('id' => $fp_nId));
        $data = $query->row_array();
        //dump($this->db->last_query(),0);
        return $data;
    }

    function getDataByIdList($fp_rowId)
    {
        if (!$fp_rowId) return false;
        foreach ((array)$fp_rowId as $k => $id) {
            $this->db->or_where('id', $id);
        }
        $query = $this->db->get($this->table);
        $result = $query->result();
        return $result;
    }

    function getAllData()
    {
        $this->db->select('*')->from($this->table);
        $query = $this->db->get();
        return $query->result();
    }

    function save($aData, $subact = 'edit', $fp_nId = '')
    {
        if ($subact == 'add') {
            $res = $this->db->insert($this->table, $aData);
            $res = $this->db->insert_id();
        } else {
            if (!$fp_nId) return false;
            $res = $this->db->update($this->table, $aData, "id = '" . $fp_nId . "'");
        }
        if (!$res) {
            return false;
        } else {
            if ($subact == 'add') {
                return $res;
            } else {
                return $fp_nId;
            }
        }
    }

    function saveWhere($where, $aData)
    {
        if (!$where) return false;
        $res = $this->db->update($this->table, $aData, $where);
        if (!$res) {
            return false;
        } else {
            return $res;
        }
    }

    function isValidLogin($fp_sLogin, $fp_nId = '')
    {
        if (!$fp_sLogin) return false;

        $this->db->from($this->table);
        $this->db->where('login', $fp_sLogin);
        if ($fp_nId) {
            $this->db->where('id !=', $fp_nId);
        }
        $res = $this->db->count_all_results();
        //dump($this->db->last_query());
        return $res ? false : true;
    }

    function isValidId($fp_nId)
    {
        if (!$fp_nId) return false;
        $this->db->from($this->table);
        $this->db->where('id', $fp_nId);
        $res = $this->db->count_all_results();
        return $res ? true : false;
    }

    /**
     * Delete data by id
     *
     * @param    array $fp_rowId = array('Key_Row' => 'ID')
     * @return    void
     */
    function del($fp_rowId)
    {
        foreach ((array)$fp_rowId as $k => $id) {
            $this->db->or_where('id', $id);
        }
        $this->db->from($this->table);
        $this->db->delete($this->table);
        //dump($this->db->last_query());
        return true;
    }

    function delWhere($where)
    {
        if (empty($where)) return false;
        $this->db->where($where);
        $this->db->from($this->table);
        $result = $this->db->delete($this->table);
        //dump($this->db->last_query());
        return $result;
    }


    /**
     * update multiple rows
     *
     * @param $data = array()
     * @return void
     */
    function updateRow($data)
    {
        $this->db->where($data['where']);
        $res = $this->db->update($this->table, $data['update']);
        return $res;
    }


    /**
     * update multiple rows
     *
     * @param $data = array()
     * @param $where = array()
     * @return void
     */
    function update($data, $where)
    {
        $this->db->where($where);
        $res = $this->db->update($this->table, $data);
        return $res;
    }

    /**
     *
     * get all data from table
     * order by function param $order
     * @param $order = string;
     */
    function get_ordered_all_data($order)
    {
        $this->db->select('*')->from($this->table)->order_by($order);
        $query = $this->db->get();
        return $query->result();

    }

    function get_translate_ordered_all_data($field_name, $prefix, $lang)
    {
        $this->db->select('d.*,t.tr_code,t.tr_text')->from($this->table . ' d')->order_by('t.tr_text');
        $this->db->join($this->translate_table . ' t', 't.tr_code = concat("' . $prefix . '", d.' . $field_name . ') AND t.lang_id = \'' . $lang . '\'', 'left');
        $query = $this->db->get();
        return $query->result();
    }

    function get_tree($parent_id_column = 'parent_id', $order = '', $parent_id = null)
    {
        $this->db->select('*')->from($this->table)->where($parent_id_column, $parent_id);

        if (!empty($order)) {
            $this->db->order_by($order);
        }

        $query = $this->db->get();
        $result = $query->result();
        $branch = array();
        if (!empty($result)) {
            foreach ($result as $key => $row) {
                $branch[$key] = $row;
                $branch[$key]->children = $this->get_tree($parent_id_column, $order, $row->id);
            }
        }
        return $branch;
    }

    function get_tree_child($parent_id_column, $order, $parent_id)
    {
        $this->db->select('*')->from($this->table)->where($parent_id_column . ' IS NULL', NULL, false);
        $query = $this->db->get();
        $result = $query->result();
    }

    function set_filter_function(&$cur_db, $op, $field, $data, $groupOp = 'AND')
    {
        $where = 'where';
        $like = 'like';
        $not_like = 'not_like';
        $in = 'where_in';
        $not_in = 'where_not_in';
        if ($groupOp != 'and') {
            $where = 'or_where';
            $like = 'or_like';
            $not_like = 'or_not_like';
            $in = 'or_where_in';
            $not_in = 'or_where_not_in';
        }
        switch ($op) {
            case 'eq':
                $cur_db->$where($field, $data);
                break;
            case 'lt':
                $cur_db->$where($field . ' <', $data);
                break;
            case 'le':
                $cur_db->$where($field . ' <=', $data);
                break;
            case 'gt':
                $cur_db->$where($field . ' >', $data);
                break;
            case 'ge':
                $cur_db->$where($field . ' >=', $data);
                break;
            case 'ne':
                $cur_db->$where($field . ' !=', $data);
                break;
            case 'bw':
                $cur_db->$like($field, $data, 'after');
                break;
            case 'bn':
                $cur_db->$not_like($field, $data, 'after');
                break;
            case 'ew':
                $cur_db->$like($field, $data, 'before');
                break;
            case 'en':
                $cur_db->$not_like($field, $data, 'before');
                break;
            case 'cn':
                $cur_db->$like($field, $data, 'both');
                break;
            case 'nc':
                $cur_db->$not_like($field, $data, 'both');
                break;
            case 'nu':
                $cur_db->$where($field, NULL);
                break;
            case 'in':
                $cur_db->$in($field, $data);
                break;
            case 'ni':
                $cur_db->$not_in($field, $data);
                break;
        }
    }

}