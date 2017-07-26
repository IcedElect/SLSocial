<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Date_helper
{

    private $CI;
    private $offset;
    private $user_timezone;
    private $format = 'H:i:s d/m/Y';
    private $date_format = 'd/m/Y';
    private $mysql_format = 'YYYY-MM-DD HH:MM:SS';

    function __construct()
    {
        $this->CI =& get_instance();
        $this->translate = &$this->CI->translate;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setTimezone($timezone)
    {
        $this->user_timezone = $timezone;
        $this->calcOffset();
    }

    private function calcOffset()
    {
        $this->offset = $this->getTimezoneOffset($this->user_timezone);
    }

    public function getTimezoneOffset($origin_tz, $remote_tz = 'UTC')
    {
        $origin_dtz = new DateTimeZone($origin_tz);
        $remote_dtz = new DateTimeZone($remote_tz);
        $origin_dt = new DateTime("now", $origin_dtz);
        $remote_dt = new DateTime("now", $remote_dtz);
        $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
        return $offset;
    }

    public function getDateFormatted($timestamp)
    {
        return date($this->format, $timestamp + $this->offset);
    }

    public function time_to_mysql($time)
    {
        return date($this->mysql_format, $time);
    }

}
