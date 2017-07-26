<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package        CodeIgniter
 * @author        ExpressionEngine Dev Team
 * @copyright    Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license        http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since        Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * File Uploading Class
 *
 * @package        CodeIgniter
 * @subpackage    Libraries
 * @category    Uploads
 * @author        ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/libraries/file_uploading.html
 */
class MY_Upload extends CI_Upload
{

    public $field_title = '';

    public function set_field_title($title)
    {
        $this->field_title = $title;
    }

    /**
     * Set an error message
     *
     * @param    string
     * @return    void
     */
    public function set_uerror($msg)
    {
        $CI =& get_instance();
        $CI->lang->load('upload');

        if (is_array($msg)) {
            foreach ($msg as $val) {
                $msg = ($CI->lang->line($val) == FALSE) ? $val : $CI->lang->line($val);
                $msg = $CI->translate->t('upload_' . $val, $msg);
                $msg = sprintf($msg, $this->field_title);
                $this->error_msg[] = $msg;
                log_message('error', $msg);
            }
        } else {
            $lang = ($CI->lang->line($msg) == FALSE) ? $msg : $CI->lang->line($msg);
            $msg = $CI->translate->t('upload_' . $msg, $lang);
            $msg = sprintf($msg, $this->field_title);
            $this->error_msg[] = $msg;
            log_message('error', $msg);
        }
    }

	public function set_filename($path, $filename)
	{
		if ($this->encrypt_name == TRUE)
		{
			mt_srand();
			$filename = md5(uniqid(mt_rand())).$this->file_ext;
		}

		if ( ! file_exists($path.$filename))
		{
			return $filename;
		}

		$filename = str_replace($this->file_ext, '', $filename);

		$new_filename = '';
		for ($i = 1; $i < 100; $i++)
		{
            $_filename = $filename . '_' . $i . $this->file_ext;
			if ( ! file_exists($path.$_filename))
			{
				$new_filename = $_filename;
				break;
			}
		}

		if ($new_filename == '')
		{
			$this->set_uerror('upload_bad_filename');
			return FALSE;
		}
		else
		{
			return $new_filename;
		}
	}

    // --------------------------------------------------------------------


}
