<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package        CodeIgniter
 * @author        ExpressionEngine Dev Team
 * @copyright    Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license        http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since        Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Validation Class
 *
 * @package        CodeIgniter
 * @subpackage    Libraries
 * @category    Validation
 * @author        ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/libraries/validation.html
 */
class MY_Validation extends CI_Validation
{

    var $class_object_call;


    function addClassObject($oClass)
    {
        $this->class_object_call = $oClass;
    }

    /**
     * Run the Validator
     *
     * This function does all the work.
     *
     * @access    public
     * @return    bool
     */
    function run()
    {

        // Do we even have any data to process?  Mm?
        if (count($_POST) == 0 OR count($this->_rules) == 0) {
            return FALSE;
        }

        // Load the language file containing error messages
        $this->CI->lang->load('validation', $this->CI->translate->get_current_lang());

        // Cycle through the rules and test for errors
        foreach ($this->_rules as $field => $rules) {
            //Explode out the rules!
            $ex = explode('|', $rules);

            // Is the field required?  If not, if the field is blank  we'll move on to the next test
            if (!in_array('required', $ex, TRUE)) {
                if ((!isset($_POST[$field]) OR $_POST[$field] == '') && !preg_match('/callback_/', $rules)) {
                    continue;
                }
            }
            /*
             * Are we dealing with an "isset" rule?
             *
             * Before going further, we'll see if one of the rules
             * is to check whether the item is set (typically this
             * applies only to checkboxes).  If so, we'll
             * test for it here since there's not reason to go
             * further
             */
            if (!isset($_POST[$field])) {
                if (in_array('isset', $ex, TRUE) OR in_array('required', $ex)) {
                    if (!isset($this->_error_messages['isset'])) {
                        $text = $this->CI->lang->line('isset');
                        $line = $this->CI->translate->t('validation_' . $rule, $text);
                        if (empty($line)) {
                            $line = 'The field was not set';
                        }
                    } else {
                        $line = $this->_error_messages['isset'];
                    }

                    // Build the error message
                    $mfield = (!isset($this->_fields[$field])) ? $field : $this->_fields[$field];
                    $message = sprintf($line, $mfield);

                    // Set the error variable.  Example: $this->username_error
                    $error = $field . '_error';
                    $this->$error = $this->_error_prefix . $message . $this->_error_suffix;
                    $this->_error_array[] = $message;
                }

                continue;
            }

            /*
             * Set the current field
             *
             * The various prepping functions need to know the
             * current field name so they can do this:
             *
             * $_POST[$this->_current_field] == 'bla bla';
             */
            $this->_current_field = $field;

            // Cycle through the rules!
            foreach ($ex As $rule) {
                // Is the rule a callback?
                $callback = FALSE;
                if (substr($rule, 0, 9) == 'callback_') {
                    $rule = substr($rule, 9);
                    $callback = TRUE;
                }

                // Strip the parameter (if exists) from the rule
                // Rules can contain a parameter: max_length[5]
                $param = FALSE;
                if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match)) {
                    $rule = $match[1];
                    $param = $match[2];
                }
                // Call the function that corresponds to the rule
                if ($callback === TRUE) {
                    if (!method_exists($this->CI, $rule)) {
                        if (!method_exists($this->class_object_call, $rule)) {
                            continue;
                        } else {
                            $oObject = $this->class_object_call;
                        }
                    } else {
                        $oObject = $this->CI;
                    }

                    $result = $oObject->$rule($_POST[$field], $param);

                    // If the field isn't required and we just processed a callback we'll move on...
                    if (!in_array('required', $ex, TRUE) AND $result !== FALSE) {
                        continue 2;
                    }

                } else {
                    if (!method_exists($this, $rule)) {
                        /*
                         * Run the native PHP function if called for
                         *
                         * If our own wrapper function doesn't exist we see
                         * if a native PHP function does. Users can use
                         * any native PHP function call that has one param.
                         */
                        if (function_exists($rule)) {
                            $_POST[$field] = $rule($_POST[$field]);
                            $this->$field = $_POST[$field];
                        }

                        continue;
                    }

                    $result = $this->$rule($_POST[$field], $param);
                }

                // Did the rule test negatively?  If so, grab the error.
                if ($result === FALSE) {
                    if (!isset($this->_error_messages[$rule])) {
                        $text = $this->CI->lang->line($rule);
                        $line = $this->CI->translate->t('validation_' . $rule, $text);
                        if (empty($line)) {
                            $line = 'Unable to access an error message corresponding to your field name.';
                        }
                    } else {
                        $line = $this->_error_messages[$rule];
                    }

                    // Build the error message
                    $mfield = (!isset($this->_fields[$field])) ? $field : $this->_fields[$field];
                    $mparam = (!isset($this->_fields[$param])) ? $param : $this->_fields[$param];
                    $message = sprintf($line, $mfield, $mparam);

                    // Set the error variable.  Example: $this->username_error
                    $error = $field . '_error';
                    $this->$error = $this->_error_prefix . $message . $this->_error_suffix;

                    // Add the error to the error array
                    $this->_error_array[$field] = $message;
                    continue 2;
                }
            }

        }

        $total_errors = count($this->_error_array);

        /*
         * Recompile the class variables
         *
         * If any prepping functions were called the $_POST data
         * might now be different then the corresponding class
         * variables so we'll set them anew.
         */
        if ($total_errors > 0) {
            $this->_safe_form_data = TRUE;
        }

        $this->set_fields();

        // Did we end up with any errors?
        if ($total_errors == 0) {
            return TRUE;
        }

        // Generate the error string
        foreach ($this->_error_array as $val) {
            $this->error_string .= $this->_error_prefix . $val . $this->_error_suffix . "\n";
        }

        return FALSE;
    }
    // --------------------------------------------------------------------


    /**
     * BB2 Used for names surname, given name, town, county etc.
     * Alpha-numeric with underscores and dashes
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function bb2_name($str)
    {
        return (!preg_match("/^([-a-z0-9\s\'])+$/i", $str)) ? FALSE : TRUE;
    }

    /**
     * BB2 Used for address validation
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function bb2_address($str)
    {
        return (!preg_match("/^([-a-z0-9_,\.\s\'])+$/i", $str)) ? FALSE : TRUE;
    }

    /**
     * BB2 Used for password validation
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function bb2_password($str)
    {
        return (!preg_match("/^([a-z0-9])+$/i", $str)) ? FALSE : TRUE;
    }

    /**
     * BB2 Used for question
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function bb2_answer($str)
    {
        return (!preg_match("/^([-a-z0-9,\s\!\?\'])+$/i", $str)) ? FALSE : TRUE;
    }

    /**
     * BB2 Used for question
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function bb2_postcode($str)
    {
        //$pattern1 = '^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$';
        //$pattern2 = '^([a-pr-uwyz0-9][a-hk-y0-9][aehmnprtvxy0-9]?[abehmnprvwxy0-9]? {1,2}[0-9][abd-hjln-uw-z]{2}|gir 0aa)$';
        //$str = trim(str_replace(' ', '', $str) );
        $str = trim($str);

        /*
        $pattern1 = '^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]{1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$';
        $pattern2 = '^([a-pr-uwyz0-9][a-hk-y0-9][aehmnprtvxy0-9]?[abehmnprvwxy0-9]{1,2}[0-9][abd-hjln-uw-z]{2}|gir 0aa)$';

		return ( !preg_match("/$pattern1/", $str) && !preg_match("/$pattern2/", $str) ) ? FALSE : TRUE;	}
        */

        $toCheck = $str;

        // Permitted letters depend upon their position in the postcode.
        $alpha1 = "[abcdefghijklmnoprstuwyz]";                          // Character 1
        $alpha2 = "[abcdefghklmnopqrstuvwxy]";                          // Character 2
        $alpha3 = "[abcdefghjkpmnrstuvwxy]";                            // Character 3
        $alpha4 = "[abehmnprvwxy]";                                     // Character 4
        $alpha5 = "[abdefghjlnpqrstuwxyz]";                             // Character 5

        // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA with a space
        $pcexp[0] = '/^(' . $alpha1 . '{1}' . $alpha2 . '{0,1}[0-9]{1,2})([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})$/';

        // Expression for postcodes: ANA NAA
        $pcexp[1] = '/^(' . $alpha1 . '{1}[0-9]{1}' . $alpha3 . '{1})([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})$/';

        // Expression for postcodes: AANA NAA
        $pcexp[2] = '/^(' . $alpha1 . '{1}' . $alpha2 . '{1}[0-9]{1}' . $alpha4 . ')([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})$/';

        // Exception for the special postcode GIR 0AA
        $pcexp[3] = '/^(gir)(0aa)$/';

        // Standard BFPO numbers
        $pcexp[4] = '/^(bfpo)([0-9]{1,4})$/';

        // c/o BFPO numbers
        $pcexp[5] = '/^(bfpo)(c\/o[0-9]{1,3})$/';

        // Overseas Territories
        $pcexp[6] = '/^([a-z]{4})(1zz)$/i';

        // Load up the string to check, converting into lowercase
        $postcode = strtolower($toCheck);

        // Assume we are not going to find a valid postcode
        $valid = false;

        // Check the string against the six types of postcodes
        foreach ($pcexp as $regexp) {

            if (preg_match($regexp, $postcode, $matches)) {
                // Load new postcode back into the form element
                $postcode = strtoupper($matches[1] . ' ' . $matches [3]);

                // Take account of the special BFPO c/o format
                $postcode = preg_replace('/C\/O/', 'c/o ', $postcode);

                // Remember that we have found that the code is valid and break from loop
                $valid = true;
                break;
            }
        }

        // Return with the reformatted valid postcode in uppercase if the postcode was
        // valid
        if ($valid) {
            return true;
        } else return false;
    }

    /**
     * Match one field to another
     *
     * @access    public
     * @param    string
     * @param    field
     * @return    bool
     */
    public function is_unique($str, $field)
    {
        list($table, $field) = explode('.', $field);
        $query = $this->CI->db->limit(1)->get_where($table, array($field => $str));

        return $query->num_rows() === 0;
    }


    function get_errors_array()
    {
        return $this->_error_array;
    }

    function add_field($field_name, $field_label, $field_rule)
    {
        if (isset($this->_rules[$field_name]) || isset($this->_fields[$field_name])) {
            return false;
        }

        $this->_rules[$field_name] = $field_rule;
        $this->_fields[$field_name] = $field_label;
    }


}
// END Validation Class

/* End of file Validation.php */
/* Location: ./system/libraries/Validation.php */