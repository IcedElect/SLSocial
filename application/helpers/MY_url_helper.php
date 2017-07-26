<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter URL Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/url_helper.html
 */

// ------------------------------------------------------------------------

// ------------------------------------------------------------------------

/**
 * Header Redirect
 *
 * Header redirect in two flavors
 * For very fine grained control over headers, you could use the Output
 * Library's set_header() function.
 *
 * @access	public
 * @param	string	the URL
 * @param	string	the method: location or redirect
 * @return	string
 */
function mygoto( $fp_sPath) {
    //$path = makePath($fp_sPath, $to_secure);
    $path = normalizeUrl($fp_sPath);
    if ( !headers_sent() ) {
        header("Location: " . $path);
    } else {
        print '<p align="center"><a href="'.$path.'">Click here to reload page</a></p>';
    }
    exit;
}

function makePath($fp_sPath, $to_secure = false) {
    if( $to_secure ) {
        $arr = parse_url( $to_secure );
    } else {
        $arr = parse_url( base_url() );
    }
    if( empty($arr['host'])) $arr['host'] = '';
    if( empty($arr['path'])) $arr['path'] = '';
    if( empty($arr['scheme'])) $arr['scheme'] = 'http';

    $tmp = parse_url( $fp_sPath );
    if( empty($tmp['path']) ) {
        $path = $arr['scheme'] . '://' . $arr['host'] . $_SERVER['PHP_SELF'];
    } else {
        //
        // »щем совпадающую часть пути в базовом имени сервера
        // и запрошенном пути.
        //
        if( $tmp['path'] == basename($tmp['path']) ) {
            $arr['path'] = dirname( $_SERVER['PHP_SELF'] ) . '/';
        }
        $t = preg_replace('|^'.$arr['path'].'|', '', $tmp['path'] );
        $path = $arr['scheme'] . '://' . $arr['host'] . $arr['path'] . $t;
    }
    if( !empty( $tmp['query'] ) ) {
         $path .= '?'.$tmp['query'];
    }
    return $path;
}

/**
 * ƒобавить в URL недостающие части: протокол (HTTP, HTTPS), домен, путь, которые берЄт из URL текущей страницы или из $baseUrl (если указан).
 *
 * @param string $url
 * @param string $baseUrl
 * @return string
 * @access public
 * @static
 */
function normalizeUrl($url, $baseUrl = null)
{
    if ($baseUrl !== null) {
        $aBaseParts = parse_url($baseUrl);
        $aCurrent = array (
            "scheme" => isset($aBaseParts['scheme']) ? $aBaseParts['scheme'] : "",
            "host"   => isset($aBaseParts['host']) ? $aBaseParts['host'] : "",
            "path"   => isset($aBaseParts['path']) ? _normalizePath($aBaseParts['path']) : "",
            "query"  => isset($aBaseParts['query']) ? $aBaseParts['query'] : "",
        );
    }
    else {
        $aCurrent = array (
            "scheme" => isset($_SERVER['HTTPS']) ? "https" : "http",
            "host"   => $_SERVER['HTTP_HOST'],
            "path"   => _normalizePath(preg_replace("`\\?.+$`", "", $_SERVER['REQUEST_URI'])),
            "query"  => $_SERVER['QUERY_STRING'],
        );
    }

    $aParts = parse_url($url);

    $normalizedUrl = "";

    if (empty($aParts['scheme']))
        $normalizedUrl .= $aCurrent['scheme'] . "://";
    else
        $normalizedUrl .= $aParts['scheme'] . "://";

    if (empty($aParts['host']))
        $normalizedUrl .= $aCurrent['host'];
    else {
        $normalizedUrl .= $aParts['host'];

        if (empty($aParts['port']))
            $normalizedUrl .= isset($aCurrent['port']) ? (":" . $aCurrent['port']) : '';
        else
            $normalizedUrl .= ":" . $aParts['port'];
    }

    if (empty($aParts['path']))
        $normalizedUrl .= $aCurrent['path'];
    elseif (!preg_match("`^/`", $aParts['path']))
        $normalizedUrl .= _normalizePath(preg_replace("`/[^/]+$`", "", $aCurrent['path']) . "/" . $aParts['path']);
    else
        $normalizedUrl .= _normalizePath($aParts['path']);

    if (empty($aParts['query']) && empty($aParts['path']))
        $normalizedUrl .= !empty($aCurrent['query']) ? "?" . $aCurrent['query'] : "";
    elseif (!empty($aParts['query']))
        $normalizedUrl .= !empty($aParts['query']) ? "?" . $aParts['query'] : "";

    if (empty($aParts['fragment']) && empty($aParts['path']))
        $normalizedUrl .= !empty($aCurrent['fragment']) ? "?" . $aCurrent['fragment'] : "";
    else
        $normalizedUrl .= !empty($aParts['fragment']) ? "#" . $aParts['fragment'] : "";

    return $normalizedUrl;
}

/**
* @param string $path
* @return string
* @access private
* @static
*/
function _normalizePath($path)
{
  if (empty($path)) return "/";

  $path = preg_replace("`^[^/]`", "/\\0", $path);
  //$path = preg_replace("`[^/]$`", "\\0/", $path);
  $path = preg_replace("`/+`", "/", $path);

  return $path;
}

function js_url(){
    return base_url().'js/';
}


function redirect_from_https(){
  if (is_https()){
    $current_url = current_url();
    $current_url = str_replace('index.php/','',$current_url);
    $current_url = str_replace('https://','http://',$current_url);
    mygoto($current_url);
    return true;
  }
  return false;
}

/* End of file MY_url_helper.php */
/* Location: ../application/helpers/MY_url_helper.php */