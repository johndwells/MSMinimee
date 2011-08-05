<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// END--------

require_once PATH_THIRD . 'msminimee/config.php';
require_once PATH_THIRD . 'msminimee/helper.php';
require_once PATH_THIRD . 'minimee/pi.minimee.php';

/**
 * MSMinimee Module Front End File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		John D Wells
 * @link		http://johndwells.com
 */

class Msminimee extends Minimee {
	
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
		$this->helper = new MSMinimee_helper();
	}
	// END

}
/* End of file mod.msminimee.php */
/* Location: /system/expressionengine/third_party/msminimee/mod.msminimee.php */