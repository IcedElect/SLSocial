<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Option
{
    var $CI;
    protected $variables = array();

    function __construct($no_db = false)
    {
        $this->CI =& get_instance();
        $this->CI->load->model('default_model','variable_model');
        $this->variable_model =& $this->CI->variable_model;
        $this->variable_model->setTable('options');
    }

    function get($name, $default_value = ''){
        if (empty($variables[$name])){
            $result = $this->variable_model->getDataByWhere(array('name' => $name));
            if (!empty($result[0])){
                $result = $result[0]->value;
            } else {
                if (!empty($default_value)){
                    $result = $default_value;
                } else {
                    $result = null;
                }
            }
            return $result;
        } else {
            return $variables[$name];
        }

    }

    function set($name, $value){
        $data = array(
            'name' => $name,
            'value' => $value,
        );
        $result = $this->variable_model->getDataByWhere(array('name' => $name));
        if (!empty($result[0]->id)){
            return $this->variable_model->save($data, 'edit', $result[0]->id);
        } else {
            return $this->variable_model->save($data, 'add');
        }
        $variables[$name] = $value;
    }

    function del($name){
        return $this->variable_model->delWhere(array('name' => $name));
    }

}

