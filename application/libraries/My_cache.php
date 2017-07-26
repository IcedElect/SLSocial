<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class My_cache
{
    private $cache;
    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->driver('cache', array('adapter' => $this->CI->config->item('cache_adapter'), 'backup' => 'file'));
        $this->cache = &$this->CI->cache;
    }

    /*
    * load cached value or run code and save to cache
    * time - seconds to store cache
    *
    */
    function load_cache_val($var_name, $time, $function, $param = '')
    {
        if (!$value = $this->cache->get($var_name)) {
            $value = call_user_func($function, $param);
            // Save into the cache for time
            $this->cache->save($var_name, $value, $time);
        }
        return $value;
    }

    function save($var_name, $value, $time = 300)
    {
        return $this->cache->save($var_name, $value, $time);
    }

    function get($var_name)
    {
        return $this->cache->get($var_name);
    }

    function delete($key)
    {
        $var = $this->get($key);
        if (!empty($var)) {
            return $this->cache->delete($key);
        } else {
            return true;
        }

    }
}

