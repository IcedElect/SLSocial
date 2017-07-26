<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * CodeIgniter Debug Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Kuzmin Igor
 * @link
 */

// ------------------------------------------------------------------------

function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    $config =& get_config();

    // Check if the error occurs when '@' operator was used
    if(error_reporting() == 0) return;

    $errorReport = '';
    $errorReportDebug = "<!--\"'--></textarea></input></select></script></noscript><fieldset style='background-color:#fff;padding:10px;font-size:12px;font-family:arial !important;'>";
    switch ($errno) {
        case __CFG_ERROR_FATAL:
            $errorReportDebug .= "<legend style='color:red'><b>FATAL ERROR</b> [errno: $errno]</legend>\n<b>$errstr</b>\nin line ".$errline." of file ".$errfile.", PHP ".PHP_VERSION." (".PHP_OS.")\n<br><br>Aborting...\n";
            $errorReport .= "<!--\"'--></textarea></input></select></script></noscript><fieldset style='padding:10px;font-size:12px;font-family:arial !important;'><legend style='color:red'><b>FATAL ERROR</b></legend>\n<b>$errstr</b>\n<br>Aborting...\n</fieldset>";
        break;
        case __CFG_ERROR_STRICT:
        case __CFG_ERROR_WARNING:
            $errorReportDebug .= "
                <legend style=\"color:blue\"><b>ERROR</b> [errno: $errno]</legend>
                <b>$errstr</b>
                <br>in line ".$errline." of file ".$errfile.", PHP ".PHP_VERSION." (".PHP_OS.")";
        break;
        case __CFG_ERROR_NOTICE:
            $errorReportDebug .= "
                <legend><b>WARNING</b> [errno: $errno]</legend>
                <b>$errstr</b>
                <br>in line ".$errline." of file ".$errfile.", PHP ".PHP_VERSION." (".PHP_OS.")";
        break;
        default:
            $errorReportDebug .= "
                <legend><b>Unkown error type</b> [errno: $errno]</legend>
                <b>$errstr</b>
                <br>in line ".$errline." of file ".$errfile.", PHP ".PHP_VERSION." (".PHP_OS.")";
        break;
    }

    $errorReportDebug .= "<br><br>\n<b>Request URL:</b><br>\n" . (((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on')? 'https://': 'http://') . $config['host'] . $_SERVER['REQUEST_URI']) . "<br>\n";

    if(!empty($_SERVER['HTTP_REFERER'])) {
        $errorReportDebug .= "<br>\n<b>HTTP referer:</b><br>\n" . $_SERVER['HTTP_REFERER'] . "<br>\n";
    }


    if (function_exists('debug_backtrace')) {
        $backtrace = debug_backtrace();
        $call_stack = "";
        foreach ($backtrace as $call) {
            if ( ($call['function'] == 'customerrorhandler') || ($call['function'] == 'trigger_error') ) continue;

            if (isset($call['class'])) {
                if (isset($call['type'])) $call_stack .= "<b style='color: blue'>$call[class]$call[type]</b>";
                else $call_stack .= $call['class'];
            }

            $call_stack .= "<b style='color: blue'>$call[function]</b><b style='color: blue;'>(</b>";
            /*
            if (isset($call['args'])) {
                $args = array ();
                foreach ($call['args'] as $arg) {
                    ob_start();
                    var_export($arg);
                    $arg_dump = trim(ob_get_contents());
                    ob_end_clean();
                    switch (gettype($arg)) {
                        case 'boolean':
                            $arg = $arg ? 'true' : 'false';
                            break;
                        case 'null':
                            $arg = 'null';
                            break;
                        case 'integer':
                        case 'double':
                            break;
                        case 'string':
                            $arg = '"'.$arg.'"';
                            break;
                        case 'array':
                            $arg = $arg_dump;
                            break;
                        case 'object':
                            $arg = $arg_dump;
                            break;
                    }
                    $args[] = $arg;
                }
                $call_stack .= implode("<b style='color: blue'>, </b>", $args);
            }
            */
            $call_stack .= "<b style='color: blue'>);</b><br>\n";
            if (isset($call['file'])) $call_stack .= "     <span style='color:green'>[$call[file]:$call[line]]</span><br>\n";
        }
        $errorReportDebug .= "<br>\n<hr><b><u>Call Stack:</u></b><br>\n$call_stack";
    }

    //if (strlen(trim(strip_tags($errorReportDebug)))) {
		$errorReportDebug .= "<hr>\n";
        if (isset($_SERVER["PWD"])) $errorReportDebug .= "<br>\n\n<b>Local path:</b> $_SERVER[PWD]";
        if (isset($_SERVER["SHELL"])) $errorReportDebug .= "<br>\n<b>Shell:</b> $_SERVER[SHELL]";
        if (isset($_SERVER["argv"])) $errorReportDebug .= "<br>\n<b>Args:</b> " . implode(' ', $_SERVER["argv"]);
        $errorReportDebug .= "<br>\n\n<b>Hostname:</b> " . $config['host'] . "<br>\n<b>Timestamp:</b> " . date('Y-m-d h:i:s');
        if (!empty($_POST)) {
            ob_start();
            print_r($_POST);
            $errorReportDebug .= "<br>\n\nHTTP POST:\n<xmp>" . ob_get_contents() . "</xmp>";
            ob_end_clean();
        }
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $errorReportDebug .= "<br>\n\n<b>User-agent</b>: " . $_SERVER['HTTP_USER_AGENT'];
        }

        if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            $ip_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif ( isset($_SERVER['HTTP_CLIENT_IP']) ) {
            $ip_addr =  $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip_addr = $_SERVER['REMOTE_ADDR'];
        }


        if ( !empty($ip_addr) ) {
            $errorReportDebug .= "<br>\n<b>Remote IP:</b> " . $ip_addr ."<br>\n";
        }

        $errorReportDebug .= "<br>\n\n<b>PHP session id:</b>\n" . @session_id() . "</xmp>";
        if (!empty($_SESSION)) {
            ob_start();
            print_r($_SESSION);
            $errorReportDebug .= "<br>\n\n<b>PHP session contents:</b>\n<xmp>" . ob_get_contents() . "</xmp>";
            ob_end_clean();
        }
    //}
    $errorReportDebug .= "</fieldset>";

    if ($config['error_notice_mode'] != 'debug' && strlen(trim($errorReportDebug))) {
        $subj = date("Y-m-d-H-i-s");
        $headers = "MIME-Version: 1.0\r\nFrom: ".$_SERVER['HTTP_HOST']."\r\nContent-type: text/html; charset=iso-8859-1\n";
        //@mail($config['error_report_email'], $subj, $errorReportDebug, $headers);
        error_log($errorReportDebug, 3, LOGPATH.$subj.'.html');
    }

    if ($config['error_notice_mode'] == "debug") {
        echo $errorReportDebug;
    }

    if ($config['error_notice_mode'] != "debug" && $errno == __CFG_ERROR_FATAL) {
        echo $errorReport;
    }

    if ($errno == __CFG_ERROR_FATAL) {
        exit;
    }
}

function dump($var, $is_die = true ,$sent_to_email = false, $show_dump = false) {
    if (function_exists('debug_backtrace')) {
        $Tmp1 = debug_backtrace();
    } else {
        $Tmp1 = array(
            'file' => 'UNKNOWN FILE',
            'line' => 'UNKNOWN LINE',
        );
    }
    $header = "<div style=\"float:left;\"><FIELDSET STYLE=\"background-color:#fff;font:normal 12px helvetica,arial; margin:10px;\"><LEGEND STYLE=\"font:bold 14px helvetica,arial\">Dump - ".$Tmp1[0]['file']." : ".$Tmp1[0]['line']."</LEGEND><PRE>\n";
    $content = $var;
    $footer = "</PRE></FIELDSET>\n\n</div>";
    /*
    $config =& get_config();
    if($config['error_notice_mode'] == 'live' && !$show_dump){
        $sent_to_email = true;
    }
    */
    if($sent_to_email ){
        ob_start();
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers  = 'From:'.$_SERVER['HTTP_HOST']. "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$subj = "Dump - ".$Tmp1[0]['file']." : ".$Tmp1[0]['line'];
        print_r($content);
		$_content = ob_get_contents();
        ob_clean();
		$message = $header.$_content.$footer;
        @mail($config['error_report_email'], $subj, $message, $headers);
        flush();
    } else{
        echo $header;
        print_r($content);
        echo $footer;
    }

    if ($is_die){
        die();
    }
}

function dump_simple($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

function dumpx($var) {
    dump($var);
    exit;
}

function dump_hidden($var) {
    echo "<!--";
    dump($var);
    echo "//-->";
}


/* End of file date_helper.php */
/* Location: ./system/helpers/date_helper.php */