<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Uploader
{
    private $CI;
    private $config = array(
        'upload_path' => 'userfiles/uploads/',
        'max_size' => 1024, //1 mb
        'overwrite' => false, //will add number to filename
        'remove_spaces' => true, //replace spaces by _
    );
    private $do_resize_image = false;
    private $field_title = '';

    function __construct()
    {
        $this->CI =& get_instance();
        $this->upload = $this->CI->load->library('upload');
        $this->image_lib = $this->CI->load->library('image_lib');
    }

    function set_do_resize_image($resize)
    {
        $do_resize_image = $resize;
    }

    function set_path($path)
    {
        $this->config['upload_path'] = $path;
    }

    function set_max_size($size)
    {
        $this->config['max_size'] = $size;
    }

    function set_max_width($size)
    {
        $this->config['max_width'] = $size;
    }

    function set_max_height($size)
    {
        $this->config['max_height'] = $size;
    }

    function set_upload_config($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    function set_field_title($title)
    {
        $this->field_title = $title;
    }

    function run($field_name)
    {
        $_config = $this->config;
        if ($this->do_resize_image) {
            unset($_config['max_width']);
            unset($_config['max_height']);
        }
        $this->upload->set_field_title($this->field_title);
        if (!empty($this->config['create_folder'])){            $this->config['upload_path'] = $this->config['upload_path'];            if ( ! @is_dir($this->config['upload_path'])){                mkdir($this->config['upload_path'], 0754, true);
            }        }
        if ( ! @is_dir($this->config['upload_path'])){
            $this->set_error('upload_no_filepath');
            return FALSE;
        }
        $this->upload->initialize($this->config);
        $this->upload->do_upload($field_name);
        $file_data = $this->upload->data();
        $errors = $this->upload->error_msg;
        if (!empty($errors)) {
            return array(
                'error' => true,
                'data' => $errors,
            );
        } else {
            return array(
                'error' => false,
                'data' => $file_data,
            );
        }
    }

}
