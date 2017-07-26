<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mailer
{

    private $CI;
    private $link_storage;
    private $menu_storage;
    private $menu_store_tree;
    private $base_url;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->config = $this->CI->config->item('mailer');
        $this->translate = &$this->CI->translate;
        $this->cache = &$this->CI->cache;
        $this->user = &$this->CI->user;
        require_once('./application/third_party/mail/PHPMailerAutoload.php');
    }

    function send_mail($from, $from_name, $to, $to_name, $subject, $message)
    {

        $mail = new PHPMailer;
        if ($this->config['use_smtp']) {
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $this->config['smtp']['host'];  // Specify main and backup server
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $this->config['smtp']['user'];                            // SMTP username
            $mail->Password = $this->config['smtp']['pass'];
            $mail->SMTPSecure = $this->config['smtp']['protocol'];
            $mail->Port = $this->config['smtp']['port'];
            $mail->SMTPDebug = $this->config['smtp']['debug'];
            $mail->Debugoutput = 'html';
        }

        $mail->CharSet = 'utf-8';
        $mail->SetFrom($from, $from_name);
        $mail->Subject = $subject;

        $mail->msgHTML($message);
        $mail->addAddress($to, $to_name);

        return $mail->send();
    }

}
