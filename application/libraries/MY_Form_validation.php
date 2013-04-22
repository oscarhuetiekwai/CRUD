<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Form Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Validation
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/form_validation.html
 */
class MY_Form_validation extends CI_Form_validation {

	protected $CI;	

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}
	
	public function valid_carplate($str)
	{
		return ( ! preg_match("/^([a-z0-9- ])+$/i", $str)) ? FALSE : TRUE;
	}
	
	public function valid_mobilephone($str)
	{		
		$cc = substr($str, 0, 2);
		
		$sgcc = substr($str, 0, 1);

		if(($cc!='01')&&($sgcc!='9'))
		{
			return false;
		}
		
		if($cc=='01')
		{		
			if(strlen(trim($str))<10)
			{
				return false;
			}
		}

		return true;	
		
	}

	

}
// END Form Validation Class

/* End of file Form_validation.php */
/* Location: ./system/libraries/Form_validation.php */
