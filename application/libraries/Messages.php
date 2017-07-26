<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Messages
{
    var $aMessages = array();
    //var $aMessagesStore = array();
    var $prefix = '';
    var $next_time_messages = array();
    private $allowed_types = array('error', 'success', 'status');
    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->aMessages = array();
        $this->translate = &$this->CI->translate;

        $this->load_stored_messages();

    }

    /*
     * param $key of message
     */
    function del_message($type, $key)
    {
        if ((!empty($type)) && (!empty($this->aMessages[$type][$this->prefix . $key])) && (isset($this->aMessages[$type]))) {
            unset($this->aMessages[$type][$this->prefix . $key]);
            return true;
        } else {
            return false;
        }
    }

    function get_message($type, $key)
    {
        if (isset($this->aMessages[$type][$this->prefix . $key])) {
            return $this->aMessages[$type][$this->prefix . $key];
        } else {
            return false;
        }

    }

    function get_all_messages()
    {
        return $this->aMessages;
    }

    function get_type_messages($type)
    {
        if (isset($this->aMessages[$type])) {
            return $this->aMessages[$type];
        } else {
            return false;
        }
    }

    function add($type, $key, $value)
    {
        return $this->add_message($type, $key, $value);
    }

    function add_message($type, $key, $value, $translate = true)
    {
        if (in_array($type, $this->allowed_types)) {
            if ($translate) {
                $message = $this->translate->t('mess_' . $type . '_' . $this->prefix . $key, $value);
            } else {
                $message = $value;
            }
            $this->aMessages[$type][$this->prefix . $key] = $message;
            return true;
        } else {
            return false;
        }
    }

    function add_tmessages_list($type, $array, $prefix = '')
    {
        if (!empty($array) && in_array($type, $this->allowed_types)) {
            $key = 0;
            foreach ($array as $message) {
                $key = $key + 1;
                $this->aMessages[$type][$prefix . $key] = $message;
            }
        }
    }

    function add_error($key, $value, $translate = true)
    {
        return $this->add_message('error', $key, $value, $translate);
    }

    function add_terror($key, $value)
    {
        return $this->add_message('error', $key, $value, false);
    }

    function add_success($key, $value)
    {
        return $this->add_message('success', $key, $value);
    }

    function add_status($key, $value)
    {
        return $this->add_message('status', $key, $value);
    }


    function assign()
    {
        $this->CI->session->userdata('next_time_message', $this->next_time_messages);
        $this->CI->my_smarty->assign('aMessages', $this->aMessages);
    }

    /*
     function add_list_to_store($messages_list){
         if (empty($this->aMessagesStore)){
             $this->aMessagesStore = array();
         }
         if (!empty($messages_list)){
             foreach ($messages_list as $key => $message){
                 $this->aMessagesStore[$this->prefix . $key] = $message;
             }
         }
     }

     function add_message_from_store($type, $key){
         if (!empty($this->aMessagesStore[$this->prefix . $key])){
             return $this->add_message($type, $this->prefix . $key, $this->aMessagesStore[$this->prefix . $key]);
         } else {
             return false;
         }
     }
     */
    function set_key_prefix($prefix = '')
    {
        $this->prefix = $prefix;
    }

    function add_message_next_time($type, $key, $value, $translate = true)
    {
        $this->next_time_messages = $this->CI->session->userdata('next_time_message');
        if ($translate) {
            $message = $this->translate->t('mess_' . $type . '_' . $this->prefix . $key, $value);
        } else {
            $message = $value;
        }
        $this->next_time_messages[$type][$this->prefix . $key] = $message;
        $this->CI->session->userdata('next_time_message', $this->next_time_messages);
    }

    function load_stored_messages()
    {
        $this->aMessages = $this->CI->session->userdata('next_time_message');
        $this->CI->session->userdata('next_time_message', array());
    }

}

